@extends('layouts.app')

@section('title', 'Daftar Review')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">
                <i class="fas fa-star me-2 text-warning"></i>
                Daftar Review
            </h2>
            <p class="text-muted mb-0">Kelola semua review dalam sistem</p>
        </div>
        
        @auth
            @if(auth()->user()->hasRole(['admin', 'superadmin']))
                <div class="d-flex gap-2">
                    <a href="{{ route('reviews.pending') }}" class="btn btn-warning">
                        <i class="fas fa-clock me-2"></i>Review Pending
                        @if($pendingCount > 0)
                            <span class="badge bg-light text-dark ms-1">{{ $pendingCount }}</span>
                        @endif
                    </a>
                    <a href="{{ route('reviews.export') }}" class="btn btn-success">
                        <i class="fas fa-download me-2"></i>Export Data
                    </a>
                </div>
            @endif
        @endauth
    </div>
    
    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('reviews.index') }}" method="GET" class="row g-3">
                <div class="col-md-3">
                    <label for="search" class="form-label">Cari Review</label>
                    <input type="text" 
                           class="form-control" 
                           id="search" 
                           name="search" 
                           value="{{ request('search') }}" 
                           placeholder="Cari judul, komentar, atau nama user...">
                </div>
                
                <div class="col-md-2">
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
                
                <div class="col-md-2">
                    <label for="type" class="form-label">Tipe Review</label>
                    <select class="form-select" id="type" name="type">
                        <option value="">Semua Tipe</option>
                        <option value="outlet" {{ request('type') == 'outlet' ? 'selected' : '' }}>Outlet</option>
                        <option value="menu" {{ request('type') == 'menu' ? 'selected' : '' }}>Menu</option>
                    </select>
                </div>
                
                <div class="col-md-2">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select" id="status" name="status">
                        <option value="">Semua Status</option>
                        <option value="verified" {{ request('status') == 'verified' ? 'selected' : '' }}>Terverifikasi</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    </select>
                </div>
                
                <div class="col-md-3">
                    <label class="form-label">&nbsp;</label>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search me-2"></i>Cari
                        </button>
                        <a href="{{ route('reviews.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times me-2"></i>Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Reviews List -->
    <div class="row">
        <div class="col-12">
            @if($reviews->count() > 0)
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <p class="text-muted mb-0">
                        Menampilkan {{ $reviews->firstItem() }}-{{ $reviews->lastItem() }} 
                        dari {{ $reviews->total() }} review
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
                
                <div class="reviews-container">
                    @foreach($reviews as $review)
                        <div class="card mb-3 shadow-sm">
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
                                                    @endif
                                                </h6>
                                                
                                                <div class="rating-display">
                                                    <x-rating-display :rating="$review->rating" :showScore="false" size="sm" />
                                                </div>
                                            </div>
                                            
                                            <div class="d-flex align-items-center gap-2">
                                                @if($review->is_verified)
                                                    <span class="badge bg-success">
                                                        <i class="fas fa-check-circle me-1"></i>Terverifikasi
                                                    </span>
                                                @else
                                                    <span class="badge bg-warning">
                                                        <i class="fas fa-clock me-1"></i>Pending
                                                    </span>
                                                @endif
                                                
                                                <div class="dropdown">
                                                    <button class="btn btn-outline-secondary btn-sm dropdown-toggle" 
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
                                                            @endif
                                                            
                                                            @if(auth()->user()->hasRole(['admin', 'superadmin']))
                                                                <li>
                                                                    <hr class="dropdown-divider">
                                                                </li>
                                                                <li>
                                                                    <form action="{{ route('reviews.toggle-verification', $review) }}" 
                                                                          method="POST" 
                                                                          class="d-inline">
                                                                        @csrf
                                                                        @method('PATCH')
                                                                        <button type="submit" class="dropdown-item">
                                                                            @if($review->is_verified)
                                                                                <i class="fas fa-times me-2"></i>Batalkan Verifikasi
                                                                            @else
                                                                                <i class="fas fa-check me-2"></i>Verifikasi
                                                                            @endif
                                                                        </button>
                                                                    </form>
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
                    {{ $reviews->appends(request()->query())->links() }}
                </div>
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
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-submit form when filters change
    const filterInputs = document.querySelectorAll('#rating, #type, #status');
    filterInputs.forEach(input => {
        input.addEventListener('change', function() {
            this.closest('form').submit();
        });
    });
});
</script>
@endpush
