#!/usr/bin/env python3
"""
Test script for enhanced FastAPI ML service
"""

import requests
import json
from datetime import datetime

# API base URL - using Docker service
BASE_URL = "http://localhost:8082"

def test_health():
    """Test health endpoint"""
    print("🩺 Testing health endpoint...")
    try:
        response = requests.get(f"{BASE_URL}/health")
        if response.status_code == 200:
            data = response.json()
            print(f"✅ Health: {data['status']}")
            print(f"   Enhanced features: {data['enhanced_features']}")
            print(f"   Model version: {data['model_version']}")
            return True
        else:
            print(f"❌ Health check failed: {response.status_code}")
            return False
    except requests.exceptions.ConnectionError:
        print("❌ Cannot connect to API - is it running?")
        return False

def test_enhanced_prediction():
    """Test enhanced prediction with confidence intervals"""
    print("\n🔮 Testing enhanced prediction...")
    
    # Sample NBM data (6 months)
    sample_data = [
        {"tahun": 2024, "bulan": 1, "kelompok": "Padi-padian", "komoditi": "Beras", "kalori_hari": 45.2},
        {"tahun": 2024, "bulan": 2, "kelompok": "Padi-padian", "komoditi": "Beras", "kalori_hari": 46.1},
        {"tahun": 2024, "bulan": 3, "kelompok": "Padi-padian", "komoditi": "Beras", "kalori_hari": 44.8},
        {"tahun": 2024, "bulan": 4, "kelompok": "Umbi-umbian", "komoditi": "Ubi kayu", "kalori_hari": 12.3},
        {"tahun": 2024, "bulan": 5, "kelompok": "Padi-padian", "komoditi": "Beras", "kalori_hari": 47.0},
        {"tahun": 2024, "bulan": 6, "kelompok": "Umbi-umbian", "komoditi": "Ubi kayu", "kalori_hari": 11.9}
    ]
    
    request_data = {
        "data": sample_data,
        "confidence_level": 0.95
    }
    
    try:
        response = requests.post(f"{BASE_URL}/predict", json=request_data)
        if response.status_code == 200:
            result = response.json()
            print(f"✅ Prediction: {result['prediction']} kcal/day")
            if result.get('confidence_interval'):
                ci = result['confidence_interval']
                print(f"   Confidence (95%): {ci['lower_bound']} - {ci['upper_bound']}")
                print(f"   Interval width: {ci['interval_width']} kcal/day")
            if result.get('uncertainty_metrics'):
                um = result['uncertainty_metrics']
                print(f"   Uncertainty: {um['relative_uncertainty']:.1f}%")
                print(f"   Method: {um['method']}")
            return True
        else:
            print(f"❌ Prediction failed: {response.status_code}")
            print(response.text)
            return False
    except Exception as e:
        print(f"❌ Prediction error: {e}")
        return False

def test_multi_step_prediction():
    """Test multi-step prediction"""
    print("\n🎯 Testing multi-step prediction...")
    
    # Sample NBM data
    sample_data = [
        {"tahun": 2024, "bulan": 1, "kelompok": "Padi-padian", "komoditi": "Beras", "kalori_hari": 45.2},
        {"tahun": 2024, "bulan": 2, "kelompok": "Padi-padian", "komoditi": "Beras", "kalori_hari": 46.1},
        {"tahun": 2024, "bulan": 3, "kelompok": "Padi-padian", "komoditi": "Beras", "kalori_hari": 44.8},
        {"tahun": 2024, "bulan": 4, "kelompok": "Umbi-umbian", "komoditi": "Ubi kayu", "kalori_hari": 12.3},
        {"tahun": 2024, "bulan": 5, "kelompok": "Padi-padian", "komoditi": "Beras", "kalori_hari": 47.0},
        {"tahun": 2024, "bulan": 6, "kelompok": "Umbi-umbian", "komoditi": "Ubi kayu", "kalori_hari": 11.9}
    ]
    
    request_data = {
        "data": sample_data,
        "n_steps": 3,
        "confidence_level": 0.95
    }
    
    try:
        response = requests.post(f"{BASE_URL}/predict/multi-step", json=request_data)
        if response.status_code == 200:
            result = response.json()
            print(f"✅ Multi-step predictions ({len(result['predictions'])} months):")
            for i, (pred, month) in enumerate(zip(result['predictions'], result['forecast_months'])):
                ci = result['confidence_intervals'][i]
                print(f"   {month}: {pred} kcal/day ({ci['lower_bound']:.1f} - {ci['upper_bound']:.1f})")
            return True
        else:
            print(f"❌ Multi-step prediction failed: {response.status_code}")
            print(response.text)
            return False
    except Exception as e:
        print(f"❌ Multi-step error: {e}")
        return False

def test_model_stats():
    """Test model statistics endpoint"""
    print("\n📊 Testing model statistics...")
    
    try:
        response = requests.get(f"{BASE_URL}/model/stats")
        if response.status_code == 200:
            stats = response.json()
            print("✅ Model statistics:")
            print(f"   Model version: {stats.get('model_version', 'unknown')}")
            print(f"   API version: {stats.get('api_version', 'unknown')}")
            
            capabilities = stats.get('capabilities', {})
            print("   Capabilities:")
            for feature, available in capabilities.items():
                status = "✅" if available else "❌"
                print(f"     {status} {feature}")
            
            performance = stats.get('performance', {})
            if performance:
                print(f"   Performance: MAPE {performance.get('mape', 'N/A')}")
            
            return True
        else:
            print(f"❌ Stats failed: {response.status_code}")
            return False
    except Exception as e:
        print(f"❌ Stats error: {e}")
        return False

def main():
    """Run all tests"""
    print("🧪 ENHANCED NBM API TESTING")
    print("="*50)
    print(f"Target: {BASE_URL}")
    print(f"Time: {datetime.now().strftime('%Y-%m-%d %H:%M:%S')}")
    print()
    
    tests = [
        ("Health Check", test_health),
        ("Enhanced Prediction", test_enhanced_prediction),
        ("Multi-Step Prediction", test_multi_step_prediction),
        ("Model Statistics", test_model_stats)
    ]
    
    results = []
    for test_name, test_func in tests:
        try:
            success = test_func()
            results.append((test_name, success))
        except Exception as e:
            print(f"❌ {test_name} crashed: {e}")
            results.append((test_name, False))
    
    print("\n" + "="*50)
    print("📋 TEST SUMMARY")
    print("="*50)
    
    passed = 0
    for test_name, success in results:
        status = "✅ PASS" if success else "❌ FAIL"
        print(f"{status}: {test_name}")
        if success:
            passed += 1
    
    print(f"\nOverall: {passed}/{len(results)} tests passed")
    
    if passed == len(results):
        print("🎉 All tests passed! Enhanced API is working correctly.")
    else:
        print("⚠️  Some tests failed. Check the API service.")

if __name__ == "__main__":
    main()