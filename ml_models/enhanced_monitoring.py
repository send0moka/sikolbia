#!/usr/bin/env python3
"""
Enhanced Model Monitoring System with Real-time Drift Detection
Extensions to the existing model_monitor.py with advanced features
"""

import numpy as np
import pandas as pd
import json
import logging
from datetime import datetime, timedelta
from typing import Dict, List, Any, Optional
from dataclasses import dataclass, asdict
from collections import deque, defaultdict
import redis
import warnings
warnings.filterwarnings('ignore')

@dataclass
class DriftMetrics:
    """Data drift detection metrics"""
    timestamp: str
    prediction_drift_score: float
    feature_drift_score: float
    statistical_distance: float
    drift_detected: bool
    confidence_degradation: float
    samples_compared: int

@dataclass
class PerformanceProfile:
    """Real-time performance profiling"""
    timestamp: str
    avg_response_time: float
    p95_response_time: float
    p99_response_time: float
    requests_per_minute: float
    concurrent_requests: int
    memory_usage_trend: List[float]
    cpu_usage_trend: List[float]

class EnhancedModelMonitor:
    """Enhanced monitoring with drift detection and real-time profiling"""
    
    def __init__(self, redis_client=None, window_size=1000):
        self.redis_client = redis_client
        self.window_size = window_size
        
        # Real-time data buffers
        self.prediction_buffer = deque(maxlen=window_size)
        self.feature_buffer = deque(maxlen=window_size)
        self.response_time_buffer = deque(maxlen=1000)
        
        # Baseline data for drift detection
        self.baseline_predictions = None
        self.baseline_features = None
        
        # Performance monitoring
        self.request_timestamps = deque(maxlen=1000)
        self.concurrent_count = 0
        
        # Alert thresholds
        self.drift_thresholds = {
            'prediction_drift': 0.3,
            'feature_drift': 0.25,
            'confidence_degradation': 0.2
        }
        
        self.logger = logging.getLogger('EnhancedMonitor')
    
    def set_baseline_data(self, predictions: List[float], features: List[Dict]):
        """Set baseline data for drift detection"""
        self.baseline_predictions = np.array(predictions)
        
        # Extract feature statistics for drift detection
        feature_stats = defaultdict(list)
        for feature_dict in features:
            for key, value in feature_dict.items():
                if isinstance(value, (int, float)):
                    feature_stats[key].append(value)
        
        self.baseline_features = {k: np.array(v) for k, v in feature_stats.items()}
        
        self.logger.info(f"Baseline set with {len(predictions)} predictions and {len(self.baseline_features)} features")
    
    def update_buffers(self, prediction: float, features: Dict, response_time: float):
        """Update monitoring buffers with new data"""
        current_time = datetime.now()
        
        # Update buffers
        self.prediction_buffer.append({
            'timestamp': current_time.isoformat(),
            'value': prediction
        })
        
        self.feature_buffer.append({
            'timestamp': current_time.isoformat(),
            'features': features
        })
        
        self.response_time_buffer.append(response_time)
        self.request_timestamps.append(current_time)
    
    def calculate_ks_drift(self, current_data: np.ndarray, baseline_data: np.ndarray) -> float:
        """Calculate drift using Kolmogorov-Smirnov test"""
        try:
            from scipy import stats
            if len(current_data) < 10 or len(baseline_data) < 10:
                return 0.0
            
            # Normalize data
            current_norm = (current_data - np.mean(baseline_data)) / (np.std(baseline_data) + 1e-8)
            baseline_norm = (baseline_data - np.mean(baseline_data)) / (np.std(baseline_data) + 1e-8)
            
            # Perform KS test
            ks_stat, p_value = stats.ks_2samp(current_norm, baseline_norm)
            
            # Convert to drift score (0-1)
            drift_score = min(1.0, ks_stat * 2)
            
            return drift_score
            
        except Exception as e:
            self.logger.error(f"KS drift calculation failed: {e}")
            return 0.0
    
    def calculate_wasserstein_distance(self, current_data: np.ndarray, baseline_data: np.ndarray) -> float:
        """Calculate Wasserstein distance for drift detection"""
        try:
            from scipy import stats
            if len(current_data) < 5 or len(baseline_data) < 5:
                return 0.0
            
            # Calculate 1-Wasserstein distance
            distance = stats.wasserstein_distance(current_data, baseline_data)
            
            # Normalize by data scale
            data_scale = np.std(np.concatenate([current_data, baseline_data])) + 1e-8
            normalized_distance = distance / data_scale
            
            return min(1.0, normalized_distance)
            
        except Exception as e:
            self.logger.error(f"Wasserstein distance calculation failed: {e}")
            return 0.0
    
    def detect_prediction_drift(self, window_size: int = 100) -> DriftMetrics:
        """Detect drift in predictions"""
        if len(self.prediction_buffer) < window_size or self.baseline_predictions is None:
            return DriftMetrics(
                timestamp=datetime.now().isoformat(),
                prediction_drift_score=0.0,
                feature_drift_score=0.0,
                statistical_distance=0.0,
                drift_detected=False,
                confidence_degradation=0.0,
                samples_compared=0
            )
        
        # Get recent predictions
        recent_data = list(self.prediction_buffer)[-window_size:]
        recent_predictions = np.array([item['value'] for item in recent_data])
        
        # Calculate drift metrics
        ks_drift = self.calculate_ks_drift(recent_predictions, self.baseline_predictions)
        wasserstein_dist = self.calculate_wasserstein_distance(recent_predictions, self.baseline_predictions)
        
        # Calculate confidence degradation
        recent_std = np.std(recent_predictions)
        baseline_std = np.std(self.baseline_predictions)
        confidence_degradation = abs(recent_std - baseline_std) / (baseline_std + 1e-8)
        
        # Determine if drift is detected
        drift_detected = (
            ks_drift > self.drift_thresholds['prediction_drift'] or
            confidence_degradation > self.drift_thresholds['confidence_degradation']
        )
        
        return DriftMetrics(
            timestamp=datetime.now().isoformat(),
            prediction_drift_score=ks_drift,
            feature_drift_score=0.0,  # Calculated separately
            statistical_distance=wasserstein_dist,
            drift_detected=drift_detected,
            confidence_degradation=confidence_degradation,
            samples_compared=min(len(recent_predictions), len(self.baseline_predictions))
        )
    
    def detect_feature_drift(self, window_size: int = 100) -> float:
        """Detect drift in input features"""
        if len(self.feature_buffer) < window_size or not self.baseline_features:
            return 0.0
        
        # Get recent features
        recent_data = list(self.feature_buffer)[-window_size:]
        
        total_drift = 0.0
        feature_count = 0
        
        for feature_name, baseline_values in self.baseline_features.items():
            # Extract feature values from recent data
            recent_values = []
            for item in recent_data:
                if feature_name in item['features']:
                    value = item['features'][feature_name]
                    if isinstance(value, (int, float)):
                        recent_values.append(value)
            
            if len(recent_values) < 10:
                continue
            
            recent_array = np.array(recent_values)
            drift_score = self.calculate_ks_drift(recent_array, baseline_values)
            total_drift += drift_score
            feature_count += 1
        
        return total_drift / max(1, feature_count)
    
    def calculate_performance_profile(self) -> PerformanceProfile:
        """Calculate real-time performance profile"""
        current_time = datetime.now()
        
        # Calculate request rate
        minute_ago = current_time - timedelta(minutes=1)
        recent_requests = [ts for ts in self.request_timestamps if ts >= minute_ago]
        requests_per_minute = len(recent_requests)
        
        # Calculate response time percentiles
        if self.response_time_buffer:
            response_times = list(self.response_time_buffer)
            avg_response = np.mean(response_times)
            p95_response = np.percentile(response_times, 95)
            p99_response = np.percentile(response_times, 99)
        else:
            avg_response = p95_response = p99_response = 0.0
        
        # Get system metrics (if available)
        memory_trend = []
        cpu_trend = []
        
        try:
            import psutil
            memory_trend = [psutil.virtual_memory().percent]
            cpu_trend = [psutil.cpu_percent()]
        except ImportError:
            pass
        
        return PerformanceProfile(
            timestamp=current_time.isoformat(),
            avg_response_time=avg_response,
            p95_response_time=p95_response,
            p99_response_time=p99_response,
            requests_per_minute=requests_per_minute,
            concurrent_requests=self.concurrent_count,
            memory_usage_trend=memory_trend,
            cpu_usage_trend=cpu_trend
        )
    
    def generate_enhanced_metrics(self) -> Dict[str, Any]:
        """Generate comprehensive enhanced metrics"""
        # Calculate drift metrics
        drift_metrics = self.detect_prediction_drift()
        feature_drift = self.detect_feature_drift()
        
        # Update drift metrics with feature drift
        drift_metrics.feature_drift_score = feature_drift
        if feature_drift > self.drift_thresholds['feature_drift']:
            drift_metrics.drift_detected = True
        
        # Calculate performance profile
        performance = self.calculate_performance_profile()
        
        # Generate summary
        enhanced_metrics = {
            'drift_detection': asdict(drift_metrics),
            'performance_profile': asdict(performance),
            'buffer_status': {
                'prediction_buffer_size': len(self.prediction_buffer),
                'feature_buffer_size': len(self.feature_buffer),
                'response_time_buffer_size': len(self.response_time_buffer),
                'baseline_available': self.baseline_predictions is not None
            },
            'alert_status': {
                'drift_alert': drift_metrics.drift_detected,
                'performance_alert': performance.p95_response_time > 1000,  # 1 second threshold
                'high_load_alert': performance.requests_per_minute > 100
            },
            'timestamp': datetime.now().isoformat()
        }
        
        return enhanced_metrics
    
    def store_metrics_redis(self, metrics: Dict[str, Any]):
        """Store enhanced metrics in Redis"""
        if not self.redis_client:
            return
        
        try:
            # Store current metrics
            key = f"enhanced_metrics:{datetime.now().strftime('%Y%m%d_%H%M')}"
            self.redis_client.setex(key, timedelta(hours=24), json.dumps(metrics))
            
            # Store drift detection results
            drift_key = f"drift_detection:{datetime.now().strftime('%Y%m%d')}"
            self.redis_client.lpush(drift_key, json.dumps(metrics['drift_detection']))
            self.redis_client.expire(drift_key, timedelta(days=7))
            
            # Store performance metrics
            perf_key = f"performance:{datetime.now().strftime('%Y%m%d_%H')}"
            self.redis_client.lpush(perf_key, json.dumps(metrics['performance_profile']))
            self.redis_client.expire(perf_key, timedelta(days=3))
            
        except Exception as e:
            self.logger.error(f"Failed to store metrics in Redis: {e}")
    
    def get_drift_history(self, hours: int = 24) -> List[Dict[str, Any]]:
        """Get drift detection history"""
        history = []
        
        if self.redis_client:
            try:
                for hour_offset in range(hours):
                    timestamp = datetime.now() - timedelta(hours=hour_offset)
                    key = f"drift_detection:{timestamp.strftime('%Y%m%d')}"
                    
                    data = self.redis_client.lrange(key, 0, -1)
                    for item in data:
                        try:
                            history.append(json.loads(item))
                        except json.JSONDecodeError:
                            continue
                            
            except Exception as e:
                self.logger.error(f"Failed to get drift history: {e}")
        
        return sorted(history, key=lambda x: x['timestamp'])
    
    def generate_monitoring_report(self) -> Dict[str, Any]:
        """Generate comprehensive monitoring report"""
        enhanced_metrics = self.generate_enhanced_metrics()
        drift_history = self.get_drift_history(24)
        
        # Calculate trends
        if drift_history:
            recent_drift_scores = [item['prediction_drift_score'] for item in drift_history[-10:]]
            drift_trend = "increasing" if len(recent_drift_scores) > 1 and recent_drift_scores[-1] > recent_drift_scores[0] else "stable"
        else:
            drift_trend = "unknown"
        
        report = {
            'report_timestamp': datetime.now().isoformat(),
            'current_status': enhanced_metrics,
            'drift_history_24h': drift_history,
            'trends': {
                'drift_trend': drift_trend,
                'performance_trend': 'stable',  # Could be enhanced with more analysis
                'request_volume_trend': 'normal'
            },
            'recommendations': self._generate_recommendations(enhanced_metrics),
            'summary': {
                'overall_health': 'good' if not enhanced_metrics['alert_status']['drift_alert'] else 'attention_required',
                'drift_detected': enhanced_metrics['alert_status']['drift_alert'],
                'performance_issues': enhanced_metrics['alert_status']['performance_alert'],
                'monitoring_coverage': 'comprehensive'
            }
        }
        
        return report
    
    def _generate_recommendations(self, metrics: Dict[str, Any]) -> List[str]:
        """Generate recommendations based on monitoring results"""
        recommendations = []
        
        # Drift recommendations
        if metrics['alert_status']['drift_alert']:
            recommendations.append("Model drift detected. Consider retraining with recent data.")
            recommendations.append("Review input data quality and feature distributions.")
        
        # Performance recommendations
        if metrics['alert_status']['performance_alert']:
            recommendations.append("High response times detected. Consider scaling resources.")
            recommendations.append("Review model inference optimization opportunities.")
        
        # Load recommendations
        if metrics['alert_status']['high_load_alert']:
            recommendations.append("High request volume. Monitor system capacity.")
            recommendations.append("Consider implementing request throttling or load balancing.")
        
        # General recommendations
        if not recommendations:
            recommendations.append("System operating normally. Continue monitoring.")
            recommendations.append("Regular model performance evaluation recommended.")
        
        return recommendations

# Enhanced monitoring integration functions
def create_enhanced_monitor(redis_client=None) -> EnhancedModelMonitor:
    """Create enhanced monitor instance"""
    return EnhancedModelMonitor(redis_client=redis_client)

def setup_baseline_from_logs(monitor: EnhancedModelMonitor, log_file: str):
    """Setup baseline data from prediction logs"""
    try:
        predictions = []
        features = []
        
        with open(log_file, 'r') as f:
            for line in f:
                try:
                    log_entry = json.loads(line.strip())
                    predictions.append(log_entry.get('prediction', 0))
                    features.append(log_entry.get('input_features', {}))
                except json.JSONDecodeError:
                    continue
        
        if predictions:
            monitor.set_baseline_data(predictions, features)
            return True
        else:
            logging.warning("No valid prediction data found in log file")
            return False
            
    except FileNotFoundError:
        logging.warning(f"Log file {log_file} not found")
        return False
    except Exception as e:
        logging.error(f"Failed to setup baseline: {e}")
        return False