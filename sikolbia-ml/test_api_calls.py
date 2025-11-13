"""
Test FastAPI NBM Prediction API
"""
import requests
import json

def test_api():
    base_url = "http://localhost:8083"  # Using port 8083 for updated server
    
    print("=" * 60)
    print("TESTING FASTAPI NBM PREDICTION API")
    print("=" * 60)
    print()
    
    # Test 1: Health check
    print("📊 Test 1: Health Check")
    try:
        response = requests.get(f"{base_url}/health")
        print(f"Status Code: {response.status_code}")
        if response.status_code == 200:
            data = response.json()
            print(json.dumps(data, indent=2))
            print("✅ Health check PASSED")
        else:
            print(f"❌ Health check FAILED: {response.text}")
    except Exception as e:
        print(f"❌ Health check ERROR: {str(e)}")
    print()
    
    # Test 2: Model info
    print("📊 Test 2: Model Info")
    try:
        response = requests.get(f"{base_url}/model/stats")
        print(f"Status Code: {response.status_code}")
        if response.status_code == 200:
            data = response.json()
            print(json.dumps(data, indent=2))
            print("✅ Model info PASSED")
        else:
            print(f"❌ Model info FAILED: {response.text}")
    except Exception as e:
        print(f"❌ Model info ERROR: {str(e)}")
    print()
    
    # Test 3: Prediction with sample data
    print("📊 Test 3: NBM Prediction")
    # Use proper kode format: kelompok=2 char (e.g. "01"), komoditi=4 char (e.g. "0101")
    payload = {
        "data_points": [
            {"tahun": 2024, "bulan": 1, "kelompok": "01", "komoditi": "0101", "kalori_hari": 500000},
            {"tahun": 2024, "bulan": 2, "kelompok": "01", "komoditi": "0101", "kalori_hari": 520000},
            {"tahun": 2024, "bulan": 3, "kelompok": "01", "komoditi": "0101", "kalori_hari": 510000},
            {"tahun": 2024, "bulan": 4, "kelompok": "01", "komoditi": "0101", "kalori_hari": 530000},
            {"tahun": 2024, "bulan": 5, "kelompok": "01", "komoditi": "0101", "kalori_hari": 540000},
            {"tahun": 2024, "bulan": 6, "kelompok": "01", "komoditi": "0101", "kalori_hari": 550000}
        ],
        "n_periods": 3
    }
    
    try:
        response = requests.post(f"{base_url}/predict", json=payload)
        print(f"Status Code: {response.status_code}")
        if response.status_code == 200:
            data = response.json()
            print(json.dumps(data, indent=2))
            print("✅ Prediction PASSED")
            print(f"\n📈 Predictions: {data['predictions']}")
            print(f"📊 Model Version: {data['model_version']}")
        else:
            print(f"❌ Prediction FAILED: {response.text}")
    except Exception as e:
        print(f"❌ Prediction ERROR: {str(e)}")
    print()
    
    print("=" * 60)
    print("✅ API TESTS COMPLETED")
    print("=" * 60)

if __name__ == "__main__":
    test_api()
