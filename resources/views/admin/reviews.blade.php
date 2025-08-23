@extends('layouts.app')

@section('title', 'Admin - Manajemen Review')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">
                <i class="fas fa-star me-2 text-warning"></i>
                Manajemen Review
            </h2>
            <p class="text-muted mb-0">Kelola dan moderasi semua review dalam sistem</p>
        </div>
        
        <div class="d-flex gap-2">
            <a href="{{ route('reviews.pending') }}" class="btn btn-warning">
                <i class="fas fa-clock me-2"></i>Review Pending
                @if($pendingCount > 0)
                    <span class="badge bg-light text-dark ms-1">{{ $pendingCount }}</span>
                @endif
            </a>
            <a href="{{ route('reviews.export') }}" class="btn btn-success">
                <i class="fas fa-download me-2"></i>Export Data
            </a>
        </div>
    </div>
    
    <!-- Statistics Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-star fa-2x"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h4 class="mb-1">{{ $totalReviews }}</h4>
                            <small>Total Review</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-check-circle fa-2x"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h4 class="mb-1">{{ $verifiedReviews }}</h4>
                            <small>Review Terverifikasi</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-clock fa-2x"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h4 class="mb-1">{{ $pendingReviews }}</h4>
                            <small>Review Pending</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-flag fa-2x"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h4 class="mb-1">{{ $reportedReviews }}</h4>
                            <small>Review Dilaporkan</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Quick Actions -->
    <div class="card mb-4">
        <div class="card-header">
            <h6 class="mb-0">
                <i class="fas fa-bolt me-2 text-warning"></i>
                Aksi Cepat
            </h6>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-3">
                    <button type="button" class="btn btn-outline-success w-100" id="verifyAllPending">
                        <i class="fas fa-check-double me-2"></i>Verifikasi Semua Pending
                    </button>
                </div>
                <div class="col-md-3">
                    <button type="button" class="btn btn-outline-warning w-100" id="moderateReviews">
                        <i class="fas fa-shield-alt me-2"></i>Moderasi Otomatis
                    </button>
                </div>
                <div class="col-md-3">
                    <button type="button" class="btn btn-outline-info w-100" id="generateReport">
                        <i class="fas fa-chart-bar me-2"></i>Generate Laporan
                    </button>
                </div>
                <div class="col-md-3">
                    <button type="button" class="btn btn-outline-danger w-100" id="bulkDelete">
                        <i class="fas fa-trash me-2"></i>Hapus Massal
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('admin.reviews.index') }}" method="GET" class="row g-3">
                <div class="col-md-3">
                    <label for="search" class="form-label">Cari Review</label>
                    <input type="text" 
                           class="form-control" 
                           id="search" 
                           name="search" 
                           value="{{ request('search') }}" 
                           placeholder="Cari judul, komentar, atau nama user...">
                </div>
                
                <div class="col-md-2">
                    <label for="rating" class="form-label">Rating</label>
                    <select class="form-select" id="rating" name="rating">
                        <option value="">Semua Rating</option>
                        @for($i = 5; $i >= 1; $i--)
                            <option value="{{ $i }}" {{ request('rating') == $i ? 'selected' : '' }}>
                                {{ $i }} Bintang
                            </option>
                        @endfor
                    </select>
                </div>
                
                <div class="col-md-2">
                    <label for="type" class="form-label">Tipe Review</label>
                    <select class="form-select" id="type" name="type">
                        <option value="">Semua Tipe</option>
                        <option value="outlet" {{ request('type') == 'outlet' ? 'selected' : '' }}>Outlet</option>
                        <option value="menu" {{ request('type') == 'menu' ? 'selected' : '' }}>Menu</option>
                    </select>
                </div>
                
                <div class="col-md-2">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select" id="status" name="status">
                        <option value="">Semua Status</option>
                        <option value="verified" {{ request('status') == 'verified' ? 'selected' : '' }}>Terverifikasi</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="reported" {{ request('status') == 'reported' ? 'selected' : '' }}>Dilaporkan</option>
                    </select>
                </div>
                
                <div class="col-md-3">
                    <label class="form-label">&nbsp;</label>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search me-2"></i>Cari
                        </button>
                        <a href="{{ route('admin.reviews.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times me-2"></i>Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Reviews Management Table -->
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h6 class="mb-0">
                    <i class="fas fa-table me-2 text-info"></i>
                    Daftar Review ({{ $reviews->total() }})
                </h6>
                
                <div class="d-flex gap-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="selectAll">
                        <label class="form-check-label" for="selectAll">
                            Pilih Semua
                        </label>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card-body p-0">
            @if($reviews->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th width="50">
                                    <input type="checkbox" class="form-check-input" id="selectAllHeader">
                                </th>
                                <th>User</th>
                                <th>Item</th>
                                <th>Rating</th>
                                <th>Komentar</th>
                                <th>Status</th>
                                <th>Tanggal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($reviews as $review)
                                <tr>
                                    <td>
                                        <input type="checkbox" class="form-check-input review-checkbox" value="{{ $review->id }}">
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($review->user->profile_photo_url)
                                                <img src="{{ $review->user->profile_photo_url }}" 
                                                     alt="{{ $review->user->name }}" 
                                                     class="rounded-circle me-2" 
                                                     style="width: 32px; height: 32px;">
                                            @else
                                                <div class="bg-secondary rounded-circle me-2 d-flex align-items-center justify-content-center" 
                                                     style="width: 32px; height: 32px;">
                                                    <i class="fas fa-user text-white"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <div class="fw-bold">{{ $review->user->name }}</div>
                                                <small class="text-muted">{{ $review->user->email }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($review->reviewable_type === 'App\Models\Outlet')
                                                <i class="fas fa-store text-warning me-2"></i>
                                            @else
                                                <i class="fas fa-utensils text-success me-2"></i>
                                            @endif
                                            <div>
                                                <div class="fw-bold">{{ $review->reviewable->name }}</div>
                                                <small class="text-muted">
                                                    {{ $review->reviewable_type === 'App\Models\Outlet' ? 'Outlet' : 'Menu' }}
                                                </small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="text-warning me-2">
                                                @for($i = 1; $i <= 5; $i++)
                                                    @if($i <= $review->rating)
                                                        <i class="fas fa-star"></i>
                                                    @else
                                                        <i class="far fa-star"></i>
                                                    @endif
                                                @endfor
                                            </div>
                                            <span class="badge bg-primary">{{ $review->rating }}/5</span>
                                        </div>
                                    </td>
                                    <td>
                                        @if($review->comment)
                                            <div class="text-truncate" style="max-width: 200px;" title="{{ $review->comment }}">
                                                {{ $review->comment }}
                                            </div>
                                        @else
                                            <span class="text-muted">Tidak ada komentar</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($review->is_verified)
                                            <span class="badge bg-success">
                                                <i class="fas fa-check-circle me-1"></i>Terverifikasi
                                            </span>
                                        @elseif($review->is_reported)
                                            <span class="badge bg-danger">
                                                <i class="fas fa-flag me-1"></i>Dilaporkan
                                            </span>
                                        @else
                                            <span class="badge bg-warning">
                                                <i class="fas fa-clock me-1"></i>Pending
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <div>
                                            <div class="fw-bold">{{ $review->created_at->format('d/m/Y') }}</div>
                                            <small class="text-muted">{{ $review->created_at->format('H:i') }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('reviews.show', $review) }}" 
                                               class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            
                                            <form action="{{ route('reviews.toggle-verification', $review) }}" 
                                                  method="POST" 
                                                  class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-sm btn-outline-success">
                                                    @if($review->is_verified)
                                                        <i class="fas fa-times"></i>
                                                    @else
                                                        <i class="fas fa-check"></i>
                                                    @endif
                                                </button>
                                            </form>
                                            
                                            <form action="{{ route('reviews.destroy', $review) }}" 
                                                  method="POST" 
                                                  class="d-inline"
                                                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus review ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div class="d-flex justify-content-center p-3">
                    {{ $reviews->appends(request()->query())->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-star fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">Belum ada review</h5>
                    <p class="text-muted">Sistem belum memiliki review apapun.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Bulk Actions Modal -->
<div class="modal fade" id="bulkActionsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Aksi Massal</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Pilih aksi yang akan dilakukan untuk review yang dipilih:</p>
                <div class="d-grid gap-2">
                    <button type="button" class="btn btn-success" id="bulkVerify">
                        <i class="fas fa-check me-2"></i>Verifikasi Semua
                    </button>
                    <button type="button" class="btn btn-warning" id="bulkUnverify">
                        <i class="fas fa-times me-2"></i>Batalkan Verifikasi
                    </button>
                    <button type="button" class="btn btn-danger" id="bulkDeleteConfirm">
                        <i class="fas fa-trash me-2"></i>Hapus Semua
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-submit form when filters change
    const filterInputs = document.querySelectorAll('#rating, #type, #status');
    filterInputs.forEach(input => {
        input.addEventListener('change', function() {
            this.closest('form').submit();
        });
    });
    
    // Select all functionality
    const selectAllHeader = document.getElementById('selectAllHeader');
    const selectAll = document.getElementById('selectAll');
    const reviewCheckboxes = document.querySelectorAll('.review-checkbox');
    
    function updateSelectAll() {
        const checkedCount = document.querySelectorAll('.review-checkbox:checked').length;
        const totalCount = reviewCheckboxes.length;
        
        selectAllHeader.checked = checkedCount === totalCount;
        selectAll.checked = checkedCount === totalCount;
    }
    
    selectAllHeader.addEventListener('change', function() {
        reviewCheckboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
        updateSelectAll();
    });
    
    selectAll.addEventListener('change', function() {
        reviewCheckboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
        updateSelectAll();
    });
    
    reviewCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateSelectAll);
    });
    
    // Quick actions
    document.getElementById('verifyAllPending').addEventListener('click', function() {
        if (confirm('Verifikasi semua review pending?')) {
            // Implementation for bulk verification
            console.log('Bulk verification requested');
        }
    });
    
    document.getElementById('moderateReviews').addEventListener('click', function() {
        if (confirm('Jalankan moderasi otomatis?')) {
            // Implementation for auto moderation
            console.log('Auto moderation requested');
        }
    });
    
    document.getElementById('generateReport').addEventListener('click', function() {
        // Implementation for report generation
        console.log('Report generation requested');
    });
    
    document.getElementById('bulkDelete').addEventListener('click', function() {
        const checkedReviews = document.querySelectorAll('.review-checkbox:checked');
        if (checkedReviews.length === 0) {
            alert('Pilih review yang akan dihapus terlebih dahulu');
            return;
        }
        
        new bootstrap.Modal(document.getElementById('bulkActionsModal')).show();
    });
    
    // Bulk actions
    document.getElementById('bulkVerify').addEventListener('click', function() {
        const checkedReviews = Array.from(document.querySelectorAll('.review-checkbox:checked')).map(cb => cb.value);
        if (confirm(`Verifikasi ${checkedReviews.length} review?`)) {
            // Implementation for bulk verification
            console.log('Bulk verify:', checkedReviews);
        }
    });
    
    document.getElementById('bulkUnverify').addEventListener('click', function() {
        const checkedReviews = Array.from(document.querySelectorAll('.review-checkbox:checked')).map(cb => cb.value);
        if (confirm(`Batalkan verifikasi ${checkedReviews.length} review?`)) {
            // Implementation for bulk unverification
            console.log('Bulk unverify:', checkedReviews);
        }
    });
    
    document.getElementById('bulkDeleteConfirm').addEventListener('click', function() {
        const checkedReviews = Array.from(document.querySelectorAll('.review-checkbox:checked')).map(cb => cb.value);
        if (confirm(`Hapus ${checkedReviews.length} review? Tindakan ini tidak dapat dibatalkan!`)) {
            // Implementation for bulk deletion
            console.log('Bulk delete:', checkedReviews);
        }
    });
});
</script>
@endpush
