@extends('layouts.app')

@section('title', 'Cari Menu - Geolokasi UMKM Kuliner')

@section('content')
<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-1">
            <i class="fas fa-search me-2 text-success"></i>
            Cari Menu
        </h2>
        <p class="text-muted mb-0">Temukan menu kuliner sesuai selera Anda</p>
    </div>
    <a href="{{ route('menus.index') }}" class="btn btn-outline-success">
        <i class="fas fa-list me-2"></i>Lihat Semua Menu
    </a>
</div>

<!-- Search Form -->
<div class="card shadow-sm mb-4">
    <div class="card-body">
        <form action="{{ route('search.menus') }}" method="GET" class="row g-3">
            <div class="col-md-3">
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="fas fa-search"></i>
                    </span>
                    <input type="text" name="q" class="form-control" 
                           placeholder="Cari menu..." value="{{ $query }}">
                </div>
            </div>
            <div class="col-md-3">
                <select name="category" class="form-select">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ $category == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <input type="number" name="price_min" class="form-control" 
                       placeholder="Harga Min" value="{{ $price_min }}">
            </div>
            <div class="col-md-2">
                <input type="number" name="price_max" class="form-control" 
                       placeholder="Harga Max" value="{{ $price_max }}">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-success w-100">
                    <i class="fas fa-search me-1"></i>Cari
                </button>
            </div>
        </form>
        
        <!-- Search Summary -->
        @if($query || $category || $price_min || $price_max)
        <div class="mt-3 pt-3 border-top">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <small class="text-muted">
                        <i class="fas fa-info-circle me-1"></i>
                        Hasil pencarian untuk:
                        @if($query) <strong>"{{ $query }}"</strong> @endif
                        @if($category) kategori <strong>{{ $categories->find($category)->name }}</strong> @endif
                        @if($price_min || $price_max)
                            harga 
                            @if($price_min && $price_max)
                                <strong>Rp {{ number_format($price_min, 0, ',', '.') }} - Rp {{ number_format($price_max, 0, ',', '.') }}</strong>
                            @elseif($price_min)
                                <strong>≥ Rp {{ number_format($price_min, 0, ',', '.') }}</strong>
                            @elseif($price_max)
                                <strong>≤ Rp {{ number_format($price_max, 0, ',', '.') }}</strong>
                            @endif
                        @endif
                    </small>
                </div>
                <a href="{{ route('search.menus') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="fas fa-times me-1"></i>Reset
                </a>
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Search Results -->
@if($menus->count() > 0)
    <div class="row g-4">
        @foreach($menus as $menu)
        <div class="col-lg-3 col-md-6">
            <div class="card menu-card h-100 shadow-sm">
                @if($menu->image)
                    <img src="{{ asset('storage/' . $menu->image) }}" 
                         class="card-img-top" alt="{{ $menu->name }}" 
                         style="height: 200px; object-fit: cover;">
                @else
                    <div class="card-img-top bg-light d-flex align-items-center justify-content-center" 
                         style="height: 200px;">
                        <i class="fas fa-utensils fa-3x text-muted"></i>
                    </div>
                @endif
                
                <div class="card-body">
                    <h6 class="card-title">{{ $menu->name }}</h6>
                    <p class="card-text text-muted small">{{ Str::limit($menu->description, 80) }}</p>
                    
                    <!-- Outlet Info -->
                    <div class="d-flex align-items-center mb-2">
                        @if($menu->outlet->image)
                            <img src="{{ asset('storage/' . $menu->outlet->image) }}" 
                                 alt="{{ $menu->outlet->name }}" 
                                 class="rounded me-2" 
                                 style="width: 24px; height: 24px; object-fit: cover;">
                        @else
                            <div class="bg-light rounded me-2 d-flex align-items-center justify-content-center" 
                                 style="width: 24px; height: 24px;">
                                <i class="fas fa-store text-muted fa-sm"></i>
                            </div>
                        @endif
                        <small class="text-muted">{{ $menu->outlet->name }}</small>
                    </div>
                    
                    <!-- Rating -->
                    <div class="d-flex align-items-center mb-2">
                        <div class="text-warning me-2">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= $menu->average_rating)
                                    <i class="fas fa-star fa-sm"></i>
                                @else
                                    <i class="far fa-star fa-sm"></i>
                                @endif
                            @endfor
                        </div>
                        <small class="text-muted">({{ $menu->total_reviews }})</small>
                    </div>
                    
                    <!-- Price and Status -->
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-primary fw-bold">Rp {{ number_format($menu->price, 0, ',', '.') }}</span>
                        <div>
                            @if($menu->is_recommended)
                                <span class="badge bg-warning me-1">
                                    <i class="fas fa-star me-1"></i>Rekomendasi
                                </span>
                            @endif
                            <span class="badge bg-{{ $menu->is_available ? 'success' : 'danger' }}">
                                {{ $menu->is_available ? 'Tersedia' : 'Tidak Tersedia' }}
                            </span>
                        </div>
                    </div>
                </div>
                
                <div class="card-footer bg-transparent">
                    <div class="d-flex gap-2">
                        <a href="{{ route('menus.show', $menu) }}" class="btn btn-outline-success flex-fill">
                            <i class="fas fa-eye me-1"></i>Lihat
                        </a>
                        @auth
                            @if(Auth::user()->isPembeli())
                                <button type="button" class="btn btn-outline-warning" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#reviewModal{{ $menu->id }}">
                                    <i class="fas fa-star me-1"></i>Review
                                </button>
                            @endif
                        @endauth
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Review Modal -->
        @auth
            @if(Auth::user()->isPembeli())
            <div class="modal fade" id="reviewModal{{ $menu->id }}" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Beri Review untuk {{ $menu->name }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <form action="{{ route('menus.review', $menu) }}" method="POST">
                            @csrf
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label class="form-label">Rating</label>
                                    <div class="rating-input">
                                        @for($i = 5; $i >= 1; $i--)
                                            <input type="radio" name="rating" value="{{ $i }}" id="star{{ $i }}{{ $menu->id }}" required>
                                            <label for="star{{ $i }}{{ $menu->id }}">
                                                <i class="far fa-star"></i>
                                            </label>
                                        @endfor
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="comment{{ $menu->id }}" class="form-label">Komentar (Opsional)</label>
                                    <textarea class="form-control" name="comment" id="comment{{ $menu->id }}" rows="3" 
                                              placeholder="Bagikan pengalaman Anda..."></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-primary">Kirim Review</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @endif
        @endauth
        @endforeach
    </div>
    
    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-5">
        {{ $menus->links() }}
    </div>
@else
    <div class="text-center py-5">
        <i class="fas fa-utensils fa-4x text-muted mb-4"></i>
        <h4 class="text-muted mb-3">Tidak ada menu ditemukan</h4>
        <p class="text-muted mb-4">
            @if($query || $category || $price_min || $price_max)
                Coba ubah filter pencarian Anda atau gunakan kata kunci yang berbeda
            @else
                Belum ada menu yang tersedia dalam sistem
            @endif
        </p>
        <div class="d-flex gap-2 justify-content-center">
            <a href="{{ route('search.menus') }}" class="btn btn-success">
                <i class="fas fa-refresh me-2"></i>Reset Filter
            </a>
            <a href="{{ route('menus.index') }}" class="btn btn-outline-success">
                <i class="fas fa-list me-2"></i>Lihat Semua Menu
            </a>
        </div>
    </div>
@endif

<!-- Search Tips -->
<div class="row mt-5">
    <div class="col-12">
        <div class="card border-0 bg-light">
            <div class="card-body">
                <h6 class="text-success mb-3">
                    <i class="fas fa-lightbulb me-2"></i>
                    Tips Pencarian Menu
                </h6>
                <div class="row g-3">
                    <div class="col-md-3">
                        <div class="d-flex">
                            <i class="fas fa-search text-success me-2 mt-1"></i>
                            <div>
                                <strong>Gunakan Kata Kunci</strong>
                                <p class="text-muted small mb-0">Cari dengan nama menu atau bahan</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="d-flex">
                            <i class="fas fa-tags text-success me-2 mt-1"></i>
                            <div>
                                <strong>Filter Kategori</strong>
                                <p class="text-muted small mb-0">Pilih kategori yang sesuai</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="d-flex">
                            <i class="fas fa-tag text-success me-2 mt-1"></i>
                            <div>
                                <strong>Filter Harga</strong>
                                <p class="text-muted small mb-0">Tentukan range harga yang diinginkan</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="d-flex">
                            <i class="fas fa-star text-success me-2 mt-1"></i>
                            <div>
                                <strong>Lihat Rating</strong>
                                <p class="text-muted small mb-0">Pilih menu dengan rating tinggi</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Popular Categories -->
<div class="row mt-5">
    <div class="col-12">
        <h4 class="text-center mb-4">
            <i class="fas fa-tags me-2 text-success"></i>
            Kategori Menu Populer
        </h4>
    </div>
    <div class="col-12">
        <div class="row g-3">
            @foreach($categories->take(6) as $cat)
            <div class="col-md-4 col-sm-6">
                <a href="{{ route('search.menus', ['category' => $cat->id]) }}" class="text-decoration-none">
                    <div class="card category-card h-100 text-center hover-shadow">
                        <div class="card-body py-4">
                            <div class="category-icon mb-3">
                                <span class="display-4">{{ $cat->icon }}</span>
                            </div>
                            <h6 class="card-title text-dark">{{ $cat->name }}</h6>
                            <small class="text-muted">{{ $cat->outlets_count }} outlet</small>
                        </div>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.menu-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    border: none;
    border-radius: 15px;
}

