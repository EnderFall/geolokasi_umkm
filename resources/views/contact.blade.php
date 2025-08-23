@extends('layouts.app')

@section('title', 'Hubungi Kami - Geolokasi UMKM Kuliner')

@section('content')
<!-- Hero Section -->
<div class="hero-section text-center py-5 mb-5">
    <div class="container">
        <h1 class="display-4 fw-bold text-success mb-3">
            <i class="fas fa-envelope me-3"></i>
            Hubungi Kami
        </h1>
        <p class="lead text-muted mb-4">
            Kami siap membantu Anda dengan pertanyaan dan dukungan teknis
        </p>
        <div class="row justify-content-center">
            <div class="col-md-8">
                <p class="text-muted">
                    Tim support kami tersedia untuk membantu UMKM dan pengguna platform 
                    dengan layanan yang responsif dan profesional.
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Contact Information -->
<div class="container mb-5">
    <div class="row g-4">
        <div class="col-lg-4">
            <div class="contact-card text-center p-4 h-100">
                <div class="contact-icon mb-3">
                    <i class="fas fa-envelope fa-2x text-success"></i>
                </div>
                <h5 class="fw-bold mb-3">Email</h5>
                <p class="text-muted mb-2">support@geolokasi-umkm.com</p>
                <p class="text-muted mb-0">info@geolokasi-umkm.com</p>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="contact-card text-center p-4 h-100">
                <div class="contact-icon mb-3">
                    <i class="fas fa-phone fa-2x text-primary"></i>
                </div>
                <h5 class="fw-bold mb-3">Telepon</h5>
                <p class="text-muted mb-2">+62 21 1234 5678</p>
                <p class="text-muted mb-0">+62 812 3456 7890</p>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="contact-card text-center p-4 h-100">
                <div class="contact-icon mb-3">
                    <i class="fas fa-clock fa-2x text-warning"></i>
                </div>
                <h5 class="fw-bold mb-3">Jam Kerja</h5>
                <p class="text-muted mb-2">Senin - Jumat: 08:00 - 17:00</p>
                <p class="text-muted mb-0">Sabtu: 09:00 - 15:00</p>
            </div>
        </div>
    </div>
</div>

