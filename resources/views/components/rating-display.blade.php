@props(['rating', 'showScore' => true, 'size' => 'sm'])

@php
    $sizeClass = $size === 'lg' ? 'fa-lg' : ($size === 'sm' ? 'fa-sm' : '');
@endphp

<div class="rating-display d-inline-flex align-items-center">
    @for($i = 1; $i <= 5; $i++)
        @if($i <= $rating)
            <i class="fas fa-star text-warning {{ $sizeClass }} me-1"></i>
        @else
            <i class="far fa-star text-muted {{ $sizeClass }} me-1"></i>
        @endif
    @endfor
    
    @if($showScore)
        <span class="ms-1 text-muted small">{{ number_format($rating, 1) }}</span>
    @endif
</div>