.menu-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.1) !important;
}

.category-card {
    transition: transform 0.3s ease;
    border: none;
    border-radius: 15px;
}

.category-card:hover {
    transform: translateY(-5px);
}

.hover-shadow:hover {
    box-shadow: 0 10px 25px rgba(0,0,0,0.1) !important;
}

.rating-input {
    display: flex;
    flex-direction: row-reverse;
    justify-content: flex-end;
}

.rating-input input {
    display: none;
}

.rating-input label {
    cursor: pointer;
    font-size: 1.5rem;
    color: #ddd;
    margin: 0 2px;
}

.rating-input input:checked ~ label,
.rating-input label:hover,
.rating-input label:hover ~ label {
    color: #ffc107;
}

.card-footer {
    border-top: 1px solid #e9ecef;
}

.badge {
    font-size: 0.75rem;
}

.btn {
    border-radius: 8px;
}

.form-control, .form-select {
    border-radius: 8px;
}

.category-icon {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: rgba(40, 167, 69, 0.1);
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
}
</style>
@endpush

@push('scripts')
<script>
// Rating star interaction
document.addEventListener('DOMContentLoaded', function() {
    const ratingLabels = document.querySelectorAll('.rating-input label');
    
    ratingLabels.forEach(label => {
        label.addEventListener('mouseenter', function() {
            const stars = this.parentElement.querySelectorAll('label');
            const currentIndex = Array.from(stars).indexOf(this);
            
            stars.forEach((star, index) => {
                if (index >= currentIndex) {
                    star.style.color = '#ffc107';
                }
            });
        });
        
        label.addEventListener('mouseleave', function() {
            const stars = this.parentElement.querySelectorAll('label');
            const checkedInput = this.parentElement.querySelector('input:checked');
            
            stars.forEach(star => {
                if (!checkedInput) {
                    star.style.color = '#ddd';
                }
            });
        });
    });
});
</script>
@endpush
