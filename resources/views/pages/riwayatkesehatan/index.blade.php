@extends('layouts.app')
@section('title', 'Riwayat Kesehatan | Admin')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card mt-4">
            <div class="card-body">
                <h4 class="mb-3 mb-md-2">Daftar Jadwal Pemeriksaan</h4>
                <form method="GET" action="{{ route('riwayatKesehatan.index') }}" class="row g-3 mb-3 justify-content-end">
                    <div class="col-auto">
                        <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}"
                            placeholder="Tanggal Mulai">
                    </div>
                    <div class="col-auto">
                        <button type="submit" class="btn btn-primary">Filter</button>
                    </div>
                    <div class="col-auto">
                        <a href="{{ route('riwayatKesehatan.index') }}" class="btn btn-secondary">Reset</a>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table" id="jadwalTable">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Lokasi</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($jadwals as $index => $jadwal)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $jadwal->tanggal }}</td>
                                    <td>{{ $jadwal->lokasi }}</td>
                                    <td>
                                        @if ($jadwal->status === 'belum_dimulai')
                                            <span class="badge bg-warning">Belum Dimulai</span>
                                        @elseif ($jadwal->status === 'berlangsung')
                                            <span class="badge bg-primary">Berlangsung</span>
                                        @else
                                            <span class="badge bg-success">Selesai</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('riwayatkesehatan.riwayatKesehatan', ['jadwal_id' => $jadwal->id]) }}"
                                            class="btn btn-warning btn-sm mb-1">Lihat Riwayat</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#jadwalTable').DataTable({
                language: {
                    emptyTable: "Data tidak tersedia"
                }
            });
        });
    </script>
@endsection
