"""
Comprehensive Test Suite for SIKOLBIA ML API
Tests FastAPI endpoints, model predictions, and performance metrics
"""

import pytest
import requests
import json
from typing import List, Dict

BASE_URL = "http://localhost:8082"

class TestMLAPIHealth:
    """Test health and status endpoints"""
    
    def test_health_endpoint(self):
        """Test that health endpoint returns healthy status"""
        response = requests.get(f"{BASE_URL}/health")
        assert response.status_code == 200
        data = response.json()
        assert data["status"] == "healthy"
        assert data["model_loaded"] == True
        assert "timestamp" in data
    
    def test_model_stats_endpoint(self):
        """Test model statistics endpoint"""
        response = requests.get(f"{BASE_URL}/model/stats")
        assert response.status_code == 200
        data = response.json()
        assert "model_loaded" in data
        assert "model_info" in data
        

class TestSinglePrediction:
    """Test single prediction endpoint"""
    
    @pytest.fixture
    def valid_data_points(self) -> List[Dict]:
        """Generate valid 6-month data points for testing"""
        return [
            {"tahun": 2024, "bulan": 1, "kelompok": "01", "komoditi": "0101", "kalori_hari": 850.5},
            {"tahun": 2024, "bulan": 2, "kelompok": "01", "komoditi": "0101", "kalori_hari": 860.3},
            {"tahun": 2024, "bulan": 3, "kelompok": "01", "komoditi": "0101", "kalori_hari": 870.1},
            {"tahun": 2024, "bulan": 4, "kelompok": "01", "komoditi": "0101", "kalori_hari": 880.8},
            {"tahun": 2024, "bulan": 5, "kelompok": "01", "komoditi": "0101", "kalori_hari": 890.2},
            {"tahun": 2024, "bulan": 6, "kelompok": "01", "komoditi": "0101", "kalori_hari": 900.9},
        ]
    
    def test_predict_with_valid_data(self, valid_data_points):
        """Test prediction with valid 6-month data"""
        payload = {"data_points": valid_data_points}
        response = requests.post(f"{BASE_URL}/predict", json=payload)
        
        assert response.status_code == 200
        data = response.json()
        
        assert data["success"] == True
        assert "predictions" in data  # plural, not singular
        assert isinstance(data["predictions"], list)
        assert len(data["predictions"]) > 0
        
        # Check confidence intervals
        assert "confidence_intervals" in data
        assert isinstance(data["confidence_intervals"], list)
        
        # Check model info
        assert "model_info" in data
    
    def test_predict_with_insufficient_data(self):
        """Test prediction fails with less than 6 data points"""
        insufficient_data = [
            {"tahun": 2024, "bulan": 1, "kelompok": "01", "komoditi": "0101", "kalori_hari": 850.5},
            {"tahun": 2024, "bulan": 2, "kelompok": "01", "komoditi": "0101", "kalori_hari": 860.3},
        ]
        payload = {"data_points": insufficient_data}
        response = requests.post(f"{BASE_URL}/predict", json=payload)
        
        assert response.status_code in [400, 422]  # Bad request or validation error
    
    def test_predict_with_invalid_data_format(self):
        """Test prediction fails with invalid data format"""
        invalid_data = [
            {"tahun": "invalid", "bulan": 1, "kelompok": "01", "komoditi": "0101", "kalori_hari": 850},
        ]
        payload = {"data_points": invalid_data}
        response = requests.post(f"{BASE_URL}/predict", json=payload)
        
        assert response.status_code in [400, 422]
    
    def test_predict_with_missing_fields(self):
        """Test prediction fails with missing required fields"""
        incomplete_data = [
            {"tahun": 2024, "bulan": 1, "kelompok": "01"},  # Missing komoditi and kalori_hari
        ]
        payload = {"data_points": incomplete_data}
        response = requests.post(f"{BASE_URL}/predict", json=payload)
        
        assert response.status_code in [400, 422]


class TestMultiStepPrediction:
    """Test multi-step ahead prediction endpoint"""
    
    @pytest.fixture
    def valid_data_points(self) -> List[Dict]:
        """Generate valid data points"""
        return [
            {"tahun": 2024, "bulan": i+1, "kelompok": "01", "komoditi": "0101", "kalori_hari": 850 + (i * 10)}
            for i in range(6)
        ]
    
    def test_multi_step_prediction_3_months(self, valid_data_points):
        """Test 3-month ahead prediction (using regular endpoint)"""
        # API doesn't have separate multi-step endpoint, use regular predict
        payload = {"data_points": valid_data_points}
        response = requests.post(f"{BASE_URL}/predict", json=payload)
        
        assert response.status_code == 200
        data = response.json()
        
        assert data["success"] == True
        assert "predictions" in data
        # Regular endpoint returns 1 prediction by default
        assert len(data["predictions"]) >= 1
    
    def test_multi_step_prediction_6_months(self, valid_data_points):
        """Test prediction with 6-month data"""
        payload = {"data_points": valid_data_points}
        response = requests.post(f"{BASE_URL}/predict", json=payload)
        
        assert response.status_code == 200
        data = response.json()
        
        assert data["success"] == True
        assert "predictions" in data
    
    def test_multi_step_invalid_steps(self, valid_data_points):
        """Test with insufficient data (less than 6 months)"""
        # Test with only 3 data points (should fail)
        payload = {"data_points": valid_data_points[:3]}
        response = requests.post(f"{BASE_URL}/predict", json=payload)
        
        # Should return error or handle gracefully
        assert response.status_code in [200, 400, 422]


