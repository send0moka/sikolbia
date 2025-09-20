# 7. API SPECIFICATION

## 7.1 API Architecture Overview

### 7.1.1 API Design Principles
- **RESTful Architecture**: Standard REST principles untuk consistent interface
- **JSON-First**: Primary data format untuk request/response
- **Stateless**: No server-side session dependencies
- **Versioned**: API versioning untuk backward compatibility
- **Documented**: Comprehensive OpenAPI/Swagger documentation

### 7.1.2 API Base Structure
```
Base URL: https://yourdomain.com/api/v1
ML API Base URL: http://localhost:8082 (FastAPI)
Content-Type: application/json
Accept: application/json
```

### 7.1.3 Authentication Methods
- **Session-based**: For web application (Laravel Sanctum)
- **Token-based**: For API clients (Bearer tokens)
- **API Keys**: For external integrations
- **Rate Limiting**: Per endpoint dan user-based limits

## 7.2 Laravel Web API Endpoints

### 7.2.1 Authentication Endpoints

#### POST /api/auth/login
**Description**: User authentication  
**Request**:
```json
{
    "email": "admin@example.com",
    "password": "password123"
}
```
**Response**:
```json
{
    "success": true,
    "data": {
        "user": {
            "id": 1,
            "name": "Admin User",
            "email": "admin@example.com",
            "roles": ["Admin"]
        },
        "token": "1|xxxxxxxxxxxxxx",
        "expires_at": "2025-09-21T10:00:00Z"
    },
    "message": "Login successful"
}
```

#### POST /api/auth/logout
**Description**: User logout  
**Headers**: `Authorization: Bearer {token}`  
**Response**:
```json
{
    "success": true,
    "message": "Logout successful"
}
```

### 7.2.2 Data Management Endpoints

#### GET /api/kelompok
**Description**: Retrieve food groups  
**Parameters**:
- `page` (int): Page number untuk pagination
- `per_page` (int): Records per page (default: 15)
- `search` (string): Search dalam nama kelompok
- `status_aktif` (boolean): Filter by active status

**Response**:
```json
{
    "success": true,
    "data": {
        "data": [
            {
                "id": 1,
                "kode": "01",
                "nama": "Padi-padian",
                "deskripsi": "Cereals dan grains",
                "status_aktif": true,
                "created_at": "2025-01-01T00:00:00Z",
                "updated_at": "2025-01-01T00:00:00Z"
            }
        ],
        "meta": {
            "current_page": 1,
            "last_page": 5,
            "per_page": 15,
            "total": 75
        }
    }
}
```

#### POST /api/kelompok
**Description**: Create new food group  
**Headers**: `Authorization: Bearer {token}`  
**Permissions**: `create kelompok`  
**Request**:
```json
{
    "kode": "12",
    "nama": "Makanan Olahan",
    "deskripsi": "Processed food products",
    "status_aktif": true
}
```

#### PUT /api/kelompok/{id}
**Description**: Update existing food group  
**Headers**: `Authorization: Bearer {token}`  
**Permissions**: `edit kelompok`  

#### DELETE /api/kelompok/{id}
**Description**: Delete food group  
**Headers**: `Authorization: Bearer {token}`  
**Permissions**: `delete kelompok`  
**Response**:
```json
{
    "success": true,
    "message": "Kelompok deleted successfully"
}
```

### 7.2.3 Komoditi Endpoints

#### GET /api/komoditi
**Description**: Retrieve food commodities  
**Parameters**:
- `kelompok_kode` (string): Filter by food group code
- `page`, `per_page`, `search`, `status_aktif`: Standard parameters

**Response**:
```json
{
    "success": true,
    "data": {
        "data": [
            {
                "id": 1,
                "kode_kelompok": "01",
                "kode_komoditi": "0101",
                "nama": "Beras",
                "deskripsi": "Rice",
                "satuan": "kg",
                "status_aktif": true,
                "kelompok": {
                    "kode": "01",
                    "nama": "Padi-padian"
                }
            }
        ]
    }
}
```

#### GET /api/ketersediaan/api/komoditi
**Description**: Get commodities by food group (AJAX endpoint)  
**Parameters**:
- `kode_kelompok` (string): Food group code

**Response**:
```json
{
    "data": [
        {
            "value": "0101",
            "label": "Beras"
        },
        {
            "value": "0102", 
            "label": "Jagung"
        }
    ]
}
```

### 7.2.4 NBM Transaction Endpoints

