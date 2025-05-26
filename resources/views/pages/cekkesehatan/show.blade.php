@extends('layouts.app')
@section('title', 'Cek Kesehatan | Admin')
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
                <p class="text-muted mb-3">Read the <a href="https://datatables.net/" target="_blank">
                        Official DataTables Documentation </a> for a full list of instructions and other
                    options.</p>
                <div class="row mb-4">
                    <div class="col-12">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="card-title mb-0">Data Pemeriksaan Kesehatan Lansia</h5>
                                <div class="export-buttons">
                                    <a href="{{ route('kehadiran.cetakLaporanPdf') }}" class="btn btn-danger btn-sm">
                                        <i class="fas fa-file-pdf mr-1"></i> Export PDF
                                    </a>
                                    <a href="{{ route('kehadiran.cetakLaporanExcel') }}"
                                        class="btn btn-success btn-sm ml-2">
                                        <i class="fas fa-file-excel mr-1"></i> Export Excel
                                    </a>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table" id="kehadiranTable">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>ID</th>
                                            <th>Nama Lengkap</th>
                                            <th>NIK</th>
                                            <th>Tempat Tanggal Lahir</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($lansiaBelumCek as $index => $lansia)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $lansia->id }}</td>
                                                <td>{{ $lansia->nama }}</td>
                                                <td>{{ $lansia->nik }}</td>
                                                <td>{{ $lansia->ttl }}</td>
                                                <td>
                                                    <a class="btn btn-success"
                                                        href="{{ route('cekKesehatan.create', ['jadwal_id' => $jadwalId, 'lansia_id' => $lansia->id]) }}">
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
                @if (session('diagnosa') || session('kesimpulan'))
                    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            Swal.fire({
                                title: '🩺 Hasil Pemeriksaan Kesehatan',
                                html: `
                        <div style="text-align: left;">
                            <p><strong>Diagnosa:</strong></p>
                            <ul style="padding-left: 20px; margin-top: -10px;">
                                @foreach (session('diagnosa') as $item)
                                    <li>{{ $item }}</li>
                                @endforeach
                            </ul>
                            <hr>
                            <p><strong>Kesimpulan:</strong></p>
                            <div style="max-height: 200px; overflow-y: auto; padding-right: 5px;">
                                <ul style="padding-left: 20px;">
                                    @foreach (session('kesimpulan') as $item)
                                        <li>{{ $item }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    `,
                                width: 600,
                                icon: 'info',
                                confirmButtonText: 'Tutup',
                                customClass: {
                                    popup: 'rounded-3 shadow'
                                }
                            });
                        });
                    </script>
                @endif

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
