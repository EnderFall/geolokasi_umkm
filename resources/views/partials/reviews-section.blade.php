@props(['reviewable', 'reviewableType'])

<div class="reviews-section mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">
            <i class="fas fa-star me-2 text-warning"></i>
            Review & Rating
        </h4>
        
        <div class="d-flex align-items-center gap-2">
            @if($reviewable->reviews->count() > 0)
                <div class="rating-summary me-3">
                    <div class="d-flex align-items-center">
                        <div class="me-2">
                            <x-rating-display :rating="$reviewable->rating" :showScore="true" size="md" />
                        </div>
                        <span class="text-muted">({{ $reviewable->total_reviews }} review)</span>
                    </div>
                </div>
            @endif
            
            <x-review-form :reviewable="$reviewable" :reviewableType="$reviewableType" />
        </div>
    </div>
    
    <!-- Review Form Component -->
    <div class="mb-4">
        <x-review-form :reviewable="$reviewable" :reviewableType="$reviewableType" />
    </div>
    
    <!-- Reviews List -->
    @if($reviewable->reviews->count() > 0)
        <div class="reviews-container">
            <!-- Review Statistics -->
            <div class="review-stats mb-4">
                <div class="row g-3">
                    @for($i = 5; $i >= 1; $i--)
                        @php
                            $count = $reviewable->reviews->where('rating', $i)->count();
                            $percentage = $reviewable->total_reviews > 0 ? ($count / $reviewable->total_reviews) * 100 : 0;
                        @endphp
                        <div class="col-md-6">
                            <div class="d-flex align-items-center">
                                <div class="me-2" style="min-width: 60px;">
                                    <span class="text-muted">{{ $i }} bintang</span>
                                </div>
                                <div class="flex-grow-1 me-2">
                                    <div class="progress" style="height: 8px;">
                                        <div class="progress-bar bg-warning" 
                                             style="width: {{ $percentage }}%"></div>
                                    </div>
                                </div>
                                <div class="text-muted small" style="min-width: 40px;">
                                    {{ $count }}
                                </div>
                            </div>
                        </div>
                    @endfor
                </div>
            </div>
            
            <!-- Reviews Filter -->
            <div class="reviews-filter mb-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex gap-2">
                        <button type="button" 
                                class="btn btn-outline-primary btn-sm filter-btn active" 
                                data-filter="all">
                            Semua ({{ $reviewable->reviews->count() }})
                        </button>
                        <button type="button" 
                                class="btn btn-outline-primary btn-sm filter-btn" 
                                data-filter="5">
                            5 Bintang ({{ $reviewable->reviews->where('rating', 5)->count() }})
                        </button>
                        <button type="button" 
                                class="btn btn-outline-primary btn-sm filter-btn" 
                                data-filter="4">
                            4 Bintang ({{ $reviewable->reviews->where('rating', 4)->count() }})
                        </button>
                        <button type="button" 
                                class="btn btn-outline-primary btn-sm filter-btn" 
                                data-filter="3">
                            3 Bintang ({{ $reviewable->reviews->where('rating', 3)->count() }})
                        </button>
                        <button type="button" 
                                class="btn btn-outline-primary btn-sm filter-btn" 
                                data-filter="2">
                            2 Bintang ({{ $reviewable->reviews->where('rating', 2)->count() }})
                        </button>
                        <button type="button" 
                                class="btn btn-outline-primary btn-sm filter-btn" 
                                data-filter="1">
                            1 Bintang ({{ $reviewable->reviews->where('rating', 1)->count() }})
                        </button>
                    </div>
                    
                    <div class="d-flex gap-2">
                        <span class="text-muted small">Urutkan:</span>
                        <select class="form-select form-select-sm" id="sortReviews" style="width: auto;">
                            <option value="latest">Terbaru</option>
                            <option value="oldest">Terlama</option>
                            <option value="rating">Rating Tertinggi</option>
                            <option value="helpful">Paling Membantu</option>
                        </select>
                    </div>
                </div>
            </div>
            
            <!-- Reviews List -->
            <div class="reviews-list">
                @foreach($reviewable->reviews->sortByDesc('created_at') as $review)
                    <div class="review-item-wrapper" data-rating="{{ $review->rating }}">
                        <x-review-item :review="$review" />
                    </div>
                @endforeach
            </div>
            
            <!-- Load More Reviews -->
            @if($reviewable->reviews->count() > 5)
                <div class="text-center mt-4">
                    <button type="button" class="btn btn-outline-primary" id="loadMoreReviews">
                        <i class="fas fa-plus me-2"></i>Muat Lebih Banyak Review
                    </button>
                </div>
            @endif
        </div>
    @else
        <div class="text-center py-5">
            <div class="mb-3">
                <i class="fas fa-star fa-3x text-muted"></i>
            </div>
            <h5 class="text-muted">Belum ada review</h6>
            <p class="text-muted">Jadilah yang pertama memberikan review untuk {{ $reviewableType === 'App\Models\Outlet' ? 'outlet' : 'menu' }} ini</p>
        </div>
    @endif
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Review filtering
    const filterBtns = document.querySelectorAll('.filter-btn');
    const reviewItems = document.querySelectorAll('.review-item-wrapper');
    
    filterBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            // Remove active class from all buttons
            filterBtns.forEach(b => b.classList.remove('active'));
            // Add active class to clicked button
            this.classList.add('active');
            
            const filter = this.dataset.filter;
            
            reviewItems.forEach(item => {
                if (filter === 'all' || item.dataset.rating == filter) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    });
    
    // Review sorting
    const sortSelect = document.getElementById('sortReviews');
    if (sortSelect) {
        sortSelect.addEventListener('change', function() {
            const sortBy = this.value;
            const reviewsList = document.querySelector('.reviews-list');
            const reviewItems = Array.from(reviewsList.querySelectorAll('.review-item-wrapper'));
            
            reviewItems.sort((a, b) => {
                switch(sortBy) {
                    case 'latest':
                        return new Date(b.querySelector('.review-item').dataset.created) - 
                               new Date(a.querySelector('.review-item').dataset.created);
                    case 'oldest':
                        return new Date(a.querySelector('.review-item').dataset.created) - 
                               new Date(b.querySelector('.review-item').dataset.created);
                    case 'rating':
                        return parseInt(b.dataset.rating) - parseInt(a.dataset.rating);
                    case 'helpful':
                        // Sort by helpful votes (if implemented)
                        return 0;
                    default:
                        return 0;
                }
            });
            
            // Reorder items in DOM
            reviewItems.forEach(item => reviewsList.appendChild(item));
        });
    }
    
    // Load more reviews
    const loadMoreBtn = document.getElementById('loadMoreReviews');
    if (loadMoreBtn) {
        let visibleCount = 5;
        const allReviews = document.querySelectorAll('.review-item-wrapper');
        
        // Initially hide reviews beyond first 5
        allReviews.forEach((item, index) => {
            if (index >= 5) {
                item.style.display = 'none';
            }
        });
        
        loadMoreBtn.addEventListener('click', function() {
            visibleCount += 5;
            
            allReviews.forEach((item, index) => {
                if (index < visibleCount) {
                    item.style.display = 'block';
                }
            });
            
            // Hide load more button if all reviews are shown
            if (visibleCount >= allReviews.length) {
                this.style.display = 'none';
            }
        });
    }
});
</script>
@endpush

<style>
.reviews-section {
    border-top: 1px solid #e9ecef;
    padding-top: 2rem;
}

.review-stats .progress {
    background-color: #f8f9fa;
    border-radius: 10px;
}

.review-stats .progress-bar {
    border-radius: 10px;
}

.filter-btn.active {
    background-color: #0d6efd;
    color: white;
    border-color: #0d6efd;
}

.review-item-wrapper {
    transition: all 0.3s ease;
}

.review-item-wrapper:hover {
    transform: translateY(-2px);
}

#sortReviews {
    border-radius: 20px;
    border: 1px solid #dee2e6;
    padding: 0.375rem 0.75rem;
    font-size: 0.875rem;
}

#loadMoreReviews {
    border-radius: 25px;
    padding: 0.5rem 1.5rem;
}
</style>
