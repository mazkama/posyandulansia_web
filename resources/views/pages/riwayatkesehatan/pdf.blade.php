<!DOCTYPE html>
<html>
<head>
    <title>Laporan Pemeriksaan Kesehatan Lansia</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
        }
        h1 {
            font-size: 18px;
            text-align: center;
            margin-bottom: 10px;
        }
        h2 {
            font-size: 14px;
            margin-bottom: 5px;
        }
        p {
            margin: 5px 0;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 1px solid #000;
            padding-bottom: 10px;
        }
        .info {
            margin-bottom: 15px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        table, th, td {
            border: 1px solid #333;
        }
        th {
            background-color: #f2f2f2;
            padding: 6px 4px;
            text-align: center;
            font-size: 11px;
        }
        td {
            padding: 5px 4px;
            font-size: 10px;
        }
        .footer {
            margin-top: 20px;
            text-align: right;
        }
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN PEMERIKSAAN KESEHATAN LANSIA</h1>
        <p>Tanggal Cetak: {{ $date }}</p>
    </div>

    <div class="info">
        <h2>Informasi Jadwal</h2>
        <table style="width:100%; border:none; margin-bottom:15px;">
            <tr style="border:none;">
                <td style="border:none; width:150px;"><strong>Tanggal Pemeriksaan</strong></td>
                <td style="border:none;">: {{ date('d-m-Y', strtotime($jadwal->tanggal)) }}</td>
            </tr>
            <tr style="border:none;">
                <td style="border:none;"><strong>Lokasi</strong></td>
                <td style="border:none;">: {{ $jadwal->lokasi }}</td>
            </tr>
            <tr style="border:none;">
                <td style="border:none;"><strong>Jumlah Data</strong></td>
                <td style="border:none;">: {{ $cekKesehatan->count() }} orang</td>
            </tr>
        </table>
    </div>

    <h2>Data Hasil Pemeriksaan</h2>
    <table>
        <thead>
            <tr>
                <th style="width:5%;">No</th>
                <th style="width:15%;">Nama Lansia</th>
                <th style="width:8%;">Berat Badan (kg)</th>
                <th style="width:8%;">TD Sistolik (mmHg)</th>
                <th style="width:8%;">TD Diastolik (mmHg)</th>
                <th style="width:8%;">Gula Darah (mg/dL)</th>
                <th style="width:8%;">Kolesterol (mg/dL)</th>
                <th style="width:8%;">Asam Urat (mg/dL)</th>
                <th style="width:32%;">Diagnosa</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp
            @foreach($cekKesehatan as $data)
            <tr>
                <td style="text-align:center;">{{ $no++ }}</td>
                <td>{{ $data->lansia->nama }}</td>
                <td style="text-align:center;">{{ $data->berat_badan }}</td>
                <td style="text-align:center;">{{ $data->tekanan_darah_sistolik }}</td>
                <td style="text-align:center;">{{ $data->tekanan_darah_diastolik }}</td>
                <td style="text-align:center;">{{ $data->gula_darah }}</td>
                <td style="text-align:center;">{{ $data->kolestrol }}</td>
                <td style="text-align:center;">{{ $data->asam_urat }}</td>
                <td>{{ $data->diagnosa }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Dicetak pada: {{ date('d/m/Y H:i:s') }}</p>
    </div>
</body>
</html>