#### GET /api/transaksi-nbm
**Description**: Retrieve NBM transaction data  
**Parameters**:
- `tahun` (int): Filter by year
- `bulan` (int): Filter by month
- `kelompok` (string): Filter by food group code
- `komoditi` (string): Filter by commodity code
- `page`, `per_page`: Pagination parameters

**Response**:
```json
{
    "success": true,
    "data": {
        "data": [
            {
                "id": 1,
                "kode_kelompok": "01",
                "kode_komoditi": "0101",
                "tahun": 2024,
                "bulan": 1,
                "status_angka": "tetap",
                "kalori_hari": 285.50,
                "protein_hari": 6.80,
                "lemak_hari": 0.70,
                "kelompok": {
                    "nama": "Padi-padian"
                },
                "komoditi": {
                    "nama": "Beras"
                }
            }
        ]
    }
}
```

#### POST /api/transaksi-nbm
**Description**: Create NBM transaction record  
**Headers**: `Authorization: Bearer {token}`  
**Permissions**: `create transaksi_nbm`  
**Request**:
```json
{
    "kode_kelompok": "01",
    "kode_komoditi": "0101",
    "tahun": 2024,
    "bulan": 1,
    "kalori_hari": 285.50,
    "protein_hari": 6.80,
    "lemak_hari": 0.70,
    "masukan": 1500.00,
    "keluaran": 1200.00
}
```

### 7.2.5 SUSENAS Endpoints

#### GET /api/konsumsi/api/laporan-susenas
**Description**: Query SUSENAS consumption data  
**Parameters**:
- `kelompok_bps` (int): BPS food group code
- `komoditi_bps` (int): BPS commodity code
- `tahun` (int): Data year
- `kode_wilayah` (string): Regional code

**Response**:
```json
{
    "success": true,
    "data": [
        {
            "kelompok_bps": 1,
            "komoditi_bps": 101,
            "tahun": 2024,
            "kode_wilayah": "32",
            "nama_wilayah": "Jawa Barat",
            "konsumsi_per_kapita_seminggu": 1.250,
            "konsumsi_per_kapita_setahun": 65.00,
            "kelompok_nama": "Padi-padian",
            "komoditi_nama": "Beras"
        }
    ]
}
```

### 7.2.6 Export Endpoints

#### POST /api/export/excel
**Description**: Generate Excel export  
**Headers**: `Authorization: Bearer {token}`  
**Permissions**: `export data`  
**Request**:
```json
{
    "table": "transaksi_nbm",
    "filters": {
        "tahun": 2024,
        "kelompok": "01"
    },
    "fields": [
        "kode_kelompok",
        "kode_komoditi", 
        "tahun",
        "kalori_hari"
    ],
    "format": "xlsx"
}
```

**Response**:
```json
{
    "success": true,
    "data": {
        "download_url": "/api/export/download/abc123",
        "filename": "transaksi_nbm_2024.xlsx",
        "expires_at": "2025-09-21T10:00:00Z"
    }
}
```

### 7.2.7 Dashboard API Endpoints

#### GET /api/dashboard-komoditas/summary
**Description**: Get dashboard summary statistics  
**Response**:
```json
{
    "success": true,
    "data": {
        "total_kelompok": 11,
        "total_komoditi": 156,
        "total_transaksi": 25000,
        "latest_year": 2024,
        "total_kalori_average": 2150.5
    }
}
```

#### GET /api/dashboard-komoditas/commodities
**Description**: Get commodity data untuk dashboard charts  
**Parameters**:
- `limit` (int): Number of records
- `year` (int): Filter by year

**Response**:
```json
{
    "success": true,
    "data": [
        {
            "nama_komoditi": "Beras",
            "kelompok_nama": "Padi-padian",
            "total_kalori": 285.50,
            "trend": "stable"
        }
    ]
}
```

## 7.3 FastAPI Machine Learning API

### 7.3.1 ML API Base Information
```
Base URL: http://localhost:8082
Documentation: http://localhost:8082/docs
OpenAPI Spec: http://localhost:8082/openapi.json
```

### 7.3.2 Health Check Endpoint

#### GET /health
**Description**: ML API health check  
**Response**:
```json
{
    "status": "healthy",
    "model_loaded": true,
    "api_version": "1.0.0",
    "timestamp": "2025-09-20T10:30:00Z"
}
```

### 7.3.3 Prediction Endpoints

