"""Resave ensemble components to deploy-friendly formats.

Run this in your Colab / training environment where the original
`lstm_enhanced_ensemble.joblib` was created. It will produce:
- xgboost_model.json          (XGBoost native JSON)
- huber_params.json           (HuberRegressor coef/intercept/params)
- scaler_X_mean.npy / scaler_X_scale.npy
- scaler_y_mean.npy / scaler_y_scale.npy
- lstm_saved/                 (tf.keras saved model directory)
- ensemble_manifest.json      (describes files and weights)

Usage (in Colab):
python resave_for_deploy.py --input ./lstm_enhanced_ensemble.joblib --out ./deployed

This script avoids custom pickled wrapper classes so the model can be
reconstructed safely in the deployment container.
"""
import os
import json
import argparse
import joblib
import numpy as np

def save_xgboost(xgb_obj, out_dir):
    try:
        # If scikit-learn wrapper
        booster = None
        if hasattr(xgb_obj, 'get_booster'):
            booster = xgb_obj.get_booster()
        elif hasattr(xgb_obj, 'booster_'):
            booster = xgb_obj.booster_

        if booster is not None:
            path = os.path.join(out_dir, 'xgboost_model.json')
            booster.save_model(path)
            return {'type': 'xgboost_json', 'path': 'xgboost_model.json'}
    except Exception:
        pass
    # Fallback: joblib dump
    path = os.path.join(out_dir, 'xgboost_component.joblib')
    joblib.dump(xgb_obj, path)
    return {'type': 'xgboost_joblib', 'path': 'xgboost_component.joblib'}

def save_huber(huber_obj, out_dir):
    data = {'coef': None, 'intercept': None, 'params': {}}
    try:
        data['coef'] = np.array(huber_obj.coef_).tolist()
        data['intercept'] = float(huber_obj.intercept_)
    except Exception:
        # fallback to joblib
        path = os.path.join(out_dir, 'huber_component.joblib')
        joblib.dump(huber_obj, path)
        return {'type': 'huber_joblib', 'path': 'huber_component.joblib'}

    # save params
    params = {}
    for k, v in huber_obj.get_params().items():
        try:
            json.dumps({k: v})
            params[k] = v
        except Exception:
            params[k] = str(v)

    data['params'] = params
    with open(os.path.join(out_dir, 'huber_params.json'), 'w') as fh:
        json.dump(data, fh)
    return {'type': 'huber_params', 'path': 'huber_params.json'}

def save_scaler(scaler_obj, prefix, out_dir):
    try:
        mean = np.array(getattr(scaler_obj, 'mean_', None))
        scale = np.array(getattr(scaler_obj, 'scale_', None))
        if mean is not None:
            np.save(os.path.join(out_dir, f"{prefix}_mean.npy"), mean)
        if scale is not None:
            np.save(os.path.join(out_dir, f"{prefix}_scale.npy"), scale)
        return {'type': 'scaler_npy', 'mean': f"{prefix}_mean.npy", 'scale': f"{prefix}_scale.npy"}
    except Exception:
        path = os.path.join(out_dir, f"{prefix}.joblib")
        joblib.dump(scaler_obj, path)
        return {'type': 'scaler_joblib', 'path': f"{prefix}.joblib"}

def save_lstm(lstm_obj, out_dir):
    try:
        lstm_dir = os.path.join(out_dir, 'lstm_saved')
        os.makedirs(lstm_dir, exist_ok=True)
        lstm_obj.save(lstm_dir)
        return {'type': 'keras_saved', 'path': 'lstm_saved'}
    except Exception:
        # try joblib fallback (rare for keras)
        path = os.path.join(out_dir, 'lstm_component.joblib')
        joblib.dump(lstm_obj, path)
        return {'type': 'lstm_joblib', 'path': 'lstm_component.joblib'}

def main(input_path, out_dir):
    os.makedirs(out_dir, exist_ok=True)
    ensemble = joblib.load(input_path)

    manifest = {'components': {}, 'weights': {}, 'feature_count': None}

    # Heuristics to find components on the ensemble object
    candidates = {}
    for name in ['xgboost', 'huber', 'lstm', 'scaler_X', 'scaler_y', 'model_info']:
        if hasattr(ensemble, name):
            candidates[name] = getattr(ensemble, name)

    # Also check dict-like attributes
    for attr in ['estimators_', 'components_', 'models', 'parts']:
        if hasattr(ensemble, attr):
            obj = getattr(ensemble, attr)
            try:
                for k, v in dict(obj).items():
                    candidates.setdefault(k, v)
            except Exception:
                pass

    # Save components
    if 'xgboost' in candidates:
        manifest['components']['xgboost'] = save_xgboost(candidates['xgboost'], out_dir)
    if 'huber' in candidates:
        manifest['components']['huber'] = save_huber(candidates['huber'], out_dir)
    if 'scaler_X' in candidates:
        manifest['components']['scaler_X'] = save_scaler(candidates['scaler_X'], 'scaler_X', out_dir)
    if 'scaler_y' in candidates:
        manifest['components']['scaler_y'] = save_scaler(candidates['scaler_y'], 'scaler_y', out_dir)
    if 'lstm' in candidates:
        manifest['components']['lstm'] = save_lstm(candidates['lstm'], out_dir)

    # Try to capture weights and metadata
    info = None
    if hasattr(ensemble, 'model_info'):
        info = getattr(ensemble, 'model_info')
    elif hasattr(ensemble, 'metadata'):
        info = getattr(ensemble, 'metadata')
    if info is not None:
        manifest['model_info'] = info
        try:
            if 'model_architecture' in info and 'ensemble_weights' in info['model_architecture']:
                manifest['weights'] = info['model_architecture']['ensemble_weights']
        except Exception:
            pass

    # Fallback: if ensemble has attribute 'weights'
    if hasattr(ensemble, 'weights') and not manifest['weights']:
        manifest['weights'] = getattr(ensemble, 'weights')

    # Save manifest
    with open(os.path.join(out_dir, 'ensemble_manifest.json'), 'w') as fh:
        json.dump(manifest, fh, indent=2)

    print('Resave complete. Files written to', out_dir)

if __name__ == '__main__':
    parser = argparse.ArgumentParser()
    parser.add_argument('--input', '-i', required=True, help='Path to original ensemble joblib')
    parser.add_argument('--out', '-o', required=True, help='Output directory for deploy files')
    args = parser.parse_args()
    main(args.input, args.out)
