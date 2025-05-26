@extends('layouts.app')
@section('title', 'Riwayat Kesehatan | Admin')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">

        <!-- Statistik -->
        <div class="row g-4 mb-4">
            {{-- Gula Darah Tinggi --}}
            <div class="col-sm-6 col-xl-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-start justify-content-between">
                            <div class="content-left">
                                <span class="text-heading">Gula Darah Tinggi</span>
                                <div class="d-flex align-items-center my-1">
                                    <h2 class="mb-0 me-2">{{ $jumlah_gula_tinggi }}</h2>
                                </div>
                                <small class="mb-0">> 140 mg/dL</small>
                            </div>
                            <div class="avatar">
                                <span class="avatar-initial rounded bg-label-danger">
                                    <i class="ti ti-activity-heartbeat ti-26px"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Asam Urat Tinggi --}}
            <div class="col-sm-6 col-xl-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-start justify-content-between">
                            <div class="content-left">
                                <span class="text-heading">Asam Urat Tinggi</span>
                                <div class="d-flex align-items-center my-1">
                                    <h2 class="mb-0 me-2">{{ $jumlah_asam_urat_tinggi }}</h2>
                                </div>
                                <small class="mb-0">> 7 mg/dL</small>
                            </div>
                            <div class="avatar">
                                <span class="avatar-initial rounded bg-label-warning">
                                    <i class="ti ti-droplet-half-2 ti-26px"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tekanan Darah Tinggi --}}
            <div class="col-sm-6 col-xl-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-start justify-content-between">
                            <div class="content-left">
                                <span class="text-heading">Tekanan Darah Tinggi</span>
                                <div class="d-flex align-items-center my-1">
                                    <h2 class="mb-0 me-2">{{ $jumlah_hipertensi }}</h2>
                                </div>
                                <small class="mb-0">> 140/90 mmHg</small>
                            </div>
                            <div class="avatar">
                                <span class="avatar-initial rounded bg-label-info">
                                    <i class="ti ti-wave-saw-tool ti-26px"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Kolesterol Tinggi --}}
            <div class="col-sm-6 col-xl-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-start justify-content-between">
                            <div class="content-left">
                                <span class="text-heading">Kolesterol Tinggi</span>
                                <div class="d-flex align-items-center my-1">
                                    <h2 class="mb-0 me-2">{{ $jumlah_kolestrol_tinggi }}</h2>
                                </div>
                                <small class="mb-0">> 200 mg/dL</small>
                            </div>
                            <div class="avatar">
                                <span class="avatar-initial rounded bg-label-secondary">
                                    <i class="ti ti-blood ti-26px"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Diabetes Mellitus --}}
            <div class="col-sm-6 col-xl-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-start justify-content-between">
                            <div class="content-left">
                                <span class="text-heading">Diabetes Mellitus</span>
                                <div class="d-flex align-items-center my-1">
                                    <h2 class="mb-0 me-2">{{ $jumlah_diabetes_mellitus }}</h2>
                                </div>
                                <small class="mb-0">Gula Darah > 140 mg/dL</small>
                            </div>
                            <div class="avatar">
                                <span class="avatar-initial rounded bg-label-primary">
                                    <i class="ti ti-dna ti-26px"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <!-- Data Table -->
        <div class="card mt-4">
            <div class="card-body">
                <h4 class="mb-3 mb-md-2">Data Riwayat Kesehatan</h4>
                <p class="text-muted mb-3">
                    Read the <a href="https://datatables.net/" target="_blank">Official DataTables Documentation</a> for a
                    full list of instructions and other options.
                </p>

                <div class="d-flex justify-content-end mb-3">
                    <a href="{{ route('riwayatKesehatan.exportPDF') }}?jadwal_id={{ $jadwal->id }}"
                        class="btn btn-danger btn-sm">
                        <i class="fas fa-file-pdf me-1"></i> Export PDF
                    </a>
                    <a href="{{ route('riwayatKesehatan.exportExcel') }}?jadwal_id={{ $jadwal->id }}"
                        class="btn btn-success btn-sm ms-2">
                        <i class="fas fa-file-excel me-1"></i> Export Excel
                    </a>
                </div>

                <div class="table-responsive">
                    <table class="table" id="riwayatTable">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>NIK</th>
                                <th>Nama Lengkap</th>
                                <th>Tanggal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($riwayats as $index => $riwayat)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $riwayat->lansia->nik }}</td>
                                    <td>{{ $riwayat->lansia->nama }}</td>
                                    <td>{{ $riwayat->tanggal }}</td>
                                    <td>
                                        <button class="btn btn-warning btn-sm show-btn" data-id="{{ $riwayat->id }}"
                                            data-bs-toggle="modal" data-bs-target="#showModal">
                                            Detail
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Modal Detail -->
                <div class="modal fade" id="showModal" tabindex="-1" aria-labelledby="showModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="showModalLabel">Detail Riwayat Kesehatan</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Tutup"></button>
                            </div>
                            <div class="modal-body">
                                <!-- Form Fields (readonly) -->
                                <div class="mb-3">
                                    <label for="tanggal" class="form-label">Tanggal</label>
                                    <input type="date" id="tanggal" class="form-control" readonly>
                                </div>
                                <div class="mb-3">
                                    <label for="berat_badan" class="form-label">Berat Badan (kg)</label>
                                    <input type="number" id="berat_badan" class="form-control" readonly>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Tekanan Darah (mmHg)</label>
                                    <div class="input-group">
                                        <input type="number" id="tekanan_darah_sistolik" class="form-control"
                                            placeholder="Sistolik" readonly>
                                        <span class="input-group-text">/</span>
                                        <input type="number" id="tekanan_darah_diastolik" class="form-control"
                                            placeholder="Diastolik" readonly>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="gula_darah" class="form-label">Gula Darah (mg/dL)</label>
                                    <input type="number" id="gula_darah" class="form-control" readonly>
                                </div>
                                <div class="mb-3">
                                    <label for="kolestrol" class="form-label">Kolesterol (mg/dL)</label>
                                    <input type="number" id="kolestrol" class="form-control" readonly>
                                </div>
                                <div class="mb-3">
                                    <label for="asam_urat" class="form-label">Asam Urat (mg/dL)</label>
                                    <input type="number" id="asam_urat" class="form-control" readonly>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>

    <script>
        $(document).ready(function() {
            $('.show-btn').on('click', function() {
                var id = $(this).data('id');
                $.ajax({
                    url: '/riwayat-kesehatan/show/' + id,
                    method: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        $('#tanggal').val(data.tanggal);
                        $('#berat_badan').val(data.berat_badan);
                        $('#tekanan_darah_sistolik').val(data.tekanan_darah_sistolik);
                        $('#tekanan_darah_diastolik').val(data.tekanan_darah_diastolik);
                        $('#gula_darah').val(data.gula_darah);
                        $('#kolestrol').val(data.kolestrol);
                        $('#asam_urat').val(data.asam_urat);
                    },
                    error: function() {
                        alert('Gagal mengambil data riwayat kesehatan.');
                    }
                });
            });

            $('#riwayatTable').DataTable({
                language: {
                    emptyTable: "Data tidak tersedia"
                }
            });
        });
    </script>
@endsection
