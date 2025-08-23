@props(['name', 'value' => 0, 'required' => false, 'disabled' => false])

<div class="rating-input" x-data="{ rating: {{ $value }}, hoverRating: 0 }">
    <input type="hidden" name="{{ $name }}" :value="rating" {{ $required ? 'required' : '' }}>
    
    <div class="stars d-flex align-items-center">
        @for($i = 1; $i <= 5; $i++)
            <button type="button" 
                    class="btn btn-link p-0 me-1 star-btn" 
                    :class="{ 'text-warning': $i <= (hoverRating || rating), 'text-muted': $i > (hoverRating || rating) }"
                    @click="rating = {{ $i }}"
                    @mouseenter="hoverRating = {{ $i }}"
                    @mouseleave="hoverRating = 0"
                    {{ $disabled ? 'disabled' : '' }}
                    style="border: none; background: none; font-size: 1.5rem;">
                <i class="fas fa-star"></i>
            </button>
        @endfor
        
        <span class="ms-2 text-muted small">
            <span x-text="rating || 0"></span>/5
        </span>
    </div>
    
    @error($name)
        <div class="invalid-feedback d-block">
            {{ $message }}
        </div>
    @enderror
</div>

<style>
.rating-input .star-btn:hover {
    transform: scale(1.1);
    transition: transform 0.2s ease;
}

.rating-input .star-btn:focus {
    outline: none;
    box-shadow: 0 0 0 0.2rem rgba(255, 193, 7, 0.25);
}
</style>
