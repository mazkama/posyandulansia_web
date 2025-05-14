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
                            <label class="form-label">Berat Badan (kg)
                                <small class="text-muted">(maks. 200 kg)</small>
                            </label>
                            <input type="number" name="berat_badan" max="200"
                                class="form-control @error('berat_badan') is-invalid @enderror"
                                value="{{ old('berat_badan') }}">
                            @error('berat_badan')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Tekanan Darah -->
                        <div class="col-md-6 mt-3">
                            <label class="form-label">Tekanan Darah (mmHg)
                                <small class="text-muted">(normal: 90–120 / 60–80)</small>
                            </label>
                            <div class="input-group">
                                <input type="number" name="tekanan_darah_sistolik" max="250"
                                    class="form-control @error('tekanan_darah_sistolik') is-invalid @enderror"
                                    placeholder="Sistolik"
                                    value="{{ old('tekanan_darah_sistolik') }}">
                                <span class="input-group-text">/</span>
                                <input type="number" name="tekanan_darah_diastolik" max="150"
                                    class="form-control @error('tekanan_darah_diastolik') is-invalid @enderror"
                                    placeholder="Diastolik"
                                    value="{{ old('tekanan_darah_diastolik') }}">
                            </div>
                            @error('tekanan_darah_sistolik')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                            @error('tekanan_darah_diastolik')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Gula Darah -->
                        <div class="col-md-6 mt-3">
                            <label class="form-label">Gula Darah (mg/dL)
                                <small class="text-muted">(normal puasa: 70–100)</small>
                            </label>
                            <input type="number" name="gula_darah" max="500"
                                class="form-control @error('gula_darah') is-invalid @enderror"
                                value="{{ old('gula_darah') }}">
                            @error('gula_darah')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Kolesterol -->
                        <div class="col-md-6 mt-3">
                            <label class="form-label">Kolesterol (mg/dL)
                                <small class="text-muted">(normal: < 200)</small>
                            </label>
                            <input type="number" name="kolestrol" max="500"
                                class="form-control @error('kolestrol') is-invalid @enderror"
                                value="{{ old('kolestrol') }}">
                            @error('kolestrol')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div> 

                        <!-- Asam Urat -->
                        <div class="col-md-6 mt-3">
                            <label class="form-label">Asam Urat (mg/dL)
                                <small class="text-muted">(normal: pria 3.4–7.0, wanita 2.4–6.0)</small>
                            </label>
                            <input type="number" name="asam_urat" max="15" step="0.1"
                                class="form-control @error('asam_urat') is-invalid @enderror"
                                value="{{ old('asam_urat') }}">
                            @error('asam_urat')
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
    
<<<<<<< HEAD
@endsection
=======
@endsection
>>>>>>> 5228d00 (laporankesehatan)
