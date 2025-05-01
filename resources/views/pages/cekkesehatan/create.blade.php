@extends('layouts.app')
@section('title', 'Cek Kesehatan | Admin')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card mt-4">
            <div class="card-body">
                <h4 class="mb-3 mb-md-2">Form Cek Kesehatan</h4>
                <form action="{{ route('cekKesehatan.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <input type="text" hidden name="lansia_id" value="{{ $lansiaId }}">
                        <input type="text" hidden name="jadwal_id" value="{{ $jadwalId }}">

                        <!-- Tanggal -->
                        <div class="col-md-6 mt-3">
                            <label class="form-label">Tanggal</label>
                            <input type="date" name="tanggal" class="form-control @error('tanggal') is-invalid @enderror"
                                value="{{ date('Y-m-d') }}" readonly>
                            @error('tanggal')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Berat Badan -->
                        <div class="col-md-6 mt-3">
                            <label class="form-label">Berat Badan (kg)</label>
                            <input type="number" name="berat_badan"
                                class="form-control @error('berat_badan') is-invalid @enderror"
                                value="{{ old('berat_badan') }}">
                            @error('berat_badan')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Tekanan Darah -->
                        <div class="col-md-6 mt-3">
                            <label class="form-label">Tekanan Darah (mmHg)</label>
                            <input type="number" name="tekanan_darah"
                                class="form-control @error('tekanan_darah') is-invalid @enderror"
                                value="{{ old('tekanan_darah') }}">
                            @error('tekanan_darah')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Gula Darah -->
                        <div class="col-md-6 mt-3">
                            <label class="form-label">Gula Darah (mg/dL)</label>
                            <input type="number" name="gula_darah"
                                class="form-control @error('gula_darah') is-invalid @enderror"
                                value="{{ old('gula_darah') }}">
                            @error('gula_darah')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Kolesterol -->
                        <div class="col-md-6 mt-3">
                            <label class="form-label">Kolesterol (mg/dL)</label>
                            <input type="number" name="kolestrol"
                                class="form-control @error('kolestrol') is-invalid @enderror"
                                value="{{ old('kolestrol') }}">
                            @error('kolestrol')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Tombol Simpan -->
                        <div class="col-12 mt-4">
                            <button type="submit" class="btn btn-primary">Simpan</button>

                            <a href="{{ route('cekKesehatan.show', ['jadwal_id' => $jadwalId]) }}"
                                class="btn btn-secondary">Kembali</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
