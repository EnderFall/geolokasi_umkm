@extends('layouts.mobile')

@section('title', 'Review')

@section('content')
<div class="mobile-container">
    <!-- Header -->
    <div class="mobile-header bg-warning text-dark">
        <div class="d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center">
                <i class="fas fa-star fa-lg me-2"></i>
                <h5 class="mb-0">Review</h5>
            </div>
            <div class="d-flex gap-2">
                <button type="button" class="btn btn-sm btn-outline-dark" data-bs-toggle="modal" data-bs-target="#filterModal">
                    <i class="fas fa-filter"></i>
                </button>
                <button type="button" class="btn btn-sm btn-outline-dark" data-bs-toggle="modal" data-bs-target="#sortModal">
                    <i class="fas fa-sort"></i>
                </button>
            </div>
        </div>
    </div>
    
    <!-- Search Bar -->
    <div class="search-bar p-3 bg-light">
        <div class="input-group">
            <span class="input-group-text bg-white border-end-0">
                <i class="fas fa-search text-muted"></i>
            </span>
            <input type="text" 
                   class="form-control border-start-0" 
                   id="searchInput" 
                   placeholder="Cari review...">
        </div>
    </div>
    
    <!-- Reviews List -->
    <div class="reviews-list">
        @if($reviews->count() > 0)
            @foreach($reviews as $review)
                <div class="review-card mb-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <!-- User Info -->
                            <div class="d-flex align-items-center mb-3">
                                <div class="flex-shrink-0 me-3">
                                    @if($review->user->profile_photo_url)
                                        <img src="{{ $review->user->profile_photo_url }}" 
                                             alt="{{ $review->user->name }}" 
                                             class="rounded-circle" 
                                             style="width: 48px; height: 48px;">
                                    @else
                                        <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center" 
                                             style="width: 48px; height: 48px;">
                                            <i class="fas fa-user text-white"></i>
                                        </div>
                                    @endif
                                </div>
                                
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">
                                        @if($review->anonymous)
                                            <i class="fas fa-user-secret me-1 text-muted"></i>Reviewer Anonim
                                        @else
                                            {{ $review->user->name }}
                                        @endif
                                    </h6>
                                    
                                    <div class="rating-display mb-1">
                                        <x-rating-display :rating="$review->rating" :showScore="false" size="sm" />
                                    </div>
                                    
                                    <small class="text-muted">
                                        <i class="fas fa-clock me-1"></i>
                                        {{ $review->created_at->diffForHumans() }}
                                    </small>
                                </div>
                                
                                <div class="dropdown">
                                    <button class="btn btn-link text-muted p-0" 
                                            type="button" 
                                            data-bs-toggle="dropdown">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a class="dropdown-item" href="{{ route('reviews.show', $review) }}">
                                                <i class="fas fa-eye me-2"></i>Lihat Detail
                                            </a>
                                        </li>
                                        @auth
                                            @if(auth()->id() === $review->user_id)
                                                <li>
                                                    <a class="dropdown-item" href="{{ route('reviews.edit', $review) }}">
                                                        <i class="fas fa-edit me-2"></i>Edit
                                                    </a>
                                                </li>
                                                <li>
                                                    <hr class="dropdown-divider">
                                                </li>
                                                <li>
                                                    <form action="{{ route('reviews.destroy', $review) }}" 
                                                          method="POST" 
                                                          class="d-inline"
                                                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus review ini?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="dropdown-item text-danger">
                                                            <i class="fas fa-trash me-2"></i>Hapus
                                                        </button>
                                                    </form>
                                                </li>
                                            @endif
                                        @endauth
                                    </ul>
                                </div>
                            </div>
                            
                            <!-- Review Content -->
                            @if($review->title)
                                <h6 class="mb-2 fw-bold">{{ $review->title }}</h6>
                            @endif
                            
                            <p class="mb-3">{{ $review->comment }}</p>
                            
                            <!-- Review Images -->
                            @if($review->images && count($review->images) > 0)
                                <div class="review-images mb-3">
                                    <div class="row g-2">
                                        @foreach(array_slice($review->images, 0, 3) as $image)
                                            <div class="col-4">
                                                <img src="{{ Storage::url($image) }}" 
                                                     alt="Review image" 
                                                     class="img-fluid rounded" 
                                                     style="width: 100%; height: 80px; object-fit: cover; cursor: pointer;"
                                                     onclick="openImageModal('{{ Storage::url($image) }}')">
                                            </div>
                                        @endforeach
                                        @if(count($review->images) > 3)
                                            <div class="col-4">
                                                <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                                                     style="width: 100%; height: 80px;">
                                                    <small class="text-muted">+{{ count($review->images) - 3 }}</small>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endif
                            
                            <!-- Review Info -->
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center text-muted small">
                                    @if($review->reviewable_type === 'App\Models\Outlet')
                                        <i class="fas fa-store me-1"></i>
                                        <span>{{ $review->reviewable->name }}</span>
                                    @else
                                        <i class="fas fa-utensils me-1"></i>
                                        <span>{{ $review->reviewable->name }}</span>
                                        <span class="mx-1">-</span>
                                        <i class="fas fa-store me-1"></i>
                                        <span>{{ $review->reviewable->outlet->name }}</span>
                                    @endif
                                </div>
                                
                                @if($review->is_verified)
                                    <span class="badge bg-success">
                                        <i class="fas fa-check-circle me-1"></i>Terverifikasi
                                    </span>
                                @else
                                    <span class="badge bg-warning">
                                        <i class="fas fa-clock me-1"></i>Pending
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
            
            <!-- Load More -->
            @if($reviews->hasMorePages())
                <div class="text-center py-4">
                    <button type="button" class="btn btn-outline-primary" id="loadMoreBtn">
                        <i class="fas fa-plus me-2"></i>Muat Lebih Banyak
                    </button>
                </div>
            @endif
        @else
            <div class="text-center py-5">
                <div class="mb-3">
                    <i class="fas fa-star fa-3x text-muted"></i>
                </div>
                <h5 class="text-muted">Belum ada review</h5>
                <p class="text-muted">Review akan muncul di sini setelah user memberikan review</p>
            </div>
        @endif
    </div>
