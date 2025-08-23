@extends('layouts.app')

@section('title', 'Cari Outlet - Geolokasi UMKM Kuliner')

@section('content')
<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-1">
            <i class="fas fa-search me-2 text-primary"></i>
            Cari Outlet
        </h2>
        <p class="text-muted mb-0">Temukan outlet kuliner sesuai preferensi Anda</p>
    </div>
    <a href="{{ route('outlets.index') }}" class="btn btn-outline-primary">
        <i class="fas fa-list me-2"></i>Lihat Semua Outlet
    </a>
</div>

<!-- Search Form -->
<div class="card shadow-sm mb-4">
    <div class="card-body">
        <form action="{{ route('search.outlets') }}" method="GET" class="row g-3">
            <div class="col-md-4">
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="fas fa-search"></i>
                    </span>
                    <input type="text" name="q" class="form-control" 
                           placeholder="Cari outlet..." value="{{ $query }}">
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
            <div class="col-md-3">
                <select name="rating" class="form-select">
                    <option value="">Semua Rating</option>
                    <option value="4" {{ $rating == '4' ? 'selected' : '' }}>4+ Bintang</option>
                    <option value="3" {{ $rating == '3' ? 'selected' : '' }}>3+ Bintang</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-search me-1"></i>Cari
                </button>
            </div>
        </form>
        
        <!-- Search Summary -->
        @if($query || $category || $rating)
        <div class="mt-3 pt-3 border-top">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <small class="text-muted">
                        <i class="fas fa-info-circle me-1"></i>
                        Hasil pencarian untuk:
                        @if($query) <strong>"{{ $query }}"</strong> @endif
                        @if($category) kategori <strong>{{ $categories->find($category)->name }}</strong> @endif
                        @if($rating) rating <strong>{{ $rating }}+ bintang</strong> @endif
                    </small>
                </div>
                <a href="{{ route('search.outlets') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="fas fa-times me-1"></i>Reset
                </a>
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Search Results -->
@if($outlets->count() > 0)
    <div class="row g-4">
        @foreach($outlets as $outlet)
        <div class="col-lg-4 col-md-6">
            <div class="card outlet-card h-100 shadow-sm">
                @if($outlet->image)
                    <img src="{{ asset('storage/' . $outlet->image) }}" 
                         class="card-img-top" alt="{{ $outlet->name }}" 
                         style="height: 200px; object-fit: cover;">
                @else
                    <div class="card-img-top bg-light d-flex align-items-center justify-content-center" 
                         style="height: 200px;">
                        <i class="fas fa-store fa-3x text-muted"></i>
                    </div>
                @endif
                
                <div class="card-body">
                    <h5 class="card-title">{{ $outlet->name }}</h5>
                    <p class="card-text text-muted">{{ Str::limit($outlet->description, 100) }}</p>
                    
                    <!-- Rating -->
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
                    
                    <!-- Categories -->
                    @if($outlet->categories->count() > 0)
                        <div class="mb-2">
                            @foreach($outlet->categories->take(3) as $cat)
                                <span class="badge bg-light text-dark me-1">{{ $cat->name }}</span>
                            @endforeach
                            @if($outlet->categories->count() > 3)
                                <small class="text-muted">+{{ $outlet->categories->count() - 3 }} lagi</small>
                            @endif
                        </div>
                    @endif
                    
                    <!-- Info -->
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <small class="text-muted">
                                <i class="fas fa-map-marker-alt me-1"></i>
                                {{ Str::limit($outlet->address, 25) }}
                            </small>
                        </div>
                        <div class="col-6">
                            <small class="text-muted">
                                <i class="fas fa-phone me-1"></i>
                                {{ $outlet->phone }}
                            </small>
                        </div>
                    </div>
                    
                    <!-- Status -->
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="badge bg-{{ $outlet->is_open ? 'success' : 'danger' }}">
                            {{ $outlet->is_open ? 'Buka' : 'Tutup' }}
                        </span>
                        @if($outlet->is_verified)
                            <span class="badge bg-primary">
                                <i class="fas fa-check-circle me-1"></i>Terverifikasi
                            </span>
                        @endif
                    </div>
                </div>
                
                <div class="card-footer bg-transparent">
                    <div class="d-flex gap-2">
                        <a href="{{ route('outlets.show', $outlet) }}" class="btn btn-outline-primary flex-fill">
                            <i class="fas fa-eye me-1"></i>Lihat Detail
                        </a>
                        @auth
                            @if(Auth::user()->isPembeli())
                                <button type="button" class="btn btn-outline-warning" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#ratingModal{{ $outlet->id }}">
                                    <i class="fas fa-star me-1"></i>Rate
                                </button>
                            @endif
                        @endauth
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Rating Modal -->
        @auth
            @if(Auth::user()->isPembeli())
            <div class="modal fade" id="ratingModal{{ $outlet->id }}" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Beri Rating untuk {{ $outlet->name }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <form action="{{ route('outlets.rate', $outlet) }}" method="POST">
                            @csrf
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label class="form-label">Rating</label>
                                    <div class="rating-input">
                                        @for($i = 5; $i >= 1; $i--)
                                            <input type="radio" name="rating" value="{{ $i }}" id="star{{ $i }}{{ $outlet->id }}" required>
                                            <label for="star{{ $i }}{{ $outlet->id }}">
                                                <i class="far fa-star"></i>
                                            </label>
                                        @endfor
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="comment{{ $outlet->id }}" class="form-label">Komentar (Opsional)</label>
                                    <textarea class="form-control" name="comment" id="comment{{ $outlet->id }}" rows="3" 
                                              placeholder="Bagikan pengalaman Anda..."></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-primary">Kirim Rating</button>
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
        {{ $outlets->links() }}
    </div>
@else
    <div class="text-center py-5">
        <i class="fas fa-search fa-4x text-muted mb-4"></i>
        <h4 class="text-muted mb-3">Tidak ada outlet ditemukan</h4>
        <p class="text-muted mb-4">
            @if($query || $category || $rating)
                Coba ubah filter pencarian Anda atau gunakan kata kunci yang berbeda
            @else
                Belum ada outlet yang terdaftar dalam sistem
            @endif
        </p>
        <div class="d-flex gap-2 justify-content-center">
            <a href="{{ route('search.outlets') }}" class="btn btn-primary">
                <i class="fas fa-refresh me-2"></i>Reset Filter
            </a>
            <a href="{{ route('outlets.index') }}" class="btn btn-outline-primary">
                <i class="fas fa-list me-2"></i>Lihat Semua Outlet
            </a>
        </div>
    </div>
@endif

<!-- Search Tips -->
<div class="row mt-5">
    <div class="col-12">
        <div class="card border-0 bg-light">
            <div class="card-body">
                <h6 class="text-primary mb-3">
                    <i class="fas fa-lightbulb me-2"></i>
                    Tips Pencarian
                </h6>
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="d-flex">
                            <i class="fas fa-search text-primary me-2 mt-1"></i>
                            <div>
                                <strong>Gunakan Kata Kunci</strong>
                                <p class="text-muted small mb-0">Cari dengan nama outlet atau jenis makanan</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="d-flex">
                            <i class="fas fa-tags text-primary me-2 mt-1"></i>
                            <div>
                                <strong>Filter Kategori</strong>
                                <p class="text-muted small mb-0">Pilih kategori yang sesuai kebutuhan</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="d-flex">
                            <i class="fas fa-star text-primary me-2 mt-1"></i>
                            <div>
                                <strong>Filter Rating</strong>
                                <p class="text-muted small mb-0">Pilih outlet dengan rating tinggi</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.outlet-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    border: none;
    border-radius: 15px;
}

.outlet-card:hover {
    transform: translateY(-5px);
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