#### POST /predict
**Description**: Single NBM prediction  
**Request Body**:
```json
{
    "data": [
        {
            "tahun": 2024,
            "bulan": 1,
            "kelompok": "Padi-padian",
            "komoditi": "Beras",
            "kalori_hari": 285.50
        },
        {
            "tahun": 2024,
            "bulan": 2,
            "kelompok": "Padi-padian", 
            "komoditi": "Beras",
            "kalori_hari": 290.20
        },
        {
            "tahun": 2024,
            "bulan": 3,
            "kelompok": "Padi-padian",
            "komoditi": "Beras", 
            "kalori_hari": 288.10
        },
        {
            "tahun": 2024,
            "bulan": 4,
            "kelompok": "Padi-padian",
            "komoditi": "Beras",
            "kalori_hari": 292.30
        },
        {
            "tahun": 2024,
            "bulan": 5,
            "kelompok": "Padi-padian",
            "komoditi": "Beras",
            "kalori_hari": 287.80
        },
        {
            "tahun": 2024,
            "bulan": 6,
            "kelompok": "Padi-padian",
            "komoditi": "Beras",
            "kalori_hari": 289.60
        }
    ]
}
```

**Response**:
```json
{
    "success": true,
    "prediction": 291.45,
    "confidence_interval": {
        "lower": 275.20,
        "upper": 307.70,
        "confidence_level": 0.95
    },
    "model_info": {
        "model_type": "HuberRegressor Ensemble",
        "mape_achieved": "8.88%",
        "training_date": "2025-08-15",
        "feature_count": 4
    },
    "input_summary": {
        "data_points": 6,
        "date_range": "2024-01 to 2024-06",
        "average_calories": 288.92,
        "commodity": "Beras",
        "group": "Padi-padian"
    },
    "timestamp": "2025-09-20T10:35:00Z"
}
```

#### POST /predict/batch
**Description**: Batch prediction untuk multiple sequences  
**Request Body**:
```json
{
    "predictions": [
        {
            "id": "seq_001",
            "data": [
                // 6 months data untuk sequence 1
            ]
        },
        {
            "id": "seq_002", 
            "data": [
                // 6 months data untuk sequence 2
            ]
        }
    ]
}
```

**Response**:
```json
{
    "success": true,
    "results": [
        {
            "id": "seq_001",
            "prediction": 291.45,
            "confidence_interval": {
                "lower": 275.20,
                "upper": 307.70
            },
            "success": true
        },
        {
            "id": "seq_002",
            "prediction": 156.80,
            "confidence_interval": {
                "lower": 148.30,
                "upper": 165.30
            },
            "success": true
        }
    ],
    "summary": {
        "total_predictions": 2,
        "successful_predictions": 2,
        "failed_predictions": 0,
        "processing_time_seconds": 1.25
    }
}
```

### 7.3.4 Model Information Endpoints

#### GET /model/info
**Description**: Get model metadata dan information  
**Response**:
```json
{
    "model_type": "HuberRegressor Ensemble with LSTM preprocessing",
    "version": "1.0.0",
    "training_date": "2025-08-15T14:30:00Z",
    "performance_metrics": {
        "mape": 8.88,
        "rmse": 12.45,
        "mae": 9.32,
        "r2_score": 0.92
    },
    "feature_info": {
        "input_features": ["kelompok", "komoditi", "tahun", "bulan", "kalori_hari"],
        "sequence_length": 6,
        "prediction_horizon": 1
    },
    "training_info": {
        "training_samples": 15000,
        "validation_samples": 3000,
        "test_samples": 2000,
        "epochs": 100,
        "early_stopping": true
    }
}
```

#### GET /model/stats
**Description**: Get detailed model statistics  
**Response**:
```json
{
    "model_performance": {
        "mape": 8.88,
        "rmse": 12.45,
        "mae": 9.32,
        "r2_score": 0.92,
        "prediction_accuracy_95ci": 87.5
    },
    "model_architecture": {
        "type": "Ensemble",
        "base_models": ["HuberRegressor", "RandomForestRegressor"],
        "preprocessing": "StandardScaler + LSTM features",
        "hyperparameters": {
            "huber_epsilon": 1.35,
            "random_forest_n_estimators": 100,
            "ensemble_weights": [0.7, 0.3]
        }
    },
    "training_data_info": {
        "total_samples": 20000,
        "date_range": "2010-01 to 2024-12",
        "commodities_covered": 156,
        "food_groups_covered": 11,
        "missing_data_percentage": 2.3
    },
    "feature_importance": [
        {
            "feature": "komoditi_encoded",
            "importance": 0.45,
            "description": "Commodity type (most important)"
        },
        {
            "feature": "kelompok_encoded", 
            "importance": 0.25,
            "description": "Food group category"
        },
        {
            "feature": "tahun_normalized",
            "importance": 0.20,
            "description": "Year trend"
        },
        {
            "feature": "bulan_cyclic",
            "importance": 0.10,
            "description": "Seasonal pattern"
        }
    ]
}
```