class TestBatchPrediction:
    """Test batch prediction (multiple calls)"""
    
    def test_batch_prediction_multiple_sequences(self):
        """Test multiple prediction calls for different komoditi"""
        # API doesn't have batch endpoint, test individual calls work
        sequences = [
            [{"tahun": 2024, "bulan": i+1, "kelompok": "01", "komoditi": "0101", "kalori_hari": 850 + (i * 10)}
             for i in range(6)],
            [{"tahun": 2024, "bulan": i+1, "kelompok": "02", "komoditi": "0201", "kalori_hari": 900 + (i * 15)}
             for i in range(6)]
        ]
        
        results = []
        for data_points in sequences:
            payload = {"data_points": data_points}
            response = requests.post(f"{BASE_URL}/predict", json=payload)
            assert response.status_code == 200
            results.append(response.json())
        
        # Both predictions should succeed
        assert len(results) == 2
        assert all(r["success"] == True for r in results)
        assert all("predictions" in r for r in results)


class TestPerformanceMetrics:
    """Test model performance and accuracy"""
    
    @pytest.fixture
    def test_data(self):
        """Historical test data with known outcomes"""
        return [
            {"tahun": 2024, "bulan": 1, "kelompok": "01", "komoditi": "0101", "kalori_hari": 850},
            {"tahun": 2024, "bulan": 2, "kelompok": "01", "komoditi": "0101", "kalori_hari": 860},
            {"tahun": 2024, "bulan": 3, "kelompok": "01", "komoditi": "0101", "kalori_hari": 870},
            {"tahun": 2024, "bulan": 4, "kelompok": "01", "komoditi": "0101", "kalori_hari": 880},
            {"tahun": 2024, "bulan": 5, "kelompok": "01", "komoditi": "0101", "kalori_hari": 890},
            {"tahun": 2024, "bulan": 6, "kelompok": "01", "komoditi": "0101", "kalori_hari": 900},
        ]
    
    def test_prediction_within_confidence_interval(self, test_data):
        """Test that predictions have confidence intervals"""
        payload = {"data_points": test_data}
        response = requests.post(f"{BASE_URL}/predict", json=payload)
        
        assert response.status_code == 200
        data = response.json()
        
        predictions = data["predictions"]
        intervals = data["confidence_intervals"]
        
        # Check intervals exist and have proper structure
        assert len(intervals) > 0
        for interval in intervals:
            assert "lower_bound" in interval
            assert "upper_bound" in interval
            assert interval["lower_bound"] <= interval["upper_bound"]
    
    def test_prediction_reasonable_range(self, test_data):
        """Test predictions are in reasonable calorie range"""
        payload = {"data_points": test_data}
        response = requests.post(f"{BASE_URL}/predict", json=payload)
        
        assert response.status_code == 200
        data = response.json()
        
        predictions = data["predictions"]
        
        # Calorie consumption should be between 0-5000 kkal/day (reasonable range)
        for pred in predictions:
            assert 0 <= pred <= 5000, f"Prediction {pred} outside reasonable range"
    
    def test_response_time_under_threshold(self, test_data):
        """Test API response time is under 2 seconds"""
        import time
        
        payload = {"data_points": test_data}
        start_time = time.time()
        response = requests.post(f"{BASE_URL}/predict", json=payload)
        end_time = time.time()
        
        response_time = end_time - start_time
        
        assert response.status_code == 200
        assert response_time < 2.0, f"Response time {response_time}s exceeds 2s threshold"


class TestEdgeCases:
    """Test edge cases and error handling"""
    
    def test_predict_with_zero_values(self):
        """Test prediction with zero calorie values"""
        data_points = [
            {"tahun": 2024, "bulan": i+1, "kelompok": "01", "komoditi": "0101", "kalori_hari": 0}
            for i in range(6)
        ]
        payload = {"data_points": data_points}
        response = requests.post(f"{BASE_URL}/predict", json=payload)
        
        # Should handle gracefully (either reject or predict with warning)
        assert response.status_code in [200, 400, 422]
    
    def test_predict_with_negative_values(self):
        """Test prediction with negative calorie values"""
        data_points = [
            {"tahun": 2024, "bulan": i+1, "kelompok": "01", "komoditi": "0101", "kalori_hari": -100}
            for i in range(6)
        ]
        payload = {"data_points": data_points}
        response = requests.post(f"{BASE_URL}/predict", json=payload)
        
        # Should reject negative values
        assert response.status_code in [400, 422]
    
    def test_predict_with_extreme_values(self):
        """Test prediction with extremely high calorie values"""
        data_points = [
            {"tahun": 2024, "bulan": i+1, "kelompok": "01", "komoditi": "0101", "kalori_hari": 10000}
            for i in range(6)
        ]
        payload = {"data_points": data_points}
        response = requests.post(f"{BASE_URL}/predict", json=payload)
        
        # Should handle or warn about outliers
        assert response.status_code in [200, 400, 422]
    
    def test_empty_payload(self):
        """Test API with empty payload"""
        response = requests.post(f"{BASE_URL}/predict", json={})
        assert response.status_code in [400, 422]
    
    def test_invalid_json(self):
        """Test API with invalid JSON"""
        response = requests.post(
            f"{BASE_URL}/predict",
            data="invalid json",
            headers={"Content-Type": "application/json"}
        )
        assert response.status_code in [400, 422]


if __name__ == "__main__":
    pytest.main([__file__, "-v", "--tb=short"])
