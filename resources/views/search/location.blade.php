@extends('layouts.app')

@section('title', 'Cari Berdasarkan Lokasi - Geolokasi UMKM Kuliner')

@section('content')
<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-1">
            <i class="fas fa-map-marker-alt me-2 text-success"></i>
            Cari Berdasarkan Lokasi
        </h2>
        <p class="text-muted mb-0">Temukan outlet kuliner terdekat dengan lokasi Anda</p>
    </div>
    <div class="d-flex gap-2">
        <button type="button" class="btn btn-success" id="getLocationBtn">
            <i class="fas fa-crosshairs me-2"></i>Dapatkan Lokasi
        </button>
        <a href="{{ route('search.outlets') }}" class="btn btn-outline-success">
            <i class="fas fa-search me-2"></i>Cari Outlet
        </a>
    </div>
</div>

<!-- Location Search Form -->
<div class="card shadow-sm mb-4">
    <div class="card-body">
        <form action="{{ route('search.location') }}" method="GET" class="row g-3">
            <div class="col-md-3">
                <label class="form-label">Latitude</label>
                <input type="number" name="latitude" id="latitude" class="form-control" 
                       step="any" placeholder="Contoh: -6.2088" value="{{ $latitude }}" required>
            </div>
            <div class="col-md-3">
                <label class="form-label">Longitude</label>
                <input type="number" name="longitude" id="longitude" class="form-control" 
                       step="any" placeholder="Contoh: 106.8456" value="{{ $longitude }}" required>
            </div>
            <div class="col-md-2">
                <label class="form-label">Radius (km)</label>
                <select name="radius" class="form-select">
                    <option value="1" {{ $radius == 1 ? 'selected' : '' }}>1 km</option>
                    <option value="2" {{ $radius == 2 ? 'selected' : '' }}>2 km</option>
                    <option value="5" {{ $radius == 5 ? 'selected' : '' }}>5 km</option>
                    <option value="10" {{ $radius == 10 ? 'selected' : '' }}>10 km</option>
                    <option value="20" {{ $radius == 20 ? 'selected' : '' }}>20 km</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Kategori</label>
                <select name="category" class="form-select">
                    <option value="">Semua</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ $category == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">&nbsp;</label>
                <button type="submit" class="btn btn-success w-100">
                    <i class="fas fa-search me-1"></i>Cari
                </button>
            </div>
        </form>
        
        <!-- Location Info -->
        <div class="mt-3 pt-3 border-top">
            <div class="row">
                <div class="col-md-6">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-info-circle text-info me-2"></i>
                        <div>
                            <small class="text-muted">
                                <strong>Tips:</strong> Gunakan tombol "Dapatkan Lokasi" untuk mendapatkan koordinat GPS Anda secara otomatis, 
                                atau masukkan koordinat secara manual.
                            </small>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 text-end">
                    @if($latitude && $longitude)
                        <small class="text-muted">
                            <i class="fas fa-map-marker-alt me-1"></i>
                            Lokasi: {{ $latitude }}, {{ $longitude }}
                            @if($radius)
                                dalam radius {{ $radius }} km
                            @endif
                        </small>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Map Container -->
<div class="card shadow-sm mb-4">
    <div class="card-header">
        <h6 class="mb-0">
            <i class="fas fa-map me-2"></i>
            Peta Lokasi
        </h6>
    </div>
    <div class="card-body p-0">
        <div id="map" style="height: 400px; width: 100%;"></div>
    </div>
</div>