</div>

<!-- Filter Modal -->
<div class="modal fade" id="filterModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Filter Review</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="filterForm">
                    <div class="mb-3">
                        <label for="filterRating" class="form-label">Rating</label>
                        <select class="form-select" id="filterRating" name="rating">
                            <option value="">Semua Rating</option>
                            @for($i = 5; $i >= 1; $i--)
                                <option value="{{ $i }}">{{ $i }} Bintang</option>
                            @endfor
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="filterType" class="form-label">Tipe Review</label>
                        <select class="form-select" id="filterType" name="type">
                            <option value="">Semua Tipe</option>
                            <option value="outlet">Outlet</option>
                            <option value="menu">Menu</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="filterStatus" class="form-label">Status</label>
                        <select class="form-select" id="filterStatus" name="status">
                            <option value="">Semua Status</option>
                            <option value="verified">Terverifikasi</option>
                            <option value="pending">Pending</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="applyFilter">Terapkan</button>
            </div>
        </div>
    </div>
</div>

<!-- Sort Modal -->
<div class="modal fade" id="sortModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Urutkan Review</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="d-grid gap-2">
                    <button type="button" class="btn btn-outline-primary text-start sort-btn" data-sort="latest">
                        <i class="fas fa-clock me-2"></i>Terbaru
                    </button>
                    <button type="button" class="btn btn-outline-primary text-start sort-btn" data-sort="oldest">
                        <i class="fas fa-history me-2"></i>Terlama
                    </button>
                    <button type="button" class="btn btn-outline-primary text-start sort-btn" data-sort="rating">
                        <i class="fas fa-star me-2"></i>Rating Tertinggi
                    </button>
                    <button type="button" class="btn btn-outline-primary text-start sort-btn" data-sort="helpful">
                        <i class="fas fa-thumbs-up me-2"></i>Paling Membantu
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Image Modal -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Gambar Review</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <img id="modalImage" src="" alt="Review image" class="img-fluid">
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Search functionality
    const searchInput = document.getElementById('searchInput');
    let searchTimeout;
    
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            // Implement search functionality
            console.log('Searching for:', this.value);
        }, 500);
    });
    
    // Filter functionality
    document.getElementById('applyFilter').addEventListener('click', function() {
        const form = document.getElementById('filterForm');
        const formData = new FormData(form);
        
        // Apply filters
        console.log('Applying filters:', Object.fromEntries(formData));
        
        // Close modal
        bootstrap.Modal.getInstance(document.getElementById('filterModal')).hide();
    });
    
    // Sort functionality
    document.querySelectorAll('.sort-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const sortBy = this.dataset.sort;
            
            // Remove active class from all buttons
            document.querySelectorAll('.sort-btn').forEach(b => b.classList.remove('active'));
            // Add active class to clicked button
            this.classList.add('active');
            
            // Apply sorting
            console.log('Sorting by:', sortBy);
            
            // Close modal
            bootstrap.Modal.getInstance(document.getElementById('sortModal')).hide();
        });
    });
    
    // Load more functionality
    const loadMoreBtn = document.getElementById('loadMoreBtn');
    if (loadMoreBtn) {
        loadMoreBtn.addEventListener('click', function() {
            // Implement load more functionality
            console.log('Loading more reviews');
        });
    }
    
    // Image modal
    window.openImageModal = function(imageSrc) {
        document.getElementById('modalImage').src = imageSrc;
        new bootstrap.Modal(document.getElementById('imageModal')).show();
    };
});
</script>
@endpush

<style>
.mobile-container {
    padding-bottom: 80px;
}

.mobile-header {
    padding: 1rem;
    position: sticky;
    top: 0;
    z-index: 1000;
}

.search-bar {
    position: sticky;
    top: 70px;
    z-index: 999;
}

.review-card {
    margin: 0 1rem;
}

.review-card .card {
    border-radius: 15px;
}

.rating-display {
    font-size: 0.875rem;
}

.review-images img {
    transition: transform 0.2s ease;
}

.review-images img:hover {
    transform: scale(1.05);
}

.sort-btn.active {
    background-color: #0d6efd;
    color: white;
    border-color: #0d6efd;
}

.modal-dialog {
    margin: 1rem;
}

@media (max-width: 576px) {
    .review-card {
        margin: 0 0.5rem;
    }
    
    .mobile-header {
        padding: 0.75rem;
    }
    
    .search-bar {
        padding: 0.75rem;
    }
}
</style>
