@extends('layouts.app')

@section('title', 'Tentang Kami - Geolokasi UMKM Kuliner')

@section('content')
<!-- Hero Section -->
<div class="hero-section text-center py-5 mb-5">
    <div class="container">
        <h1 class="display-4 fw-bold text-success mb-3">
            <i class="fas fa-store me-3"></i>
            Tentang Geolokasi UMKM Kuliner
        </h1>
        <p class="lead text-muted mb-4">
            Platform digital yang menghubungkan UMKM kuliner lokal dengan pelanggan setia
        </p>
        <div class="row justify-content-center">
            <div class="col-md-8">
                <p class="text-muted">
                    Kami berkomitmen untuk mendukung pertumbuhan UMKM kuliner Indonesia dengan menyediakan 
                    platform yang mudah digunakan, aman, dan terpercaya.
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Mission & Vision -->
<div class="container mb-5">
    <div class="row g-4">
        <div class="col-lg-6">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body text-center p-5">
                    <div class="mission-icon mb-4">
                        <i class="fas fa-bullseye fa-3x text-success"></i>
                    </div>
                    <h4 class="card-title mb-3">Misi Kami</h4>
                    <p class="card-text text-muted">
                        Memfasilitasi pertumbuhan UMKM kuliner lokal dengan menyediakan platform digital 
                        yang inovatif, mudah diakses, dan mendukung pengembangan bisnis yang berkelanjutan.
                    </p>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body text-center p-5">
                    <div class="vision-icon mb-4">
                        <i class="fas fa-eye fa-3x text-primary"></i>
                    </div>
                    <h4 class="card-title mb-3">Visi Kami</h4>
                    <p class="card-text text-muted">
                        Menjadi platform terdepan dalam digitalisasi UMKM kuliner Indonesia, 
                        mendorong inovasi, dan berkontribusi pada pertumbuhan ekonomi lokal.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- What We Do -->
<div class="container mb-5">
    <div class="text-center mb-5">
        <h2 class="fw-bold text-success mb-3">
            <i class="fas fa-cogs me-2"></i>
            Apa yang Kami Lakukan
        </h2>
        <p class="text-muted">Platform komprehensif untuk semua kebutuhan UMKM kuliner</p>
    </div>
    
    <div class="row g-4">
        <div class="col-md-4">
            <div class="feature-card text-center p-4">
                <div class="feature-icon mb-3">
                    <i class="fas fa-map-marker-alt fa-2x text-success"></i>
                </div>
                <h5 class="fw-bold mb-3">Geolokasi Pintar</h5>
                <p class="text-muted">
                    Sistem pencarian berdasarkan lokasi yang memudahkan pelanggan menemukan 
                    outlet kuliner terdekat dengan cepat dan akurat.
                </p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="feature-card text-center p-4">
                <div class="feature-icon mb-3">
                    <i class="fas fa-star fa-2x text-warning"></i>
                </div>
                <h5 class="fw-bold mb-3">Sistem Rating & Review</h5>
                <p class="text-muted">
                    Platform review yang transparan untuk membantu pelanggan membuat keputusan 
                    dan mendorong UMKM meningkatkan kualitas layanan.
                </p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="feature-card text-center p-4">
                <div class="feature-icon mb-3">
                    <i class="fas fa-mobile-alt fa-2x text-info"></i>
                </div>
                <h5 class="fw-bold mb-3">Akses Mudah</h5>
                <p class="text-muted">
                    Interface yang responsif dan user-friendly, dapat diakses dari berbagai 
                    perangkat untuk pengalaman yang optimal.
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Benefits -->
<div class="container mb-5">
    <div class="text-center mb-5">
        <h2 class="fw-bold text-success mb-3">
            <i class="fas fa-gift me-2"></i>
            Manfaat Platform
        </h2>
        <p class="text-muted">Keuntungan yang didapatkan oleh semua pihak</p>
    </div>
    
    <div class="row g-4">
        <div class="col-lg-6">
            <h5 class="text-success mb-4">
                <i class="fas fa-store me-2"></i>
                Untuk UMKM Kuliner
            </h5>
            <ul class="benefits-list">
                <li>
                    <i class="fas fa-check-circle text-success me-2"></i>
                    <strong>Jangkauan Luas:</strong> Pelanggan dapat menemukan outlet Anda dengan mudah
                </li>
                <li>
                    <i class="fas fa-check-circle text-success me-2"></i>
                    <strong>Promosi Gratis:</strong> Platform gratis untuk mempromosikan bisnis Anda
                </li>
                <li>
                    <i class="fas fa-check-circle text-success me-2"></i>
                    <strong>Feedback Langsung:</strong> Mendapatkan review dan rating dari pelanggan
                </li>
                <li>
                    <i class="fas fa-check-circle text-success me-2"></i>
                    <strong>Analisis Data:</strong> Melihat statistik dan performa outlet Anda
                </li>
                <li>
                    <i class="fas fa-check-circle text-success me-2"></i>
                    <strong>Manajemen Menu:</strong> Mudah mengelola dan update menu secara real-time
                </li>
            </ul>
        </div>
        <div class="col-lg-6">
            <h5 class="text-primary mb-4">
                <i class="fas fa-users me-2"></i>
                Untuk Pelanggan
            </h5>
            <ul class="benefits-list">
                <li>
                    <i class="fas fa-check-circle text-primary me-2"></i>
                    <strong>Pencarian Cepat:</strong> Temukan outlet kuliner terdekat dengan mudah
                </li>
                <li>
                    <i class="fas fa-check-circle text-primary me-2"></i>
                    <strong>Informasi Lengkap:</strong> Lihat menu, harga, dan jam operasional
                </li>
                <li>
                    <i class="fas fa-check-circle text-primary me-2"></i>
                    <strong>Review Terpercaya:</strong> Baca review dari pelanggan lain
                </li>
                <li>
                    <i class="fas fa-check-circle text-primary me-2"></i>
                    <strong>Lokasi Akurat:</strong> Navigasi yang tepat ke outlet pilihan
                </li>
                <li>
                    <i class="fas fa-check-circle text-primary me-2"></i>
                    <strong>Pengalaman Baru:</strong> Jelajahi kuliner lokal yang belum pernah dicoba
                </li>
            </ul>
        </div>
    </div>
