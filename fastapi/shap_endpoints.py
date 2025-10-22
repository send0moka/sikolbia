"""
SHAP API endpoints for model interpretability
Provides REST API access to SHAP analysis functionality
"""

from fastapi import APIRouter, HTTPException, BackgroundTasks
from pydantic import BaseModel, Field
from typing import List, Dict, Any, Optional
import numpy as np
import pandas as pd
import logging
from datetime import datetime
import asyncio
import json
from pathlib import Path

# Import the SHAP analyzer
from .shap_analyzer import SHAPAnalyzer

logger = logging.getLogger(__name__)

# Initialize router
shap_router = APIRouter(prefix="/shap", tags=["SHAP Analysis"])

# Global SHAP analyzer instance
global_shap_analyzer = None

# Pydantic models for API
class SHAPAnalysisRequest(BaseModel):
    """Request model for SHAP analysis"""
    features: List[float] = Field(..., description="Input features for analysis")
    feature_names: Optional[List[str]] = Field(None, description="Feature names")
    explain_single: bool = Field(True, description="Whether to explain single prediction")

class SHAPAnalysisResponse(BaseModel):
    """Response model for SHAP analysis"""
    success: bool
    prediction: Optional[float] = None
    base_value: Optional[float] = None
    feature_contributions: Optional[Dict[str, float]] = None
    top_positive_features: Optional[List[Dict[str, Any]]] = None
    top_negative_features: Optional[List[Dict[str, Any]]] = None
    insights: Optional[List[str]] = None
    timestamp: str

class FeatureImportanceResponse(BaseModel):
    """Response model for feature importance"""
    success: bool
    feature_importance: Dict[str, float]
    category_importance: Dict[str, float]
    top_features: List[str]
    insights: List[str]
    timestamp: str

class SHAPInitRequest(BaseModel):
    """Request model for SHAP initialization"""
    model_path: Optional[str] = Field("models/nbm_production", description="Path to model")
    explainer_type: Optional[str] = Field("auto", description="Type of SHAP explainer")
    background_samples: Optional[int] = Field(100, description="Number of background samples")

async def initialize_shap_analyzer(model_path: str = "models/nbm_production", 
                                 explainer_type: str = "auto",
                                 background_samples: int = 100) -> bool:
    """
    Initialize global SHAP analyzer
    
    Args:
        model_path: Path to trained model
        explainer_type: Type of SHAP explainer
        background_samples: Number of background samples
        
    Returns:
        bool: Success status
    """
    global global_shap_analyzer
    
    try:
        # Initialize analyzer
        global_shap_analyzer = SHAPAnalyzer(model_path)
        
        # Load model
        if not global_shap_analyzer.load_model():
            logger.error("Failed to load model for SHAP analysis")
            return False
        
        # Load background data
        if not global_shap_analyzer.load_background_data(sample_size=background_samples):
            logger.error("Failed to load background data for SHAP analysis")
            return False
        
        # Initialize explainer
        if not global_shap_analyzer.initialize_explainer(explainer_type):
            logger.error("Failed to initialize SHAP explainer")
            return False
        
        logger.info("SHAP analyzer initialized successfully")
        return True
        
    except Exception as e:
        logger.error(f"Error initializing SHAP analyzer: {str(e)}")
        return False

@shap_router.post("/initialize", response_model=Dict[str, Any])
async def initialize_shap(request: SHAPInitRequest, background_tasks: BackgroundTasks):
    """
    Initialize SHAP analyzer with specified parameters
    """
    try:
        # Run initialization in background
        background_tasks.add_task(
            initialize_shap_analyzer,
            request.model_path,
            request.explainer_type,
            request.background_samples
        )
        
        return {
            "success": True,
            "message": "SHAP analyzer initialization started",
            "parameters": {
                "model_path": request.model_path,
                "explainer_type": request.explainer_type,
                "background_samples": request.background_samples
            },
            "timestamp": datetime.now().isoformat()
        }
        
    except Exception as e:
        logger.error(f"Error in SHAP initialization endpoint: {str(e)}")
        raise HTTPException(status_code=500, detail=str(e))

