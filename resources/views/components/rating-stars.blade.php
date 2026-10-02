@props(['rating' => 0, 'count' => null, 'showScore' => false])

@php
    $rounded = round($rating * 2) / 2;
@endphp

<div class="product-rating-row" style="display: inline-flex; align-items: center; gap: 4px; color: #F59E0B;">
    @for($i = 1; $i <= 5; $i++)
        @if($i <= $rounded)
            <i class="fa-solid fa-star"></i>
        @elseif($i - 0.5 == $rounded)
            <i class="fa-solid fa-star-half-stroke"></i>
        @else
            <i class="fa-regular fa-star" style="color: #D1D5DB;"></i>
        @endif
    @endfor

    @if($showScore && $rating > 0)
        <span style="font-weight: 700; color: #181615; margin-right: 4px; font-size: 0.9rem;">{{ number_format($rating, 1) }}</span>
    @endif

    @if(!is_null($count))
        <span class="product-rating-count" style="color: #8E867F; font-size: 0.8rem; margin-right: 2px;">
            ({{ $count }})
        </span>
    @endif
</div>
