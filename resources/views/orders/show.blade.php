@extends('layouts.app')

@section('title', 'Detail Pesanan - Geolokasi UMKM Kuliner')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-1">
            <i class="fas fa-receipt me-2 text-primary"></i>
            Detail Pesanan #{{ $order->order_number }}
        </h2>
        <p class="text-muted mb-0">Informasi lengkap pesanan Anda</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('orders.index') }}" class="btn btn-outline-primary">
            <i class="fas fa-arrow-left me-2"></i>Kembali ke Pesanan
        </a>
        @if($order->status === 'pending')
        <form action="{{ route('orders.destroy', $order) }}" method="POST" class="d-inline">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger" onclick="return confirm('Batalkan pesanan ini?')">
                <i class="fas fa-times-circle me-2"></i>Batalkan Pesanan
            </button>
        </form>
        @endif
    </div>
</div>

<div class="row g-4">
    <!-- Order Information -->
    <div class="col-lg-8">
        <div class="card shadow-sm">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="fas fa-info-circle me-2"></i>
                    Informasi Pesanan
                </h6>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label text-muted">Status Pesanan</label>
                        <div>
                            <span class="badge bg-{{ $order->status_color }} fs-6 px-3 py-2">
                                {{ ucfirst($order->status) }}
                            </span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted">Tanggal Pesan</label>
                        <p class="mb-0 fw-bold">
                            {{ $order->ordered_at ? $order->ordered_at->format('d/m/Y H:i') : 'N/A' }}
                        </p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted">Outlet</label>
                        <p class="mb-0 fw-bold">{{ $order->outlet->name }}</p>
                        <small class="text-muted">{{ $order->outlet->address }}</small>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted">Total Pembayaran</label>
                        <p class="mb-0 fw-bold text-primary fs-5">
                            Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                        </p>
                    </div>
                    @if($order->notes)
                    <div class="col-12">
                        <label class="form-label text-muted">Catatan Pesanan</label>
                        <p class="mb-0">{{ $order->notes }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Order Items -->
        <div class="card shadow-sm mt-4">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="fas fa-utensils me-2"></i>
                    Item Pesanan
                </h6>
            </div>
            <div class="card-body p-0">
                @foreach($order->orderItems as $item)
                <div class="d-flex align-items-center p-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                    <div class="flex-shrink-0 me-3">
                        @if($item->menu->image)
                            <img src="{{ asset('storage/' . $item->menu->image) }}" 
                                 alt="{{ $item->menu->name }}" 
                                 class="rounded" 
                                 style="width: 80px; height: 80px; object-fit: cover;">
                        @else
                            <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                                 style="width: 80px; height: 80px;">
                                <i class="fas fa-utensils fa-2x text-muted"></i>
                            </div>
                        @endif
                    </div>
                    <div class="flex-grow-1">
                        <h6 class="mb-1">{{ $item->menu->name }}</h6>
                        <p class="text-muted mb-1">{{ $item->menu->description }}</p>
                        @if($item->notes)
                            <small class="text-muted">
                                <i class="fas fa-sticky-note me-1"></i>
                                {{ $item->notes }}
                            </small>
                        @endif
                    </div>
                    <div class="text-end">
                        <div class="mb-1">
                            <span class="text-muted">{{ $item->quantity }}x</span>
                            <span class="fw-bold">Rp {{ number_format($item->price, 0, ',', '.') }}</span>
                        </div>
                        <div class="fw-bold text-primary fs-6">
                            Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Order Timeline & Actions -->
    <div class="col-lg-4">
        <!-- Order Timeline -->
        <div class="card shadow-sm">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="fas fa-clock me-2"></i>
                    Timeline Pesanan
                </h6>
            </div>
            <div class="card-body">
                <div class="timeline">
                    <div class="timeline-item {{ in_array($order->status, ['pending', 'confirmed', 'preparing', 'ready', 'delivered']) ? 'active' : '' }}">
                        <div class="timeline-marker bg-primary">
                            <i class="fas fa-check text-white"></i>
                        </div>
                        <div class="timeline-content">
                            <h6 class="mb-1">Pesanan Dibuat</h6>
                            <small class="text-muted">
                                {{ $order->created_at ? $order->created_at->format('d/m/Y H:i') : 'N/A' }}
                            </small>
                        </div>
                    </div>
                    
                    <div class="timeline-item {{ in_array($order->status, ['confirmed', 'preparing', 'ready', 'delivered']) ? 'active' : '' }}">
                        <div class="timeline-marker {{ in_array($order->status, ['confirmed', 'preparing', 'ready', 'delivered']) ? 'bg-success' : 'bg-light' }}">
                            <i class="fas fa-check text-white"></i>
                        </div>
                        <div class="timeline-content">
                            <h6 class="mb-1">Pesanan Dikonfirmasi</h6>
                            <small class="text-muted">
                                @if($order->status === 'pending')
                                    Menunggu konfirmasi
                                @else
                                    {{ $order->updated_at ? $order->updated_at->format('d/m/Y H:i') : 'N/A' }}
                                @endif
                            </small>
                        </div>
                    </div>
                    
                    <div class="timeline-item {{ in_array($order->status, ['ready', 'delivered']) ? 'active' : '' }}">
                        <div class="timeline-marker {{ in_array($order->status, ['ready', 'delivered']) ? 'bg-success' : 'bg-light' }}">
                            <i class="fas fa-utensils text-white"></i>
                        </div>
                        <div class="timeline-content">
                            <h6 class="mb-1">Siap Diambil</h6>
                            <small class="text-muted">
                                @if(in_array($order->status, ['pending', 'confirmed']))
                                    Sedang disiapkan
                                @else
                                    {{ $order->updated_at ? $order->updated_at->format('d/m/Y H:i') : 'N/A' }}
                                @endif
                            </small>
                        </div>
                    </div>
                    
                    <div class="timeline-item {{ $order->status === 'delivered' ? 'active' : '' }}">
                        <div class="timeline-marker {{ $order->status === 'delivered' ? 'bg-success' : 'bg-light' }}">
                            <i class="fas fa-check-double text-white"></i>
                        </div>
                        <div class="timeline-content">
                            <h6 class="mb-1">Selesai</h6>
                            <small class="text-muted">
                                @if($order->status !== 'delivered')
                                    Menunggu penyelesaian
                                @else
                                    {{ $order->updated_at ? $order->updated_at->format('d/m/Y H:i') : 'N/A' }}
                                @endif
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        @if($order->status === 'pending')
        <div class="card shadow-sm mt-4">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="fas fa-tools me-2"></i>
                    Aksi Cepat
                </h6>
            </div>
            <div class="card-body">
                <form action="{{ route('orders.destroy', $order) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger w-100" 
                            onclick="return confirm('Apakah Anda yakin ingin membatalkan pesanan ini?')">
                        <i class="fas fa-times-circle me-2"></i>
                        Batalkan Pesanan
                    </button>
                </form>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

@push('styles')
<style>
.card {
    border: none;
    border-radius: 15px;
}

.card-header {
    background-color: #f8f9fa;
    border-bottom: 1px solid #e9ecef;
    border-radius: 15px 15px 0 0 !important;
}

.btn {
    border-radius: 8px;
}

.badge {
    font-size: 0.75rem;
}

.timeline {
    position: relative;
    padding-left: 20px;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 10px;
    top: 0;
    bottom: 0;
    width: 2px;
    background-color: #e9ecef;
}

.timeline-item {
    position: relative;
    margin-bottom: 20px;
    opacity: 0.5;
}

.timeline-item.active {
    opacity: 1;
}

.timeline-marker {
    position: absolute;
    left: -20px;
    top: 0;
    width: 20px;
    height: 20px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.75rem;
}

.timeline-content {
    margin-left: 10px;
}

.timeline-content h6 {
    margin-bottom: 5px;
    font-size: 0.875rem;
    font-weight: 600;
}

.timeline-content small {
    font-size: 0.75rem;
}
</style>
@endpush