@shap_router.get("/status")
async def get_shap_status():
    """
    Get SHAP analyzer status
    """
    global global_shap_analyzer
    
    try:
        if global_shap_analyzer is None:
            return {
                "initialized": False,
                "message": "SHAP analyzer not initialized",
                "timestamp": datetime.now().isoformat()
            }
        
        # Check if fully initialized
        is_ready = (
            global_shap_analyzer.model is not None and
            global_shap_analyzer.explainer is not None and
            global_shap_analyzer.background_data is not None
        )
        
        return {
            "initialized": True,
            "ready": is_ready,
            "model_loaded": global_shap_analyzer.model is not None,
            "explainer_loaded": global_shap_analyzer.explainer is not None,
            "background_data_loaded": global_shap_analyzer.background_data is not None,
            "feature_count": len(global_shap_analyzer.feature_names) if global_shap_analyzer.feature_names else 0,
            "timestamp": datetime.now().isoformat()
        }
        
    except Exception as e:
        logger.error(f"Error checking SHAP status: {str(e)}")
        raise HTTPException(status_code=500, detail=str(e))

@shap_router.post("/analyze", response_model=SHAPAnalysisResponse)
async def analyze_prediction(request: SHAPAnalysisRequest):
    """
    Analyze a prediction with SHAP values
    """
    global global_shap_analyzer
    
    try:
        # Check if analyzer is initialized
        if global_shap_analyzer is None:
            raise HTTPException(status_code=400, detail="SHAP analyzer not initialized")
        
        if global_shap_analyzer.explainer is None:
            raise HTTPException(status_code=400, detail="SHAP explainer not ready")
        
        # Convert features to numpy array
        X_single = np.array(request.features)
        
        # Prepare feature values for interpretation
        feature_values = {}
        if request.feature_names and len(request.feature_names) == len(request.features):
            feature_values = dict(zip(request.feature_names, request.features))
        
        # Explain prediction
        explanation = global_shap_analyzer.explain_prediction(X_single, feature_values)
        
        if not explanation:
            raise HTTPException(status_code=500, detail="Failed to generate explanation")
        
        # Generate insights
        insights = []
        if explanation.get('top_positive_features'):
            top_feature = explanation['top_positive_features'][0]
            insights.append(f"Faktor '{top_feature['feature']}' paling meningkatkan prediksi sebesar {top_feature['contribution']:.3f}")
        
        if explanation.get('top_negative_features'):
            bottom_feature = explanation['top_negative_features'][0]
            insights.append(f"Faktor '{bottom_feature['feature']}' paling menurunkan prediksi sebesar {abs(bottom_feature['contribution']):.3f}")
        
        return SHAPAnalysisResponse(
            success=True,
            prediction=explanation.get('prediction'),
            base_value=explanation.get('base_value'),
            feature_contributions=explanation.get('feature_contributions'),
            top_positive_features=explanation.get('top_positive_features'),
            top_negative_features=explanation.get('top_negative_features'),
            insights=insights,
            timestamp=datetime.now().isoformat()
        )
        
    except HTTPException:
        raise
    except Exception as e:
        logger.error(f"Error in SHAP analysis endpoint: {str(e)}")
        raise HTTPException(status_code=500, detail=str(e))

