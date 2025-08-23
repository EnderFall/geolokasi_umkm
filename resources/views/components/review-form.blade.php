@props(['reviewable', 'reviewableType'])

@php
    $type = $reviewableType === 'App\Models\Outlet' ? 'outlet' : 'menu';
    $route = $type === 'outlet' ? route('reviews.create.outlet', $reviewable) : route('reviews.create.menu', $reviewable);
@endphp

<div class="review-form-component">
    @auth
        @if(Review::canUserReview(auth()->id(), $reviewableType, $reviewable->id))
            <div class="card shadow-sm">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-star me-2 text-warning"></i>
                        Berikan Review
                    </h6>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-3">Bagikan pengalaman Anda dengan {{ $reviewable->name }}</p>
                    <a href="{{ $route }}" class="btn btn-warning">
                        <i class="fas fa-star me-2"></i>Buat Review
                    </a>
                </div>
            </div>
        @else
            <div class="alert alert-info">
                <div class="d-flex align-items-center">
                    <i class="fas fa-info-circle me-2"></i>
                    <div>
                        <strong>Review tersedia setelah pesanan selesai</strong>
                        <br><small class="text-muted">Anda harus menyelesaikan pesanan dari {{ $type === 'outlet' ? 'outlet' : 'menu' }} ini terlebih dahulu sebelum dapat memberikan review.</small>
                    </div>
                </div>
            </div>
        @endif
    @else
        <div class="alert alert-warning">
            <div class="d-flex align-items-center">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <div>
                    <strong>Login untuk memberikan review</strong>
                    <br><small class="text-muted">Silakan login terlebih dahulu untuk dapat memberikan review.</small>
                </div>
            </div>
            <div class="mt-2">
                <a href="{{ route('login') }}" class="btn btn-warning btn-sm">
                    <i class="fas fa-sign-in-alt me-2"></i>Login
                </a>
                <a href="{{ route('register') }}" class="btn btn-outline-warning btn-sm ms-2">
                    <i class="fas fa-user-plus me-2"></i>Register
                </a>
            </div>
        </div>
    @endauth
</div>
