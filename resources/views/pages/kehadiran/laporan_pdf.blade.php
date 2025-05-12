<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Kehadiran</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h2 { margin: 0; font-size: 18px; }
        .sub-header { text-align: center; margin-top: -10px; font-size: 14px; }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            font-size: 12px;
        }
        th, td {
            border: 1px solid #000;
            padding: 6px;
            text-align: center;
        }
        th {
            background-color: #d3f8d3;
            font-weight: bold;
        }
        .totals td {
            font-weight: bold;
            text-align: left;
        }
    </style>
</head>
<body>

    <div class="header">
        <h2>Laporan Kehadiran Posyandu Lansia</h2>
    </div>
    <div class="sub-header">
        <p>Tanggal: {{ \Carbon\Carbon::parse($cekJadwal->tanggal)->format('d F Y') }}</p>
        <p>Lokasi: {{ $cekJadwal->lokasi }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Lengkap</th>
                <th>NIK</th>
                <th>Tempat Tanggal Lahir</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($lansias as $index => $lansia)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $lansia->nama }}</td>
                    <td>'{{ $lansia->nik }}</td>
                    <td>{{ $lansia->ttl ?? '-' }}</td>
                    <td>
                        @php
                            $hadir = $kehadiran->where('lansia_id', $lansia->id)->isNotEmpty();
                        @endphp
                        {{ $hadir ? 'Hadir' : 'Tidak Hadir' }}
                    </td>
                </tr>
            @endforeach
            <tr class="totals">
                <td colspan="2">Total Hadir</td>
                <td colspan="3">{{ $totalHadir }}</td>
            </tr>
            <tr class="totals">
                <td colspan="2">Total Tidak Hadir</td>
                <td colspan="3">{{ $totalTidakHadir }}</td>
            </tr>
            <tr class="totals">
                <td colspan="2">Total Keseluruhan</td>
                <td colspan="3">{{ $totalKeseluruhan }}</td>
            </tr>
        </tbody>
    </table>

</body>
</html>
