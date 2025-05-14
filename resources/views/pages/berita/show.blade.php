@extends('layouts.app')

@section('title', $berita->judul)

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-9">
            <!-- Breadcrumb navigation -->
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Beranda</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Berita</li>
                </ol>
            </nav>
            
            <!-- Article Card -->
            <article class="card border-0 shadow-lg rounded-3 overflow-hidden">
                @if($berita->foto)
                    <div class="position-relative">
                        <img src="{{ asset('storage/' . $berita->foto) }}" class="img-fluid w-100" alt="{{ $berita->judul }}" style="max-height: 450px; object-fit: cover;">
                        <div class="position-absolute bottom-0 start-0 w-100 p-3" style="background: linear-gradient(to top, rgba(0,0,0,0.7), transparent);">
                            <span class="badge bg-primary rounded-pill">Berita Terkini</span>
                        </div>
                    </div>
                @endif
                
                <div class="card-body p-4 p-lg-5">
                    <!-- Article header -->
                    <header class="mb-4">
                        <h1 class="display-5 fw-bold mb-3">{{ $berita->judul }}</h1>
                        <div class="d-flex flex-wrap align-items-center text-muted border-bottom pb-3 mb-4">
                            <div class="me-4 mb-2">
                                <i class="fas fa-calendar-alt me-1"></i> 
                                {{ \Carbon\Carbon::parse($berita->tanggal_publish)->locale('id')->isoFormat('DD MMMM YYYY') }}
                            </div>
                            
                            @if($berita->penulis)
                                <div class="me-4 mb-2">
                                    <i class="fas fa-user-edit me-1"></i> 
                                    {{ $berita->penulis }}
                                </div>
                            @endif
                        </div>
                    </header>
                    
                    <!-- Article content -->
                    <div class="fs-5 lh-lg">
                        {!! $berita->konten !!}
                    </div>
                    
                    <!-- Article footer -->
                    <footer class="mt-5 pt-4 border-top">
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center">
                            <!-- Share buttons -->
                            <div class="mb-3 mb-md-0">
                                <span class="fw-bold me-3">Bagikan:</span>
                                <a href="https://www.facebook.com/sharer/sharer.php?u={{ url()->current() }}" class="btn btn-sm btn-outline-primary rounded-circle me-1" target="_blank">
                                    <i class="fab fa-facebook-f"></i>
                                </a>
                                <a href="https://twitter.com/intent/tweet?url={{ url()->current() }}&text={{ $berita->judul }}" class="btn btn-sm btn-outline-info rounded-circle me-1" target="_blank">
                                    <i class="fab fa-twitter"></i>
                                </a>
                                <a href="https://wa.me/?text={{ $berita->judul }} {{ url()->current() }}" class="btn btn-sm btn-outline-success rounded-circle me-1" target="_blank">
                                    <i class="fab fa-whatsapp"></i>
                                </a>
                            </div>
                            
                            <!-- Back button -->
                            <a href="{{ route('dashboard') }}" class="btn btn-primary">
                                <i class="fas fa-arrow-left me-2"></i>Kembali ke Daftar Berita
                            </a>
                        </div>
                    </footer>
                </div>
            </article>
            
            <!-- Related articles section (optional) -->
            @if(isset($related_news) && count($related_news) > 0)
            <div class="mt-5">
                <h3 class="fw-bold mb-4">Berita Terkait</h3>
                <div class="row row-cols-1 row-cols-md-2 g-4">
                    @foreach($related_news as $related)
                    <div class="col">
                        <div class="card h-100 border-0 shadow-sm" style="transition: all 0.3s ease;">
                            <div class="position-relative">
                                @if($related->foto)
                                    <img src="{{ asset('storage/' . $related->foto) }}" class="card-img-top" alt="{{ $related->judul }}" style="height: 180px; object-fit: cover;">
                                @else
                                    <div class="bg-light" style="height: 180px"></div>
                                @endif
                            </div>
                            <div class="card-body">
                                <h5 class="card-title">{{ $related->judul }}</h5>
                                <p class="card-text text-muted">{{ \Illuminate\Support\Str::limit(strip_tags($related->konten), 100) }}</p>
                                <a href="{{ route('berita.show', $related->id) }}" class="text-decoration-none">Baca selengkapnya <i class="fas fa-arrow-right ms-1"></i></a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection