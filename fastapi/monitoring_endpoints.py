#!/usr/bin/env python3
"""
Enhanced FastAPI Monitoring Endpoints
Real-time monitoring, drift detection, and performance analytics
"""

from fastapi import APIRouter, HTTPException, BackgroundTasks
from pydantic import BaseModel, Field
from typing import Dict, List, Any, Optional
import json
import logging
from datetime import datetime, timedelta
import asyncio

# Enhanced monitoring models
class MonitoringMetrics(BaseModel):
    """Enhanced monitoring metrics response"""
    timestamp: str
    drift_detection: Dict[str, Any]
    performance_profile: Dict[str, Any]
    buffer_status: Dict[str, Any]
    alert_status: Dict[str, Any]
    health_score: float = Field(..., ge=0, le=100, description="Overall health score (0-100)")

class DriftAlert(BaseModel):
    """Drift detection alert"""
    timestamp: str
    alert_type: str
    severity: str
    drift_score: float
    threshold: float
    message: str
    recommendations: List[str]

class PerformanceMetrics(BaseModel):
    """Performance monitoring metrics"""
    timestamp: str
    avg_response_time: float
    p95_response_time: float
    p99_response_time: float
    requests_per_minute: float
    error_rate: float
    concurrent_requests: int
    system_resources: Dict[str, float]

class MonitoringReport(BaseModel):
    """Comprehensive monitoring report"""
    report_timestamp: str
    monitoring_period_hours: int
    overall_health: str
    drift_detected: bool
    performance_issues: bool
    trends: Dict[str, str]
    recommendations: List[str]
    detailed_metrics: Dict[str, Any]

# Create monitoring router
monitoring_router = APIRouter(prefix="/monitoring", tags=["Enhanced Monitoring"])

# Global variables (to be set by main application)
enhanced_monitor = None
redis_client = None

def set_monitoring_instances(monitor, redis_conn):
    """Set global monitoring instances"""
    global enhanced_monitor, redis_client
    enhanced_monitor = monitor
    redis_client = redis_conn

@monitoring_router.get("/metrics", response_model=MonitoringMetrics)
async def get_enhanced_metrics():
    """Get comprehensive enhanced monitoring metrics"""
    if not enhanced_monitor:
        raise HTTPException(status_code=503, detail="Enhanced monitoring not available")
    
    try:
        # Generate enhanced metrics
        metrics = enhanced_monitor.generate_enhanced_metrics()
        
        # Calculate health score
        health_score = calculate_health_score(metrics)
        
        return MonitoringMetrics(
            timestamp=metrics['timestamp'],
            drift_detection=metrics['drift_detection'],
            performance_profile=metrics['performance_profile'],
            buffer_status=metrics['buffer_status'],
            alert_status=metrics['alert_status'],
            health_score=health_score
        )
        
    except Exception as e:
        logging.error(f"Failed to get enhanced metrics: {e}")
        raise HTTPException(status_code=500, detail="Failed to retrieve monitoring metrics")

@monitoring_router.get("/drift/status")
async def get_drift_status():
    """Get current drift detection status"""
    if not enhanced_monitor:
        raise HTTPException(status_code=503, detail="Enhanced monitoring not available")
    
    try:
        drift_metrics = enhanced_monitor.detect_prediction_drift()
        feature_drift = enhanced_monitor.detect_feature_drift()
        
        status = {
            "timestamp": datetime.now().isoformat(),
            "prediction_drift": {
                "score": drift_metrics.prediction_drift_score,
                "threshold": enhanced_monitor.drift_thresholds['prediction_drift'],
                "detected": drift_metrics.drift_detected,
                "samples_compared": drift_metrics.samples_compared
            },
            "feature_drift": {
                "score": feature_drift,
                "threshold": enhanced_monitor.drift_thresholds['feature_drift'],
                "detected": feature_drift > enhanced_monitor.drift_thresholds['feature_drift']
            },
            "overall_drift_detected": drift_metrics.drift_detected or (feature_drift > enhanced_monitor.drift_thresholds['feature_drift']),
            "confidence_degradation": drift_metrics.confidence_degradation,
            "statistical_distance": drift_metrics.statistical_distance
        }
        
        return status
        
    except Exception as e:
        logging.error(f"Failed to get drift status: {e}")
        raise HTTPException(status_code=500, detail="Failed to retrieve drift status")

@monitoring_router.get("/drift/history")
async def get_drift_history(hours: int = 24):
    """Get drift detection history"""
    if not enhanced_monitor:
        raise HTTPException(status_code=503, detail="Enhanced monitoring not available")
    
    try:
        history = enhanced_monitor.get_drift_history(hours)
        
        return {
            "period_hours": hours,
            "total_samples": len(history),
            "drift_events": [item for item in history if item.get('drift_detected', False)],
            "history": history,
            "summary": {
                "avg_prediction_drift": sum(item.get('prediction_drift_score', 0) for item in history) / max(1, len(history)),
                "avg_feature_drift": sum(item.get('feature_drift_score', 0) for item in history) / max(1, len(history)),
                "drift_event_count": len([item for item in history if item.get('drift_detected', False)])
            }
        }
        
    except Exception as e:
        logging.error(f"Failed to get drift history: {e}")
        raise HTTPException(status_code=500, detail="Failed to retrieve drift history")

