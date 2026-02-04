"""
Model Management Router
Endpoints for model training, versioning, and management
"""

from fastapi import APIRouter, HTTPException, BackgroundTasks
from pydantic import BaseModel
from typing import List, Dict, Any, Optional
from datetime import datetime
from pathlib import Path
import json
import logging
import subprocess
import shutil

logger = logging.getLogger(__name__)

router = APIRouter(prefix="/model", tags=["Model Management"])


class TrainingRequest(BaseModel):
    """Request to train a new model version"""
    data_source: str = "mysql"  # mysql or csv
    csv_path: Optional[str] = None
    release_stage: str = "beta"  # alpha, beta, production
    description: Optional[str] = None


class VersionSwitchRequest(BaseModel):
    """Request to switch active model version"""
    version: str


class ModelVersionInfo(BaseModel):
    """Model version information"""
    version: str
    model_name: str
    mae: float
    rmse: float
    mape: float
    r2_score: float
    status: str
    is_active: bool
    release_stage: str
    released_at: Optional[str]
    description: Optional[str]
    training_data_count: Optional[int]


# Global variable to track training status
training_status = {
    'is_training': False,
    'current_version': None,
    'progress': 0,
    'message': ''
}


@router.get("/versions", response_model=List[ModelVersionInfo])
async def get_model_versions():
    """Get all available model versions"""
    try:
        # Read from models directory
        models_dir = Path("ml_models/models")
        versions = []
        
        if not models_dir.exists():
            return []
        
        for model_dir in models_dir.iterdir():
            if model_dir.is_dir() and model_dir.name.startswith('nbm_v'):
                metadata_file = model_dir / 'metadata.json'
                if metadata_file.exists():
                    with open(metadata_file, 'r') as f:
                        metadata = json.load(f)
                        
                    # Check if this is the active version
                    # This should ideally be checked against the database
                    # For now, we'll mark the latest as active
                    is_active = model_dir.name == "nbm_google_colab"  # Legacy active model
                    
                    versions.append({
                        'version': metadata.get('version', model_dir.name.replace('nbm_', '')),
                        'model_name': 'LSTM Enhanced Ensemble',
                        'mae': metadata.get('metrics', {}).get('mae', 0),
                        'rmse': metadata.get('metrics', {}).get('rmse', 0),
                        'mape': metadata.get('metrics', {}).get('mape', 0),
                        'r2_score': metadata.get('metrics', {}).get('r2', 0),
                        'status': 'active' if is_active else 'completed',
                        'is_active': is_active,
                        'release_stage': metadata.get('release_stage', 'beta'),
                        'released_at': metadata.get('trained_at'),
                        'description': metadata.get('description'),
                        'training_data_count': metadata.get('training_info', {}).get('total_records')
                    })
        
        # Sort by version descending
        versions.sort(key=lambda x: x['version'], reverse=True)
        
        return versions
        
    except Exception as e:
        logger.error(f"Error getting model versions: {str(e)}")
        raise HTTPException(status_code=500, detail=str(e))


@router.get("/active")
async def get_active_model():
    """Get currently active model version"""
    try:
        versions = await get_model_versions()
        active = next((v for v in versions if v['is_active']), None)
        
        if not active:
            raise HTTPException(status_code=404, detail="No active model found")
        
        return active
        
    except HTTPException:
        raise
    except Exception as e:
        logger.error(f"Error getting active model: {str(e)}")
        raise HTTPException(status_code=500, detail=str(e))


@router.post("/switch")
async def switch_model_version(request: VersionSwitchRequest):
    """Switch to a different model version"""
    try:
        logger.info(f"Switching to model version {request.version}")
        
        # Check if version exists
        version_dir = Path(f"ml_models/models/nbm_{request.version}")
        if not version_dir.exists():
            raise HTTPException(status_code=404, detail=f"Version {request.version} not found")
        
        # Check if all required files exist
        required_files = [
            'model_lstm.keras',
            'model_xgb.pkl',
            'model_huber.pkl',
            'scaler_X.pkl',
            'scaler_y.pkl',
            'label_encoder.pkl',
            'ensemble_config.pkl'
        ]
        
        for file in required_files:
            if not (version_dir / file).exists():
                raise HTTPException(
                    status_code=400,
                    detail=f"Model version incomplete: missing {file}"
                )
        
        # Update symlink or active marker
        active_link = Path("ml_models/models/active")
        if active_link.exists() or active_link.is_symlink():
            active_link.unlink()
        
        # Create symlink to new active version (Windows: requires admin or dev mode)
        try:
            active_link.symlink_to(version_dir, target_is_directory=True)
        except OSError:
            # Fallback: copy to 'active' directory if symlink fails
            if active_link.exists():
                shutil.rmtree(active_link)
            shutil.copytree(version_dir, active_link)
        
        logger.info(f"Successfully switched to version {request.version}")
        
        # Reload the predictor (would need to restart or reload dynamically)
        # For now, return success - requires FastAPI restart to take effect
        
        return {
            'success': True,
            'message': f'Switched to version {request.version}. Restart API for changes to take effect.',
            'version': request.version
        }
        
    except HTTPException:
        raise
    except Exception as e:
        logger.error(f"Error switching model version: {str(e)}")
        raise HTTPException(status_code=500, detail=str(e))


