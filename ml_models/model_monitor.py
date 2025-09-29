#!/usr/bin/env python3
"""
Production Model Monitoring System
Real-time monitoring and alerting for NBM prediction model
"""

import numpy as np
import pandas as pd
import json
import requests
import time
import logging
from datetime import datetime, timedelta
import sqlite3
import os
from typing import Dict, List, Optional, Tuple
import smtplib
from email.mime.text import MimeText
from email.mime.multipart import MimeMultipart
import matplotlib.pyplot as plt
import seaborn as sns
from dataclasses import dataclass, asdict
import warnings
warnings.filterwarnings('ignore')

from production_model import NBMProductionModel
from data_loader import DataLoader

@dataclass
class ModelMetrics:
    """Data class for model performance metrics"""
    timestamp: str
    mape: float
    rmse: float
    mae: float
    r2: float
    prediction_count: int
    avg_prediction: float
    std_prediction: float
    response_time_ms: float
    error_rate: float
    
@dataclass
class HealthCheck:
    """Data class for system health check"""
    timestamp: str
    api_status: str
    model_loaded: bool
    database_connected: bool
    memory_usage_mb: float
    disk_usage_mb: float
    last_prediction_time: str
    uptime_hours: float

class ModelMonitor:
    """Production model monitoring and alerting system"""
    
    def __init__(self, config_file: str = "monitoring_config.json"):
        self.config = self._load_config(config_file)
        self.setup_logging()
        self.setup_database()
        self.model = NBMProductionModel()
        self.data_loader = DataLoader()
        
        # Performance thresholds
        self.mape_threshold = self.config.get('mape_threshold', 12.0)
        self.response_time_threshold = self.config.get('response_time_threshold', 1000)  # ms
        self.error_rate_threshold = self.config.get('error_rate_threshold', 0.05)  # 5%
        
    def _load_config(self, config_file: str) -> Dict:
        """Load monitoring configuration"""
        default_config = {
            "api_base_url": "http://localhost:8000",
            "monitoring_interval": 300,  # 5 minutes
            "alert_email": "admin@example.com",
            "smtp_server": "smtp.gmail.com",
            "smtp_port": 587,
            "smtp_username": "",
            "smtp_password": "",
            "mape_threshold": 12.0,
            "response_time_threshold": 1000,
            "error_rate_threshold": 0.05,
            "retention_days": 30,
            "alert_cooldown_minutes": 60
        }
        
        if os.path.exists(config_file):
            try:
                with open(config_file, 'r') as f:
                    user_config = json.load(f)
                default_config.update(user_config)
            except Exception as e:
                print(f"Warning: Could not load config file {config_file}: {e}")
        else:
            # Create default config file
            with open(config_file, 'w') as f:
                json.dump(default_config, f, indent=2)
            print(f"Created default config file: {config_file}")
        
        return default_config
    
    def setup_logging(self):
        """Setup logging configuration"""
        log_dir = "logs"
        os.makedirs(log_dir, exist_ok=True)
        
        logging.basicConfig(
            level=logging.INFO,
            format='%(asctime)s - %(name)s - %(levelname)s - %(message)s',
            handlers=[
                logging.FileHandler(f'{log_dir}/model_monitor.log'),
                logging.StreamHandler()
            ]
        )
        self.logger = logging.getLogger('ModelMonitor')
    
    def setup_database(self):
        """Setup SQLite database for monitoring data"""
        self.db_path = "monitoring/monitor.db"
        os.makedirs("monitoring", exist_ok=True)
        
        with sqlite3.connect(self.db_path) as conn:
            cursor = conn.cursor()
            
            # Create metrics table
            cursor.execute('''
                CREATE TABLE IF NOT EXISTS model_metrics (
                    id INTEGER PRIMARY KEY AUTOINCREMENT,
                    timestamp TEXT NOT NULL,
                    mape REAL,
                    rmse REAL,
                    mae REAL,
                    r2 REAL,
                    prediction_count INTEGER,
                    avg_prediction REAL,
                    std_prediction REAL,
                    response_time_ms REAL,
                    error_rate REAL
                )
            ''')
            
            # Create health checks table
            cursor.execute('''
                CREATE TABLE IF NOT EXISTS health_checks (
                    id INTEGER PRIMARY KEY AUTOINCREMENT,
                    timestamp TEXT NOT NULL,
                    api_status TEXT,
                    model_loaded BOOLEAN,
                    database_connected BOOLEAN,
                    memory_usage_mb REAL,
                    disk_usage_mb REAL,
                    last_prediction_time TEXT,
                    uptime_hours REAL
                )
            ''')
            
            # Create alerts table
            cursor.execute('''
                CREATE TABLE IF NOT EXISTS alerts (
                    id INTEGER PRIMARY KEY AUTOINCREMENT,
                    timestamp TEXT NOT NULL,
                    alert_type TEXT,
                    severity TEXT,
                    message TEXT,
                    resolved BOOLEAN DEFAULT FALSE,
                    resolved_timestamp TEXT
                )
            ''')
            
            conn.commit()
        
        self.logger.info("Monitoring database initialized")
    
    def check_api_health(self) -> HealthCheck:
        """Perform comprehensive health check of the ML API"""
        timestamp = datetime.now().isoformat()
        
        try:
            # Check API endpoint
            start_time = time.time()
            response = requests.get(f"{self.config['api_base_url']}/health", timeout=10)
            response_time = (time.time() - start_time) * 1000
            
            api_status = "healthy" if response.status_code == 200 else f"error_{response.status_code}"
            
            # Parse response if available
            health_data = {}
            if response.status_code == 200:
                try:
                    health_data = response.json()
                except:
                    health_data = {}
            
            # System resource checks
            import psutil
            memory_usage = psutil.virtual_memory().used / (1024 * 1024)  # MB
            disk_usage = psutil.disk_usage('/').used / (1024 * 1024)  # MB
            
            # Model status check
            model_loaded = health_data.get('model_loaded', False)
            database_connected = health_data.get('database_connected', False)
            last_prediction_time = health_data.get('last_prediction_time', 'unknown')
            uptime_hours = health_data.get('uptime_hours', 0)
            
            return HealthCheck(
                timestamp=timestamp,
                api_status=api_status,
                model_loaded=model_loaded,
                database_connected=database_connected,
                memory_usage_mb=memory_usage,
                disk_usage_mb=disk_usage,
                last_prediction_time=last_prediction_time,
                uptime_hours=uptime_hours
            )
            
        except Exception as e:
            self.logger.error(f"Health check failed: {e}")
            return HealthCheck(
                timestamp=timestamp,
                api_status="unreachable",
                model_loaded=False,
                database_connected=False,
                memory_usage_mb=0,
                disk_usage_mb=0,
                last_prediction_time="unknown",
                uptime_hours=0
            )
    
    def evaluate_model_performance(self) -> Optional[ModelMetrics]:
        """Evaluate current model performance on recent data"""
        try:
            # Load recent data for evaluation
            recent_data = self.data_loader.load_recent_nbm_data(days=30)
            if recent_data is None or len(recent_data) < 10:
                self.logger.warning("Insufficient recent data for performance evaluation")
                return None
            
            # Prepare data for prediction
            from data_preprocessing_monthly import DataPreprocessorMonthly
            preprocessor = DataPreprocessorMonthly(sequence_length=6)
            processed_data = preprocessor.prepare_monthly_pipeline(recent_data)
            
            X, y = processed_data['X'], processed_data['y']
            
            # Generate predictions and measure performance
            start_time = time.time()
            predictions = []
            errors = 0
            
            for i in range(len(X)):
                try:
                    pred = self.model.predict(X[i:i+1])[0]
                    predictions.append(pred)
                except Exception as e:
                    errors += 1
                    predictions.append(np.nan)
            
            response_time = ((time.time() - start_time) / len(X)) * 1000  # ms per prediction
            predictions = np.array(predictions)
            
            # Filter out failed predictions
            valid_mask = ~np.isnan(predictions)
            if np.sum(valid_mask) == 0:
                self.logger.error("All predictions failed")
                return None
            
            y_valid = y[valid_mask]
            pred_valid = predictions[valid_mask]
            
            # Calculate metrics
            def calculate_mape(y_true, y_pred):
                return np.mean(np.abs((y_true - y_pred) / y_true)) * 100
            
            mape = calculate_mape(y_valid, pred_valid)
            rmse = np.sqrt(np.mean((y_valid - pred_valid) ** 2))
            mae = np.mean(np.abs(y_valid - pred_valid))
            r2 = 1 - np.sum((y_valid - pred_valid) ** 2) / np.sum((y_valid - np.mean(y_valid)) ** 2)
            
            error_rate = errors / len(X)
            
            return ModelMetrics(
                timestamp=datetime.now().isoformat(),
                mape=mape,
                rmse=rmse,
                mae=mae,
                r2=r2,
                prediction_count=len(X),
                avg_prediction=np.mean(pred_valid),
                std_prediction=np.std(pred_valid),
                response_time_ms=response_time,
                error_rate=error_rate
            )
            
        except Exception as e:
            self.logger.error(f"Performance evaluation failed: {e}")
            return None
    
    def store_metrics(self, metrics: ModelMetrics):
        """Store metrics in database"""
        with sqlite3.connect(self.db_path) as conn:
            cursor = conn.cursor()
            
            cursor.execute('''
                INSERT INTO model_metrics (
                    timestamp, mape, rmse, mae, r2, prediction_count,
                    avg_prediction, std_prediction, response_time_ms, error_rate
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ''', (
                metrics.timestamp, metrics.mape, metrics.rmse, metrics.mae,
                metrics.r2, metrics.prediction_count, metrics.avg_prediction,
                metrics.std_prediction, metrics.response_time_ms, metrics.error_rate
            ))
            
            conn.commit()
    
    def store_health_check(self, health: HealthCheck):
        """Store health check in database"""
        with sqlite3.connect(self.db_path) as conn:
            cursor = conn.cursor()
            
            cursor.execute('''
                INSERT INTO health_checks (
                    timestamp, api_status, model_loaded, database_connected,
                    memory_usage_mb, disk_usage_mb, last_prediction_time, uptime_hours
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)
            ''', (
                health.timestamp, health.api_status, health.model_loaded,
                health.database_connected, health.memory_usage_mb,
                health.disk_usage_mb, health.last_prediction_time, health.uptime_hours
            ))
            
            conn.commit()
    
    def check_thresholds(self, metrics: ModelMetrics, health: HealthCheck) -> List[str]:
        """Check if any thresholds are violated and return alert messages"""
        alerts = []
        
        # Performance alerts
        if metrics and metrics.mape > self.mape_threshold:
            alerts.append({
                'type': 'performance',
                'severity': 'high',
                'message': f'MAPE ({metrics.mape:.2f}%) exceeds threshold ({self.mape_threshold}%)'
            })
        
        if metrics and metrics.response_time_ms > self.response_time_threshold:
            alerts.append({
                'type': 'performance',
                'severity': 'medium',
                'message': f'Response time ({metrics.response_time_ms:.1f}ms) exceeds threshold ({self.response_time_threshold}ms)'
            })
        
        if metrics and metrics.error_rate > self.error_rate_threshold:
            alerts.append({
                'type': 'reliability',
                'severity': 'high',
                'message': f'Error rate ({metrics.error_rate:.1%}) exceeds threshold ({self.error_rate_threshold:.1%})'
            })
        
        # Health alerts
        if health.api_status != "healthy":
            alerts.append({
                'type': 'availability',
                'severity': 'critical',
                'message': f'API status: {health.api_status}'
            })
        
        if not health.model_loaded:
            alerts.append({
                'type': 'availability',
                'severity': 'critical',
                'message': 'Model is not loaded'
            })
        
        if not health.database_connected:
            alerts.append({
                'type': 'availability',
                'severity': 'high',
                'message': 'Database connection failed'
            })
        
        # Resource alerts
        if health.memory_usage_mb > 8192:  # 8GB
            alerts.append({
                'type': 'resources',
                'severity': 'medium',
                'message': f'High memory usage: {health.memory_usage_mb:.1f}MB'
            })
        
        return alerts
    
    def send_alert(self, alert: Dict[str, str]):
        """Send alert via email"""
        try:
            if not self.config.get('smtp_username') or not self.config.get('smtp_password'):
                self.logger.warning("Email credentials not configured, skipping email alert")
                return
            
            # Check cooldown period
            if self._is_alert_in_cooldown(alert['type']):
                return
            
            msg = MimeMultipart()
            msg['From'] = self.config['smtp_username']
            msg['To'] = self.config['alert_email']
            msg['Subject'] = f"[NBM Model Alert] {alert['severity'].upper()}: {alert['type']}"
            
            body = f"""
NBM Model Alert

Severity: {alert['severity'].upper()}
Type: {alert['type']}
Time: {datetime.now().strftime('%Y-%m-%d %H:%M:%S')}

Message: {alert['message']}

Please check the monitoring dashboard for more details.

---
Automated NBM Model Monitoring System
            """
            
            msg.attach(MimeText(body, 'plain'))
            
            server = smtplib.SMTP(self.config['smtp_server'], self.config['smtp_port'])
            server.starttls()
            server.login(self.config['smtp_username'], self.config['smtp_password'])
            server.send_message(msg)
            server.quit()
            
            self.logger.info(f"Alert sent: {alert['message']}")
            
        except Exception as e:
            self.logger.error(f"Failed to send alert: {e}")
    
    def _is_alert_in_cooldown(self, alert_type: str) -> bool:
        """Check if alert type is in cooldown period"""
        try:
            with sqlite3.connect(self.db_path) as conn:
                cursor = conn.cursor()
                
                cooldown_time = datetime.now() - timedelta(minutes=self.config['alert_cooldown_minutes'])
                
                cursor.execute('''
                    SELECT COUNT(*) FROM alerts 
                    WHERE alert_type = ? AND timestamp > ? AND resolved = FALSE
                ''', (alert_type, cooldown_time.isoformat()))
                
                count = cursor.fetchone()[0]
                return count > 0
                
        except Exception as e:
            self.logger.error(f"Error checking alert cooldown: {e}")
            return False
    
    def store_alert(self, alert: Dict[str, str]):
        """Store alert in database"""
        with sqlite3.connect(self.db_path) as conn:
            cursor = conn.cursor()
            
            cursor.execute('''
                INSERT INTO alerts (timestamp, alert_type, severity, message)
                VALUES (?, ?, ?, ?)
            ''', (
                datetime.now().isoformat(),
                alert['type'],
                alert['severity'],
                alert['message']
            ))
            
            conn.commit()
    
    def generate_monitoring_report(self, days: int = 7) -> str:
        """Generate monitoring report for the last N days"""
        end_date = datetime.now()
        start_date = end_date - timedelta(days=days)
        
        with sqlite3.connect(self.db_path) as conn:
            # Get metrics data
            metrics_df = pd.read_sql_query('''
                SELECT * FROM model_metrics 
                WHERE timestamp >= ? 
                ORDER BY timestamp DESC
            ''', conn, params=(start_date.isoformat(),))
            
            # Get health data
            health_df = pd.read_sql_query('''
                SELECT * FROM health_checks 
                WHERE timestamp >= ?
                ORDER BY timestamp DESC
            ''', conn, params=(start_date.isoformat(),))
            
            # Get alerts data
            alerts_df = pd.read_sql_query('''
                SELECT * FROM alerts 
                WHERE timestamp >= ?
                ORDER BY timestamp DESC
            ''', conn, params=(start_date.isoformat(),))
        
        # Generate report
        report = f"""# NBM Model Monitoring Report
**Period:** {start_date.strftime('%Y-%m-%d')} to {end_date.strftime('%Y-%m-%d')}
**Generated:** {datetime.now().strftime('%Y-%m-%d %H:%M:%S')}

## Executive Summary

"""
        
        if not metrics_df.empty:
            avg_mape = metrics_df['mape'].mean()
            avg_response_time = metrics_df['response_time_ms'].mean()
            avg_error_rate = metrics_df['error_rate'].mean()
            total_predictions = metrics_df['prediction_count'].sum()
            
            report += f"""- **Average MAPE:** {avg_mape:.2f}% (Target: <{self.mape_threshold}%)
- **Average Response Time:** {avg_response_time:.1f}ms (Target: <{self.response_time_threshold}ms)
- **Average Error Rate:** {avg_error_rate:.2%} (Target: <{self.error_rate_threshold:.1%})
- **Total Predictions:** {total_predictions:,}
- **Uptime:** {(len(health_df[health_df['api_status'] == 'healthy']) / max(len(health_df), 1)) * 100:.1f}%

"""
        else:
            report += "No metrics data available for this period.\n\n"
        
        # Alert summary
        if not alerts_df.empty:
            alert_counts = alerts_df.groupby(['severity', 'alert_type']).size().unstack(fill_value=0)
            report += f"""## Alert Summary

**Total Alerts:** {len(alerts_df)}

"""
            for severity in ['critical', 'high', 'medium', 'low']:
                if severity in alert_counts.index:
                    count = alert_counts.loc[severity].sum()
                    report += f"- **{severity.title()}:** {count}\n"
            
            report += "\n"
        
        # Performance trends
        if not metrics_df.empty and len(metrics_df) > 1:
            report += f"""## Performance Trends

- **MAPE Trend:** {self._get_trend_indicator(metrics_df['mape'])}
- **Response Time Trend:** {self._get_trend_indicator(metrics_df['response_time_ms'])}
- **Error Rate Trend:** {self._get_trend_indicator(metrics_df['error_rate'])}

"""
        
        # Recent alerts
        if not alerts_df.empty:
            recent_alerts = alerts_df.head(10)
            report += "## Recent Alerts\n\n"
            for _, alert in recent_alerts.iterrows():
                report += f"- **{alert['severity'].title()}** ({alert['timestamp'][:16]}): {alert['message']}\n"
            report += "\n"
        
        # Recommendations
        report += """## Recommendations

"""
        if not metrics_df.empty:
            if avg_mape > self.mape_threshold:
                report += "⚠️ **Model Retraining Required:** MAPE is above threshold, consider retraining with recent data.\n"
            
            if avg_response_time > self.response_time_threshold:
                report += "⚠️ **Performance Optimization:** Response time is above threshold, consider model optimization or scaling.\n"
            
            if avg_error_rate > self.error_rate_threshold:
                report += "⚠️ **Reliability Issues:** High error rate detected, investigate model stability.\n"
            
            if avg_mape <= self.mape_threshold and avg_response_time <= self.response_time_threshold:
                report += "✅ **Model Performance:** All metrics within acceptable thresholds.\n"
        
        return report
    
    def _get_trend_indicator(self, series: pd.Series) -> str:
        """Get trend indicator for a metric series"""
        if len(series) < 2:
            return "No trend data"
        
        recent_avg = series.head(len(series)//2).mean()
        older_avg = series.tail(len(series)//2).mean()
        
        change_pct = ((recent_avg - older_avg) / older_avg) * 100
        
        if abs(change_pct) < 5:
            return "Stable"
        elif change_pct > 0:
            return f"Increasing (+{change_pct:.1f}%)"
        else:
            return f"Decreasing ({change_pct:.1f}%)"
    
    def cleanup_old_data(self):
        """Clean up old monitoring data"""
        cutoff_date = datetime.now() - timedelta(days=self.config['retention_days'])
        
        with sqlite3.connect(self.db_path) as conn:
            cursor = conn.cursor()
            
            # Clean up old metrics
            cursor.execute('DELETE FROM model_metrics WHERE timestamp < ?', (cutoff_date.isoformat(),))
            cursor.execute('DELETE FROM health_checks WHERE timestamp < ?', (cutoff_date.isoformat(),))
            cursor.execute('DELETE FROM alerts WHERE timestamp < ? AND resolved = TRUE', (cutoff_date.isoformat(),))
            
            conn.commit()
            
        self.logger.info(f"Cleaned up data older than {self.config['retention_days']} days")
    
    def run_monitoring_cycle(self):
        """Run one complete monitoring cycle"""
        self.logger.info("Starting monitoring cycle")
        
        try:
            # Health check
            health = self.check_api_health()
            self.store_health_check(health)
            self.logger.info(f"API Status: {health.api_status}, Model Loaded: {health.model_loaded}")
            
            # Performance evaluation
            metrics = self.evaluate_model_performance()
            if metrics:
                self.store_metrics(metrics)
                self.logger.info(f"Performance - MAPE: {metrics.mape:.2f}%, Response Time: {metrics.response_time_ms:.1f}ms")
            
            # Check for alerts
            alerts = self.check_thresholds(metrics, health)
            for alert in alerts:
                self.logger.warning(f"Alert: {alert['message']}")
                self.store_alert(alert)
                self.send_alert(alert)
            
            # Cleanup old data
            self.cleanup_old_data()
            
            self.logger.info("Monitoring cycle completed successfully")
            
        except Exception as e:
            self.logger.error(f"Monitoring cycle failed: {e}")
            import traceback
            traceback.print_exc()
    
    def run_continuous_monitoring(self):
        """Run continuous monitoring loop"""
        self.logger.info(f"Starting continuous monitoring (interval: {self.config['monitoring_interval']}s)")
        
        while True:
            try:
                self.run_monitoring_cycle()
                time.sleep(self.config['monitoring_interval'])
                
            except KeyboardInterrupt:
                self.logger.info("Monitoring stopped by user")
                break
            except Exception as e:
                self.logger.error(f"Monitoring error: {e}")
                time.sleep(60)  # Wait 1 minute before retry

def main():
    """Main monitoring function"""
    import argparse
    
    parser = argparse.ArgumentParser(description='NBM Model Monitoring System')
    parser.add_argument('--mode', choices=['once', 'continuous', 'report'], default='once',
                      help='Monitoring mode: once, continuous, or generate report')
    parser.add_argument('--days', type=int, default=7,
                      help='Number of days for report generation')
    parser.add_argument('--config', default='monitoring_config.json',
                      help='Configuration file path')
    
    args = parser.parse_args()
    
    monitor = ModelMonitor(args.config)
    
    if args.mode == 'once':
        monitor.run_monitoring_cycle()
    elif args.mode == 'continuous':
        monitor.run_continuous_monitoring()
    elif args.mode == 'report':
        report = monitor.generate_monitoring_report(args.days)
        
        # Save report
        timestamp = datetime.now().strftime("%Y%m%d_%H%M%S")
        report_path = f"monitoring/report_{timestamp}.md"
        with open(report_path, 'w') as f:
            f.write(report)
        
        print(f"Report generated: {report_path}")
        print("\n" + "="*60)
        print(report)
        print("="*60)

if __name__ == "__main__":
    main()