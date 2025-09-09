@echo off
echo 🚀 Setting up NBM Prediction System...
echo ============================================

REM Check if Python is installed
python --version >nul 2>&1
if %errorlevel% neq 0 (
    echo ❌ Python is not installed. Please install Python 3.9+ first.
    pause
    exit /b 1
)

echo ✅ Python found
python --version

REM Install Python dependencies
echo.
echo 📦 Installing Python dependencies...
pip install -r requirements.txt

REM Check if production model exists
if not exist "ml_models\models\nbm_production" (
    echo.
    echo 🔧 Production model not found. Creating it now...
    echo ⏳ This may take a few minutes...
    python ml_models\production_model.py
    
    if %errorlevel% equ 0 (
        echo ✅ Production model created successfully!
    ) else (
        echo ❌ Failed to create production model. Please check the error above.
        pause
        exit /b 1
    )
) else (
    echo ✅ Production model already exists.
)

echo.
echo 🎉 Setup complete!
echo.
echo 📋 Next steps:
echo 1. Start the Python API: python nbm_api.py
echo 2. Start Laravel: php artisan serve
echo 3. Access prediction interface: http://localhost:8000/prediksi
echo.
echo 🔗 API Documentation: http://localhost:8081/docs
echo 📊 Model Performance: 8.88%% MAPE (91.12%% accuracy)
echo.
pause