@router.get("/training/status")
async def get_training_status():
    """Get current training status"""
    return training_status


def train_model_background(version: str, data_path: str, output_dir: str):
    """Background task to train model"""
    global training_status
    
    try:
        training_status['is_training'] = True
        training_status['current_version'] = version
        training_status['progress'] = 10
        training_status['message'] = 'Starting training...'
        
        # Run training script
        cmd = [
            'python',
            'training/train_nbm_model.py',
            '--version', version,
            '--data', data_path,
            '--output', output_dir
        ]
        
        logger.info(f"Running command: {' '.join(cmd)}")
        
        training_status['progress'] = 30
        training_status['message'] = 'Training models...'
        
        result = subprocess.run(
            cmd,
            capture_output=True,
            text=True,
            check=True
        )
        
        training_status['progress'] = 90
        training_status['message'] = 'Saving models...'
        
        # Parse result
        output = json.loads(result.stdout)
        
        training_status['progress'] = 100
        training_status['message'] = 'Training completed!'
        training_status['is_training'] = False
        
        logger.info(f"Training completed successfully: {output}")
        
    except subprocess.CalledProcessError as e:
        logger.error(f"Training failed: {e.stderr}")
        training_status['is_training'] = False
        training_status['message'] = f'Training failed: {e.stderr}'
    except Exception as e:
        logger.error(f"Training error: {str(e)}")
        training_status['is_training'] = False
        training_status['message'] = f'Training error: {str(e)}'


@router.post("/train")
async def train_new_model(request: TrainingRequest, background_tasks: BackgroundTasks):
    """
    Train a new model version
    This will create a new versioned model based on latest data
    """
    try:
        if training_status['is_training']:
            raise HTTPException(status_code=409, detail="Training already in progress")
        
        # Get next version number
        versions = await get_model_versions()
        if versions:
            latest = versions[0]['version']
            # Parse version and increment patch
            parts = latest.replace('v', '').split('.')
            major, minor, patch = int(parts[0]), int(parts[1]), int(parts[2])
            new_version = f"v{major}.{minor}.{patch + 1}"
        else:
            new_version = "v1.0.0"
        
        logger.info(f"Training new model version: {new_version}")
        
        # Determine data source
        if request.data_source == "csv" and request.csv_path:
            data_path = request.csv_path
        else:
            # Export from MySQL first (would need Laravel endpoint)
            data_path = "data/transaksi_nbms_export.csv"
            # TODO: Call Laravel API to export latest data
        
        # Check if data file exists
        if not Path(data_path).exists():
            raise HTTPException(
                status_code=400,
                detail=f"Training data not found: {data_path}"
            )
        
        # Start training in background
        output_dir = "ml_models/models"
        background_tasks.add_task(
            train_model_background,
            new_version,
            data_path,
            output_dir
        )
        
        return {
            'success': True,
            'message': f'Training started for version {new_version}',
            'version': new_version,
            'status': 'training'
        }
        
    except HTTPException:
        raise
    except Exception as e:
        logger.error(f"Error starting training: {str(e)}")
        raise HTTPException(status_code=500, detail=str(e))


@router.delete("/versions/{version}")
async def delete_model_version(version: str):
    """Delete a model version (archive)"""
    try:
        # Check if it's the active version
        active = await get_active_model()
        if active['version'] == version:
            raise HTTPException(
                status_code=400,
                detail="Cannot delete active model version"
            )
        
        # Delete model directory
        version_dir = Path(f"ml_models/models/nbm_{version}")
        if not version_dir.exists():
            raise HTTPException(status_code=404, detail=f"Version {version} not found")
        
        # Move to archive instead of deleting
        archive_dir = Path("ml_models/archive")
        archive_dir.mkdir(exist_ok=True)
        
        shutil.move(str(version_dir), str(archive_dir / f"nbm_{version}"))
        
        logger.info(f"Archived model version {version}")
        
        return {
            'success': True,
            'message': f'Version {version} archived successfully'
        }
        
    except HTTPException:
        raise
    except Exception as e:
        logger.error(f"Error archiving model version: {str(e)}")
        raise HTTPException(status_code=500, detail=str(e))
