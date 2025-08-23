@extends('layouts.app')

@section('title', 'Dashboard Admin - Geolokasi UMKM Kuliner')

@section('content')
<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-1">
            <i class="fas fa-tachometer-alt me-2 text-primary"></i>
            Dashboard Admin
        </h2>
        <p class="text-muted mb-0">Selamat datang, {{ Auth::user()->name }}!</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.users') }}" class="btn btn-outline-primary">
            <i class="fas fa-users me-2"></i>Kelola User
        </a>
        <a href="{{ route('admin.outlets') }}" class="btn btn-outline-success">
            <i class="fas fa-store me-2"></i>Kelola Outlet
        </a>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row g-4 mb-5">
    <div class="col-xl-3 col-md-6">
        <div class="card bg-primary text-white h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-1">{{ number_format($stats['total_users']) }}</h4>
                        <p class="mb-0">Total Users</p>
                    </div>
                    <div class="stat-icon">
                        <i class="fas fa-users fa-2x opacity-75"></i>
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
                        <h4 class="mb-1">{{ number_format($stats['total_outlets']) }}</h4>
                        <p class="mb-0">Total Outlets</p>
                    </div>
                    <div class="stat-icon">
                        <i class="fas fa-store fa-2x opacity-75"></i>
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
                        <h4 class="mb-1">{{ number_format($stats['total_orders']) }}</h4>
                        <p class="mb-0">Total Orders</p>
                    </div>
                    <div class="stat-icon">
                        <i class="fas fa-shopping-cart fa-2x opacity-75"></i>
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
                        <h4 class="mb-1">Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}</h4>
                        <p class="mb-0">Total Revenue</p>
                    </div>
                    <div class="stat-icon">
                        <i class="fas fa-money-bill-wave fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Outlets -->
<div class="row mb-5">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header bg-light">
                <h5 class="mb-0">
                    <i class="fas fa-store me-2 text-primary"></i>
                    Outlet Terbaru
                </h5>
            </div>
            <div class="card-body">
                @if($recent_outlets->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Nama Outlet</th>
                                    <th>Pemilik</th>
                                    <th>Alamat</th>
                                    <th>Status</th>
                                    <th>Tanggal Dibuat</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recent_outlets as $outlet)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($outlet->image)
                                                <img src="{{ asset('storage/' . $outlet->image) }}" 
                                                     alt="{{ $outlet->name }}" 
                                                     class="rounded me-2" 
                                                     style="width: 40px; height: 40px; object-fit: cover;">
                                            @else
                                                <div class="bg-light rounded me-2 d-flex align-items-center justify-content-center" 
                                                     style="width: 40px; height: 40px;">
                                                    <i class="fas fa-store text-muted"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <h6 class="mb-0">{{ $outlet->name }}</h6>
                                                <small class="text-muted">{{ $outlet->phone }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $outlet->user->name }}</td>
                                    <td>{{ Str::limit($outlet->address, 30) }}</td>
                                    <td>
                                        @if($outlet->is_verified)
                                            <span class="badge bg-success">Terverifikasi</span>
                                        @else
                                            <span class="badge bg-warning">Pending</span>
                                        @endif
                                    </td>
                                    <td>{{ $outlet->created_at->format('d/m/Y H:i') }}</td>
                                    <td>
                                        <a href="{{ route('outlets.show', $outlet) }}" 
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if(!$outlet->is_verified)
                                            <form action="{{ route('admin.outlets.verify', $outlet) }}" 
                                                  method="POST" class="d-inline">
                                                @csrf
                                                @method('PATCH')
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
                        <i class="fas fa-store fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Belum ada outlet yang terdaftar</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Recent Orders -->
<div class="row">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header bg-light">
                <h5 class="mb-0">
                    <i class="fas fa-shopping-cart me-2 text-warning"></i>
                    Pesanan Terbaru
                </h5>
            </div>
            <div class="card-body">
                @if($recent_orders->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Order ID</th>
                                    <th>Pembeli</th>
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
                                    <td>{{ $order->user->name }}</td>
                                    <td>{{ $order->outlet->name }}</td>
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
