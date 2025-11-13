# Visual Diff - main_simple.py Changes

## File: sikolbia-ml/app/main_simple.py

---

## Change 1: Logging Level (Lines 13-19)

### BEFORE:
```python
logging.basicConfig(
    level=logging.ERROR,
    format='%(asctime)s - %(name)s - %(levelname)s - %(message)s'
)
```

### AFTER:
```python
logging.basicConfig(
    level=logging.INFO,  # Changed from ERROR to INFO
    format='%(asctime)s - %(name)s - %(levelname)s - %(message)s'
)
```

### Alasan:
Supaya bisa lihat startup logs dan konfirmasi model berhasil di-load

---

## Change 2: NEW Helper Function (Lines 90-156)

### BEFORE:
```python
# (Function tidak ada)
```

### AFTER:
```python
def predict_with_real_model(request: NBMPredictionRequest):
    """
    Use the loaded production LSTM model for prediction
    
    Args:
        request: NBMPredictionRequest with historical data points
        
    Returns:
        Tuple of (predictions, confidence_intervals)
    """
    global production_model
    
    if not production_model:
        raise ValueError("Production model not loaded")
    
    # Get historical values from request
    historical_values = [dp.kalori_hari for dp in request.data_points]
    
    if len(historical_values) < 6:
        raise ValueError("Need at least 6 historical data points")
    
    # Prepare sequence (last 6 values)
    sequence = np.array(historical_values[-6:]).reshape(1, 6, 1)
    
    # Make multi-step predictions
    predictions = []
    current_sequence = sequence.copy()
    
    for _ in range(request.n_periods):
        # Predict next value
        pred = production_model.predict(current_sequence, verbose=0)
        pred_value = float(pred[0][0])
        predictions.append(pred_value)
        
        # Update sequence for next prediction
        current_sequence = np.roll(current_sequence, -1, axis=1)
        current_sequence[0, -1, 0] = pred_value
    
    # Calculate confidence intervals (20% margin)
    confidence_intervals = []
    for pred in predictions:
        margin = abs(pred * 0.20)  # 20% margin
        confidence_intervals.append({
            "lower_bound": float(pred - margin),
            "upper_bound": float(pred + margin),
            "margin_percent": 20.0
        })
    
    return predictions, confidence_intervals
```

### Alasan:
Function untuk melakukan inference dengan real LSTM model dan menghitung confidence intervals

---

## Change 3: Modified /predict Endpoint (Lines 168-285)

### BEFORE:
```python
@app.post("/predict", response_model=NBMPredictionResponse)
async def predict_nbm(request: NBMPredictionRequest):
    """Predict NBM values for n_periods ahead"""
    try:
        # Always use trend-based prediction
        historical_values = [dp.kalori_hari for dp in request.data_points]
        
        # ... trend-based calculation ...
        
        return NBMPredictionResponse(
            success=True,
            predictions=predictions,
            # ... rest of response
        )
```

### AFTER:
```python
@app.post("/predict", response_model=NBMPredictionResponse)
async def predict_nbm(request: NBMPredictionRequest):
    """Predict NBM values for n_periods ahead"""
    global model_info, production_model
    
    try:
        # Check if production model is loaded
        if model_info and model_info.get('status') == 'loaded' and production_model:
            logger.info("Using production LSTM model for prediction")
            
            try:
                # Use real LSTM model
                predictions, confidence_intervals = predict_with_real_model(request)
                
                return NBMPredictionResponse(
                    predictions=predictions,
                    confidence_interval=ConfidenceInterval(
                        lower_bound=confidence_intervals[0]["lower_bound"],
                        upper_bound=confidence_intervals[0]["upper_bound"],
                        margin_percent=20.0,
                        interval_width=confidence_intervals[0]["upper_bound"] - 
                                     confidence_intervals[0]["lower_bound"]
                    ),
                    confidence_intervals=confidence_intervals,
                    model_version="1.0.0-production-lstm",
                    prediction_timestamp=datetime.now().isoformat(),
                    has_data=True,
                    warning=None
                )
            except Exception as e:
                logger.error(f"LSTM prediction failed: {str(e)}, falling back to trend-based")
                # Fall through to trend-based prediction
        
        # Fallback: Use trend-based prediction
        logger.info("Using fallback trend-based prediction")
        
        # ... rest of trend-based code (unchanged) ...
```

