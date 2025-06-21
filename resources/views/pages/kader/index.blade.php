@extends('layouts.app')
@section('title', 'Kader | Admin')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card mt-4">
            <div class="card-body">
                <h4 class="mb-3 mb-md-2">Data Kader</h4>
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
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($kaders as $index => $kader)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $kader->nama }}</td>
                                    <td>{{ $kader->nik }}</td>
                                    <td>{{ $kader->ttl }}</td>
                                    <td>
                                        <a href="{{ route('kader.show', $kader->id) }}" class="btn btn-info btn-sm mb-1">Show</a>
                                        <a href="{{ route('kader.edit', $kader->id) }}" class="btn btn-warning btn-sm mb-1">Edit</a>
                                        <form action="{{ route('kader.destroy', $kader->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm mb-1" 
                                                onclick="return confirm('Yakin ingin menghapus {{$kader->nama}}?')">Hapus</button>
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
