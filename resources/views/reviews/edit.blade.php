@extends('layouts.app')

@section('title', 'Edit Review')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-edit me-2"></i>
                        <h5 class="mb-0">Edit Review</h5>
                    </div>
                </div>
                
                <div class="card-body">
                    <!-- Review Info -->
                    <div class="review-info mb-4 p-3 bg-light rounded">
                        <div class="d-flex align-items-center">
                            @if($review->reviewable_type === 'App\Models\Outlet')
                                <div class="bg-warning rounded d-flex align-items-center justify-content-center me-3" 
                                     style="width: 60px; height: 60px;">
                                    <i class="fas fa-store fa-2x text-white"></i>
                                </div>
                                <div>
                                    <h6 class="mb-1">{{ $review->reviewable->name }}</h6>
                                    <p class="text-muted mb-0">
                                        <i class="fas fa-map-marker-alt me-1"></i>
                                        {{ $review->reviewable->address }}
                                    </p>
                                </div>
                            @else
                                <div class="bg-success rounded d-flex align-items-center justify-content-center me-3" 
                                     style="width: 60px; height: 60px;">
                                    <i class="fas fa-utensils fa-2x text-white"></i>
                                </div>
                                <div>
                                    <h6 class="mb-1">{{ $review->reviewable->name }}</h6>
                                    <p class="text-muted mb-0">
                                        <i class="fas fa-store me-1"></i>
                                        {{ $review->reviewable->outlet->name }}
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Edit Form -->
                    <form action="{{ route('reviews.update', $review) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="rating" class="form-label">Rating <span class="text-danger">*</span></label>
                            <x-rating-input name="rating" :value="old('rating', $review->rating)" required />
                        </div>
                        
                        <div class="mb-3">
                            <label for="title" class="form-label">Judul Review</label>
                            <input type="text" 
                                   class="form-control @error('title') is-invalid @enderror" 
                                   id="title" 
                                   name="title" 
                                   value="{{ old('title', $review->title) }}" 
                                   placeholder="Berikan judul singkat untuk review Anda">
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="comment" class="form-label">Komentar <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('comment') is-invalid @enderror" 
                                      id="comment" 
                                      name="comment" 
                                      rows="4" 
                                      placeholder="Bagikan pengalaman Anda...">{{ old('comment', $review->comment) }}</textarea>
                            @error('comment')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <!-- Current Images -->
                        @if($review->images && count($review->images) > 0)
                            <div class="mb-3">
                                <label class="form-label">Gambar Saat Ini</label>
                                <div class="row g-2">
                                    @foreach($review->images as $index => $image)
                                        <div class="col-auto">
                                            <div class="position-relative">
                                                <img src="{{ Storage::url($image) }}" 
                                                     alt="Review image" 
                                                     class="img-thumbnail" 
                                                     style="width: 100px; height: 100px; object-fit: cover;">
                                                <div class="form-check position-absolute top-0 start-0 m-2">
                                                    <input class="form-check-input" 
                                                           type="checkbox" 
                                                           name="remove_images[]" 
                                                           value="{{ $index }}" 
                                                           id="remove_image_{{ $index }}">
                                                    <label class="form-check-label text-white fw-bold" 
                                                           for="remove_image_{{ $index }}"
                                                           style="text-shadow: 1px 1px 2px rgba(0,0,0,0.8);">
                                                        Hapus
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <small class="text-muted">Centang gambar yang ingin dihapus</small>
                            </div>
                        @endif
                        
                        <div class="mb-3">
                            <x-image-upload name="new_images" 
                                           label="Tambah Gambar Baru (Opsional)" 
                                           multiple 
                                           :maxFiles="5" />
                        </div>
                        
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" 
                                       type="checkbox" 
                                       id="anonymous" 
                                       name="anonymous" 
                                       value="1" 
                                       {{ old('anonymous', $review->anonymous) ? 'checked' : '' }}>
                                <label class="form-check-label" for="anonymous">
                                    Kirim review secara anonim
                                </label>
                            </div>
                            <small class="text-muted">Review anonim tidak akan menampilkan nama Anda</small>
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <a href="{{ $review->reviewable_type === 'App\Models\Outlet' ? route('outlets.show', $review->reviewable) : route('menus.show', $review->reviewable) }}" 
                               class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Kembali
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Update Review
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Review Guidelines -->
            <div class="card mt-4">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-info-circle me-2 text-info"></i>
                        Panduan Review
                    </h6>
                </div>
                <div class="card-body">
                    <ul class="mb-0">
                        <li>Berikan review yang jujur dan berdasarkan pengalaman pribadi</li>
                        <li>Hindari kata-kata kasar atau tidak sopan</li>
                        <li>Review akan dimoderasi sebelum ditampilkan</li>
                        <li>Anda dapat mengedit atau menghapus review Anda kapan saja</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Form validation
    const form = document.querySelector('form');
    const ratingInput = document.querySelector('input[name="rating"]');
    
    form.addEventListener('submit', function(e) {
        if (!ratingInput.value || ratingInput.value == 0) {
            e.preventDefault();
            alert('Silakan berikan rating terlebih dahulu');
            return false;
        }
    });
});
</script>
@endpush