<!-- Contact Form & Map -->
<div class="container mb-5">
    <div class="row g-4">
        <!-- Contact Form -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-paper-plane me-2"></i>
                        Kirim Pesan
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('contact.send') }}" method="POST">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="name" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6">
                                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       id="email" name="email" value="{{ old('email') }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6">
                                <label for="phone" class="form-label">Nomor Telepon</label>
                                <input type="tel" class="form-control @error('phone') is-invalid @enderror" 
                                       id="phone" name="phone" value="{{ old('phone') }}">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6">
                                <label for="subject" class="form-label">Subjek <span class="text-danger">*</span></label>
                                <select class="form-select @error('subject') is-invalid @enderror" 
                                        id="subject" name="subject" required>
                                    <option value="">Pilih subjek</option>
                                    <option value="general" {{ old('subject') == 'general' ? 'selected' : '' }}>
                                        Pertanyaan Umum
                                    </option>
                                    <option value="technical" {{ old('subject') == 'technical' ? 'selected' : '' }}>
                                        Dukungan Teknis
                                    </option>
                                    <option value="business" {{ old('subject') == 'business' ? 'selected' : '' }}>
                                        Kerjasama Bisnis
                                    </option>
                                    <option value="feedback" {{ old('subject') == 'feedback' ? 'selected' : '' }}>
                                        Saran & Feedback
                                    </option>
                                    <option value="complaint" {{ old('subject') == 'complaint' ? 'selected' : '' }}>
                                        Keluhan
                                    </option>
                                    <option value="other" {{ old('subject') == 'other' ? 'selected' : '' }}>
                                        Lainnya
                                    </option>
                                </select>
                                @error('subject')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-12">
                                <label for="message" class="form-label">Pesan <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('message') is-invalid @enderror" 
                                          id="message" name="message" rows="5" required 
                                          placeholder="Tulis pesan Anda di sini...">{{ old('message') }}</textarea>
                                @error('message')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-12">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="privacy" name="privacy" required>
                                    <label class="form-check-label" for="privacy">
                                        Saya setuju dengan <a href="#" class="text-success">kebijakan privasi</a> dan 
                                        <a href="#" class="text-success">ketentuan penggunaan</a>
                                    </label>
                                </div>
                            </div>
                            
                            <div class="col-12">
                                <button type="submit" class="btn btn-success btn-lg">
                                    <i class="fas fa-paper-plane me-2"></i>Kirim Pesan
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Contact Info & Map -->
        <div class="col-lg-4">
            <!-- Office Location -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-map-marker-alt me-2"></i>
                        Lokasi Kantor
                    </h6>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-3">
                        <strong>Geolokasi UMKM Kuliner</strong><br>
                        Jl. Sudirman No. 123<br>
                        Jakarta Pusat, 10220<br>
                        Indonesia
                    </p>
                    <a href="https://maps.google.com" target="_blank" class="btn btn-outline-success btn-sm">
                        <i class="fas fa-map me-2"></i>Lihat di Google Maps
                    </a>
                </div>
            </div>
            
            <!-- Social Media -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-share-alt me-2"></i>
                        Media Sosial
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="#" class="btn btn-outline-primary btn-sm">
                            <i class="fab fa-facebook me-2"></i>Facebook
                        </a>
                        <a href="#" class="btn btn-outline-info btn-sm">
                            <i class="fab fa-twitter me-2"></i>Twitter
                        </a>
                        <a href="#" class="btn btn-outline-danger btn-sm">
                            <i class="fab fa-instagram me-2"></i>Instagram
                        </a>
                        <a href="#" class="btn btn-outline-dark btn-sm">
                            <i class="fab fa-youtube me-2"></i>YouTube
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Quick Contact -->
            <div class="card border-0 shadow-sm">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-bolt me-2"></i>
                        Kontak Cepat
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="mailto:support@geolokasi-umkm.com" class="btn btn-outline-success btn-sm">
                            <i class="fas fa-envelope me-2"></i>Email Support
                        </a>
                        <a href="tel:+622112345678" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-phone me-2"></i>Telepon
                        </a>
                        <a href="https://wa.me/6281234567890" target="_blank" class="btn btn-outline-success btn-sm">
                            <i class="fab fa-whatsapp me-2"></i>WhatsApp
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Map Container -->
<div class="container mb-5">
    <div class="card border-0 shadow-sm">
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
</div>

