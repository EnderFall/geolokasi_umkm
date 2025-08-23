@props(['review'])

<div class="review-item border-bottom pb-3 mb-3">
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
            <div class="d-flex align-items-center mb-2">
                <h6 class="mb-0 me-2">{{ $review->user->name }}</h6>
                <div class="rating-display">
                    @for($i = 1; $i <= 5; $i++)
                        @if($i <= $review->rating)
                            <i class="fas fa-star text-warning"></i>
                        @else
                            <i class="far fa-star text-muted"></i>
                        @endif
                    @endfor
                    <span class="ms-2 text-muted small">{{ $review->rating }}/5</span>
                </div>
            </div>
            
            @if($review->title)
                <h6 class="mb-2">{{ $review->title }}</h6>
            @endif
            
            <p class="mb-2">{{ $review->comment }}</p>
            
            <div class="d-flex align-items-center text-muted small">
                <i class="fas fa-clock me-1"></i>
                <span>{{ $review->created_at->diffForHumans() }}</span>
                
                @if($review->reviewable_type === 'App\Models\Outlet')
                    <span class="mx-2">•</span>
                    <i class="fas fa-store me-1"></i>
                    <span>Review Outlet</span>
                @elseif($review->reviewable_type === 'App\Models\Menu')
                    <span class="mx-2">•</span>
                    <i class="fas fa-utensils me-1"></i>
                    <span>Review Menu</span>
                @endif
            </div>
            
            @if($review->images && count($review->images) > 0)
                <div class="review-images mt-3">
                    <div class="row g-2">
                        @foreach($review->images as $image)
                            <div class="col-auto">
                                <img src="{{ Storage::url($image) }}" 
                                     alt="Review image" 
                                     class="img-thumbnail" 
                                     style="width: 80px; height: 80px; object-fit: cover;"
                                     onclick="openImageModal('{{ Storage::url($image) }}')">
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
            
            @auth
                @if(auth()->id() === $review->user_id)
                    <div class="review-actions mt-3">
                        <a href="{{ route('reviews.edit', $review) }}" 
                           class="btn btn-outline-primary btn-sm me-2">
                            <i class="fas fa-edit me-1"></i>Edit
                        </a>
                        <form action="{{ route('reviews.destroy', $review) }}" 
                              method="POST" 
                              class="d-inline"
                              onsubmit="return confirm('Apakah Anda yakin ingin menghapus review ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger btn-sm">
                                <i class="fas fa-trash me-1"></i>Hapus
                            </button>
                        </form>
                    </div>
                @endif
            @endauth
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
