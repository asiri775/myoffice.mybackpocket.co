<?php
$topRated = $topRated ?? false;
?>
@if ($type == 'listing')
    <div class="col-6 ratingitem-wrapper rating-view-wrapper listing-page">

        <div class="stars lh-16 m-10 lh-16">
            @php
                $fullStars = floor($rating);
                $halfStar = $rating - $fullStars >= 0.5;
                $emptyStars = 5 - $fullStars - ($halfStar ? 1 : 0);
            @endphp

            @for ($i = 0; $i < $fullStars; $i++)
                <i class="las la-star"></i>
            @endfor

            @if ($halfStar)
                <i class="las la-star-half-alt"></i>
            @endif

            @for ($i = 0; $i < $emptyStars; $i++)
                <i class="lar la-star"></i>
            @endfor

            @if ($topRated)
                <span class="topRatedBagde">
                    <img src="{{ asset('images/mo_toprated.png') }}" alt="Top Rated">
                </span>
            @endif

        </div>

        <p class="f-r lh-16 text-primary">{{ $rating ?? 0 }} Reviews</p>
    </div>
@else
    <div class="mt-10  rating-view-wrapper common-page">
        <h4 class="inline rating">{{ $rating ?? 0 }}</h4>
        <div class="stars lh-16 m-10 lh-16 inline">
            @php
                $fullStars = floor($rating);
                $halfStar = $rating - $fullStars >= 0.5;
                $emptyStars = 5 - $fullStars - ($halfStar ? 1 : 0);
            @endphp

            @for ($i = 0; $i < $fullStars; $i++)
                <i class="las la-star"></i>
            @endfor

            @if ($halfStar)
                <i class="las la-star-half-alt"></i>
            @endif

            @for ($i = 0; $i < $emptyStars; $i++)
                <i class="lar la-star"></i>
            @endfor
        </div>
        @if ($topRated)
            <span class="topRatedBagde">
                <img src="{{ asset('images/mo_toprated.png') }}" alt="Top Rated">
            </span>
        @endif
    </div>

@endif