</div>

<!-- Statistics -->
<div class="container mb-5">
    <div class="stats-section text-center py-5">
        <h2 class="fw-bold text-white mb-5">
            <i class="fas fa-chart-line me-2"></i>
            Platform dalam Angka
        </h2>
        <div class="row g-4">
            <div class="col-md-3 col-sm-6">
                <div class="stat-item">
                    <div class="stat-number text-warning mb-2">
                        <i class="fas fa-store fa-2x"></i>
                    </div>
                    <h4 class="text-white fw-bold">100+</h4>
                    <p class="text-light mb-0">UMKM Terdaftar</p>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="stat-item">
                    <div class="stat-number text-warning mb-2">
                        <i class="fas fa-utensils fa-2x"></i>
                    </div>
                    <h4 class="text-white fw-bold">500+</h4>
                    <p class="text-light mb-0">Menu Tersedia</p>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="stat-item">
                    <div class="stat-number text-warning mb-2">
                        <i class="fas fa-users fa-2x"></i>
                    </div>
                    <h4 class="text-white fw-bold">1000+</h4>
                    <p class="text-light mb-0">Pengguna Aktif</p>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="stat-item">
                    <div class="stat-number text-warning mb-2">
                        <i class="fas fa-star fa-2x"></i>
                    </div>
                    <h4 class="text-white fw-bold">4.5</h4>
                    <p class="text-light mb-0">Rating Rata-rata</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Technology Stack -->
<div class="container mb-5">
    <div class="text-center mb-5">
        <h2 class="fw-bold text-success mb-3">
            <i class="fas fa-code me-2"></i>
            Teknologi yang Digunakan
        </h2>
        <p class="text-muted">Platform dibangun dengan teknologi modern dan terpercaya</p>
    </div>
    
    <div class="row g-4">
        <div class="col-md-3 col-sm-6">
            <div class="tech-card text-center p-4">
                <div class="tech-icon mb-3">
                    <i class="fab fa-laravel fa-3x text-danger"></i>
                </div>
                <h6 class="fw-bold">Laravel Framework</h6>
                <small class="text-muted">Backend yang robust dan scalable</small>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="tech-card text-center p-4">
                <div class="tech-icon mb-3">
                    <i class="fab fa-php fa-3x text-primary"></i>
                </div>
                <h6 class="fw-bold">PHP Native</h6>
                <small class="text-muted">Bahasa pemrograman yang handal</small>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="tech-card text-center p-4">
                <div class="tech-icon mb-3">
                    <i class="fab fa-bootstrap fa-3x text-primary"></i>
                </div>
                <h6 class="fw-bold">Bootstrap 5</h6>
                <small class="text-muted">Framework CSS yang responsif</small>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="tech-card text-center p-4">
                <div class="tech-icon mb-3">
                    <i class="fas fa-database fa-3x text-success"></i>
                </div>
                <h6 class="fw-bold">MySQL</h6>
                <small class="text-muted">Database yang reliable</small>
            </div>
        </div>
    </div>
