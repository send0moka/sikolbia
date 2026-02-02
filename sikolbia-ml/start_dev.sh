#!/bin/bash
# ========================================
# Start SIKOLBIA ML API - Development
# ========================================

echo ""
echo "============================================================"
echo "  SIKOLBIA ML API - Development Server"
echo "============================================================"
echo ""

# Check if Python is installed
if ! command -v python3 &> /dev/null; then
    echo "[ERROR] Python3 not found! Please install Python 3.9+"
    exit 1
fi

# Check if model directory exists
if [ ! -d "app/ml_models/models/nbm_google_colab" ]; then
    echo "[WARNING] Model directory not found: app/ml_models/models/nbm_google_colab"
    echo "[INFO] Please ensure Google Colab models are exported"
    echo ""
fi

# Change to app directory
cd app

echo "[INFO] Starting FastAPI server..."
echo "[INFO] Server will run on: http://localhost:8082"
echo "[INFO] API Documentation: http://localhost:8082/docs"
echo "[INFO] Press Ctrl+C to stop"
echo ""

# Start uvicorn with auto-reload
python3 -m uvicorn main:app --host 0.0.0.0 --port 8082 --reload
