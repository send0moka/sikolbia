"""
Database connection and data loading utilities for LSTM model
"""

import os
import pandas as pd
import numpy as np
import mysql.connector
from sqlalchemy import create_engine
from dotenv import load_dotenv
import logging

# Setup logging
logging.basicConfig(level=logging.INFO)
logger = logging.getLogger(__name__)

class DataLoader:
    def __init__(self):
        """Initialize database connection"""
        load_dotenv()
        
        self.db_config = {
            'host': os.getenv('DB_HOST', 'localhost'),
            'port': int(os.getenv('DB_PORT', 3306)),
            'database': os.getenv('DB_DATABASE', 'konsumsi_pangan'),
            'user': os.getenv('DB_USERNAME', 'root'),
            'password': os.getenv('DB_PASSWORD', '')
        }
        
        # Create SQLAlchemy engine for pandas
        connection_string = f"mysql+pymysql://{self.db_config['user']}:{self.db_config['password']}@{self.db_config['host']}:{self.db_config['port']}/{self.db_config['database']}"
        self.engine = create_engine(connection_string)
        
    def load_nbm_data(self):
        """
        Load NBM (Neraca Bahan Makanan) data from database
        Focus on kalori_hari for LSTM prediction
        """
        query = """
        SELECT 
            tn.tahun,
            tn.kode_kelompok,
            tn.kode_komoditi,
            k.nama as nama_kelompok,
            ko.nama as nama_komoditi,
            tn.kalori_hari,
            tn.protein_hari,
            tn.lemak_hari,
            tn.bahan_makanan,
            tn.kg_tahun,
            tn.gram_hari
        FROM transaksi_nbms tn
        LEFT JOIN kelompok k ON tn.kode_kelompok = k.kode
        LEFT JOIN komoditi ko ON tn.kode_komoditi = ko.kode_komoditi
        WHERE tn.kalori_hari IS NOT NULL
        ORDER BY tn.tahun ASC, tn.kode_kelompok, tn.kode_komoditi
        """
        
        try:
            df = pd.read_sql(query, self.engine)
            logger.info(f"Loaded NBM data: {df.shape[0]} records from {df['tahun'].min()} to {df['tahun'].max()}")
            return df
        except Exception as e:
            logger.error(f"Error loading NBM data: {e}")
            return None
    
    def aggregate_yearly_calories(self, df):
        """
        Aggregate total calories per year for national consumption prediction
        """
        if df is None:
            return None
            
        # Group by year and sum calories
        yearly_data = df.groupby('tahun').agg({
            'kalori_hari': 'sum',
            'protein_hari': 'sum', 
            'lemak_hari': 'sum',
            'bahan_makanan': 'sum'
        }).reset_index()
        
        # Calculate per capita values (assuming population data or use total)
        yearly_data['kalori_per_kapita'] = yearly_data['kalori_hari']
        yearly_data['protein_per_kapita'] = yearly_data['protein_hari']
        yearly_data['lemak_per_kapita'] = yearly_data['lemak_hari']
        
        logger.info(f"Aggregated yearly data: {yearly_data.shape[0]} years")
        return yearly_data
    
    def get_time_series_data(self):
        """
        Get time series data ready for LSTM processing
        Returns: DataFrame with year and kalori_per_kapita columns
        """
        # Load raw data
        nbm_data = self.load_nbm_data()
        if nbm_data is None:
            return None
            
        # Aggregate by year
        yearly_data = self.aggregate_yearly_calories(nbm_data)
        if yearly_data is None:
            return None
            
        # Ensure continuous years (fill missing years if any)
        min_year = yearly_data['tahun'].min()
        max_year = yearly_data['tahun'].max()
        
        all_years = pd.DataFrame({'tahun': range(min_year, max_year + 1)})
        
        # Merge and fill missing values
        complete_data = all_years.merge(yearly_data, on='tahun', how='left')
        
        # Forward fill missing values
        complete_data = complete_data.fillna(method='ffill').fillna(method='bfill')
        
        logger.info(f"Time series data ready: {complete_data.shape[0]} years from {min_year} to {max_year}")
        return complete_data
    
    def save_processed_data(self, df, filename):
        """Save processed data to CSV"""
        if df is not None:
            filepath = f"data/{filename}"
            df.to_csv(filepath, index=False)
            logger.info(f"Saved processed data to {filepath}")
        
    def test_connection(self):
        """Test database connection"""
        try:
            connection = mysql.connector.connect(**self.db_config)
            if connection.is_connected():
                cursor = connection.cursor()
                cursor.execute("SELECT COUNT(*) FROM transaksi_nbms")
                count = cursor.fetchone()[0]
                logger.info(f"Database connection successful. NBM records: {count}")
                connection.close()
                return True
        except Exception as e:
            logger.error(f"Database connection failed: {e}")
            return False

if __name__ == "__main__":
    # Test the data loader
    loader = DataLoader()
    
    # Test connection
    if loader.test_connection():
        # Load and process data
        time_series_data = loader.get_time_series_data()
        
        if time_series_data is not None:
            print("Sample data:")
            print(time_series_data.head(10))
            print(f"\nData shape: {time_series_data.shape}")
            print(f"Date range: {time_series_data['tahun'].min()} - {time_series_data['tahun'].max()}")
            
            # Save processed data
            loader.save_processed_data(time_series_data, "nbm_time_series.csv")
        else:
            print("Failed to load time series data")
    else:
        print("Database connection failed")