</div>

<!-- Team -->
<div class="container mb-5">
    <div class="text-center mb-5">
        <h2 class="fw-bold text-success mb-3">
            <i class="fas fa-users me-2"></i>
            Tim Pengembang
        </h2>
        <p class="text-muted">Dedikasi kami untuk UMKM kuliner Indonesia</p>
    </div>
    
    <div class="row g-4">
        <div class="col-lg-4">
            <div class="team-card text-center">
                <div class="team-avatar mb-3">
                    <i class="fas fa-user-tie fa-3x text-success"></i>
                </div>
                <h5 class="fw-bold">Tim Pengembang</h5>
                <p class="text-muted">Full-Stack Developer</p>
                <p class="small text-muted">
                    Berpengalaman dalam pengembangan aplikasi web dan mobile 
                    dengan fokus pada user experience yang optimal.
                </p>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="team-card text-center">
                <div class="team-avatar mb-3">
                    <i class="fas fa-palette fa-3x text-primary"></i>
                </div>
                <h5 class="fw-bold">Tim Desain</h5>
                <p class="text-muted">UI/UX Designer</p>
                <p class="small text-muted">
                    Menciptakan interface yang menarik, intuitif, dan mudah digunakan 
                    untuk semua kalangan pengguna.
                </p>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="team-card text-center">
                <div class="team-avatar mb-3">
                    <i class="fas fa-cogs fa-3x text-warning"></i>
                </div>
                <h5 class="fw-bold">Tim Support</h5>
                <p class="text-muted">Customer Service</p>
                <p class="small text-muted">
                    Memberikan dukungan teknis dan bantuan kepada UMKM dan pengguna 
                    platform dengan responsif dan profesional.
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Call to Action -->
<div class="container mb-5">
    <div class="cta-section text-center py-5">
        <h2 class="fw-bold text-white mb-3">
            <i class="fas fa-rocket me-2"></i>
            Bergabung Bersama Kami
        </h2>
        <p class="text-light mb-4">
            Mari wujudkan digitalisasi UMKM kuliner Indonesia yang lebih maju dan berkembang
        </p>
        <div class="d-flex gap-3 justify-content-center flex-wrap">
            <a href="{{ route('register') }}" class="btn btn-warning btn-lg">
                <i class="fas fa-user-plus me-2"></i>Daftar Sekarang
            </a>
            <a href="{{ route('contact') }}" class="btn btn-outline-light btn-lg">
                <i class="fas fa-envelope me-2"></i>Hubungi Kami
            </a>
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

.mission-icon, .vision-icon {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: rgba(40, 167, 69, 0.1);
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
}

.feature-card {
    background: white;
    border-radius: 15px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.08);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    height: 100%;
}

.feature-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.15);
}

.feature-icon {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: rgba(40, 167, 69, 0.1);
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
}

.benefits-list {
    list-style: none;
    padding: 0;
}

.benefits-list li {
    margin-bottom: 1rem;
    padding-left: 0;
}

.stats-section {
    background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
    border-radius: 20px;
}

.stat-item {
    padding: 1rem;
}

.tech-card {
    background: white;
    border-radius: 15px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.08);
    transition: transform 0.3s ease;
    height: 100%;
}

.tech-card:hover {
    transform: translateY(-5px);
}

.tech-icon {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: rgba(40, 167, 69, 0.1);
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
}

.team-card {
    padding: 2rem;
    background: white;
    border-radius: 15px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.08);
    transition: transform 0.3s ease;
    height: 100%;
}

.team-card:hover {
    transform: translateY(-5px);
}

.team-avatar {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: rgba(40, 167, 69, 0.1);
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
}

.cta-section {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    border-radius: 20px;
}

.card {
    border: none;
    border-radius: 15px;
}

.btn {
    border-radius: 8px;
}

@media (max-width: 768px) {
    .hero-section {
        border-radius: 0 0 30px 30px;
    }
    
    .cta-section {
        border-radius: 15px;
    }
}
</style>
@endpush
