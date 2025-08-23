@extends('layouts.app')

@section('title', 'Buat Review - Geolokasi UMKM Kuliner')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-1">
            <i class="fas fa-star me-2 text-warning"></i>
            Buat Review Baru
        </h2>
        <p class="text-muted mb-0">Bagikan pengalaman Anda</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ url()->previous() }}" class="btn btn-outline-primary">
            <i class="fas fa-arrow-left me-2"></i>Kembali
        </a>
    </div>
</div>

<div class="row g-4">
    <!-- Review Form -->
    <div class="col-lg-8">
        <div class="card shadow-sm">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="fas fa-edit me-2"></i>
                    Form Review
                </h6>
            </div>
            <div class="card-body">
                <form action="{{ route('reviews.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <!-- Review Type Info -->
                    <div class="alert alert-info">
                        <div class="d-flex align-items-center">
                            <div class="me-3">
                                @if($item_type === 'outlet')
                                    <i class="fas fa-store text-primary"></i>
                                @else
                                    <i class="fas fa-utensils text-success"></i>
                                @endif
                            </div>
                            <div>
                                <strong>Review untuk:</strong> {{ $item->name }}
                                <br><small class="text-muted">
                                    {{ ucfirst($item_type) }}
                                </small>
                            </div>
                        </div>
                    </div>

                    <!-- Hidden Fields -->
                    <input type="hidden" name="reviewable_type" value="{{ $item_type === 'outlet' ? 'App\Models\Outlet' : 'App\Models\Menu' }}">
                    <input type="hidden" name="reviewable_id" value="{{ $item->id }}">

                    <!-- Rating -->
                    <div class="mb-4">
                        <label class="form-label">Rating <span class="text-danger">*</span></label>
                        <div class="rating-input">
                            @for($i = 5; $i >= 1; $i--)
                            <input type="radio" name="rating" id="star{{ $i }}" value="{{ $i }}" required>
                            <label for="star{{ $i }}" class="star-label">
                                <i class="fas fa-star"></i>
                            </label>
                            @endfor
                        </div>
                        @error('rating')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Pilih rating dari 1-5 bintang</div>
                    </div>

                    <!-- Comment -->
                    <div class="mb-4">
                        <label for="comment" class="form-label">Komentar</label>
                        <textarea name="comment" id="comment" rows="4" class="form-control @error('comment') is-invalid @enderror" 
                                  placeholder="Bagikan pengalaman Anda dengan {{ $item->name }}...">{{ old('comment') }}</textarea>
                        @error('comment')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Maksimal 1000 karakter. Kosongkan jika tidak ingin memberikan komentar.</div>
                    </div>

                    <!-- Images -->
                    <div class="mb-4">
                        <label for="images" class="form-label">Upload Gambar</label>
                        <input type="file" name="images[]" id="images" class="form-control @error('images.*') is-invalid @enderror" 
                               multiple accept="image/*">
                        @error('images.*')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">
                            Upload gambar untuk memperjelas review Anda (opsional). Format: JPEG, PNG, JPG, GIF. Maksimal 2MB per gambar.
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-paper-plane me-2"></i>Kirim Review
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="col-lg-4">
        <!-- Item Information -->
        <div class="card shadow-sm mb-4">
            <div class="card-header">
                <h6 class="mb-0">
                    @if($item_type === 'outlet')
                        <i class="fas fa-store me-2"></i>
                        Informasi Outlet
                    @else
                        <i class="fas fa-utensils me-2"></i>
                        Informasi Menu
                    @endif
                </h6>
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <div class="flex-shrink-0 me-3">
                        @if($item->image)
                            <img src="{{ asset('storage/' . $item->image) }}" 
                                 alt="{{ $item->name }}" 
                                 class="rounded" 
                                 style="width: 80px; height: 80px; object-fit: cover;">
                        @else
                            <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                                 style="width: 80px; height: 80px;">
                                @if($item_type === 'outlet')
                                    <i class="fas fa-store fa-2x text-muted"></i>
                                @else
                                    <i class="fas fa-utensils fa-2x text-muted"></i>
                                @endif
                            </div>
                        @endif
                    </div>
                    <div>
                        <h6 class="mb-1">{{ $item->name }}</h6>
                        @if($item_type === 'outlet')
                            <small class="text-muted">{{ $item->address }}</small>
                        @else
                            <small class="text-muted">{{ $item->outlet->name }}</small>
                        @endif
                    </div>
                </div>
                
                @if($item_type === 'outlet')
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <small class="text-muted d-block">Status</small>
                            <span class="badge bg-{{ $item->is_open ? 'success' : 'danger' }}">
                                {{ $item->is_open ? 'Buka' : 'Tutup' }}
                            </span>
                        </div>
                        <div class="col-6">
                            <small class="text-muted d-block">Verifikasi</small>
                            <span class="badge bg-{{ $item->is_verified ? 'primary' : 'warning' }}">
                                {{ $item->is_verified ? 'Terverifikasi' : 'Belum Verifikasi' }}
                            </span>
                        </div>
                    </div>
                    
                    @if($item->description)
                    <div class="mb-3">
                        <small class="text-muted d-block">Deskripsi</small>
                        <strong>{{ Str::limit($item->description, 100) }}</strong>
                    </div>
                    @endif
                    
                    <div class="mb-3">
                        <small class="text-muted d-block">Rating Rata-rata</small>
                        <div class="d-flex align-items-center">
                            {!! $item->rating_stars !!}
                            <span class="ms-2 fw-bold">{{ $item->rating }}/5</span>
                            <span class="ms-2 text-muted">({{ $item->total_reviews }} review)</span>
                        </div>
                    </div>
                @else
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <small class="text-muted d-block">Harga</small>
                            <strong class="text-primary">Rp {{ number_format($item->price, 0, ',', '.') }}</strong>
                        </div>
                        <div class="col-6">
                            <small class="text-muted d-block">Status</small>
                            <span class="badge bg-{{ $item->is_available ? 'success' : 'danger' }}">
                                {{ $item->is_available ? 'Tersedia' : 'Tidak Tersedia' }}
                            </span>
                        </div>
                    </div>
                    
                    @if($item->description)
                    <div class="mb-3">
                        <small class="text-muted d-block">Deskripsi</small>
                        <strong>{{ Str::limit($item->description, 100) }}</strong>
                    </div>
                    @endif
                    
                    <div class="mb-3">
                        <small class="text-muted d-block">Rating Rata-rata</small>
                        <div class="d-flex align-items-center">
                            {!! $item->rating_stars !!}
                            <span class="ms-2 fw-bold">{{ $item->rating }}/5</span>
                            <span class="ms-2 text-muted">({{ $item->total_reviews }} review)</span>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Review Guidelines -->
        <div class="card shadow-sm">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="fas fa-lightbulb me-2 text-warning"></i>
                    Panduan Review
                </h6>
            </div>
            <div class="card-body">
                <ul class="list-unstyled mb-0">
                    <li class="mb-2">
                        <i class="fas fa-check text-success me-2"></i>
                        Berikan rating yang objektif berdasarkan pengalaman
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-check text-success me-2"></i>
                        Tulis komentar yang informatif dan bermanfaat
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-check text-success me-2"></i>
                        Gunakan bahasa yang sopan dan konstruktif
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-check text-success me-2"></i>
                        Tambahkan foto untuk memperjelas review
                    </li>
                    <li class="mb-0">
                        <i class="fas fa-check text-success me-2"></i>
                        Review akan diverifikasi oleh admin
                    </li>
                </ul>
            </div>
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

