
# Kalori Predictor - Deployment Package

## Files Included:
- model_lstm.keras: LSTM model
- model_xgb.pkl: XGBoost model
- model_huber.pkl: HuberRegressor model
- scaler_X.pkl: Feature scaler
- scaler_y.pkl: Target scaler
- label_encoder.pkl: Komoditi encoder
- ensemble_config.pkl: Ensemble configuration
- data_clean.csv: Clean historical data

## Usage:
```python
from kalori_predictor import KaloriPredictor

predictor = KaloriPredictor()
predictions = predictor.predict_future('0102', n_months=3)
print(predictions)
```

## Model Performance:
- MAE: 821.79
- RMSE: 1843.80
- MAPE: 3.72%

## Requirements:
- pandas
- numpy
- scikit-learn
- xgboost
- tensorflow
- scipy
