@extends('layouts.app')

@section('title', $outlet->name . ' - Geolokasi UMKM Kuliner')

@section('content')
<!-- Outlet Header -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card bg-gradient-primary text-white">
            <div class="card-body p-4">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-2">
                                <li class="breadcrumb-item">
                                    <a href="{{ route('outlets.index') }}" class="text-white text-decoration-none">
                                        <i class="fas fa-store me-1"></i>Outlet
                                    </a>
                                </li>
                                <li class="breadcrumb-item active text-white" aria-current="page">{{ $outlet->name }}</li>
                            </ol>
                        </nav>
                        
                        <h2 class="mb-2">{{ $outlet->name }}</h2>
                        <p class="mb-2">{{ $outlet->description }}</p>
                        
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
                            <span class="fw-bold me-2">{{ number_format($outlet->average_rating, 1) }}</span>
                            <span class="text-white-50">({{ $outlet->total_ratings }} rating)</span>
                        </div>
                        
                        <div class="row g-3">
                            <div class="col-md-6">
                                <small class="text-white-50">
                                    <i class="fas fa-map-marker-alt me-2"></i>
                                    {{ $outlet->address }}
                                </small>
                            </div>
                            <div class="col-md-6">
                                <small class="text-white-50">
                                    <i class="fas fa-phone me-2"></i>
                                    {{ $outlet->phone }}
                                </small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4 text-md-end">
                        <div class="d-flex flex-column align-items-md-end gap-2">
                            <span class="badge bg-{{ $outlet->is_open ? 'success' : 'danger' }} fs-6">
                                {{ $outlet->is_open ? 'Buka' : 'Tutup' }}
                            </span>
                            
                            @if($outlet->is_verified)
                                <span class="badge bg-primary">
                                    <i class="fas fa-check-circle me-1"></i>Terverifikasi
                                </span>
                            @endif
                            

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Outlet Details -->
<div class="row mb-4">
    <div class="col-lg-8">
        <!-- Outlet Image -->
        <div class="card mb-4">
            @if($outlet->image)
                <img src="{{ asset('storage/' . $outlet->image) }}" 
                     class="card-img-top" alt="{{ $outlet->name }}" 
                     style="height: 400px; object-fit: cover;">
            @else
                <div class="card-img-top bg-light d-flex align-items-center justify-content-center" 
                     style="height: 400px;">
                    <i class="fas fa-store fa-5x text-muted"></i>
                </div>
            @endif
        </div>
        
        <!-- Menu Section -->
        <div class="card">
            <div class="card-header bg-light">
                <h5 class="mb-0">
                    <i class="fas fa-utensils me-2 text-success"></i>
                    Menu Tersedia
                </h5>
            </div>
            <div class="card-body">
                @if($outlet->menus->count() > 0)
                    <div class="row g-3">
                        @foreach($outlet->menus as $menu)
                        <div class="col-md-6">
                            <div class="card menu-card h-100">
                                @if($menu->image)
                                    <img src="{{ asset('storage/' . $menu->image) }}" 
                                         class="card-img-top" alt="{{ $menu->name }}" 
                                         style="height: 150px; object-fit: cover;">
                                @else
                                    <div class="card-img-top bg-light d-flex align-items-center justify-content-center" 
                                         style="height: 150px;">
                                        <i class="fas fa-utensils fa-2x text-muted"></i>
                                    </div>
                                @endif
                                <div class="card-body">
                                    <h6 class="card-title">{{ $menu->name }}</h6>
                                    <p class="card-text text-muted small">{{ Str::limit($menu->description, 80) }}</p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="text-primary fw-bold">Rp {{ number_format($menu->price, 0, ',', '.') }}</span>
                                        <span class="badge bg-{{ $menu->is_available ? 'success' : 'danger' }}">
                                            {{ $menu->is_available ? 'Tersedia' : 'Tidak Tersedia' }}
                                        </span>
                                    </div>
                                </div>
                                <div class="card-footer bg-transparent">
                                    <a href="{{ route('menus.show', $menu) }}" class="btn btn-outline-success btn-sm w-100">
                                        <i class="fas fa-eye me-1"></i>Lihat Detail
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-utensils fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Belum ada menu yang tersedia</p>
                    </div>
                @endif
            </div>
        </div>
        
        <!-- Reviews Section -->
        @include('partials.reviews-section', [
            'reviewable' => $outlet,
            'reviewableType' => 'App\Models\Outlet'
        ])
    </div>
    
    <div class="col-lg-4">
        <!-- Outlet Info -->
        <div class="card mb-4">
            <div class="card-header bg-light">
                <h6 class="mb-0">
                    <i class="fas fa-info-circle me-2 text-primary"></i>
                    Informasi Outlet
                </h6>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-12">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-clock text-primary me-2"></i>
                            <div>
                                <small class="text-muted d-block">Jam Operasional</small>
                                @if($outlet->open_time && $outlet->close_time)
                                    <strong>{{ $outlet->open_time->format('H:i') }} - {{ $outlet->close_time->format('H:i') }}</strong>
                                @else
                                    <strong class="text-muted">Tidak ditentukan</strong>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-12">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-tags text-primary me-2"></i>
                            <div>
                                <small class="text-muted d-block">Kategori</small>
                                @if($outlet->categories->count() > 0)
                                    @foreach($outlet->categories as $category)
                                        <span class="badge bg-light text-dark me-1">{{ $category->name }}</span>
                                    @endforeach
                                @else
                                    <strong class="text-muted">Tidak ada kategori</strong>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    @if($outlet->latitude && $outlet->longitude)
                    <div class="col-12">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-map-marker-alt text-primary me-2"></i>
                            <div>
                                <small class="text-muted d-block">Koordinat</small>
                                <strong>{{ $outlet->latitude }}, {{ $outlet->longitude }}</strong>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Owner Info -->
        <div class="card mb-4">
            <div class="card-header bg-light">
                <h6 class="mb-0">
                    <i class="fas fa-user me-2 text-success"></i>
                    Informasi Pemilik
                </h6>
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-3" 
                         style="width: 50px; height: 50px;">
                        <i class="fas fa-user fa-lg text-white"></i>
                    </div>
                    <div>
                        <h6 class="mb-1">{{ $outlet->user->name }}</h6>
                        <small class="text-muted">{{ $outlet->user->email }}</small>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Related Outlets -->
        @if($related_outlets->count() > 0)
        <div class="card">
            <div class="card-header bg-light">
                <h6 class="mb-0">
                    <i class="fas fa-store me-2 text-warning"></i>
                    Outlet Serupa
                </h6>
            </div>
            <div class="card-body">
                @foreach($related_outlets as $related)
                <div class="d-flex align-items-center mb-3">
                    @if($related->image)
                        <img src="{{ asset('storage/' . $related->image) }}" 
                             alt="{{ $related->name }}" 
                             class="rounded me-2" 
                             style="width: 40px; height: 40px; object-fit: cover;">
                    @else
                        <div class="bg-light rounded me-2 d-flex align-items-center justify-content-center" 
                             style="width: 40px; height: 40px;">
                            <i class="fas fa-store text-muted"></i>
                        </div>
                    @endif
                    <div class="flex-grow-1">
                        <h6 class="mb-0">{{ $related->name }}</h6>
                        <small class="text-muted">{{ Str::limit($related->address, 30) }}</small>
                    </div>
                    <a href="{{ route('outlets.show', $related) }}" class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-eye"></i>
                    </a>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>


@endsection

@push('styles')
<style>
.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.menu-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    border: none;
    border-radius: 15px;
}

.menu-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.1) !important;
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


