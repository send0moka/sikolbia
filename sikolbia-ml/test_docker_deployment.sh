#!/bin/bash
# ========================================
# Test Docker Deployment
# ========================================

echo ""
echo "============================================================"
echo "  Testing SIKOLBIA ML API Docker Deployment"
echo "============================================================"
echo ""

# Colors
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Test counter
PASSED=0
FAILED=0

# Function to test endpoint
test_endpoint() {
    local name=$1
    local url=$2
    local expected_status=$3
    
    echo -n "Testing $name... "
    
    response=$(curl -s -w "\n%{http_code}" "$url" 2>/dev/null)
    http_code=$(echo "$response" | tail -n1)
    body=$(echo "$response" | head -n-1)
    
    if [ "$http_code" = "$expected_status" ]; then
        echo -e "${GREEN}✓ PASSED${NC} (HTTP $http_code)"
        PASSED=$((PASSED + 1))
        return 0
    else
        echo -e "${RED}✗ FAILED${NC} (Expected $expected_status, got $http_code)"
        FAILED=$((FAILED + 1))
        return 1
    fi
}

# Check if Docker is running
echo "[1] Checking Docker..."
if ! docker info > /dev/null 2>&1; then
    echo -e "${RED}✗ Docker is not running${NC}"
    exit 1
fi
echo -e "${GREEN}✓ Docker is running${NC}"
echo ""

# Check if container exists
echo "[2] Checking container..."
if docker ps -a | grep -q sikolbia-ml-api; then
    echo -e "${GREEN}✓ Container exists${NC}"
    
    # Check if running
    if docker ps | grep -q sikolbia-ml-api; then
        echo -e "${GREEN}✓ Container is running${NC}"
    else
        echo -e "${YELLOW}⚠ Container exists but not running${NC}"
        echo "Starting container..."
        docker start sikolbia-ml-api
        sleep 5
    fi
else
    echo -e "${YELLOW}⚠ Container not found${NC}"
    echo "Building and starting container..."
    docker-compose up -d --build
    sleep 10
fi
echo ""

# Test endpoints
echo "[3] Testing API endpoints..."
echo ""

test_endpoint "Health Check" "http://localhost:8082/health" "200"
test_endpoint "Model Stats" "http://localhost:8082/model/stats" "200"
test_endpoint "Root Endpoint" "http://localhost:8082/" "200"

# Test prediction endpoint
echo -n "Testing Prediction Endpoint... "
response=$(curl -s -w "\n%{http_code}" -X POST http://localhost:8082/predict \
  -H "Content-Type: application/json" \
  -d '{
    "data_points": [
      {"tahun": 2024, "bulan": 1, "kelompok": "01", "komoditi": "0101", "kalori_hari": 500000},
      {"tahun": 2024, "bulan": 2, "kelompok": "01", "komoditi": "0101", "kalori_hari": 520000},
      {"tahun": 2024, "bulan": 3, "kelompok": "01", "komoditi": "0101", "kalori_hari": 510000},
      {"tahun": 2024, "bulan": 4, "kelompok": "01", "komoditi": "0101", "kalori_hari": 530000},
      {"tahun": 2024, "bulan": 5, "kelompok": "01", "komoditi": "0101", "kalori_hari": 540000},
      {"tahun": 2024, "bulan": 6, "kelompok": "01", "komoditi": "0101", "kalori_hari": 550000}
    ],
    "n_periods": 3
  }' 2>/dev/null)

http_code=$(echo "$response" | tail -n1)
body=$(echo "$response" | head -n-1)

if [ "$http_code" = "200" ]; then
    echo -e "${GREEN}✓ PASSED${NC} (HTTP $http_code)"
    PASSED=$((PASSED + 1))
    
    # Check if response contains predictions
    if echo "$body" | grep -q "predictions"; then
        echo -e "  ${GREEN}✓ Response contains predictions${NC}"
    fi
    
    # Check model version
    if echo "$body" | grep -q "production-lstm"; then
        echo -e "  ${GREEN}✓ Using production LSTM model${NC}"
    elif echo "$body" | grep -q "fallback"; then
        echo -e "  ${YELLOW}⚠ Using fallback model${NC}"
    fi
else
    echo -e "${RED}✗ FAILED${NC} (Expected 200, got $http_code)"
    FAILED=$((FAILED + 1))
fi

echo ""

# Check container logs for errors
echo "[4] Checking container logs..."
echo ""

if docker logs sikolbia-ml-api 2>&1 | tail -20 | grep -qi "error"; then
    echo -e "${YELLOW}⚠ Found errors in logs:${NC}"
    docker logs sikolbia-ml-api 2>&1 | tail -10
else
    echo -e "${GREEN}✓ No errors in recent logs${NC}"
fi

echo ""

# Check if model is loaded
echo "[5] Checking model status..."
echo ""

model_status=$(curl -s http://localhost:8082/model/stats 2>/dev/null | grep -o '"status":"[^"]*"' | cut -d'"' -f4)

if [ "$model_status" = "production" ]; then
    echo -e "${GREEN}✓ Model loaded in PRODUCTION mode${NC}"
elif [ "$model_status" = "mock" ]; then
    echo -e "${YELLOW}⚠ Model in MOCK/FALLBACK mode${NC}"
    echo "  Check if model file is mounted in container"
else
    echo -e "${RED}✗ Unknown model status${NC}"
fi

echo ""

# Final summary
echo "============================================================"
echo "  TEST SUMMARY"
echo "============================================================"
echo ""
echo -e "Tests Passed: ${GREEN}$PASSED${NC}"
echo -e "Tests Failed: ${RED}$FAILED${NC}"
echo ""

if [ $FAILED -eq 0 ]; then
    echo -e "${GREEN}✓ All tests passed!${NC}"
    echo ""
    echo "Container is ready for use:"
    echo "  API: http://localhost:8082"
    echo "  Docs: http://localhost:8082/docs"
    exit 0
else
    echo -e "${RED}✗ Some tests failed${NC}"
    echo ""
    echo "Check Docker logs:"
    echo "  docker logs sikolbia-ml-api"
    exit 1
fi
