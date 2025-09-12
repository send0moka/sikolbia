"""
Quick test script to verify ML environment setup
Run this before full training to catch issues early
"""

import sys
import os

def test_imports():
    """Test if all required packages can be imported"""
    print("🔍 Testing package imports...")
    
    try:
        import numpy as np
        print("✅ NumPy imported successfully")
    except ImportError as e:
        print(f"❌ NumPy import failed: {e}")
        return False
    
    try:
        import pandas as pd
        print("✅ Pandas imported successfully")
    except ImportError as e:
        print(f"❌ Pandas import failed: {e}")
        return False
    
    try:
        import tensorflow as tf
        print(f"✅ TensorFlow imported successfully (version: {tf.__version__})")
    except ImportError as e:
        print(f"❌ TensorFlow import failed: {e}")
        return False
    
    try:
        import sklearn
        print(f"✅ Scikit-learn imported successfully (version: {sklearn.__version__})")
    except ImportError as e:
        print(f"❌ Scikit-learn import failed: {e}")
        return False
    
    try:
        import mysql.connector
        print("✅ MySQL connector imported successfully")
    except ImportError as e:
        print(f"❌ MySQL connector import failed: {e}")
        return False
    
    try:
        import matplotlib.pyplot as plt
        print("✅ Matplotlib imported successfully")
    except ImportError as e:
        print(f"❌ Matplotlib import failed: {e}")
        return False
    
    return True

def test_environment():
    """Test environment configuration"""
    print("\n🔍 Testing environment setup...")
    
    # Check if .env file exists
    if os.path.exists('.env'):
        print("✅ .env file found")
    else:
        print("❌ .env file not found")
        return False
    
    # Check if directories exist
    directories = ['data', 'models', 'notebooks']
    for directory in directories:
        if os.path.exists(directory):
            print(f"✅ {directory}/ directory exists")
        else:
            print(f"❌ {directory}/ directory missing")
            return False
    
    return True

def test_database_connection():
    """Test database connection"""
    print("\n🔍 Testing database connection...")
    
    try:
        from data_loader import DataLoader
        loader = DataLoader()
        
        if loader.test_connection():
            print("✅ Database connection successful")
            return True
        else:
            print("❌ Database connection failed")
            return False
    except Exception as e:
        print(f"❌ Database test error: {e}")
        return False

def test_sample_data_processing():
    """Test data processing with sample data"""
    print("\n🔍 Testing data processing pipeline...")
    
    try:
        import numpy as np
        import pandas as pd
        from data_preprocessing import preprocess_pipeline
        
        # Create sample data
        sample_data = pd.DataFrame({
            'tahun': range(2000, 2025),
            'kalori_per_kapita': np.random.normal(2500, 200, 25) + np.arange(25) * 10
        })
        
        # Test preprocessing
        result = preprocess_pipeline(sample_data, sequence_length=10)
        
        if result is not None and 'X_train' in result:
            print(f"✅ Data processing successful - Train shape: {result['X_train'].shape}")
            return True
        else:
            print("❌ Data processing failed")
            return False
            
    except Exception as e:
        print(f"❌ Data processing error: {e}")
        return False

def test_model_creation():
    """Test LSTM model creation"""
    print("\n🔍 Testing LSTM model creation...")
    
    try:
        from lstm_model import LSTMCaloriePredictor
        
        # Create model
        model = LSTMCaloriePredictor(sequence_length=10)
        model.build_model()
        
        print("✅ LSTM model created successfully")
        print(f"   Model parameters: {model.model.count_params()}")
        return True
        
    except Exception as e:
        print(f"❌ Model creation error: {e}")
        return False

def main():
    """Run all tests"""
    print("🚀 ML Environment Test Suite")
    print("=" * 40)
    
    tests = [
        ("Package Imports", test_imports),
        ("Environment Setup", test_environment),
        ("Database Connection", test_database_connection),
        ("Data Processing", test_sample_data_processing),
        ("Model Creation", test_model_creation)
    ]
    
    passed = 0
    total = len(tests)
    
    for test_name, test_func in tests:
        print(f"\n📋 Running {test_name} test...")
        try:
            if test_func():
                passed += 1
                print(f"✅ {test_name} test PASSED")
            else:
                print(f"❌ {test_name} test FAILED")
        except Exception as e:
            print(f"❌ {test_name} test ERROR: {e}")
    
    print("\n" + "=" * 40)
    print(f"📊 Test Results: {passed}/{total} tests passed")
    
    if passed == total:
        print("🎉 All tests passed! Ready for training.")
        return True
    else:
        print("⚠️  Some tests failed. Fix issues before training.")
        return False

if __name__ == "__main__":
    success = main()
    sys.exit(0 if success else 1)
