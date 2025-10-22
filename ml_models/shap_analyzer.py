#!/usr/bin/env python3
"""
SHAP Analysis Implementation for SIKOLBIA NBM Prediction Model
Provides model interpretability and feature importance analysis
"""

import numpy as np
import pandas as pd
import joblib
import shap
import matplotlib.pyplot as plt
import seaborn as sns
from typing import Dict, List, Tuple, Any, Optional
import logging
from pathlib import Path
import json
from datetime import datetime
import warnings
warnings.filterwarnings('ignore')

# Configure logging
logging.basicConfig(
    level=logging.INFO,
    format='%(asctime)s - %(name)s - %(levelname)s - %(message)s',
    handlers=[
        logging.FileHandler('logs/shap_analysis.log'),
        logging.StreamHandler()
    ]
)
logger = logging.getLogger(__name__)

class SHAPAnalyzer:
    """
    SHAP (SHapley Additive exPlanations) analyzer for NBM prediction model
    Provides comprehensive model interpretability and feature importance analysis
    """
    
    def __init__(self, model_path: str = "models/nbm_production"):
        """
        Initialize SHAP analyzer with trained model
        
        Args:
            model_path: Path to trained model directory
        """
        self.model_path = Path(model_path)
        self.model = None
        self.feature_names = None
        self.explainer = None
        self.shap_values = None
        self.background_data = None
        
        # Feature categories for analysis
        self.feature_categories = {
            'temporal': ['tahun', 'bulan', 'lag_1', 'lag_2', 'lag_3'],
            'categorical': ['kelompok_encoded', 'komoditi_encoded'],
            'nutritional': ['kalori_hari', 'protein_ratio', 'fat_ratio'],
            'derived': ['trend', 'seasonality', 'moving_avg_3', 'moving_avg_6'],
            'economic': ['price_index', 'inflation_factor']
        }
        
        # Komoditi mapping for interpretation
        self.komoditi_mapping = {
            'Padi-padian': ['Beras', 'Jagung', 'Gandum', 'Terigu'],
            'Umbi-umbian': ['Ubi kayu', 'Ubi jalar', 'Kentang'],
            'Protein Hewani': ['Daging sapi', 'Daging ayam', 'Telur ayam', 'Ikan', 'Susu'],
            'Minyak dan Lemak': ['Minyak kelapa', 'Minyak sawit', 'Margarine'],
            'Buah/Biji Berminyak': ['Kelapa', 'Kemiri'],
            'Kacang-kacangan': ['Kacang tanah', 'Kacang kedelai', 'Kacang hijau'],
            'Gula': ['Gula pasir', 'Gula aren'],
            'Sayur dan Buah': ['Tomat', 'Bawang merah', 'Cabai', 'Pisang', 'Jeruk'],
            'Lain-lain': ['Teh', 'Kopi', 'Coklat']
        }
    
    def load_model(self) -> bool:
        """
        Load trained NBM prediction model
        
        Returns:
            bool: Success status
        """
        try:
            # Load main model
            model_file = self.model_path / "nbm_model.joblib"
            if not model_file.exists():
                logger.error(f"Model file not found: {model_file}")
                return False
            
            self.model = joblib.load(model_file)
            logger.info(f"Model loaded successfully from {model_file}")
            
            # Load feature names
            feature_file = self.model_path / "feature_names.json"
            if feature_file.exists():
                with open(feature_file, 'r') as f:
                    self.feature_names = json.load(f)
                logger.info(f"Feature names loaded: {len(self.feature_names)} features")
            else:
                # Default feature names if file doesn't exist
                self.feature_names = [
                    'tahun', 'bulan', 'kelompok_encoded', 'komoditi_encoded', 
                    'kalori_hari', 'lag_1', 'lag_2', 'lag_3',
                    'trend', 'seasonality', 'moving_avg_3', 'moving_avg_6'
                ]
                logger.warning("Using default feature names")
            
            return True
            
        except Exception as e:
            logger.error(f"Error loading model: {str(e)}")
            return False
    
    def load_background_data(self, data_path: str = "data/training_sample.csv", 
                           sample_size: int = 100) -> bool:
        """
        Load background data for SHAP explainer
        
        Args:
            data_path: Path to training data
            sample_size: Number of samples for background
            
        Returns:
            bool: Success status
        """
        try:
            # Load training data sample
            if Path(data_path).exists():
                df = pd.read_csv(data_path)
                
                # Sample data for background
                if len(df) > sample_size:
                    self.background_data = df.sample(n=sample_size, random_state=42)
                else:
                    self.background_data = df.copy()
                
                logger.info(f"Background data loaded: {len(self.background_data)} samples")
                
            else:
                # Generate synthetic background data if file doesn't exist
                logger.warning("Training data not found, generating synthetic background")
                self.background_data = self._generate_synthetic_background(sample_size)
            
            return True
            
        except Exception as e:
            logger.error(f"Error loading background data: {str(e)}")
            return False
    
    def _generate_synthetic_background(self, sample_size: int) -> pd.DataFrame:
        """
        Generate synthetic background data for SHAP analysis
        
        Args:
            sample_size: Number of samples to generate
            
        Returns:
            pd.DataFrame: Synthetic background data
        """
        np.random.seed(42)
        
        data = {
            'tahun': np.random.choice(range(2018, 2025), sample_size),
            'bulan': np.random.choice(range(1, 13), sample_size),
            'kelompok_encoded': np.random.choice(range(9), sample_size),
            'komoditi_encoded': np.random.choice(range(50), sample_size),
            'kalori_hari': np.random.normal(25.0, 15.0, sample_size),
            'lag_1': np.random.normal(25.0, 15.0, sample_size),
            'lag_2': np.random.normal(25.0, 15.0, sample_size),
            'lag_3': np.random.normal(25.0, 15.0, sample_size),
            'trend': np.random.normal(0.0, 0.1, sample_size),
            'seasonality': np.random.normal(0.0, 0.5, sample_size),
            'moving_avg_3': np.random.normal(25.0, 10.0, sample_size),
            'moving_avg_6': np.random.normal(25.0, 8.0, sample_size)
        }
        
        # Add additional features if they exist
        for feature in self.feature_names:
            if feature not in data:
                data[feature] = np.random.normal(0.0, 1.0, sample_size)
        
        return pd.DataFrame(data)
    
    def initialize_explainer(self, explainer_type: str = "tree") -> bool:
        """
        Initialize SHAP explainer
        
        Args:
            explainer_type: Type of explainer ('tree', 'linear', 'kernel')
            
        Returns:
            bool: Success status
        """
        try:
            if self.model is None:
                logger.error("Model not loaded. Call load_model() first.")
                return False
            
            if self.background_data is None:
                logger.error("Background data not loaded. Call load_background_data() first.")
                return False
            
            # Prepare background data features
            if len(self.background_data.columns) > len(self.feature_names):
                background_features = self.background_data[self.feature_names].values
            else:
                background_features = self.background_data.values
            
            # Initialize appropriate explainer
            if explainer_type == "tree":
                # For tree-based models (RandomForest, XGBoost, etc.)
                self.explainer = shap.TreeExplainer(self.model)
                logger.info("Tree explainer initialized")
                
            elif explainer_type == "linear":
                # For linear models
                self.explainer = shap.LinearExplainer(self.model, background_features)
                logger.info("Linear explainer initialized")
                
            elif explainer_type == "kernel":
                # Universal explainer (slower but works with any model)
                self.explainer = shap.KernelExplainer(self.model.predict, background_features)
                logger.info("Kernel explainer initialized")
                
            else:
                # Auto-detect explainer type
                try:
                    self.explainer = shap.TreeExplainer(self.model)
                    logger.info("Auto-detected tree explainer")
                except:
                    try:
                        self.explainer = shap.LinearExplainer(self.model, background_features)
                        logger.info("Auto-detected linear explainer")
                    except:
                        self.explainer = shap.KernelExplainer(self.model.predict, background_features)
                        logger.info("Fallback to kernel explainer")
            
            return True
            
        except Exception as e:
            logger.error(f"Error initializing explainer: {str(e)}")
            return False
    
    def calculate_shap_values(self, X: np.ndarray, max_samples: int = 1000) -> bool:
        """
        Calculate SHAP values for given samples
        
        Args:
            X: Input features
            max_samples: Maximum number of samples to analyze
            
        Returns:
            bool: Success status
        """
        try:
            if self.explainer is None:
                logger.error("Explainer not initialized. Call initialize_explainer() first.")
                return False
            
            # Limit samples for performance
            if len(X) > max_samples:
                indices = np.random.choice(len(X), max_samples, replace=False)
                X_sample = X[indices]
                logger.info(f"Sampling {max_samples} from {len(X)} samples for SHAP analysis")
            else:
                X_sample = X
            
            # Calculate SHAP values
            logger.info("Calculating SHAP values...")
            self.shap_values = self.explainer.shap_values(X_sample)
            
            # Handle multi-output models
            if isinstance(self.shap_values, list):
                self.shap_values = self.shap_values[0]  # Take first output
            
            logger.info(f"SHAP values calculated for {len(X_sample)} samples")
            return True
            
        except Exception as e:
            logger.error(f"Error calculating SHAP values: {str(e)}")
            return False
    
    def get_feature_importance(self) -> Dict[str, float]:
        """
        Get global feature importance based on SHAP values
        
        Returns:
            Dict[str, float]: Feature importance scores
        """
        if self.shap_values is None:
            logger.error("SHAP values not calculated. Call calculate_shap_values() first.")
            return {}
        
        try:
            # Calculate mean absolute SHAP values
            importance_scores = np.abs(self.shap_values).mean(axis=0)
            
            # Create feature importance dictionary
            feature_importance = {}
            for i, feature in enumerate(self.feature_names[:len(importance_scores)]):
                feature_importance[feature] = float(importance_scores[i])
            
            # Sort by importance
            feature_importance = dict(sorted(feature_importance.items(), 
                                           key=lambda x: x[1], reverse=True))
            
            logger.info("Feature importance calculated successfully")
            return feature_importance
            
        except Exception as e:
            logger.error(f"Error calculating feature importance: {str(e)}")
            return {}
    
    def get_category_importance(self) -> Dict[str, float]:
        """
        Get importance by feature categories
        
        Returns:
            Dict[str, float]: Category importance scores
        """
        feature_importance = self.get_feature_importance()
        if not feature_importance:
            return {}
        
        try:
            category_importance = {}
            
            for category, features in self.feature_categories.items():
                total_importance = 0.0
                feature_count = 0
                
                for feature in features:
                    if feature in feature_importance:
                        total_importance += feature_importance[feature]
                        feature_count += 1
                
                if feature_count > 0:
                    category_importance[category] = total_importance / feature_count
                else:
                    category_importance[category] = 0.0
            
            # Sort by importance
            category_importance = dict(sorted(category_importance.items(), 
                                            key=lambda x: x[1], reverse=True))
            
            logger.info("Category importance calculated successfully")
            return category_importance
            
        except Exception as e:
            logger.error(f"Error calculating category importance: {str(e)}")
            return {}
    
    def explain_prediction(self, X_single: np.ndarray, 
                          feature_values: Dict[str, Any] = None) -> Dict[str, Any]:
        """
        Explain a single prediction with SHAP values
        
        Args:
            X_single: Single sample features
            feature_values: Original feature values for interpretation
            
        Returns:
            Dict[str, Any]: Explanation results
        """
        if self.explainer is None:
            logger.error("Explainer not initialized.")
            return {}
        
        try:
            # Ensure single sample format
            if X_single.ndim == 1:
                X_single = X_single.reshape(1, -1)
            
            # Calculate SHAP values for single prediction
            shap_values_single = self.explainer.shap_values(X_single)
            
            # Handle multi-output models
            if isinstance(shap_values_single, list):
                shap_values_single = shap_values_single[0]
            
            # Get prediction
            prediction = self.model.predict(X_single)[0]
            
            # Create explanation
            explanation = {
                'prediction': float(prediction),
                'base_value': float(self.explainer.expected_value if hasattr(self.explainer, 'expected_value') else 0),
                'feature_contributions': {},
                'top_positive_features': [],
                'top_negative_features': [],
                'feature_values': feature_values or {}
            }
            
            # Feature contributions
            for i, feature in enumerate(self.feature_names[:len(shap_values_single[0])]):
                contribution = float(shap_values_single[0][i])
                explanation['feature_contributions'][feature] = contribution
            
            # Sort features by contribution
            sorted_contributions = sorted(explanation['feature_contributions'].items(), 
                                        key=lambda x: x[1], reverse=True)
            
            # Top positive and negative features
            explanation['top_positive_features'] = [
                {'feature': feat, 'contribution': contrib} 
                for feat, contrib in sorted_contributions[:5] if contrib > 0
            ]
            
            explanation['top_negative_features'] = [
                {'feature': feat, 'contribution': contrib} 
                for feat, contrib in sorted_contributions[-5:] if contrib < 0
            ]
            
            logger.info("Single prediction explained successfully")
            return explanation
            
        except Exception as e:
            logger.error(f"Error explaining prediction: {str(e)}")
            return {}
    
    def generate_interpretation_report(self, save_path: str = "reports/shap_analysis.json") -> bool:
        """
        Generate comprehensive interpretation report
        
        Args:
            save_path: Path to save the report
            
        Returns:
            bool: Success status
        """
        try:
            # Gather all analysis results
            feature_importance = self.get_feature_importance()
            category_importance = self.get_category_importance()
            
            # Create comprehensive report
            report = {
                'timestamp': datetime.now().isoformat(),
                'model_info': {
                    'model_path': str(self.model_path),
                    'feature_count': len(self.feature_names),
                    'background_samples': len(self.background_data) if self.background_data is not None else 0
                },
                'feature_importance': feature_importance,
                'category_importance': category_importance,
                'feature_categories': self.feature_categories,
                'top_features': {
                    'most_important': list(feature_importance.keys())[:10],
                    'least_important': list(feature_importance.keys())[-5:]
                },
                'insights': self._generate_insights(feature_importance, category_importance),
                'recommendations': self._generate_recommendations(feature_importance, category_importance)
            }
            
            # Save report
            Path(save_path).parent.mkdir(parents=True, exist_ok=True)
            with open(save_path, 'w') as f:
                json.dump(report, f, indent=2, ensure_ascii=False)
            
            logger.info(f"SHAP analysis report saved to {save_path}")
            return True
            
        except Exception as e:
            logger.error(f"Error generating report: {str(e)}")
            return False
    
    def _generate_insights(self, feature_importance: Dict[str, float], 
                          category_importance: Dict[str, float]) -> List[str]:
        """
        Generate insights from SHAP analysis
        
        Args:
            feature_importance: Feature importance scores
            category_importance: Category importance scores
            
        Returns:
            List[str]: Generated insights
        """
        insights = []
        
        if not feature_importance or not category_importance:
            return insights
        
        # Top feature insights
        top_feature = list(feature_importance.keys())[0]
        top_importance = feature_importance[top_feature]
        insights.append(f"Feature '{top_feature}' adalah yang paling berpengaruh dengan skor {top_importance:.3f}")
        
        # Category insights
        top_category = list(category_importance.keys())[0]
        insights.append(f"Kategori '{top_category}' memiliki pengaruh terbesar terhadap prediksi")
        
        # Temporal features
        temporal_score = category_importance.get('temporal', 0)
        if temporal_score > 0.1:
            insights.append("Faktor waktu (tahun, bulan) memiliki pengaruh signifikan pada prediksi")
        
        # Nutritional features
        nutritional_score = category_importance.get('nutritional', 0)
        if nutritional_score > 0.15:
            insights.append("Data nutrisi historis sangat penting untuk akurasi prediksi")
        
        # Feature diversity
        if len([f for f, v in feature_importance.items() if v > 0.05]) > 5:
            insights.append("Model menggunakan berbagai jenis fitur, menunjukkan kompleksitas yang baik")
        
        return insights
    
    def _generate_recommendations(self, feature_importance: Dict[str, float], 
                                 category_importance: Dict[str, float]) -> List[str]:
        """
        Generate recommendations from SHAP analysis
        
        Args:
            feature_importance: Feature importance scores
            category_importance: Category importance scores
            
        Returns:
            List[str]: Generated recommendations
        """
        recommendations = []
        
        if not feature_importance or not category_importance:
            return recommendations
        
        # Data quality recommendations
        low_importance_features = [f for f, v in feature_importance.items() if v < 0.01]
        if len(low_importance_features) > 3:
            recommendations.append("Pertimbangkan untuk menghapus fitur dengan pengaruh rendah untuk meningkatkan efisiensi model")
        
        # Feature engineering recommendations
        if category_importance.get('derived', 0) < 0.1:
            recommendations.append("Tambahkan lebih banyak fitur derived (trend, seasonality) untuk meningkatkan akurasi")
        
        # Data collection recommendations
        if category_importance.get('economic', 0) < 0.05:
            recommendations.append("Pertimbangkan menambahkan data ekonomi (harga, inflasi) untuk prediksi yang lebih akurat")
        
        # Model improvement recommendations
        top_5_importance = sum(list(feature_importance.values())[:5])
        total_importance = sum(feature_importance.values())
        if top_5_importance / total_importance > 0.8:
            recommendations.append("Model sangat bergantung pada beberapa fitur utama, pertimbangkan diversifikasi fitur")
        
        # Temporal recommendations
        if 'bulan' in feature_importance and feature_importance['bulan'] > 0.1:
            recommendations.append("Faktor musiman penting, pastikan data mencakup siklus tahunan yang lengkap")
        
        return recommendations

