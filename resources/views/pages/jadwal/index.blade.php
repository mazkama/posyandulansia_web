@extends('layouts.app')
@section('title', 'Jadwal | Admin')
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
                    <div class="d-flex align-items-center flex-wrap text-nowrap">
                        <button type="button" class="btn btn-primary mb-2 mb-md-0" data-bs-toggle="modal"
                            data-bs-target="#createModal">
                            Tambah Jadwal
                        </button>
                        {{-- <a href="{{ route('jadwal.create') }}" wire:navigate type="button"
                        class="btn btn-primary btn-icon-text mb-2 mb-md-0">
                        <i class="btn-icon-prepend" data-feather="plus-square"></i>
                        Tambah Data
                    </a> --}}
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
                                        <a class="btn btn-primary btn-sm"
                                            href="{{ route('kehadiran', ['jadwal_id'=>$jadwal->id]) }}">Absensi</a>
                                        <!-- Tombol Edit dengan modal popup -->
                                        <button class="btn btn-warning btn-sm edit-btn" data-id="{{ $jadwal->id }}"
                                            data-bs-toggle="modal" data-bs-target="#editModal">
                                            Edit
                                        </button>
                                        <form action="{{ route('jadwal.destroy', $jadwal->id) }}" method="POST"
                                            style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm"
                                                onclick="return confirm('Yakin ingin menghapus?')">
                                                Hapus
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @include('pages.jadwal.modal')

    <!-- Script untuk load data ke modal edit -->
    <script>
        $(document).ready(function() {
            $('.edit-btn').on('click', function() {
                var id = $(this).data('id');
                // Mengambil data jadwal via AJAX
                $.ajax({
                    url: '/jadwal/' + id + '/edit',
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        // Isi data ke dalam form edit modal
                        $('#edit_tanggal').val(data.tanggal);
                        $('#edit_waktu').val(data.waktu);
                        $('#edit_lokasi').val(data.lokasi);
                        $('#edit_keterangan').val(data.keterangan);

                        // Update action form edit
                        $('#editForm').attr('action', '/jadwal/' + id);
                    },
                    error: function(xhr) {
                        alert('Terjadi kesalahan saat mengambil data jadwal.');
                    }
                });
            });
            $('#jadwalTable').DataTable({
                language: {
                    emptyTable: "Data tidak tersedia"
                }
            });
        });
    </script>
@endsection