@monitoring_router.get("/performance", response_model=PerformanceMetrics)
async def get_performance_metrics():
    """Get detailed performance metrics"""
    if not enhanced_monitor:
        raise HTTPException(status_code=503, detail="Enhanced monitoring not available")
    
    try:
        performance = enhanced_monitor.calculate_performance_profile()
        
        # Calculate error rate (simplified)
        error_rate = 0.0  # Would be calculated from actual error logs
        
        # Get system resources
        system_resources = {}
        try:
            import psutil
            system_resources = {
                "cpu_percent": psutil.cpu_percent(),
                "memory_percent": psutil.virtual_memory().percent,
                "disk_percent": psutil.disk_usage('/').percent
            }
        except ImportError:
            system_resources = {"note": "psutil not available"}
        
        return PerformanceMetrics(
            timestamp=performance.timestamp,
            avg_response_time=performance.avg_response_time,
            p95_response_time=performance.p95_response_time,
            p99_response_time=performance.p99_response_time,
            requests_per_minute=performance.requests_per_minute,
            error_rate=error_rate,
            concurrent_requests=performance.concurrent_requests,
            system_resources=system_resources
        )
        
    except Exception as e:
        logging.error(f"Failed to get performance metrics: {e}")
        raise HTTPException(status_code=500, detail="Failed to retrieve performance metrics")

@monitoring_router.get("/alerts")
async def get_active_alerts():
    """Get active monitoring alerts"""
    if not enhanced_monitor:
        raise HTTPException(status_code=503, detail="Enhanced monitoring not available")
    
    try:
        metrics = enhanced_monitor.generate_enhanced_metrics()
        alerts = []
        
        # Check for drift alerts
        if metrics['alert_status']['drift_alert']:
            drift_data = metrics['drift_detection']
            alerts.append({
                "timestamp": datetime.now().isoformat(),
                "alert_type": "model_drift",
                "severity": "high" if drift_data['prediction_drift_score'] > 0.5 else "medium",
                "drift_score": drift_data['prediction_drift_score'],
                "threshold": enhanced_monitor.drift_thresholds['prediction_drift'],
                "message": f"Model drift detected. Drift score: {drift_data['prediction_drift_score']:.3f}",
                "recommendations": [
                    "Review recent prediction patterns",
                    "Consider model retraining",
                    "Check input data quality"
                ]
            })
        
        # Check for performance alerts
        if metrics['alert_status']['performance_alert']:
            perf_data = metrics['performance_profile']
            alerts.append({
                "timestamp": datetime.now().isoformat(),
                "alert_type": "performance_degradation",
                "severity": "medium",
                "response_time": perf_data['p95_response_time'],
                "threshold": 1000,
                "message": f"High response times detected. P95: {perf_data['p95_response_time']:.1f}ms",
                "recommendations": [
                    "Check system resources",
                    "Review model optimization",
                    "Consider scaling infrastructure"
                ]
            })
        
        # Check for high load alerts
        if metrics['alert_status']['high_load_alert']:
            perf_data = metrics['performance_profile']
            alerts.append({
                "timestamp": datetime.now().isoformat(),
                "alert_type": "high_load",
                "severity": "low",
                "requests_per_minute": perf_data['requests_per_minute'],
                "threshold": 100,
                "message": f"High request volume: {perf_data['requests_per_minute']:.1f} req/min",
                "recommendations": [
                    "Monitor system capacity",
                    "Consider load balancing",
                    "Review rate limiting"
                ]
            })
        
        return {
            "timestamp": datetime.now().isoformat(),
            "total_alerts": len(alerts),
            "active_alerts": alerts,
            "alert_summary": {
                "critical": len([a for a in alerts if a.get('severity') == 'critical']),
                "high": len([a for a in alerts if a.get('severity') == 'high']),
                "medium": len([a for a in alerts if a.get('severity') == 'medium']),
                "low": len([a for a in alerts if a.get('severity') == 'low'])
            }
        }
        
    except Exception as e:
        logging.error(f"Failed to get alerts: {e}")
        raise HTTPException(status_code=500, detail="Failed to retrieve alerts")

