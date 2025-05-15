<?php
//dd($row);
$spaceSettings = \Modules\Core\Models\Settings::getSettings('space');
?>
@php
$terms_ids = $row->terms->pluck('term_id');

$attributes_terms = \Modules\Core\Models\Terms::query()
    ->with(['translations', 'attribute'])
    ->find($terms_ids)
    ->pluck('id')
    ->toArray();
    
$attributes = \Modules\Core\Models\Terms::where('attr_id', 4)->get();
 
$hasWifi = false;

@endphp

@if (!empty($terms_ids) and !empty($attributes))
    @foreach ($attributes as $attribute)
        @if (empty($attribute['parent']['hide_in_single']))
            @php $terms = $attribute['child'] @endphp
            <?php
            if(
                in_array($attribute->id, $attributes_terms) && 
                (
                    strpos(strtolower($attribute->name), 'wifi') !== false ||
                    strpos(strtolower($attribute->name), 'wi-fi') !== false
                )
            ){
                // echo $attribute->id."  --  ";
                // echo $attribute->name;
                // dd($attributes_terms);
                $hasWifi = true; 
            }
            ?>
        @endif
    @endforeach
@endif

<div class="g-header mt-5 mb-3">
    <div class="left">
        <h1>{!! clean($translation->title) !!}</h1>
        <?php
        $addressData = $row->addressWithDistance();
        ?>
        <p class="address"><i class="fa fa-map-marker mr-1"></i>
            <a href="{{ $addressData['link'] }}" target="_blank">{{ $addressData['address'] }}</a>
        </p>
    </div>
    <script>
        function getSelectedTabIndex() {
            $('#space-tabs li a[href="#module-location"]').click();
            $('html,body').animate({
                scrollTop: '+=100px'
            });

        }
    </script>
    <div class="right">
        @if ($row->getReviewEnable() and $review_score['score_total'] != 0)
            @if ($review_score)
                <div class="review-score">
                    <div class="head">
                        <div class="score">
                            {{ $review_score['score_total'] }}</span>
                        </div>
                        <div class="left text-center ml-2">
                            <span class="head-rating">
                                <?php
                                // $reviewData = $row->getScoreReview();
                                // $score_total = $reviewData['score_total'];
                                ?>
                                <div class="star-rate">
                                    @for ($number = 1; $number <= $review_score['score_total']; $number++)
                                        <i class="fa fa-star"></i>
                                    @endfor
                                </div>
                            </span>
                            <span
                                class="text-rating">
                                <a class="openReviews" href="javascript:;" data-href="#module-review">{{ __('from :number reviews', ['number' => $review_score['total_review']]) }}</a>
                            </span>
                        </div>
                    </div>
                </div>
            @endif
        @endif
    </div>
</div>
<div class="g-space-feature">
    <div class="row">
        <div class="col-xs-6 col-lg col-md-6">
            <div class="item">
                <img src="{{ \App\Helpers\CodeHelper::withAppUrl('/images/desk-chair.png') }}">
                <div class="info">
                    <h4 class="name">Desks</h4>
                    <p class="value">
                        {{ $row->desk ? $row->desk : 0 }}
                    </p>
                </div>
            </div>
        </div>
        <div class="col-xs-6 col-lg col-md-6">
            <div class="item">
                <img src="{{ \App\Helpers\CodeHelper::withAppUrl('/images/armchair.png') }}">
                <div class="info">
                    <h4 class="name">Seats</h4>
                    <p class="value">
                        {{ $row->seat ? $row->seat : 0 }}
                    </p>
                </div>
            </div>
        </div>
        <div class="col-xs-6 col-lg col-md-6">
            <div class="item">
                <img src="{{ \App\Helpers\CodeHelper::withAppUrl('/images/guests.png') }}">
                <div class="info">
                    <h4 class="name">Guests</h4>
                    <p class="value">
                        {{ $row->max_guests }}
                    </p>
                </div>
            </div>
        </div>
        <div class="col-xs-6 col-lg col-md-6">
            <div class="item">
                <img src="{{ \App\Helpers\CodeHelper::withAppUrl('/images/parking-area.png') }}">
                <div class="info">
                    <h4 class="name">Parking</h4>
                    <p class="value">
                        @if ($row->parking)
                            {{ 'Yes' }}
                        @else
                            {{ 'No' }}
                        @endif
                    </p>
                </div>
            </div>
        </div>
        <div class="col-xs-6 col-lg col-md-6">
            <div class="item">
                <img src="{{ \App\Helpers\CodeHelper::withAppUrl('/images/wifi-signal.png') }}">
                <div class="info">
                    <h4 class="name">Wi-Fi</h4>
                    <p class="value">{{$hasWifi ? "Yes": "No"}}</p>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).on("click", ".bravo_detail_hotel .details-hotel .g-faq .item .header", function() {
        $(this).parent().toggleClass("active");
    });
