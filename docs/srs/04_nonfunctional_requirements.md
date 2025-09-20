# 5. OTHER NONFUNCTIONAL REQUIREMENTS

## 5.1 Performance Requirements

### 5.1.1 Response Time Requirements

#### REQ-PERF-001: Web Page Load Times
**Standard Pages**:
- Dashboard: ≤ 2 seconds initial load, ≤ 1 second subsequent loads
- Data tables: ≤ 3 seconds untuk 1000 records dengan pagination
- Forms: ≤ 1 second load time
- Static content: ≤ 500ms

**Large Data Operations**:
- Report generation: ≤ 30 seconds untuk complex reports
- Data export: ≤ 60 seconds untuk 10,000 records
- Import validation: ≤ 45 seconds untuk 5,000 records

#### REQ-PERF-002: API Response Times
**Standard API Endpoints**:
- CRUD operations: ≤ 500ms average response time
- Search queries: ≤ 1 second untuk filtered results
- Authentication: ≤ 200ms untuk login validation

**Machine Learning API**:
- Single prediction: ≤ 2 seconds
- Batch prediction (100 records): ≤ 30 seconds
- Model statistics: ≤ 500ms
- Health check: ≤ 100ms

#### REQ-PERF-003: Database Query Performance
**Query Response Times**:
- Simple SELECT queries: ≤ 100ms
- Complex JOIN queries: ≤ 500ms
- Aggregation queries: ≤ 1 second
- Full-text search: ≤ 2 seconds

### 5.1.2 Throughput Requirements

#### REQ-PERF-004: Concurrent Users
**User Load Support**:
- Concurrent active users: 500 simultaneous users
- Peak load handling: 1000 concurrent users untuk short periods
- Graceful degradation beyond peak capacity
- Queue management untuk resource-intensive operations

#### REQ-PERF-005: Data Processing Throughput
**Data Operations**:
- Record insertion: 1000 records/minute
- Bulk import: 10,000 records dalam 5 minutes
- Export generation: 50,000 records dalam 2 minutes
- ML predictions: 100 predictions/minute

### 5.1.3 Resource Utilization

#### REQ-PERF-006: Server Resource Limits
**CPU Utilization**:
- Normal operations: ≤ 70% average CPU usage
- Peak load: ≤ 90% CPU usage (maximum 5 minutes)
- ML inference: Dedicated 2 CPU cores untuk prediction tasks

**Memory Usage**:
- Application memory: ≤ 4GB normal operations
- Database buffer: ≤ 2GB untuk query caching
- File upload buffer: ≤ 512MB per concurrent upload

**Storage I/O**:
- Database IOPS: ≤ 5000 IOPS normal operations
- File system I/O: ≤ 1000 IOPS untuk uploads/exports
- Log writing: ≤ 100 IOPS continuous logging

## 5.2 Safety Requirements

### 5.2.1 Data Safety

#### REQ-SAFETY-001: Data Backup and Recovery
**Backup Requirements**:
- Automated daily database backups
- Incremental backups every 6 hours
- File system backups daily
- Backup retention: 30 days untuk daily, 12 months untuk monthly
- Cross-site backup replication

**Recovery Objectives**:
- Recovery Time Objective (RTO): ≤ 4 hours
- Recovery Point Objective (RPO): ≤ 1 hour data loss maximum
- Automated recovery procedures
- Disaster recovery testing quarterly

#### REQ-SAFETY-002: System Fault Tolerance
**Failure Handling**:
- Graceful degradation pada component failures
- Automatic failover untuk critical services
- Circuit breaker pattern untuk external service calls
- Health monitoring dengan automatic alerts

**Error Recovery**:
- Transaction rollback pada data errors
- Automatic retry untuk transient failures
- User-friendly error messages
- Error logging dan tracking

### 5.2.2 Operational Safety

#### REQ-SAFETY-003: Input Validation
**Data Validation**:
- Comprehensive input sanitization
- SQL injection prevention
- XSS attack prevention
- File upload validation (type, size, content)
- Rate limiting untuk API endpoints

#### REQ-SAFETY-004: System Monitoring
**Monitoring Requirements**:
- Real-time system health monitoring
- Performance metrics tracking
- Error rate monitoring
- User activity monitoring
- Automated alert system

## 5.3 Security Requirements

### 5.3.1 Authentication and Authorization

#### REQ-SEC-001: User Authentication
**Authentication Mechanisms**:
- Strong password policies (minimum 8 characters, complexity requirements)
- Multi-factor authentication support (optional)
- Account lockout after 5 failed attempts
- Password history (prevent reuse of last 5 passwords)
- Password expiration (90 days untuk admin accounts)

**Session Security**:
- Secure session token generation
- Session timeout (2 hours inactivity)
- Session fixation prevention
- Concurrent session control

#### REQ-SEC-002: Authorization System
**Role-Based Access Control**:
- Principle of least privilege
- Fine-grained permission system
- Role inheritance support
- Permission caching untuk performance
- Regular access review requirements

### 5.3.2 Data Protection

#### REQ-SEC-003: Data Encryption
**Data at Rest**:
- Database encryption untuk sensitive fields
- File system encryption untuk uploads
- Backup encryption with separate keys
- Key rotation procedures

**Data in Transit**:
- HTTPS enforcement (TLS 1.2+)
- Database connection encryption
- API communication encryption
- Email encryption untuk sensitive data

#### REQ-SEC-004: Data Privacy
**Personal Data Protection**:
- Data minimization principles
- Consent management
- Right to data deletion
- Data access logging
- Privacy policy compliance

