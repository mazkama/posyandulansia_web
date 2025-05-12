@extends('layouts.app')
@section('title', 'Berita | Admin')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card mt-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
                <div>
                    <h4 class="mb-3 mb-md-2">Data Berita</h4>
                    <p class="text-muted mb-3">
                        Read the <a href="https://datatables.net/" target="_blank">Official DataTables Documentation</a> for full list of options.
                    </p>
                </div>
                <div class="d-flex align-items-center flex-wrap text-nowrap">
                    <button type="button" class="btn btn-primary mb-2 mb-md-0" data-bs-toggle="modal" data-bs-target="#createModal">
                        Tambah Berita
                    </button>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table" id="beritaTable">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Judul</th>
                            <th>Konten</th> <!-- Menambahkan kolom Konten -->
                            <th>Tanggal Publish</th>
                            <th>Foto</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($beritas as $index => $berita)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $berita->judul }}</td>
                            <td>{{ Str::limit($berita->konten, 100) }}</td> <!-- Menampilkan Konten -->
                            <td>{{ \Carbon\Carbon::parse($berita->tanggal_publish)->translatedFormat('d F Y') }}</td>
                            <td>
                                @if ($berita->foto)
                                    <img src="{{ asset('storage/' . $berita->foto) }}" alt="Foto Berita" width="100">
                                @else
                                    <span class="text-muted">Tidak ada foto</span>
                                @endif
                            </td>
                            <td>
                                <button class="btn btn-warning btn-sm edit-btn"
                                    data-id="{{ $berita->id }}"
                                    data-judul="{{ $berita->judul }}"
                                    data-konten="{{ $berita->konten }}"
                                    data-tanggal="{{ $berita->tanggal_publish }}"
                                    data-foto="{{ $berita->foto ? asset('storage/' . $berita->foto) : '' }}"
                                    data-bs-toggle="modal" data-bs-target="#editModal">
                                    Edit
                                </button>

                                <form action="{{ route('berita.destroy', $berita->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm"
                                        onclick="return confirm('Yakin ingin menghapus berita ini?')">
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

@include('pages.berita.modal') 

<script>
    $(document).ready(function () {
        $('#beritaTable').DataTable({
            language: {
                emptyTable: "Data tidak tersedia"
            }
        });

        $('.edit-btn').on('click', function () {
        const id = $(this).data('id');
        $('#editForm').attr('action', '/berita/' + id); 
        $('#edit_judul').val($(this).data('judul'));
        $('#edit_konten').val($(this).data('konten'));
        $('#edit_tanggal_publish').val($(this).data('tanggal'));

        const foto = $(this).data('foto');
        if (foto) {
            $('#edit_foto_preview').attr('src', foto).show();
        } else {
            $('#edit_foto_preview').hide();
        }
    });

    });

    @if (session('error'))
        alert("{{ session('error') }}");
    @endif

    @if (session('success'))
        alert("{{ session('success') }}");
    @endif
</script>
@endsection
