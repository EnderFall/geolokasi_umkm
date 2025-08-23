@extends('layouts.app')

@section('title', 'Review Pending')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">
                <i class="fas fa-clock me-2 text-warning"></i>
                Review Pending
            </h2>
            <p class="text-muted mb-0">Review yang menunggu verifikasi admin</p>
        </div>
        
        <div class="d-flex gap-2">
            <a href="{{ route('reviews.index') }}" class="btn btn-outline-primary">
                <i class="fas fa-arrow-left me-2"></i>Kembali ke Review
            </a>
            <a href="{{ route('reviews.export') }}?status=pending" class="btn btn-success">
                <i class="fas fa-download me-2"></i>Export Pending
            </a>
        </div>
    </div>
    
    <!-- Statistics -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-clock fa-2x"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h4 class="mb-1">{{ $pendingReviews->total() }}</h4>
                            <small>Total Pending</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-store fa-2x"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h4 class="mb-1">{{ $pendingReviews->where('reviewable_type', 'App\Models\Outlet')->count() }}</h4>
                            <small>Review Outlet</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-utensils fa-2x"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h4 class="mb-1">{{ $pendingReviews->where('reviewable_type', 'App\Models\Menu')->count() }}</h4>
                            <small>Review Menu</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-users fa-2x"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h4 class="mb-1">{{ $pendingReviews->unique('user_id')->count() }}</h4>
                            <small>User Pending</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('reviews.pending') }}" method="GET" class="row g-3">
                <div class="col-md-4">
                    <label for="search" class="form-label">Cari Review</label>
                    <input type="text" 
                           class="form-control" 
                           id="search" 
                           name="search" 
                           value="{{ request('search') }}" 
                           placeholder="Cari judul, komentar, atau nama user...">
                </div>
                
                <div class="col-md-3">
                    <label for="rating" class="form-label">Rating</label>
                    <select class="form-select" id="rating" name="rating">
                        <option value="">Semua Rating</option>
                        @for($i = 5; $i >= 1; $i--)
                            <option value="{{ $i }}" {{ request('rating') == $i ? 'selected' : '' }}>
                                {{ $i }} Bintang
                            </option>
                        @endfor
                    </select>
                </div>
                
                <div class="col-md-3">
                    <label for="type" class="form-label">Tipe Review</label>
                    <select class="form-select" id="type" name="type">
                        <option value="">Semua Tipe</option>
                        <option value="outlet" {{ request('type') == 'outlet' ? 'selected' : '' }}>Outlet</option>
                        <option value="menu" {{ request('type') == 'menu' ? 'selected' : '' }}>Menu</option>
                    </select>
                </div>
                
                <div class="col-md-2">
                    <label class="form-label">&nbsp;</label>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search me-2"></i>Cari
                        </button>
                        <a href="{{ route('reviews.pending') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times me-2"></i>Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Pending Reviews List -->
    <div class="row">
        <div class="col-12">
            @if($pendingReviews->count() > 0)
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <p class="text-muted mb-0">
                        Menampilkan {{ $pendingReviews->firstItem() }}-{{ $pendingReviews->lastItem() }} 
                        dari {{ $pendingReviews->total() }} review pending
                    </p>
                    
                    <div class="d-flex gap-2">
                        <span class="text-muted">Urutkan:</span>
                        <div class="btn-group" role="group">
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'latest']) }}" 
                               class="btn btn-outline-primary btn-sm {{ request('sort', 'latest') == 'latest' ? 'active' : '' }}">
                                Terbaru
                            </a>
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'oldest']) }}" 
                               class="btn btn-outline-primary btn-sm {{ request('sort') == 'oldest' ? 'active' : '' }}">
                                Terlama
                            </a>
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'rating']) }}" 
                               class="btn btn-outline-primary btn-sm {{ request('sort') == 'rating' ? 'active' : '' }}">
                                Rating Tertinggi
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="pending-reviews-container">
                    @foreach($pendingReviews as $review)
                        <div class="card mb-3 shadow-sm border-warning">
                            <div class="card-body">
                                <div class="d-flex align-items-start">
                                    <div class="flex-shrink-0 me-3">
                                        @if($review->user->profile_photo_url)
                                            <img src="{{ $review->user->profile_photo_url }}" 
                                                 alt="{{ $review->user->name }}" 
                                                 class="rounded-circle" 
                                                 width="48" 
                                                 height="48">
                                        @else
                                            <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center" 
                                                 style="width: 48px; height: 48px;">
                                                <i class="fas fa-user text-white"></i>
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <div class="flex-grow-1">
                                        <div class="d-flex align-items-center justify-content-between mb-2">
                                            <div class="d-flex align-items-center">
                                                <h6 class="mb-0 me-2">
                                                    @if($review->anonymous)
                                                        <i class="fas fa-user-secret me-1 text-muted"></i>Reviewer Anonim
                                                    @else
                                                        {{ $review->user->name }}
                                                    </h6>
                                                @endif
                                                
                                                <div class="rating-display">
                                                    <x-rating-display :rating="$review->rating" :showScore="false" size="sm" />
                                                </div>
                                                
                                                <span class="badge bg-warning ms-2">
                                                    <i class="fas fa-clock me-1"></i>Pending
                                                </span>
                                            </div>
                                            
                                            <div class="d-flex gap-2">
                                                <a href="{{ route('reviews.show', $review) }}" class="btn btn-outline-primary btn-sm">
                                                    <i class="fas fa-eye me-1"></i>Lihat
                                                </a>
                                                
                                                <form action="{{ route('reviews.toggle-verification', $review) }}" 
                                                      method="POST" 
                                                      class="d-inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="btn btn-success btn-sm">
                                                        <i class="fas fa-check me-1"></i>Verifikasi
                                                    </button>
                                                </form>
                                                
                                                <form action="{{ route('reviews.destroy', $review) }}" 
                                                      method="POST" 
                                                      class="d-inline"
                                                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus review ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm">
                                                        <i class="fas fa-trash me-1"></i>Hapus
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                        
                                        @if($review->title)
                                            <h6 class="mb-2">{{ $review->title }}</h6>
                                        @endif
                                        
                                        <p class="mb-2">{{ Str::limit($review->comment, 150) }}</p>
                                        
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div class="d-flex align-items-center text-muted small">
                                                <i class="fas fa-clock me-1"></i>
                                                <span>{{ $review->created_at->diffForHumans() }}</span>
                                                
                                                <span class="mx-2">•</span>
                                                
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
                                            
                                            @if($review->images && count($review->images) > 0)
                                                <div class="text-muted small">
                                                    <i class="fas fa-images me-1"></i>
                                                    {{ count($review->images) }} gambar
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <!-- Pagination -->
                <div class="d-flex justify-content-center">
                    {{ $pendingReviews->appends(request()->query())->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <div class="mb-3">
                        <i class="fas fa-check-circle fa-3x text-success"></i>
                    </div>
                    <h5 class="text-success">Tidak ada review pending</h5>
                    <p class="text-muted">Semua review telah diverifikasi</p>
                    <a href="{{ route('reviews.index') }}" class="btn btn-primary">
                        <i class="fas fa-arrow-left me-2"></i>Kembali ke Review
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-submit form when filters change
    const filterInputs = document.querySelectorAll('#rating, #type');
    filterInputs.forEach(input => {
        input.addEventListener('change', function() {
            this.closest('form').submit();
        });
    });
    
    // Bulk verification
    const verifyAllBtn = document.getElementById('verifyAll');
    if (verifyAllBtn) {
        verifyAllBtn.addEventListener('click', function() {
            if (confirm('Verifikasi semua review pending?')) {
                // Implementation for bulk verification
                console.log('Bulk verification requested');
            }
        });
    }
});
</script>
@endpush
