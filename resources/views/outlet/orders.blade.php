@extends('layouts.app')

@section('title', 'Pesanan Outlet - Geolokasi UMKM Kuliner')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-1">
            <i class="fas fa-receipt me-2 text-primary"></i>
            Pesanan Outlet
        </h2>
        <p class="text-muted mb-0">Kelola semua pesanan yang masuk ke outlet Anda</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('dashboard') }}" class="btn btn-outline-primary">
            <i class="fas fa-arrow-left me-2"></i>Kembali ke Dashboard
        </a>
    </div>
</div>

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
                            <th>Pelanggan</th>
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
                                <div>
                                    <strong>{{ $order->user->name }}</strong>
                                    <br><small class="text-muted">{{ $order->user->phone }}</small>
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
                                    <button type="button" class="btn btn-sm btn-outline-primary" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#orderDetailModal{{ $order->id }}">
                                            <i class="fas fa-eye"></i>
                                    </button>
                                    
                                    @if($order->status === 'pending')
                                        <form action="{{ route('orders.confirm', $order) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-success" 
                                                    onclick="return confirm('Konfirmasi pesanan ini?')">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                    @elseif($order->status === 'confirmed')
                                        <form action="{{ route('orders.mark-ready', $order) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-info" 
                                                    onclick="return confirm('Tandai pesanan siap diambil?')">
                                                <i class="fas fa-utensils"></i>
                                            </button>
                                        </form>
                                    @elseif($order->status === 'ready')
                                        <form action="{{ route('orders.mark-delivered', $order) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-success" 
                                                    onclick="return confirm('Tandai pesanan selesai?')">
                                                <i class="fas fa-check-double"></i>
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
                <p class="text-muted">Outlet Anda belum menerima pesanan apapun.</p>
            </div>
        @endif
    </div>
</div>

@foreach($orders as $order)
<div class="modal fade" id="orderDetailModal{{ $order->id }}" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Pesanan #{{ $order->order_number }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label text-muted">Pelanggan</label>
                        <p class="mb-0 fw-bold">{{ $order->user->name }}</p>
                        <small class="text-muted">{{ $order->user->email }} | {{ $order->user->phone }}</small>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted">Status</label>
                        <p class="mb-0">
                            <span class="badge bg-{{ $order->status_color }} fs-6">
                                {{ ucfirst($order->status) }}
                            </span>
                        </p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted">Tanggal Pesan</label>
                        <p class="mb-0">{{ $order->ordered_at ? $order->ordered_at->format('d/m/Y H:i') : 'N/A' }}</p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted">Total Pembayaran</label>
                        <p class="mb-0 fw-bold text-primary">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p>
                    </div>
                    @if($order->notes)
                    <div class="col-12">
                        <label class="form-label text-muted">Catatan Pesanan</label>
                        <p class="mb-0">{{ $order->notes }}</p>
                    </div>
                    @endif
                </div>
                
                <hr>
                
                <h6 class="mb-3">Item Pesanan</h6>
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
                            <small class="text-muted">{{ $item->notes }}</small>
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
                @if($order->status === 'pending')
                    <form action="{{ route('orders.confirm', $order) }}" method="POST" class="d-inline">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-success" 
                                onclick="return confirm('Konfirmasi pesanan ini?')">
                            <i class="fas fa-check me-2"></i>Konfirmasi
                        </button>
                    </form>
                @elseif($order->status === 'confirmed')
                    <form action="{{ route('orders.mark-ready', $order) }}" method="POST" class="d-inline">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-info" 
                                onclick="return confirm('Tandai pesanan siap diambil?')">
                            <i class="fas fa-utensils me-2"></i>Siap Diambil
                        </button>
                    </form>
                @elseif($order->status === 'ready')
                    <form action="{{ route('orders.mark-delivered', $order) }}" method="POST" class="d-inline">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-success" 
                                onclick="return confirm('Tandai pesanan selesai?')">
                            <i class="fas fa-check-double me-2"></i>Selesai
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>

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
                            <small class="text-muted">{{ $item->notes }}</small>
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
</style>
@endpush