.rating-input {
    display: flex;
    flex-direction: row-reverse;
    gap: 0.5rem;
}

.rating-input input[type="radio"] {
    display: none;
}

.star-label {
    font-size: 2rem;
    color: #e9ecef;
    cursor: pointer;
    transition: color 0.2s ease;
}

.star-label:hover,
.star-label:hover ~ .star-label,
.rating-input input[type="radio"]:checked ~ .star-label {
    color: #ffc107;
}

.alert {
    border-radius: 10px;
    border: none;
}

.alert-info {
    background-color: #e7f3ff;
    color: #0c5460;
}

.badge {
    font-size: 0.75rem;
}
</style>
@endpush

@push('scripts')
<script>
// Preview images
document.getElementById('images').addEventListener('change', function(e) {
    const files = e.target.files;
    const previewContainer = document.createElement('div');
    previewContainer.className = 'row g-3 mt-2';
    
    for (let i = 0; i < files.length; i++) {
        const file = files[i];
        const reader = new FileReader();
        
        reader.onload = function(e) {
            const col = document.createElement('div');
            col.className = 'col-md-4 col-lg-3';
            col.innerHTML = `
                <div class="position-relative">
                    <img src="${e.target.result}" alt="Preview" class="img-fluid rounded" style="width: 100%; height: 150px; object-fit: cover;">
                    <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-1" onclick="this.parentElement.parentElement.remove()">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            `;
            previewContainer.appendChild(col);
        };
        
        reader.readAsDataURL(file);
    }
    
    // Remove existing preview
    const existingPreview = document.querySelector('.preview-container');
    if (existingPreview) {
        existingPreview.remove();
    }
    
    // Add new preview
    previewContainer.className += ' preview-container';
    document.getElementById('images').parentNode.appendChild(previewContainer);
});

// Form validation
document.querySelector('form').addEventListener('submit', function(e) {
    const rating = document.querySelector('input[name="rating"]:checked');
    if (!rating) {
        e.preventDefault();
        alert('Silakan pilih rating terlebih dahulu.');
        return false;
    }
});
</script>
@endpush
