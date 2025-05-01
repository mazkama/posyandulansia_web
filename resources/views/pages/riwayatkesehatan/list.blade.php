@extends('layouts.app')
@section('title', 'Riwayat Kesehatan | Admin')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card mt-4">
            <div class="card-body">
                <h4 class="mb-3 mb-md-2">Data Riwayat Kesehatan</h4> 
                <p class="text-muted mb-3">Read the <a href="https://datatables.net/" target="_blank">
                        Official DataTables Documentation </a> for a full list of instructions and other
                    options.</p>
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
                                    {{-- <td>{{ $riwayat->umur }}</td>
                                <td>{{ $riwayat->alamat }}</td>
                                <td>{{ $riwayat->no_hp }}</td> --}}
                                    <td>
                                        <button class="btn btn-warning btn-sm show-btn" data-id="{{ $riwayat->id }}"  data-bs-toggle="modal"
                                            data-bs-target="#showModal">
                                            Detail
                                        </button>

                                    </td>

                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <!-- Riwayat Modal -->
                    <div class="modal fade" id="showModal" tabindex="-1" aria-labelledby="showModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form id="showForm" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="showModalLabel">Detail Riwayat Kesehatan</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Tutup"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label for="tanggal" class="form-label">Tanggal</label>
                                            <input type="date" class="form-control" id="tanggal" name="tanggal"
                                                readonly>
                                        </div>
                                        <div class="mb-3">
                                            <label for="berat_badan" class="form-label">Berat Badan (kg)</label>
                                            <input type="number" class="form-control" id="berat_badan" name="berat_badan"
                                                readonly>
                                        </div>
                                        <div class="mb-3">
                                            <label for="tekanan_darah" class="form-label">Tekanan Darah (mmHg)</label>
                                            <input type="number" class="form-control" id="tekanan_darah"
                                                name="tekanan_darah" readonly>
                                        </div>
                                        <div class="mb-3">
                                            <label for="gula_darah" class="form-label">Gula Darah (mg/dL)</label>
                                            <input type="number" class="form-control" id="gula_darah" name="gula_darah"
                                                readonly>
                                        </div>
                                        <div class="mb-3">
                                            <label for="kolestrol" class="form-label">Kolesterol (mg/dL)</label>
                                            <input type="number" class="form-control" id="kolestrol" name="kolestrol"
                                                readonly>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Batal</button>
                                    </div>
                                </form>
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
                    dataType: 'json',
                    success: function(data) {
                        // Mengecek apakah data yang diterima memiliki nilai yang benar
                        console.log(data);
                        $('#tanggal').val(data.tanggal);
                        $('#berat_badan').val(data.berat_badan);
                        $('#tekanan_darah').val(data.tekanan_darah);
                        $('#gula_darah').val(data.gula_darah);
                        $('#kolestrol').val(data.kolestrol);
                    },
                    error: function(xhr, status, error) {
                        console.log('Status:', status);
                        console.log('Error:', error);
                        alert('Terjadi kesalahan saat mengambil data riwayat.');
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
