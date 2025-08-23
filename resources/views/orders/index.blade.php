@extends('layouts.app')

@section('title', 'Daftar Pesanan - Geolokasi UMKM Kuliner')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-1">
            <i class="fas fa-receipt me-2 text-primary"></i>
            Daftar Pesanan
        </h2>
        <p class="text-muted mb-0">Kelola semua pesanan Anda</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('outlets.index') }}" class="btn btn-success">
            <i class="fas fa-plus me-2"></i>Buat Pesanan Baru
        </a>
    </div>
</div>

<!-- Order Statistics -->
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-clock fa-2x"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h4 class="mb-1">{{ $stats['pending'] ?? 0 }}</h4>
                        <small>Menunggu Konfirmasi</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-utensils fa-2x"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h4 class="mb-1">{{ $stats['preparing'] ?? 0 }}</h4>
                        <small>Sedang Disiapkan</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-check fa-2x"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h4 class="mb-1">{{ $stats['ready'] ?? 0 }}</h4>
                        <small>Siap Diambil</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-check-double fa-2x"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h4 class="mb-1">{{ $stats['delivered'] ?? 0 }}</h4>
                        <small>Selesai</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="card shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('orders.index') }}" class="row g-3">
            <div class="col-md-3">
                <label for="status" class="form-label">Status</label>
                <select name="status" id="status" class="form-select">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Menunggu Konfirmasi</option>
                    <option value="confirmed" {{ request('status') === 'confirmed' ? 'selected' : '' }}>Dikonfirmasi</option>
                    <option value="preparing" {{ request('status') === 'preparing' ? 'selected' : '' }}>Sedang Disiapkan</option>
                    <option value="ready" {{ request('status') === 'ready' ? 'selected' : '' }}>Siap Diambil</option>
                    <option value="delivered" {{ request('status') === 'delivered' ? 'selected' : '' }}>Selesai</option>
                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                </select>
            </div>
            <div class="col-md-3">
                <label for="outlet" class="form-label">Outlet</label>
                <select name="outlet" id="outlet" class="form-select">
                    <option value="">Semua Outlet</option>
                    @foreach($outlets as $outlet)
                        <option value="{{ $outlet->id }}" {{ request('outlet') == $outlet->id ? 'selected' : '' }}>
                            {{ $outlet->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label for="date_from" class="form-label">Dari Tanggal</label>
                <input type="date" name="date_from" id="date_from" class="form-control" value="{{ request('date_from') }}">
            </div>
            <div class="col-md-3">
                <label for="date_to" class="form-label">Sampai Tanggal</label>
                <input type="date" name="date_to" id="date_to" class="form-control" value="{{ request('date_to') }}">
            </div>
            <div class="col-12">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-search me-2"></i>Filter
                </button>
                <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-times me-2"></i>Reset
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Orders List -->
<div class="card shadow-sm">
    <div class="card-header">
        <h6 class="mb-0">
            <i class="fas fa-list me-2"></i>
            Daftar Pesanan ({{ $orders->total() }} pesanan)
        </h6>
    </div>
    <div class="card-body p-0">
        @if($orders->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>No. Pesanan</th>
                            <th>Outlet</th>
                            <th>Item</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="me-3">
                                        <i class="fas fa-receipt text-primary"></i>
                                    </div>
                                    <div>
                                        <strong>{{ $order->order_number }}</strong>
                                        @if($order->notes)
                                            <br><small class="text-muted">{{ Str::limit($order->notes, 30) }}</small>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="me-2">
                                        @if($order->outlet->image)
                                            <img src="{{ asset('storage/' . $order->outlet->image) }}" 
                                                 alt="{{ $order->outlet->name }}" 
                                                 class="rounded" 
                                                 style="width: 40px; height: 40px; object-fit: cover;">
                                        @else
                                            <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                                                 style="width: 40px; height: 40px;">
                                                <i class="fas fa-store text-muted"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <strong>{{ $order->outlet->name }}</strong>
                                        <br><small class="text-muted">{{ Str::limit($order->outlet->address, 30) }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="me-2">
                                        <span class="badge bg-light text-dark">{{ $order->orderItems->sum('quantity') }} item</span>
                                    </div>
                                    <button type="button" class="btn btn-sm btn-outline-info" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#orderItemsModal{{ $order->id }}">
                                            <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </td>
                            <td>
                                <strong class="text-primary">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</strong>
                            </td>
                            <td>
                                <span class="badge bg-{{ $order->status_color }} fs-6 px-3 py-2">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                            <td>
                                <div>
                                    <strong>{{ $order->ordered_at ? $order->ordered_at->format('d/m/Y') : 'N/A' }}</strong>
                                    <br><small class="text-muted">{{ $order->ordered_at ? $order->ordered_at->format('H:i') : 'N/A' }}</small>
                                </div>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('orders.show', $order) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    
                                    @if($order->status === 'pending')
                                        <form action="{{ route('orders.destroy', $order) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" 
                                                    onclick="return confirm('Batalkan pesanan ini?')">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex justify-content-center p-3">
                {{ $orders->appends(request()->query())->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">Belum ada pesanan</h5>
                <p class="text-muted">Anda belum membuat pesanan apapun.</p>
                <a href="{{ route('outlets.index') }}" class="btn btn-primary">
                    <i class="fas fa-store me-2"></i>Jelajahi Outlet
                </a>
            </div>
        @endif
    </div>
</div>

<!-- Order Items Modals -->
@foreach($orders as $order)
<div class="modal fade" id="orderItemsModal{{ $order->id }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Item Pesanan #{{ $order->order_number }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                @foreach($order->orderItems as $item)
                <div class="d-flex align-items-center py-2 {{ !$loop->last ? 'border-bottom' : '' }}">
                    <div class="flex-shrink-0 me-3">
                        @if($item->menu->image)
                            <img src="{{ asset('storage/' . $item->menu->image) }}" 
                                 alt="{{ $item->menu->name }}" 
                                 class="rounded" 
                                 style="width: 50px; height: 50px; object-fit: cover;">
                        @else
                            <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                                 style="width: 50px; height: 50px;">
                                <i class="fas fa-utensils text-muted"></i>
                            </div>
                        @endif
                    </div>
                    <div class="flex-grow-1">
                        <h6 class="mb-1">{{ $item->menu->name }}</h6>
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
                        <div class="fw-bold text-primary">
                            Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endforeach
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

.table th {
    border-top: none;
    font-weight: 600;
    color: #495057;
}

.table td {
    vertical-align: middle;
}

.btn-group .btn {
    border-radius: 0;
}

.btn-group .btn:first-child {
    border-top-left-radius: 8px;
    border-bottom-left-radius: 8px;
}

.btn-group .btn:last-child {
    border-top-right-radius: 8px;
    border-bottom-right-radius: 8px;
}

.modal-content {
    border-radius: 15px;
    border: none;
}

.modal-header {
    border-bottom: 1px solid #e9ecef;
    border-radius: 15px 15px 0 0;
}

.modal-footer {
    border-top: 1px solid #e9ecef;
    border-radius: 0 0 15px 15px;
}

.bg-primary, .bg-info, .bg-warning, .bg-success {
    border: none;
}

.bg-primary .card-body, .bg-info .card-body, .bg-warning .card-body, .bg-success .card-body {
    padding: 1.5rem;
}
</style>
@endpush
