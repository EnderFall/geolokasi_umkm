@extends('layouts.app')

@section('title', 'Beranda - Geolokasi UMKM Kuliner')

@section('content')
<!-- Hero Section -->
<section class="hero-section text-center text-white py-5 mb-5" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 15px;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <h1 class="display-4 fw-bold mb-4">
                    <i class="fas fa-map-marker-alt me-3"></i>
                    Temukan UMKM Kuliner Terdekat
                </h1>
                <p class="lead mb-4">
                    Jelajahi berbagai outlet makanan lokal dengan sistem geolokasi yang akurat. 
                    Pesan makanan favorit Anda dan dukung UMKM Indonesia!
                </p>
                <div class="d-flex justify-content-center gap-3">
                    <a href="{{ route('outlets.index') }}" class="btn btn-light btn-lg px-4">
                        <i class="fas fa-store me-2"></i>Jelajahi Outlet
                    </a>
                    <a href="{{ route('search.outlets') }}" class="btn btn-outline-light btn-lg px-4">
                        <i class="fas fa-search me-2"></i>Cari Makanan
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Search Section -->
<section class="search-section mb-5">
    <div class="container">
        <div class="card shadow-sm">
            <div class="card-body p-4">
                <h4 class="text-center mb-4">
                    <i class="fas fa-search me-2 text-primary"></i>
                    Cari Outlet atau Menu Favorit
                </h4>
                <form action="{{ route('search.outlets') }}" method="GET" class="row g-3">
                    <div class="col-md-4">
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-search"></i>
                            </span>
                            <input type="text" name="q" class="form-control" placeholder="Cari outlet atau menu..." value="{{ request('q') }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <select name="category" class="form-select">
                            <option value="">Semua Kategori</option>
                            @foreach($popular_categories as $category)
                                <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="rating" class="form-select">
                            <option value="">Semua Rating</option>
                            <option value="4" {{ request('rating') == '4' ? 'selected' : '' }}>4+ Bintang</option>
                            <option value="3" {{ request('rating') == '3' ? 'selected' : '' }}>3+ Bintang</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-search me-1"></i>Cari
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<!-- Popular Categories -->
<section class="categories-section mb-5">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h3 class="text-center mb-4">
                    <i class="fas fa-tags me-2 text-primary"></i>
                    Kategori Populer
                </h3>
            </div>
        </div>
        <div class="row g-3">
            @foreach($popular_categories as $category)
            <div class="col-md-3 col-sm-6">
                <a href="{{ route('search.outlets', ['category' => $category->id]) }}" class="text-decoration-none">
                    <div class="card category-card h-100 text-center shadow-sm hover-shadow">
                        <div class="card-body py-4">
                            <div class="category-icon mb-3">
                                <span class="display-4">{{ $category->icon }}</span>
                            </div>
                            <h6 class="card-title text-dark">{{ $category->name }}</h6>
                            <small class="text-muted">{{ $category->outlets_count }} outlet</small>
                        </div>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Featured Outlets -->
<section class="featured-outlets mb-5">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h3 class="text-center mb-4">
                    <i class="fas fa-star me-2 text-warning"></i>
                    Outlet Unggulan
                </h3>
            </div>
        </div>
        <div class="row g-4">
            @foreach($featured_outlets as $outlet)
            <div class="col-lg-4 col-md-6">
                <div class="card outlet-card h-100 shadow-sm hover-shadow">
                    @if($outlet->image)
                        <img src="{{ asset('storage/' . $outlet->image) }}" class="card-img-top" alt="{{ $outlet->name }}" style="height: 200px; object-fit: cover;">
                    @else
                        <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                            <i class="fas fa-store fa-3x text-muted"></i>
                        </div>
                    @endif
                    <div class="card-body">
                        <h5 class="card-title">{{ $outlet->name }}</h5>
                        <p class="card-text text-muted small">{{ Str::limit($outlet->description, 100) }}</p>
                        <div class="d-flex align-items-center mb-2">
                            <div class="text-warning me-2">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= $outlet->average_rating)
                                        <i class="fas fa-star"></i>
                                    @else
                                        <i class="far fa-star"></i>
                                    @endif
                                @endfor
                            </div>
                            <small class="text-muted">({{ $outlet->total_ratings }})</small>
                        </div>
                        <div class="d-flex align-items-center justify-content-between">
                            <small class="text-muted">
                                <i class="fas fa-map-marker-alt me-1"></i>
                                {{ Str::limit($outlet->address, 30) }}
                            </small>
                            <span class="badge bg-{{ $outlet->is_open ? 'success' : 'danger' }}">
                                {{ $outlet->is_open ? 'Buka' : 'Tutup' }}
                            </span>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent">
                        <a href="{{ route('outlets.show', $outlet) }}" class="btn btn-outline-primary btn-sm w-100">
                            <i class="fas fa-eye me-1"></i>Lihat Detail
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <div class="text-center mt-4">
            <a href="{{ route('outlets.index') }}" class="btn btn-primary btn-lg">
                <i class="fas fa-store me-2"></i>Lihat Semua Outlet
            </a>
        </div>
    </div>
