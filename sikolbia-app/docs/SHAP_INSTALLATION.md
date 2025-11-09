# SHAP Analysis Installation and Requirements

## Dependencies for SHAP Analysis

### Python Requirements
```bash
# Install SHAP and required packages
pip install shap>=0.43.0
pip install scikit-learn>=1.3.0
pip install matplotlib>=3.7.0
pip install seaborn>=0.12.0
pip install pandas>=2.0.0
pip install numpy>=1.24.0
```

### Optional Dependencies
```bash
# For visualization enhancements
pip install plotly>=5.15.0
pip install ipywidgets>=8.0.0

# For deep learning model explanations
pip install torch>=2.0.0
pip install tensorflow>=2.13.0
```

## Installation Script

Create a requirements file for SHAP analysis:

```bash
# Create requirements-shap.txt
cat > requirements-shap.txt << EOF
shap>=0.43.0
scikit-learn>=1.3.0
matplotlib>=3.7.0
seaborn>=0.12.0
pandas>=2.0.0
numpy>=1.24.0
plotly>=5.15.0
EOF

# Install using pip
pip install -r requirements-shap.txt
```

## Docker Integration

Add SHAP dependencies to the FastAPI Dockerfile:

```dockerfile
# In fastapi/Dockerfile, add to requirements.txt:
RUN pip install shap>=0.43.0 matplotlib>=3.7.0 seaborn>=0.12.0
```

## Model Compatibility

SHAP supports various model types:

### Tree-based Models (Recommended)
- Random Forest
- XGBoost
- LightGBM
- Decision Trees
- Extra Trees

### Linear Models
- Linear Regression
- Logistic Regression
- Ridge/Lasso Regression

### Deep Learning Models
- Neural Networks (with KernelExplainer)
- TensorFlow/Keras models
- PyTorch models

## Performance Considerations

### For Production Use:
1. **Background Data Sampling**: Use 100-500 background samples
2. **Batch Processing**: Limit SHAP calculations to 1000 samples max
3. **Caching**: Cache SHAP values for frequently analyzed data
4. **Async Processing**: Use background tasks for large SHAP calculations

### Memory Requirements:
- **Small models** (<100 features): 2-4 GB RAM
- **Medium models** (100-1000 features): 4-8 GB RAM
- **Large models** (>1000 features): 8+ GB RAM

## API Rate Limiting

Recommended limits for SHAP endpoints:
- `/shap/analyze`: 10 requests/minute
- `/shap/batch-analyze`: 2 requests/minute
- `/shap/feature-importance`: 5 requests/minute

## Security Considerations

1. **Input Validation**: Validate all feature inputs
2. **Resource Limits**: Set memory and time limits for SHAP calculations
3. **Access Control**: Restrict SHAP analysis to authorized users
4. **Data Privacy**: Ensure SHAP explanations don't leak sensitive data

## Monitoring and Logging

Enable comprehensive logging for SHAP operations:

```python
# Configure SHAP logging
import logging
logging.getLogger('shap').setLevel(logging.INFO)
```

Monitor SHAP performance metrics:
- Calculation time per explanation
- Memory usage during analysis
- Success/failure rates
- Feature importance stability

## Troubleshooting

### Common Issues:

1. **Memory Errors**: Reduce background sample size
2. **Slow Performance**: Use TreeExplainer for tree models
3. **Model Compatibility**: Check if model supports SHAP directly
4. **Feature Mismatch**: Ensure feature names match training data

### Debug Mode:

Enable debug logging for troubleshooting:

```python
import logging
logging.basicConfig(level=logging.DEBUG)
```

## Testing SHAP Installation

```bash
# Test SHAP installation
python -c "
import shap
import numpy as np
from sklearn.ensemble import RandomForestRegressor

# Create test data
X = np.random.randn(100, 10)
y = np.sum(X, axis=1)

# Train model
model = RandomForestRegressor(n_estimators=10)
model.fit(X, y)

# Test SHAP
explainer = shap.TreeExplainer(model)
shap_values = explainer.shap_values(X[:5])
print('SHAP installation successful!')
print(f'SHAP values shape: {shap_values.shape}')
"
```

## Integration with SIKOLBIA

The SHAP analysis is integrated into the SIKOLBIA system:

1. **FastAPI Integration**: `fastapi/shap_endpoints.py`
2. **Frontend Dashboard**: `resources/views/admin/prediction-dashboard-shap.blade.php`
3. **Model Analyzer**: `ml_models/shap_analyzer.py`

## Production Deployment

For production deployment:

1. **Pre-calculate**: Generate feature importance during model training
2. **Cache Results**: Store SHAP explanations in Redis
3. **Async Processing**: Use Celery/RQ for background SHAP calculations
4. **Resource Monitoring**: Monitor CPU/memory usage for SHAP operations