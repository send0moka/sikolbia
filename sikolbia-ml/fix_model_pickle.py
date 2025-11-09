"""
Script to fix pickled model by re-saving with proper module path
"""
import sys
import os
import pickle
import joblib

# Add ml_models to path
sys.path.append(os.path.join(os.path.dirname(__file__), 'ml_models'))

# Import the class
from ml_models.production_model import NBMProductionModel

# Monkey-patch sys.modules to allow loading from __main__
class MainModuleRedirector:
    def __getattr__(self, name):
        # Redirect to production_model module
        from ml_models import production_model
        return getattr(production_model, name)

sys.modules['__main__'].NBMProductionModel = NBMProductionModel

model_file = "ml_models/models/nbm_production/nbm_production_model.pkl"
print(f"Loading model from {model_file}...")

try:
    # Load with __main__ patched
    model = joblib.load(model_file)
    print(f"✅ Model loaded successfully: {type(model)}")
    
    # Save with proper module path
    output_file = "ml_models/models/nbm_production/nbm_production_model_fixed.pkl"
    joblib.dump(model, output_file)
    print(f"✅ Model re-saved to {output_file}")
    
    # Test load
    print("Testing reload...")
    test_model = joblib.load(output_file)
    print(f"✅ Test reload successful: {type(test_model)}")
    
    # Backup original and replace
    os.rename(model_file, model_file + ".bak")
    os.rename(output_file, model_file)
    print(f"✅ Original backed up and replaced")
    
except Exception as e:
    print(f"❌ Error: {e}")
    import traceback
    traceback.print_exc()