# CLI Interface
def main():
    """Main function for CLI usage"""
    import argparse
    
    parser = argparse.ArgumentParser(description="SHAP Analysis for NBM Prediction Model")
    parser.add_argument("--model-path", default="models/nbm_production", 
                       help="Path to trained model")
    parser.add_argument("--data-path", default="data/training_sample.csv", 
                       help="Path to training data")
    parser.add_argument("--output", default="reports/shap_analysis.json", 
                       help="Output report path")
    parser.add_argument("--explainer", default="auto", 
                       choices=["tree", "linear", "kernel", "auto"],
                       help="SHAP explainer type")
    parser.add_argument("--samples", type=int, default=1000, 
                       help="Number of samples for analysis")
    
    args = parser.parse_args()
    
    # Initialize analyzer
    analyzer = SHAPAnalyzer(args.model_path)
    
    # Load model and data
    if not analyzer.load_model():
        logger.error("Failed to load model")
        return False
    
    if not analyzer.load_background_data(args.data_path):
        logger.error("Failed to load background data")
        return False
    
    # Initialize explainer
    if not analyzer.initialize_explainer(args.explainer):
        logger.error("Failed to initialize explainer")
        return False
    
    # Calculate SHAP values
    if analyzer.background_data is not None:
        X = analyzer.background_data[analyzer.feature_names].values
        if not analyzer.calculate_shap_values(X, args.samples):
            logger.error("Failed to calculate SHAP values")
            return False
    
    # Generate report
    if not analyzer.generate_interpretation_report(args.output):
        logger.error("Failed to generate report")
        return False
    
    logger.info("SHAP analysis completed successfully!")
    return True

if __name__ == "__main__":
    main()