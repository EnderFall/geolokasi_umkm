@extends('layouts.app')

@section('title', 'Export Review')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-success text-white">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-download me-2"></i>
                        <h5 class="mb-0">Export Data Review</h5>
                    </div>
                </div>
                
                <div class="card-body">
                    <div class="alert alert-info">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-info-circle me-2"></i>
                            <div>
                                <strong>Export Data Review</strong>
                                <br><small class="text-muted">Pilih format dan filter untuk mengexport data review</small>
                            </div>
                        </div>
                    </div>
                    
                    <form action="{{ route('reviews.export') }}" method="POST">
                        @csrf
                        
                        <!-- Export Format -->
                        <div class="mb-3">
                            <label for="format" class="form-label">Format Export <span class="text-danger">*</span></label>
                            <select class="form-select @error('format') is-invalid @enderror" id="format" name="format" required>
                                <option value="">Pilih Format</option>
                                <option value="excel" {{ old('format') == 'excel' ? 'selected' : '' }}>Excel (.xlsx)</option>
                                <option value="csv" {{ old('format') == 'csv' ? 'selected' : '' }}>CSV (.csv)</option>
                                <option value="pdf" {{ old('format') == 'pdf' ? 'selected' : '' }}>PDF (.pdf)</option>
                            </select>
                            @error('format')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <!-- Date Range -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="date_from" class="form-label">Dari Tanggal</label>
                                <input type="date" 
                                       class="form-control @error('date_from') is-invalid @enderror" 
                                       id="date_from" 
                                       name="date_from" 
                                       value="{{ old('date_from') }}">
                                @error('date_from')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6">
                                <label for="date_to" class="form-label">Sampai Tanggal</label>
                                <input type="date" 
                                       class="form-control @error('date_to') is-invalid @enderror" 
                                       id="date_to" 
                                       name="date_to" 
                                       value="{{ old('date_to') }}">
                                @error('date_to')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <!-- Filters -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="rating" class="form-label">Rating</label>
                                <select class="form-select" id="rating" name="rating">
                                    <option value="">Semua Rating</option>
                                    @for($i = 5; $i >= 1; $i--)
                                        <option value="{{ $i }}" {{ old('rating') == $i ? 'selected' : '' }}>
                                            {{ $i }} Bintang
                                        </option>
                                    @endfor
                                </select>
                            </div>
                            
                            <div class="col-md-6">
                                <label for="type" class="form-label">Tipe Review</label>
                                <select class="form-select" id="type" name="type">
                                    <option value="">Semua Tipe</option>
                                    <option value="outlet" {{ old('type') == 'outlet' ? 'selected' : '' }}>Outlet</option>
                                    <option value="menu" {{ old('type') == 'menu' ? 'selected' : '' }}>Menu</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-select" id="status" name="status">
                                    <option value="">Semua Status</option>
                                    <option value="verified" {{ old('status') == 'verified' ? 'selected' : '' }}>Terverifikasi</option>
                                    <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                </select>
                            </div>
                            
                            <div class="col-md-6">
                                <label for="user_id" class="form-label">User</label>
                                <select class="form-select" id="user_id" name="user_id">
                                    <option value="">Semua User</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        
                        <!-- Columns to Export -->
                        <div class="mb-3">
                            <label class="form-label">Kolom yang Diexport</label>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="col_id" name="columns[]" value="id" checked>
                                        <label class="form-check-label" for="col_id">ID Review</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="col_user" name="columns[]" value="user" checked>
                                        <label class="form-check-label" for="col_user">Nama User</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="col_rating" name="columns[]" value="rating" checked>
                                        <label class="form-check-label" for="col_rating">Rating</label>
                                    </div>
                                </div>
                                
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="col_title" name="columns[]" value="title" checked>
                                        <label class="form-check-label" for="col_title">Judul</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="col_comment" name="columns[]" value="comment" checked>
                                        <label class="form-check-label" for="col_comment">Komentar</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="col_type" name="columns[]" value="type" checked>
                                        <label class="form-check-label" for="col_type">Tipe Review</label>
                                    </div>
                                </div>
                                
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="col_item" name="columns[]" value="item" checked>
                                        <label class="form-check-label" for="col_item">Item yang Direview</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="col_status" name="columns[]" value="status" checked>
                                        <label class="form-check-label" for="col_status">Status</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="col_date" name="columns[]" value="date" checked>
                                        <label class="form-check-label" for="col_date">Tanggal</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Export Options -->
                        <div class="mb-3">
                            <label class="form-label">Opsi Export</label>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="include_images" name="include_images" value="1">
                                <label class="form-check-label" for="include_images">
                                    Sertakan informasi gambar
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="include_anonymous" name="include_anonymous" value="1">
                                <label class="form-check-label" for="include_anonymous">
                                    Sertakan review anonim
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="group_by" name="group_by" value="1">
                                <label class="form-check-label" for="group_by">
                                    Kelompokkan berdasarkan tipe review
                                </label>
                            </div>
                        </div>
                        
                        <!-- Submit Buttons -->
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('reviews.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Kembali
                            </a>
                            
                            <div class="d-flex gap-2">
                                <button type="submit" name="action" value="preview" class="btn btn-info">
                                    <i class="fas fa-eye me-2"></i>Preview Data
                                </button>
                                <button type="submit" name="action" value="export" class="btn btn-success">
                                    <i class="fas fa-download me-2"></i>Export Data
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Export History -->
            <div class="card mt-4">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-history me-2 text-info"></i>
                        Riwayat Export
                    </h6>
                </div>
                <div class="card-body">
                    @if($exportHistory->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Format</th>
                                        <th>Filter</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($exportHistory as $export)
                                        <tr>
                                            <td>{{ $export->created_at->format('d/m/Y H:i') }}</td>
                                            <td>
                                                <span class="badge bg-{{ $export->format == 'excel' ? 'success' : ($export->format == 'csv' ? 'info' : 'danger') }}">
                                                    {{ strtoupper($export->format) }}
                                                </span>
                                            </td>
                                            <td>{{ $export->filter_summary }}</td>
                                            <td>
                                                @if($export->status == 'completed')
                                                    <span class="badge bg-success">Selesai</span>
                                                @elseif($export->status == 'processing')
                                                    <span class="badge bg-warning">Diproses</span>
                                                @else
                                                    <span class="badge bg-danger">Gagal</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($export->status == 'completed' && $export->file_path)
                                                    <a href="{{ route('reviews.download-export', $export) }}" 
                                                       class="btn btn-sm btn-outline-primary">
                                                        <i class="fas fa-download me-1"></i>Download
                                                    </a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted text-center mb-0">Belum ada riwayat export</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Date validation
    const dateFrom = document.getElementById('date_from');
    const dateTo = document.getElementById('date_to');
    
    dateFrom.addEventListener('change', function() {
        if (dateTo.value && this.value > dateTo.value) {
            dateTo.value = this.value;
        }
    });
    
    dateTo.addEventListener('change', function() {
        if (dateFrom.value && this.value < dateFrom.value) {
            dateFrom.value = this.value;
        }
    });
    
    // Select all columns
    const selectAllBtn = document.createElement('button');
    selectAllBtn.type = 'button';
    selectAllBtn.className = 'btn btn-outline-secondary btn-sm mb-2';
    selectAllBtn.innerHTML = '<i class="fas fa-check-double me-1"></i>Pilih Semua';
    selectAllBtn.onclick = function() {
        document.querySelectorAll('input[name="columns[]"]').forEach(checkbox => {
            checkbox.checked = true;
        });
    };
    
    const columnsLabel = document.querySelector('label[for="col_id"]').parentElement.parentElement.parentElement;
    columnsLabel.parentElement.insertBefore(selectAllBtn, columnsLabel);
    
    // Deselect all columns
    const deselectAllBtn = document.createElement('button');
    deselectAllBtn.type = 'button';
    deselectAllBtn.className = 'btn btn-outline-secondary btn-sm mb-2 ms-2';
    deselectAllBtn.innerHTML = '<i class="fas fa-times me-1"></i>Hapus Semua';
    deselectAllBtn.onclick = function() {
        document.querySelectorAll('input[name="columns[]"]').forEach(checkbox => {
            checkbox.checked = false;
        });
    };
    
    selectAllBtn.after(deselectAllBtn);
});
</script>
@endpush
