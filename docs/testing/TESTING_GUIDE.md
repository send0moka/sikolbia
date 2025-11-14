# 🧪 SIKOLBIA Testing Guide

Panduan lengkap untuk menjalankan testing pada sistem SIKOLBIA (Laravel + FastAPI ML).

## 📋 Table of Contents

1. [Testing Overview](#testing-overview)
2. [Prerequisites](#prerequisites)
3. [Laravel Testing](#laravel-testing)
4. [ML API Testing](#ml-api-testing)
5. [Integration Testing](#integration-testing)
6. [Performance Testing](#performance-testing)
7. [Test Coverage](#test-coverage)
8. [CI/CD Integration](#cicd-integration)

---

## 1️⃣ Testing Overview

### Test Pyramid Structure

```
         /\
        /E2E\           ← Integration Tests (10%)
       /------\
      /  API   \        ← Feature Tests (30%)
     /----------\
    /    Unit    \      ← Unit Tests (60%)
   /--------------\
```

### Test Suites

| Test Suite | Files | Purpose |
|------------|-------|---------|
| **Unit Tests** | `tests/Unit/` | Test individual classes/methods |
| **Feature Tests** | `tests/Feature/` | Test API endpoints & workflows |
| **ML API Tests** | `tests/test_ml_api.py` | Test FastAPI ML service |
| **Integration Tests** | `tests/Integration/` | Test Laravel ↔ FastAPI integration |

---

## 2️⃣ Prerequisites

### System Requirements

```bash
# Laravel Testing
- PHP 8.3+
- PHPUnit 10.x
- Pest PHP (optional)
- SQLite (for testing database)

# ML API Testing
- Python 3.10+
- pytest
- requests library
```

### Installation

**Laravel Dependencies:**
```bash
cd sikolbia-app
composer install
composer require --dev phpunit/phpunit
composer require --dev pestphp/pest --with-all-dependencies
```

**Python Dependencies:**
```bash
cd sikolbia-ml
pip install pytest requests
```

---

## 3️⃣ Laravel Testing

### Setup Testing Environment

**1. Configure `.env.testing`:**
```bash
cd sikolbia-app
cp .env .env.testing
```

**Edit `.env.testing`:**
```env
APP_ENV=testing
APP_DEBUG=true
DB_CONNECTION=sqlite
DB_DATABASE=:memory:

CACHE_DRIVER=array
SESSION_DRIVER=array
QUEUE_CONNECTION=sync

NBM_API_URL=http://localhost:8082
ML_API_URL=http://localhost:8082
```

**2. Run Migrations for Testing:**
```bash
php artisan config:clear
php artisan migrate --env=testing
```

### Running Laravel Tests

**Run All Tests:**
```bash
php artisan test
```

**Run Specific Test Suite:**
```bash
# Prediksi NBM tests only
php artisan test --filter=PrediksiNbmTest

# Insight Service tests only
php artisan test --filter=PredictionInsightServiceTest
```

**Run with Coverage:**
```bash
php artisan test --coverage
```

**Run with Pest (if installed):**
```bash
./vendor/bin/pest
./vendor/bin/pest --filter=PrediksiNbm
```

### Test Structure

**Example Test File: `tests/Feature/PrediksiNbmTest.php`**

Tests included:
- ✅ Page access authentication
- ✅ Komoditi API endpoint
- ✅ Prediction with valid data
- ✅ Validation errors
- ✅ Save to history
- ✅ Bookmark functionality
- ✅ Delete functionality
- ✅ AI insights generation
- ✅ Excel/PDF export
- ✅ User data isolation

**Example Test File: `tests/Feature/PredictionInsightServiceTest.php`**

Tests included:
- ✅ Trend analysis (increasing/decreasing/stable)
- ✅ Volatility calculation
- ✅ Risk level assessment
- ✅ Anomaly detection
- ✅ Recommendations generation
- ✅ Summary generation
- ✅ Edge cases handling

### Expected Output

```
PASS  Tests\Feature\PrediksiNbmTest
  ✓ authenticated pemerintah can access prediksi nbm page
  ✓ unauthenticated user cannot access prediksi nbm
  ✓ can fetch komoditi by kelompok
  ✓ can run prediction with valid data
  ✓ prediction requires valid kelompok and komoditi
  ✓ prediction requires bulan between 1 and 12
  ✓ can save prediction to history
  ✓ can view prediction history
  ✓ can toggle bookmark on prediction
  ✓ can delete prediction from history
  ✓ can generate ai insights
  ✓ can export prediction to excel
  ✓ can export prediction to pdf
  ✓ user can only see their own prediction history

PASS  Tests\Feature\PredictionInsightServiceTest
  ✓ can analyze increasing trend
  ✓ can analyze decreasing trend
  ✓ can calculate volatility
  ✓ can assess low risk level
  ✓ can assess high risk level
  ✓ can detect anomalies
  ✓ can generate recommendations
  ✓ can generate summary
  ✓ handles empty historical data gracefully
  ✓ handles single prediction value

Tests:    24 passed (156 assertions)
Duration: 3.45s
```

---

## 4️⃣ ML API Testing

### Setup ML Testing Environment

**1. Ensure ML API is Running:**
```bash
# Check health
curl http://localhost:8082/health

# Expected: {"status":"healthy","model_loaded":true,...}
```

**2. Install Python Test Dependencies:**
```bash
cd sikolbia-ml
pip install pytest requests
```

### Running ML API Tests

**Run All Tests:**
```bash
cd sikolbia-ml
pytest tests/test_ml_api.py -v
```

**Run Specific Test Class:**
```bash
# Health tests only
pytest tests/test_ml_api.py::TestMLAPIHealth -v

# Prediction tests only
pytest tests/test_ml_api.py::TestSinglePrediction -v

# Performance tests only
pytest tests/test_ml_api.py::TestPerformanceMetrics -v
```

**Run with Coverage:**
```bash
pytest tests/test_ml_api.py --cov=fastapi --cov-report=html
```

**Run with Detailed Output:**
```bash
pytest tests/test_ml_api.py -vv --tb=short
```

### Test Structure

**Test Classes:**

1. **TestMLAPIHealth** (2 tests)
   - Health endpoint status
   - Model statistics endpoint

2. **TestSinglePrediction** (4 tests)
   - Valid 6-month data prediction
   - Insufficient data handling
   - Invalid data format handling
   - Missing fields validation

3. **TestMultiStepPrediction** (3 tests)
   - 3-month ahead prediction
   - 6-month ahead prediction
   - Invalid steps_ahead validation

4. **TestBatchPrediction** (1 test)
   - Multiple sequences prediction

5. **TestPerformanceMetrics** (3 tests)
   - Confidence interval validation
   - Reasonable range checking
   - Response time < 2 seconds

6. **TestEdgeCases** (6 tests)
   - Zero values handling
   - Negative values rejection
   - Extreme values handling
   - Empty payload rejection
   - Invalid JSON rejection

### Expected Output

```
============================= test session starts ==============================
collected 19 items

tests/test_ml_api.py::TestMLAPIHealth::test_health_endpoint PASSED        [  5%]
tests/test_ml_api.py::TestMLAPIHealth::test_model_stats_endpoint PASSED   [ 10%]
tests/test_ml_api.py::TestSinglePrediction::test_predict_with_valid_data PASSED [ 15%]
tests/test_ml_api.py::TestSinglePrediction::test_predict_with_insufficient_data PASSED [ 21%]
tests/test_ml_api.py::TestSinglePrediction::test_predict_with_invalid_data_format PASSED [ 26%]
tests/test_ml_api.py::TestSinglePrediction::test_predict_with_missing_fields PASSED [ 31%]
tests/test_ml_api.py::TestMultiStepPrediction::test_multi_step_prediction_3_months PASSED [ 36%]
tests/test_ml_api.py::TestMultiStepPrediction::test_multi_step_prediction_6_months PASSED [ 42%]
tests/test_ml_api.py::TestMultiStepPrediction::test_multi_step_invalid_steps PASSED [ 47%]
tests/test_ml_api.py::TestBatchPrediction::test_batch_prediction_multiple_sequences PASSED [ 52%]
tests/test_ml_api.py::TestPerformanceMetrics::test_prediction_within_confidence_interval PASSED [ 57%]
tests/test_ml_api.py::TestPerformanceMetrics::test_prediction_reasonable_range PASSED [ 63%]
tests/test_ml_api.py::TestPerformanceMetrics::test_response_time_under_threshold PASSED [ 68%]
tests/test_ml_api.py::TestEdgeCases::test_predict_with_zero_values PASSED [ 73%]
tests/test_ml_api.py::TestEdgeCases::test_predict_with_negative_values PASSED [ 78%]
tests/test_ml_api.py::TestEdgeCases::test_predict_with_extreme_values PASSED [ 84%]
tests/test_ml_api.py::TestEdgeCases::test_empty_payload PASSED            [ 89%]
tests/test_ml_api.py::TestEdgeCases::test_invalid_json PASSED             [ 94%]

========================= 19 passed in 5.23s ===============================
```

---

## 5️⃣ Integration Testing

### End-to-End Workflow Test

**Test Scenario: Complete Prediction Workflow**

```bash
# 1. Start all services
cd sikolbia-app && docker-compose up -d
cd sikolbia-ml && docker-compose up -d

# 2. Run Laravel tests (includes ML API calls)
cd sikolbia-app
php artisan test --filter=PrediksiNbmTest::test_can_run_prediction_with_valid_data

# 3. Verify ML API integration
curl -X POST http://localhost:8082/predict \
  -H "Content-Type: application/json" \
  -d '{
    "data_points": [
      {"tahun": 2024, "bulan": 1, "kelompok": "01", "komoditi": "0101", "kalori_hari": 850},
      {"tahun": 2024, "bulan": 2, "kelompok": "01", "komoditi": "0101", "kalori_hari": 860},
      {"tahun": 2024, "bulan": 3, "kelompok": "01", "komoditi": "0101", "kalori_hari": 870},
      {"tahun": 2024, "bulan": 4, "kelompok": "01", "komoditi": "0101", "kalori_hari": 880},
      {"tahun": 2024, "bulan": 5, "kelompok": "01", "komoditi": "0101", "kalori_hari": 890},
      {"tahun": 2024, "bulan": 6, "kelompok": "01", "komoditi": "0101", "kalori_hari": 900}
    ]
  }'

# Expected: {"success":true,"prediction":[...],...}
```

### Manual Integration Test Checklist

- [ ] **1. User Login** → Access `/login` → Submit credentials
- [ ] **2. Navigate to Prediksi** → Click "Konsumsi Pangan NBM" menu
- [ ] **3. Select Kelompok** → Dropdown populates komoditi via AJAX
- [ ] **4. Run Prediction** → Submit form → ML API called → Results displayed
- [ ] **5. View Charts** → 3 charts render with Chart.js
- [ ] **6. Generate Insights** → AI insights cards populate
- [ ] **7. Save History** → Click "Simpan" → Database entry created
- [ ] **8. Export Excel** → Download 2-sheet Excel file
- [ ] **9. Export PDF** → Download professional PDF report
- [ ] **10. View History** → Navigate to history page → See saved predictions

---

## 6️⃣ Performance Testing

### Load Testing with Apache Bench

**Test ML API Performance:**
```bash
# 100 requests, 10 concurrent
ab -n 100 -c 10 -p predict_payload.json -T "application/json" \
   http://localhost:8082/predict

# Expected: Requests per second > 10, Mean time < 1000ms
```

**Test Laravel Prediction Endpoint:**
```bash
# Login first to get session cookie
curl -c cookies.txt -d "email=test@example.com&password=password" \
     http://localhost:8000/login

# Then test with cookie
ab -n 100 -c 10 -C "laravel_session=$(cat cookies.txt)" \
   http://localhost:8000/pemerintah/prediksi-nbm
```

### Performance Benchmarks

| Endpoint | Target | Actual (Expected) |
|----------|--------|-------------------|
| ML `/predict` | < 1s | ~500ms |
| ML `/multi-step` | < 2s | ~800ms |
| Laravel prediction page | < 500ms | ~200ms |
| Excel export | < 3s | ~1.5s |
| PDF export | < 3s | ~1.2s |

---

## 7️⃣ Test Coverage

### Generate Coverage Report

**Laravel (PHPUnit):**
```bash
php artisan test --coverage --min=80
```

**Python (pytest):**
```bash
pytest tests/test_ml_api.py --cov=fastapi --cov-report=html --cov-report=term
```

### Coverage Targets

| Component | Target | Current (Expected) |
|-----------|--------|--------------------|
| Controllers | 80% | ~75% |
| Services | 90% | ~85% |
| Models | 70% | ~80% |
| ML API | 80% | ~70% |
| **Overall** | **80%** | **~75%** |

---

## 8️⃣ CI/CD Integration

### GitHub Actions Workflow

Create `.github/workflows/tests.yml`:

```yaml
name: Tests

on: [push, pull_request]

jobs:
  laravel-tests:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v3
      - name: Setup PHP
        uses: shivammathur/setup-php@v2
        with:
          php-version: 8.3
      - name: Install Dependencies
        run: composer install
      - name: Run Tests
        run: php artisan test
  
  ml-api-tests:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v3
      - name: Setup Python
        uses: actions/setup-python@v4
        with:
          python-version: '3.10'
      - name: Install Dependencies
        run: pip install -r requirements.txt
      - name: Start ML API
        run: |
          uvicorn nbm_api:app --host 0.0.0.0 --port 8082 &
          sleep 5
      - name: Run Tests
        run: pytest tests/test_ml_api.py -v
```

---

## 9️⃣ Quick Commands Reference

### Laravel Testing

```bash
# Run all tests
php artisan test

# Run with coverage
php artisan test --coverage

# Run specific test
php artisan test --filter=PrediksiNbmTest

# Run parallel (faster)
php artisan test --parallel
```

### ML API Testing

```bash
# Run all tests
pytest tests/test_ml_api.py -v

# Run with coverage
pytest tests/test_ml_api.py --cov=fastapi

# Run specific test class
pytest tests/test_ml_api.py::TestSinglePrediction -v

# Run with output
pytest tests/test_ml_api.py -vv -s
```

### Docker Testing

```bash
# Test inside Docker container
docker exec sikolbia-app php artisan test

# Test ML API from container
docker exec sikolbia-ml-api pytest tests/test_ml_api.py
```

---

## 🎯 Testing Checklist for Defense

### Pre-Defense Testing

- [ ] **All Laravel tests passing** (24/24 tests)
- [ ] **All ML API tests passing** (19/19 tests)
- [ ] **Integration test successful** (manual checklist)
- [ ] **Performance benchmarks met** (< 2s response time)
- [ ] **Coverage > 75%** (Laravel + ML API)
- [ ] **No critical bugs** (check logs)
- [ ] **Demo scenario tested** (end-to-end workflow)

### Day of Defense

- [ ] Run full test suite: `php artisan test && pytest tests/test_ml_api.py`
- [ ] Verify all services UP: `docker-compose ps`
- [ ] Check ML API health: `curl http://localhost:8082/health`
- [ ] Test live demo scenario once
- [ ] Have backup screenshots ready

---

## 📞 Troubleshooting

### Common Issues

**1. Tests fail with "Connection refused"**
```bash
# Solution: Ensure ML API is running
docker-compose up -d ml-api
curl http://localhost:8082/health
```

**2. Laravel tests timeout**
```bash
# Solution: Increase timeout in phpunit.xml
<php>
    <env name="HTTP_TIMEOUT" value="30"/>
</php>
```

**3. Database locked error**
```bash
# Solution: Use separate testing database
php artisan config:clear
php artisan migrate:fresh --env=testing
```

**4. Python tests fail with import errors**
```bash
# Solution: Install dependencies
pip install -r requirements.txt
export PYTHONPATH="${PYTHONPATH}:$(pwd)"
```

---

## 📊 Test Results Summary

### Expected Test Results (for Defense)

**Laravel Tests:**
```
Tests:    24 passed
Duration: 3-5 seconds
Coverage: ~75%
```

**ML API Tests:**
```
Tests:    19 passed
Duration: 5-7 seconds
Coverage: ~70%
```

**Integration:**
```
Manual:   10/10 checklist items ✓
E2E Time: < 30 seconds
```

**Performance:**
```
ML API:   ~500ms per request
Laravel:  ~200ms per page
Exports:  < 2s each
```

---

**🎉 All tests ready for demonstration! Good luck with your defense!** 🚀

---

**Last Updated**: November 14, 2025  
**Status**: Production Ready ✅
