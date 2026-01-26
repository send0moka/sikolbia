# LSTM Enhanced Ensemble Model

## Model Information
- **Model Name**: LSTM Enhanced Ensemble
- **Version**: 1.0
- **Training Date**: 2026-01-20 18:28:06
- **Performance**: MAPE 7.46%, R² 0.8830

## Model Architecture
This ensemble combines three models:
- **XGBoost** (30%): Gradient boosting for robust predictions
- **LSTM** (40%): Time series patterns and temporal dependencies  
- **HuberRegressor** (30%): Outlier-resistant linear predictions

## Files Structure
```
models/ensemble/
├── lstm_enhanced_ensemble.joblib   # Main ensemble model
├── xgboost_component.joblib        # XGBoost component
├── lstm_component.keras            # LSTM component
├── huber_component.joblib          # Huber component
├── scaler_X.joblib                 # Feature scaler
├── scaler_y_lstm.joblib            # Target scaler for LSTM
├── metadata.json                   # Training metadata
├── feature_config.json             # Feature configuration
└── README.md                       # This file
```

## Usage in FastAPI
```python
import joblib
import numpy as np

# Load model
model = joblib.load('models/ensemble/lstm_enhanced_ensemble.joblib')

# Predict single sample
prediction = model.predict_single(features)

# Predict batch
predictions = model.predict(X)
```

## Input Features (31 features)
The model requires 31 features in this exact order:
1. kalori_lag_1
2. kalori_lag_3
3. kalori_lag_6
4. kalori_lag_12
5. bahan_makanan_lag_1
6. bahan_makanan_lag_3
7. kalori_ma_3
8. kalori_ma_6
9. kalori_ma_12
10. kalori_growth_yoy
11. month_sin
12. month_cos
13. quarter_sin
14. quarter_cos
15. bahan_makanan
16. masukan
17. keluaran
18. impor
19. ekspor
20. perubahan_stok
21. import_ratio
22. export_ratio
23. price_margin
24. kalori_per_100g
25. protein_per_100g
26. lemak_per_100g
27. karbohidrat_per_100g
28. is_crisis_1998
29. is_crisis_2008
30. is_el_nino_2015
31. is_pandemic

## Performance Metrics (Test Set 2021-2024)
- **MAPE**: 7.46%
- **RMSE**: 75.97
- **MAE**: 13.31
- **R²**: 0.8830

## Retraining Workflow
1. Admin updates raw data in `transaksi_nbms` table
2. Python script processes data to generate processed dataset
3. Retrain model with new data
4. Save updated model files
5. FastAPI automatically loads new model
6. Laravel web displays updated predictions

## Notes
- Model expects scaled input (will be handled by embedded scaler)
- Predictions are clipped to non-negative values
- LSTM component uses lookback window of 6 timesteps
