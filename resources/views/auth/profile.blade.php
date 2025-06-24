@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <h4 class="mb-4">Profil Saya</h4>

        {{-- Alert Sukses --}}
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        {{-- Alert Error --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- FORM UPDATE PROFIL --}}
        <div class="card mb-4">
            <div class="card-header">Ubah Informasi Profil</div>
            <div class="card-body">
                <form method="POST" action="{{ route('profile.update') }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" name="username" id="username"
                            value="{{ old('username', $user->username) }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Lengkap</label>
                        <input type="text" class="form-control" name="name" id="name"
                            value="{{ old('name', $admin->name) }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email Gmail</label>
                        <input type="email" class="form-control" name="email" id="email"
                            value="{{ old('email', $admin->email) }}" required>
                    </div>

                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </form>
            </div>
        </div>

        {{-- Form Update Password --}}
        <div class="card">
            <div class="card-header">Ubah Password</div>
            <div class="card-body">
                <form method="POST" action="{{ route('profile.update-password') }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="current_password" class="form-label">Password Saat Ini</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="current_password" name="current_password"
                                required>
                            <span class="input-group-text cursor-pointer toggle-password" data-target="current_password">
                                <i class="ti ti-eye-off"></i>
                            </span>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="new_password" class="form-label">Password Baru</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="new_password" name="new_password" required>
                            <span class="input-group-text cursor-pointer toggle-password" data-target="new_password">
                                <i class="ti ti-eye-off"></i>
                            </span>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="new_password_confirmation" class="form-label">Konfirmasi Password Baru</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="new_password_confirmation"
                                name="new_password_confirmation" required>
                            <span class="input-group-text cursor-pointer toggle-password"
                                data-target="new_password_confirmation">
                                <i class="ti ti-eye-off"></i>
                            </span>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-danger">Ubah Password</button>
                </form>
            </div>
        </div>
    </div>
@endsection
