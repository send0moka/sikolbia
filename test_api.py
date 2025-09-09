#!/usr/bin/env python3
"""
NBM API Test Client - untuk testing FastAPI endpoints
"""

import requests
import json
from datetime import datetime
from typing import List, Dict, Any

class NBMAPIClient:
    """Client untuk testing NBM API"""
    
    def __init__(self, base_url: str = "http://localhost:8081"):
        self.base_url = base_url.rstrip('/')
        
    def health_check(self) -> Dict[str, Any]:
        """Check API health"""
        try:
            response = requests.get(f"{self.base_url}/health")
            return response.json()
        except Exception as e:
            return {"error": str(e)}
    
    def get_model_stats(self) -> Dict[str, Any]:
        """Get model statistics"""
        try:
            response = requests.get(f"{self.base_url}/model/stats")
            return response.json()
        except Exception as e:
            return {"error": str(e)}
    
    def predict(self, data: List[Dict[str, Any]]) -> Dict[str, Any]:
        """Make prediction"""
        try:
            payload = {"data": data}
            response = requests.post(
                f"{self.base_url}/predict",
                json=payload,
                headers={"Content-Type": "application/json"}
            )
            return response.json()
        except Exception as e:
            return {"error": str(e)}
    
    def create_sample_data(self) -> List[Dict[str, Any]]:
        """Create sample 6-month data for testing"""
        sample_data = [
            {
                "tahun": 2024,
                "bulan": 1,
                "kelompok": "Padi-padian",
                "komoditi": "Beras",
                "kalori_hari": 45.5
            },
            {
                "tahun": 2024,
                "bulan": 2,
                "kelompok": "Padi-padian", 
                "komoditi": "Beras",
                "kalori_hari": 47.2
            },
            {
                "tahun": 2024,
                "bulan": 3,
                "kelompok": "Padi-padian",
                "komoditi": "Beras", 
                "kalori_hari": 46.8
            },
            {
                "tahun": 2024,
                "bulan": 4,
                "kelompok": "Padi-padian",
                "komoditi": "Beras",
                "kalori_hari": 48.1
            },
            {
                "tahun": 2024,
                "bulan": 5,
                "kelompok": "Padi-padian",
                "komoditi": "Beras",
                "kalori_hari": 47.9
            },
            {
                "tahun": 2024,
                "bulan": 6,
                "kelompok": "Padi-padian",
                "komoditi": "Beras",
                "kalori_hari": 49.3
            }
        ]
        return sample_data

def test_api():
    """Test semua API endpoints"""
    
    print("🧪 Testing NBM Prediction API")
    print("=" * 50)
    
    client = NBMAPIClient()
    
    # Test 1: Health Check
    print("\\n1. Testing Health Check...")
    health = client.health_check()
    print(f"Health Status: {health}")
    
    if health.get('model_loaded'):
        print("✅ Model loaded successfully")
    else:
        print("❌ Model not loaded")
        return
    
    # Test 2: Model Stats
    print("\\n2. Testing Model Stats...")
    stats = client.get_model_stats()
    if 'error' not in stats:
        print("✅ Model stats retrieved:")
        print(f"   MAPE: {stats['model_performance']['mape']}%")
        print(f"   Model Type: {stats['model_architecture']['type']}")
        print(f"   Training Records: {stats['training_data_info']['records']}")
    else:
        print(f"❌ Error getting stats: {stats['error']}")
    
    # Test 3: Prediction
    print("\\n3. Testing Prediction...")
    sample_data = client.create_sample_data()
    
    print("Sample input data:")
    for i, data_point in enumerate(sample_data):
        print(f"   {i+1}. {data_point['tahun']}-{data_point['bulan']:02d}: {data_point['kalori_hari']} kcal/day")
    
    prediction_result = client.predict(sample_data)
    
    if 'error' not in prediction_result:
        print("\\n✅ Prediction successful:")
        print(f"   Predicted calories: {prediction_result['prediction']} kcal/day")
        
        if 'confidence_interval' in prediction_result:
            ci = prediction_result['confidence_interval']
            print(f"   Confidence interval: [{ci['lower_bound']:.2f}, {ci['upper_bound']:.2f}]")
            print(f"   Margin: ±{ci['margin_percent']:.1f}%")
        
        print(f"   Model info: {prediction_result['model_info']['model_type']}")
        print(f"   Accuracy: {prediction_result['model_info']['accuracy']}")
        
    else:
        print(f"❌ Prediction error: {prediction_result['error']}")
    
    print("\\n🎯 API Testing Complete!")

if __name__ == "__main__":
    test_api()
