# UML Diagrams untuk Sistem Prediksi NBM

## Activity Diagram - Alur Prediksi Konsumsi Kalori

```mermaid
flowchart TD
    Start([Start: User Login]) --> A[Pilih Menu Prediksi NBM]
    A --> B[Tampilkan Form Parameter]
    B --> C[Input Parameter:<br/>- Kelompok Komoditas<br/>- Jenis Komoditi<br/>- Jumlah Bulan]
    C --> D[Submit Form]
    D --> E{Validasi<br/>Input?}
    
    E -->|Tidak Valid| F[Tampilkan Error Validasi]
    F --> End1([End])
    
    E -->|Valid| G[Query Data Historis<br/>6 Bulan Terakhir<br/>dari MySQL]
    G --> H{Data<br/>Cukup?}
    
    H -->|Tidak| I[Warning: Data<br/>Tidak Mencukupi]
    I --> End2([End])
    
    H -->|Ya| J[Format Data<br/>ke JSON Payload]
    J --> K[POST Request<br/>ke FastAPI /predict]
    K --> L{Payload<br/>Valid?}
    
    L -->|Tidak| M[Error 422<br/>Validation Failed]
    M --> N[Log Error &<br/>Tampilkan Pesan]
    N --> End3([End])
    
    L -->|Ya| O[Load Model LSTM<br/>dari Memory]
    O --> P[Preprocessing:<br/>- MinMaxScaler<br/>- Create Sequences]
    P --> Q[LSTM Inference<br/>Forward Pass]
    Q --> R[HuberRegressor<br/>Prediction]
    R --> S[Ensemble Weighting<br/>70% LSTM + 30% Huber]
    S --> T[Hitung Confidence<br/>Interval ±15%]
    T --> U[Return JSON Response<br/>ke Laravel]
    U --> V[Parse Results &<br/>Simpan ke Database]
    V --> W[Render Hasil:<br/>- Tabel Prediksi<br/>- Grafik Tren<br/>- Confidence Interval]
    W --> X{User Perlu<br/>Export?}
    
    X -->|Tidak| End4([End])
    
    X -->|Ya| Y[Klik Export to Excel]
    Y --> Z[Set Session Data &<br/>Redirect Export Route]
    Z --> AA[Generate Excel<br/>Maatwebsite]
    AA --> AB[Stream File .xlsx]
    AB --> AC[User Download File]
    AC --> End5([End])
    
    style Start fill:#90EE90
    style End1 fill:#FFB6C1
    style End2 fill:#FFB6C1
    style End3 fill:#FFB6C1
    style End4 fill:#90EE90
    style End5 fill:#90EE90
    style E fill:#FFE4B5
    style H fill:#FFE4B5
    style L fill:#FFE4B5
    style X fill:#FFE4B5
    style O fill:#87CEEB
    style P fill:#87CEEB
    style Q fill:#87CEEB
    style R fill:#87CEEB
    style S fill:#87CEEB
```

## Sequence Diagram - Interaksi Sistem Prediksi NBM