#### POST /model/validate
**Description**: Validate input data untuk prediction  
**Request Body**:
```json
{
    "data": [
        {
            "tahun": 2024,
            "bulan": 1,
            "kelompok": "Padi-padian",
            "komoditi": "Beras",
            "kalori_hari": 285.50
        }
    ]
}
```

**Response**:
```json
{
    "valid": true,
    "validation_results": [
        {
            "field": "tahun",
            "valid": true,
            "message": "Year within valid range"
        },
        {
            "field": "kalori_hari",
            "valid": true,
            "message": "Calorie value within expected range"
        }
    ],
    "data_quality_score": 0.95,
    "recommendations": []
}
```

## 7.4 Error Handling

### 7.4.1 Standard Error Response Format
```json
{
    "success": false,
    "error": {
        "code": "VALIDATION_ERROR",
        "message": "The given data was invalid.",
        "details": {
            "email": ["The email field is required."],
            "password": ["The password must be at least 8 characters."]
        }
    },
    "timestamp": "2025-09-20T10:40:00Z"
}
```

### 7.4.2 HTTP Status Codes

#### Success Codes
- `200 OK`: Successful GET, PUT, PATCH requests
- `201 Created`: Successful POST requests
- `204 No Content`: Successful DELETE requests

#### Client Error Codes
- `400 Bad Request`: Invalid request format atau parameters
- `401 Unauthorized`: Authentication required
- `403 Forbidden`: Insufficient permissions
- `404 Not Found`: Resource not found
- `422 Unprocessable Entity`: Validation errors
- `429 Too Many Requests`: Rate limit exceeded

#### Server Error Codes
- `500 Internal Server Error`: General server error
- `502 Bad Gateway`: External service unavailable
- `503 Service Unavailable`: Temporary service outage

### 7.4.3 ML API Specific Errors

#### Prediction Errors
```json
{
    "success": false,
    "error": {
        "code": "PREDICTION_ERROR",
        "message": "Insufficient historical data for prediction",
        "details": {
            "required_months": 6,
            "provided_months": 3,
            "missing_periods": ["2024-01", "2024-02", "2024-03"]
        }
    }
}
```

#### Model Errors
```json
{
    "success": false,
    "error": {
        "code": "MODEL_UNAVAILABLE",
        "message": "ML model is currently unavailable",
        "details": {
            "model_status": "loading",
            "estimated_ready_time": "2025-09-20T10:45:00Z"
        }
    }
}
```

## 7.5 Rate Limiting

### 7.5.1 Rate Limit Configuration

#### Laravel API Endpoints
- **Authentication**: 10 requests per minute
- **Data Queries**: 100 requests per minute
- **Data Modifications**: 30 requests per minute
- **Export Operations**: 5 requests per minute

#### FastAPI ML Endpoints
- **Health Check**: 60 requests per minute
- **Single Prediction**: 20 requests per minute
- **Batch Prediction**: 5 requests per minute
- **Model Info**: 30 requests per minute

### 7.5.2 Rate Limit Headers
```
X-RateLimit-Limit: 100
X-RateLimit-Remaining: 85
X-RateLimit-Reset: 1695110400
```

### 7.5.3 Rate Limit Exceeded Response
```json
{
    "success": false,
    "error": {
        "code": "RATE_LIMIT_EXCEEDED",
        "message": "Too many requests. Please try again later.",
        "details": {
            "limit": 100,
            "reset_time": "2025-09-20T11:00:00Z"
        }
    }
}
```

## 7.6 API Security

### 7.6.1 Authentication Security
- **HTTPS Only**: All API communication encrypted
- **Token Expiration**: Bearer tokens expire after 24 hours
- **Token Refresh**: Automatic token refresh mechanism
- **Request Signing**: Critical operations require request signing

### 7.6.2 Input Validation
- **Schema Validation**: All inputs validated against JSON schemas
- **SQL Injection Prevention**: Parameterized queries only
- **XSS Prevention**: Output encoding dan sanitization
- **File Upload Security**: Type dan size validation

### 7.6.3 API Monitoring
- **Request Logging**: All API requests logged
- **Performance Monitoring**: Response time tracking
- **Error Tracking**: Automated error alerting
- **Security Scanning**: Regular vulnerability assessments