</script>
<div class="tab-content-wrapper">
    <div id="space-tabs">
        <ul class="location-module-nav nav nav-pills justify-content-center">
            {{-- <li class="active">
                <a href="#module-photos" data-toggle="tab">Photos</a>
            </li> --}}
            <li class="active">
                <a href="#module-description" data-toggle="tab">Description</a>
            </li>
            <li>
                <a href="#module-location" data-toggle="tab">Location</a>
            </li>
            <li>
                <a href="#module-amenities" data-toggle="tab">Amenities</a>
            </li>
            <li>
                <a href="#module-faq" data-toggle="tab">FAQs</a>
            </li>
            
            <li>
                <a href="#module-review" data-toggle="tab">Reviews</a>
            </li>
        </ul>
    </div>
    <div class="details-hotel tab-content clearfix">
        <div class="tab-pane" id="module-photos">
            <div class="g-gallery">
                <div class="fotorama" data-width="100%" data-thumbwidth="135" data-thumbheight="135"
                    data-thumbmargin="15" data-nav="thumbs" data-allowfullscreen="true">
                    @if ($row->getGallery())
                        @foreach ($row->getGallery() as $key => $item)
                            <a href="{{ $item['large'] }}" data-thumb="{{ $item['thumb'] }}"
                                data-alt="{{ __('Gallery') }}"></a>
                        @endforeach
                    @endif
                </div>
                <div class="social">
                    <div class="social-share">
                        <span class="social-icon">
                            <i class="icofont-share"></i>
                        </span>
                        <ul class="share-wrapper">
                            <li>
                                <a class="facebook"
                                    href="https://www.facebook.com/sharer/sharer.php?u={{ $row->getDetailUrl() }}&amp;title={{ $translation->title }}"
                                    target="_blank" rel="noopener" original-title="{{ __('Facebook') }}">
                                    <i class="fa fa-facebook fa-lg"></i>
                                </a>
                            </li>
                            <li>
                                <a class="twitter"
                                    href="https://twitter.com/share?url={{ $row->getDetailUrl() }}&amp;title={{ $translation->title }}"
                                    target="_blank" rel="noopener" original-title="{{ __('Twitter') }}">
                                    <i class="fa fa-twitter fa-lg"></i>
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="service-wishlist {{ $row->isWishList() }}" data-id="{{ $row->id }}"
                        data-type="{{ $row->type }}">
                        <i class="fa fa-heart-o"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="tab-pane active" id="module-description">
            <div class="g-rules">
                <div class="description">
                    <div class="row">
                        <div class="col-lg-2">
                            <div class="key align-end"><strong>Rating:</strong></div>
                        </div>
                        <div class="col-lg-2">
                            <div class="value">{{$row->review_score}}</div>
                        </div>
                        <div class="col-lg-2">
                            <div class="key align-end"><strong>Open:</strong></div>
                        </div>
                        <div class="col-lg-2">
                            <div class="value">{{ date('h:i A', strtotime($row->available_from)) }}</div>
                        </div>
                        <div class="col-lg-2">
                            <div class="key align-end"><strong>Close:</strong></div>
                        </div>
                        <div class="col-lg-2" style="left: 18px;">
                            <div class="value">{{ date('h:i A', strtotime($row->available_to)) }}</div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-2">
                            <div class="key align-end"><strong>Bookings:</strong></div>
                        </div>
                        <div class="col-lg-2">
                            <div class="value">{{$totalBookings}}</div>
                        </div>
                        <div class="col-lg-2">
                            <div class="key align-end"><strong>Reviews:</strong></div>
                        </div>
                        <div class="col-lg-2">
                            <div class="value">{{$totalRatings}} Reviews</div>
                        </div>
                        <div class="col-lg-2">
                            <div class="key align-end"><strong>Capacity:</strong></div>
                        </div>
                        <div class="col-lg-2" style="left: 18px;">
                            <div class="value">
                                @if ($row->max_guests <= 1)
                                    {{ __(':count Guest', ['count' => $row->max_guests]) }}
                                @else
                                    {{ __(':count Guests', ['count' => $row->max_guests]) }}
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @if ($translation->content)
                <div class="g-overview" style="margin-top: 35px !important;">
                    <h3>{{ __('Description') }}</h3>
                    <div class="no-formatting basic-formatting">
                        <?php echo $translation->content; ?>
                    </div>
                </div>
            @endif
        </div>
        <div class="tab-pane" id="module-amenities">
            <ul class="aminitlistingul mgnT20">
                @if (!empty($terms_ids) and !empty($attributes))
                    @foreach ($attributes as $attribute)
                        @if (empty($attribute['parent']['hide_in_single']))
                            @php $terms = $attribute['child'] @endphp
                            @if(in_array($attribute->id, $attributes_terms))
                                <li
                                    class="detaillistingli {{ in_array($attribute->id, $attributes_terms) ? '' : 'not' }} fulwidthm mgnB10">
                                    <i class="aminti_icon {{ $attribute->icon }}"></i>
                                    <span class="aminidis">{{ $attribute->name }}</span>
                                </li>
                            @endif
                        @endif
                    @endforeach
                @endif
            </ul>
        </div>
        <div class="tab-pane bravo_content" id="module-faq">
            <?php
            if($translation->faqs){
                if($translation->faqs != ''){
                    $translation->faqs = json_decode($spaceSettings['space_default_faqs'], true);
                }
            }else{
                $translation->faqs = json_decode($spaceSettings['space_default_faqs'], true);
            }
            ?>
            @if ($translation->faqs)
                <div class="g-faq">
                    @foreach ($translation->faqs as $item)
                        <div class="item">
                            <div class="header" data-parent="#accordion" data-toggle="collapse">
                                <i class="field-icon icofont-support-faq"></i>
                                <h5>{{ $item['title'] }}</h5>
                                <span class="arrow"><i class="fa fa-angle-down"></i></span>
                            </div>
                            <div class="body" class="collapse" id="accordion">
                                {{ $item['content'] }}
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif 
        </div>
        <div class="tab-pane" id="module-location">
            <div class="g-location">
                <div class="location-map">
                    <div id="map_content" style="position: relative; overflow: hidden;"></div>
                </div> 
            </div>
        </div>
        <div class="tab-pane" id="module-review">
            @include('Space::frontend.layouts.details.space-review')
        </div>
    </div> 
</div>
