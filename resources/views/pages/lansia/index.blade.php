@extends('layouts.app')
@section('title', 'Lansia | Admin')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card mt-4">
            <div class="card-body">
                <h4 class="mb-3 mb-md-2">Data Lansia</h4>
                <p class="text-muted mb-3">Read the <a href="https://datatables.net/" target="_blank">
                        Official DataTables Documentation </a> for a full list of instructions and other
                    options.</p>
                <div class="table-responsive">
                    <table class="table" id="usersTable">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Lengkap</th>
                                <th>NIK</th>
                                <th>Tempat Tanggal Lahir</th>
                                {{-- <th>Umur</th>
                            <th>Alamat</th>
                            <th>No HP</th> --}}
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($lansias as $index => $lansia)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $lansia->nama }}</td>
                                    <td>{{ $lansia->nik }}</td>
                                    <td>{{ $lansia->ttl }}</td>
                                    {{-- <td>{{ $lansia->umur }}</td>
                                <td>{{ $lansia->alamat }}</td>
                                <td>{{ $lansia->no_hp }}</td> --}}
                                    <td>
                                        <a href="{{ route('lansia.edit', $lansia->id) }}"
                                            class="btn btn-warning btn-sm mb-1">Edit</a>
                                        <form action="{{ route('lansia.destroy', $lansia->id) }}" method="POST"
                                            style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm mb-1">Hapus</button>
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
    <script>
        $(document).ready(function() {
            $('#usersTable').DataTable({
                language: {
                    emptyTable: "Data tidak tersedia"
                }
            });
        });
    </script>


@endsection