<!-- Search Results -->
@if(isset($outlets) && $outlets->count() > 0)
    <div class="row g-4">
        @foreach($outlets as $outlet)
        <div class="col-lg-4 col-md-6">
            <div class="card outlet-card h-100 shadow-sm">
                @if($outlet->image)
                    <img src="{{ asset('storage/' . $outlet->image) }}" 
                         class="card-img-top" alt="{{ $outlet->name }}" 
                         style="height: 200px; object-fit: cover;">
                @else
                    <div class="card-img-top bg-light d-flex align-items-center justify-content-center" 
                         style="height: 200px;">
                        <i class="fas fa-store fa-3x text-muted"></i>
                    </div>
                @endif
                
                <div class="card-body">
                    <h6 class="card-title">{{ $outlet->name }}</h6>
                    <p class="card-text text-muted small">{{ Str::limit($outlet->description, 100) }}</p>
                    
                    <!-- Distance Info -->
                    @if(isset($outlet->distance))
                    <div class="mb-2">
                        <span class="badge bg-info">
                            <i class="fas fa-ruler me-1"></i>
                            {{ number_format($outlet->distance, 2) }} km
                        </span>
                    </div>
                    @endif
                    
                    <!-- Rating -->
                    <div class="d-flex align-items-center mb-2">
                        <div class="text-warning me-2">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= $outlet->average_rating)
                                    <i class="fas fa-star fa-sm"></i>
                                @else
                                    <i class="far fa-star fa-sm"></i>
                                @endif
                            @endfor
                        </div>
                        <small class="text-muted">({{ $outlet->total_ratings }})</small>
                    </div>
                    
                    <!-- Address -->
                    <div class="mb-2">
                        <small class="text-muted">
                            <i class="fas fa-map-marker-alt me-1"></i>
                            {{ Str::limit($outlet->address, 80) }}
                        </small>
                    </div>
                    
                    <!-- Status -->
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="badge bg-{{ $outlet->is_open ? 'success' : 'danger' }}">
                            {{ $outlet->is_open ? 'Buka' : 'Tutup' }}
                        </span>
                        @if($outlet->is_verified)
                            <span class="badge bg-primary">
                                <i class="fas fa-check-circle me-1"></i>Terverifikasi
                            </span>
                        @endif
                    </div>
                </div>
                
                <div class="card-footer bg-transparent">
                    <div class="d-flex gap-2">
                        <a href="{{ route('outlets.show', $outlet) }}" class="btn btn-outline-success flex-fill">
                            <i class="fas fa-eye me-1"></i>Lihat
                        </a>
                        @auth
                            @if(Auth::user()->isPembeli())
                                <button type="button" class="btn btn-outline-warning" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#ratingModal{{ $outlet->id }}">
                                    <i class="fas fa-star me-1"></i>Rating
                                </button>
                            @endif
                        @endauth
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Rating Modal -->
        @auth
            @if(Auth::user()->isPembeli())
            <div class="modal fade" id="ratingModal{{ $outlet->id }}" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Beri Rating untuk {{ $outlet->name }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <form action="{{ route('outlets.rating', $outlet) }}" method="POST">
                            @csrf
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label class="form-label">Rating</label>
                                    <div class="rating-input">
                                        @for($i = 5; $i >= 1; $i--)
                                            <input type="radio" name="rating" value="{{ $i }}" id="star{{ $i }}{{ $outlet->id }}" required>
                                            <label for="star{{ $i }}{{ $outlet->id }}">
                                                <i class="far fa-star"></i>
                                            </label>
                                        @endfor
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="comment{{ $outlet->id }}" class="form-label">Komentar (Opsional)</label>
                                    <textarea class="form-control" name="comment" id="comment{{ $outlet->id }}" rows="3" 
                                              placeholder="Bagikan pengalaman Anda..."></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-primary">Kirim Rating</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @endif
        @endauth
        @endforeach
    </div>
    
    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-5">
        {{ $outlets->links() }}
    </div>
@elseif(isset($outlets))
    <div class="text-center py-5">
        <i class="fas fa-map-marker-alt fa-4x text-muted mb-4"></i>
        <h4 class="text-muted mb-3">Tidak ada outlet ditemukan</h4>
        <p class="text-muted mb-4">
            @if($latitude && $longitude)
                Tidak ada outlet dalam radius {{ $radius }} km dari lokasi Anda.
                Coba perbesar radius atau pindah ke lokasi lain.
            @else
                Masukkan koordinat lokasi untuk mencari outlet terdekat.
            @endif
        </p>
        <div class="d-flex gap-2 justify-content-center">
            <button type="button" class="btn btn-success" id="getLocationBtn2">
                <i class="fas fa-crosshairs me-2"></i>Dapatkan Lokasi
            </button>
            <a href="{{ route('search.outlets') }}" class="btn btn-outline-success">
                <i class="fas fa-search me-2"></i>Cari Outlet
            </a>
        </div>
    </div>
@endif

<!-- Location Tips -->
<div class="row mt-5">
    <div class="col-12">
        <div class="card border-0 bg-light">
            <div class="card-body">
                <h6 class="text-success mb-3">
                    <i class="fas fa-lightbulb me-2"></i>
                    Tips Pencarian Berdasarkan Lokasi
                </h6>
                <div class="row g-3">
                    <div class="col-md-3">
                        <div class="d-flex">
                            <i class="fas fa-crosshairs text-success me-2 mt-1"></i>
                            <div>
                                <strong>Gunakan GPS</strong>
                                <p class="text-muted small mb-0">Dapatkan lokasi secara otomatis</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="d-flex">
                            <i class="fas fa-ruler text-success me-2 mt-1"></i>
                            <div>
                                <strong>Atur Radius</strong>
                                <p class="text-muted small mb-0">Tentukan jarak maksimal</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="d-flex">
                            <i class="fas fa-tags text-success me-2 mt-1"></i>
                            <div>
                                <strong>Filter Kategori</strong>
                                <p class="text-muted small mb-0">Pilih jenis kuliner</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="d-flex">
                            <i class="fas fa-map text-success me-2 mt-1"></i>
                            <div>
                                <strong>Lihat Peta</strong>
                                <p class="text-muted small mb-0">Visualisasi lokasi outlet</p>
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
.outlet-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    border: none;
    border-radius: 15px;
}

