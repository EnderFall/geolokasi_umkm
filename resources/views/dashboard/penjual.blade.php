@extends('layouts.app')

@section('title', 'Dashboard Penjual - Geolokasi UMKM Kuliner')

@section('content')
<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-1">
            <i class="fas fa-tachometer-alt me-2 text-success"></i>
            Dashboard Penjual
        </h2>
        <p class="text-muted mb-0">Selamat datang, {{ Auth::user()->name }}!</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('menus.create') }}" class="btn btn-success">
            <i class="fas fa-plus me-2"></i>Tambah Menu
        </a>
        <a href="{{ route('outlets.edit', $outlet) }}" class="btn btn-outline-primary">
            <i class="fas fa-edit me-2"></i>Edit Outlet
        </a>
    </div>
</div>

<!-- Outlet Info Card -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card bg-gradient-primary text-white">
            <div class="card-body p-4">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h4 class="mb-2">{{ $outlet->name }}</h4>
                        <p class="mb-2">
                            <i class="fas fa-map-marker-alt me-2"></i>
                            {{ $outlet->address }}
                        </p>
                        <p class="mb-0">
                            <i class="fas fa-phone me-2"></i>
                            {{ $outlet->phone }}
                        </p>
                    </div>
                    <div class="col-md-4 text-md-end">
                        <div class="d-flex align-items-center justify-content-md-end mb-2">
                            <div class="text-warning me-2">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= $outlet->average_rating)
                                        <i class="fas fa-star"></i>
                                    @else
                                        <i class="far fa-star"></i>
                                    @endif
                                @endfor
                            </div>
                            <span class="fw-bold">{{ number_format($outlet->average_rating, 1) }}</span>
                        </div>
                        <span class="badge bg-{{ $outlet->is_open ? 'success' : 'danger' }} fs-6">
                            {{ $outlet->is_open ? 'Buka' : 'Tutup' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row g-4 mb-5">
    <div class="col-xl-3 col-md-6">
        <div class="card bg-primary text-white h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-1">{{ number_format($stats['total_menus']) }}</h4>
                        <p class="mb-0">Total Menu</p>
                    </div>
                    <div class="stat-icon">
                        <i class="fas fa-utensils fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="card bg-success text-white h-100">
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
    
    <div class="col-xl-3 col-md-6">
        <div class="card bg-warning text-white h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-1">Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}</h4>
                        <p class="mb-0">Total Pendapatan</p>
                    </div>
                    <div class="stat-icon">
                        <i class="fas fa-money-bill-wave fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="card bg-info text-white h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-1">{{ number_format($stats['average_rating'], 1) }}</h4>
                        <p class="mb-0">Rating Rata-rata</p>
                    </div>
                    <div class="stat-icon">
                        <i class="fas fa-star fa-2x opacity-75"></i>
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
                <a href="{{ route('outlet.orders') }}" class="btn btn-sm btn-outline-primary">
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
                                    <th>Pembeli</th>
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
                                    <td>{{ $order->user->name }}</td>
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
                                        <a href="#" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if($order->status === 'pending')
                                            <form action="{{ route('orders.update-status', $order) }}" 
                                                  method="POST" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="confirmed">
                                                <button type="submit" class="btn btn-sm btn-success">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </form>
                                        @endif
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
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Popular Menus -->
<div class="row">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-utensils me-2 text-success"></i>
                    Menu Populer
                </h5>
                <a href="{{ route('menus.index') }}" class="btn btn-sm btn-outline-success">
                    Lihat Semua
                </a>
            </div>
            <div class="card-body">
                @if($popular_menus->count() > 0)
                    <div class="row g-3">
                        @foreach($popular_menus as $menu)
                        <div class="col-md-6 col-lg-4">
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
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('menus.edit', $menu) }}" class="btn btn-sm btn-outline-primary flex-fill">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('menus.toggle-availability', $menu) }}" method="POST" class="flex-fill">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-outline-{{ $menu->is_available ? 'warning' : 'success' }} w-100">
                                                <i class="fas fa-{{ $menu->is_available ? 'times' : 'check' }}"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-utensils fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Belum ada menu</p>
                        <a href="{{ route('menus.create') }}" class="btn btn-success">
                            <i class="fas fa-plus me-2"></i>Tambah Menu Pertama
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

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

.menu-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.menu-card:hover {
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
</style>
@endpush
