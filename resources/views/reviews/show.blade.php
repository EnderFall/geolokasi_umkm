@extends('layouts.app')

@section('title', 'Detail Review')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-info text-white">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-star me-2"></i>
                            <h5 class="mb-0">Detail Review</h5>
                        </div>
                        <div class="d-flex gap-2">
                            @auth
                                @if(auth()->id() === $review->user_id)
                                    <a href="{{ route('reviews.edit', $review) }}" class="btn btn-warning btn-sm">
                                        <i class="fas fa-edit me-1"></i>Edit
                                    </a>
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
                                @endif
                            @endauth
                        </div>
                    </div>
                </div>
                
                <div class="card-body">
                    <!-- Review Header -->
                    <div class="review-header mb-4">
                        <div class="d-flex align-items-start">
                            <div class="flex-shrink-0 me-3">
                                @if($review->user->profile_photo_url)
                                    <img src="{{ $review->user->profile_photo_url }}" 
                                         alt="{{ $review->user->name }}" 
                                         class="rounded-circle" 
                                         width="64" 
                                         height="64">
                                @else
                                    <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center" 
                                         style="width: 64px; height: 64px;">
                                        <i class="fas fa-user fa-2x text-white"></i>
                                    </div>
                                @endif
                            </div>
                            
                            <div class="flex-grow-1">
                                <h5 class="mb-1">
                                    @if($review->anonymous)
                                        <i class="fas fa-user-secret me-2 text-muted"></i>Reviewer Anonim
                                    @else
                                        {{ $review->user->name }}
                                    @endif
                                </h5>
                                
                                <div class="rating-display mb-2">
                                    <x-rating-display :rating="$review->rating" :showScore="true" size="md" />
                                </div>
                                
                                <div class="text-muted small">
                                    <i class="fas fa-clock me-1"></i>
                                    {{ $review->created_at->format('d F Y H:i') }}
                                    @if($review->updated_at != $review->created_at)
                                        <span class="ms-2">
                                            <i class="fas fa-edit me-1"></i>
                                            Diedit {{ $review->updated_at->diffForHumans() }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Review Content -->
                    @if($review->title)
                        <div class="review-title mb-3">
                            <h6 class="fw-bold">{{ $review->title }}</h6>
                        </div>
                    @endif
                    
                    <div class="review-comment mb-4">
                        <p class="mb-0">{{ $review->comment }}</p>
                    </div>
                    
                    <!-- Review Images -->
                    @if($review->images && count($review->images) > 0)
                        <div class="review-images mb-4">
                            <h6 class="mb-3">Gambar Review</h6>
                            <div class="row g-3">
                                @foreach($review->images as $image)
                                    <div class="col-md-4">
                                        <img src="{{ Storage::url($image) }}" 
                                             alt="Review image" 
                                             class="img-fluid rounded shadow-sm" 
                                             style="width: 100%; height: 200px; object-fit: cover; cursor: pointer;"
                                             onclick="openImageModal('{{ Storage::url($image) }}')">
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                    
                    <!-- Reviewable Info -->
                    <div class="reviewable-info p-3 bg-light rounded">
                        <h6 class="mb-3">
                            @if($review->reviewable_type === 'App\Models\Outlet')
                                <i class="fas fa-store me-2 text-warning"></i>
                                Review untuk Outlet
                            @else
                                <i class="fas fa-utensils me-2 text-success"></i>
                                Review untuk Menu
                            @endif
                        </h6>
                        
                        <div class="d-flex align-items-center">
                            @if($review->reviewable->image)
                                <img src="{{ Storage::url($review->reviewable->image) }}" 
                                     alt="{{ $review->reviewable->name }}" 
                                     class="rounded me-3" 
                                     style="width: 80px; height: 80px; object-fit: cover;">
                            @else
                                <div class="bg-secondary rounded d-flex align-items-center justify-content-center me-3" 
                                     style="width: 80px; height: 80px;">
                                    @if($review->reviewable_type === 'App\Models\Outlet')
                                        <i class="fas fa-store fa-2x text-white"></i>
                                    @else
                                        <i class="fas fa-utensils fa-2x text-white"></i>
                                    @endif
                                </div>
                            @endif
                            
                            <div>
                                <h6 class="mb-1">{{ $review->reviewable->name }}</h6>
                                @if($review->reviewable_type === 'App\Models\Outlet')
                                    <p class="text-muted mb-1">
                                        <i class="fas fa-map-marker-alt me-1"></i>
                                        {{ $review->reviewable->address }}
                                    </p>
                                    <p class="text-muted mb-0">
                                        <i class="fas fa-phone me-1"></i>
                                        {{ $review->reviewable->phone }}
                                    </p>
                                @else
                                    <p class="text-muted mb-1">
                                        <i class="fas fa-store me-1"></i>
                                        {{ $review->reviewable->outlet->name }}
                                    </p>
                                    <p class="text-muted mb-0">
                                        <i class="fas fa-tag me-1"></i>
                                        Rp {{ number_format($review->reviewable->price, 0, ',', '.') }}
                                    </p>
                                @endif
                            </div>
                        </div>
                        
                        <div class="mt-3">
                            <a href="{{ $review->reviewable_type === 'App\Models\Outlet' ? route('outlets.show', $review->reviewable) : route('menus.show', $review->reviewable) }}" 
                               class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-eye me-1"></i>
                                Lihat Detail {{ $review->reviewable_type === 'App\Models\Outlet' ? 'Outlet' : 'Menu' }}
                            </a>
                        </div>
                    </div>
                    
                    <!-- Review Status -->
                    <div class="review-status mt-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center">
                                <span class="me-2">Status:</span>
                                @if($review->is_verified)
                                    <span class="badge bg-success">
                                        <i class="fas fa-check-circle me-1"></i>Terverifikasi
                                    </span>
                                @else
                                    <span class="badge bg-warning">
                                        <i class="fas fa-clock me-1"></i>Menunggu Verifikasi
                                    </span>
                                @endif
                            </div>
                            
                            <div class="text-muted small">
                                ID Review: #{{ $review->id }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Navigation -->
            <div class="d-flex justify-content-between mt-4">
                <a href="{{ $review->reviewable_type === 'App\Models\Outlet' ? route('outlets.show', $review->reviewable) : route('menus.show', $review->reviewable) }}" 
                   class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Kembali
                </a>
                
                <div class="d-flex gap-2">
                    @if($review->reviewable_type === 'App\Models\Outlet')
                        <a href="{{ route('reviews.create.outlet', $review->reviewable) }}" class="btn btn-warning">
                            <i class="fas fa-star me-2"></i>Buat Review Lain
                        </a>
                    @else
                        <a href="{{ route('reviews.create.menu', $review->reviewable) }}" class="btn btn-warning">
                            <i class="fas fa-star me-2"></i>Buat Review Lain
                        </a>
                    @endif
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

<script>
function openImageModal(imageSrc) {
    document.getElementById('modalImage').src = imageSrc;
    new bootstrap.Modal(document.getElementById('imageModal')).show();
}
</script>
@endsection
