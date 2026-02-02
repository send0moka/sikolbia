"""
NBM Kalori Predictor - LSTM Enhanced Ensemble
Based on Google Colab trained model

Author: Jehian Athaya Tsani Az Zuhry (H1D022006)
Model Performance: MAE=842.43, RMSE=1778.98, MAPE=3.74%

Strategy:
- Small values (<5000): Use XGBoost
- Large values (≥5000): Use Weighted Ensemble (LSTM 90%, XGBoost 5%, Huber 5%)
"""

import numpy as np
import pandas as pd
import pickle
import mysql.connector
from pathlib import Path
from typing import Dict, List, Optional
from datetime import datetime, timedelta
import logging
import os

logging.basicConfig(level=logging.INFO)
logger = logging.getLogger(__name__)


class KaloriPredictor:
    """
    Predictor untuk konsumsi kalori harian menggunakan LSTM Enhanced Ensemble
    """
    
    def __init__(self, models_dir: str = None):
        # Auto-detect models directory (Docker vs local)
        if models_dir is None:
            docker_path = Path("/app/ml_models/models/nbm_google_colab")
            local_path = Path(__file__).parent.parent.parent / "ml_models" / "models" / "nbm_google_colab"
            
            if docker_path.exists():
                models_dir = docker_path
            elif local_path.exists():
                models_dir = local_path
            else:
                raise FileNotFoundError(
                    f"Models not found in Docker ({docker_path}) or local ({local_path})"
                )
        
        self.models_dir = Path(models_dir)
        self.threshold = 5000  # Threshold untuk switching model
        
        logger.info(f"Using models from: {self.models_dir}")
        
        # DB Config - auto-detect Docker vs local
        is_docker = docker_path.exists() if 'docker_path' in locals() else Path("/app").exists()
        default_host = 'sikolbia-mysql' if is_docker else 'localhost'
        
        self.db_config = {
            'host': os.getenv('DB_HOST', default_host),
            'port': int(os.getenv('DB_PORT', 3306)),
            'user': os.getenv('DB_USERNAME', 'sikolbia_user'),
            'password': os.getenv('DB_PASSWORD', 'sikolbia_pass'),
            'database': os.getenv('DB_DATABASE', 'sikolbia_db')
        }
        
        logger.info(f"Database host: {self.db_config['host']}")
        
        # Load models dan artifacts
        self._load_artifacts()
        
        logger.info("✓ KaloriPredictor initialized")
    
    def _load_artifacts(self):
        """Load semua model dan preprocessing artifacts"""
        try:
            # TensorFlow/Keras
            from tensorflow import keras
            self.model_lstm = keras.models.load_model(
                self.models_dir / "model_lstm.keras"
            )
            logger.info("✓ LSTM model loaded")
            
            # XGBoost
            with open(self.models_dir / "model_xgb.pkl", 'rb') as f:
                self.model_xgb = pickle.load(f)
            logger.info("✓ XGBoost model loaded")
            
            # HuberRegressor
            with open(self.models_dir / "model_huber.pkl", 'rb') as f:
                self.model_huber = pickle.load(f)
            logger.info("✓ HuberRegressor model loaded")
            
            # Scalers
            with open(self.models_dir / "scaler_X.pkl", 'rb') as f:
                self.scaler_X = pickle.load(f)
            with open(self.models_dir / "scaler_y.pkl", 'rb') as f:
                self.scaler_y = pickle.load(f)
            logger.info("✓ Scalers loaded")
            
            # Label Encoder
            with open(self.models_dir / "label_encoder.pkl", 'rb') as f:
                self.label_encoder = pickle.load(f)
            logger.info("✓ Label encoder loaded")
            
            # Ensemble Config
            with open(self.models_dir / "ensemble_config.pkl", 'rb') as f:
                config = pickle.load(f)
                # Handle different key names (weights vs optimal_weights)
                if 'weights' in config:
                    weights_array = config['weights']
                elif 'optimal_weights' in config:
                    weights_array = config['optimal_weights']
                else:
                    weights_array = [0.9, 0.05, 0.05]  # Default
                
                # Convert to dict
                self.ensemble_weights = {
                    'lstm': weights_array[0],
                    'xgboost': weights_array[1],
                    'huber': weights_array[2]
                }
                self.threshold = config.get('threshold', 5000)
            logger.info(f"✓ Ensemble config loaded (threshold={self.threshold})")
            
        except Exception as e:
            logger.error(f"✗ Error loading artifacts: {e}")
            raise
    
    def _get_db_connection(self):
        """Get MySQL connection"""
        return mysql.connector.connect(**self.db_config)
    
    def predict_future(
        self, 
        kode_komoditi: str, 
        n_months: int = 6,
        return_confidence: bool = True
    ) -> Dict:
        """
        Prediksi konsumsi kalori untuk n bulan ke depan
        
        Args:
            kode_komoditi: Kode komoditi (e.g., "0102" untuk Beras)
            n_months: Jumlah bulan prediksi (default 6)
            return_confidence: Return confidence interval atau tidak
            
        Returns:
            Dict dengan predictions dan metadata
        """
        try:
            # Validate if komoditi exists in model
            if kode_komoditi not in self.label_encoder.classes_:
                # Check if komoditi exists in database
                conn = self._get_db_connection()
                check_query = """
                SELECT k.nama, COUNT(t.id) as data_count
                FROM komoditi k
                LEFT JOIN transaksi_nbms t ON k.kode_kelompok = t.kode_kelompok 
                    AND k.kode_komoditi = t.kode_komoditi
                    AND t.periode_data = 'bulanan'
                    AND t.bahan_makanan > 0
                WHERE k.kode_komoditi = %s
                GROUP BY k.nama
                """
                df_check = pd.read_sql(check_query, conn, params=(kode_komoditi,))
                conn.close()
                
                if len(df_check) == 0:
                    raise ValueError(f"Komoditi dengan kode {kode_komoditi} tidak ditemukan dalam database")
                elif df_check['data_count'].iloc[0] == 0:
                    nama = df_check['nama'].iloc[0]
                    raise ValueError(f"Data bahan makanan untuk komoditi {nama} tidak tersedia. Prediksi tidak dapat dilakukan karena tidak ada data historis.")
                else:
                    nama = df_check['nama'].iloc[0]
                    raise ValueError(f"Komoditi {nama} tidak tersedia dalam model prediksi. Model belum dilatih dengan data komoditi ini.")
            
            if n_months < 1 or n_months > 24:
                raise ValueError("n_months harus antara 1-24")
            
            # Get historical data from database
            conn = self._get_db_connection()
            df_komoditi = self._get_historical_data(conn, kode_komoditi)
            conn.close()
            
            if len(df_komoditi) < 6:
                # Get komoditi name for better error message
                conn = self._get_db_connection()
                name_query = "SELECT nama FROM komoditi WHERE kode_komoditi = %s LIMIT 1"
                df_name = pd.read_sql(name_query, conn, params=(kode_komoditi,))
                conn.close()
                nama = df_name['nama'].iloc[0] if len(df_name) > 0 else kode_komoditi
                raise ValueError(f"Data historis komoditi {nama} tidak mencukupi untuk prediksi. Dibutuhkan minimal 6 bulan data, tersedia: {len(df_komoditi)} bulan.")
            
            # Get komoditi info
            komoditi_info = {
                'kode': kode_komoditi,
                'nama': df_komoditi['nama'].iloc[0],
                'last_date': df_komoditi['date'].max().strftime('%Y-%m-%d'),
                'data_points': len(df_komoditi)
            }
            
            # Prepare sequence (last 6 months)
            last_sequence = df_komoditi.tail(6).copy()
            
            # Predict iteratively
            predictions = []
            current_date = df_komoditi['date'].max()
            
            for i in range(n_months):
                # Prepare features
                X = self._prepare_features(last_sequence, kode_komoditi)
                
                # Get predictions from all models
                pred_lstm = self._predict_lstm(X)
                pred_xgb = self._predict_xgb(X)
                pred_huber = self._predict_huber(X)
                
                # DEBUG: Log predictions
                logger.info(f"  Month {i+1} - LSTM: {pred_lstm:.4f}, XGBoost: {pred_xgb:.4f}, Huber: {pred_huber:.4f}")
                
                # Ensemble decision (returns bahan_makanan in ribu ton)
                if pred_lstm < self.threshold:
                    # Use XGBoost for small values, fallback to Huber if XGBoost returns 0
                    if pred_xgb > 0:
                        final_pred_bahan = pred_xgb
                        method = "XGBoost"
                    else:
                        final_pred_bahan = pred_huber
                        method = "Huber"
                else:
                    final_pred_bahan = (
                        self.ensemble_weights['lstm'] * pred_lstm +
                        self.ensemble_weights['xgboost'] * pred_xgb +
                        self.ensemble_weights['huber'] * pred_huber
                    )
                    method = "LSTM_Ensemble"
                
                # Convert bahan_makanan (ribu ton) to kalori_hari
                # Get kalori_per_100g and populasi from last known data
                kalori_per_100g = last_sequence['kalori_per_100g'].iloc[-1]
                populasi = last_sequence['populasi_indonesia'].iloc[-1]
                
                # Next month
                current_date = current_date + timedelta(days=32)
                current_date = current_date.replace(day=1)
                
                days_in_year = 366 if current_date.year % 4 == 0 and (
                    current_date.year % 100 != 0 or current_date.year % 400 == 0
                ) else 365
                
                kalori_hari = (
                    final_pred_bahan * 1e9 *  # ribu ton → grams
                    kalori_per_100g / 100 /
                    populasi / 
                    days_in_year
                )
                
                predictions.append({
                    'date': current_date.strftime('%Y-%m-%d'),
                    'tahun': current_date.year,
                    'bulan': current_date.month,
                    'bahan_makanan': round(float(final_pred_bahan), 2),
                    'kalori_hari': round(float(kalori_hari), 2),
                    'method': method
                })
                
                # Update sequence for next iteration
                last_sequence = self._update_sequence(
                    last_sequence, 
                    current_date, 
                    final_pred_bahan
                )
            
            result = {
                'success': True,
                'komoditi_info': komoditi_info,
                'predictions': predictions,
                'model_info': {
                    'type': 'LSTM Enhanced Ensemble',
                    'mae': 867.04,
                    'rmse': 1788.78,
                    'mape': 3.73,
                    'r2': 0.9901,
                    'threshold': self.threshold,
                    'weights': self.ensemble_weights
                }
            }
            
            if return_confidence:
                result['confidence_intervals'] = self._calculate_confidence(predictions)
            
            logger.info(f"✓ Predicted {n_months} months for {kode_komoditi}")
            return result
            
        except ValueError as e:
            error_msg = str(e) or "Invalid input or insufficient data"
            logger.error(f"✗ Validation error for {kode_komoditi}: {error_msg}")
            return {
                'success': False,
                'error': error_msg,
                'kode_komoditi': kode_komoditi
            }
        except Exception as e:
            error_msg = str(e) or "Unknown prediction error"
            logger.error(f"✗ Prediction exception for {kode_komoditi}: {error_msg}", exc_info=True)
            return {
                'success': False,
                'error': error_msg,
                'kode_komoditi': kode_komoditi
            }
    
    def _get_historical_data(self, conn, kode_komoditi: str) -> pd.DataFrame:
        """Get historical data dari database"""
        query = """
        SELECT 
            t.tahun, t.bulan, t.kode_komoditi,
            t.bahan_makanan, t.populasi_indonesia, t.harga_konsumen,
            t.curah_hujan_mm, t.suhu_rata_celsius,
            k.nama, k.kalori_per_100g
        FROM transaksi_nbms t
        LEFT JOIN komoditi k ON t.kode_kelompok = k.kode_kelompok 
            AND t.kode_komoditi = k.kode_komoditi
        WHERE t.periode_data = 'bulanan'
            AND t.kode_komoditi = %s
            AND t.bahan_makanan > 0
        ORDER BY t.tahun DESC, t.bulan DESC
        LIMIT 12
        """
        
        df = pd.read_sql(query, conn, params=(kode_komoditi,))
        
        # Create date column with proper datetime format
        df['date'] = pd.to_datetime(
            df['tahun'].astype(str) + '-' + 
            df['bulan'].astype(str).str.zfill(2) + '-01'
        )
        
        # Sort ascending by date (because query is DESC, need to reverse)
        df = df.sort_values('date').reset_index(drop=True)
        
        # Calculate kalori per kapita per hari
        df['kalori_hari'] = (
            df['bahan_makanan'] * 1e9 *  # ribu ton → grams
            df['kalori_per_100g'] / 100 /
            df['populasi_indonesia'] / 
            df['tahun'].apply(lambda y: 366 if y % 4 == 0 and (y % 100 != 0 or y % 400 == 0) else 365)
        ).fillna(0)
        
        # Fill missing values with reasonable defaults
        df['harga_konsumen'] = df['harga_konsumen'].fillna(df['harga_konsumen'].mean() if df['harga_konsumen'].notna().any() else 0)
        df['curah_hujan_mm'] = df['curah_hujan_mm'].fillna(df['curah_hujan_mm'].mean() if df['curah_hujan_mm'].notna().any() else 200)  # Indonesia average
        df['suhu_rata_celsius'] = df['suhu_rata_celsius'].fillna(df['suhu_rata_celsius'].mean() if df['suhu_rata_celsius'].notna().any() else 27)  # Indonesia average
        
        # Feature engineering
        df = self._engineer_features(df)
        
        # Return last 6 rows (now with proper lags calculated)
        return df.tail(6)
    
    def _engineer_features(self, df: pd.DataFrame) -> pd.DataFrame:
        """Create lag, rolling, and cyclical features - match Google Colab training"""
        # Encode komoditi
        df['komoditi_encoded'] = self.label_encoder.transform(df['kode_komoditi'])
        
        # Lag features (using bahan_makanan column name like in training)
        df['bahan_makanan_lag_1'] = df['bahan_makanan'].shift(1).fillna(0)
        df['bahan_makanan_lag_2'] = df['bahan_makanan'].shift(2).fillna(0)
        df['bahan_makanan_lag_3'] = df['bahan_makanan'].shift(3).fillna(0)
        
        # Rolling features
        df['bahan_makanan_roll_mean_3'] = df['bahan_makanan'].rolling(3, min_periods=1).mean()
        df['bahan_makanan_roll_std_3'] = df['bahan_makanan'].rolling(3, min_periods=1).std().fillna(0)
        df['bahan_makanan_roll_mean_6'] = df['bahan_makanan'].rolling(6, min_periods=1).mean()
        df['bahan_makanan_roll_std_6'] = df['bahan_makanan'].rolling(6, min_periods=1).std().fillna(0)
        
        # Cyclical features
        df['month_sin'] = np.sin(2 * np.pi * df['bulan'] / 12)
        df['month_cos'] = np.cos(2 * np.pi * df['bulan'] / 12)
        
        # Quarter
        df['quarter'] = df['date'].dt.quarter
        
        return df
    
    def _prepare_features(self, sequence_df: pd.DataFrame, kode_komoditi: str) -> np.ndarray:
        """Prepare features untuk prediction - match Google Colab feature columns
        Returns: scaled features for LAST row only, shape (17,)
        """
        feature_cols = [
            'komoditi_encoded', 'tahun', 'bulan', 'month_sin', 'month_cos', 'quarter',
            'bahan_makanan_lag_1', 'bahan_makanan_lag_2', 'bahan_makanan_lag_3',
            'bahan_makanan_roll_mean_3', 'bahan_makanan_roll_std_3',
            'bahan_makanan_roll_mean_6', 'bahan_makanan_roll_std_6',
            'populasi_indonesia', 'harga_konsumen',
            'curah_hujan_mm', 'suhu_rata_celsius'
        ]
        
        # Get LAST row only (most recent data for prediction)
        X = sequence_df[feature_cols].iloc[-1:].values  # shape (1, 17)
        X_scaled = self.scaler_X.transform(X)[0]  # shape (17,)
        
        return X_scaled
    
    def _predict_lstm(self, X: np.ndarray) -> float:
        """Predict menggunakan LSTM - expects shape (1, 1, 17)"""
        # X is already scaled, shape (17,) for single prediction
        X_lstm = X.reshape(1, 1, -1)  # reshape to (1, 1, 17)
        pred_scaled = self.model_lstm.predict(X_lstm, verbose=0)[0, 0]
        pred = self.scaler_y.inverse_transform([[pred_scaled]])[0, 0]
        return max(0, float(pred))
    
    def _predict_xgb(self, X: np.ndarray) -> float:
        """Predict menggunakan XGBoost - expects shape (1, 17)
        XGBoost and Huber were trained on original scale (not scaled y)
        """
        X_flat = X.reshape(1, -1)  # reshape to (1, 17)
        pred = self.model_xgb.predict(X_flat)[0]
        return max(0, float(pred))
    
    def _predict_huber(self, X: np.ndarray) -> float:
        """Predict menggunakan HuberRegressor - expects shape (1, 17)
        XGBoost and Huber were trained on original scale (not scaled y)
        """
        X_flat = X.reshape(1, -1)  # reshape to (1, 17)
        pred = self.model_huber.predict(X_flat)[0]
        return max(0, float(pred))
    
    def _update_sequence(
        self, 
        sequence_df: pd.DataFrame, 
        new_date: datetime,
        new_bahan_makanan: float
    ) -> pd.DataFrame:
        """Update sequence dengan prediksi baru"""
        new_row = sequence_df.iloc[-1].copy()
        
        new_row['date'] = new_date
        new_row['tahun'] = new_date.year
        new_row['bulan'] = new_date.month
        new_row['bahan_makanan'] = new_bahan_makanan
        
        # Update lag features (using bahan_makanan)
        new_row['bahan_makanan_lag_1'] = sequence_df['bahan_makanan'].iloc[-1]
        new_row['bahan_makanan_lag_2'] = sequence_df['bahan_makanan_lag_1'].iloc[-1]
        new_row['bahan_makanan_lag_3'] = sequence_df['bahan_makanan_lag_2'].iloc[-1]
        
        # Update rolling features (3-month window)
        recent_values_3 = [
            sequence_df['bahan_makanan'].iloc[-2],
            sequence_df['bahan_makanan'].iloc[-1],
            new_bahan_makanan
        ]
        new_row['bahan_makanan_roll_mean_3'] = np.mean(recent_values_3)
        new_row['bahan_makanan_roll_std_3'] = np.std(recent_values_3)
        
        # Update rolling features (6-month window)
        recent_values_6 = list(sequence_df['bahan_makanan'].iloc[-5:]) + [new_bahan_makanan]
        new_row['bahan_makanan_roll_mean_6'] = np.mean(recent_values_6)
        new_row['bahan_makanan_roll_std_6'] = np.std(recent_values_6)
        
        # Update cyclical features
        new_row['month_sin'] = np.sin(2 * np.pi * new_date.month / 12)
        new_row['month_cos'] = np.cos(2 * np.pi * new_date.month / 12)
        
        # Update quarter
        new_row['quarter'] = (new_date.month - 1) // 3 + 1
        
        # For exogenous variables (populasi, harga, curah_hujan, suhu):
        # Use last known values (simple carry forward)
        # In production, you might want to fetch forecasted values
        new_row['populasi_indonesia'] = sequence_df['populasi_indonesia'].iloc[-1]
        new_row['harga_konsumen'] = sequence_df['harga_konsumen'].iloc[-1]
        new_row['curah_hujan_mm'] = sequence_df['curah_hujan_mm'].iloc[-1]
        new_row['suhu_rata_celsius'] = sequence_df['suhu_rata_celsius'].iloc[-1]
        
        # Append dan ambil last 6
        updated = pd.concat([sequence_df, pd.DataFrame([new_row])], ignore_index=True)
        return updated.tail(6)
    
    def _calculate_confidence(self, predictions: List[Dict]) -> List[Dict]:
        """Calculate confidence intervals (simplified)"""
        intervals = []
        for pred in predictions:
            kalori = pred['kalori_hari']
            intervals.append({
                'date': pred['date'],
                'lower': round(kalori * 0.9, 2),
                'upper': round(kalori * 1.1, 2)
            })
        return intervals
    
    def get_komoditi_list(self) -> List[Dict]:
        """Get daftar semua komoditi yang tersedia"""
        conn = self._get_db_connection()
        
        query = """
        SELECT DISTINCT 
            k.kode_komoditi,
            k.nama,
            MIN(t.tahun) as min_year,
            MAX(t.tahun) as max_year,
            COUNT(*) as data_points
        FROM komoditi k
        LEFT JOIN transaksi_nbms t ON k.kode_komoditi = t.kode_komoditi
        WHERE t.periode_data = 'bulanan'
        GROUP BY k.kode_komoditi, k.nama
        ORDER BY k.kode_komoditi
        """
        
        df = pd.read_sql(query, conn)
        conn.close()
        
        return df.to_dict('records')
    
    def get_historical_data_api(
        self, 
        kode_komoditi: str, 
        months: int = 12
    ) -> Dict:
        """Get historical data untuk visualisasi API"""
        try:
            conn = self._get_db_connection()
            df = self._get_historical_data(conn, kode_komoditi)
            conn.close()
            
            df = df.tail(months)
            
            if len(df) == 0:
                return {'success': False, 'error': 'Komoditi not found'}
            
            return {
                'success': True,
                'komoditi_info': {
                    'kode': kode_komoditi,
                    'nama': df['nama'].iloc[0]
                },
                'data': df[['date', 'kalori_hari']].to_dict('records')
            }
        except Exception as e:
            return {'success': False, 'error': str(e)}
