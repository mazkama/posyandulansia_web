@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container py-4">
    <!-- Header Dashboard -->
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h3 font-weight-bold text-gray-800 border-bottom pb-3">Dashboard</h1>
        </div>
    </div>

    <!-- Statistik -->
    <div class="row mb-4">
        <div class="col-md-4 mb-3">
            <div class="card h-100 rounded-lg">
                <div class="card-body bg-primary text-white rounded-lg d-flex align-items-center">
                    <div class="rounded-circle bg-white text-primary p-3 mr-3">
                        <i class="fas fa-users fa-2x"></i>
                    </div>
                    <div>
                        <h6 class="mb-0 font-weight-light">Jumlah Lansia</h6>
                        <h2 class="mb-0 font-weight-bold">{{ $jumlahLansia }}</h2>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card h-100 rounded-lg">
                <div class="card-body bg-success text-white rounded-lg d-flex align-items-center">
                    <div class="rounded-circle bg-white text-success p-3 mr-3">
                        <i class="fas fa-newspaper fa-2x"></i>
                    </div>
                    <div>
                        <h6 class="mb-0 font-weight-light">Jumlah Berita</h6>
                        <h2 class="mb-0 font-weight-bold">{{ $jumlahBerita }}</h2>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card h-100 rounded-lg">
                <div class="card-body bg-info text-white rounded-lg d-flex align-items-center">
                    <div class="rounded-circle bg-white text-info p-3 mr-3">
                        <i class="fas fa-calendar fa-2x"></i>
                    </div>
                    <div>
                        <h6 class="mb-0 font-weight-light">Total Jadwal</h6>
                        <h2 class="mb-0 font-weight-bold">{{ $totalJadwal }}</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Section -->
    <div class="row">
        <!-- Jadwal Selanjutnya -->
        <div class="col-lg-5 mb-4">
            <div class="card shadow-sm rounded-lg h-100">
                <div class="card-header bg-secondary text-white py-3">
                    <h5 class="mb-0 font-weight-bold">Jadwal Selanjutnya</h5>
                </div>
                <div class="card-body p-4">
                    @if ($jadwalSelanjutnya)
                        <div class="text-center mb-3">
                            <div class="bg-light rounded-circle p-3 d-inline-block mb-2" style="width: 80px; height: 80px;">
                                <i class="fas fa-calendar-day fa-3x text-secondary"></i>
                            </div>
                        </div>
                        <h4 class="text-center mb-3">{{ $jadwalSelanjutnya->judul ?? 'Kegiatan' }}</h4>
                        <div class="table-responsive">
                            <table class="table table-borderless">
                                <tr>
                                    <td width="30%"><i class="fas fa-calendar-alt mr-2 text-secondary"></i> Tanggal</td>
                                    <td width="5%">:</td>
                                    <td>{{ \Carbon\Carbon::parse($jadwalSelanjutnya->tanggal)->format('d M Y') }}</td>
                                </tr>
                                <tr>
                                    <td><i class="fas fa-clock mr-2 text-secondary"></i> Waktu</td>
                                    <td>:</td>
                                    <td>{{ \Carbon\Carbon::parse($jadwalSelanjutnya->waktu)->format('H:i') }} WIB</td>
                                </tr>
                                @if(isset($jadwalSelanjutnya->lokasi))
                                <tr>
                                    <td><i class="fas fa-map-marker-alt mr-2 text-secondary"></i> Lokasi</td>
                                    <td>:</td>
                                    <td>{{ $jadwalSelanjutnya->lokasi }}</td>
                                </tr>
                                @endif
                            </table>
                        </div>
                        @if(isset($jadwalSelanjutnya->deskripsi))
                            <div class="mt-3">
                                <h6 class="font-weight-bold">Deskripsi:</h6>
                                <p class="text-muted">{{ Str::limit($jadwalSelanjutnya->deskripsi, 150) }}</p>
                            </div>
                        @endif
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-calendar-alt fa-4x text-muted mb-3"></i>
                            <h5 class="text-muted">Tidak ada jadwal mendatang</h5>
                            <p class="text-muted mb-0">Jadwal kegiatan akan muncul di sini.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Berita Utama -->
        <div class="col-lg-7 mb-4">
            <div class="card shadow-sm rounded-lg h-100">
                <div class="card-header bg-primary text-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 font-weight-bold">Berita Utama</h5>
                    <a href="{{ route('berita.index') }}" class="btn btn-sm btn-light rounded-pill px-3">Lihat Semua</a>
                </div>
                <div class="card-body p-0">
                    @if(count($beritaTerbaru) > 0)
                        <div class="p-4 border-bottom">
                            <div class="row">
                                <div class="col-md-5 mb-3 mb-md-0">
                                    @if ($beritaTerbaru[0]->foto)
                                        <img src="{{ asset('storage/' . $beritaTerbaru[0]->foto) }}" alt="Foto Berita Utama" class="img-fluid rounded" style="width: 100%; height: 180px; object-fit: cover;">
                                    @else
                                        <div class="bg-light rounded d-flex align-items-center justify-content-center" style="height: 180px;">
                                            <i class="fas fa-newspaper fa-3x text-muted"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="col-md-7">
                                    <span class="badge badge-primary mb-2">Terbaru</span>
                                    <h5 class="font-weight-bold mb-2">{{ $beritaTerbaru[0]->judul }}</h5>
                                    <p class="small text-muted mb-2">
                                        <i class="fas fa-calendar-alt mr-1"></i> {{ \Carbon\Carbon::parse($beritaTerbaru[0]->tanggal_publish)->format('d M Y') }}
                                        @if(isset($beritaTerbaru[0]->penulis))
                                        <span class="mx-2">|</span>
                                        <i class="fas fa-user mr-1"></i> {{ $beritaTerbaru[0]->penulis }}
                                        @endif
                                    </p>
                                    <p class="small mb-3">{{ Str::limit(strip_tags($beritaTerbaru[0]->konten), 120, '...') }}</p>
                                    <a href="{{ route('berita.show', $beritaTerbaru[0]->id) }}" class="btn btn-sm btn-primary">Baca Selengkapnya</a>
                                </div>
                            </div>
                        </div>
                
                        <!-- List Berita Lainnya -->
                        <ul class="list-group list-group-flush">
                            @foreach($beritaTerbaru->slice(1, 3) as $berita)
                                <li class="list-group-item px-4 py-3">
                                    <div class="d-flex">
                                        @if ($berita->foto)
                                            <img src="{{ asset('storage/' . $berita->foto) }}" alt="Foto Berita" class="mr-3 rounded" style="width: 60px; height: 60px; object-fit: cover;">
                                        @else
                                            <div class="mr-3 rounded bg-light d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                                <i class="fas fa-newspaper text-muted"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <h6 class="mb-1 font-weight-bold">{{ Str::limit($berita->judul, 60) }}</h6>
                                            <p class="small text-muted mb-1">
                                                <i class="fas fa-calendar-alt mr-1"></i> {{ \Carbon\Carbon::parse($berita->tanggal_publish)->format('d M Y') }}
                                            </p>
                                            <a href="{{ route('berita.show', $berita->id) }}" class="small">Selengkapnya</a>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-newspaper fa-4x text-muted mb-3"></i>
                            <h5 class="text-muted">Belum Ada Berita</h5>
                            <p class="text-muted mb-0">Berita terbaru akan ditampilkan di sini.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    <!-- Aktivitas Terbaru atau Informasi Tambahan -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm rounded-lg">
                <div class="card-header bg-success text-white py-3">
                    <h5 class="mb-0 font-weight-bold">Informasi Penting</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-3 mb-md-0">
                            <div class="card h-100 border-0 rounded-lg text-center">
                                <div class="card-body">
                                    <div class="bg-light rounded-circle p-4 d-inline-block mb-3" style="width: 100px; height: 100px;">
                                        <i class="fas fa-hand-holding-heart fa-3x text-success"></i>
                                    </div>
                                    <h5 class="font-weight-bold">Layanan Kesehatan</h5>
                                    <p class="text-muted">Pemeriksaan rutin untuk lansia setiap hari Senin.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3 mb-md-0">
                            <div class="card h-100 border-0 rounded-lg text-center">
                                <div class="card-body">
                                    <div class="bg-light rounded-circle p-4 d-inline-block mb-3" style="width: 100px; height: 100px;">
                                        <i class="fas fa-utensils fa-3x text-warning"></i>
                                    </div>
                                    <h5 class="font-weight-bold">Nutrisi</h5>
                                    <p class="text-muted">Program pemberian makanan bergizi dilaksanakan mingguan.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3 mb-md-0">
                            <div class="card h-100 border-0 rounded-lg text-center">
                                <div class="card-body">
                                    <div class="bg-light rounded-circle p-4 d-inline-block mb-3" style="width: 100px; height: 100px;">
                                        <i class="fas fa-walking fa-3x text-danger"></i>
                                    </div>
                                    <h5 class="font-weight-bold">Aktivitas</h5>
                                    <p class="text-muted">Senam lansia setiap Rabu dan Sabtu pagi pukul 07.00 WIB.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3 mb-md-0">
                            <div class="card h-100 border-0 rounded-lg text-center">
                                <div class="card-body">
                                    <div class="bg-light rounded-circle p-4 d-inline-block mb-3" style="width: 100px; height: 100px;">
                                        <i class="fas fa-hands-helping fa-3x text-info"></i>
                                    </div>
                                    <h5 class="font-weight-bold">Konsultasi</h5>
                                    <p class="text-muted">Layanan konsultasi sosial dan psikologis tersedia setiap hari.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    /* Custom styles */
    .card {
        border: none;
        box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.1) !important;
        transition: all 0.3s ease;
    }
    
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 0.5rem 2rem 0 rgba(58, 59, 69, 0.15) !important;
    }
    
    .rounded-lg {
        border-radius: 0.5rem !important;
    }
    
    .bg-primary, .btn-primary {
        background-color: #4e73df !important;
        border-color: #4e73df !important;
    }
    
    .bg-success {
        background-color: #1cc88a !important;
        border-color: #1cc88a !important;
    }
    
    .bg-info {
        background-color: #36b9cc !important;
        border-color: #36b9cc !important;
    }
    
    .bg-secondary {
        background-color: #6c757d !important;
        border-color: #6c757d !important;
    }
    
    .text-primary {
        color: #4e73df !important;
    }
    
    .text-success {
        color: #1cc88a !important;
    }
    
    .text-info {
        color: #36b9cc !important;
    }
    
    .text-secondary {
        color: #6c757d !important;
    }
    
    .card-header {
        border-bottom: none;
        padding: 1rem 1.25rem;
        border-top-left-radius: 0.5rem !important;
        border-top-right-radius: 0.5rem !important;
    }
    
    h1, h2, h3, h4, h5, h6, .h1, .h2, .h3, .h4, .h5, .h6 {
        font-family: 'Nunito', sans-serif;
    }
    
    .badge {
        padding: 0.4em 0.6em;
        font-weight: 600;
    }
    
    .list-group-item {
        border-left: none;
        border-right: none;
    }
    
    .btn {
        border-radius: 0.35rem;
    }
</style>
@endpush

@push('scripts')
<script>
    // Tambahan script jika diperlukan
</script>
@endpush
@endsection