@extends('layouts.app')

@section('title', 'Dashboard Review')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">
                <i class="fas fa-star me-2 text-warning"></i>
                Dashboard Review
            </h2>
            <p class="text-muted mb-0">Kelola dan monitor semua review dalam sistem</p>
        </div>
        
        <div class="d-flex gap-2">
            <a href="{{ route('reviews.create.outlet', 1) }}" class="btn btn-warning">
                <i class="fas fa-plus me-2"></i>Buat Review
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
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-users fa-2x"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h4 class="mb-1">{{ $activeReviewers }}</h4>
                            <small>Reviewer Aktif</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Charts Row -->
    <div class="row g-3 mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-chart-pie me-2 text-primary"></i>
                        Distribusi Rating
                    </h6>
                </div>
                <div class="card-body">
                    <canvas id="ratingChart" width="400" height="200"></canvas>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-chart-line me-2 text-success"></i>
                        Trend Review Bulanan
                    </h6>
                </div>
                <div class="card-body">
                    <canvas id="trendChart" width="400" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Recent Reviews -->
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h6 class="mb-0">
                    <i class="fas fa-list me-2 text-info"></i>
                    Review Terbaru
                </h6>
                <a href="{{ route('reviews.index') }}" class="btn btn-outline-primary btn-sm">
                    Lihat Semua
                </a>
            </div>
        </div>
        <div class="card-body">
            @if($recentReviews->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
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
                            @foreach($recentReviews as $review)
                                <tr>
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
                                            @if(auth()->user()->hasRole(['admin', 'superadmin']))
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
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-4">
                    <i class="fas fa-star fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">Belum ada review</h5>
                    <p class="text-muted">Review akan muncul di sini setelah user memberikan review</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Rating Distribution Chart
    const ratingCtx = document.getElementById('ratingChart').getContext('2d');
    const ratingChart = new Chart(ratingCtx, {
        type: 'doughnut',
        data: {
            labels: ['5 Bintang', '4 Bintang', '3 Bintang', '2 Bintang', '1 Bintang'],
            datasets: [{
                data: [
                    {{ $ratingStats[5] ?? 0 }},
                    {{ $ratingStats[4] ?? 0 }},
                    {{ $ratingStats[3] ?? 0 }},
                    {{ $ratingStats[2] ?? 0 }},
                    {{ $ratingStats[1] ?? 0 }}
                ],
                backgroundColor: [
                    '#28a745',
                    '#20c997',
                    '#ffc107',
                    '#fd7e14',
                    '#dc3545'
                ],
                borderWidth: 2,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
    
    // Monthly Trend Chart
    const trendCtx = document.getElementById('trendChart').getContext('2d');
    const trendChart = new Chart(trendCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($monthlyTrend->pluck('month')) !!},
            datasets: [{
                label: 'Jumlah Review',
                data: {!! json_encode($monthlyTrend->pluck('count')) !!},
                borderColor: '#007bff',
                backgroundColor: 'rgba(0, 123, 255, 0.1)',
                borderWidth: 2,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });
});
</script>
@endpush
