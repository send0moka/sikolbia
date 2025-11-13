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

# Check if model file exists
if [ ! -f "models/nbm_production_model.keras" ]; then
    echo "[WARNING] Model file not found: models/nbm_production_model.keras"
    echo "[INFO] Server will run in fallback mode"
    echo ""
fi

# Change to app directory
cd app

echo "[INFO] Starting FastAPI server..."
echo "[INFO] Server will run on: http://localhost:8083"
echo "[INFO] API Documentation: http://localhost:8083/docs"
echo "[INFO] Press Ctrl+C to stop"
echo ""

# Start uvicorn with auto-reload
python3 -m uvicorn main_simple:app --host 0.0.0.0 --port 8083 --reload
