@extends('layouts.app')

@section('title', 'Dashboard | Detail Lansia')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row justify-content-center">
            <div class="col-12 col-md-10">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-0">
                        <div class="bg-primary text-white p-4 rounded-top d-flex align-items-center">
                            <div class="avatar avatar-lg me-3">
                                <span class="avatar-initial rounded-circle bg-white text-primary">
                                    {{ strtoupper(substr($lansia->nama, 0, 1)) }}
                                </span>
                            </div>
                            <div>
                                <h4 class="mb-0">{{ $lansia->nama }}</h4>
                                <p class="mb-0 opacity-75">{{ $lansia->umur }} Tahun</p>
                            </div>
                        </div>

                        <!-- Detail Informasi -->
                        <div class="p-4">
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <p class="small text-muted mb-1">
                                            <i class="ti ti-id me-1"></i>NIK
                                        </p>
                                        <p class="fs-5 mb-0">{{ $lansia->nik }}</p>
                                    </div>

                                    <div class="mb-3">
                                        <p class="small text-muted mb-1">
                                            <i class="ti ti-calendar me-1"></i>Tempat, Tanggal Lahir
                                        </p>
                                        <p class="fs-5 mb-0">{{ $lansia->ttl }}</p>
                                    </div>

                                    <div class="mb-3">
                                        <p class="small text-muted mb-1">
                                            <i class="ti ti-user me-1"></i>Username
                                        </p>
                                        <p class="fs-5 mb-0">{{ $lansia->user->username ?? 'Tidak ada' }}</p>
                                    </div>

                                    <div class="mb-3">
                                        <p class="small text-muted mb-1">
                                            <i class="ti ti-fingerprint me-1"></i>User ID
                                        </p>
                                        <p class="fs-5 mb-0">{{ $lansia->user_id ?? 'Tidak ada' }}</p>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <p class="small text-muted mb-1">
                                            <i class="ti ti-user me-1"></i>Jenis Kelamin
                                        </p>
                                        <p class="fs-5 mb-0">
                                            {{ $lansia->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}
                                        </p>
                                    </div>
                                    <div class="mb-3">
                                        <p class="small text-muted mb-1">
                                            <i class="ti ti-phone me-1"></i>Nomor HP
                                        </p>
                                        <p class="fs-5 mb-0">
                                            @if ($lansia->no_hp)
                                                <a href="tel:{{ $lansia->no_hp }}"
                                                    class="text-primary text-decoration-none">
                                                    {{ $lansia->no_hp }}
                                                </a>
                                            @else
                                                <span class="text-muted">Tidak ada</span>
                                            @endif
                                        </p>
                                    </div>

                                    <div class="mb-3">
                                        <p class="small text-muted mb-1">
                                            <i class="ti ti-map-pin me-1"></i>Alamat
                                        </p>
                                        <p class="fs-5 mb-0">{{ $lansia->alamat }}</p>
                                    </div>

                                    <div class="mb-3">
                                        <p class="small text-muted mb-1">
                                            <i class="ti ti-clock me-1"></i>Umur
                                        </p>
                                        <p class="fs-5 mb-0">{{ $lansia->umur }} Tahun</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr class="m-0">
                        <div class="p-4 d-flex justify-content-between align-items-center">
                            <a href="{{ route('lansia.index') }}" class="btn btn-outline-secondary">
                                <i class="ti ti-arrow-left me-1"></i>Kembali
                            </a>
                            <div>
                                <a href="{{ route('lansia.edit', $lansia->id) }}" class="btn btn-warning me-2">
                                    <i class="ti ti-pencil me-1"></i>Edit
                                </a>
                                <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                    data-bs-target="#deleteModal">
                                    <i class="ti ti-trash me-1"></i>Hapus
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0 pb-0">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center px-4">
                    <div class="avatar avatar-md mb-3 mx-auto">
                        <span class="avatar-initial rounded-circle bg-label-danger">
                            <i class="ti ti-alert-triangle"></i>
                        </span>
                    </div>
                    <h5>Hapus Data</h5>
                    <p>Yakin ingin menghapus data lansia <strong>{{ $lansia->nama }}</strong>?</p>
                </div>
                <div class="modal-footer border-0 justify-content-center pb-4">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <form action="{{ route('lansia.destroy', $lansia->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
