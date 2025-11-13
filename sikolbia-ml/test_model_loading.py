"""
Test script untuk verify model loading
"""
import os
import sys

# Add parent directory to path
sys.path.insert(0, os.path.join(os.path.dirname(__file__), '..'))

def test_model_loading():
    """Test if model can be loaded successfully"""
    try:
        import tensorflow as tf
        import numpy as np
        
        print("=" * 60)
        print("TESTING NBM PRODUCTION MODEL LOADING")
        print("=" * 60)
        print()
        
        # Model path
        model_path = os.path.join(os.path.dirname(__file__), 'models', 'nbm_production_model.keras')
        print(f"📂 Model path: {model_path}")
        print(f"📂 Model exists: {os.path.exists(model_path)}")
        print()
        
        if not os.path.exists(model_path):
            print("❌ Model file not found!")
            return False
        
        # Load model
        print("⏳ Loading model...")
        model = tf.keras.models.load_model(model_path, compile=False)
        
        # Compile
        model.compile(
            optimizer='adam',
            loss='huber',
            metrics=['mae', 'mse']
        )
        
        print("✅ Model loaded successfully!")
        print()
        
        # Model info
        print("📊 MODEL INFORMATION:")
        print(f"   Input shape: {model.input_shape}")
        print(f"   Output shape: {model.output_shape}")
        print()
        
        # Model summary
        print("📋 MODEL ARCHITECTURE:")
        model.summary()
        print()
        
        # Test prediction
        print("🧪 TESTING PREDICTION:")
        # Create dummy input: (batch_size=1, sequence_length=6, features=1)
        dummy_input = np.random.rand(1, 6, 1).astype(np.float32)
        print(f"   Input shape: {dummy_input.shape}")
        
        prediction = model.predict(dummy_input, verbose=0)
        print(f"   Prediction shape: {prediction.shape}")
        print(f"   Prediction value: {prediction[0][0]:.2f}")
        print()
        
        print("=" * 60)
        print("✅ ALL TESTS PASSED!")
        print("=" * 60)
        
        return True
        
    except Exception as e:
        print()
        print("=" * 60)
        print(f"❌ ERROR: {str(e)}")
        print("=" * 60)
        import traceback
        traceback.print_exc()
        return False

if __name__ == "__main__":
    success = test_model_loading()
    sys.exit(0 if success else 1)
