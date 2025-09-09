<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Benih & Pupuk</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            font-size: 18px;
            color: #333;
        }
        .header p {
            margin: 5px 0;
            color: #666;
        }
        .filters {
            background-color: #f8f9fa;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        .filters h3 {
            margin-top: 0;
            font-size: 14px;
            color: #333;
        }
        .filter-item {
            display: inline-block;
            margin-right: 20px;
            margin-bottom: 5px;
        }
        .filter-label {
            font-weight: bold;
            color: #555;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
            color: #333;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
        .no-data {
            text-align: center;
            padding: 40px;
            color: #666;
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Laporan Data Benih & Pupuk</h1>
        <p>Kementerian Pertanian Republik Indonesia</p>
        <p>Tanggal: {{ date('d F Y') }}</p>
    </div>

    <div class="filters">
        <h3>Filter Laporan:</h3>
        @if(!empty($filters['tahun']))
            <div class="filter-item">
                <span class="filter-label">Tahun:</span> {{ $filters['tahun'] }}
            </div>
        @endif
        @if(!empty($filters['bulan']))
            <div class="filter-item">
                <span class="filter-label">Bulan:</span> {{ $filters['bulan'] }}
            </div>
        @endif
        @if(!empty($filters['wilayah']))
            <div class="filter-item">
                <span class="filter-label">Wilayah:</span> {{ $filters['wilayah'] }}
            </div>
        @endif
        @if(!empty($filters['variabel']))
            <div class="filter-item">
                <span class="filter-label">Variabel:</span> {{ $filters['variabel'] }}
            </div>
        @endif
        @if(!empty($filters['klasifikasi']))
            <div class="filter-item">
                <span class="filter-label">Klasifikasi:</span> {{ $filters['klasifikasi'] }}
            </div>
        @endif
        @if(!empty($filters['status']))
            <div class="filter-item">
                <span class="filter-label">Status:</span> 
                @switch($filters['status'])
                    @case('A') Aktif @break
                    @case('I') Tidak Aktif @break
                    @case('D') Draft @break
                    @default {{ $filters['status'] }}
                @endswitch
            </div>
        @endif
    </div>

    @if($data->count() > 0)
        <table>
            <thead>
                <tr>
                    <th class="text-center">No</th>
                    <th>Tahun</th>
                    <th>Bulan</th>
                    <th>Wilayah</th>
                    <th>Variabel</th>
                    <th>Klasifikasi</th>
                    <th class="text-right">Nilai</th>
                    <th class="text-center">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $index => $item)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>{{ $item->tahun }}</td>
                        <td>{{ $item->bulan }}</td>
                        <td>{{ $item->wilayah }}</td>
                        <td>{{ $item->variabel }}</td>
                        <td>{{ $item->klasifikasi }}</td>
                        <td class="text-right">{{ number_format($item->nilai, 2, ',', '.') }}</td>
                        <td class="text-center">
                            @switch($item->status)
                                @case('A') Aktif @break
                                @case('I') Tidak Aktif @break
                                @case('D') Draft @break
                                @default {{ $item->status }}
                            @endswitch
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="footer">
            <p>Total {{ $data->count() }} record(s) | Digenerate pada {{ date('d F Y H:i:s') }}</p>
        </div>
    @else
        <div class="no-data">
            <p>Tidak ada data yang sesuai dengan filter yang dipilih.</p>
        </div>
    @endif
</body>
</html>
