<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Daftar Alamat</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header h1 {
            font-size: 18px;
            margin-bottom: 5px;
        }
        .header p {
            font-size: 12px;
            color: #666;
        }
        .filters {
            background-color: #f8f9fa;
            padding: 15px;
            margin-bottom: 20px;
            border: 1px solid #dee2e6;
        }
        .filters h3 {
            margin-top: 0;
            font-size: 14px;
        }
        .filter-item {
            display: inline-block;
            margin-right: 20px;
            margin-bottom: 5px;
        }
        .filter-label {
            font-weight: bold;
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
            background-color: #f8f9fa;
            font-weight: bold;
        }
        .status-aktif {
            background-color: #d4edda;
            color: #155724;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 10px;
        }
        .status-tidak-aktif {
            background-color: #f8d7da;
            color: #721c24;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 10px;
        }
        .status-draft {
            background-color: #fff3cd;
            color: #856404;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 10px;
        }
        .status-arsip {
            background-color: #e2e3e5;
            color: #383d41;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 10px;
        }
        .status-pending {
            background-color: #d1ecf1;
            color: #0c5460;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 10px;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
        }
        .truncate {
            max-width: 200px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Laporan Daftar Alamat Dinas Pertanian</h1>
        <p>Dicetak pada: {{ now()->format('d F Y H:i:s') }}</p>
    </div>

    @if(!empty($filters))
    <div class="filters">
        <h3>Filter yang Diterapkan:</h3>
        @if(!empty($filters['date_from']) && !empty($filters['date_to']))
            <div class="filter-item">
                <span class="filter-label">Periode:</span> 
                {{ \Carbon\Carbon::parse($filters['date_from'])->format('d/m/Y') }} - 
                {{ \Carbon\Carbon::parse($filters['date_to'])->format('d/m/Y') }}
            </div>
        @endif
        @if(!empty($filters['status']))
            <div class="filter-item">
                <span class="filter-label">Status:</span> {{ $filters['status'] }}
            </div>
        @endif
        @if(!empty($filters['kategori']))
            <div class="filter-item">
                <span class="filter-label">Kategori:</span> {{ $filters['kategori'] }}
            </div>
        @endif
        @if(!empty($filters['wilayah']))
            <div class="filter-item">
                <span class="filter-label">Wilayah:</span> {{ $filters['wilayah'] }}
            </div>
        @endif
    </div>
    @endif

    <table>
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 15%;">Wilayah</th>
                <th style="width: 20%;">Nama Dinas</th>
                <th style="width: 25%;">Alamat</th>
                <th style="width: 15%;">Kontak</th>
                <th style="width: 10%;">Status</th>
                <th style="width: 10%;">Kategori</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data as $index => $alamat)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $alamat->wilayah }}</td>
                    <td>{{ $alamat->nama_dinas }}</td>
                    <td class="truncate">{{ $alamat->alamat }}</td>
                    <td>
                        @if($alamat->telp)
                            <div>{{ $alamat->telp }}</div>
                        @endif
                        @if($alamat->email)
                            <div style="font-size: 10px;">{{ $alamat->email }}</div>
                        @endif
                    </td>
                    <td>
                        <span class="status-{{ strtolower(str_replace(' ', '-', $alamat->status)) }}">
                            {{ $alamat->status }}
                        </span>
                    </td>
                    <td>{{ $alamat->kategori ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center; padding: 20px;">
                        Tidak ada data yang ditemukan
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Total: {{ $data->count() }} alamat dinas pertanian</p>
        <p>Laporan ini dibuat secara otomatis oleh Sistem Basis Data Konsumsi Pangan</p>
    </div>
</body>
</html>
