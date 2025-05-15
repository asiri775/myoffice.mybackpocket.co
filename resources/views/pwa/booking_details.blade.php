@extends('pwa.layout.index')
@section('content')


    <?php
    //dd($row);
    
    $listingUrl = \App\Helpers\CodeHelper::getFullCurrentUrl();
    $startTime = null;
    $endTime = null;
    
    $startDate = date('m-d-Y', time());
    $endDate = date('m-d-Y', time());
    $toDate = date('m-d-Y', time());
    
    $startTime = '10:00';
    $endTime = '20:00';
    
    $showTimingOption = true;
    
    if ($space_details->available_from == null) {
        $space_details->available_from = '00:00';
    }
    
    if ($space_details->available_to == null) {
        $space_details->available_to = '23:59';
    }
    
    if ($space_details->long_term_rental == 1) {
        // $showTimingOption = false;
        $startTime = $space_details->available_from;
        $endTime = $space_details->available_to;
    }
    
    //below was before in else condition now move into true so always worked
    if (true) {
        if (isset($_GET['start_hour']) && trim($_GET['start_hour']) != null && trim($_GET['start_hour'])) {
            $startTime = trim($_GET['start_hour']);
        }
    
        if (isset($_GET['to_hour']) && trim($_GET['to_hour']) != null && trim($_GET['to_hour'])) {
            $endTime = trim($_GET['to_hour']);
        }
    
        if (isset($_GET['start']) && $_GET['start'] != null) {
            $startDate = trim($_GET['start']);
            if ($startDate == date('Y-m-d')) {
                $startTimeData = date('H:i');
                if ($startTimeData > $startTime) {
                    $startTimeDataExploded = explode(':', $startTimeData);
                    if ($startTimeDataExploded[1] > 0) {
                        $startTimeHR = $startTimeDataExploded[0];
                        $startTimeHR = $startTimeHR + 1;
                        if (strlen($startTimeHR) == 1) {
                            $startTimeHR = '0' . $startTimeHR;
                        }
                        $nextNearestHour = $startTimeHR . ':00';
                        if ($nextNearestHour < $space_details->available_to) {
                            $startTime = $nextNearestHour;
                        }
                    } else {
                        $startTime = $startTimeData;
                    }
                }
            }
        }
    }
    
    if ($space_details->min_hour_stays != null && $startTime != null && $endTime == null) {
        $startTimeExploded = explode(':', $startTime);
        $startTimeHR = trim($startTimeExploded[0]);
        if ($startTimeHR > 0) {
            $startTimeHR = $startTimeHR + $space_details->min_hour_stays;
            $endTime = $startTimeHR . ':00';
        }
    }
    
    if ($startTime != null) {
        if ($startTime < $space_details->available_from) {
            $startTime = $space_details->available_from;
        }
        if ($startTime > $space_details->available_to) {
            $startTime = $space_details->available_from;
        }
    }
    
    if ($endTime != null) {
        if ($endTime < $space_details->available_from) {
            $endTime = $space_details->available_to;
        }
        if ($endTime > $space_details->available_to) {
            $endTime = $space_details->available_to;
        }
    }
    
    if ($startTime != null && $endTime != null) {
        $startTimeExploded = explode(':', $startTime);
        $endTimeExploded = explode(':', $endTime);
        $startTimeHR = trim($startTimeExploded[0]);
        $endTimeHR = trim($endTimeExploded[0]);
        if ($startTimeHR > 0 && $endTimeHR > 0) {
            $diffHour = $endTimeHR - $startTimeHR;
            if ($diffHour < $space_details->min_hour_stays) {
                $startTimeHR = $startTimeHR + $space_details->min_hour_stays;
                $endTime = $startTimeHR . ':00';
            }
        }
    }
    
    $timesNotAvailable = $space_details->getTimesNotAvailable();
    $allDayTimeSlots = \App\Helpers\Constants::getTimeSlots();
    
    $startEndDate = '';
    
    if (isset($_GET['start']) && isset($_GET['end'])) {
        if (!empty(trim($_GET['start'])) && !empty(trim($_GET['end']))) {
            $startDate = $_GET['start'];
            $toDate = $_GET['end'];
            $startEndDate = date('m/d/Y', strtotime(trim($_GET['start']))) . ' - ' . date('m/d/Y', strtotime(trim($_GET['end'])));
        }
    }
    
    $start_hour_state = null;
    $end_hour_state = null;
    
    if ($startTime != null) {
        $start_hour_state = \App\Helpers\CodeHelper::getAMPMFromHourTime($startTime);
        $startTime = \App\Helpers\CodeHelper::getSmallMinTimeFromHourTime($startTime);
    }
    
    if ($endTime != null) {
        $end_hour_state = \App\Helpers\CodeHelper::getAMPMFromHourTime($endTime);
        $endTime = \App\Helpers\CodeHelper::getSmallMinTimeFromHourTime($endTime);
    }
    //$showTimingOption = true;
    
    $distance = $space_details->addressWithDistance();
    
    ?>


    <style type="text/css">
        .whenInputC {
            display: none;
        } 

        .book-model-form .detailformrow {
            display: flex;
            align-items: center;
        }

        .book-model-form .detailformrow .dateInputC,
        .book-model-form .detailformrow .timeInputC {
            width: 100%;
            margin: 10px 0;
        }

        .book-model-form .detailformrow .dateInputC {
            padding-right: 10px
        }

        .book-model-form .detailformrow .dateInputC input {
            width: 100%;
        }

        .appBottomMenu {
            display: none;
        }

        .book-model-form {
            border: 1px solid #eee;
            padding: 15px;
            margin: 0 -15px;
            border-radius: 5px;
        }

        .book-model-form input,
        .book-model-form select {
            padding: 5px;
            border-radius: 5px;
            height: 35px;
            border: 1px solid #ddd;
            font-size: 14px;
        }

        .book-model-form .form-section-group {
            padding: 0 10px;
        }

        .book-model-form label {
            font-size: 12px;
        }

        .book-model-form .submit-group {
            margin-top: 15px;
        }

        .book-model-form .submit-group a {
            background: #ffc107;
            color: #000;
            width: 100%;
            display: block;
            line-height: 28px;
        }
    </style>

    <!-- loader -->
    <div id="loader">
        <img src="{{ url('pwa') }}/assets/img/logo_instagram.png" alt="icon" class="loading-icon">
    </div>

    <!-- <div id="bookingFooter">
        @php
            $priceDetails = $space_details->getDefaultPrice($space_details->id);
        @endphp
        <div class="left">
            <p><span class="price" id="bookingTImeInfoPrice">${{ $priceDetails['price'] }}</span></p>
            <p class="sm" id="bookingTImeInfo">Oct 1-4</p>
        </div>
        <div class="right">
            <a href="javascript:;" id="bookNowBtn" class="btn btn-primary">Book</a>
        </div>
    </div> -->

    <!-- * loader -->

    <!-- App Header -->
    <div class="appHeader br-0 mb-10">
        <div class="left">
            <a href="#" class="headerButton goBack">
                <ion-icon name="chevron-back-outline"></ion-icon>
            </a>
        </div>

        <div class="right">
            <span class="icon mt-10">
                <ion-icon class="icon mr-10 add-to-fav  @if ($space_details->isfavourite()) active @endif" name="heart"
                    space_id={{ $space_details->id }}>
                    <div class='red-bg'></div>
                </ion-icon>
            </span>
            <a href="#" data-bs-toggle="modal" data-bs-target="#actionSheetForm6">
                <ion-icon class="vl-m" name="share-outline"></ion-icon>
            </a>
        </div>

    </div>
    <!-- * App Header -->


    <!-- App Capsule -->
    <div id="appCapsule" class="full-height">



        <!--  -->
        <section class="">

            <div class="swiper-container swiper-full-mobile swiper-container-initialized swiper-container-horizontal">
                <div class="swiper-wrapper">


                    <?php
                    
                    $images = $space_details->gallery;
                    $explode_images = explode(',', $images);
                    
                    ?>


                    @foreach ($explode_images as $row)
                        <div class="swiper-slide">
                            <img src="{{ get_file_url($row, 'full') }}" alt="">
                            <div class="card-img-overlay  pt-140">
                                <!-- <p class="img-count">1/ {{ count($explode_images) }}</p> -->
                            </div>
                        </div>
                    @endforeach
                    <!-- <div class="swiper-slide">
                                                                      <img src="assets/img/sample/Image.png" alt="">
                                                                          <div class="card-img-overlay  pt-140">
                                                                            <p class="img-count">2/5</p>
                                                                      </div>
                                                                     </div>
                                                                      <div class="swiper-slide">
                                                                      <img src="assets/img/sample/Image.png" alt="">
                                                                          <div class="card-img-overlay  pt-140">
                                                                            <p class="img-count">3/5</p>
                                                                      </div>
                                                                     </div>
                                                                     <div class="swiper-slide">
                                                                      <img src="assets/img/sample/Image.png" alt="">
                                                                          <div class="card-img-overlay  pt-140">
                                                                            <p class="img-count">4/5</p>
                                                                      </div>
                                                                     </div>
                                                                     <div class="swiper-slide">
                                                                      <img src="assets/img/sample/Image.png" alt="">
                                                                          <div class="card-img-overlay  pt-140">
                                                                            <p class="img-count">5/5</p>
                                                                      </div>
                                                                     </div> -->

                </div>

            </div>

            <!-- Add Pagination -->
            <!-- Add Arrows -->

        </section>


        <div class="listing-details-header">
            <div>
                <h3>{{ $space_details->title }}</h3>

                @include('pwa.common.rating', ['type' => 'tiles', 'rating' => $space_details->review_score])

                <a href={{ $distance['link'] }}>{!! $distance['address'] !!}</a>

                <!-- <div class="mt-10 mb-20">
                                            <ul class=" inline nav nav-tabs br-0"  role="tablist">
                                                <li class="nav-item  inline">
                                              <a class="nav-links" href="#reviews" data-bs-toggle="tab" role="tab">
                                            <div class="stars lh-16 m-10 lh-16 inline"> <span class="star on lh-16"></span>
                                                <span class="star on"></span>
                                                <span class="star on"></span>
                                                <span class="star on"></span>
                                                <span class="star"></span>
                                              </div>
                                             
                                                <p class="f-11 inline mb-0 v-good t-ul">(349 Reviews)</p>
                                              </a>
                                                </li>
                                              </ul>
                                              <h4 class="inline rating fw-b">8.8</h4>

                                        </div> -->
            </div>
        </div>



        <!--  -->
        <div class="card box-s">
            <div class=" pt-0">

                <ul class="nav nav-tabs lined  mt-1" role="tablist">
                    <li class="nav-item">
                        <a class="nav-links col-b f-11 active fw-bold tabs" data-bs-toggle="tab" href="#booking"
                            role="tab" aria-selected="false">
                            BOOKING
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-links col-b f-11  fw-bold" data-bs-toggle="tab" href="#details" role="tab"
                            aria-selected="true">
                            DETAILS
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-links col-b f-11  fw-bold" data-bs-toggle="tab" href="#location" role="tab"
                            aria-selected="true">
                            LOCATION
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-links col-b f-11 fw-bold " data-bs-toggle="tab" href="#amenities" role="tab"
                            aria-selected="true">
                            AMENITIES
                        </a>
                    </li>
                    <!-- <li class="nav-item">
                                                                            <a class="nav-links col-b f-11 fw-bold " data-bs-toggle="tab" href="#reviews" role="tab" aria-selected="true">
                                                                                REVIEWS
                                                                            </a>
                                                                        </li> -->

                </ul>
                <!-- Booking -->
                <div class=" card-body tab-content pt-0 mt-05">

                    <div class="section tab-pane active show fade" id="booking" role="tabpanel">
                        <div class=" mt-2">
                            <!-- <div class="pageTitle pr-2">
                                                                                <button type="button" class="btn btn-text-primary rounded shadowed"  data-bs-toggle="modal" data-bs-target="#actionSheetForm5"><ion-icon class="f-13" name="person-outline"></ion-icon>1&middot;Today</button>
                                                                            </div> -->
                            <!-- Scroll -->
                            <div class=" mt-2 book-model-form">
                                <!-- <div class="carousel-small splide splide--draggable splide--slide splide--ltr is-active" id="splide04" style="visibility: visible;">
                                                                                <div class="splide__track" id="splide04-track" style="padding-left: 18px; padding-right: 18px;">
                                                                                    <ul class="splide__list splidelist" id="splide04-list" style="transform: translateX(0px);">
                                                                                      
                                                                                        <li class="splide__slide splide__slide--clone is-visible" style="margin-right: 10px; width: 106.333px;" id="splide04-slide03" aria-hidden="false" tabindex="0">
                                                                                            <a href="#">
                                                                                                <div class="dropdown">
                                                                                                    <button class=" btn f-13 btn-primary  item" type="button" data-bs-toggle="modal" data-bs-target="#actionSheetForm3">
                                                                                                        7:00 AM
                                                                                                    </button>
                                                                                                </div>
                                                                                            </a>
                                                                                        </li>
                                                                                        <li class="splide__slide splide__slide--clone is-visible" style="margin-right: 10px; width: 106.333px;" id="splide04-slide04" aria-hidden="false" tabindex="0">
                                                                                            <a href="#">
                                                                                                <div class="dropdown">
                                                                                                    <button class=" btn f-13 btn-primary   item" type="button" data-bs-toggle="modal" data-bs-target="#actionSheetForm4">
                                                                                                        8:00 AM
                                                                                                    </button>
                                                                                                </div>
                                                                                            </a>
                                                                                        </li>
                                                                                        <li class="splide__slide splide__slide--clone is-visible" style="margin-right: 10px; width: 106.333px;" id="splide04-slide04" aria-hidden="false" tabindex="0">
                                                                                            <a href="#">
                                                                                                <div class="dropdown">
                                                                                                    <button class=" btn f-13 btn-primary  item" type="button" data-bs-toggle="modal" data-bs-target="#actionSheetForm4">
                                                                                                        9:00 AM
                                                                                                    </button>
                                                                                                </div>
                                                                                            </a>
                                                                                        </li>
                                                                                        <li class="splide__slide splide__slide--clone is-visible" style="margin-right: 10px; width: 106.333px;" id="splide04-slide04" aria-hidden="false" tabindex="0">
                                                                                            <a href="#">
                                                                                                <div class="dropdown">
                                                                                                    <button class=" btn f-13 btn-primary   item" type="button" data-bs-toggle="modal" data-bs-target="#actionSheetForm4">
                                                                                                        10:00 AM
                                                                                                    </button>
                                                                                                </div>
                                                                                            </a>
                                                                                        </li>
                                                                                    </ul>
                                                                                   
                                                                                </div>
                                                                            </div> -->
                                <!-- <hr class="solid"> -->
                                <!-- <div class="">
                                                                                <h3>Cozy Corner Office</h3>
                                                                            </div> -->
                                {{-- <hr class="solid"> --}}
                                <div class="row">
                                    <div class="col ">
                                        <!-- <h4 class="fw-b pl-10">Max Guests: {{ $space_details->max_guests }}</h4> -->
                                        <!-- <h4 class="fw-b pl-10">3 Chairs</h4> -->
                                        <div class="inline br-0">
                             
                                        <p> <b>Start Date :</b> {{date('m-d-Y h:i A', strtotime($booking_details->start_date))}}</p>

                                        <p> <b>Start Date :</b> {{date('m-d-Y h:i A', strtotime($booking_details->end_date))}}</p>

                                        <p> <b>Totat Guests :</b> {{$booking_details->total_guests}}</p>

                                        <p> <b>Amount :</b> ${{$booking_details->total}}</p>


                                        </div>
                                    </div>
                               
                                  

                                </div>
                                {{-- <hr class="solid"> --}}
                            </div>
                        </div>
                    </div>
                    <!-- //Booking -->
                    <div class="tab-pane fade" id="details" role="tabpanel">
                        <div class=" mt-4">
                            <div class="">
                                <h3>About This Space</h3>
                            </div>
                            <div class="wrapper">
                                <!-- <div class="small">
                                                                                    <p class="f-13"> {{ $space_details->content }}</p>
                                                                                </div> -->
                                @if ($space_details->content)
                                    <div class="g-overview">
                                        <div class="description">
                                            <?php echo $space_details->content; ?>
                                        </div>
                                    </div>
                                @endif
                                {{-- <a href="#" class="f-13 fw-b c-b">Read More</a> --}}
                            </div>

                        </div>
                    </div>
                    <div class="tab-pane fade" id="location" role="tabpanel">
                        <div class=" mt-4">
                            <div class="">
                            </div>
                            <div class="row">

                                <?php
                                // $mapLink = 'https://www.google.com/maps/place/' . trim(urlencode($space_details->address));
                                ?>

                                <div class="col-8 address-n-things">
                                    <h3>Location</h3>
                                    <p class="address-n-text inline f-11 vl-m light">{!! $distance['title'] !!}</p>
                                    <a href="{{ $distance['link'] }}" target="_blank">
                                        View Map and Directions
                                    </a>
                                </div>

                                <div class="col-4">

                                    <!-- <div class="space-listing-iframe">
                                        <iframe width="100" height="100" frameborder="0" style="border:0"
                                            src="https://www.google.com/maps/embed/v1/place?q=33.8866584,-118.4053577&key=AIzaSyCRu_qlT0HNjPcs45NXXiOSMd3btAUduSc">
                                        </iframe>
                                    </div> -->
                                    <a href={{ $distance['link'] }} target="_blank" > 
                                        <img class="map_icon_on_detail_page"  src="{{$space_details->mapViewImage()}}" alt="Map">
                                    </a>

                                </div>
                            </div>


                        </div>
                    </div>
                    <div class="tab-pane fade" id="amenities" role="tabpanel">
                        <div class=" mt-2">
                            <div class="section-heading ">
                                <h3>Amenities</h3>
                                <!-- <a href="#" class="link fw-b">View All</a> -->
                            </div>
                        </div>
                        <div class=" row section full  mr-40 ">
                            <!-- carousel small -->
                            <div class=" carousel-small splide  splide--draggable " id="splide04"
                                style="visibility: visible;">
                                <div class="splide__track" id="splide04-track"
                                    style="padding-left: 16px; padding-right: 16px;">
                                    <ul class="splide__list splidelist" id="splide04-list"
                                        style="transform: translateX(-1262.25px);">

                                        @php
                                            
                                            $terms_ids = \Modules\Space\Models\SpaceTerm::where('target_id', $space_details->id)->pluck('term_id');
                                            
                                            $attributes_terms = \Modules\Core\Models\Terms::query()
                                                ->with(['translations', 'attribute'])
                                                ->find($terms_ids)
                                                ->where('attr_id', 4)
                                                ->pluck('id')
                                                ->toArray();
                                            
                                            $attributes = \Modules\Core\Models\Terms::where('attr_id', 4)->get();
                                            
                                        @endphp

                                        <br>
                                        <ul class="aminitlistingul mgnT20">
                                            <?php
                                            $amentiesCount = 0;
                                            ?>
                                            @if (count($attributes_terms) > 0)
                                                @foreach ($attributes as $attribute)
                                                    @if (in_array($attribute->id, $attributes_terms))
                                                        <?php
                                                        $amentiesCount = $amentiesCount + 1;
                                                        ?>
                                                        <li
                                                            class="space_amenities_list detaillistingli {{ in_array($attribute->id, $attributes_terms) ? '' : 'fade_amenities' }} fulwidthm mgnB10 {{ $amentiesCount > 10 ? 'not_show_space_amenities' : 'show_space_amenities' }}">
                                                            <i class="aminti_icon {{ $attribute->icon }}"></i>
                                                            <span class="aminidis">{{ $attribute->name }}</span>
                                                        </li>
                                                    @endif
                                                @endforeach
                                                @if (count($attributes_terms) > 10)
                                                    <p id="viewMoreAmenities_in_space">Load {{ $amentiesCount - 2 }} more
                                                        Amenities</p>
                                                @endif
                                            @else
                                                <p>No Amenities</p>
                                            @endif
                                        </ul>

                                    </ul>

                                </div>
                            </div>
                        </div>


                        <hr class="solid">

                        <!-- * carousel small -->
                    </div>

                    <!-- Reviews -->
                    <div class="tab-pane fade" id="reviews" role="tabpanel">
                        <div class=" mt-2">
                            <h3>Reviews</h3>
                            <div class="row">
                                <div class="col-2">
                                    <img class="review-img" src="assets/img/sample/avatar/avatar9.jpg" alt="">
                                </div>
                                <div class="col-10">
                                    <h4 class="mb-0">Sri Wedari Soekarno</h4>
                                    <p>May 2018</p>
                                </div>
                            </div>
                            <div class="wrapper1 f-13">
                                <div class="small1 f-13">
                                    <p class="f-13"> Absolutely perfect! Best sea house in earth, this is a gated
                                        property, architects
                                        , neighborhood live next door You will find feeling.
                                    </p>
                                </div>
                                <a href="#" class="fw-b c-b">Read More</a>
                            </div>
                            <div class="text-center mt-2">
                                <a href="reviews.html">
                                    <button class=" btn f-13 btn-review br-20  item" type="button"
                                        data-bs-toggle="modal" data-bs-target="#actionSheetForm4">
                                        Read all 237 reviews
                                    </button>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Tabs End -->
        <!-- Similar space start -->
        <div class="section full mt-2">
            <div class="section-heading padding">
                <h2 class="title">Similar Spaces Nearby</h2>
                <!--  <a href="search.html" class="link fw-b">View All</a> -->
            </div>


            <!-- * carousel single -->
            <!-- carousel small -->

            <div class="carousel-multiple splide splide--loop splide--ltr splide--draggable is-active" id="splide03"
                style="visibility: visible;">
                <div class="splide__track" id="splide03-track" style="padding-left: 16px; padding-right: 16px;">
                    <ul class="splide__list" id="splide03-list" style="transform: translateX(-2520.19px);">

                        @foreach ($similar_space as $row)
                            @include('pwa.common.single_space_tile_wise', ['row' => $row])
                        @endforeach
                    </ul>
                </div>
            </div>
            <!-- XD-Mockup -->
            <!-- <div class="carousel-multiple splide splide--loop splide--ltr splide--draggable is-active" id="splide03" style="visibility: visible;">
                                                                        <div class="splide__track" id="splide03-track" style="padding-left: 16px; padding-right: 16px;">
                                                                            <ul class="splide__list" id="splide03-list" style="transform: translateX(-2520.19px);">

                                                                                <li class="splide__slide splide__slide--clone" aria-hidden="true" tabindex="-1" style="margin-right: 16px; width: 156px;">
                                                                                    <a href="#">
                                                                                        <div class="blog-card">
                                                                                            <div class="img-wrapper">
                                                                                                <img src="assets/img/sample/photo/1.jpg" alt="image" class="imageds w-100">
                                                                                            </div>
                                                                                            <div class="mt-10">
                                                                                                <p class="f-11  mb-0 v-good">5 Stars </p>
                                                                                                <h3 class="titl">New Clayton Hotel Birmingham</h3>
                                                                                                <a href="#" class="inline"> <ion-icon  name="location-outline"></ion-icon></a>
                                                                                                <p class="inline f-11 vl-m light">Birmingham City Center</p>
                                                                                                <p class="f-13 mb-1 ">$15 per hour</p>
                                                                                                <h4 class="inline rating">8.8</h4>
                                                                                                <p class="f-11 inline mb-0 v-good">Very Good </p>
                                                                                            </div>
                                                                                        </div>
                                                                                    </a>
                                                                                </li>
                                                                                <li class="splide__slide splide__slide--clone" aria-hidden="true" tabindex="-1" style="margin-right: 16px; width: 156px;">
                                                                                    <a href="#">
                                                                                        <div class="blog-card">
                                                                                            <div class="img-wrapper">
                                                                                                <img src="assets/img/sample/photo/1.jpg" alt="image" class="imageds w-100">
                                                                                            </div>
                                                                                            <div class="mt-10">
                                                                                                <p class="f-11  mb-0 v-good">4 Stars </p>
                                                                                                <h3 class="titl">Ibis Milano Centro Milan</h3>
                                                                                                <a href="#" class="inline"> <ion-icon  name="location-outline"></ion-icon></a>
                                                                                                <p class="inline f-11 vl-m light">Milan City Center</p>
                                                                                                <p class="f-13 mb-1 ">$32 per hour <span class="gree">(25% Off)</span></p>
                                                                                                <h4 class="inline rating">7.9</h4>
                                                                                                <p class="f-11 inline mb-0 v-good">Very Good </p>
                                                                                            </div>
                                                                                        </div>
                                                                                    </a>
                                                                                </li>
                                                                                <li class="splide__slide splide__slide--clone" aria-hidden="true" tabindex="-1" style="margin-right: 16px; width: 156px;">
                                                                                    <a href="#">
                                                                                        <div class="blog-card">
                                                                                            <div class="img-wrapper">
                                                                                                <img src="assets/img/sample/photo/1.jpg" alt="image" class="imageds w-100">
                                                                                            </div>
                                                                                            <div class="mt-10">
                                                                                                <p class="f-11  mb-0 v-good">5 Stars </p>
                                                                                                <h3 class="titl">Zoo Hotel Poliziano Fiera</h3>
                                                                                                <a href="#" class="inline"> <ion-icon  name="location-outline"></ion-icon></a>
                                                                                                <p class="inline f-11 vl-m light">Birmingham City Center</p>
                                                                                                <p class="f-13 mb-1 ">$178 per night</p>
                                                                                                <h4 class="inline rating">8.8</h4>
                                                                                                <p class="f-11 inline mb-0 v-good">Very Good </p>
                                                                                            </div>
                                                                                        </div>
                                                                                    </a>
                                                                                </li>
                                                                               
                                                                               
                                                                            </ul>
                                                                        </div>
                                                                    </div> -->

        </div>

        <?php
        /*
                                                                        ?>
        ?>
        ?>
        ?>
        ?>
        ?>
        ?>


        <hr class="solid">
        <!-- //Similar spaces end -->
        <!-- More Options -->
        <div class="section full mt-2">
            <div class="section-heading padding">
                <h2 class="title">More Options</h2>

            </div>

            <div class="section">
                <a href="checkin.html">
                    <p class=" inline f-12">Check In and Out Procedures</p>
                    <ion-icon class=" fl-r f-20 vl-m" name="chevron-forward-outline"></ion-icon>
                </a>
                <hr class="solid">
            </div>
            <div class="section">
                <a href="space-policies.html">
                    <p class="inline f-12">Space Policies</p>
                    <ion-icon class="fl-r f-20 vl-m" name="chevron-forward-outline"></ion-icon>
                </a>
                <hr class="solid">
            </div>
            <div class="section">
                <a href="health-protocols.html">
                    <p class="inline f-12">Health and Safety Protocols</p>
                    <ion-icon class="fl-r f-20 vl-m" name="chevron-forward-outline"></ion-icon>
                </a>
                <hr class="solid">
            </div>

        </div>

        <?php
        */
        ?>

        <div class="section full mt-50 mr-40 ">



        </div>

        <div class="modal fade modalbox" id="ModalBasic" tabindex="-1" style="display: none;" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"> Filter</h5>
                        <a href="#" class="ms-1 close" data-bs-dismiss="modal"><i
                                class="icon f-16 ion-ios-close-circle"></i></a>

                    </div>
                    <div class="modal-body">
                        <div class="card bg-g mt-2 mb-2 px-3 pt-3 pb-3">
                            <div class="form-group boxed">
                                <div class="input-wrapper">
                                    <label class="label" for="text4b">Search by Name</label>
                                    <input type="text" class="form-control" id="text4b" placeholder="">
                                    <i class="clear-input">
                                        <ion-icon name="close-circle" role="img" class="md hydrated"
                                            aria-label="close circle"></ion-icon>
                                    </i>
                                </div>
                            </div>
                            <div class="form-group boxed">
                                <div class="input-wrapper">
                                    <label class="label" for="text4b">Quick Date</label>
                                    <select class="form-select" aria-label="Default select example">
                                        <option selected="">Select Date</option>
                                        <option value="1">All Time</option>
                                        <option value="2">Today</option>
                                        <option value="3">This Week</option>
                                        <option value="4">This Month</option>
                                        <option value="5">This Year to Date</option>
                                    </select>
                                    <i class="clear-input">
                                        <ion-icon name="close-circle" role="img" class="md hydrated"
                                            aria-label="close circle"></ion-icon>
                                    </i>
                                </div>
                            </div>
                            <form class="search-form">
                                <div class="form-group advance-search mb-1">
                                    <label class="label mb-1" for="text4b">Date Range</label>
                                    <div class="input-group input-daterange">
                                        <input type="date" class="form-control" name="date">
                                        <div class="input-group-addon lh-40 px-2">to</div>
                                        <input type="date" class="form-control" name="date">
                                    </div>
                                </div>
                                <div class="form-group boxed">
                                    <div class="input-wrapper">
                                        <label class="label" for="text4b">Category</label>
                                        <select class="form-select" aria-label="Default select example">
                                            <option selected="">Open this select menu</option>
                                            <option value="1">Option 1</option>
                                            <option value="2">Option 2</option>
                                            <option value="3">Option 3</option>
                                        </select>
                                        <i class="clear-input">
                                            <ion-icon name="close-circle" role="img" class="md hydrated"
                                                aria-label="close circle"></ion-icon>
                                        </i>
                                    </div>
                                </div>
                                <div class="form-group boxed">
                                    <div class="input-wrapper">
                                        <label class="label" for="text4b">Tansaction Id</label>
                                        <input type="text" class="form-control" id="text4b"
                                            placeholder="Enter trans#">
                                        <i class="clear-input">
                                            <ion-icon name="close-circle" role="img" class="md hydrated"
                                                aria-label="close circle"></ion-icon>
                                        </i>
                                    </div>
                                </div>
                                <div class="form-group boxed advance-search mb-1">
                                    <label class="label mb-1" for="text4b">Amount</label>
                                    <div class="input-group input-daterange">
                                        <input type="number" class="form-control" name="amount"
                                            placeholder="Max Amount">
                                        <div class="input-group-addon lh-40 px-2">to</div>
                                        <input type="number" class="form-control" name="amount"
                                            placeholder="Min Amount">
                                    </div>
                                </div>
                                <div class="form-group boxed">
                                    <div class="input-wrapper">
                                        <label class="label" for="text4b">Payment Method</label>
                                        <select class="form-select" aria-label="Default select example">
                                            <option selected="">Credit</option>
                                            <option value="1">Option 1</option>
                                            <option value="2">Option 2</option>
                                            <option value="3">Option 3</option>
                                        </select>
                                        <i class="clear-input">
                                            <ion-icon name="close-circle" role="img" class="md hydrated"
                                                aria-label="close circle"></ion-icon>
                                        </i>
                                    </div>
                                </div>
                            </form>
                            <button type="button" class="btn btn-primary btn-lg btn-block mt-2 me-1 mb-2">Search</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!--  -->

        <!-- Form Action Sheet1 -->
        <div class="modal fade action-sheet" id="actionSheetForm" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Categories</h5>
                    </div>
                    <div class="modal-body bg-g">
                        <div class="action-sheet-content">

                            <div class="card">
                                <div class="card-body">

                                    <div class="form-check mb-1">
                                        <input type="checkbox" class="form-check-input" id="customCheckb1">
                                        <label class="form-check-label" for="customCheckb1">Option1</label>
                                    </div>
                                    <div class="form-check mb-1">
                                        <input type="checkbox" class="form-check-input" id="customCheckb2">
                                        <label class="form-check-label" for="customCheckb2">Option2</label>
                                    </div>
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="customCheckb3">
                                        <label class="form-check-label" for="customCheckb3">Option3</label>
                                    </div>

                                </div>
                            </div>
                            <button type="button" class="btn btn-primary btn-lg btn-block mt-2 me-1 mb-2">Apply</button>


                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- * Form Action Sheet1 -->
        <!-- Form Action Sheet2 -->
        <div class="modal fade action-sheet" id="actionSheetForm2" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Amenities</h5>
                    </div>
                    <div class="modal-body bg-g">
                        <div class="action-sheet-content">

                            <div class="card">
                                <div class="card-body">

                                    <div class="form-check mb-1">
                                        <input class="form-check-input" type="radio" name="flexRadioDefault"
                                            id="flexRadioDefault1">
                                        <label class="form-check-label" for="flexRadioDefault1">
                                            Option1
                                        </label>
                                    </div>
                                    <div class="form-check mb-1">
                                        <input class="form-check-input" type="radio" name="flexRadioDefault"
                                            id="flexRadioDefault2">
                                        <label class="form-check-label" for="flexRadioDefault2">
                                            Option2
                                        </label>
                                    </div>
                                    <div class="form-check mb-1">
                                        <input class="form-check-input" type="radio" name="flexRadioDefault"
                                            id="flexRadioDefault3">
                                        <label class="form-check-label" for="flexRadioDefault3">
                                            Option3
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="flexRadioDefault"
                                            id="flexRadioDefault4">
                                        <label class="form-check-label" for="flexRadioDefault4">
                                            Option4
                                        </label>
                                    </div>

                                </div>
                            </div>
                            <button type="button" class="btn btn-primary btn-lg btn-block mt-2 me-1 mb-2">Apply</button>


                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- * Form Action Sheet2 -->
        <!-- Form Action Sheet3 -->
        <div class="modal fade action-sheet" id="actionSheetForm3" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Capacity</h5>
                    </div>
                    <div class="modal-body bg-g">
                        <div class="action-sheet-content">

                            <div class="section">
                                <div class="row mt-2">
                                    <div class="col-6">
                                        <button type="button" class="btn bg-w opt btn-lg">Option1</button>
                                    </div>
                                    <div class="col-6">
                                        <button type="button" class="btn bg-w opt btn-lg">Option2</button>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-6">
                                        <button type="button" class="btn bg-w opt btn-lg">Option3</button>
                                    </div>
                                    <div class="col-6">
                                        <button type="button" class="btn bg-w opt btn-lg">Option4</button>
                                    </div>
                                </div>
                            </div>
                            <button type="button" class="btn btn-primary btn-lg btn-block mt-2 me-1 mb-2">Apply</button>


                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- * Form Action Sheet3 -->
        <!-- Form Action Sheet4 -->
        <div class="modal fade action-sheet" id="actionSheetForm4" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Capacity</h5>
                    </div>
                    <div class="modal-body bg-g">
                        <div class="action-sheet-content">

                            <div class="section">
                                <input type="checkbox" id="" name="" value="">
                                <label for="vehicle1"> Option1</label><br>
                                <input type="checkbox" id="" name="" value="">
                                <label for="vehicle2"> Option2</label><br>
                                <input type="checkbox" id="" name="" value="">
                                <label for="vehicle3"> Option3</label>
                            </div>
                            <button type="button" class="btn btn-primary btn-lg btn-block mt-2 me-1 mb-2">Apply</button>


                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- * Form Action Sheet4 -->
        <!-- Form Action Sheet5 -->
        <div class="modal fade action-sheet" id="actionSheetForm5" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Action modal</h5>
                    </div>
                    <div class="modal-body bg-g">
                        <div class="action-sheet-content">


                            <button type="button" class="btn btn-primary btn-lg btn-block mt-2 me-1 mb-2">Apply</button>


                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- * Form Action Sheet5 -->
        <!-- Form Action Sheet6 -->
        <div class="modal fade action-sheet" id="actionSheetForm6" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Share</h5>
                        <!-- <a href="#" class="ms-1 close text-end" data-bs-dismiss="modal"><i class="f-20 icon ion-ios-close-circle"></i></a> -->
                    </div>
                    <div class="modal-body bg-g">
                        <div class="action-sheet-content">


                            <div class="row px-3 text-center mt-3 mb-3" style="justify-content: center">
                                <div class="col-2">

                                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ $listingUrl }}"
                                        target="_blank" class="btn btn-icon btn-facebook">
                                        <ion-icon name="logo-facebook" role="img" class="md hydrated"
                                            aria-label="logo facebook"></ion-icon>
                                    </a>
                                </div>
                                <div class="col-2">
                                    <a href="https://twitter.com/intent/tweet?text={{ $listingUrl }}" target="_blank"
                                        class="btn btn-icon btn-twitter">
                                        <ion-icon name="logo-twitter" role="img" class="md hydrated"
                                            aria-label="logo twitter"></ion-icon>
                                    </a>
                                </div>
                                <div class="col-2">
                                    <a href="https://www.linkedin.com/shareArticle?url={{ $listingUrl }}"
                                        target="_blank" class="btn btn-icon btn-linkedin">
                                        <ion-icon name="logo-linkedin" role="img" class="md hydrated"
                                            aria-label="logo linkedin"></ion-icon>
                                    </a>
                                </div>
                                <div class="col-2">
                                    <a href="https://web.whatsapp.com/send?text={{ $listingUrl }}" target="_blank"
                                        class="btn btn-icon btn-whatsapp">
                                        <ion-icon name="logo-whatsapp" role="img" class="md hydrated"
                                            aria-label="logo whatsapp"></ion-icon>
                                    </a>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- * Form Action Sheet6 -->
        <!--  -->



    </div>
    <!-- * App Capsule -->
    <div class="appBottomMenu">
        <a href="homev1.html" class="item active">
            <div class="col">
                <ion-icon name="home-outline" role="img" class="md hydrated" aria-label="home outline"></ion-icon>
                <strong>Home</strong>
            </div>
        </a>
        <a href="homev2.html" class="item">
            <div class="col">
                <ion-icon name="document-text-outline" role="img" class="md hydrated"
                    aria-label="document text outline"></ion-icon>
                <strong>Bookings</strong>
            </div>
        </a>
        <a href="search.html" class="item">
            <div class=" action-button large">
                <!-- <ion-icon class="blk" name="share-social-outline"></ion-icon>       -->
                <img class="text-center pt-04" src="assets/img/share.png" alt="" width="40">
            </div>
            <strong class="sear">Search</strong>

        </a>
        <a href="listingv1.html" class="item">
            <div class="col">
                <ion-icon name="mail-outline"></ion-icon>
                <strong>Inbox</strong>
            </div>
        </a>
        <a href="listingv2.html" class="item">
            <div class="col">
                <ion-icon name="person-circle-outline" role="img" class="md hydrated"
                    aria-label="person circle outline"></ion-icon>
                <strong>Profile</strong>
            </div>
        </a>
    </div>

    <!-- ========= JS Files =========  -->

