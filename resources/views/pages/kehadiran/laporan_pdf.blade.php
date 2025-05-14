<!DOCTYPE html>
<html>
<head>
    <title>Laporan Kehadiran Lansia</title>
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
        .totals td {
            font-weight: bold;
            background-color: #eee;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>LAPORAN KEHADIRAN POSYANDU LANSIA</h1>
        <p>Tanggal Cetak: {{ now()->format('d/m/Y') }}</p>
    </div>

    <div class="info">
        <h2>Informasi Jadwal</h2>
        <table style="width:100%; border:none; margin-bottom:15px;">
            <tr style="border:none;">
                <td style="border:none; width:150px;"><strong>Tanggal Kegiatan</strong></td>
                <td style="border:none;">: {{ \Carbon\Carbon::parse($cekJadwal->tanggal)->translatedFormat('d F Y') }}</td>
            </tr>
            <tr style="border:none;">
                <td style="border:none;"><strong>Lokasi</strong></td>
                <td style="border:none;">: {{ $cekJadwal->lokasi }}</td>
            </tr>
            <tr style="border:none;">
                <td style="border:none;"><strong>Total Lansia</strong></td>
                <td style="border:none;">: {{ $totalKeseluruhan }} orang</td>
            </tr>
        </table>
    </div>

    <h2>Data Kehadiran Lansia</h2>
    <table>
        <thead>
            <tr>
                <th style="width:5%;">No</th>
                <th style="width:25%;">Nama Lengkap</th>
                <th style="width:10%;">Usia</th>
                <th style="width:40%;">Alamat</th>
                <th style="width:20%;">Kehadiran</th>
            </tr>
        </thead>
        <tbody>
            @foreach($lansias as $index => $lansia)
                <tr>
                    <td style="text-align:center;">{{ $index + 1 }}</td>
                    <td>{{ $lansia->nama }}</td>
                    <td style="text-align:center;">{{ $lansia->umur }} tahun</td>
                    <td>{{ $lansia->alamat }}</td>
                    <td style="text-align:center;">
                        @php
                            $hadir = $kehadiran->where('lansia_id', $lansia->id)->isNotEmpty();
                        @endphp
                        {{ $hadir ? 'Hadir' : 'Tidak Hadir' }}
                    </td>
                </tr>
            @endforeach
            <tr class="totals">
                <td colspan="4">Total Hadir</td>
                <td style="text-align:center;">{{ $totalHadir }}</td>
            </tr>
            <tr class="totals">
                <td colspan="4">Total Tidak Hadir</td>
                <td style="text-align:center;">{{ $totalTidakHadir }}</td>
            </tr>
            <tr class="totals">
                <td colspan="4">Total Keseluruhan</td>
                <td style="text-align:center;">{{ $totalKeseluruhan }}</td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        <p>Dicetak pada: {{ now()->format('d/m/Y H:i:s') }}</p>
    </div>

</body>
</html>
