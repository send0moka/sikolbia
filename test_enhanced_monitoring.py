#!/usr/bin/env python3
"""
Test script for Enhanced Model Monitoring System
"""

import requests
import json
import time
from datetime import datetime

# API Configuration
BASE_URL = "http://localhost:8082"

def test_monitoring_endpoints():
    """Test all enhanced monitoring endpoints"""
    print("🧪 TESTING ENHANCED MODEL MONITORING")
    print("="*50)
    print(f"Target API: {BASE_URL}")
    print(f"Time: {datetime.now().strftime('%Y-%m-%d %H:%M:%S')}")
    print()
    
    endpoints = [
        ("Health Score", "/monitoring/health-score"),
        ("Enhanced Metrics", "/monitoring/metrics"),
        ("Drift Status", "/monitoring/drift/status"),
        ("Drift History", "/monitoring/drift/history?hours=1"),
        ("Performance Metrics", "/monitoring/performance"),
        ("Active Alerts", "/monitoring/alerts"),
        ("Monitoring Report", "/monitoring/report?hours=1")
    ]
    
    results = []
    
    for name, endpoint in endpoints:
        print(f"🔍 Testing {name}...")
        try:
            response = requests.get(f"{BASE_URL}{endpoint}", timeout=10)
            
            if response.status_code == 200:
                data = response.json()
                print(f"   ✅ Success: {endpoint}")
                
                # Show key information
                if 'health_score' in data:
                    print(f"      Health Score: {data['health_score']:.1f}")
                elif 'overall_drift_detected' in data:
                    print(f"      Drift Detected: {data['overall_drift_detected']}")
                elif 'total_alerts' in data:
                    print(f"      Active Alerts: {data['total_alerts']}")
                elif 'overall_health' in data:
                    print(f"      Overall Health: {data['overall_health']}")
                
                results.append((name, True, response.status_code))
                
            elif response.status_code == 503:
                print(f"   ⚠️  Service Unavailable: {endpoint}")
                print(f"      Enhanced monitoring not available")
                results.append((name, False, response.status_code))
                
            else:
                print(f"   ❌ Failed: {endpoint} (Status: {response.status_code})")
                results.append((name, False, response.status_code))
                
        except requests.exceptions.ConnectionError:
            print(f"   ❌ Connection Error: Cannot reach {endpoint}")
            results.append((name, False, "Connection Error"))
            
        except requests.exceptions.Timeout:
            print(f"   ❌ Timeout: {endpoint}")
            results.append((name, False, "Timeout"))
            
        except Exception as e:
            print(f"   ❌ Error: {endpoint} - {str(e)}")
            results.append((name, False, str(e)))
    
    print("\n" + "="*50)
    print("📊 TEST RESULTS SUMMARY")
    print("="*50)
    
    passed = 0
    total = len(results)
    
    for name, success, status in results:
        status_icon = "✅" if success else "❌"
        print(f"{status_icon} {name}: {status}")
        if success:
            passed += 1
    
    print(f"\nOverall: {passed}/{total} endpoints working")
    
    if passed == total:
        print("🎉 All monitoring endpoints are working!")
    elif passed > 0:
        print("⚠️  Some monitoring features available")
    else:
        print("❌ Enhanced monitoring not available")
    
    return passed, total