<!-- FAQ Section -->
<div class="container mb-5">
    <div class="text-center mb-5">
        <h2 class="fw-bold text-success mb-3">
            <i class="fas fa-question-circle me-2"></i>
            Pertanyaan yang Sering Diajukan
        </h2>
        <p class="text-muted">Temukan jawaban untuk pertanyaan umum</p>
    </div>
    
    <div class="row g-4">
        <div class="col-lg-6">
            <div class="faq-item">
                <h6 class="fw-bold text-success mb-2">
                    <i class="fas fa-store me-2"></i>
                    Bagaimana cara mendaftar sebagai UMKM?
                </h6>
                <p class="text-muted small">
                    Daftar akun baru, pilih role "Penjual/Outlet", lengkapi profil, dan verifikasi data outlet Anda. 
                    Tim kami akan memverifikasi dalam 1-2 hari kerja.
                </p>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="faq-item">
                <h6 class="fw-bold text-success mb-2">
                    <i class="fas fa-credit-card me-2"></i>
                    Apakah ada biaya untuk menggunakan platform?
                </h6>
                <p class="text-muted small">
                    Platform ini sepenuhnya gratis untuk UMKM dan pelanggan. Tidak ada biaya pendaftaran, 
                    komisi, atau biaya tersembunyi lainnya.
                </p>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="faq-item">
                <h6 class="fw-bold text-success mb-2">
                    <i class="fas fa-map-marker-alt me-2"></i>
                    Bagaimana sistem geolokasi bekerja?
                </h6>
                <p class="text-muted small">
                    Sistem menggunakan GPS untuk menentukan lokasi pengguna dan mencari outlet terdekat 
                    berdasarkan radius yang dapat disesuaikan.
                </p>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="faq-item">
                <h6 class="fw-bold text-success mb-2">
                    <i class="fas fa-star me-2"></i>
                    Bagaimana sistem rating dan review?
                </h6>
                <p class="text-muted small">
                    Pelanggan dapat memberikan rating 1-5 bintang dan komentar untuk outlet atau menu. 
                    Rating akan ditampilkan secara transparan untuk semua pengguna.
                </p>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="faq-item">
                <h6 class="fw-bold text-success mb-2">
                    <i class="fas fa-shield-alt me-2"></i>
                    Apakah data outlet aman?
                </h6>
                <p class="text-muted small">
                    Ya, kami menggunakan enkripsi data dan protokol keamanan yang ketat. 
                    Data outlet tidak akan dibagikan kepada pihak ketiga tanpa izin.
                </p>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="faq-item">
                <h6 class="fw-bold text-success mb-2">
                    <i class="fas fa-headset me-2"></i>
                    Berapa lama waktu respons support?
                </h6>
                <p class="text-muted small">
                    Tim support kami akan merespons dalam waktu maksimal 24 jam untuk pertanyaan umum 
                    dan 4 jam untuk masalah teknis yang mendesak.
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Support Hours -->
<div class="container mb-5">
    <div class="support-hours text-center py-5">
        <h3 class="fw-bold text-white mb-4">
            <i class="fas fa-clock me-2"></i>
            Jam Dukungan Teknis
        </h3>
        <div class="row g-4">
            <div class="col-md-3">
                <div class="support-time">
                    <h5 class="text-warning">Senin - Jumat</h5>
                    <p class="text-light mb-0">08:00 - 17:00 WIB</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="support-time">
                    <h5 class="text-warning">Sabtu</h5>
                    <p class="text-light mb-0">09:00 - 15:00 WIB</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="support-time">
                    <h5 class="text-warning">Minggu</h5>
                    <p class="text-light mb-0">10:00 - 14:00 WIB</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="support-time">
                    <h5 class="text-warning">24/7</h5>
                    <p class="text-light mb-0">Email Support</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.hero-section {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    color: white;
    border-radius: 0 0 50px 50px;
}

.contact-card {
    background: white;
    border-radius: 15px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.08);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.contact-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.15);
}

.contact-icon {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: rgba(40, 167, 69, 0.1);
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
}

.faq-item {
    background: white;
    padding: 1.5rem;
    border-radius: 15px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.08);
    transition: transform 0.3s ease;
}

.faq-item:hover {
    transform: translateY(-3px);
}

.support-hours {
    background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
    border-radius: 20px;
}

.support-time {
    padding: 1rem;
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

.btn {
    border-radius: 8px;
}

.form-control:focus, .form-select:focus {
    border-color: #28a745;
    box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
}

#map {
    border-radius: 0 0 15px 15px;
}

@media (max-width: 768px) {
    .hero-section {
        border-radius: 0 0 30px 30px;
    }
    
    .support-hours {
        border-radius: 15px;
    }
}
</style>
@endpush

@push('scripts')
<script>
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
                    <small class="text-muted">Lokasi: Jl. Sudirman No. 123, Jakarta Pusat</small>
                </div>
            </div>
        `;
    }
}

// Form validation
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function(e) {
            const name = document.getElementById('name').value.trim();
            const email = document.getElementById('email').value.trim();
            const subject = document.getElementById('subject').value;
            const message = document.getElementById('message').value.trim();
            const privacy = document.getElementById('privacy').checked;
            
            if (name === '') {
                e.preventDefault();
                showAlert('Nama lengkap harus diisi.', 'danger');
                return false;
            }
            
            if (email === '') {
                e.preventDefault();
                showAlert('Email harus diisi.', 'danger');
                return false;
            }
            
            if (subject === '') {
                e.preventDefault();
                showAlert('Pilih subjek pesan.', 'danger');
                return false;
            }
            
            if (message === '') {
                e.preventDefault();
                showAlert('Pesan harus diisi.', 'danger');
                return false;
            }
            
            if (!privacy) {
                e.preventDefault();
                showAlert('Anda harus menyetujui kebijakan privasi.', 'danger');
                return false;
            }
        });
    }
    
    // Initialize map
    initMap();
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
