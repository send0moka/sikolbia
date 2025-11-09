#!/usr/bin/env python3
"""
Test script for current Docker NBM API service
"""

import requests
import json

BASE_URL = "http://localhost:8082"

def test_current_api():
    """Test the current Docker API"""
    print("🧪 Testing Current Docker NBM API")
    print("="*40)
    
    # Test health
    try:
        response = requests.get(f"{BASE_URL}/health")
        print(f"Health: {response.status_code}")
        if response.status_code == 200:
            data = response.json()
            print(f"  Status: {data.get('status', 'unknown')}")
            print(f"  Model loaded: {data.get('model_loaded', False)}")
    except Exception as e:
        print(f"Health error: {e}")
    
    # Test prediction with original structure
    sample_data = {
        "data": [
            {"tahun": 2024, "bulan": 1, "kelompok": "Padi-padian", "komoditi": "Beras", "kalori_hari": 45.2},
            {"tahun": 2024, "bulan": 2, "kelompok": "Padi-padian", "komoditi": "Beras", "kalori_hari": 46.1},
            {"tahun": 2024, "bulan": 3, "kelompok": "Padi-padian", "komoditi": "Beras", "kalori_hari": 44.8},
            {"tahun": 2024, "bulan": 4, "kelompok": "Umbi-umbian", "komoditi": "Ubi kayu", "kalori_hari": 12.3},
            {"tahun": 2024, "bulan": 5, "kelompok": "Padi-padian", "komoditi": "Beras", "kalori_hari": 47.0},
            {"tahun": 2024, "bulan": 6, "kelompok": "Umbi-umbian", "komoditi": "Ubi kayu", "kalori_hari": 11.9}
        ]
    }
    
    try:
        response = requests.post(f"{BASE_URL}/predict", json=sample_data)
        print(f"Prediction: {response.status_code}")
        if response.status_code == 200:
            result = response.json()
            print(f"  Success: {result.get('success', False)}")
            print(f"  Prediction: {result.get('prediction', 'N/A')} kcal/day")
        elif response.status_code == 422:
            print(f"  Validation error: {response.json()}")
        else:
            print(f"  Error: {response.text}")
    except Exception as e:
        print(f"Prediction error: {e}")

if __name__ == "__main__":
    test_current_api()