@extends('layouts.common_home')
@section('head')
    <link href="{{ asset('dist/frontend/module/space/css/space.css?_ver=' . config('app.version')) }}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ asset('libs/ion_rangeslider/css/ion.rangeSlider.min.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('libs/fotorama/fotorama.css') }}" />
    {{-- <link rel="stylesheet" href="{{ asset('css/lightbox.min.css') }}"> --}}
    <link rel="stylesheet" href="{{ asset('css/simpleLightbox.min.css') }}">
@endsection
<?php
$getPageData = \App\Helpers\CodeHelper::getPageData($seo_meta ?? null);
?>
<style>
    .tab-content-wrapper .g-faq h5 {
    font-weight: 400;
    font-size: 15px !important;
}
</style>
@section('content')
    <?php
    $review_score = $row->review_data;
    $addressData = $row->addressWithDistance();
    $galleryImages = $row->getGallery();
    $favourite = App\Models\AddToFavourite::where('user_id', Auth::id())
        ->where('object_id', $row->id)
        ->first();
    ?>

    <div class="layout1 bravo_wrap space-details-page">

        <div id="msg-success" align="left" class="alert alert-success" style="display:none;"></div>

        <section class="space-details-home">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="space-details-head">
                            <div class="title-box">
                                <h1>{!! clean($translation->title) !!}</h1>
                                <ul>
                                    @if ($row->getReviewEnable() and $review_score['score_total'] != 0)
                                        <li class="rate-box">
                                            @if ($review_score)
                                                <div class="review-score">
                                                    <span>{{ $review_score['score_total'] }}</span>
                                                    <span class="head-rating">
                                                        <div class="star-rate">
                                                            @for ($number = 1; $number <= $review_score['score_total']; $number++)
                                                                <i class="fa fa-star"></i>
                                                            @endfor
                                                        </div>
                                                    </span>
                                                    <a class="openReviews" href="javascript:;"
                                                        data-href="#module-review">({{ __(':number', ['number' => $review_score['total_review']]) }})</a>
                                                </div>
                                            @endif
                                        </li>
                                    @endif 
                                    <li>
                                        <p class="address">
                                            {{ $addressData['address'] }}
                                            <a href="{{ $addressData['link'] }}" target="_blank">(Get directions)</a>
                                        </p>
                                    </li>
                                </ul>
                            </div>
                            <div class="btn-boxes">
                                <div class="social">
                                    <ul>
                                        <li>
                                            <a title="Copy Space URL" href="javascript:;" class="copyToClipboard" data-clipboard-text="{{$getPageData['url_without_query']}}">
                                                <i class="icofont-copy"></i>
                                            </a>
                                        </li>
                                        <li>
                                            <a title="Share Space" href="javascript:;" class="shareBoxModalOpen"><i class="icofont-share"></i></a>
                                        </li>
                                        <li>
                                            <a title="Add to Wishlist" onclick="addToFavouriteSpace(event)" data-id="{{ $row->id }}"
                                                href="javascript:;" class="service-wishlist"
                                                data-type="{{ $row->type }}">
                                                <?php
                                                if ($favourite != ''){
                                                    ?>
                                                <i class="fa fa-heart"></i>
                                                <?php
                                                }else{
                                                    ?>
                                                <i class="fa fa-heart-o"></i>
                                                <?php
                                                }
                                                ?>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="space-details-gallery">
                            <div class="gallery-lightbox-images">
                                <a href="{{ $row->getBannerImageUrlAttribute('full') }}"
                                    data-lightbox="space">{{ $row->getBannerImageUrlAttribute('full') }}</a>
                                <?php
                                if ($galleryImages && count($galleryImages) > 0) {
                                foreach ($galleryImages as $key => $item) {
                                    if($item['large']!==false){ 
                                    ?>

                                <a href="{{ $item['large'] }}" data-lightbox="space">{{ $item['large'] }}</a>
                                <?php
                                    }
                                }
                            }
                                ?>
                            </div>
                            <div class="row">
                                @if ($row->banner_image_id)
                                    <div class="<?php if ($galleryImages && count($galleryImages) > 0) {
                                        echo 'col-md-8';
                                    } else {
                                        echo 'col-md-12';
                                    } ?> col-12">
                                        <a href="javascript:;" data-index="0"
                                            class="banner-img triggerLightBox view-image-large"
                                            style='background-image: url("{{ $row->getBannerImageUrlAttribute('full') }}")'>
                                        </a>
                                    </div>
                                @endif
                                <?php
                                if ($galleryImages && count($galleryImages) > 0) {
                                    ?>
                                <?php
                                $imageUrl = 'https://placehold.co/600x400?text=' . urlencode($translation->title);
                                if (count($galleryImages) === 1) {
                                    $galleryImages[] = [
                                        'large' => $imageUrl,
                                        'thumb' => $imageUrl,
                                    ];
                                } elseif (count($galleryImages) > 1) {
                                    if ($galleryImages[1]['large'] === false) {
                                        $galleryImages[1] = [
                                            'large' => $imageUrl,
                                            'thumb' => $imageUrl,
                                        ];
                                    }
                                }
                                ?>
                                <div class="col-md-4 col-12">
                                    <div class="row">
                                        <?php
                                            $i = 0;
                                            foreach ($galleryImages as $key => $item) {
                                                $i++;
                                                if ($i <= 2) {
                                                    ?>
                                        <div class="col-12">
                                            <div class="view-image-box">
                                                <a data-index="{{ $i }}" href="javascript:;"
                                                    class="triggerLightBox main-img view-image-large"
                                                    style='background-image: url("{{ $item['large'] }}")'></a>
                                                <?php
                                                    if($i==2){
                                                        ?>
                                                <a data-index="0" href="javascript:;"
                                                    class="triggerLightBox view-images-gallery">See all images</a>
                                                <?php
                                                    }
                                                    ?>
                                            </div>
                                        </div>
                                        <?php
                                                }
                                        }
                                        ?>
                                    </div>
                                </div>
                                <?php
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <div class=" container-fixed-lg">
            <div class="bravo_detail_hotel">
                {{-- @include('Space::frontend.layouts.details.space-banner') --}}
                <div class="bravo_content">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-8 col-lg-8 mb-5 page-template-content">
                                @php $review_score = $row->review_data @endphp
                                @include('Space::frontend.layouts.details.space-detail')
                                {{-- @include('Space::frontend.layouts.details.space-related') --}}
                            </div>
                            <div class="col-md-4 col-lg-4 mb-5">
                                @include('Space::frontend.layouts.details.space-form-book')

                                <div class="clearfix"></div>
                                @include('Tour::frontend.layouts.details.vendor')

                                <div class="clearfix"></div>
                                <div class="add-btn mt-1 mb-3">
                                    <a style="margin-right: 0 !important;" href="javascript:void(0)"
                                        data-id="{{ $row->id }}" onclick="addToFavouriteSpace(event)"
                                        @if ($favourite != '') style="pointer-events: none" @endif
                                        class="btn btn-large">
                                        @if ($favourite != '')
                                            Remove from Favourites
                                        @else
                                            Add To Favourites
                                        @endif
                                    </a>
                                    <span id="success-message" style="color: green;"></span>
                                </div>

                            </div>
                        </div>
                        @if (count($relatedSpaces) > 0)
                            <div class="row end_tour_sticky nearby-spaces-section">
                                <div class="col-md-12 bravo_search_space">
                                    @if ($relatedSpaces)
                                        <div class="g-itinerary bravo-list-item">
                                            <h4> {{ __('Spaces Nearby') }} </h4>
                                            <div class="list-item owl-carousel nearby-spaces">
                                                @foreach ($relatedSpaces as $relatedSpace)
                                                    <?php
                                                    $row = $relatedSpace;
                                                    ?>
                                                    <div class="item">
                                                        @include('Space::frontend.layouts.search.loop-gird')
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
                @include('Space::frontend.layouts.details.space-form-book-mobile')
            </div>

            <script>
                function addToFavouriteSpace(event) {
                    event.preventDefault();
                    event.stopPropagation();
                    var space_id = $(".service-wishlist").attr("data-id");

                    let _url = "{{ \App\Helpers\CodeHelper::withAppUrl('/space/add-to-favourite') }}";
                    let _token = $('meta[name="csrf-token"]').attr('content');

                    $.ajax({
                        url: _url,
                        type: "POST",
                        data: {
                            space_id: space_id,
                            _token: _token
                        },
                        success: function(response) {
                            showNotification(response.success, "success");
                            setTimeout(() => {
                                window.location.reload();
                            }, 200);
                        }
                    });
                }
            </script>

        @endsection

        @section('footer')
            {!! App\Helpers\MapEngine::scripts() !!}
            <script>
                jQuery(function($) {
                    @if ($row->map_lat && $row->map_lng)
                        new BravoMapEngine('map_content', {
                            disableScripts: true,
                            fitBounds: true,
                            center: [{{ $row->map_lat }}, {{ $row->map_lng }}],
                            zoom: {{ $row->map_zoom ?? '8' }},
                            ready: function(engineMap) {
                                engineMap.addMarker([{{ $row->map_lat }}, {{ $row->map_lng }}], {
                                    icon_options: {
                                        iconUrl: "{{ get_file_url(setting_item('space_icon_marker_map'), 'full') ?? url('images/myoffice-marker-1.png') }}"
                                    }
                                });
                            }
                        });
                    @endif
                })
            </script>
            <script>
                var bravo_booking_data = {!! json_encode($booking_data) !!}
                var
                    bravo_booking_i18n = {
                        no_date_select: '{{ __('Please select Start and End date') }}',
                        no_guest_select: '{{ __('Please select at least one guest') }}',
                        load_dates_url: '{{ route('space.vendor.availability.loadDates') }}',
                        name_required: '{{ __('Name is Required') }}',
                        email_required: '{{ __('Email is Required') }}',
                    };
            </script>
            <script type="text/javascript" src="{{ asset('libs/ion_rangeslider/js/ion.rangeSlider.min.js') }}"></script>
            <script type="text/javascript" src="{{ asset('libs/fotorama/fotorama.js') }}"></script>
            <script type="text/javascript" src="{{ asset('libs/sticky/jquery.sticky.js') }}"></script>
            <script type="text/javascript" src="{{ asset('module/space/js/single-space.js?_ver=' . config('app.version')) }}">
            </script>
            {{-- <script src="{{ asset('js/lightbox.min.js') }}"></script> --}}
            <script src="{{ asset('js/simpleLightbox.min.js') }}"></script>
            <script>
                $(document).ready(function() {
                    $('.gallery-lightbox-images a').simpleLightbox();
                    const sLightBox = new SimpleLightbox({elements: '.gallery-lightbox-images a'});

                    // lightbox.option({
                    //     resizeDuration: 200,  
                    //     wrapAround: true,
                    //     alwaysShowNavOnTouchDevices: true,
                    //     fitImagesInViewport:false
                    // });
                    $(document).on("click", ".triggerLightBox", function() {
                        let position = parseInt($(this).attr("data-index"));
                        // $('.gallery-lightbox-images a')[position].click()
                        sLightBox.showPosition(position);
                    });
                });
            </script>
        @endsection
