<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Siswa Belum Bayar SPP</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            padding: 0;
            font-size: 18px;
        }
        .header p {
            margin: 5px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .footer {
            margin-top: 30px;
            text-align: right;
        }
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN SISWA BELUM BAYAR BULAN INI</h1>
        <p>Aplikasi Pembayaran SPP Manarul Huda</p>
        <p>Periode: {{ $bulan_nama }} {{ $tahun }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>NISN</th>
                <th>Nama Siswa</th>
                <th>Kelas</th>
                <th>Status</th>
                <th>Terakhir Bayar</th>
            </tr>
        </thead>
        <tbody>
            @if(count($siswa_belum_bayar) > 0)
                @foreach($siswa_belum_bayar as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item['siswa']->nisn }}</td>
                        <td>{{ $item['siswa']->nama }}</td>
                        <td>{{ $item['siswa']->kelas->nama_kelas ?? 'N/A' }}</td>
                        <td>Belum Bayar Bulan Ini</td>
                        <td>{{ $item['bulan_terakhir_bayar'] ?? 'Belum Pernah Bayar' }}</td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="6" style="text-align: center;">Semua siswa sudah membayar SPP</td>
                </tr>
            @endif
        </tbody>
    </table>

    <div class="footer">
        <p>Dicetak pada tanggal: {{ \Carbon\Carbon::now()->translatedFormat('d/m/Y') }}</p>
    </div>
</body>
</html>






