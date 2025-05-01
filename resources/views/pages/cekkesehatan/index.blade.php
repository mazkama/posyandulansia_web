@extends('layouts.app')
@section('title', 'Cek Kesehatan | Admin')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card mt-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
                    <div>
                        <h4 class="mb-3 mb-md-2">Data Jadwal</h4>
                        <p class="text-muted mb-3">Read the <a href="https://datatables.net/" target="_blank">
                                Official DataTables Documentation </a> for a full list of instructions and other
                            options.</p>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table" id="jadwalTable">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Waktu</th>
                                <th>Lokasi</th>
                                <th>Keterangan</th>
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
                                    <td>{{ $jadwal->keterangan }}</td>
                                    <td>
                                        <a class="btn btn-warning btn-sm"
                                            href="{{ route('cekKesehatan.show',['jadwal_id' => $jadwal->id] ) }}">Cek Kesehatan</a>
                                        <!-- Tombol Edit dengan modal popup -->
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