```mermaid
sequenceDiagram
    actor User as User<br/>(Pemerintah)
    participant Browser as Browser<br/>(Livewire)
    participant Laravel as Laravel<br/>Controller
    participant MySQL as MySQL<br/>Database
    participant FastAPI as FastAPI<br/>ML Service
    participant LSTM as LSTM<br/>Model
    participant Huber as HuberRegressor
    participant Redis as Redis<br/>Cache
    
    User->>Browser: Akses halaman prediksi
    Browser->>Laravel: GET /admin/konsumsi-pangan/prediksi-nbm
    Laravel-->>Browser: Return view form prediksi
    
    User->>Browser: Input parameter (Kelompok, Komoditi, Bulan)
    Browser->>Laravel: POST /run dengan parameters
    activate Laravel
    
    Laravel->>Laravel: Validasi input (Form Request)
    
    alt Validasi Gagal
        Laravel-->>Browser: Error 422 (Validation Failed)
        Browser-->>User: Tampilkan error validasi
    else Validasi Berhasil
        Laravel->>MySQL: Query 6 bulan data historis
        activate MySQL
        MySQL-->>Laravel: Return 6 records
        deactivate MySQL
        
        alt Data < 6 bulan
            Laravel-->>Browser: Warning: Data tidak mencukupi
            Browser-->>User: Tampilkan peringatan
        else Data Cukup
            Laravel->>Laravel: Format payload JSON
            Laravel->>FastAPI: POST /predict (JSON payload)
            activate FastAPI
            
            FastAPI->>FastAPI: Validasi Pydantic Model
            
            alt Payload Invalid
                FastAPI-->>Laravel: 422 Unprocessable Entity
                Laravel-->>Browser: Error: Invalid format
            else Payload Valid
                FastAPI->>FastAPI: Preprocessing (MinMaxScaler, Sequences)
                
                FastAPI->>LSTM: Predict(X_sequences)
                activate LSTM
                LSTM->>LSTM: Forward pass (gates computation)
                LSTM-->>FastAPI: LSTM predictions [y1..y6]
                deactivate LSTM
                
                FastAPI->>Huber: Predict(X_features)
                activate Huber
                Huber->>Huber: Huber loss regression
                Huber-->>FastAPI: Huber predictions [h1..h6]
                deactivate Huber
                
                FastAPI->>FastAPI: Ensemble: 0.7*LSTM + 0.3*Huber
                FastAPI->>FastAPI: Inverse transform scaler
                FastAPI->>FastAPI: Hitung CI (±15%)
                
                FastAPI-->>Laravel: 200 OK (JSON response)
                deactivate FastAPI
                
                Laravel->>Laravel: Parse JSON response
                
                Laravel->>MySQL: INSERT prediction_histories
                activate MySQL
                MySQL-->>Laravel: Insert success (ID: 1234)
                deactivate MySQL
                
                Laravel->>Redis: Cache prediction (TTL: 24h)
                activate Redis
                Redis-->>Laravel: Cache stored
                deactivate Redis
                
                Laravel-->>Browser: JSON response (predictions, chart_data)
                Browser->>Browser: Render tabel, chart, CI bars
                Browser-->>User: Tampilkan hasil dengan visualisasi
                
                User->>Browser: Klik "Export to Excel"
                Browser->>Laravel: GET /export-prediksi?history_id=1234
                
                Laravel->>Laravel: Set session export_id
                Laravel->>MySQL: SELECT prediction record
                activate MySQL
                MySQL-->>Laravel: Return prediction data
                deactivate MySQL
                
                Laravel->>Laravel: Generate Excel (Maatwebsite)
                Laravel-->>Browser: Stream .xlsx file
                Browser-->>User: Download Excel file
            end
        end
    end
    
    deactivate Laravel
```

## Penjelasan Diagram

### Activity Diagram
Activity diagram menggambarkan alur lengkap proses prediksi konsumsi kalori NBM dari perspektif user workflow:
1. **Input Phase**: User login dan input parameter prediksi
2. **Validation Phase**: Sistem validasi input dan ketersediaan data historis
3. **Prediction Phase**: Integrasi Laravel dengan FastAPI ML service untuk inference
4. **Output Phase**: Rendering hasil prediksi dengan opsi export ke Excel

### Sequence Diagram
Sequence diagram menunjukkan interaksi temporal antar komponen sistem:
1. **User Interface Layer**: Browser (Livewire) sebagai reactive frontend
2. **Application Layer**: Laravel Controller untuk business logic
3. **Data Layer**: MySQL untuk persistence, Redis untuk caching
4. **ML Layer**: FastAPI service dengan LSTM dan HuberRegressor models
5. **Message Flow**: HTTP requests, database queries, dan model inference calls

## Rendering Diagram

### PlantUML (untuk dokumen formal):
```bash
# Install PlantUML
apt-get install plantuml

# Generate PNG
plantuml activity_diagram.puml
plantuml sequence_diagram.puml
```

### Mermaid (untuk GitHub/Markdown):
Diagram Mermaid akan otomatis ter-render di GitHub, GitLab, dan beberapa Markdown viewer modern.

### Online Tools:
- PlantUML Online Editor: https://www.plantuml.com/plantuml/
- Mermaid Live Editor: https://mermaid.live/
