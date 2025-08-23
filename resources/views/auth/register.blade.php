@extends('layouts.app')

@section('title', 'Daftar - Geolokasi UMKM Kuliner')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8 col-lg-7">
        <div class="card shadow-lg border-0">
            <div class="card-header bg-success text-white text-center py-4">
                <h4 class="mb-0">
                    <i class="fas fa-user-plus me-2"></i>
                    Daftar Akun Baru
                </h4>
                <p class="mb-0 mt-2">Bergabung dengan platform UMKM kuliner terbaik</p>
            </div>
            <div class="card-body p-4">
                <form method="POST" action="{{ route('register') }}">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name" class="form-label">
                                    <i class="fas fa-user me-2"></i>Nama Lengkap
                                </label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="email" class="form-label">
                                    <i class="fas fa-envelope me-2"></i>Email
                                </label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       id="email" name="email" value="{{ old('email') }}" required>
                                @error('email')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="phone" class="form-label">
                                    <i class="fas fa-phone me-2"></i>Nomor Telepon
                                </label>
                                <input type="tel" class="form-control @error('phone') is-invalid @enderror" 
                                       id="phone" name="phone" value="{{ old('phone') }}" required>
                                @error('phone')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="role" class="form-label">
                                    <i class="fas fa-users me-2"></i>Peran
                                </label>
                                <select class="form-select @error('role') is-invalid @enderror" 
                                        id="role" name="role" required>
                                    <option value="">Pilih Peran</option>
                                    <option value="pembeli" {{ old('role') == 'pembeli' ? 'selected' : '' }}>
                                        Pembeli - Pesan makanan dari outlet
                                    </option>
                                    <option value="penjual" {{ old('role') == 'penjual' ? 'selected' : '' }}>
                                        Penjual - Kelola outlet dan menu
                                    </option>
                                </select>
                                @error('role')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="address" class="form-label">
                            <i class="fas fa-map-marker-alt me-2"></i>Alamat
                        </label>
                        <textarea class="form-control @error('address') is-invalid @enderror" 
                                  id="address" name="address" rows="3" required>{{ old('address') }}</textarea>
                        @error('address')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6">
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
                                <small class="form-text text-muted">Minimal 8 karakter</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label">
                                    <i class="fas fa-lock me-2"></i>Konfirmasi Password
                                </label>
                                <input type="password" class="form-control" 
                                       id="password_confirmation" name="password_confirmation" required>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="terms" name="terms" required>
                        <label class="form-check-label" for="terms">
                            Saya setuju dengan <a href="#" class="text-decoration-none">Syarat & Ketentuan</a> dan 
                            <a href="#" class="text-decoration-none">Kebijakan Privasi</a>
                        </label>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-success btn-lg">
                            <i class="fas fa-user-plus me-2"></i>
                            Daftar Sekarang
                        </button>
                    </div>
                </form>

                <hr class="my-4">

                <div class="text-center">
                    <p class="mb-2">Sudah punya akun?</p>
                    <a href="{{ route('login') }}" class="btn btn-outline-success">
                        <i class="fas fa-sign-in-alt me-2"></i>
                        Masuk Sekarang
                    </a>
                </div>
            </div>
        </div>

        <!-- Benefits -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card border-0 bg-light">
                    <div class="card-body">
                        <h6 class="text-center text-muted mb-3">
                            <i class="fas fa-gift me-2 text-success"></i>
                            Keuntungan Bergabung
                        </h6>
                        <div class="row g-3">
                            <div class="col-md-4 text-center">
                                <div class="benefit-item">
                                    <i class="fas fa-store fa-2x text-primary mb-2"></i>
                                    <h6 class="mb-1">Kelola Outlet</h6>
                                    <small class="text-muted">Untuk penjual</small>
                                </div>
                            </div>
                            <div class="col-md-4 text-center">
                                <div class="benefit-item">
                                    <i class="fas fa-utensils fa-2x text-success mb-2"></i>
                                    <h6 class="mb-1">Kelola Menu</h6>
                                    <small class="text-muted">Untuk penjual</small>
                                </div>
                            </div>
                            <div class="col-md-4 text-center">
                                <div class="benefit-item">
                                    <i class="fas fa-shopping-cart fa-2x text-warning mb-2"></i>
                                    <h6 class="mb-1">Pesan Makanan</h6>
                                    <small class="text-muted">Untuk pembeli</small>
                                </div>
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

.benefit-item {
    padding: 1rem;
    border-radius: 10px;
    transition: transform 0.3s ease;
}

.benefit-item:hover {
    transform: translateY(-5px);
}
</style>
@endpush
