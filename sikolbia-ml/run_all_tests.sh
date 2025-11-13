#!/bin/bash
# ========================================
# Run All Tests - SIKOLBIA ML API
# ========================================

echo ""
echo "============================================================"
echo "  SIKOLBIA ML API - Complete Test Suite"
echo "============================================================"
echo ""

# Colors
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

TOTAL_PASSED=0
TOTAL_FAILED=0

# Test 1: Model Loading
echo -e "${BLUE}[TEST 1]${NC} Model Loading Test"
echo "--------------------------------------------------------------"
if python test_model_loading.py > /tmp/test_model.log 2>&1; then
    echo -e "${GREEN}✓ PASSED${NC}"
    TOTAL_PASSED=$((TOTAL_PASSED + 1))
else
    echo -e "${RED}✗ FAILED${NC}"
    echo "See: /tmp/test_model.log"
    TOTAL_FAILED=$((TOTAL_FAILED + 1))
fi
echo ""

# Test 2: API Endpoints (requires server running)
echo -e "${BLUE}[TEST 2]${NC} API Endpoints Test"
echo "--------------------------------------------------------------"

# Check if server is running
if curl -s http://localhost:8083/health > /dev/null 2>&1; then
    echo "Server detected on port 8083..."
    if python test_api_calls.py > /tmp/test_api.log 2>&1; then
        echo -e "${GREEN}✓ PASSED${NC}"
        TOTAL_PASSED=$((TOTAL_PASSED + 1))
    else
        echo -e "${RED}✗ FAILED${NC}"
        echo "See: /tmp/test_api.log"
        TOTAL_FAILED=$((TOTAL_FAILED + 1))
    fi
else
    echo -e "${YELLOW}⚠ SKIPPED${NC} (Server not running on port 8083)"
    echo "Start server with: ./start_dev.sh"
fi
echo ""

# Test 3: Laravel Integration (requires PHP)
echo -e "${BLUE}[TEST 3]${NC} Laravel Integration Test"
echo "--------------------------------------------------------------"

if command -v php &> /dev/null; then
    if curl -s http://localhost:8083/health > /dev/null 2>&1; then
        if php ../test_laravel_ml_integration.php > /tmp/test_laravel.log 2>&1; then
            echo -e "${GREEN}✓ PASSED${NC}"
            TOTAL_PASSED=$((TOTAL_PASSED + 1))
        else
            echo -e "${RED}✗ FAILED${NC}"
            echo "See: /tmp/test_laravel.log"
            TOTAL_FAILED=$((TOTAL_FAILED + 1))
        fi
    else
        echo -e "${YELLOW}⚠ SKIPPED${NC} (Server not running)"
    fi
else
    echo -e "${YELLOW}⚠ SKIPPED${NC} (PHP not installed)"
fi
echo ""

# Test 4: Model Architecture Validation
echo -e "${BLUE}[TEST 4]${NC} Model Architecture Validation"
echo "--------------------------------------------------------------"
python -c "
import keras
import os

try:
    model_path = 'models/nbm_production_model.keras'
    if not os.path.exists(model_path):
        print('✗ Model file not found')
        exit(1)
    
    model = keras.models.load_model(model_path, compile=False)
    
    # Check input shape
    input_shape = model.input_shape
    if input_shape != (None, 6, 1):
        print(f'✗ Wrong input shape: {input_shape}')
        exit(1)
    
    # Check output shape
    output_shape = model.output_shape
    if output_shape != (None, 1):
        print(f'✗ Wrong output shape: {output_shape}')
        exit(1)
    
    # Check parameter count
    params = model.count_params()
    if params != 29857:
        print(f'✗ Wrong parameter count: {params}')
        exit(1)
    
    print('✓ Model architecture valid')
    print(f'  - Input: {input_shape}')
    print(f'  - Output: {output_shape}')
    print(f'  - Params: {params:,}')
    
except Exception as e:
    print(f'✗ Validation failed: {e}')
    exit(1)
" > /tmp/test_arch.log 2>&1

if [ $? -eq 0 ]; then
    echo -e "${GREEN}✓ PASSED${NC}"
    cat /tmp/test_arch.log | grep -E "✓|Input|Output|Params"
    TOTAL_PASSED=$((TOTAL_PASSED + 1))
else
    echo -e "${RED}✗ FAILED${NC}"
    cat /tmp/test_arch.log
    TOTAL_FAILED=$((TOTAL_FAILED + 1))
fi
echo ""

# Test 5: Configuration Check
echo -e "${BLUE}[TEST 5]${NC} Configuration Check"
echo "--------------------------------------------------------------"

config_ok=true

# Check Laravel .env
if [ -f "../sikolbia-app/.env" ]; then
    if grep -q "NBM_API_URL=http://localhost:8083" "../sikolbia-app/.env"; then
        echo -e "  ${GREEN}✓${NC} Laravel .env configured"
    else
        echo -e "  ${RED}✗${NC} Laravel .env not configured"
        config_ok=false
    fi
else
    echo -e "  ${YELLOW}⚠${NC} Laravel .env not found"
fi

# Check model file
if [ -f "models/nbm_production_model.keras" ]; then
    size=$(ls -lh models/nbm_production_model.keras | awk '{print $5}')
    echo -e "  ${GREEN}✓${NC} Model file exists ($size)"
else
    echo -e "  ${RED}✗${NC} Model file not found"
    config_ok=false
fi

# Check requirements.txt
if [ -f "requirements.txt" ]; then
    if grep -q "tensorflow" requirements.txt && grep -q "fastapi" requirements.txt; then
        echo -e "  ${GREEN}✓${NC} Requirements file valid"
    else
        echo -e "  ${YELLOW}⚠${NC} Requirements file incomplete"
    fi
else
    echo -e "  ${RED}✗${NC} Requirements file not found"
    config_ok=false
fi

if [ "$config_ok" = true ]; then
    echo -e "${GREEN}✓ PASSED${NC}"
    TOTAL_PASSED=$((TOTAL_PASSED + 1))
else
    echo -e "${RED}✗ FAILED${NC}"
    TOTAL_FAILED=$((TOTAL_FAILED + 1))
fi
echo ""

# Final Summary
echo "============================================================"
echo "  FINAL SUMMARY"
echo "============================================================"
echo ""
echo -e "Total Tests: $((TOTAL_PASSED + TOTAL_FAILED))"
echo -e "Passed: ${GREEN}$TOTAL_PASSED${NC}"
echo -e "Failed: ${RED}$TOTAL_FAILED${NC}"
echo ""

if [ $TOTAL_FAILED -eq 0 ]; then
    echo -e "${GREEN}✓✓✓ ALL TESTS PASSED! ✓✓✓${NC}"
    echo ""
    echo "System is ready for deployment."
    exit 0
else
    echo -e "${RED}✗✗✗ SOME TESTS FAILED ✗✗✗${NC}"
    echo ""
    echo "Please check the logs:"
    echo "  - /tmp/test_model.log"
    echo "  - /tmp/test_api.log"
    echo "  - /tmp/test_laravel.log"
    echo "  - /tmp/test_arch.log"
    exit 1
fi
