#!/bin/bash

echo "Starting NBM Prediction API Server..."
echo

cd "$(dirname "$0")"

echo "Activating virtual environment..."
source ml_models/lstm_env/Scripts/activate

echo
echo "Installing/updating dependencies..."
pip install fastapi uvicorn pydantic python-multipart pandas numpy scikit-learn joblib

echo
echo "Starting FastAPI server..."
echo "API will be available at:"
echo "  - Swagger UI: http://localhost:8080/docs"
echo "  - ReDoc: http://localhost:8080/redoc"  
echo "  - Health Check: http://localhost:8080/health"
echo
echo "Press Ctrl+C to stop the server"
echo

python nbm_api.py