### Alasan:
Dual strategy - gunakan LSTM jika loaded, fallback ke trend-based jika gagal

---

## Change 4: NEW /model/stats Endpoint (Lines 317-320)

### BEFORE:
```python
# (Endpoint tidak ada)
```

### AFTER:
```python
@app.get("/model/stats")
async def get_model_stats():
    """Get model statistics - alias for model info"""
    return await get_model_info()
```

### Alasan:
Compatibility - beberapa clients expect `/model/stats` instead of `/model/info`

---

## Change 5: Modified startup_event() (Lines 325-380)

### BEFORE:
```python
@app.on_event("startup")
async def startup_event():
    """Initialize model on startup"""
    global production_model, model_info
    
    try:
        logger.info("Starting NBM Prediction API...")
        
        # TODO: Implement model loading when models are containerized properly
        logger.info("Model loading not implemented yet")
        model_info = {
            "version": "1.0.0-mock",
            "status": "mock",
            "reason": "Model loading not implemented"
        }
        
    except Exception as e:
        logger.error(f"Startup failed: {str(e)}")
        model_info = {
            "version": "1.0.0-mock",
            "status": "mock",
            "reason": str(e)
        }
```

### AFTER:
```python
@app.on_event("startup")
async def startup_event():
    """Initialize model on startup"""
    global production_model, model_info
    
    try:
        logger.info("Starting NBM Prediction API...")
        logger.info("Attempting to load production model...")
        
        # Construct path to model file
        model_path = os.path.join(
            os.path.dirname(__file__),
            '..',
            'models',
            'nbm_production_model.keras'
        )
        model_path = os.path.abspath(model_path)
        
        if os.path.exists(model_path):
            # Load the production model
            production_model = keras.models.load_model(model_path, compile=False)
            
            # Recompile with specific settings
            production_model.compile(
                optimizer='adam',
                loss='huber',
                metrics=['mae', 'mse']
            )
            
            # Set model info
            model_info = {
                "version": "1.0.0-production",
                "status": "loaded",
                "architecture": "LSTM Enhanced Ensemble",
                "sequence_length": 6,
                "features": 19,
                "target": "NBM Kalori/Hari",
                "last_trained": "N/A",
                "model_path": model_path
            }
            
            logger.info(f"✅ Model loaded successfully from {model_path}")
            logger.info(f"Model architecture: {model_info['architecture']}")
            
        else:
            logger.warning(f"⚠️ Model file not found: {model_path}")
            logger.warning("API will use fallback trend-based predictions")
            
            model_info = {
                "version": "1.0.0-mock",
                "status": "mock",
                "reason": "Model file not found",
                "expected_path": model_path
            }
            
    except Exception as e:
        logger.error(f"❌ Failed to load production model: {str(e)}")
        logger.error(traceback.format_exc())
        
        model_info = {
            "version": "1.0.0-mock",
            "status": "mock",
            "reason": f"Model loading failed: {str(e)}"
        }
    
    logger.info("API startup completed")
    logger.info(f"Mode: {model_info['status']}")
```

### Alasan:
Real model loading implementation dengan proper error handling dan fallback strategy

---

## Summary of Changes in main_simple.py

| Line Range | Change Type | Description |
|------------|-------------|-------------|
| 13-19 | Modified | Logging level ERROR → INFO |
| 90-156 | New | Added `predict_with_real_model()` function |
| 168-285 | Modified | Updated `/predict` to use LSTM if loaded |
| 317-320 | New | Added `/model/stats` endpoint |
| 325-380 | Modified | Implemented real model loading in startup |

**Total Changes**: 5 major modifications + 1 new endpoint + 1 new helper function

---

## File: sikolbia-app/.env

### BEFORE:
```env
NBM_API_URL=http://sikolbia-ml-api:8000
```

### AFTER:
```env
NBM_API_URL=http://localhost:8083
```

### Alasan:
For local development, FastAPI runs on port 8083 (not Docker service)

---

*Generated: 13 November 2025*
*File: VISUAL_DIFF.md*
