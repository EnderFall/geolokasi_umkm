@extends('layouts.app')

@section('title', 'Manajemen Outlet - Geolokasi UMKM Kuliner')

@section('content')
<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-1">
            <i class="fas fa-store me-2 text-primary"></i>
            Manajemen Outlet
        </h2>
        <p class="text-muted mb-0">Kelola semua outlet dalam sistem</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('dashboard') }}" class="btn btn-outline-primary">
            <i class="fas fa-arrow-left me-2"></i>Kembali ke Dashboard
        </a>
        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#createOutletModal">
            <i class="fas fa-plus me-2"></i>Tambah Outlet
        </button>
    </div>
</div>

<!-- Outlet Statistics -->
<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center">
                <div class="mb-2">
                    <i class="fas fa-store fa-2x text-primary"></i>
                </div>
                <h4 class="mb-1">{{ $totalOutlets }}</h4>
                <p class="text-muted mb-0">Total Outlet</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center">
                <div class="mb-2">
                    <i class="fas fa-check-circle fa-2x text-success"></i>
                </div>
                <h4 class="mb-1">{{ $verifiedOutlets }}</h4>
                <p class="text-muted mb-0">Terverifikasi</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center">
                <div class="mb-2">
                    <i class="fas fa-clock fa-2x text-warning"></i>
                </div>
                <h4 class="mb-1">{{ $pendingOutlets }}</h4>
                <p class="text-muted mb-0">Menunggu Verifikasi</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center">
                <div class="mb-2">
                    <i class="fas fa-door-open fa-2x text-info"></i>
                </div>
                <h4 class="mb-1">{{ $openOutlets }}</h4>
                <p class="text-muted mb-0">Sedang Buka</p>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="card shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.outlets') }}" class="row g-3">
            <div class="col-md-3">
                <label for="verification" class="form-label">Status Verifikasi</label>
                <select name="verification" id="verification" class="form-select">
                    <option value="">Semua Status</option>
                    <option value="1" {{ request('verification') === '1' ? 'selected' : '' }}>Terverifikasi</option>
                    <option value="0" {{ request('verification') === '0' ? 'selected' : '' }}>Belum Verifikasi</option>
                </select>
            </div>
            <div class="col-md-3">
                <label for="status" class="form-label">Status Outlet</label>
                <select name="status" id="status" class="form-select">
                    <option value="">Semua Status</option>
                    <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Buka</option>
                    <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Tutup</option>
                </select>
            </div>
            <div class="col-md-3">
                <label for="search" class="form-label">Cari</label>
                <input type="text" name="search" id="search" class="form-control" 
                       placeholder="Nama outlet atau pemilik" value="{{ request('search') }}">
            </div>
            <div class="col-md-3 d-flex align-items-end">
                <div class="d-flex gap-2 w-100">
                    <button type="submit" class="btn btn-primary flex-fill">
                        <i class="fas fa-search me-2"></i>Filter
                    </button>
                    <a href="{{ route('admin.outlets') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-times me-2"></i>Reset
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Outlets List -->
<div class="card shadow-sm">
    <div class="card-header">
        <h6 class="mb-0">
            <i class="fas fa-list me-2"></i>
            Daftar Outlet ({{ $outlets->total() }} outlet)
        </h6>
    </div>
    <div class="card-body p-0">
        @if($outlets->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Outlet</th>
                            <th>Pemilik</th>
                            <th>Kontak</th>
                            <th>Status</th>
                            <th>Verifikasi</th>
                            <th>Bergabung</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($outlets as $outlet)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0 me-3">
                                        @if($outlet->image)
                                            <img src="{{ asset('storage/' . $outlet->image) }}" 
                                                 alt="{{ $outlet->name }}" 
                                                 class="rounded" 
                                                 style="width: 50px; height: 50px; object-fit: cover;">
                                        @else
                                            <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                                                 style="width: 50px; height: 50px;">
                                                <i class="fas fa-store text-muted"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <h6 class="mb-1">{{ $outlet->name }}</h6>
                                        <small class="text-muted">{{ Str::limit($outlet->description, 40) }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div>
                                    <strong>{{ $outlet->user->name }}</strong>
                                    <br><small class="text-muted">{{ $outlet->user->email }}</small>
                                </div>
                            </td>
                            <td>
                                <div>
                                    <strong>{{ $outlet->phone }}</strong>
                                    @if($outlet->address)
                                        <br><small class="text-muted">{{ Str::limit($outlet->address, 30) }}</small>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-{{ $outlet->is_open ? 'success' : 'danger' }} fs-6 px-3 py-2">
                                    {{ $outlet->is_open ? 'Buka' : 'Tutup' }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-{{ $outlet->is_verified ? 'primary' : 'warning' }} fs-6 px-3 py-2">
                                    {{ $outlet->is_verified ? 'Terverifikasi' : 'Belum Verifikasi' }}
                                </span>
                            </td>
                            <td>
                                <div>
                                    <strong>{{ $outlet->created_at->format('d/m/Y') }}</strong>
                                    <br><small class="text-muted">{{ $outlet->created_at->format('H:i') }}</small>
                                </div>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('outlets.show', $outlet) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    
                                    <a href="{{ route('outlets.edit', $outlet) }}" class="btn btn-sm btn-outline-info">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    
                                    @if(!$outlet->is_verified)
                                        <form action="{{ route('admin.outlets.verify', $outlet) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-success" 
                                                    onclick="return confirm('Verifikasi outlet ini?')">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                    @else
                                        <form action="{{ route('admin.outlets.unverify', $outlet) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-warning" 
                                                    onclick="return confirm('Batalkan verifikasi outlet ini?')">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </form>
                                    @endif
                                    
                                    <form action="{{ route('admin.outlets.destroy', $outlet) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" 
                                                onclick="return confirm('Hapus outlet ini? Tindakan ini tidak dapat dibatalkan.')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="d-flex justify-content-center p-3">
                {{ $outlets->appends(request()->query())->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-store fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">Belum ada outlet</h5>
                <p class="text-muted">Sistem belum memiliki outlet apapun.</p>
            </div>
        @endif
    </div>
</div>

<!-- Create Outlet Modal -->
<div class="modal fade" id="createOutletModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('admin.outlets.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Outlet Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="name" class="form-label">Nama Outlet <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label for="user_id" class="form-label">Pemilik <span class="text-danger">*</span></label>
                            <select name="user_id" id="user_id" class="form-select" required>
                                <option value="">Pilih Pemilik</option>
                                @foreach($penjualUsers as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="phone" class="form-label">Telepon <span class="text-danger">*</span></label>
                            <input type="text" name="phone" id="phone" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label for="open_time" class="form-label">Jam Buka</label>
                            <input type="time" name="open_time" id="open_time" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label for="close_time" class="form-label">Jam Tutup</label>
                            <input type="time" name="close_time" id="close_time" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label for="is_open" class="form-label">Status</label>
                            <select name="is_open" id="is_open" class="form-select">
                                <option value="1">Buka</option>
                                <option value="0">Tutup</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label for="description" class="form-label">Deskripsi</label>
                            <textarea name="description" id="description" class="form-control" rows="3"></textarea>
                        </div>
                        <div class="col-12">
                            <label for="address" class="form-label">Alamat <span class="text-danger">*</span></label>
                            <textarea name="address" id="address" class="form-control" rows="3" required></textarea>
                        </div>
                        <div class="col-md-6">
                            <label for="latitude" class="form-label">Latitude</label>
                            <input type="number" name="latitude" id="latitude" class="form-control" step="any">
                        </div>
                        <div class="col-md-6">
                            <label for="longitude" class="form-label">Longitude</label>
                            <input type="number" name="longitude" id="longitude" class="form-control" step="any">
                        </div>
                        <div class="col-12">
                            <label for="image" class="form-label">Gambar Outlet</label>
                            <input type="file" name="image" id="image" class="form-control" accept="image/*">
                            <small class="text-muted">Format: JPG, PNG, GIF. Maksimal 2MB.</small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Tambah Outlet</button>
                </div>
            </form>
        </div>
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
