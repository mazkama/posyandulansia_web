@extends('layouts.app')
@section('title', 'Cek Kesehatan | Admin')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Card untuk Data Cek Kesehatan -->
        <div class="card mt-4">
            <div class="card-body">
                <!-- Header section -->
                <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
                    <div>
                        <h4 class="mb-3 mb-md-2">Data Cek Kesehatan</h4>
                        <p class="text-muted mb-3">
                            Read the <a href="https://datatables.net/" target="_blank">Official DataTables Documentation</a>
                            for a full list of instructions and other options.
                        </p>
                    </div>
                </div>
                <form method="GET" action="{{ route('cekKesehatan.index') }}" class="row g-3 mb-3 justify-content-end">
                    <div class="col-auto">
                        <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}"
                            placeholder="Tanggal">
                    </div>
                    <div class="col-auto">
                        <button type="submit" class="btn btn-primary">Filter</button>
                    </div>
                    <div class="col-auto">
                        <a href="{{ route('cekKesehatan.index') }}" class="btn btn-secondary">Reset</a>
                    </div>
                </form>


                <!-- Tabel Data Cek Kesehatan -->
                <div class="table-responsive">
                    <table class="table" id="jadwalTable">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Waktu</th>
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
                                    <td>{{ $jadwal->waktu }}</td>
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
                                        <a class="btn btn-warning btn-sm"
                                            href="{{ route('cekKesehatan.show', ['jadwal_id' => $jadwal->id]) }}">
                                            Cek Kesehatan
                                        </a>
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
            // Initialize DataTables with custom language settings
            $('#jadwalTable').DataTable({
                language: {
                    emptyTable: "Data tidak tersedia"
                }
            });
        });
    </script>
@endsection
