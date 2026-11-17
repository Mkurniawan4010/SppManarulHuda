<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Pembayaran SPP</title>
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
        <h1>LAPORAN PEMBAYARAN SPP</h1>
        <p>Aplikasi Pembayaran SPP Manarul Huda</p>
        <p>Periode: {{ $bulan_nama }} {{ $tahun }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>NISN</th>
                <th>Nama Siswa</th>
                <th>Kelas</th>
                <th>Tahun SPP</th>
                <th>Jumlah Bayar</th>
                <th>Petugas</th>
            </tr>
        </thead>
        <tbody>
            @if(count($pembayaran) > 0)
                @php $total = 0; @endphp
                @foreach($pembayaran as $index => $p)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ \Carbon\Carbon::parse($p->tgl_bayar)->translatedFormat('d/m/Y') }}</td>
                        <td>{{ $p->nisn }}</td>
                        <td>{{ $p->siswa->nama ?? 'N/A' }}</td>
                        <td>{{ $p->siswa->kelas->nama_kelas ?? 'N/A' }}</td>
                        <td>{{ $p->spp->tahun ?? 'N/A' }}</td>
                        <td>Rp {{ number_format($p->jumlah_bayar, 0, ',', '.') }}</td>
                        <td>{{ $p->petugas->nama_petugas ?? 'N/A' }}</td>
                    </tr>
                    @php $total += $p->jumlah_bayar; @endphp
                @endforeach
                <tr>
                    <td colspan="6" style="text-align: right;"><strong>Total</strong></td>
                    <td colspan="2"><strong>Rp {{ number_format($total, 0, ',', '.') }}</strong></td>
                </tr>
            @else
                <tr>
                    <td colspan="8" style="text-align: center;">Tidak ada data pembayaran</td>
                </tr>
            @endif
        </tbody>
    </table>

    <div class="footer">
        <p>Dicetak pada tanggal: {{ \Carbon\Carbon::now()->translatedFormat('d/m/Y') }}</p>
    </div>
</body>
</html>




