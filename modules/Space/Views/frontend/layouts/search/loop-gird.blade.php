@php
    $translation = $row->translateOrOrigin(app()->getLocale());
    $spacePriceData = \App\Helpers\CodeHelper::spacePriceData($row);
    $priceType = $priceType ?? $spacePriceData['priceType'];
@endphp
<?php
$latLangData = \App\Helpers\CodeHelper::requestLatLng();
$addressData = $row->addressWithDistance(true, false, $latLangData['lat'], $latLangData['lng']);
?>
@php $user = \App\User::where('id', $row->create_user)->first(); @endphp
<style>
    .space-list-item-details .content-item-data{
        height:120px !important;
    }
    .space-list-item-details .content-item-data .location{
        height: 100px !important;
    }
</style>
<div class="item-loop space-list-item-details">
    @if (count($spacePriceData['prices']) >= 2)
        {{-- <span class="specialPriceBadge">
            <i class="fa fa-dollar"></i>
        </span> --}}
    @endif
    <div class="thumb-image">
        <a target="_blank" @if (!empty($blank))  @endif
            href="{{ $row->getDetailUrl($include_param ?? true) }}">
            @if ($row->image_url)
                @if (!empty($disable_lazyload))
                    <img src="{{ $row->image_url }}" class="img-responsive lazy loaded" alt=""
                        data-src="{{ $row->image_url }}" data-was-processed="true">
                @else
                    {!! get_image_tag($row->image_id, 'medium', ['class' => 'img-responsive', 'alt' => $row->title]) !!}
                @endif
            @endif
        </a>
        <div class="userimageresult  text-center">
            <a target="_blank" href="{{ $row->getDetailUrl($include_param ?? true) }}">
                <img src="userdata/Profile/20/images.png" alt="Heather Larkin" title="Heather Larkin">
            </a>
        </div>
        @if (\App\Helpers\CodeHelper::checkIfNumValNotNull($spacePriceData['discountRate']))
            <div class="featured-off">
                {{ round($spacePriceData['discountRate'], 0) }}% OFF
            </div>
        @endif
        @if ($row->isTopRated())
            <span class="topRatedBagde">
                <img src="{{ asset('images/mo_toprated.png') }}" alt="Top Rated">
            </span>
        @endif
    </div>
    <div class="row location-header">


        <div class="price-tab tab-content-wrapper">
            <div class="tab-content">
                <?php
                    foreach ($spacePriceData['prices'] as $key => $priceData) {
                        ?>
                <div class="tab-pane {{ $priceType === $key ? 'active' : '' }}"
                    id="{{ $key }}-price-tab-{{ $row->id }}"
                    aria-labelledby="{{ $key }}-price-tab-{{ $row->id }}-tab">
                    @if (
                        \App\Helpers\CodeHelper::checkIfNumValNotNull($priceData['discountedPrice']) &&
                            $priceData['discountedPrice'] < $priceData['price']
                    )
                        <span class="onsale 2">${{ $priceData['price'] }}</span>
                        <span class="text-price">${{ $priceData['discountedPrice'] }}</span>
                    @else
                        <span class="text-price">${{ $priceData['price'] }}</span>
                    @endif
                </div>
                <?php
                    }
                    ?>
            </div>
            <ul class="nav nav-tabs" role="tablist">
                <?php
                    foreach ($spacePriceData['prices'] as $key => $priceData) {
                        ?>
                <li class="{{ $priceType === $key ? 'active' : '' }}"><a
                        href="#{{ $key }}-price-tab-{{ $row->id }}"
                        id="{{ $key }}-price-tab-{{ $row->id }}-tab"
                        data-toggle="tab">{{ $key }}</a></li>
                <?php
                    }
                    ?>
            </ul>
        </div>


        <div class="col-sm-12 content-item-data">
            <div class="item-title">
                <a target="_blank" @if (!empty($blank))  @endif
                    href="{{ $row->getDetailUrl($include_param ?? true) }}">
                    @if ($row->is_instant)
                        <i class="fa fa-bolt d-none"></i>
                    @endif
                    {!! clean($translation->title) !!}
                </a>
                <div class="div-line"></div>
            </div>

            <div class="location" style="height: 18px;">
                {!! $addressData['address'] !!}
            </div>
        </div>
        <?php
        $review_score = $row->review_data;
        $score_total = $review_score['score_total'];
        ?>
        <div class="col-sm-12 location-rating mt-4 text-center">
            @if ($score_total != 0)
                <div class="mb-2"><span class="star-div">{{ $score_total }}</span></div>
                <div class="rating_stars fulwidthm left pr-2">
                    @for ($number = 1; $number <= $score_total; $number++)
                        <i class="fa fa-star yellowtext"></i>
                    @endfor
                </div>
                @if ($review_score['total_review'] > 1)
                    <div class="text-rating">
                        {{ __(':number Reviews', ['number' => $review_score['total_review']]) }}
                    </div>
                @endif
            @endif
        </div>
    </div>
    <div class="amenities search-icon">
        <div class="pop" href="#">
            <a href="{{ url('/profile/' . $user->user_name) }}">
                <span class="amenity total">
                    <i class="input-icon field-icon icofont-people"></i> Host</span>
            </a>
            <div class="popup listing-item-box-host-popup">
                @php $totalReviews = $user->review_score!=null ? $user->review_score: 0  @endphp
                @php $pendingReviews = 5 - $totalReviews  @endphp
                <div class="author">
                    <a href="{{ url('/profile/' . $user->user_name) }}"><img src="{{ $user->getAvatarUrl() }}"
                            alt="{{ $user->name }}"></a>
                    <div class="author-meta">
                        <h4><a href="{{ url('/profile/' . $user->user_name) }}">{{ $user->name }}</a></h4>
                        <div class="star">
                            @for ($number = 1; $number <= $totalReviews; $number++)
                                <i class="fa fa-star yellowtext"></i>
                            @endfor
                            @for ($number = 1; $number <= $pendingReviews; $number++)
                                <i class="fa fa-star"></i>
                            @endfor
                        </div>
                        <h5>{{ $totalReviews }}</h5>
                        <p>(<a href="{{ url('/profile/' . $user->user_name) }}">
                                @if ($user->review_count <= 1)
                                    {{ __(':count review', ['count' => $user->review_count]) }}
                                @else
                                    {{ __(':count reviews', ['count' => $user->review_count]) }}
                                @endif
                            </a>)</p>
                    </div>
                </div>
            </div>
        </div>
        <a target="_blank" href="{{ $row->getDetailUrl($include_param ?? true) }}"><span class="amenity bed"
                data-toggle="tooltip" title="" data-original-title="Manually Book">
                <i class="input-icon field-icon icofont-ui-calendar"></i>Book</span>
        </a>
        <a href="javascript:;" class="contactHostModalTrigger" data-host="{{ $user->id }}"
            data-space="{{ $row->id }}"><span class="amenity bath" data-toggle="tooltip" title=""
                data-original-title="Contact Us">
                <i class="input-icon field-icon icofont-envelope"></i> Contact</span>
        </a>
    </div>
</div>
