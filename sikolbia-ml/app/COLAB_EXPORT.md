Colab export instructions — produce a clean SavedModel for deployment

Use this snippet inside your Colab notebook where the trained `lstm` model is available.
It saves a TensorFlow SavedModel dir (no custom dtype policies) and zips it for download.

Python cell:

```python
import tensorflow as tf
import os
from pathlib import Path

# Attempt to discover a Keras model variable in the notebook and save a clean SavedModel
export_dir = '/content/lstm_saved_for_deploy/saved_lstm'
Path(export_dir).mkdir(parents=True, exist_ok=True)

def find_keras_model():
    # Check common variable names first
    candidates = ['lstm_model', 'model', 'model_lstm', 'best_model', 'final_model']
    for n in candidates:
        if n in globals() and isinstance(globals()[n], tf.keras.Model):
            return n, globals()[n]

    # Fallback: scan globals for any tf.keras.Model instances
    for name, val in list(globals().items()):
        try:
            if isinstance(val, tf.keras.Model):
                return name, val
        except Exception:
            continue

    # As a last resort, scan stack frames' locals
    import inspect
    for frame_info in inspect.stack():
        frame = frame_info.frame
        for name, val in frame.f_locals.items():
            try:
                if isinstance(val, tf.keras.Model):
                    return name, val
            except Exception:
                continue
    return None, None

name, lstm_model = find_keras_model()
if lstm_model is None:
    raise NameError("No tf.keras.Model found in the notebook globals; assign your trained model to variable `lstm_model` and re-run this cell.")

# Clone architecture and copy weights to remove custom objects if possible
cloned = None
try:
    cloned = tf.keras.models.clone_model(lstm_model)
    cloned.set_weights(lstm_model.get_weights())
except Exception:
    cloned = None

def _export_saved_model(model, path):
    """Try multiple export strategies compatible with Keras 3 and TF.
    Returns True on success, False otherwise."""
    # 1) Prefer `model.export()` if available (Keras 3 export helper)
    try:
        if hasattr(model, 'export'):
            model.export(path)
            return True
    except Exception:
        pass

    # 2) Try tf.saved_model.save
    try:
        tf.saved_model.save(model, path)
        return True
    except Exception:
        pass

    # 3) Save as native Keras file (.keras) then reload and export
    try:
        tmp_file = '/content/lstm_saved_for_deploy/_tmp_model.keras'
        tf.keras.models.save_model(model, tmp_file)
        reloaded = tf.keras.models.load_model(tmp_file, compile=False)
        tf.saved_model.save(reloaded, path)
        return True
    except Exception:
        pass

    return False

exported = False
if cloned is not None:
    exported = _export_saved_model(cloned, export_dir)

if not exported:
    exported = _export_saved_model(lstm_model, export_dir)

if not exported:
    raise RuntimeError('Could not export model to SavedModel using available strategies; consider simplifying custom objects or using a pinned TF runtime.')

# Zip for download using Python (portable across Colab)
import shutil
shutil.make_archive('/content/lstm_saved_for_deploy', 'zip', '/content/lstm_saved_for_deploy')

print('Saved and zipped to /content/lstm_saved_for_deploy.zip')
```

Notes:

- This produces a SavedModel directory at `saved_lstm` containing `saved_model.pb`.
- Download `lstm_saved_for_deploy.zip`, extract on your host to `sikolbia-ml/ml_models/ensemble/saved_lstm_clean/` so that the path contains `saved_model.pb`.
- After extracting, either run the helper script below or ask me to restart the ML service and verify.