@endsection

@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Swiper/5.4.5/js/swiper.min.js"></script>

    <script>
        $('.wrapper').find('a[href="#"]').on('click', function(e) {
            e.preventDefault();
            this.expand = !this.expand;
            $(this).text(this.expand ? "" : "Read More");
            $(this).closest('.wrapper').find('.small, .big').toggleClass('small big');
        });
        $('.wrapper1').find('a[href="#"]').on('click', function(e) {
            e.preventDefault();
            this.expand = !this.expand;
            $(this).text(this.expand ? "" : "Read More");
            $(this).closest('.wrapper1').find('.small1, .big').toggleClass('small1 big');
        });


        var swiperMobile = new Swiper('.swiper-container.swiper-full-mobile', {
            slidesPerView: 1,
            spaceBetween: 50,
            slideToClickedSlide: true,
            centeredSlides: true,
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            loop: false,
            //   autoplay: {
            //     delay: 100000,
            //   },
            keyboard: {
                enabled: true,
                onlyInViewport: true,
            },
            breakpoints: {

                640: {
                    freemode: true,
                    slidesPerView: 2,
                    spaceBetween: 20,
                },
                320: {
                    freemode: true,
                    slidesPerView: 1,
                    spaceBetween: 20,
                }
            }

        });
    </script>
    

    <script>
        let blockedTimes = <?= json_encode($timesNotAvailable) ?>;

        let availabilityTimeCalendar = null;


        function showAvailabilityCalendarModal() {
            $("#availabilityCalendar").modal("show");
            availabilityTimeCalendar = new FullCalendar.Calendar(document.getElementById('availabilityTimeCalendar'), {
                eventSources: [{
                    url: '{{ route('space.vendor.availability.availableDates') }}?id={{ $row->id }}',
                }],
                headerToolbar: {
                    left: 'prevYear,prev,next,nextYear today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                initialView: 'dayGridMonth',
                dayMaxEvents: true,
                navLinks: true,
                eventClick: function(eventInfo) {
                    let eventId = eventInfo.event.id;
                }
            });
            availabilityTimeCalendar.render();
        }

        function showNotification(message, type = "error") {
            window.webAlerts.push({
                        type: type,
                        message: message
                    });
            // switch (type) {
            //     case "error":
            //         toastr.error(message);
            //         $("#msgx-error").html(message);
            //         break;
            //     case "success":
            //         toastr.success(message);
            //         break;
            //     default:
            //         toastr.info(message);
            //         break;
            // }
        }

        let SEARCH_AJAX_REQUEST = null;

        function checkTimeAvailability(showAlerts = true) {
            showInfoOnBootomBar();
            console.log("checkTimeAvailability called 3");
            let checkAvailability = true;

            let startDate = $('#dpd1x1').val().toString().trim();
            if (startDate === '') {
                checkAvailability = false;
            }

            let endDate = $('#dpd2x2').val().toString().trim();
            if (endDate === '') {
                checkAvailability = false;
                if (startDate !== '') {
                    $('#dpd2x2').val(startDate);
                }
            }

            console.log("startDatedd", startDate);

            let startHour = $('select[name="start_hour"]').val().toString().trim();
            if (startHour === '') {
                checkAvailability = false;
            }

            let endHour = $('select[name="end_hour"]').val().toString().trim();
            if (endHour === '') {
                checkAvailability = false;
            }

            let startAmpPm = $("#start_ampm option:selected").val().toString().trim();
            if (startAmpPm === '') {
                checkAvailability = false;
            }

            let endAmpPm = $("#end_ampm option:selected").val().toString().trim();
            if (endAmpPm === '') {
                checkAvailability = false;
            }

            if (checkAvailability) {

                if (SEARCH_AJAX_REQUEST != null) {
                    SEARCH_AJAX_REQUEST.abort();
                    SEARCH_AJAX_REQUEST = null;
                }


                $("#alreadyBookedFor").hide();
                $("#msgx-error").hide();
                $("#msgx-success").hide();
                $('#loading-image').hide();
                $('#spaceBookBtn').addClass('disabled');
                $("#start_hour option:selected").val();

                $("#spaceCalPrice").hide();

                console.log("startDate", startDate);

                SEARCH_AJAX_REQUEST = $.post("{{ route('space.vendor.availability.verifySelectedTimes') }}", {
                    id: {{ $row->id }},
                    start_date: moment(startDate).format("MM/DD/YYYY"),
                    end_date: moment(endDate).format("MM/DD/YYYY"),
                    start_hour: startHour,
                    end_hour: endHour,
                    start_ampm: startAmpPm,
                    end_ampm: endAmpPm,
                }, function(response) {
                    if (response.status == 'error') {
                        $('#loading-image').hide();
                        if (showAlerts) {
                            showNotification(response.message);
                            $("#msgx-error").show();

                        }
                    } else if (response.status == 'success') {
                        $('#loading-image').hide();
                        if (showAlerts) {
                            showNotification('Space is available', 'success');
                        }
                        $('#spaceBookBtn').removeClass('disabled');
                        $("#spaceCalPrice").show().find("span").html(response.priceFormatted);
                        $("#bookingTImeInfoPrice").html(response.priceFormatted);
                    }
                    if (response.bookings && response.bookings.length > 0) {
                        $('#loading-image').hide();
                        $("#alreadyBookedFor").show();
                        $("#alreadyBookedFor ul").html('');
                        for (let alreadyBooked of response.bookings) {
                            $("#alreadyBookedFor ul").append('<li>' + alreadyBooked + '</li>');
                        }
                    }
                });

            }

        }

        function addToCart(showAlerts = true) {
            var extraPrices = [];

            $('input[name=extra_price]:checked').map(function() {
                extraPrices.push($(this).val());
            });

            $("#msgx-error").hide();

            let startDate = $('#dpd1x1').val().toString().trim();
            let endDate = $('#dpd2x2').val().toString().trim();
            let startHour = $('select[name="start_hour"]').val().toString().trim();
            let endHour = $('select[name="end_hour"]').val().toString().trim();
            let startAmpPm = $("#start_ampm option:selected").val().toString().trim();
            let endAmpPm = $("#end_ampm option:selected").val().toString().trim();

            // @if (Auth::check())
            // @else
            //     let currentUrl = "{{ Request::url() }}";
            //     let loginRedirectUrl = "{{ route('auth.redirectLogin') }}";
            //     let queryData = {
            //         start_hour: startHour,
            //         to_hour: endHour,
            //         start: moment(startDate).format("MM/DD/YYYY"),
            //         end: moment(endDate).format("MM/DD/YYYY"),
            //     };
            //     let queryParams = "";
            //     for (let queryKey in queryData) {
            //         queryParams += queryKey + "=" + queryData[queryKey] + "&";
            //     }
            //     queryParams = queryParams.slice(0, -1);
            //     let currentUrlPath = encodeURIComponent((currentUrl + '?' + queryParams));
            //     //console.log(currentUrlPath);
            //     loginRedirectUrl = loginRedirectUrl + '?redirect=' + currentUrlPath;
            //     //console.log(loginRedirectUrl);
            //     window.location.href = loginRedirectUrl;
            // @endif

            var totalAduts = $("#adultsFieldItem").val().toString().trim();
            if (totalAduts == '') {
                showNotification("Enter number of Guests");
                return false;
            }

            totalAduts = totalAduts * 1;

            $.post("{{ route('booking.addToCart') }}", {
                service_id: {{ $row->id }},
                service_type: "space",
                start_date: moment(startDate).format("MM/DD/YYYY"),
                end_date: moment(endDate).format("MM/DD/YYYY"),
                start_ampm: startAmpPm,
                end_ampm: endAmpPm,
                start_hour: startHour,
                end_hour: endHour,
                extra_price: extraPrices,
                adults: totalAduts,
                platform: "mobile"
            }, function(response) {
                console.log(response);
                if (response.status == 0) {
                    if (showAlerts) {
                        showNotification(response.message);
                    }
                } else if (response.status == 1) {
                    window.location.href = response.url;
                }
            }).fail(function(response) {
                response = response.responseJSON;
                showNotification(response.message);
            });

        }

        function jqueryLoaded() {
            $('.submit-group a[name="submit"]').attr("id", "spaceBookBtn");
            $('#spaceBookBtn').addClass('disabled');

            $(document).on("click", "#openCalendar", function() {
                showAvailabilityCalendarModal();
            });

            $("#alreadyBookedFor").hide();

            $(document).on("change", 'input[name="start"]', function() {
                checkTimeAvailability();
            });

            $(document).on("change", 'input[name="end"]', function() {
                checkTimeAvailability();
            });

            $(document).on("change", 'select[name="start_hour"]', function() {
                checkTimeAvailability();
            });

            $(document).on("change", 'select[name="end_hour"]', function() {
                checkTimeAvailability();
            });

            $(document).on("change", 'select[name="start_ampm"]', function() {
                checkTimeAvailability();
            });

            $(document).on("change", 'select[name="end_ampm"]', function() {
                checkTimeAvailability();
            });

            setTimeout(e => {
                checkTimeAvailability(false);
            }, 2500);

        }

        let jqueryCheckTimer = null;
        jqueryCheckTimer = setInterval(e => {
            if (window.jQuery) {
                clearInterval(jqueryCheckTimer);
                jqueryLoaded();
            }
        }, 500);
    </script>

    <script type="text/javascript">
        $(document).ready(function(e) {

            // $(".formselect").select2();

            // $(".selectsearch, .footerselect").select2();

            // wow = new WOW({
            //     animateClass: 'animated',
            //     mobile: false,

            //     offset: 100
            // });
            // wow.init();
            // slect style

        });
    </script>

    <script type="text/javascript" src="{{ asset('js/datepikernew.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            var userloginstat = '0';

            if (userloginstat == 0) {
                $(".signinbtn").click();

            }

            $('#tempsend').hide();
            $('#tempmsg').show();
            $('#tempeff').hide();
            $('#temperror').show();
            var today = new Date();
            var dd = today.getDate() + 1;
            var mm = today.getMonth() + 1;
            //January is 0!
            var yyyy = today.getFullYear();

            if (dd < 10) {
                dd = '0' + dd
            }

            if (mm < 10) {
                mm = '0' + mm
            }

            today = mm + '/' + dd + '/' + yyyy;
            var temp = '';
            // alert(temp);

            var blockedarray = temp.split('#');

            // alert(blockedarray);
            $('.input-daterange input').datepicker({
                clearBtn: true,
                autoclose: true,
                startDate: '+0d'
            });

            $("#accounmentationGuests").on("keyup", function() {
                getPricingOfListing();
            });

            function getPricingOfListing() {

                $("#temperror").show().html("");
                //validate guest count
                var maxiGuestAllo = 1;
                maxiGuestAllo = maxiGuestAllo * 1;


                var fromval = $('#dpd1x1').val();
                var toval = $('#dpd2x2').val();
                $('#listselprice').hide();
                if (fromval == toval && fromval != "" && toval != "") {
                    //$('#dpd2x2').focus();

                }

                var flag = 0;
                if (fromval != "") {

                    var fromtimestamp = new Date(fromval).getTime();

                } else {

                    flag = 4;
                }
                if (toval != "") {

                    var totimestamp = new Date(toval).getTime();

                } else {

                    flag = 4;
                }
                var tdaytimestamp = new Date(today).getTime();

                /*if (totimestamp == fromtimestamp) {
                    flag = 2;

                }*/

                var fromvalnew = $('#dpd1x1').val();
                var tovalnew = $('#dpd2x2').val();
                var newtimestampfrom = new Date(fromvalnew).getTime();
                var newtimestampto = new Date(tovalnew).getTime();
                var requestGutestor = $("#accounmentationGuests").val();
                requestGutestor = requestGutestor * 1;
                var guestselect = requestGutestor;
                if (requestGutestor > maxiGuestAllo) {
                    $("#tempeff").hide();
                    $("#temperror").show().html("Maximum number of guests allowed are " + maxiGuestAllo + ".");
                    return false;
                } else if (flag == 1) {
                    $('#tempeff').hide();
                    $('#msgx').html('Those dates are not available');
                    //alert('Those dates are not available');
                    $('#temperror').show();
                    //$('#dpd1x1').focus();
                } else if (flag == 2) {
                    $('#tempeff').hide();
                    $('#msgx').html('Check In and Check Out Cannot be on Same Day');
                    $('#temperror').show();
                    $('#dpd2x2').trigger("click");
                } else if (flag == 4) {
                    $('#tempeff').hide();
                    $('#msgx').html('Select a Check In and Check out date');
                    $('#temperror').show();
                } else {
                    var start_time = $("#start_time").val();
                    var start_ampm = $("#start_ampm").val();
                    var end_time = $("#end_time").val();
                    var end_ampm = $("#end_ampm").val();
                    var time_array = [{
                        url: window.location.href,
                        'indate': fromvalnew,
                        'outdate': tovalnew,
                        'in_start_time': start_time,
                        'in_start_ampm': start_ampm,
                        'out_start_time': end_time,
                        'out_start_ampm': end_ampm,
                        'guestselect': guestselect
                    }];
                    localStorage.setItem('time_array', JSON.stringify(time_array));
                }
            }


            $(document).on("change", "#start_time,#start_ampm,#end_time,#end_ampm", function() {
                getPricingOfListing();
            })


            $('.input-daterange input').each(function() {
                $(this).on('changeDate', function() {
                    getPricingOfListing();
                });

                $(this).on('clearDate', function() {
                    var id = $(this).parent('div').parent('div').attr('id');
                    if (id == "datepicker") {
                        $('#listselprice').hide();
                        $('#tempeff').hide();
                        $('#msgx').html('Select a Check In and Check out date');
                        $('#temperror').show();
                    }

                    //$('#dpd2x2').focus();
                    if (id == "datepickercontact") {
                        $('#tempsend').hide();
                        $('#msgy').html(
                            'Choose a Check In and Check Out date & type in the Message you want to Send! '
                        );
                        $('#tempmsg').show();
                    }

                });

            });

            //check local storage for already present values
            if (localStorage.getItem("time_array") !== null) {
                timesArray = JSON.parse(localStorage["time_array"]);
                timeArrayKey = timesArray[0];
                console.log(timesArray);
                if (timeArrayKey['url'] == window.location.href) {
                    if (timeArrayKey['indate'] != null) {
                        $("#dpd1x1").val(timeArrayKey['indate']);
                    }
                    if (timeArrayKey['outdate'] != null) {
                        $("#dpd2x2").val(timeArrayKey['outdate']);
                    }

                    if (timeArrayKey['in_start_time'] != '') {
                        var vald = timeArrayKey['in_start_time'];
                        $("#start_time").val(vald);
                        $("#select2-start_time-container").html(vald).attr("title", vald);
                    } else {
                        $("#start_time").val("12:00");
                        $("#select2-start_time-container").html("12:00").attr("title", "12:00");
                    }

                    if (timeArrayKey['out_start_time'] != '') {
                        var vald = timeArrayKey['out_start_time'];
                        $("#end_time").val(vald);
                        $("#select2-end_time-container").html(vald).attr("title", vald);
                    } else {
                        $("#end_time").val("11:30");
                        $("#select2-end_time-container").html("11:30").attr("title", "11:30");
                    }

                    if (timeArrayKey['in_start_ampm'] != '') {
                        var vald = timeArrayKey['in_start_ampm'];
                        $("#start_ampm").val(vald);
                        $("#select2-start_ampm-container").html(vald).attr("title", vald);
                    } else {
                        $("#start_ampm").val("AM");
                        $("#select2-start_ampm-container").html("AM").attr("title", "AM");
                    }

                    if (timeArrayKey['out_start_ampm'] != '') {
                        var vald = timeArrayKey['out_start_ampm'];
                        $("#end_ampm").val(vald);
                        $("#select2-end_ampm-container").html(vald).attr("title", vald);
                    } else {
                        $("#end_ampm").val("PM");
                        $("#select2-end_ampm-container").html("PM").attr("title", "PM");
                    }

                    if (timeArrayKey['guestselect'] != '') {
                        var vald = timeArrayKey['guestselect'];
                        $("#accounmentationGuests").val(vald);
                    }


                    getPricingOfListing();
                } else {
                    //console.log("Fsdf");
                    $("#start_time").val('10:00');
                    $("#select2-start_time-container").html('10:00').attr("title", '10:00');
                    $("#end_time").val('1:00');
                    $("#select2-end_time-container").html('1:00').attr("title", '1:00');
                    $("#start_ampm").val('AM');
                    $("#select2-start_ampm-container").html('AM').attr("title", 'AM');
                    $("#end_ampm").val('PM');
                    $("#select2-end_ampm-container").html('PM').attr("title", 'PM');
                }

            }

        });
    </script>


    <script type="text/javascript">
        $(document).ready(function() {
            $('#viewMoreAmenities_in_space').on('click', function() {
                $('.space_amenities_list').removeClass('not_show_space_amenities')
                $(this).css('display', 'none')
            });
        });

        $(document).on("click", "#bookNowBtn", function() {
            $('a.nav-links[href="#booking"]').click();
            setTimeout((e) => {
                addToCart();
            }, 500);
        });

        // $(".datepickerelem").datepicker({
        //     dateFormat: "yy-mm-dd"
        // });

        $('.datepickerelem').datepicker({
            format: "mm-dd-yyyy",
            autoclose: true
        });

        function showInfoOnBootomBar() {

            let startDate = $('#dpd1x1').val().toString().trim();
            let endDate = $('#dpd2x2').val().toString().trim();
            let startHour = $('select[name="start_hour"]').val().toString().trim();
            let endHour = $('select[name="end_hour"]').val().toString().trim();

            startDate = startDate + " " + startHour + ":00";
            endDate = endDate + " " + endHour + ":00";

            startDate = new Date(startDate);
            endDate = new Date(endDate);

            let startDateM = moment(startDate);
            let endDateM = moment(endDate);

            let startDay = startDate.getDate();
            let endDay = endDate.getDate();

            let timeString = "";
            if (startDay === endDay) {
                //show base on hours
                timeString = startDateM.format("LT")+" - "+endDateM.format("LT");
            } else {
                let startMonth = startDate.getMonth();
                let endMonth = endDate.getMonth();
                if(startMonth === endMonth){
                    //same month
                    timeString = startDateM.format("MMM") + " "+startDateM.format("DD")+" - "+endDateM.format("DD");
                }else{
                    timeString = startDateM.format("ll")+" - "+endDateM.format("ll");
                }
            }

            $("#bookingTImeInfo").html(timeString);
        }

        showInfoOnBootomBar();
    </script>
@endsection
