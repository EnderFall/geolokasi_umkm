@extends('layouts.app')

@section('title', 'Buat Review Menu - ' . $menu->name)

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-warning text-dark">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-star me-2"></i>
                        <h5 class="mb-0">Buat Review Menu</h5>
                    </div>
                </div>
                
                <div class="card-body">
                    <!-- Menu Info -->
                    <div class="menu-info mb-4 p-3 bg-light rounded">
                        <div class="d-flex align-items-center">
                            @if($menu->image)
                                <img src="{{ Storage::url($menu->image) }}" 
                                     alt="{{ $menu->name }}" 
                                     class="rounded me-3" 
                                     style="width: 80px; height: 80px; object-fit: cover;">
                            @else
                                <div class="bg-secondary rounded d-flex align-items-center justify-content-center me-3" 
                                     style="width: 80px; height: 80px;">
                                    <i class="fas fa-utensils fa-2x text-white"></i>
                                </div>
                            @endif
                            
                            <div>
                                <h6 class="mb-1">{{ $menu->name }}</h6>
                                <p class="text-muted mb-1">
                                    <i class="fas fa-store me-1"></i>
                                    {{ $menu->outlet->name }}
                                </p>
                                <p class="text-muted mb-0">
                                    <i class="fas fa-tag me-1"></i>
                                    Rp {{ number_format($menu->price, 0, ',', '.') }}
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Review Form -->
                    <form action="{{ route('reviews.store.menu', $menu) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="rating" class="form-label">Rating <span class="text-danger">*</span></label>
                            <x-rating-input name="rating" :value="old('rating', 0)" required />
                        </div>
                        
                        <div class="mb-3">
                            <label for="title" class="form-label">Judul Review</label>
                            <input type="text" 
                                   class="form-control @error('title') is-invalid @enderror" 
                                   id="title" 
                                   name="title" 
                                   value="{{ old('title') }}" 
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
                                      placeholder="Bagikan pengalaman Anda dengan menu ini...">{{ old('comment') }}</textarea>
                            @error('comment')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <x-image-upload name="images" 
                                           label="Upload Gambar (Opsional)" 
                                           multiple 
                                           :maxFiles="5" />
                        </div>
                        
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="anonymous" name="anonymous" value="1" {{ old('anonymous') ? 'checked' : '' }}>
                                <label class="form-check-label" for="anonymous">
                                    Kirim review secara anonim
                                </label>
                            </div>
                            <small class="text-muted">Review anonim tidak akan menampilkan nama Anda</small>
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('menus.show', $menu) }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Kembali
                            </a>
                            <button type="submit" class="btn btn-warning">
                                <i class="fas fa-paper-plane me-2"></i>Kirim Review
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
