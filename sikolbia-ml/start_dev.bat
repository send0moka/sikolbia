@echo off
REM ========================================
REM Start SIKOLBIA ML API - Development
REM ========================================

echo.
echo ============================================================
echo   SIKOLBIA ML API - Development Server
echo ============================================================
echo.

REM Check if Python is installed
python --version >nul 2>&1
if errorlevel 1 (
    echo [ERROR] Python not found! Please install Python 3.9+
    pause
    exit /b 1
)

REM Check if model file exists
if not exist "models\nbm_production_model.keras" (
    echo [WARNING] Model file not found: models\nbm_production_model.keras
    echo [INFO] Server will run in fallback mode
    echo.
)

REM Change to app directory
cd app

echo [INFO] Starting FastAPI server...
echo [INFO] Server will run on: http://localhost:8083
echo [INFO] API Documentation: http://localhost:8083/docs
echo [INFO] Press Ctrl+C to stop
echo.

REM Start uvicorn with auto-reload
python -m uvicorn main_simple:app --host 0.0.0.0 --port 8083 --reload

pause