@shap_router.get("/feature-importance", response_model=FeatureImportanceResponse)
async def get_feature_importance():
    """
    Get global feature importance from SHAP analysis
    """
    global global_shap_analyzer
    
    try:
        # Check if analyzer is initialized
        if global_shap_analyzer is None:
            raise HTTPException(status_code=400, detail="SHAP analyzer not initialized")
        
        # Calculate feature importance if not already done
        if global_shap_analyzer.shap_values is None:
            # Use background data for calculation
            if global_shap_analyzer.background_data is not None:
                X = global_shap_analyzer.background_data[global_shap_analyzer.feature_names].values
                if not global_shap_analyzer.calculate_shap_values(X, max_samples=500):
                    raise HTTPException(status_code=500, detail="Failed to calculate SHAP values")
            else:
                raise HTTPException(status_code=400, detail="No data available for SHAP analysis")
        
        # Get importance scores
        feature_importance = global_shap_analyzer.get_feature_importance()
        category_importance = global_shap_analyzer.get_category_importance()
        
        if not feature_importance:
            raise HTTPException(status_code=500, detail="Failed to calculate feature importance")
        
        # Get top features
        top_features = list(feature_importance.keys())[:10]
        
        # Generate insights
        insights = global_shap_analyzer._generate_insights(feature_importance, category_importance)
        
        return FeatureImportanceResponse(
            success=True,
            feature_importance=feature_importance,
            category_importance=category_importance,
            top_features=top_features,
            insights=insights,
            timestamp=datetime.now().isoformat()
        )
        
    except HTTPException:
        raise
    except Exception as e:
        logger.error(f"Error in feature importance endpoint: {str(e)}")
        raise HTTPException(status_code=500, detail=str(e))

@shap_router.get("/report")
async def generate_shap_report():
    """
    Generate comprehensive SHAP analysis report
    """
    global global_shap_analyzer
    
    try:
        # Check if analyzer is initialized
        if global_shap_analyzer is None:
            raise HTTPException(status_code=400, detail="SHAP analyzer not initialized")
        
        # Generate report
        report_path = "reports/shap_analysis_api.json"
        if not global_shap_analyzer.generate_interpretation_report(report_path):
            raise HTTPException(status_code=500, detail="Failed to generate SHAP report")
        
        # Read and return report
        if Path(report_path).exists():
            with open(report_path, 'r') as f:
                report_data = json.load(f)
            
            return {
                "success": True,
                "report": report_data,
                "report_path": report_path,
                "timestamp": datetime.now().isoformat()
            }
        else:
            raise HTTPException(status_code=500, detail="Report file not found after generation")
        
    except HTTPException:
        raise
    except Exception as e:
        logger.error(f"Error in SHAP report endpoint: {str(e)}")
        raise HTTPException(status_code=500, detail=str(e))

@shap_router.post("/batch-analyze")
async def batch_analyze(features_batch: List[List[float]], max_samples: int = 100):
    """
    Analyze multiple predictions with SHAP values
    """
    global global_shap_analyzer
    
    try:
        # Check if analyzer is initialized
        if global_shap_analyzer is None:
            raise HTTPException(status_code=400, detail="SHAP analyzer not initialized")
        
        # Limit batch size
        if len(features_batch) > max_samples:
            features_batch = features_batch[:max_samples]
        
        # Convert to numpy array
        X_batch = np.array(features_batch)
        
        # Calculate SHAP values for batch
        if not global_shap_analyzer.calculate_shap_values(X_batch):
            raise HTTPException(status_code=500, detail="Failed to calculate SHAP values for batch")
        
        # Get predictions
        predictions = global_shap_analyzer.model.predict(X_batch).tolist()
        
        # Get feature importance
        feature_importance = global_shap_analyzer.get_feature_importance()
        
        return {
            "success": True,
            "batch_size": len(features_batch),
            "predictions": predictions,
            "feature_importance": feature_importance,
            "shap_values_calculated": True,
            "timestamp": datetime.now().isoformat()
        }
        
    except HTTPException:
        raise
    except Exception as e:
        logger.error(f"Error in batch SHAP analysis: {str(e)}")
        raise HTTPException(status_code=500, detail=str(e))

# Auto-initialize SHAP analyzer on startup
async def startup_shap_init():
    """Initialize SHAP analyzer on startup"""
    try:
        await initialize_shap_analyzer()
        logger.info("SHAP analyzer auto-initialized on startup")
    except Exception as e:
        logger.warning(f"Failed to auto-initialize SHAP analyzer: {str(e)}")

# Export router for main FastAPI app
__all__ = ["shap_router", "startup_shap_init"]