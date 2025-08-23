@extends('layouts.app')

@section('title', 'Edit Menu - Geolokasi UMKM Kuliner')

@section('content')
<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-1">
            <i class="fas fa-edit me-2 text-success"></i>
            Edit Menu
        </h2>
        <p class="text-muted mb-0">Perbarui informasi menu Anda</p>
    </div>
    <a href="{{ route('menus.show', $menu) }}" class="btn btn-outline-success">
        <i class="fas fa-eye me-2"></i>Lihat Menu
    </a>
</div>

<!-- Edit Form -->
<div class="row">
    <div class="col-lg-8">
        <div class="card shadow-sm">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="fas fa-utensils me-2"></i>
                    Informasi Menu
                </h6>
            </div>
            <div class="card-body">
                <form action="{{ route('menus.update', $menu) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="row g-3">
                        <!-- Menu Name -->
                        <div class="col-12">
                            <label for="name" class="form-label">Nama Menu <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name', $menu->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <!-- Price -->
                        <div class="col-md-6">
                            <label for="price" class="form-label">Harga <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" class="form-control @error('price') is-invalid @enderror" 
                                       id="price" name="price" value="{{ old('price', $menu->price) }}" 
                                       min="0" step="100" required>
                            </div>
                            @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <!-- Outlet Info (Read-only) -->
                        <div class="col-md-6">
                            <label class="form-label">Outlet</label>
                            <div class="form-control-plaintext">
                                <div class="d-flex align-items-center">
                                    @if($menu->outlet->image)
                                        <img src="{{ asset('storage/' . $menu->outlet->image) }}" 
                                             alt="{{ $menu->outlet->name }}" 
                                             class="rounded me-2" 
                                             style="width: 24px; height: 24px; object-fit: cover;">
                                    @else
                                        <div class="bg-light rounded me-2 d-flex align-items-center justify-content-center" 
                                             style="width: 24px; height: 24px;">
                                            <i class="fas fa-store text-muted fa-sm"></i>
                                        </div>
                                    @endif
                                    <strong>{{ $menu->outlet->name }}</strong>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Description -->
                        <div class="col-12">
                            <label for="description" class="form-label">Deskripsi</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="4" 
                                      placeholder="Jelaskan menu Anda, bahan-bahan, dan keunggulan...">{{ old('description', $menu->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <!-- Image -->
                        <div class="col-12">
                            <label for="image" class="form-label">Gambar Menu</label>
                            <input type="file" class="form-control @error('image') is-invalid @enderror" 
                                   id="image" name="image" accept="image/*">
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Format: JPG, PNG, GIF. Maksimal 2MB.</small>
                        </div>
                        
                        <!-- Current Image Preview -->
                        @if($menu->image)
                        <div class="col-12">
                            <label class="form-label">Gambar Saat Ini</label>
                            <div class="d-flex align-items-center">
                                <img src="{{ asset('storage/' . $menu->image) }}" 
                                     alt="{{ $menu->name }}" 
                                     class="rounded me-3" 
                                     style="width: 100px; height: 100px; object-fit: cover;">
                                <div>
                                    <p class="mb-1"><strong>{{ $menu->name }}</strong></p>
                                    <small class="text-muted">Gambar saat ini akan diganti jika Anda memilih file baru</small>
                                </div>
                            </div>
                        </div>
                        @endif
                        
                        <!-- Status Toggles -->
                        <div class="col-12">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="is_available" name="is_available" value="1"
                                               {{ old('is_available', $menu->is_available) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_available">
                                            Menu tersedia
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="is_recommended" name="is_recommended" value="1"
                                               {{ old('is_recommended', $menu->is_recommended) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_recommended">
                                            Menu rekomendasi
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <hr class="my-4">
                    
                    <!-- Submit Buttons -->
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save me-2"></i>Simpan Perubahan
                        </button>
                        <a href="{{ route('menus.show', $menu) }}" class="btn btn-secondary">
                            <i class="fas fa-times me-2"></i>Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Sidebar -->
    <div class="col-lg-4">
        <!-- Tips Card -->
        <div class="card shadow-sm mb-4">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="fas fa-lightbulb me-2 text-warning"></i>
                    Tips Edit Menu
                </h6>
            </div>
            <div class="card-body">
                <ul class="list-unstyled mb-0">
                    <li class="mb-2">
                        <i class="fas fa-check text-success me-2"></i>
                        <strong>Nama yang Menarik:</strong> Gunakan nama yang menggugah selera
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-check text-success me-2"></i>
                        <strong>Deskripsi Lengkap:</strong> Jelaskan bahan dan keunggulan
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-check text-success me-2"></i>
                        <strong>Harga Kompetitif:</strong> Sesuaikan dengan pasar dan kualitas
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-check text-success me-2"></i>
                        <strong>Gambar Berkualitas:</strong> Foto yang jelas dan menarik
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-check text-success me-2"></i>
                        <strong>Status Tersedia:</strong> Update sesuai stok yang ada
                    </li>
                    <li class="mb-0">
                        <i class="fas fa-check text-success me-2"></i>
                        <strong>Menu Rekomendasi:</strong> Pilih menu unggulan outlet
                    </li>
                </ul>
            </div>
        </div>
        
        <!-- Current Status -->
        <div class="card shadow-sm mb-4">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="fas fa-info-circle me-2 text-info"></i>
                    Status Saat Ini
                </h6>
            </div>
            <div class="card-body">
                <div class="row g-2">
                    <div class="col-6">
                        <div class="text-center p-2 bg-light rounded">
                            <small class="text-muted d-block">Ketersediaan</small>
                            <span class="badge bg-{{ $menu->is_available ? 'success' : 'danger' }}">
                                {{ $menu->is_available ? 'Tersedia' : 'Tidak Tersedia' }}
                            </span>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-center p-2 bg-light rounded">
                            <small class="text-muted d-block">Rekomendasi</small>
                            <span class="badge bg-{{ $menu->is_recommended ? 'warning' : 'secondary' }}">
                                {{ $menu->is_recommended ? 'Ya' : 'Tidak' }}
                            </span>
                        </div>
                    </div>
                </div>
                
                <hr class="my-3">
                
                <div class="row g-2">
                    <div class="col-6">
                        <div class="text-center p-2 bg-light rounded">
                            <small class="text-muted d-block">Total Review</small>
                            <strong>{{ $menu->total_reviews ?? 0 }}</strong>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-center p-2 bg-light rounded">
                            <small class="text-muted d-block">Rating</small>
                            <div class="text-warning">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= ($menu->average_rating ?? 0))
                                        <i class="fas fa-star fa-sm"></i>
                                    @else
                                        <i class="far fa-star fa-sm"></i>
                                    @endif
                                @endfor
                            </div>
                            <small class="text-muted">({{ $menu->total_reviews ?? 0 }})</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Quick Actions -->
        <div class="card shadow-sm">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="fas fa-bolt me-2 text-primary"></i>
                    Aksi Cepat
                </h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('menus.index') }}" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-list me-2"></i>Lihat Semua Menu
                    </a>
                    <a href="{{ route('outlets.show', $menu->outlet) }}" class="btn btn-outline-info btn-sm">
                        <i class="fas fa-store me-2"></i>Lihat Outlet
                    </a>
                    <button type="button" class="btn btn-outline-warning btn-sm" 
                            onclick="toggleAvailability()">
                        <i class="fas fa-toggle-on me-2"></i>Toggle Ketersediaan
                    </button>
                    <button type="button" class="btn btn-outline-success btn-sm" 
                            onclick="toggleRecommendation()">
                        <i class="fas fa-star me-2"></i>Toggle Rekomendasi
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Toggle Forms (Hidden) -->
<form id="toggleAvailabilityForm" action="{{ route('menus.toggle-availability', $menu) }}" method="POST" style="display: none;">
    @csrf
</form>

<form id="toggleRecommendationForm" action="{{ route('menus.toggle-recommendation', $menu) }}" method="POST" style="display: none;">
    @csrf
</form>
@endsection

@push('styles')
<style>
.form-check-input:checked {
    background-color: #28a745;
    border-color: #28a745;
}

.form-control:focus {
    border-color: #28a745;
    box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
}

.btn {
    border-radius: 8px;
}

.card {
    border: none;
    border-radius: 15px;
}

.card-header {
    background-color: #f8f9fa;
    border-bottom: 1px solid #e9ecef;
    border-radius: 15px 15px 0 0 !important;
}

.badge {
    font-size: 0.75rem;
}

.input-group-text {
    background-color: #f8f9fa;
    border-color: #ced4da;
}

.form-control-plaintext {
    background-color: #f8f9fa;
    border: 1px solid #ced4da;
    border-radius: 8px;
    padding: 0.375rem 0.75rem;
}
</style>
@endpush

@push('scripts')
<script>
// Toggle availability
function toggleAvailability() {
    if (confirm('Apakah Anda yakin ingin mengubah status ketersediaan menu ini?')) {
        document.getElementById('toggleAvailabilityForm').submit();
    }
}

// Toggle recommendation
function toggleRecommendation() {
    if (confirm('Apakah Anda yakin ingin mengubah status rekomendasi menu ini?')) {
        document.getElementById('toggleRecommendationForm').submit();
    }
}

// Event listeners
document.addEventListener('DOMContentLoaded', function() {
    // Form validation
    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function(e) {
            const name = document.getElementById('name').value.trim();
            const price = document.getElementById('price').value;
            
            if (name === '') {
                e.preventDefault();
                showAlert('Nama menu tidak boleh kosong.', 'danger');
                return false;
            }
            
            if (price <= 0) {
                e.preventDefault();
                showAlert('Harga harus lebih dari 0.', 'danger');
                return false;
            }
        });
    }
    
    // Price formatting
    const priceInput = document.getElementById('price');
    if (priceInput) {
        priceInput.addEventListener('blur', function() {
            const value = parseInt(this.value);
            if (value > 0) {
                this.value = value;
            }
        });
    }
});

// Show alert message
function showAlert(message, type) {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    const container = document.querySelector('.card-body');
    container.insertBefore(alertDiv, container.firstChild);
    
    // Auto dismiss after 5 seconds
    setTimeout(() => {
        if (alertDiv.parentNode) {
            alertDiv.remove();
        }
    }, 5000);
}
</script>
@endpush
