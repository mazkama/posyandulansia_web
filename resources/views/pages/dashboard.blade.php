@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="container py-4">
        <!-- Header Dashboard -->
        <div class="row mb-4">
            <div class="col-12 bg-primary text-white p-4 rounded-3 shadow-sm">
                <div class="d-flex align-items-center">
                    <div class="bg-white rounded-circle p-3 me-3 shadow-sm">
                        <i class="fas fa-chart-line fa-lg text-primary"></i>
                    </div>
                    <div>
                        <h1 class="h3 fw-bold mb-0 text-white">Dashboard</h1>
                        <p class="mb-0 opacity-75">Selamat datang di sistem pendataan lansia</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistik -->
        <div class="row gy-4 mb-4">
            <div class="col-sm-6 col-xl-4">
                <div class="card h-100 border-0 shadow-sm rounded-3">
                    <div class="card-body">
                        <div class="d-flex align-items-start justify-content-between">
                            <div>
                                <span class="text-secondary small fw-medium">Jumlah Lansia</span>
                                <div class="d-flex align-items-center my-1">
                                    <h2 class="mb-0 me-2">{{ $jumlahLansia }}</h2>
                                </div>
                                <small class="mb-0 text-muted">Total lansia terdaftar</small>
                            </div>
                            <div class="d-flex align-items-center justify-content-center rounded-circle bg-light"
                                style="width: 48px; height: 48px;">
                                <i class="fas fa-users fa-lg text-primary"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-4">
                <div class="card h-100 border-0 shadow-sm rounded-3">
                    <div class="card-body">
                        <div class="d-flex align-items-start justify-content-between">
                            <div>
                                <span class="text-secondary small fw-medium text-uppercase">Jumlah Berita</span>
                                <div class="d-flex align-items-center my-1">
                                    <h2 class="display-6 mb-0 me-2 fw-bold text-success">{{ $jumlahBerita }}</h2>
                                </div>
                                <small class="mb-0 text-muted">Total berita tersedia</small>
                            </div>
                            <div
                                class="bg-success bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center p-3">
                                <i class="fas fa-newspaper fa-2x text-success"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-4">
                <div class="card h-100 border-0 shadow-sm rounded-3">
                    <div class="card-body">
                        <div class="d-flex align-items-start justify-content-between">
                            <div>
                                <span class="text-secondary small fw-medium text-uppercase">Total Jadwal</span>
                                <div class="d-flex align-items-center my-1">
                                    <h2 class="display-6 mb-0 me-2 fw-bold text-info">{{ $totalJadwal }}</h2>
                                </div>
                                <small class="mb-0 text-muted">Jadwal kegiatan aktif</small>
                            </div>
                            <div
                                class="bg-info bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center p-3">
                                <i class="fas fa-calendar-alt fa-2x text-info"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Section -->
        <div class="row">
            <!-- Jadwal Selanjutnya -->
            <div class="col-lg-5 mb-4">
                <div class="card border-0 shadow-sm rounded-3 h-100">
                    <div class="card-header bg-white border-bottom py-3">
                        <h5 class="mb-0 fw-bold text-dark">Jadwal Selanjutnya</h5>
                    </div>
                    <div class="card-body p-4">
                        @if ($jadwalSelanjutnya)
                            <div class="text-center mb-3">
                                <div class="d-inline-flex align-items-center justify-content-center bg-light rounded-circle mb-2"
                                    style="width: 80px; height: 80px;">
                                    <i class="fas fa-calendar-day fa-3x text-primary"></i>
                                </div>
                            </div>
                            <h4 class="text-center mb-3">{{ $jadwalSelanjutnya->judul ?? 'Kegiatan' }}</h4>
                            <div class="table-responsive">
                                <table class="table table-borderless">
                                    <tr>
                                        <td width="30%"><i class="fas fa-calendar-alt me-2 text-primary"></i> Tanggal
                                        </td>
                                        <td width="5%">:</td>
                                        <td>{{ \Carbon\Carbon::parse($jadwalSelanjutnya->tanggal)->format('d M Y') }}</td>
                                    </tr>
                                    <tr>
                                        <td><i class="fas fa-clock me-2 text-primary"></i> Waktu</td>
                                        <td>:</td>
                                        <td>{{ \Carbon\Carbon::parse($jadwalSelanjutnya->waktu)->format('H:i') }} WIB</td>
                                    </tr>
                                    @if (isset($jadwalSelanjutnya->lokasi))
                                        <tr>
                                            <td><i class="fas fa-map-marker-alt me-2 text-primary"></i> Lokasi</td>
                                            <td>:</td>
                                            <td>{{ $jadwalSelanjutnya->lokasi }}</td>
                                        </tr>
                                    @endif
                                </table>
                            </div>
                            @if (isset($jadwalSelanjutnya->deskripsi))
                                <div class="mt-3">
                                    <h6 class="fw-bold">Deskripsi:</h6>
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
                <div class="card border-0 shadow-sm rounded-3 h-100">
                    <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle bg-success bg-opacity-10 p-2 me-3">
                                <i class="fas fa-newspaper text-success"></i>
                            </div>
                            <h5 class="mb-0 fw-bold text-dark">Berita Utama</h5>
                        </div>
                        <a href="{{ route('berita.index') }}" class="btn btn-sm btn-primary rounded-pill px-3">
                            <i class="fas fa-arrow-right me-1"></i> Lihat Semua
                        </a>
                    </div>
                    <div class="card-body p-0">
                        @if (count($beritaTerbaru) > 0)
                            <!-- Berita Utama -->
                            <article class="p-4 border-bottom bg-light">
                                <div class="card border-0 shadow-sm rounded-3">
                                    <div class="row g-0">
                                        <div class="col-md-5">
                                            @if ($beritaTerbaru[0]->foto)
                                                <img src="{{ asset('storage/' . $beritaTerbaru[0]->foto) }}"
                                                    alt="Foto Berita Utama" class="img-fluid h-100 rounded-start"
                                                    style="object-fit: cover; min-height: 220px;">
                                            @else
                                                <div class="bg-light d-flex align-items-center justify-content-center h-100 rounded-start"
                                                    style="min-height: 220px;">
                                                    <i class="fas fa-newspaper fa-3x text-muted"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="col-md-7">
                                            <div class="p-4">
                                                <div class="d-flex align-items-center mb-2">
                                                    <span class="badge bg-success me-2">Terbaru</span>
                                                    <span class="small text-muted">
                                                        <i class="fas fa-calendar-alt me-1"></i>
                                                        {{ \Carbon\Carbon::parse($beritaTerbaru[0]->tanggal_publish)->format('d M Y') }}
                                                    </span>
                                                    @if (isset($beritaTerbaru[0]->penulis))
                                                        <span class="mx-2">•</span>
                                                        <span class="small text-muted">
                                                            <i class="fas fa-user me-1"></i>
                                                            {{ $beritaTerbaru[0]->penulis }}
                                                        </span>
                                                    @endif
                                                </div>
                                                <h4 class="fw-bold mb-2">{{ $beritaTerbaru[0]->judul }}</h4>
                                                <p class="mb-3 text-muted">
                                                    {{ Str::limit(strip_tags($beritaTerbaru[0]->konten), 120, '...') }}</p>
                                                <a href="{{ route('berita.show', $beritaTerbaru[0]->id) }}"
                                                    class="btn btn-primary rounded-pill">
                                                    <i class="fas fa-book-reader me-1"></i> Baca Selengkapnya
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </article>

                            <!-- Berita Lainnya -->
                            <div class="px-4 py-3">
                                <h6 class="fw-bold mb-3 text-muted">Berita Lainnya</h6>
                                <div class="row g-3">
                                    @foreach ($beritaTerbaru->slice(1, 3) as $berita)
                                        <div class="col-md-12">
                                            <article class="card border shadow-sm rounded-3 h-100">
                                                <div class="card-body p-3">
                                                    <div class="d-flex">
                                                        @if ($berita->foto)
                                                            <img src="{{ asset('storage/' . $berita->foto) }}"
                                                                alt="Foto Berita" class="me-3 rounded-3"
                                                                style="width: 100px; height: 80px; object-fit: cover;">
                                                        @else
                                                            <div class="me-3 rounded-3 bg-light d-flex align-items-center justify-content-center"
                                                                style="width: 100px; height: 80px;">
                                                                <i class="fas fa-newspaper text-muted"></i>
                                                            </div>
                                                        @endif
                                                        <div>
                                                            <h6 class="mb-1 fw-bold">{{ Str::limit($berita->judul, 60) }}
                                                            </h6>
                                                            <p class="small text-muted mb-1">
                                                                <i class="fas fa-calendar-alt me-1"></i>
                                                                {{ \Carbon\Carbon::parse($berita->tanggal_publish)->format('d M Y') }}
                                                            </p>
                                                            <a href="{{ route('berita.show', $berita->id) }}"
                                                                class="btn btn-sm btn-link text-success p-0">
                                                                Selengkapnya <i class="fas fa-angle-right ms-1"></i>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </article>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @else
                            <div class="text-center py-5">
                                <div
                                    class="d-inline-flex align-items-center justify-content-center bg-light rounded-circle mb-3 p-3">
                                    <i class="fas fa-newspaper fa-3x text-muted opacity-50"></i>
                                </div>
                                <h5 class="text-muted">Belum Ada Berita</h5>
                                <p class="text-muted mb-0 small">Berita terbaru akan ditampilkan di sini.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
