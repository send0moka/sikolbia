@echo off
echo 🚀 Starting NBM Prediction API...
echo ================================

cd /d "%~dp0"

REM Check if production model exists
if not exist "ml_models\models\nbm_production" (
    echo ❌ Production model not found!
    echo Please run setup.bat first to create the model.
    pause
    exit /b 1
)

echo ✅ Production model found
echo 🔥 Starting FastAPI server on port 8081...
echo 📖 API Documentation: http://localhost:8081/docs
echo 🛑 Press Ctrl+C to stop the server
echo.

python nbm_api.py

python nbm_api.py
