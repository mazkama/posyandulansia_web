@extends('layouts.app')
@section('title', 'Dashboard | Admin')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="col-12 mb-6">
            <h4>Data Baru Lansia</h4>
            <div class="bs-stepper wizard-numbered mt-2">
                <div class="bs-stepper-header">
                    <div class="step" data-target="#account-details">
                        <button type="button" class="step-trigger">
                            <span class="bs-stepper-circle">1</span>
                            <span class="bs-stepper-label">
                                <span class="bs-stepper-title">Detail Akun</span>
                                <span class="bs-stepper-subtitle">Data Akun</span>
                            </span>
                        </button>
                    </div>
                    <div class="line">
                        <i class="ti ti-chevron-right"></i>
                    </div>
                    <div class="step" data-target="#personal-info">
                        <button type="button" class="step-trigger">
                            <span class="bs-stepper-circle">2</span>
                            <span class="bs-stepper-label">
                                <span class="bs-stepper-title">Biodata Lansia</span>
                                <span class="bs-stepper-subtitle">Tambah Biodata Lansia</span>
                            </span>
                        </button>
                    </div>
                </div>
                <div class="bs-stepper-content">
                    <form action="{{ route('lansia.store') }}" method="POST">
                        @csrf
                        <!-- Detail Akun -->
                        <div id="account-details" class="content">
                            <div class="content-header mb-4">
                                <h6 class="mb-0">Detail Akun</h6>
                                <small>Masukan Data Detail Akun.</small>
                            </div>
                            <div class="row g-6">
                                <div class="col-sm-6">
                                    <label class="form-label" for="username">Username</label>
                                    <input type="text" id="username" name="username" class="form-control"
                                        placeholder="masukan username" value="{{ old('username') }}" required />
                                    @error('username')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-sm-6 form-password-toggle mb-3">
                                    <label class="form-label" for="password">Password</label>
                                    <div class="input-group input-group-merge">
                                        <input type="password" id="password" name="password" class="form-control"
                                            placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                            aria-describedby="password2" value="{{ old('password') }}"required />
                                        <span class="input-group-text cursor-pointer" id="password2"><i
                                                class="ti ti-eye-off"></i></span>
                                    </div>
                                    @error('password')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-12 d-flex justify-content-between">
                                    <button class="btn btn-label-secondary btn-prev" type="button" disabled>
                                        <i class="ti ti-arrow-left ti-xs me-sm-2 me-0"></i>
                                        <span class="align-middle d-sm-inline-block d-none">Previous</span>
                                    </button>
                                    <button class="btn btn-primary btn-next" type="button">
                                        <span class="align-middle d-sm-inline-block d-none me-sm-2">Next</span>
                                        <i class="ti ti-arrow-right ti-xs"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <!-- Biodata Lansia -->
                        <div id="personal-info" class="content">
                            <div class="content-header mb-4">
                                <h6 class="mb-0">Biodata Lansia</h6>
                                <small>Masukan Biodata Lansia.</small>
                            </div>
                            <div class="row g-6">
                                <div class="col-sm-6">
                                    <label for="nama" class="form-label">Nama</label>
                                    <input type="text" class="form-control" id="nama" name="nama"
                                        placeholder="masukan nama lengkap" value="{{ old('nama') }}" required />
                                    @error('nama')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-sm-6">
                                    <label for="nik" class="form-label">NIK</label>
                                    <input class="form-control" type="number" id="nik" name="nik"
                                        placeholder="masukan NIK" value="{{ old('nik') }}"required />
                                    @error('nik')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-sm-6">
                                    <label class="form-label d-block">Jenis Kelamin</label>
                                    <div class="form-check form-check-inline mt-2">
                                        <input class="form-check-input" type="radio" name="jenis_kelamin" id="jenis_kelamin_l" value="L" {{ old('jenis_kelamin') == 'L' ? 'checked' : '' }} required>
                                        <label class="form-check-label" for="jenis_kelamin_l">Laki-laki</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="jenis_kelamin" id="jenis_kelamin_p" value="P" {{ old('jenis_kelamin') == 'P' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="jenis_kelamin_p">Perempuan</label>
                                    </div>
                                    @error('jenis_kelamin')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-sm-6">
                                    <label for="no_hp" class="form-label">Nomor HP</label>
                                    <input type="text" class="form-control" id="no_hp" name="no_hp"
                                        placeholder="masukan nomor hp" value="{{ old('no_hp') }}"/>
                                    @error('no_hp')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-sm-6">
                                    <label for="ttl" class="form-label">Tempat Tanggal Lahir</label>
                                    <input type="text" class="form-control" id="ttl" name="ttl"
                                        placeholder="masukan tempat tanggal lahir" value="{{ old('ttl') }}"required />
                                    @error('ttl')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-sm-6">
                                    <label for="umur" class="form-label">Umur</label>
                                    <input class="form-control" type="number" id="umur" name="umur"
                                        placeholder="masukan umur" value="{{ old('umur') }}"required />
                                    @error('umur')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-sm-6">
                                    <label for="alamat" class="form-label">Alamat</label>
                                    <textarea class="form-control" id="alamat" name="alamat" rows="3" required>{{ old('alamat') }}</textarea>
                                    @error('alamat')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-12 d-flex justify-content-between">
                                    <button class="btn btn-label-secondary btn-prev" type="button">
                                        <i class="ti ti-arrow-left ti-xs me-sm-2 me-0"></i>
                                        <span class="align-middle d-sm-inline-block d-none">Previous</span>
                                    </button>
                                    <button class="btn btn-primary btn-submit" type="submit">
                                        <span class="align-middle d-sm-inline-block d-none me-sm-2">Submit</span>
                                        <i class="ti ti-arrow-right ti-xs"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
