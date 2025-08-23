@extends('layouts.app')

@section('title', 'Dashboard Pembeli - Geolokasi UMKM Kuliner')

@section('content')
<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-1">
            <i class="fas fa-tachometer-alt me-2 text-warning"></i>
            Dashboard Pembeli
        </h2>
        <p class="text-muted mb-0">Selamat datang, {{ Auth::user()->name }}!</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('search.outlets') }}" class="btn btn-primary">
            <i class="fas fa-search me-2"></i>Cari Outlet
        </a>
        <a href="{{ route('menus.index') }}" class="btn btn-outline-success">
            <i class="fas fa-utensils me-2"></i>Lihat Menu
        </a>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row g-4 mb-5">
    <div class="col-xl-4 col-md-6">
        <div class="card bg-primary text-white h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-1">{{ number_format($stats['total_orders']) }}</h4>
                        <p class="mb-0">Total Pesanan</p>
                    </div>
                    <div class="stat-icon">
                        <i class="fas fa-shopping-cart fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-4 col-md-6">
        <div class="card bg-success text-white h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-1">{{ number_format($stats['total_reviews']) }}</h4>
                        <p class="mb-0">Total Review</p>
                    </div>
                    <div class="stat-icon">
                        <i class="fas fa-star fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-4 col-md-6">
        <div class="card bg-warning text-white h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-1">{{ number_format($stats['total_ratings']) }}</h4>
                        <p class="mb-0">Total Rating</p>
                    </div>
                    <div class="stat-icon">
                        <i class="fas fa-thumbs-up fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Orders -->
<div class="row mb-5">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-shopping-cart me-2 text-warning"></i>
                    Pesanan Terbaru
                </h5>
                <a href="{{ route('orders.index') }}" class="btn btn-sm btn-outline-primary">
                    Lihat Semua
                </a>
            </div>
            <div class="card-body">
                @if($recent_orders->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Order ID</th>
                                    <th>Outlet</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>Tanggal</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recent_orders as $order)
                                <tr>
                                    <td>
                                        <span class="fw-bold">{{ $order->order_number }}</span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($order->outlet->image)
                                                <img src="{{ asset('storage/' . $order->outlet->image) }}" 
                                                     alt="{{ $order->outlet->name }}" 
                                                     class="rounded me-2" 
                                                     style="width: 40px; height: 40px; object-fit: cover;">
                                            @else
                                                <div class="bg-light rounded me-2 d-flex align-items-center justify-content-center" 
                                                     style="width: 40px; height: 40px;">
                                                    <i class="fas fa-store text-muted"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <h6 class="mb-0">{{ $order->outlet->name }}</h6>
                                                <small class="text-muted">{{ Str::limit($order->outlet->address, 30) }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                                    <td>
                                        @php
                                            $statusColors = [
                                                'pending' => 'warning',
                                                'confirmed' => 'info',
                                                'preparing' => 'primary',
                                                'ready' => 'success',
                                                'delivered' => 'success',
                                                'cancelled' => 'danger'
                                            ];
                                            $statusLabels = [
                                                'pending' => 'Menunggu',
                                                'confirmed' => 'Dikonfirmasi',
                                                'preparing' => 'Disiapkan',
                                                'ready' => 'Siap',
                                                'delivered' => 'Dikirim',
                                                'cancelled' => 'Dibatalkan'
                                            ];
                                        @endphp
                                        <span class="badge bg-{{ $statusColors[$order->status] }}">
                                            {{ $statusLabels[$order->status] }}
                                        </span>
                                    </td>
                                    <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                    <td>
                                        <a href="{{ route('orders.show', $order) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Belum ada pesanan</p>
                        <a href="{{ route('search.outlets') }}" class="btn btn-primary">
                            <i class="fas fa-search me-2"></i>Cari Outlet
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Nearby Outlets -->
<div class="row">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-map-marker-alt me-2 text-danger"></i>
                    Outlet Terdekat
                </h5>
                <a href="{{ route('search.outlets') }}" class="btn btn-sm btn-outline-primary">
                    Lihat Semua
                </a>
            </div>
            <div class="card-body">
                @if($nearby_outlets->count() > 0)
                    <div class="row g-4">
                        @foreach($nearby_outlets as $outlet)
                        <div class="col-md-6 col-lg-4">
                            <div class="card outlet-card h-100">
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
                                    <h6 class="card-title">{{ $outlet->name }}</h6>
                                    <p class="card-text text-muted small">{{ Str::limit($outlet->description, 100) }}</p>
                                    
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
                                    
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                        <small class="text-muted">
                                            <i class="fas fa-map-marker-alt me-1"></i>
                                            {{ Str::limit($outlet->address, 30) }}
                                        </small>
                                        <span class="badge bg-{{ $outlet->is_open ? 'success' : 'danger' }}">
                                            {{ $outlet->is_open ? 'Buka' : 'Tutup' }}
                                        </span>
                                    </div>
                                    
                                    @if(isset($outlet->distance))
                                        <small class="text-info">
                                            <i class="fas fa-location-arrow me-1"></i>
                                            {{ number_format($outlet->distance, 1) }} km
                                        </small>
                                    @endif
                                </div>
                                <div class="card-footer bg-transparent">
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('outlets.show', $outlet) }}" class="btn btn-sm btn-outline-primary flex-fill">
                                            <i class="fas fa-eye me-1"></i>Lihat
                                        </a>
                                        <a href="{{ route('search.outlets', ['outlet' => $outlet->id]) }}" class="btn btn-sm btn-outline-success flex-fill">
                                            <i class="fas fa-utensils me-1"></i>Menu
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-map-marker-alt fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Tidak ada outlet terdekat</p>
                        <a href="{{ route('search.outlets') }}" class="btn btn-primary">
                            <i class="fas fa-search me-2"></i>Cari Outlet
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card border-0 bg-light">
            <div class="card-body text-center">
                <h6 class="text-muted mb-3">
                    <i class="fas fa-bolt me-2 text-warning"></i>
                    Aksi Cepat
                </h6>
                <div class="row g-3">
                    <div class="col-md-3">
                        <a href="{{ route('search.outlets') }}" class="btn btn-outline-primary w-100">
                            <i class="fas fa-search me-2"></i>Cari Outlet
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="{{ route('menus.index') }}" class="btn btn-outline-success w-100">
                            <i class="fas fa-utensils me-2"></i>Lihat Menu
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="{{ route('orders.create') }}" class="btn btn-outline-warning w-100">
                            <i class="fas fa-shopping-cart me-2"></i>Buat Pesanan
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="{{ route('orders.index') }}" class="btn btn-outline-info w-100">
                            <i class="fas fa-list me-2"></i>Riwayat Pesanan
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.stat-icon {
    opacity: 0.8;
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

.outlet-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.outlet-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.1) !important;
}

.table th {
    border-top: none;
    font-weight: 600;
    color: #495057;
}

.table td {
    vertical-align: middle;
}

.badge {
    font-size: 0.75rem;
}

.btn-sm {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
}

.quick-action-btn {
    transition: transform 0.3s ease;
}

.quick-action-btn:hover {
    transform: translateY(-2px);
}
</style>
@endpush