@monitoring_router.get("/report", response_model=MonitoringReport)
async def get_monitoring_report(hours: int = 24):
    """Get comprehensive monitoring report"""
    if not enhanced_monitor:
        raise HTTPException(status_code=503, detail="Enhanced monitoring not available")
    
    try:
        report = enhanced_monitor.generate_monitoring_report()
        
        return MonitoringReport(
            report_timestamp=report['report_timestamp'],
            monitoring_period_hours=hours,
            overall_health=report['summary']['overall_health'],
            drift_detected=report['summary']['drift_detected'],
            performance_issues=report['current_status']['alert_status']['performance_alert'],
            trends=report['trends'],
            recommendations=report['recommendations'],
            detailed_metrics=report['current_status']
        )
        
    except Exception as e:
        logging.error(f"Failed to generate monitoring report: {e}")
        raise HTTPException(status_code=500, detail="Failed to generate monitoring report")

@monitoring_router.post("/baseline/set")
async def set_monitoring_baseline(background_tasks: BackgroundTasks):
    """Set new monitoring baseline from recent data"""
    if not enhanced_monitor:
        raise HTTPException(status_code=503, detail="Enhanced monitoring not available")
    
    try:
        # Extract recent data for baseline
        recent_predictions = []
        recent_features = []
        
        if len(enhanced_monitor.prediction_buffer) >= 100:
            for item in list(enhanced_monitor.prediction_buffer)[-100:]:
                recent_predictions.append(item['value'])
            
            for item in list(enhanced_monitor.feature_buffer)[-100:]:
                recent_features.append(item['features'])
        
        if recent_predictions:
            # Set new baseline in background
            background_tasks.add_task(
                enhanced_monitor.set_baseline_data,
                recent_predictions,
                recent_features
            )
            
            return {
                "message": "Baseline update initiated",
                "timestamp": datetime.now().isoformat(),
                "samples_count": len(recent_predictions),
                "status": "success"
            }
        else:
            raise HTTPException(status_code=400, detail="Insufficient data for baseline update")
        
    except Exception as e:
        logging.error(f"Failed to set baseline: {e}")
        raise HTTPException(status_code=500, detail="Failed to set monitoring baseline")

@monitoring_router.get("/health-score")
async def get_health_score():
    """Get overall system health score"""
    if not enhanced_monitor:
        raise HTTPException(status_code=503, detail="Enhanced monitoring not available")
    
    try:
        metrics = enhanced_monitor.generate_enhanced_metrics()
        health_score = calculate_health_score(metrics)
        
        # Breakdown of health score components
        components = {
            "drift_impact": 30 if metrics['alert_status']['drift_alert'] else 0,
            "performance_impact": 20 if metrics['alert_status']['performance_alert'] else 0,
            "load_impact": 10 if metrics['alert_status']['high_load_alert'] else 0,
            "baseline_penalty": 10 if not metrics['buffer_status']['baseline_available'] else 0
        }
        
        return {
            "timestamp": datetime.now().isoformat(),
            "health_score": health_score,
            "health_status": get_health_status(health_score),
            "score_breakdown": components,
            "monitoring_coverage": "comprehensive" if enhanced_monitor else "basic"
        }
        
    except Exception as e:
        logging.error(f"Failed to get health score: {e}")
        raise HTTPException(status_code=500, detail="Failed to calculate health score")

# Helper functions
def calculate_health_score(metrics: Dict[str, Any]) -> float:
    """Calculate overall health score based on metrics"""
    score = 100.0
    
    # Deduct for alerts
    if metrics['alert_status']['drift_alert']:
        drift_score = metrics['drift_detection']['prediction_drift_score']
        score -= min(30, drift_score * 50)  # Max 30 points for drift
    
    if metrics['alert_status']['performance_alert']:
        score -= 20  # 20 points for performance issues
    
    if metrics['alert_status']['high_load_alert']:
        score -= 10  # 10 points for high load
    
    # Deduct for missing baseline
    if not metrics['buffer_status']['baseline_available']:
        score -= 10
    
    return max(0.0, min(100.0, score))

def get_health_status(score: float) -> str:
    """Get health status based on score"""
    if score >= 90:
        return "excellent"
    elif score >= 75:
        return "good"
    elif score >= 60:
        return "fair"
    elif score >= 40:
        return "poor"
    else:
        return "critical"

# Background monitoring task
async def background_monitoring_task():
    """Background task for continuous monitoring"""
    while True:
        try:
            if enhanced_monitor:
                # Generate and store metrics
                metrics = enhanced_monitor.generate_enhanced_metrics()
                enhanced_monitor.store_metrics_redis(metrics)
                
                # Log important events
                if metrics['alert_status']['drift_alert']:
                    logging.warning("Model drift detected in background monitoring")
                
                if metrics['alert_status']['performance_alert']:
                    logging.warning("Performance issues detected in background monitoring")
            
            # Wait 5 minutes before next check
            await asyncio.sleep(300)
            
        except Exception as e:
            logging.error(f"Background monitoring task error: {e}")
            await asyncio.sleep(60)  # Wait 1 minute on error

# Export the router
__all__ = ['monitoring_router', 'set_monitoring_instances', 'background_monitoring_task']