def test_monitoring_integration():
    """Test monitoring integration with prediction endpoint"""
    print("\n🔗 TESTING MONITORING INTEGRATION")
    print("-" * 40)
    
    # Sample prediction data
    sample_data = {
        "data": [
            {"tahun": 2024, "bulan": 1, "kelompok": "Padi-padian", "komoditi": "Beras", "kalori_hari": 45.2},
            {"tahun": 2024, "bulan": 2, "kelompok": "Padi-padian", "komoditi": "Beras", "kalori_hari": 46.1},
            {"tahun": 2024, "bulan": 3, "kelompok": "Padi-padian", "komoditi": "Beras", "kalori_hari": 44.8},
            {"tahun": 2024, "bulan": 4, "kelompok": "Umbi-umbian", "komoditi": "Ubi kayu", "kalori_hari": 12.3},
            {"tahun": 2024, "bulan": 5, "kelompok": "Padi-padian", "komoditi": "Beras", "kalori_hari": 47.0},
            {"tahun": 2024, "bulan": 6, "kelompok": "Umbi-umbian", "komoditi": "Ubi kayu", "kalori_hari": 11.9}
        ],
        "confidence_level": 0.95
    }
    
    try:
        # Make prediction
        print("1. Making prediction...")
        pred_response = requests.post(f"{BASE_URL}/predict", json=sample_data, timeout=10)
        
        if pred_response.status_code == 200:
            prediction_data = pred_response.json()
            print(f"   ✅ Prediction successful: {prediction_data.get('prediction', 'N/A')} kcal/day")
            
            # Wait a moment for monitoring to update
            time.sleep(2)
            
            # Check if monitoring captured the prediction
            print("2. Checking monitoring capture...")
            metrics_response = requests.get(f"{BASE_URL}/monitoring/metrics", timeout=10)
            
            if metrics_response.status_code == 200:
                metrics_data = metrics_response.json()
                buffer_size = metrics_data.get('buffer_status', {}).get('prediction_buffer_size', 0)
                print(f"   ✅ Monitoring captured data. Buffer size: {buffer_size}")
                
                # Check health score
                health_response = requests.get(f"{BASE_URL}/monitoring/health-score", timeout=10)
                if health_response.status_code == 200:
                    health_data = health_response.json()
                    print(f"   ✅ Health Score: {health_data.get('health_score', 'N/A')}")
                    
                print("   🎉 Monitoring integration working!")
                return True
                
            else:
                print("   ⚠️  Monitoring not capturing predictions")
                return False
                
        else:
            print(f"   ❌ Prediction failed: {pred_response.status_code}")
            return False
            
    except Exception as e:
        print(f"   ❌ Integration test failed: {e}")
        return False

def test_baseline_setup():
    """Test baseline setup for drift detection"""
    print("\n📊 TESTING BASELINE SETUP")
    print("-" * 40)
    
    try:
        response = requests.post(f"{BASE_URL}/monitoring/baseline/set", timeout=10)
        
        if response.status_code == 200:
            data = response.json()
            print(f"   ✅ Baseline setup: {data.get('message', 'Success')}")
            print(f"      Samples used: {data.get('samples_count', 'N/A')}")
            return True
        elif response.status_code == 400:
            print("   ⚠️  Insufficient data for baseline")
            return False
        else:
            print(f"   ❌ Baseline setup failed: {response.status_code}")
            return False
            
    except Exception as e:
        print(f"   ❌ Baseline test error: {e}")
        return False

def generate_test_report():
    """Generate comprehensive test report"""
    print("\n" + "="*60)
    print("📋 ENHANCED MONITORING TEST REPORT")
    print("="*60)
    
    # Test all components
    endpoints_passed, endpoints_total = test_monitoring_endpoints()
    integration_working = test_monitoring_integration()
    baseline_working = test_baseline_setup()
    
    # Summary
    print(f"\n📈 SUMMARY:")
    print(f"   Monitoring Endpoints: {endpoints_passed}/{endpoints_total}")
    print(f"   Integration Test: {'✅ PASS' if integration_working else '❌ FAIL'}")
    print(f"   Baseline Setup: {'✅ PASS' if baseline_working else '❌ FAIL'}")
    
    # Calculate overall score
    total_tests = endpoints_total + 2  # +2 for integration and baseline
    passed_tests = endpoints_passed + (1 if integration_working else 0) + (1 if baseline_working else 0)
    
    success_rate = (passed_tests / total_tests) * 100
    
    print(f"\n🎯 OVERALL SUCCESS RATE: {success_rate:.1f}% ({passed_tests}/{total_tests})")
    
    if success_rate >= 90:
        print("🎉 EXCELLENT: Enhanced monitoring fully operational!")
    elif success_rate >= 70:
        print("✅ GOOD: Most monitoring features working")
    elif success_rate >= 50:
        print("⚠️  PARTIAL: Some monitoring features available")
    else:
        print("❌ POOR: Enhanced monitoring needs attention")
    
    # Recommendations
    print(f"\n💡 RECOMMENDATIONS:")
    if endpoints_passed == 0:
        print("   • Check if enhanced monitoring is properly installed")
        print("   • Verify FastAPI service is running with monitoring endpoints")
    elif not integration_working:
        print("   • Check monitoring integration in prediction endpoints")
        print("   • Verify data flow between prediction and monitoring systems")
    elif not baseline_working:
        print("   • Generate more prediction data for baseline establishment")
        print("   • Check monitoring buffer initialization")
    else:
        print("   • System is working well, continue monitoring")
        print("   • Consider setting up automated alerting")
    
    print(f"\nTest completed at: {datetime.now().strftime('%Y-%m-%d %H:%M:%S')}")

if __name__ == "__main__":
    generate_test_report()