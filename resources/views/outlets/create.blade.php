@extends('layouts.app')

@section('title', 'Buat Outlet Baru - Geolokasi UMKM Kuliner')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow-lg border-0">
            <div class="card-header bg-primary text-white text-center py-4">
                <h4 class="mb-0">
                    <i class="fas fa-store me-2"></i>
                    Buat Outlet Baru
                </h4>
                <p class="mb-0 mt-2">Daftarkan outlet kuliner Anda ke platform</p>
            </div>
            <div class="card-body p-4">
                <form method="POST" action="{{ route('outlets.store') }}" enctype="multipart/form-data">
                    @csrf
                    
                    <!-- Basic Information -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h5 class="text-primary mb-3">
                                <i class="fas fa-info-circle me-2"></i>
                                Informasi Dasar
                            </h5>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name" class="form-label">
                                    <i class="fas fa-store me-1"></i>Nama Outlet <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="phone" class="form-label">
                                    <i class="fas fa-phone me-1"></i>Nomor Telepon <span class="text-danger">*</span>
                                </label>
                                <input type="tel" class="form-control @error('phone') is-invalid @enderror" 
                                       id="phone" name="phone" value="{{ old('phone') }}" required>
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-12">
                            <div class="mb-3">
                                <label for="description" class="form-label">
                                    <i class="fas fa-align-left me-1"></i>Deskripsi Outlet
                                </label>
                                <textarea class="form-control @error('description') is-invalid @enderror" 
                                          id="description" name="description" rows="3" 
                                          placeholder="Jelaskan outlet Anda...">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <!-- Address and Location -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h5 class="text-primary mb-3">
                                <i class="fas fa-map-marker-alt me-2"></i>
                                Alamat & Lokasi
                            </h5>
                        </div>
                        
                        <div class="col-12">
                            <div class="mb-3">
                                <label for="address" class="form-label">
                                    <i class="fas fa-map-marker-alt me-1"></i>Alamat Lengkap <span class="text-danger">*</span>
                                </label>
                                <textarea class="form-control @error('address') is-invalid @enderror" 
                                          id="address" name="address" rows="3" required 
                                          placeholder="Masukkan alamat lengkap outlet...">{{ old('address') }}</textarea>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="latitude" class="form-label">
                                    <i class="fas fa-globe me-1"></i>Latitude
                                </label>
                                <input type="number" step="any" class="form-control @error('latitude') is-invalid @enderror" 
                                       id="latitude" name="latitude" value="{{ old('latitude') }}" 
                                       placeholder="-6.2088">
                                @error('latitude')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">Opsional, untuk fitur geolokasi</small>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="longitude" class="form-label">
                                    <i class="fas fa-globe me-1"></i>Longitude
                                </label>
                                <input type="number" step="any" class="form-control @error('longitude') is-invalid @enderror" 
                                       id="longitude" name="longitude" value="{{ old('longitude') }}" 
                                       placeholder="106.8456">
                                @error('longitude')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">Opsional, untuk fitur geolokasi</small>
                            </div>
                        </div>
                        
                        <div class="col-12">
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                <strong>Tips:</strong> Anda dapat menggunakan Google Maps untuk mendapatkan koordinat latitude dan longitude outlet Anda.
                            </div>
                        </div>
                    </div>
                    
                    <!-- Operating Hours -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h5 class="text-primary mb-3">
                                <i class="fas fa-clock me-2"></i>
                                Jam Operasional
                            </h5>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="open_time" class="form-label">
                                    <i class="fas fa-sun me-1"></i>Jam Buka
                                </label>
                                <input type="time" class="form-control @error('open_time') is-invalid @enderror" 
                                       id="open_time" name="open_time" value="{{ old('open_time') }}">
                                @error('open_time')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="close_time" class="form-label">
                                    <i class="fas fa-moon me-1"></i>Jam Tutup
                                </label>
                                <input type="time" class="form-control @error('close_time') is-invalid @enderror" 
                                       id="close_time" name="close_time" value="{{ old('close_time') }}">
                                @error('close_time')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <!-- Categories -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h5 class="text-primary mb-3">
                                <i class="fas fa-tags me-2"></i>
                                Kategori Outlet
                            </h5>
                        </div>
                        
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label">
                                    <i class="fas fa-tags me-1"></i>Pilih Kategori <span class="text-danger">*</span>
                                </label>
                                <div class="row g-2">
                                    @foreach($categories as $category)
                                    <div class="col-md-4 col-sm-6">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" 
                                                   name="categories[]" value="{{ $category->id }}" 
                                                   id="category{{ $category->id }}"
                                                   {{ in_array($category->id, old('categories', [])) ? 'checked' : '' }}>
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
                        </div>
                    </div>
                    
                    <!-- Image Upload -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h5 class="text-primary mb-3">
                                <i class="fas fa-image me-2"></i>
                                Foto Outlet
                            </h5>
                        </div>
                        
                        <div class="col-12">
                            <div class="mb-3">
                                <label for="image" class="form-label">
                                    <i class="fas fa-upload me-1"></i>Upload Foto
                                </label>
                                <input type="file" class="form-control @error('image') is-invalid @enderror" 
                                       id="image" name="image" accept="image/*">
                                @error('image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">
                                    Format: JPG, PNG, GIF. Maksimal 2MB. Opsional.
                                </small>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Submit Buttons -->
                    <div class="row">
                        <div class="col-12">
                            <div class="d-flex gap-2 justify-content-end">
                                <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                                    <i class="fas fa-times me-2"></i>Batal
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>Buat Outlet
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Help Section -->
        <div class="card border-0 bg-light mt-4">
            <div class="card-body">
                <h6 class="text-primary mb-3">
                    <i class="fas fa-question-circle me-2"></i>
                    Tips Membuat Outlet
                </h6>
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="d-flex">
                            <i class="fas fa-check-circle text-success me-2 mt-1"></i>
                            <div>
                                <strong>Foto Berkualitas</strong>
                                <p class="text-muted small mb-0">Gunakan foto yang jelas dan menarik</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex">
                            <i class="fas fa-check-circle text-success me-2 mt-1"></i>
                            <div>
                                <strong>Deskripsi Lengkap</strong>
                                <p class="text-muted small mb-0">Jelaskan keunikan outlet Anda</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex">
                            <i class="fas fa-check-circle text-success me-2 mt-1"></i>
                            <div>
                                <strong>Kategori Tepat</strong>
                                <p class="text-muted small mb-0">Pilih kategori yang sesuai</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex">
                            <i class="fas fa-check-circle text-success me-2 mt-1"></i>
                            <div>
                                <strong>Jam Operasional</strong>
                                <p class="text-muted small mb-0">Update jam buka dan tutup</p>
                            </div>
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
.card {
    border-radius: 15px;
}

.card-header {
    border-radius: 15px 15px 0 0 !important;
}

.btn {
    border-radius: 10px;
}

.form-control, .form-select {
    border-radius: 10px;
    border: 2px solid #e9ecef;
    transition: border-color 0.3s ease;
}

.form-control:focus, .form-select:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
}

.form-check-input:checked {
    background-color: #0d6efd;
    border-color: #0d6efd;
}

.alert {
    border-radius: 10px;
}
</style>
@endpush

@push('scripts')
<script>
// Preview image before upload
document.getElementById('image').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            // You can add image preview functionality here
            console.log('Image selected:', file.name);
        };
        reader.readAsDataURL(file);
    }
});

// Form validation
document.querySelector('form').addEventListener('submit', function(e) {
    const categories = document.querySelectorAll('input[name="categories[]"]:checked');
    if (categories.length === 0) {
        e.preventDefault();
        alert('Pilih minimal satu kategori outlet!');
        return false;
    }
});
</script>
@endpush