.outlet-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.1) !important;
}

.rating-input {
    display: flex;
    flex-direction: row-reverse;
    justify-content: flex-end;
}

.rating-input input {
    display: none;
}

.rating-input label {
    cursor: pointer;
    font-size: 1.5rem;
    color: #ddd;
    margin: 0 2px;
}

.rating-input input:checked ~ label,
.rating-input label:hover,
.rating-input label:hover ~ label {
    color: #ffc107;
}

.card-footer {
    border-top: 1px solid #e9ecef;
}

.badge {
    font-size: 0.75rem;
}

.btn {
    border-radius: 8px;
}

.form-control, .form-select {
    border-radius: 8px;
}

#map {
    border-radius: 0 0 15px 15px;
}

.location-info {
    background: rgba(40, 167, 69, 0.1);
    border-radius: 10px;
    padding: 15px;
    margin-bottom: 20px;
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
                
                // Update map if exists
                if (typeof updateMap === 'function') {
                    updateMap(position.coords.latitude, position.coords.longitude);
                }
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
    const getLocationBtn2 = document.getElementById('getLocationBtn2');
    
    if (getLocationBtn) {
        getLocationBtn.addEventListener('click', getLocation);
    }
    
    if (getLocationBtn2) {
        getLocationBtn2.addEventListener('click', getLocation);
    }
    
    // Rating star interaction
    const ratingLabels = document.querySelectorAll('.rating-input label');
    
    ratingLabels.forEach(label => {
        label.addEventListener('mouseenter', function() {
            const stars = this.parentElement.querySelectorAll('label');
            const currentIndex = Array.from(stars).indexOf(this);
            
            stars.forEach((star, index) => {
                if (index >= currentIndex) {
                    star.style.color = '#ffc107';
                }
            });
        });
        
        label.addEventListener('mouseleave', function() {
            const stars = this.parentElement.querySelectorAll('label');
            const checkedInput = this.parentElement.querySelector('input:checked');
            
            stars.forEach(star => {
                if (!checkedInput) {
                    star.style.color = '#ddd';
                }
            });
        });
    });
});

// Simple map implementation (you can replace this with Google Maps or Leaflet)
function initMap() {
    const mapContainer = document.getElementById('map');
    if (mapContainer) {
        // Placeholder for map implementation
        mapContainer.innerHTML = `
            <div class="d-flex align-items-center justify-content-center h-100 bg-light">
                <div class="text-center">
                    <i class="fas fa-map fa-3x text-muted mb-3"></i>
                    <p class="text-muted">Peta akan ditampilkan di sini</p>
                    <small class="text-muted">Gunakan tombol "Dapatkan Lokasi" untuk memulai</small>
                </div>
            </div>
        `;
    }
}

// Initialize map when page loads
document.addEventListener('DOMContentLoaded', initMap);
</script>
@endpush
