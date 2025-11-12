#!/usr/bin/env python3
"""
Extract NBM Data from Database for Model Evaluation
Generates time series data for LSTM evaluation with proper train/val/test split
"""

import pandas as pd
import numpy as np
import mysql.connector
from datetime import datetime
import os

def connect_db():
    """Connect to MySQL database"""
    return mysql.connector.connect(
        host='localhost',
        port=3306,
        user='sikolbia_user',
        password='sikolbia_pass',
        database='sikolbia_db'
    )

def extract_aggregated_data():
    """Extract aggregated monthly NBM data (1993-2024)"""
    print("Connecting to database...")
    conn = connect_db()
    
    query = """
    SELECT 
        t.tahun,
        t.bulan,
        SUM(
            (t.makanan / 1000) * 
            (k.kalori_per_100g / 100) * 
            (1000000 / 365)
        ) as total_kalori_hari,
        COUNT(DISTINCT t.kode_komoditi) as jumlah_komoditi,
        AVG(t.populasi_indonesia) as populasi
    FROM transaksi_nbms t
    JOIN komoditi k ON t.kode_komoditi = k.kode_komoditi
    WHERE t.tahun >= 1993 
        AND t.tahun <= 2024
        AND t.periode_data = 'bulanan'
        AND t.makanan IS NOT NULL
        AND t.makanan > 0
    GROUP BY t.tahun, t.bulan
    ORDER BY t.tahun ASC, t.bulan ASC
    """
    
    print("Extracting NBM data...")
    df = pd.read_sql(query, conn)
    conn.close()
    
    print(f"Extracted {len(df)} monthly records")
    print(f"Period: {df['tahun'].min()}-{df['tahun'].max()}")
    print(f"Kalori range: {df['total_kalori_hari'].min():.2f} - {df['total_kalori_hari'].max():.2f}")
    
    return df

def prepare_features(df):
    """Prepare features for LSTM model"""
    print("\n Engineering features...")
    
    # Sort by date
    df = df.sort_values(['tahun', 'bulan']).reset_index(drop=True)
    
    # Create datetime - ensure proper column names
    df['year'] = df['tahun']
    df['month'] = df['bulan'] 
    df['day'] = 1
    df['date'] = pd.to_datetime(df[['year', 'month', 'day']])
    df = df.drop(['year', 'month', 'day'], axis=1)
    
    # Cyclical encoding for month
    df['month_sin'] = np.sin(2 * np.pi * df['bulan'] / 12)
    df['month_cos'] = np.cos(2 * np.pi * df['bulan'] / 12)
    
    # Rolling statistics (3, 6, 12 months)
    for window in [3, 6, 12]:
        df[f'kalori_ma{window}'] = df['total_kalori_hari'].rolling(window=window, min_periods=1).mean()
        df[f'kalori_std{window}'] = df['total_kalori_hari'].rolling(window=window, min_periods=1).std()
    
    # Lag features
    for lag in [1, 2, 3, 6, 12]:
        df[f'kalori_lag{lag}'] = df['total_kalori_hari'].shift(lag)
    
    # Fill NaN from lags with forward fill
    df = df.fillna(method='ffill').fillna(method='bfill')
    
    print(f" Features created: {df.shape[1]} columns")
    
    return df

def split_data(df, train_end='2015-12', val_end='2019-12'):
    """
    Split data chronologically:
    - Training: 1993-2015 (23 years = 276 months)
    - Validation: 2016-2019 (4 years = 48 months)
    - Testing: 2020-2024 (5 years = 60 months)
    """
    print("\n Splitting data chronologically...")
    
    df['period'] = pd.to_datetime(df['date'])
    
    train_mask = df['period'] <= train_end
    val_mask = (df['period'] > train_end) & (df['period'] <= val_end)
    test_mask = df['period'] > val_end
    
    train_df = df[train_mask].copy()
    val_df = df[val_mask].copy()
    test_df = df[test_mask].copy()
    
    print(f" Train set: {len(train_df)} months ({train_df['tahun'].min()}-{train_df['tahun'].max()})")
    print(f" Val set:   {len(val_df)} months ({val_df['tahun'].min()}-{val_df['tahun'].max()})")
    print(f" Test set:  {len(test_df)} months ({test_df['tahun'].min()}-{test_df['tahun'].max()})")
    
    # Statistics
    print(f"\n Kalori Statistics:")
    print(f"   Train mean: {train_df['total_kalori_hari'].mean():.2f}  {train_df['total_kalori_hari'].std():.2f}")
    print(f"   Val mean:   {val_df['total_kalori_hari'].mean():.2f}  {val_df['total_kalori_hari'].std():.2f}")
    print(f"   Test mean:  {test_df['total_kalori_hari'].mean():.2f}  {test_df['total_kalori_hari'].std():.2f}")
    
    return train_df, val_df, test_df

def save_datasets(train_df, val_df, test_df, full_df):
    """Save datasets to CSV"""
    print("\n Saving datasets...")
    
    data_dir = 'data'
    os.makedirs(data_dir, exist_ok=True)
    
    # Save full dataset
    full_path = os.path.join(data_dir, 'nbm_full_1993_2024.csv')
    full_df.to_csv(full_path, index=False)
    print(f" Full dataset: {full_path} ({len(full_df)} records)")
    
    # Save splits
    train_path = os.path.join(data_dir, 'nbm_train_1993_2015.csv')
    train_df.to_csv(train_path, index=False)
    print(f" Train set: {train_path} ({len(train_df)} records)")
    
    val_path = os.path.join(data_dir, 'nbm_val_2016_2019.csv')
    val_df.to_csv(val_path, index=False)
    print(f" Val set: {val_path} ({len(val_df)} records)")
    
    test_path = os.path.join(data_dir, 'nbm_test_2020_2024.csv')
    test_df.to_csv(test_path, index=False)
    print(f" Test set: {test_path} ({len(test_df)} records)")
    
    # Save metadata
    metadata = {
        'extraction_date': datetime.now().isoformat(),
        'total_records': len(full_df),
        'train_records': len(train_df),
        'val_records': len(val_df),
        'test_records': len(test_df),
        'train_period': f"{train_df['tahun'].min()}-{train_df['tahun'].max()}",
        'val_period': f"{val_df['tahun'].min()}-{val_df['tahun'].max()}",
        'test_period': f"{test_df['tahun'].min()}-{test_df['tahun'].max()}",
        'features': list(full_df.columns),
        'target_column': 'total_kalori_hari'
    }
    
    import json
    metadata_path = os.path.join(data_dir, 'dataset_metadata.json')
    with open(metadata_path, 'w') as f:
        json.dump(metadata, f, indent=2)
    print(f" Metadata: {metadata_path}")

def main():
    """Main execution"""
    print("=" * 60)
    print("NBM DATA EXTRACTION FOR MODEL EVALUATION")
    print("=" * 60)
    
    # Extract data
    df = extract_aggregated_data()
    
    # Prepare features
    df = prepare_features(df)
    
    # Split data
    train_df, val_df, test_df = split_data(df)
    
    # Save datasets
    save_datasets(train_df, val_df, test_df, df)
    
    print("\n" + "=" * 60)
    print(" DATA EXTRACTION COMPLETE!")
    print("=" * 60)
    print("\n Next steps:")
    print("   1. Review data in data/ folder")
    print("   2. Run model evaluation with: python run_evaluation.py")
    print("   3. Check results in results/ folder")

if __name__ == '__main__':
    main()
