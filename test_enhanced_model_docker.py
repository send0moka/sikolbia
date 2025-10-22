#!/usr/bin/env python3
"""
Test enhanced model loading in Docker container
"""

import sys
import os
sys.path.append('/app/ml_models')

def test_enhanced_model():
    try:
        from enhanced_model_adapter import NBMProductionModelEnhanced
        
        # Test with correct path
        model = NBMProductionModelEnhanced.load_enhanced_model("ml_models/models/nbm_production_enhanced")
        print("✅ Enhanced model loaded successfully!")
        
        # Test prediction capabilities
        import numpy as np
        test_data = np.random.rand(1, 6, 10)
        
        result = model.predict_original_scale_with_confidence(test_data)
        print(f"✅ Confidence prediction: {result['prediction'][0]:.1f} ± {result['interval_width'][0]/2:.1f}")
        
        multi_result = model.predict_multi_step(test_data, n_steps=3)
        print(f"✅ Multi-step prediction: {len(multi_result['predictions'])} steps")
        
        return True
        
    except Exception as e:
        print(f"❌ Enhanced model test failed: {e}")
        return False

if __name__ == "__main__":
    success = test_enhanced_model()
    if success:
        print("🎉 Enhanced model ready for use!")
    else:
        print("⚠️ Enhanced model not available")