### 5.3.3 Application Security

#### REQ-SEC-005: Web Application Security
**OWASP Top 10 Compliance**:
- SQL injection prevention
- XSS protection
- CSRF protection
- Insecure direct object reference prevention
- Security misconfiguration prevention

**Security Headers**:
- Content Security Policy (CSP)
- HTTP Strict Transport Security (HSTS)
- X-Frame-Options
- X-Content-Type-Options
- Referrer Policy

#### REQ-SEC-006: API Security
**API Protection**:
- Rate limiting per endpoint
- Input validation dan sanitization
- Output encoding
- Authentication token validation
- API audit logging

### 5.3.4 Infrastructure Security

#### REQ-SEC-007: Network Security
**Network Protection**:
- Firewall configuration
- Intrusion detection system
- VPN access untuk administrative tasks
- Network segmentation
- DDoS protection

#### REQ-SEC-008: Server Security
**Server Hardening**:
- Operating system hardening
- Regular security updates
- Unnecessary service removal
- File permission management
- Audit logging

## 5.4 Software Quality Attributes

### 5.4.1 Reliability

#### REQ-QUAL-001: System Availability
**Uptime Requirements**:
- System availability: 99.5% (≈ 3.6 hours downtime/month)
- Planned maintenance windows: 4 hours/month maximum
- Unplanned downtime: ≤ 30 minutes/month
- Service degradation notification

**Fault Tolerance**:
- Component redundancy untuk critical services
- Automatic error recovery
- Graceful degradation patterns
- Circuit breaker implementation

#### REQ-QUAL-002: Data Integrity
**Data Consistency**:
- ACID transaction support
- Foreign key constraint enforcement
- Data validation at all layers
- Audit trail untuk data changes
- Backup verification procedures

### 5.4.2 Scalability

#### REQ-QUAL-003: Horizontal Scalability
**Scale-out Capabilities**:
- Load balancer support
- Stateless application design
- Database read replica support
- CDN integration untuk static content
- Microservices architecture readiness

#### REQ-QUAL-004: Vertical Scalability
**Scale-up Requirements**:
- CPU scaling support up to 16 cores
- Memory scaling up to 32GB
- Storage scaling up to 10TB
- Network bandwidth scaling

### 5.4.3 Maintainability

#### REQ-QUAL-005: Code Quality
**Development Standards**:
- PSR-12 coding standards untuk PHP
- PEP 8 standards untuk Python
- Comprehensive code documentation
- Unit test coverage ≥ 80%
- Integration test coverage ≥ 60%

#### REQ-QUAL-006: System Monitoring
**Observability Requirements**:
- Application performance monitoring (APM)
- Log aggregation dan analysis
- Metrics collection dan visualization
- Distributed tracing support
- Error tracking dan alerting

### 5.4.4 Usability

#### REQ-QUAL-007: User Experience
**Usability Standards**:
- Intuitive navigation structure
- Consistent UI/UX patterns
- Mobile-responsive design
- Accessibility compliance (WCAG 2.1 AA)
- User feedback collection

#### REQ-QUAL-008: Documentation
**User Documentation**:
- Comprehensive user manual
- Video tutorials
- Context-sensitive help
- FAQ section
- Getting started guide

## 5.5 Business Rules

### 5.5.1 Data Management Rules

#### REQ-BIZ-001: Data Validation Rules
**NBM Data Rules**:
- Tahun data: 1990-2030 range
- Bulan: 1-12 valid range
- Kalori per hari: 0-1000 reasonable range
- Negative values: Only allowed untuk specific fields (perubahan_stok, ekspor-impor balance)

**Referential Integrity**:
- Komoditi must belong to valid kelompok
- Transaksi must reference existing komoditi
- User permissions must reference valid roles

#### REQ-BIZ-002: Data Quality Rules
**Completeness Requirements**:
- Core fields (kelompok, komoditi, tahun, kalori_hari) must be present
- Data gaps ≤ 5% untuk any given year
- Missing value handling procedures
- Data source attribution required

**Consistency Rules**:
- Cross-field validation (total consumption = sum of components)
- Temporal consistency checks (reasonable year-over-year changes)
- Geographic consistency validation

### 5.5.2 User Access Rules

#### REQ-BIZ-003: Role Assignment Rules
**User Role Constraints**:
- Super Admin: Maximum 5 users
- Admin: No limit, but requires approval
- Public: Self-registration allowed with verification
- Role changes require Super Admin approval

#### REQ-BIZ-004: Data Access Rules
**Permission-Based Access**:
- Users can only access data within their permission scope
- Data modification requires appropriate write permissions
- Export permissions separate from view permissions
- Audit logging untuk all data access

### 5.5.3 Machine Learning Rules

#### REQ-BIZ-005: Prediction Rules
**Model Input Validation**:
- Minimum 6 months historical data required
- Data must be chronologically ordered
- No gaps in time series data
- Input data must pass quality checks

**Prediction Output**:
- Confidence interval must be provided
- Prediction validity period: 30 days
- Model performance metrics displayed
- Uncertainty quantification required

### 5.5.4 Export and Reporting Rules

#### REQ-BIZ-006: Export Limitations
**Export Constraints**:
- Maximum 50,000 records per export untuk Excel
- No limit untuk CSV exports
- File size limit: 100MB
- Export history retention: 30 days

#### REQ-BIZ-007: Data Sharing Rules
**Public Data Rules**:
- Aggregated data only untuk public access
- No personal/sensitive information exposure
- Data must be at least 3 months old untuk public release
- Attribution requirements untuk data usage