</section>

<!-- Recommended Menus -->
<section class="recommended-menus mb-5">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h3 class="text-center mb-4">
                    <i class="fas fa-utensils me-2 text-success"></i>
                    Menu Rekomendasi
                </h3>
            </div>
        </div>
        <div class="row g-4">
            @foreach($recommended_menus as $menu)
            <div class="col-lg-3 col-md-6">
                <div class="card menu-card h-100 shadow-sm hover-shadow">
                    @if($menu->image)
                        <img src="{{ asset('storage/' . $menu->image) }}" class="card-img-top" alt="{{ $menu->name }}" style="height: 150px; object-fit: cover;">
                    @else
                        <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 150px;">
                            <i class="fas fa-utensils fa-2x text-muted"></i>
                        </div>
                    @endif
                    <div class="card-body">
                        <h6 class="card-title">{{ $menu->name }}</h6>
                        <p class="card-text text-muted small">{{ Str::limit($menu->description, 80) }}</p>
                        <div class="d-flex align-items-center justify-content-between">
                            <span class="text-primary fw-bold">Rp {{ number_format($menu->price, 0, ',', '.') }}</span>
                            <small class="text-muted">{{ $menu->outlet->name }}</small>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent">
                        <a href="{{ route('menus.show', $menu) }}" class="btn btn-outline-success btn-sm w-100">
                            <i class="fas fa-eye me-1"></i>Lihat Menu
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <div class="text-center mt-4">
            <a href="{{ route('menus.index') }}" class="btn btn-success btn-lg">
                <i class="fas fa-utensils me-2"></i>Lihat Semua Menu
            </a>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="features-section mb-5">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h3 class="text-center mb-5">
                    <i class="fas fa-cogs me-2 text-primary"></i>
                    Fitur Unggulan
                </h3>
            </div>
        </div>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="text-center">
                    <div class="feature-icon mb-3">
                        <i class="fas fa-map-marker-alt fa-3x text-primary"></i>
                    </div>
                    <h5>Geolokasi Akurat</h5>
                    <p class="text-muted">Temukan outlet terdekat dengan sistem geolokasi yang presisi</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="text-center">
                    <div class="feature-icon mb-3">
                        <i class="fas fa-star fa-3x text-warning"></i>
                    </div>
                    <h5>Rating & Review</h5>
                    <p class="text-muted">Lihat rating dan review dari pengguna lain untuk memilih outlet terbaik</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="text-center">
                    <div class="feature-icon mb-3">
                        <i class="fas fa-mobile-alt fa-3x text-success"></i>
                    </div>
                    <h5>Responsive Design</h5>
                    <p class="text-muted">Akses dari berbagai perangkat dengan tampilan yang optimal</p>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
.hero-section {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.hover-shadow:hover {
    transform: translateY(-5px);
    transition: transform 0.3s ease;
    box-shadow: 0 10px 25px rgba(0,0,0,0.1) !important;
}

.category-card:hover {
    transform: translateY(-5px);
    transition: transform 0.3s ease;
}

.outlet-card, .menu-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.outlet-card:hover, .menu-card:hover {
    transform: translateY(-5px);
}

.feature-icon {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: rgba(102, 126, 234, 0.1);
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
}
</style>
@endpush
