@extends('layouts.app')

@section('title', $menu->name . ' - Geolokasi UMKM Kuliner')

@section('content')
<!-- Menu Header -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card bg-gradient-success text-white">
            <div class="card-body p-4">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-2">
                                <li class="breadcrumb-item">
                                    <a href="{{ route('menus.index') }}" class="text-white text-decoration-none">
                                        <i class="fas fa-utensils me-1"></i>Menu
                                    </a>
                                </li>
                                <li class="breadcrumb-item active text-white" aria-current="page">{{ $menu->name }}</li>
                            </ol>
                        </nav>
                        
                        <h2 class="mb-2">{{ $menu->name }}</h2>
                        <p class="mb-2">{{ $menu->description }}</p>
                        
                        <div class="d-flex align-items-center mb-2">
                            <div class="text-warning me-2">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= $menu->average_rating)
                                        <i class="fas fa-star"></i>
                                    @else
                                        <i class="far fa-star"></i>
                                    @endif
                                @endfor
                            </div>
                            <span class="fw-bold me-2">{{ number_format($menu->average_rating, 1) }}</span>
                            <span class="text-white-50">({{ $menu->total_reviews }} review)</span>
                        </div>
                        
                        <div class="row g-3">
                            <div class="col-md-6">
                                <small class="text-white-50">
                                    <i class="fas fa-store me-2"></i>
                                    {{ $menu->outlet->name }}
                                </small>
                            </div>
                            <div class="col-md-6">
                                <small class="text-white-50">
                                    <i class="fas fa-tag me-2"></i>
                                    Rp {{ number_format($menu->price, 0, ',', '.') }}
                                </small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4 text-md-end">
                        <div class="d-flex flex-column align-items-md-end gap-2">
                            <span class="badge bg-{{ $menu->is_available ? 'success' : 'danger' }} fs-6">
                                {{ $menu->is_available ? 'Tersedia' : 'Tidak Tersedia' }}
                            </span>
                            
                            @if($menu->is_recommended)
                                <span class="badge bg-warning">
                                    <i class="fas fa-star me-1"></i>Rekomendasi
                                </span>
                            @endif
                            

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Menu Details -->
<div class="row mb-4">
    <div class="col-lg-8">
        <!-- Menu Image -->
        <div class="card mb-4">
            @if($menu->image)
                <img src="{{ asset('storage/' . $menu->image) }}" 
                     class="card-img-top" alt="{{ $menu->name }}" 
                     style="height: 400px; object-fit: cover;">
            @else
                <div class="card-img-top bg-light d-flex align-items-center justify-content-center" 
                     style="height: 400px;">
                    <i class="fas fa-utensils fa-5x text-muted"></i>
                </div>
            @endif
        </div>
        
        <!-- Reviews Section -->
        <div class="card">
            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-star me-2 text-warning"></i>
                    Review & Rating
                </h5>
                <span class="badge bg-primary">{{ $menu->reviews->count() }} Review</span>
            </div>
            <div class="card-body">
                @if($menu->reviews->count() > 0)
                    @foreach($menu->reviews as $review)
                    <div class="review-item border-bottom pb-3 mb-3">
                        <div class="d-flex align-items-center mb-2">
                            <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-3" 
                                 style="width: 40px; height: 40px;">
                                <i class="fas fa-user text-white"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-0">{{ $review->user->name }}</h6>
                                <small class="text-muted">{{ $review->created_at->format('d/m/Y H:i') }}</small>
                            </div>
                            <div class="text-warning">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= $review->rating)
                                        <i class="fas fa-star"></i>
                                    @else
                                        <i class="far fa-star"></i>
                                    @endif
                                @endfor
                            </div>
                        </div>
                        @if($review->comment)
                            <p class="mb-0">{{ $review->comment }}</p>
                        @endif
                    </div>
                    @endforeach
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-star fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Belum ada review untuk menu ini</p>
                        @auth
                            @if(Auth::user()->isPembeli())
                                <button type="button" class="btn btn-warning" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#reviewModal">
                                    <i class="fas fa-star me-2"></i>Jadi yang Pertama Review
                                </button>
                            @endif
                        @endauth
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <!-- Menu Info -->
        <div class="card mb-4">
            <div class="card-header bg-light">
                <h6 class="mb-0">
                    <i class="fas fa-info-circle me-2 text-success"></i>
                    Informasi Menu
                </h6>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-12">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-tag text-success me-2"></i>
                            <div>
                                <small class="text-muted d-block">Harga</small>
                                <strong class="text-success fs-5">Rp {{ number_format($menu->price, 0, ',', '.') }}</strong>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-12">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-check-circle text-success me-2"></i>
                            <div>
                                <small class="text-muted d-block">Status</small>
                                <span class="badge bg-{{ $menu->is_available ? 'success' : 'danger' }}">
                                    {{ $menu->is_available ? 'Tersedia' : 'Tidak Tersedia' }}
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    @if($menu->is_recommended)
                    <div class="col-12">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-star text-warning me-2"></i>
                            <div>
                                <small class="text-muted d-block">Status</small>
                                <span class="badge bg-warning">
                                    <i class="fas fa-star me-1"></i>Rekomendasi
                                </span>
                            </div>
                        </div>
                    </div>
                    @endif
                    
                    <div class="col-12">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-calendar text-success me-2"></i>
                            <div>
                                <small class="text-muted d-block">Ditambahkan</small>
                                <strong>{{ $menu->created_at->format('d/m/Y') }}</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Outlet Info -->
        <div class="card mb-4">
            <div class="card-header bg-light">
                <h6 class="mb-0">
                    <i class="fas fa-store me-2 text-primary"></i>
                    Informasi Outlet
                </h6>
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    @if($menu->outlet->image)
                        <img src="{{ asset('storage/' . $menu->outlet->image) }}" 
                             alt="{{ $menu->outlet->name }}" 
                             class="rounded me-3" 
                             style="width: 50px; height: 50px; object-fit: cover;">
                    @else
                        <div class="bg-light rounded me-3 d-flex align-items-center justify-content-center" 
                             style="width: 50px; height: 50px;">
                            <i class="fas fa-store text-muted"></i>
                        </div>
                    @endif
                    <div>
                        <h6 class="mb-1">{{ $menu->outlet->name }}</h6>
                        <small class="text-muted">{{ Str::limit($menu->outlet->address, 30) }}</small>
                    </div>
                </div>
                
                <div class="d-flex align-items-center mb-2">
                    <i class="fas fa-phone text-primary me-2"></i>
                    <small class="text-muted">{{ $menu->outlet->phone }}</small>
                </div>
                
                <div class="d-flex align-items-center mb-3">
                    <i class="fas fa-clock text-primary me-2"></i>
                    <small class="text-muted">
                        @if($menu->outlet->open_time && $menu->outlet->close_time)
                            {{ $menu->outlet->open_time->format('H:i') }} - {{ $menu->outlet->close_time->format('H:i') }}
                        @else
                            Jam operasional tidak ditentukan
                        @endif
                    </small>
                </div>
                
                <a href="{{ route('outlets.show', $menu->outlet) }}" class="btn btn-outline-primary btn-sm w-100">
                    <i class="fas fa-eye me-1"></i>Lihat Outlet
                </a>
            </div>
        </div>
        
        <!-- Related Menus -->
        @if($related_menus->count() > 0)
        <div class="card">
            <div class="card-header bg-light">
                <h6 class="mb-0">
                    <i class="fas fa-utensils me-2 text-success"></i>
                    Menu Serupa
                </h6>
            </div>
            <div class="card-body">
                @foreach($related_menus as $related)
                <div class="d-flex align-items-center mb-3">
                    @if($related->image)
                        <img src="{{ asset('storage/' . $related->image) }}" 
                             alt="{{ $related->name }}" 
                             class="rounded me-2" 
                             style="width: 40px; height: 40px; object-fit: cover;">
                    @else
                        <div class="bg-light rounded me-2 d-flex align-items-center justify-content-center" 
                             style="width: 40px; height: 40px;">
                            <i class="fas fa-utensils text-muted"></i>
                        </div>
                    @endif
                    <div class="flex-grow-1">
                        <h6 class="mb-0">{{ $related->name }}</h6>
                        <small class="text-muted">Rp {{ number_format($related->price, 0, ',', '.') }}</small>
                    </div>
                    <a href="{{ route('menus.show', $related) }}" class="btn btn-sm btn-outline-success">
                        <i class="fas fa-eye"></i>
                    </a>
                </div>
                @endforeach
            </div>
        </div>
        @endif
        
        <!-- Reviews Section -->
        @include('partials.reviews-section', [
            'reviewable' => $menu,
            'reviewableType' => 'App\Models\Menu'
        ])
    </div>
</div>


@endsection

@push('styles')
<style>
.bg-gradient-success {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
}



.card {
    border: none;
    border-radius: 15px;
}

.card-header {
    border-bottom: 1px solid #e9ecef;
    background-color: #f8f9fa !important;
    border-radius: 15px 15px 0 0 !important;
}

.review-item:last-child {
    border-bottom: none !important;
}

.badge {
    font-size: 0.75rem;
}

.btn {
    border-radius: 8px;
}

.breadcrumb-item + .breadcrumb-item::before {
    color: rgba(255, 255, 255, 0.5);
}
</style>
@endpush


