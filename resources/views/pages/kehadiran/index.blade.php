@extends('layouts.app')
@section('title', 'Kehadiran | Admin')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row g-6 mb-6">
            <div class="col-sm-6 col-xl-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-start justify-content-between">
                            <div class="content-left">
                                <span class="text-heading">Hadir</span>
                                <div class="d-flex align-items-center my-1">
                                    <h2 class="mb-0 me-2">{{ $totalHadir }}</h2>
                                </div>
                                <small class="mb-0">Total Lansia Hadir</small>
                            </div>
                            <div class="avatar">
                                <span class="avatar-initial rounded bg-label-primary">
                                    <i class="ti ti-users ti-26px"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-start justify-content-between">
                            <div class="content-left">
                                <span class="text-heading">Menunggu</span>
                                <div class="d-flex align-items-center my-1">
                                    <h2 class="mb-0 me-2">{{ $totalBelumCek }}</h2>
                                </div>
                                <small class="mb-0">Total Belum Cek</small>
                            </div>
                            <div class="avatar">
                                <span class="avatar-initial rounded bg-label-warning">
                                    <i class="ti ti-user-search ti-26px"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-start justify-content-between">
                            <div class="content-left">
                                <span class="text-heading">Selesai</span>
                                <div class="d-flex align-items-center my-1">
                                    <h2 class="mb-0 me-2">{{ $totalSudahCek }}</h2>
                                </div>
                                <small class="mb-0">Total Selesai di Cek</small>
                            </div>
                            <div class="avatar">
                                <span class="avatar-initial rounded bg-label-success">
                                    <i class="ti ti-user-check ti-26px"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card mt-4">
            <div class="card-body">
                <h4 class="mb-3 mb-md-2">Data Lansia</h4>
                <p class="text-muted mb-3">
                    Read the 
                    <a href="https://datatables.net/" target="_blank">Official DataTables Documentation</a>
                    for a full list of instructions and other options.
                </p>
        
                <div class="d-flex justify-content-end gap-2 mb-4">
                    <form 
                        action="{{ route('kehadiran.cetakLaporanPdf') }}" 
                        method="POST" 
                        onsubmit="return confirm('Yakin ingin mencetak laporan dan mengubah status menjadi selesai?')"
                        style="display: inline-block;"
                    >
                        @csrf
                        <input type="hidden" name="jadwal_id" value="{{ $jadwalId }}">
                        <button type="submit" class="btn btn-danger btn-sm">
                            <i class="fas fa-file-pdf mr-1"></i> Cetak PDF
                        </button>
                    </form>
                    <form 
                        action="{{ route('kehadiran.cetakLaporanExcel') }}" 
                        method="POST" 
                        onsubmit="return confirm('Yakin ingin mencetak laporan dan mengubah status menjadi selesai?')"
                        style="display: inline-block; margin-left: 8px;"
                    >
                        @csrf
                        <input type="hidden" name="jadwal_id" value="{{ $jadwalId }}">
                        <button type="submit" class="btn btn-success btn-sm">
                            <i class="fas fa-file-excel mr-1"></i> Cetak Excel
                        </button>
                    </form>

                </div>
        
                <div class="table-responsive">
                    <table class="table" id="kehadiranTable">
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
                            @foreach ($lansias as $index => $lansia)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $lansia->nama }}</td>
                                    <td>{{ $lansia->nik }}</td>
                                    <td>{{ $lansia->ttl }}</td>
                                    <td>
                                        @if (in_array($lansia->id, $kehadiran))
                                            <button class="btn btn-success" disabled>Hadir</button>
                                        @elseif ($jadwalStatus)
                                            <button class="btn btn-secondary" disabled>Jadwal Selesai</button>
                                        @else
                                            <form action="{{ route('kehadiran.store') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="lansia_id" value="{{ $lansia->id }}">
                                                <input type="hidden" name="jadwal_id" value="{{ $jadwalId }}">
                                                <input type="hidden" name="totalSudahCek" value="{{ $totalSudahCek }}">
                                                <input type="hidden" name="totalHadir" value="{{ $totalHadir }}">
                                                <input type="hidden" name="totalBelumCek" value="{{ $totalBelumCek }}">
                                                <button type="submit" class="btn btn-success">Absen</button>
                                            </form>
                                        @endif
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
            $('#kehadiranTable').DataTable({
                language: {
                    emptyTable: "Data tidak tersedia"
                }
            });
        });
    </script>
@endsection
