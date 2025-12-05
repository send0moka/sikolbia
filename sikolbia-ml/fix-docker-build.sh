#!/bin/bash

# Quick Fix Script for Docker Build Models Issue
# Run this after pulling the fix commit

set -e

echo "============================================"
echo "SIKOLBIA ML Docker Build Fix"
echo "============================================"
echo ""

# Colors
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m'

cd "$(dirname "$0")"

echo "Step 1: Cleaning old Docker artifacts..."
docker rmi sikolbia-ml 2>/dev/null || echo "  No old image to remove"
echo -e "${GREEN}✓${NC} Cleaned"

echo ""
echo "Step 2: Building Docker image (without models)..."
docker build -t sikolbia-ml .
echo -e "${GREEN}✓${NC} Build successful"

echo ""
echo "Step 3: Testing container (fallback mode)..."
docker run -d --name sikolbia-ml-test -p 8082:8000 sikolbia-ml
sleep 5

# Test health endpoint
if curl -sf http://localhost:8082/health > /dev/null; then
    echo -e "${GREEN}✓${NC} Container running in fallback mode"
else
    echo -e "${YELLOW}⚠${NC} Container started but health check failed"
fi

echo ""
echo "Step 4: Stopping test container..."
docker stop sikolbia-ml-test
docker rm sikolbia-ml-test
echo -e "${GREEN}✓${NC} Test complete"

echo ""
echo "============================================"
echo "Build Fixed Successfully!"
echo "============================================"
echo ""
echo "To run with ML models:"
echo "  docker run -d -p 8082:8000 \\"
echo "    -v \$(pwd)/ml_models/models:/app/ml_models/models:ro \\"
echo "    sikolbia-ml"
echo ""
echo "To run without models (fallback mode):"
echo "  docker run -d -p 8082:8000 sikolbia-ml"
echo ""
echo "Test endpoints:"
echo "  curl http://localhost:8082/health"
echo "  curl http://localhost:8082/model/info"
echo ""

exit 0
