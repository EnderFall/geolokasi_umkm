@extends('layouts.app')

@section('title', 'Manajemen User - Geolokasi UMKM Kuliner')

@section('content')
<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-1">
            <i class="fas fa-users me-2 text-primary"></i>
            Manajemen User
        </h2>
        <p class="text-muted mb-0">Kelola semua user dalam sistem</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('dashboard') }}" class="btn btn-outline-primary">
            <i class="fas fa-arrow-left me-2"></i>Kembali ke Dashboard
        </a>
        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#createUserModal">
            <i class="fas fa-plus me-2"></i>Tambah User
        </button>
    </div>
</div>

<!-- User Statistics -->
<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center">
                <div class="mb-2">
                    <i class="fas fa-users fa-2x text-primary"></i>
                </div>
                <h4 class="mb-1">{{ $totalUsers }}</h4>
                <p class="text-muted mb-0">Total User</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center">
                <div class="mb-2">
                    <i class="fas fa-store fa-2x text-success"></i>
                </div>
                <h4 class="mb-1">{{ $penjualUsers }}</h4>
                <p class="text-muted mb-0">Penjual/Outlet</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center">
                <div class="mb-2">
                    <i class="fas fa-shopping-cart fa-2x text-info"></i>
                </div>
                <h4 class="mb-1">{{ $pembeliUsers }}</h4>
                <p class="text-muted mb-0">Pembeli</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center">
                <div class="mb-2">
                    <i class="fas fa-user-shield fa-2x text-warning"></i>
                </div>
                <h4 class="mb-1">{{ $adminUsers }}</h4>
                <p class="text-muted mb-0">Admin</p>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="card shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.users') }}" class="row g-3">
            <div class="col-md-3">
                <label for="role" class="form-label">Role</label>
                <select name="role" id="role" class="form-select">
                    <option value="">Semua Role</option>
                    <option value="superadmin" {{ request('role') === 'superadmin' ? 'selected' : '' }}>Super Admin</option>
                    <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="penjual" {{ request('role') === 'penjual' ? 'selected' : '' }}>Penjual/Outlet</option>
                    <option value="pembeli" {{ request('role') === 'pembeli' ? 'selected' : '' }}>Pembeli</option>
                </select>
            </div>
            <div class="col-md-3">
                <label for="status" class="form-label">Status</label>
                <select name="status" id="status" class="form-select">
                    <option value="">Semua Status</option>
                    <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Aktif</option>
                    <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Tidak Aktif</option>
                </select>
            </div>
            <div class="col-md-3">
                <label for="search" class="form-label">Cari</label>
                <input type="text" name="search" id="search" class="form-control" 
                       placeholder="Nama, email, atau telepon" value="{{ request('search') }}">
            </div>
            <div class="col-md-3 d-flex align-items-end">
                <div class="d-flex gap-2 w-100">
                    <button type="submit" class="btn btn-primary flex-fill">
                        <i class="fas fa-search me-2"></i>Filter
                    </button>
                    <a href="{{ route('admin.users') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-times me-2"></i>Reset
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Users List -->
<div class="card shadow-sm">
    <div class="card-header">
        <h6 class="mb-0">
            <i class="fas fa-list me-2"></i>
            Daftar User ({{ $users->total() }} user)
        </h6>
    </div>
    <div class="card-body p-0">
        @if($users->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>User</th>
                            <th>Role</th>
                            <th>Kontak</th>
                            <th>Status</th>
                            <th>Bergabung</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0 me-3">
                                        <div class="avatar bg-light rounded-circle d-flex align-items-center justify-content-center" 
                                             style="width: 50px; height: 50px;">
                                            <i class="fas fa-user text-muted"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <h6 class="mb-1">{{ $user->name }}</h6>
                                        <small class="text-muted">{{ $user->email }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-{{ $user->role_color }} fs-6 px-3 py-2">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>
                            <td>
                                <div>
                                    <strong>{{ $user->phone ?? 'N/A' }}</strong>
                                    @if($user->address)
                                        <br><small class="text-muted">{{ Str::limit($user->address, 30) }}</small>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-{{ $user->is_active ? 'success' : 'danger' }} fs-6 px-3 py-2">
                                    {{ $user->is_active ? 'Aktif' : 'Tidak Aktif' }}
                                </span>
                            </td>
                            <td>
                                <div>
                                    <strong>{{ $user->created_at->format('d/m/Y') }}</strong>
                                    <br><small class="text-muted">{{ $user->created_at->format('H:i') }}</small>
                                </div>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-sm btn-outline-primary" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#editUserModal{{ $user->id }}">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    
                                    @if($user->id !== auth()->id())
                                        <form action="{{ route('admin.users.toggle-status', $user) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm {{ $user->is_active ? 'btn-warning' : 'btn-success' }}" 
                                                    onclick="return confirm('{{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }} user ini?')">
                                                <i class="fas fa-{{ $user->is_active ? 'ban' : 'check' }}"></i>
                                            </button>
                                        </form>
                                        
                                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" 
                                                    onclick="return confirm('Hapus user ini? Tindakan ini tidak dapat dibatalkan.')">
                                                <i class="fas fa-trash"></i>
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
            
            <!-- Pagination -->
            <div class="d-flex justify-content-center p-3">
                {{ $users->appends(request()->query())->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-users fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">Belum ada user</h5>
                <p class="text-muted">Sistem belum memiliki user apapun.</p>
            </div>
        @endif
    </div>
</div>

<!-- Create User Modal -->
<div class="modal fade" id="createUserModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('admin.users.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah User Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="name" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" id="email" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label for="phone" class="form-label">Telepon</label>
                            <input type="text" name="phone" id="phone" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label for="role" class="form-label">Role <span class="text-danger">*</span></label>
                            <select name="role" id="role" class="form-select" required>
                                <option value="">Pilih Role</option>
                                <option value="admin">Admin</option>
                                <option value="penjual">Penjual/Outlet</option>
                                <option value="pembeli">Pembeli</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label for="address" class="form-label">Alamat</label>
                            <textarea name="address" id="address" class="form-control" rows="3"></textarea>
                        </div>
                        <div class="col-md-6">
                            <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                            <input type="password" name="password" id="password" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label for="password_confirmation" class="form-label">Konfirmasi Password <span class="text-danger">*</span></label>
                            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Tambah User</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit User Modals -->
@foreach($users as $user)
<div class="modal fade" id="editUserModal{{ $user->id }}" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('admin.users.update', $user) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Edit User: {{ $user->name }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="edit_name_{{ $user->id }}" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="edit_name_{{ $user->id }}" class="form-control" 
                                   value="{{ $user->name }}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="edit_email_{{ $user->id }}" class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" id="edit_email_{{ $user->id }}" class="form-control" 
                                   value="{{ $user->email }}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="edit_phone_{{ $user->id }}" class="form-label">Telepon</label>
                            <input type="text" name="phone" id="edit_phone_{{ $user->id }}" class="form-control" 
                                   value="{{ $user->phone }}">
                        </div>
                        <div class="col-md-6">
                            <label for="edit_role_{{ $user->id }}" class="form-label">Role <span class="text-danger">*</span></label>
                            <select name="role" id="edit_role_{{ $user->id }}" class="form-select" required>
                                <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                                <option value="penjual" {{ $user->role === 'penjual' ? 'selected' : '' }}>Penjual/Outlet</option>
                                <option value="pembeli" {{ $user->role === 'pembeli' ? 'selected' : '' }}>Pembeli</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label for="edit_address_{{ $user->id }}" class="form-label">Alamat</label>
                            <textarea name="address" id="edit_address_{{ $user->id }}" class="form-control" rows="3">{{ $user->address }}</textarea>
                        </div>
                        <div class="col-md-6">
                            <label for="edit_password_{{ $user->id }}" class="form-label">Password Baru (kosongkan jika tidak ingin mengubah)</label>
                            <input type="password" name="password" id="edit_password_{{ $user->id }}" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label for="edit_password_confirmation_{{ $user->id }}" class="form-label">Konfirmasi Password Baru</label>
                            <input type="password" name="password_confirmation" id="edit_password_confirmation_{{ $user->id }}" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Update User</button>
                </div>
            </form>
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

.avatar {
    background-color: #e9ecef;
    color: #6c757d;
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Password confirmation validation
    const passwordFields = document.querySelectorAll('input[type="password"]');
    const confirmFields = document.querySelectorAll('input[name="password_confirmation"]');
    
    confirmFields.forEach((confirmField, index) => {
        const passwordField = passwordFields[index];
        
        confirmField.addEventListener('input', function() {
            if (this.value !== passwordField.value) {
                this.setCustomValidity('Password tidak cocok');
            } else {
                this.setCustomValidity('');
            }
        });
        
        passwordField.addEventListener('input', function() {
            if (confirmField.value && this.value !== confirmField.value) {
                confirmField.setCustomValidity('Password tidak cocok');
            } else {
                confirmField.setCustomValidity('');
            }
        });
    });
});
</script>
@endpush
