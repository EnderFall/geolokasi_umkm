@extends('layouts.app')

@section('title', 'Tambah Menu Baru - Geolokasi UMKM Kuliner')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow-lg border-0">
            <div class="card-header bg-success text-white text-center py-4">
                <h4 class="mb-0">
                    <i class="fas fa-utensils me-2"></i>
                    Tambah Menu Baru
                </h4>
                <p class="mb-0 mt-2">Tambahkan menu kuliner ke outlet Anda</p>
            </div>
            <div class="card-body p-4">
                <form method="POST" action="{{ route('menus.store') }}" enctype="multipart/form-data">
                    @csrf
                    
                    <!-- Outlet Info -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="alert alert-info">
                                <i class="fas fa-store me-2"></i>
                                <strong>Outlet:</strong> {{ $outlet->name }}
                            </div>
                        </div>
                    </div>
                    
                    <!-- Basic Information -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h5 class="text-success mb-3">
                                <i class="fas fa-info-circle me-2"></i>
                                Informasi Menu
                            </h5>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name" class="form-label">
                                    <i class="fas fa-utensils me-1"></i>Nama Menu <span class="text-danger">*</span>
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
                                <label for="price" class="form-label">
                                    <i class="fas fa-tag me-1"></i>Harga <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number" class="form-control @error('price') is-invalid @enderror" 
                                           id="price" name="price" value="{{ old('price') }}" 
                                           min="0" step="100" required>
                                </div>
                                @error('price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-12">
                            <div class="mb-3">
                                <label for="description" class="form-label">
                                    <i class="fas fa-align-left me-1"></i>Deskripsi Menu
                                </label>
                                <textarea class="form-control @error('description') is-invalid @enderror" 
                                          id="description" name="description" rows="3" 
                                          placeholder="Jelaskan menu Anda...">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">
                                    Jelaskan bahan, cara penyajian, atau keunikan menu Anda
                                </small>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Menu Settings -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h5 class="text-success mb-3">
                                <i class="fas fa-cogs me-2"></i>
                                Pengaturan Menu
                            </h5>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" 
                                           id="is_available" name="is_available" value="1" 
                                           {{ old('is_available', true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_available">
                                        <i class="fas fa-check-circle me-1"></i>Menu Tersedia
                                    </label>
                                </div>
                                <small class="form-text text-muted">
                                    Aktifkan jika menu sedang tersedia untuk dipesan
                                </small>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" 
                                           id="is_recommended" name="is_recommended" value="1" 
                                           {{ old('is_recommended') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_recommended">
                                        <i class="fas fa-star me-1"></i>Menu Rekomendasi
                                    </label>
                                </div>
                                <small class="form-text text-muted">
                                    Aktifkan untuk menampilkan menu sebagai rekomendasi
                                </small>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Image Upload -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h5 class="text-success mb-3">
                                <i class="fas fa-image me-2"></i>
                                Foto Menu
                            </h5>
                        </div>
                        
                        <div class="col-12">
                            <div class="mb-3">
                                <label for="image" class="form-label">
                                    <i class="fas fa-upload me-1"></i>Upload Foto Menu
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
                        
                        <!-- Image Preview -->
                        <div class="col-12">
                            <div id="imagePreview" class="d-none">
                                <label class="form-label">Preview Foto:</label>
                                <div class="border rounded p-3 text-center">
                                    <img id="previewImg" src="" alt="Preview" 
                                         class="img-fluid" style="max-height: 200px;">
                                </div>
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
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-save me-2"></i>Simpan Menu
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
                <h6 class="text-success mb-3">
                    <i class="fas fa-question-circle me-2"></i>
                    Tips Membuat Menu
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
                                <p class="text-muted small mb-0">Jelaskan keunikan menu Anda</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex">
                            <i class="fas fa-check-circle text-success me-2 mt-1"></i>
                            <div>
                                <strong>Harga Kompetitif</strong>
                                <p class="text-muted small mb-0">Tentukan harga yang sesuai</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex">
                            <i class="fas fa-check-circle text-success me-2 mt-1"></i>
                            <div>
                                <strong>Update Status</strong>
                                <p class="text-muted small mb-0">Update ketersediaan menu secara rutin</p>
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
    border-color: #198754;
    box-shadow: 0 0 0 0.2rem rgba(25, 135, 84, 0.25);
}

.form-check-input:checked {
    background-color: #198754;
    border-color: #198754;
}

.alert {
    border-radius: 10px;
}

#imagePreview {
    transition: all 0.3s ease;
}

.form-switch .form-check-input {
    width: 3rem;
    height: 1.5rem;
}
</style>
@endpush

@push('scripts')
<script>
// Preview image before upload
document.getElementById('image').addEventListener('change', function(e) {
    const file = e.target.files[0];
    const preview = document.getElementById('imagePreview');
    const previewImg = document.getElementById('previewImg');
    
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            previewImg.src = e.target.result;
            preview.classList.remove('d-none');
        };
        reader.readAsDataURL(file);
    } else {
        preview.classList.add('d-none');
    }
});

// Form validation
document.querySelector('form').addEventListener('submit', function(e) {
    const name = document.getElementById('name').value.trim();
    const price = document.getElementById('price').value;
    
    if (name === '') {
        e.preventDefault();
        alert('Nama menu harus diisi!');
        document.getElementById('name').focus();
        return false;
    }
    
    if (price <= 0) {
        e.preventDefault();
        alert('Harga harus lebih dari 0!');
        document.getElementById('price').focus();
        return false;
    }
});

// Auto-format price input
document.getElementById('price').addEventListener('input', function(e) {
    let value = e.target.value.replace(/\D/g, '');
    if (value) {
        value = parseInt(value);
        e.target.value = value;
    }
});
</script>
@endpush
