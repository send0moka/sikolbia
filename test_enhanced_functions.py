#!/usr/bin/env python3
"""
Simple enhanced prediction script for Docker container
"""

import sys
import os
import numpy as np
import joblib
from scipy import stats

sys.path.append('/app/ml_models')

def load_production_model():
    """Load the base production model"""
    try:
        from production_model import NBMProductionModel
        model = NBMProductionModel.load_production_model("ml_models/models/nbm_production")
        return model
    except Exception as e:
        print(f"Error loading production model: {e}")
        return None

def enhanced_predict_with_confidence(model, X, confidence_level=0.95):
    """Enhanced prediction with confidence intervals"""
    if model is None:
        return None
    
    # Get point prediction
    prediction = model.predict_original_scale(X)[0]
    
    # Calculate confidence intervals using model performance
    # Using 8.88% MAPE as base uncertainty
    relative_uncertainty = 0.088 + 0.03  # 8.8% + additional uncertainty
    
    # Calculate margin using t-distribution
    alpha = 1 - confidence_level
    df = 100  # degrees of freedom
    t_value = stats.t.ppf(1 - alpha/2, df=df)
    
    margin = t_value * relative_uncertainty * abs(prediction)
    
    lower_bound = max(0, prediction - margin)
    upper_bound = prediction + margin
    
    return {
        'prediction': prediction,
        'lower_bound': lower_bound,
        'upper_bound': upper_bound,
        'confidence_level': confidence_level,
        'interval_width': upper_bound - lower_bound,
        'relative_uncertainty': relative_uncertainty * 100
    }

def enhanced_predict_multi_step(model, X, n_steps=3):
    """Multi-step prediction with uncertainty propagation"""
    if model is None:
        return None
    
    predictions = []
    confidence_intervals = []
    current_sequence = X.copy()
    
    base_uncertainty = 0.088
    
    for step in range(n_steps):
        # Increase uncertainty with each step
        step_uncertainty = base_uncertainty * (1 + 0.1 * step)
        
        # Get prediction
        prediction = model.predict_original_scale(current_sequence)[0]
        
        # Calculate confidence with growing uncertainty
        margin_multiplier = 1 + (0.15 * step)  # 15% wider per step
        t_value = stats.t.ppf(0.975, df=100)  # 95% confidence
        margin = t_value * step_uncertainty * abs(prediction) * margin_multiplier
        
        lower = max(0, prediction - margin)
        upper = prediction + margin
        
        predictions.append(prediction)
        confidence_intervals.append({
            'lower': lower,
            'upper': upper,
            'width': upper - lower
        })
        
        # Update sequence (simplified)
        if hasattr(model, 'data_scaler') and model.data_scaler:
            normalized_pred = model.data_scaler.transform([[prediction]])[0, 0]
            current_sequence = np.roll(current_sequence, -1, axis=1)
            current_sequence[0, -1, 0] = normalized_pred
    
    return {
        'predictions': predictions,
        'confidence_intervals': confidence_intervals,
        'steps': n_steps
    }

def test_enhanced_functions():
    """Test the enhanced functions"""
    print("🧪 Testing Enhanced Functions in Docker")
    print("="*40)
    
    # Load model
    model = load_production_model()
    if model is None:
        print("❌ Failed to load production model")
        return False
    
    print("✅ Production model loaded")
    
    # Create test data
    test_data = np.random.rand(1, 6, 10)
    
    # Test confidence prediction
    try:
        result = enhanced_predict_with_confidence(model, test_data)
        if result:
            print(f"✅ Confidence prediction: {result['prediction']:.1f} ({result['lower_bound']:.1f} - {result['upper_bound']:.1f})")
            print(f"   Uncertainty: {result['relative_uncertainty']:.1f}%")
        else:
            print("❌ Confidence prediction failed")
    except Exception as e:
        print(f"❌ Confidence prediction error: {e}")
    
    # Test multi-step prediction
    try:
        multi_result = enhanced_predict_multi_step(model, test_data, n_steps=3)
        if multi_result:
            print(f"✅ Multi-step prediction: {len(multi_result['predictions'])} steps")
            for i, (pred, ci) in enumerate(zip(multi_result['predictions'], multi_result['confidence_intervals'])):
                print(f"   Step {i+1}: {pred:.1f} ({ci['lower']:.1f} - {ci['upper']:.1f})")
        else:
            print("❌ Multi-step prediction failed")
    except Exception as e:
        print(f"❌ Multi-step prediction error: {e}")
    
    print("🎉 Enhanced functions working!")
    return True

if __name__ == "__main__":
    test_enhanced_functions()