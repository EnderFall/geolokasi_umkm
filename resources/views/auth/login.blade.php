@extends('layouts.app')

@section('title', 'Masuk - Geolokasi UMKM Kuliner')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
        <div class="card shadow-lg border-0">
            <div class="card-header bg-primary text-white text-center py-4">
                <h4 class="mb-0">
                    <i class="fas fa-sign-in-alt me-2"></i>
                    Masuk ke Akun
                </h4>
                <p class="mb-0 mt-2">Akses dashboard dan fitur lengkap</p>
            </div>
            <div class="card-body p-4">
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="email" class="form-label">
                            <i class="fas fa-envelope me-2"></i>Email
                        </label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                               id="email" name="email" value="{{ old('email') }}" required autofocus>
                        @error('email')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">
                            <i class="fas fa-lock me-2"></i>Password
                        </label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" 
                               id="password" name="password" required>
                        @error('password')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="remember" name="remember">
                        <label class="form-check-label" for="remember">
                            Ingat saya
                        </label>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-sign-in-alt me-2"></i>
                            Masuk
                        </button>
                    </div>
                </form>

                <hr class="my-4">

                <div class="text-center">
                    <p class="mb-2">Belum punya akun?</p>
                    <a href="{{ route('register') }}" class="btn btn-outline-primary">
                        <i class="fas fa-user-plus me-2"></i>
                        Daftar Sekarang
                    </a>
                </div>
            </div>
        </div>

        <!-- Features -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card border-0 bg-light">
                    <div class="card-body text-center">
                        <h6 class="text-muted mb-3">
                            <i class="fas fa-star me-2 text-warning"></i>
                            Fitur Setelah Login
                        </h6>
                        <div class="row g-2">
                            <div class="col-4">
                                <small class="text-muted">
                                    <i class="fas fa-tachometer-alt me-1"></i>Dashboard
                                </small>
                            </div>
                            <div class="col-4">
                                <small class="text-muted">
                                    <i class="fas fa-store me-1"></i>Kelola Outlet
                                </small>
                            </div>
                            <div class="col-4">
                                <small class="text-muted">
                                    <i class="fas fa-utensils me-1"></i>Kelola Menu
                                </small>
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

.form-control {
    border-radius: 10px;
    border: 2px solid #e9ecef;
    transition: border-color 0.3s ease;
}

.form-control:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
}

.form-check-input:checked {
    background-color: #0d6efd;
    border-color: #0d6efd;
}
</style>
@endpush
