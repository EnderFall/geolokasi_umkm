@extends('layouts.app')

@section('title', 'Edit Outlet - Geolokasi UMKM Kuliner')

@section('content')
<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-1">
            <i class="fas fa-edit me-2 text-success"></i>
            Edit Outlet
        </h2>
        <p class="text-muted mb-0">Perbarui informasi outlet Anda</p>
    </div>
    <a href="{{ route('outlets.show', $outlet) }}" class="btn btn-outline-success">
        <i class="fas fa-eye me-2"></i>Lihat Outlet
    </a>
</div>

<!-- Edit Form -->
<div class="row">
    <div class="col-lg-8">
        <div class="card shadow-sm">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="fas fa-store me-2"></i>
                    Informasi Outlet
                </h6>
            </div>
            <div class="card-body">
                <form action="{{ route('outlets.update', $outlet) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="row g-3">
                        <!-- Outlet Name -->
                        <div class="col-12">
                            <label for="name" class="form-label">Nama Outlet <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name', $outlet->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <!-- Phone -->
                        <div class="col-md-6">
                            <label for="phone" class="form-label">Nomor Telepon <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                   id="phone" name="phone" value="{{ old('phone', $outlet->phone) }}" required>
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <!-- Operating Hours -->
                        <div class="col-md-3">
                            <label for="open_time" class="form-label">Jam Buka</label>
                            <input type="time" class="form-control @error('open_time') is-invalid @enderror" 
                                   id="open_time" name="open_time" value="{{ old('open_time', $outlet->open_time) }}">
                            @error('open_time')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-3">
                            <label for="close_time" class="form-label">Jam Tutup</label>
                            <input type="time" class="form-control @error('close_time') is-invalid @enderror" 
                                   id="close_time" name="close_time" value="{{ old('close_time', $outlet->close_time) }}">
                            @error('close_time')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <!-- Description -->
                        <div class="col-12">
                            <label for="description" class="form-label">Deskripsi</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="4" 
                                      placeholder="Jelaskan outlet Anda, menu unggulan, dan keunggulan...">{{ old('description', $outlet->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <!-- Address -->
                        <div class="col-12">
                            <label for="address" class="form-label">Alamat <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('address') is-invalid @enderror" 
                                      id="address" name="address" rows="3" required 
                                      placeholder="Masukkan alamat lengkap outlet...">{{ old('address', $outlet->address) }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <!-- Coordinates -->
                        <div class="col-md-6">
                            <label for="latitude" class="form-label">Latitude</label>
                            <div class="input-group">
                                <input type="number" class="form-control @error('latitude') is-invalid @enderror" 
                                       id="latitude" name="latitude" step="any" 
                                       value="{{ old('latitude', $outlet->latitude) }}" 
                                       placeholder="-6.2088">
                                <button type="button" class="btn btn-outline-secondary" id="getLocationBtn">
                                    <i class="fas fa-crosshairs"></i>
                                </button>
                            </div>
                            @error('latitude')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Gunakan tombol GPS untuk mendapatkan lokasi otomatis</small>
                        </div>
                        
                        <div class="col-md-6">
                            <label for="longitude" class="form-label">Longitude</label>
                            <input type="number" class="form-control @error('longitude') is-invalid @enderror" 
                                   id="longitude" name="longitude" step="any" 
                                   value="{{ old('longitude', $outlet->longitude) }}" 
                                   placeholder="106.8456">
                            @error('longitude')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <!-- Categories -->
                        <div class="col-12">
                            <label class="form-label">Kategori <span class="text-danger">*</span></label>
                            <div class="row g-2">
                                @foreach($categories as $category)
                                <div class="col-md-4 col-sm-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" 
                                               name="categories[]" value="{{ $category->id }}" 
                                               id="category{{ $category->id }}"
                                               {{ in_array($category->id, old('categories', $outlet->categories->pluck('id')->toArray())) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="category{{ $category->id }}">
                                            <span class="me-2">{{ $category->icon }}</span>
                                            {{ $category->name }}
                                        </label>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            @error('categories')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <!-- Image -->
                        <div class="col-12">
                            <label for="image" class="form-label">Gambar Outlet</label>
                            <input type="file" class="form-control @error('image') is-invalid @enderror" 
                                   id="image" name="image" accept="image/*">
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Format: JPG, PNG, GIF. Maksimal 2MB.</small>
                        </div>
                        
                        <!-- Current Image Preview -->
                        @if($outlet->image)
                        <div class="col-12">
                            <label class="form-label">Gambar Saat Ini</label>
                            <div class="d-flex align-items-center">
                                <img src="{{ asset('storage/' . $outlet->image) }}" 
                                     alt="{{ $outlet->name }}" 
                                     class="rounded me-3" 
                                     style="width: 100px; height: 100px; object-fit: cover;">
                                <div>
                                    <p class="mb-1"><strong>{{ $outlet->name }}</strong></p>
                                    <small class="text-muted">Gambar saat ini akan diganti jika Anda memilih file baru</small>
                                </div>
                            </div>
                        </div>
                        @endif
                        
                        <!-- Status -->
                        <div class="col-12">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="is_open" name="is_open" value="1"
                                       {{ old('is_open', $outlet->is_open) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_open">
                                    Outlet sedang buka
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <hr class="my-4">
                    
                    <!-- Submit Buttons -->
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save me-2"></i>Simpan Perubahan
                        </button>
                        <a href="{{ route('outlets.show', $outlet) }}" class="btn btn-secondary">
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
                    Tips Edit Outlet
                </h6>
            </div>
            <div class="card-body">
                <ul class="list-unstyled mb-0">
                    <li class="mb-2">
                        <i class="fas fa-check text-success me-2"></i>
                        <strong>Nama yang Menarik:</strong> Gunakan nama yang mudah diingat
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-check text-success me-2"></i>
                        <strong>Deskripsi Lengkap:</strong> Jelaskan keunggulan outlet Anda
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-check text-success me-2"></i>
                        <strong>Jam Operasional:</strong> Pastikan akurat untuk customer
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-check text-success me-2"></i>
                        <strong>Lokasi Tepat:</strong> Koordinat GPS membantu customer menemukan outlet
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-check text-success me-2"></i>
                        <strong>Kategori Tepat:</strong> Pilih kategori yang sesuai dengan menu
                    </li>
                    <li class="mb-0">
                        <i class="fas fa-check text-success me-2"></i>
                        <strong>Gambar Berkualitas:</strong> Gunakan foto yang jelas dan menarik
                    </li>
                </ul>
            </div>
        </div>
        
        <!-- Current Status -->
        <div class="card shadow-sm">
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
                            <small class="text-muted d-block">Status</small>
                            <span class="badge bg-{{ $outlet->is_open ? 'success' : 'danger' }}">
                                {{ $outlet->is_open ? 'Buka' : 'Tutup' }}
                            </span>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-center p-2 bg-light rounded">
                            <small class="text-muted d-block">Verifikasi</small>
                            <span class="badge bg-{{ $outlet->is_verified ? 'primary' : 'warning' }}">
                                {{ $outlet->is_verified ? 'Terverifikasi' : 'Belum Verifikasi' }}
                            </span>
                        </div>
                    </div>
                </div>
                
                <hr class="my-3">
                
                <div class="row g-2">
                    <div class="col-6">
                        <div class="text-center p-2 bg-light rounded">
                            <small class="text-muted d-block">Total Menu</small>
                            <strong>{{ $outlet->menus_count ?? 0 }}</strong>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-center p-2 bg-light rounded">
                            <small class="text-muted d-block">Rating</small>
                            <div class="text-warning">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= ($outlet->average_rating ?? 0))
                                        <i class="fas fa-star fa-sm"></i>
                                    @else
                                        <i class="far fa-star fa-sm"></i>
                                    @endif
                                @endfor
                            </div>
                            <small class="text-muted">({{ $outlet->total_ratings ?? 0 }})</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.form-check-input:checked {
    background-color: #28a745;
    border-color: #28a745;
}

.form-control:focus, .form-select:focus {
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

.input-group .btn {
    border-top-left-radius: 0;
    border-bottom-left-radius: 0;
}
</style>
@endpush

@push('scripts')
<script>
// Get user location
function getLocation() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            function(position) {
                document.getElementById('latitude').value = position.coords.latitude;
                document.getElementById('longitude').value = position.coords.longitude;
                
                // Show success message
                showAlert('Lokasi berhasil didapatkan!', 'success');
            },
            function(error) {
                let errorMessage = 'Tidak dapat mendapatkan lokasi Anda.';
                switch(error.code) {
                    case error.PERMISSION_DENIED:
                        errorMessage = 'Akses lokasi ditolak.';
                        break;
                    case error.POSITION_UNAVAILABLE:
                        errorMessage = 'Informasi lokasi tidak tersedia.';
                        break;
                    case error.TIMEOUT:
                        errorMessage = 'Waktu permintaan lokasi habis.';
                        break;
                }
                showAlert(errorMessage, 'danger');
            },
            {
                enableHighAccuracy: true,
                timeout: 10000,
                maximumAge: 60000
            }
        );
    } else {
        showAlert('Geolokasi tidak didukung oleh browser ini.', 'warning');
    }
}

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

// Event listeners
document.addEventListener('DOMContentLoaded', function() {
    const getLocationBtn = document.getElementById('getLocationBtn');
    
    if (getLocationBtn) {
        getLocationBtn.addEventListener('click', getLocation);
    }
    
    // Form validation
    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function(e) {
            const categories = document.querySelectorAll('input[name="categories[]"]:checked');
            if (categories.length === 0) {
                e.preventDefault();
                showAlert('Pilih minimal satu kategori untuk outlet Anda.', 'danger');
                return false;
            }
        });
    }
});
</script>
@endpush
