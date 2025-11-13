<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Hasil Prediksi NBM - {{ $komoditi_name }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 10pt;
            margin: 20px;
        }
        h1 {
            color: #1e40af;
            border-bottom: 3px solid #1e40af;
            padding-bottom: 10px;
        }
        h2 {
            color: #374151;
            margin-top: 20px;
            border-bottom: 1px solid #d1d5db;
            padding-bottom: 5px;
        }
        .header-info {
            background-color: #eff6ff;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .header-info p {
            margin: 5px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
        }
        th {
            background-color: #1e40af;
            color: white;
            padding: 10px;
            text-align: left;
            font-weight: bold;
        }
        td {
            padding: 8px;
            border-bottom: 1px solid #e5e7eb;
        }
        tr:nth-child(even) {
            background-color: #f9fafb;
        }
        .prediction-value {
            font-weight: bold;
            color: #1e40af;
        }
        .confidence-value {
            color: #6b7280;
            font-size: 9pt;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 8pt;
            color: #6b7280;
            border-top: 1px solid #e5e7eb;
            padding-top: 10px;
        }
        .warning {
            background-color: #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 10px;
            margin: 15px 0;
        }
    </style>
</head>
<body>
    <h1>Hasil Prediksi Konsumsi Pangan NBM</h1>
    
    <div class="header-info">
        <p><strong>Komoditi:</strong> {{ $komoditi_name }} ({{ $kelompok_name }})</p>
        <p><strong>Jumlah Prediksi:</strong> {{ $bulan_prediksi }} bulan ke depan</p>
        <p><strong>Tanggal Export:</strong> {{ $export_date }}</p>
        <p><strong>Model:</strong> LSTM Enhanced Ensemble v1.0</p>
    </div>

    <h2>Data Prediksi</h2>
    
    <table>
        <thead>
            <tr>
                <th style="width: 20%;">Periode</th>
                <th style="width: 25%;">Prediksi Kalori/Hari</th>
                <th style="width: 20%;">Batas Bawah</th>
                <th style="width: 20%;">Batas Atas</th>
                <th style="width: 15%;">Margin</th>
            </tr>
        </thead>
        <tbody>
            @foreach($predictions as $idx => $pred)
                @php
                    $lastHistorical = !empty($historical) ? $historical[0] : null;
                    $periodLabel = 'Bulan +' . ($idx + 1);
                    
                    if ($lastHistorical) {
                        $futureMonth = (int)$lastHistorical['bulan'] + $idx + 1;
                        $futureYear = (int)$lastHistorical['tahun'] + floor(($futureMonth - 1) / 12);
                        $month = (($futureMonth - 1) % 12) + 1;
                        $periodLabel = $futureYear . '-' . str_pad($month, 2, '0', STR_PAD_LEFT);
                    }
                    
                    $ci = $confidence_intervals[$idx] ?? null;
                @endphp
                <tr>
                    <td>{{ $periodLabel }}</td>
                    <td class="prediction-value">{{ number_format($pred, 2) }}</td>
                    <td class="confidence-value">{{ $ci ? number_format($ci['lower_bound'], 2) : '-' }}</td>
                    <td class="confidence-value">{{ $ci ? number_format($ci['upper_bound'], 2) : '-' }}</td>
                    <td class="confidence-value">{{ $ci ? '±' . number_format($ci['margin_percent'], 1) . '%' : '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h2>Data Historis (Input Model)</h2>
    
    <table>
        <thead>
            <tr>
                <th style="width: 15%;">Tahun</th>
                <th style="width: 15%;">Bulan</th>
                <th style="width: 40%;">Komoditi</th>
                <th style="width: 30%;">Kalori/Hari</th>
            </tr>
        </thead>
        <tbody>
            @foreach($historical as $item)
                <tr>
                    <td>{{ $item['tahun'] }}</td>
                    <td>{{ $item['bulan'] }}</td>
                    <td>{{ $komoditi_name }}</td>
                    <td>{{ number_format($item['kalori_hari'], 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="warning">
        <strong>Catatan:</strong> Hasil prediksi ini dihasilkan menggunakan model Machine Learning LSTM yang dilatih 
        dengan data historis lengkap (1993-2024). Model menganalisis pola konsumsi dari 6 bulan terakhir untuk 
        menghasilkan proyeksi yang akurat. Confidence interval menunjukkan tingkat ketidakpastian prediksi.
    </div>

    <div class="footer">
        <p>Sistem Informasi Ketersediaan Pangan dan Lahan Pertanian (SIKOLBIA)</p>
        <p>© {{ date('Y') }} - Dinas Pangan dan Pertanian</p>
    </div>
</body>
</html>
