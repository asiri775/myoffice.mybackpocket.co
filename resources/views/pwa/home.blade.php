@extends('pwa.layout.index')
@section('content')
    <!-- loader -->
    <!--  <div id="loader">
           <img src="{{ url('pwa') }}/assets/img/logo_instagram.png" alt="icon" class="loading-icon">
           </div> -->
    <style>
        /* public/css/loader.css */
        .loader-container {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 9999;
            justify-content: center;
            align-items: center;
        }

        .loader {
            border: 4px solid #f3f3f3;
            border-top: 4px solid #3498db;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 2s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }
    </style>
    <!-- <div id="loading">
           Loading...
           </div> -->
    <div class="loader-container">
        <div class="loader"></div>
    </div>
    <!-- App Header -->
    <div class="appHeader br-0 h-96">
        @include('pwa.layout._top-bar')
        <ul class="nav nav-tabs lined  mt-13" role="tablist">
            <li class="nav-item">
                <a class="nav-links main_tabs col-b f-11 fw-bold tabs @if (!$type || $type == 'hourly') active @endif"
                    href="#" role="tab" aria-selected="false" type="hourly">
                    HOURLY
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-links main_tabs col-b f-11 fw-bold @if ($type == 'long_term') active @endif"
                    href="#" role="tab" aria-selected="true" type="long_term">
                    LONG TERM
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-links main_tabs col-b f-11 fw-bold @if ($type == 'parking') active @endif"
                    href="#" role="tab" aria-selected="true" type="parking">
                    PARKING
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-links main_tabs col-b f-11 fw-bold @if ($type == 'cafe') active @endif"
                    href="#" role="tab" aria-selected="true" type="cafe">
                    CAFES
                </a>
            </li>
            <!-- <li class="nav-item">
                <a class="nav-links col-b f-11 fw-bold fav-tab" href="{{ route('pwa.get.myFavourites') }}" role="tab"
                    aria-selected="true">
                    FAVOURITES
                </a>
            </li> -->
            {{-- <li class="nav-item">
                <a class="nav-links col-b f-11 fw-bold fav-tab" href="{{ route('pwa.get.cafesList') }}" role="tab"
                    aria-selected="true">
                    CAFES
                </a>
            </li> --}}
        </ul>
    </div>
    <!-- * App Header -->
    <!-- App Capsule -->
    <div id="appCapsule" class="full-height mt-50">
        <input type="hidden" name="current_lat" value="" id="current_lat" class="form-control">
        <input type="hidden" name="current_long" value="" id="current_long" class="form-control">
        <!--  -->
        <div class="card box-s">
            <div class=" pt-0">
                <div class=" card-body tab-content pt-0 mt-05">
                    <div class="tab-pane fade active show" id="bookings" role="tabpanel">
                        <!-- <div class="extraHeader pos-rel pad-0">
                    <div class="pageTitle pr-2">
                    <button type="button" class="btn btn-text-primary rounded shadowed"  data-bs-toggle="modal" data-bs-target="#actionSheetForm5"><ion-icon class="f-16" name="person-outline"></ion-icon>1&middot;Today</button>
                    </div>
                    <form class="search-form">
                        <div class="form-group searchbox  ">
                            <input type="text" class="form-control bg-g f-10 br-20" placeholder="Search for a Business, City, Category or Area">
                           
                            <i class="input-icon f-16">
                                <ion-icon name="search-outline" role="img" class="md hydrated" aria-label="search outline"></ion-icon>
                            </i>
                        </div>
                    </form>
                    </div> -->
                        <hr class="solid m-1">
                        <!--  -->
                        <div class="section full mt-2">
                            <div class="section-heading padding">
                                <h2 class="title">Spaces Nearby</h2>
                                <a href="#" id="view_nearby_space" class="link fw-b">View All</a>
                            </div>
                            <!-- * carousel single -->
                            <!-- carousel small -->
                            <div class="carousel-multiple splide splide--loop splide--ltr splide--draggable is-active"
                                id="splide03" style="visibility: visible;">
                                <div class="splide__track" id="splide03-track"
                                    style="padding-left: 16px; padding-right: 16px;">
                                    <ul class="splide__list" id="splide03-list" style="transform: translateX(-2520.19px);">
                                        @foreach ($nearby_spaces as $row)
                                            @include('pwa.common.single_space_tile_wise', ['row' => $row])
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <!--  -->
                        <hr class="solid m-1">
                        <!-- Categories -->
                        <div class="section full mt-2">
                            <div class="section-heading padding">
                                <h2 class="title">Categories</h2>
                                <!-- <a href="search.html" class="link fw-b">View All</a> -->
                            </div>
                            <!-- * carousel single -->
                            <!-- carousel small -->
                            <div class="carousel-multiple splide splide--loop splide--ltr splide--draggable is-active"
                                id="splide03" style="visibility: visible;">
                                <div class="splide__track" id="splide03-track"
                                    style="padding-left: 16px; padding-right: 16px;">
                                    <ul class="splide__list" id="splide03-list" style="transform: translateX(-2520.19px);">
                                        @foreach ($categoryDetail as $row)
                                            @if ($row->term->attr_id == 3)
                                                <?php
                                                $imageId = $row->term->image_id;
                                                $imageUrl = url('pwa') . '/assets/img/sample/photo/wide1.jpg';
                                                if ($imageId != null) {
                                                    $imageUrl = get_file_url($imageId, 'full');
                                                }
                                                
                                                ?>
                                                <li class="splide__slide splide__slide--clone" aria-hidden="true"
                                                    tabindex="-1" style="margin-right: 16px; width: 156px;">
                                                    <a href="{{ url('m/space-by-category') . '/' . $row->term->id }}">
                                                        <div class="card bg-dark text-white">
                                                            <img src="{{ $imageUrl }}" class="card-img overlay-img"
                                                                alt="image">
                                                            <div class="card-img-overlay pt-140">
                                                                <h5 class="m-0 card-title">{{ $row->term->name }}</h5>
                                                                <p class="">{{ $row->count }} Spaces</p>
                                                            </div>
                                                        </div>
                                                    </a>
                                                </li>
                                            @endif
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                        
                        {{-- @foreach ($categoryDetail as $row)
                            <div class="section full mt-2">
                                <div class="section-heading padding">
                                    <h2 class="title">{{ $row->term->name }}</h2>
                                </div>
                                <?php
                                $space_ids_of_category = Modules\Space\Models\SpaceTerm::where('term_id', $row->term->id)->pluck('target_id');
                                
                                if (!$type || $type == 'hourly') {
                                    $space_list_of_category = Modules\Space\Models\Space::whereIn('id', $space_ids_of_category)->get();
                                }
                                
                                if ($type == 'long_term') {
                                    $space_list_of_category = Modules\Space\Models\Space::whereIn('id', $space_ids_of_category)
                                        ->where('long_term_rental', 1)
                                        ->get();
                                }
                                if ($type == 'parking') {
                                    $space_list_of_category = Modules\Space\Models\Space::whereIn('id', $space_ids_of_category)->get();
                                }
                                ?>
                                <div class="carousel-multiple splide splide--loop splide--ltr splide--draggable is-active"
                                    id="splide03" style="visibility: visible;">
                                    <div class="splide__track" id="splide03-track"
                                        style="padding-left: 16px; padding-right: 16px;">
                                        <ul class="splide__list" id="splide03-list"
                                            style="transform: translateX(-2520.19px);">
                                            @foreach ($space_list_of_category as $row)
                                                @include('pwa.common.single_space_tile_wise', [
                                                    'row' => $row,
                                                ])
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        @endforeach --}}


                        <!-- //Fitness -->
                        <!--  -->
                        <div class="section full mt-2">
                            <div class="section-heading padding">
                                <h2 class="title">Explore Spaces by Area</h2>
                                <a href="{{ url('/m/search') }}" class="link fw-b">View All</a>
                            </div>
                        </div>
                        <!-- #1st Scroll -->
                        <div class="carousel-small splide splide--draggable splide--slide splide--ltr is-active"
                            id="splide04" style="visibility: visible;">
                            <div class="splide__track" id="splide04-track"
                                style="padding-left: 18px; padding-right: 18px;">
                                <ul class="splide__list splidelist" id="splide04-list"
                                    style="transform: translateX(0px);">
                                    <li class="splide__slide splide__slide--clone is-active is-visible"
                                        style="margin-right: 10px; width: 106.333px;" id="splide04-slide01"
                                        aria-hidden="false" tabindex="0">
                                        <div class="">
                                          <a href="{{url('/m/search')}}?address_search=Long+beach" class="px-2 btn btn-primary br-20 f-11  item"
                                                type="button">
                                                Long Beach
                                            </a>
                                        </div>
                                    </li>
                                    <li class="splide__slide splide__slide--clone is-visible"
                                        style="margin-right: 10px; width: 106.333px;" id="splide04-slide02"
                                        aria-hidden="false" tabindex="0">
                                        <div class="">
                                          <a href="{{url('/m/search')}}?address_search=Culver" class="px-2 btn btn-primary br-20 f-11  item"
                                                type="button">
                                                Culver
                                            </a>
                                        </div>
                                    </li>
                                    <li class="splide__slide splide__slide--clone is-visible"
                                        style="margin-right: 10px; width: 106.333px;" id="splide04-slide03"
                                        aria-hidden="false" tabindex="0">
                                        <div class="">
                                          <a href="{{url('/m/search')}}?address_search=Milan" class="px-2 btn btn-primary br-20 f-11  item"
                                                type="button">
                                                Milan
                                            </a>
                                        </div>
                                    </li>
                                    <li class="splide__slide splide__slide--clone is-visible"
                                        style="margin-right: 10px; width: 106.333px;" id="splide04-slide04"
                                        aria-hidden="false" tabindex="0">
                                        <div class="">
                                          <a href="{{url('/m/search')}}?address_search=Osaka" class="px-2 btn btn-primary br-20 f-11  item"
                                                type="button">
                                                Osaka
                                            </a>
                                        </div>
                                    </li>
                                    <li class="splide__slide splide__slide--clone is-visible"
                                        style="margin-right: 10px; width: 106.333px;" id="splide04-slide04"
                                        aria-hidden="false" tabindex="0">
                                        <div class="">
                                          <a href="{{url('/m/search')}}?address_search=Rome" class="px-2 btn btn-primary br-20 f-11  item"
                                                type="button">
                                                Rome
                                            </a>
                                        </div>
                                    </li>
                                    <li class="splide__slide splide__slide--clone is-visible"
                                        style="margin-right: 10px; width: 106.333px;" id="splide04-slide04"
                                        aria-hidden="false" tabindex="0">
                                        <div class="">
                                          <a href="{{url('/m/search')}}?address_search=London" class="px-2 btn btn-primary br-20 f-11  item"
                                                type="button">
                                                London
                                            </a>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <!--  #2nd Scroll-->
                        <div class="mt-05 carousel-small splide splide--draggable splide--slide splide--ltr is-active"
                            id="splide04" style="visibility: visible;">
                            <div class="splide__track" id="splide04-track"
                                style="padding-left: 18px; padding-right: 18px;">
                                <ul class="splide__list splidelist" id="splide04-list"
                                    style="transform: translateX(0px);">
                                    <li class="splide__slide splide__slide--clone is-active is-visible"
                                        style="margin-right: 10px; width: 106.333px;" id="splide04-slide01"
                                        aria-hidden="false" tabindex="0">
                                        <div class="">
                                          <a href="{{url('/m/search')}}?address_search=Seoul" class="px-2 btn btn-primary br-20 f-11  item"
                                                type="button">
                                                Seoul
                                            </a>
                                        </div>
                                    </li>
                                    <li class="splide__slide splide__slide--clone is-visible"
                                        style="margin-right: 10px; width: 106.333px;" id="splide04-slide02"
                                        aria-hidden="false" tabindex="0">
                                        <div class="">
                                          <a href="{{url('/m/search')}}?address_search=Singapore" class="px-2 btn btn-primary br-20 f-11  item"
                                                type="button">
                                                Singapore
                                            </a>
                                        </div>
                                    </li>
                                    <li class="splide__slide splide__slide--clone is-visible"
                                        style="margin-right: 10px; width: 106.333px;" id="splide04-slide03"
                                        aria-hidden="false" tabindex="0">
                                        <div class="">
                                          <a href="{{url('/m/search')}}?address_search=Tokoyo" class="px-2 btn btn-primary br-20 f-11  item"
                                                type="button">
                                                Tokoyo
                                            </a>
                                        </div>
                                    </li>
                                    <li class="splide__slide splide__slide--clone is-visible"
                                        style="margin-right: 10px; width: 106.333px;" id="splide04-slide04"
                                        aria-hidden="false" tabindex="0">
                                        <div class="">
                                          <a href="{{url('/m/search')}}?address_search=Hong+Kong" class="px-2 btn btn-primary br-20 f-11  item"
                                                type="button">
                                                Hong Kong
                                            </a>
                                        </div>
                                    </li>
                                    <li class="splide__slide splide__slide--clone is-visible"
                                        style="margin-right: 10px; width: 106.333px;" id="splide04-slide04"
                                        aria-hidden="false" tabindex="0">
                                        <div class="">
                                          <a href="{{url('/m/search')}}?address_search=Sydney" class="px-2 btn btn-primary br-20 f-11  item"
                                                type="button">
                                                Sydney
                                            </a>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <!-- #3rd Scroll -->
                        <div class="mt-05 carousel-small splide splide--draggable splide--slide splide--ltr is-active"
                            id="splide04" style="visibility: visible;">
                            <div class="splide__track" id="splide04-track"
                                style="padding-left: 18px; padding-right: 18px;">
                                <ul class="splide__list splidelist" id="splide04-list"
                                    style="transform: translateX(0px);">
                                    <li class="splide__slide splide__slide--clone is-active is-visible"
                                        style="margin-right: 10px; width: 106.333px;" id="splide04-slide01"
                                        aria-hidden="false" tabindex="0">
                                        <div class="">
                                          <a href="{{url('/m/search')}}?address_search=Amsterdam" class="px-2 btn btn-primary br-20 f-11  item"
                                                type="button">
                                                Amsterdam
                                            </a>
                                        </div>
                                    </li>
                                    <li class="splide__slide splide__slide--clone is-visible"
                                        style="margin-right: 10px; width: 106.333px;" id="splide04-slide02"
                                        aria-hidden="false" tabindex="0">
                                        <div class="">
                                          <a href="{{url('/m/search')}}?address_search=Venice" class="px-2 btn btn-primary br-20 f-11  item"
                                                type="button">
                                                Venice
                                            </a>
                                        </div>
                                    </li>
                                    <li class="splide__slide splide__slide--clone is-visible"
                                        style="margin-right: 10px; width: 106.333px;" id="splide04-slide03"
                                        aria-hidden="false" tabindex="0">
                                        <div class="">
                                          <a href="{{url('/m/search')}}?address_search=Barcelona" class="px-2 btn btn-primary br-20 f-11  item"
                                                type="button">
                                                Barcelona
                                            </a>
                                        </div>
                                    </li>
                                    <li class="splide__slide splide__slide--clone is-visible"
                                        style="margin-right: 10px; width: 106.333px;" id="splide04-slide04"
                                        aria-hidden="false" tabindex="0">
                                        <div class="">
                                          <a href="{{url('/m/search')}}?address_search=Liverpool" class="px-2 btn btn-primary br-20 f-11  item"
                                                type="button">
                                                Liverpool
                                            </a>
                                        </div>
                                    </li>
                                    <li class="splide__slide splide__slide--clone is-visible"
                                        style="margin-right: 10px; width: 106.333px;" id="splide04-slide04"
                                        aria-hidden="false" tabindex="0">
                                        <div class="">
                                          <a href="{{url('/m/search')}}?address_search=Dubai" class="px-2 btn btn-primary br-20 f-11  item"
                                                type="button">
                                                Dubai
                                            </a>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <!-- * carousel small -->
                        <!-- Card-1 -->
                        <div class="section mt-2">
                            <a href="ad-tiles.html">
                                <div id="image1" class="card bg-dark text-white outer">
                                    <button id="close" onClick="hideImg()" class="close">
                                        <span>&times;</span>
                                    </button>
                                    <img src="{{ url('pwa') }}/assets/img/sample/photo/wide1.jpg"
                                        class="card-img card-1 overlay-img" alt="image">
                                    <div class="card-img-overlay pt-70">
                                        <h5 class="card-title">List Your Space.</h5>
                                        <p class=" pt-40"><small>View more</small></p>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <!-- Card-2 -->
                        <div class="section mt-2">
                            <a href="ad-tiles.html">
                                <div id="image2" class="card bg-dark text-white outer">
                                    <button id="close2" onClick="hideImg2()" class="close">
                                        <span>&times;</span>
                                    </button>
                                    <img src="{{ url('pwa') }}/assets/img/sample/photo/wide1.jpg"
                                        class="card-img card-1 overlay-img" alt="image">
                                    <div class="card-img-overlay pt-70 text-center">
                                        <p class="card-title text-center mb-5">Use promo code 'Discover Earth' to </p>
                                        <a href="#" class="btn bg-btn fw-normal">Redeem</a>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <!-- Card-3 -->
                        <div class="section mt-2">
                            <a href="ad-tiles.html">
                                <div id="image3" class="card bg-dark text-white outer">
                                    <button id="close3" onClick="hideImg3()" class="close">
                                        <span>&times;</span>
                                    </button>
                                    <img src="{{ url('pwa') }}/assets/img/sample/photo/wide1.jpg"
                                        class="card-img card-1 overlay-img" alt="image">
                                    <div class="card-img-overlay pt-70">
                                        <h5 class="card-title">Get free credits when you complete an </h5>
                                        <p class=" pt-40"><small>View more</small></p>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="spaces" role="tabpanel">
                        <div class=" mt-2">
                            <p class="text-center">"LONG TERM content goes here"</p>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="longterm" role="tabpanel">
                        <div class=" mt-2">
                            <p class="text-center">"PARKING content goes here"</p>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="favourites" role="tabpanel">
                        <div class=" mt-2">
                            <p class="text-center">"Favourites content goes here"</p>
                        </div>
                    </div>


                </div>
            </div>
            <!--  -->
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
                                <button type="button"
                                    class="btn btn-primary btn-lg btn-block mt-2 me-1 mb-2">Search</button>
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
                                <button type="button"
                                    class="btn btn-primary btn-lg btn-block mt-2 me-1 mb-2">Apply</button>
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
                                <button type="button"
                                    class="btn btn-primary btn-lg btn-block mt-2 me-1 mb-2">Apply</button>
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
                                <button type="button"
                                    class="btn btn-primary btn-lg btn-block mt-2 me-1 mb-2">Apply</button>
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
                                <button type="button"
                                    class="btn btn-primary btn-lg btn-block mt-2 me-1 mb-2">Apply</button>
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
                                <button type="button"
                                    class="btn btn-primary btn-lg btn-block mt-2 me-1 mb-2">Apply</button>
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
                            <h5 class="modal-title">Profile Settings</h5>
                        </div>
                        <div class="modal-body bg-g">
                            <div class="action-sheet-content">
                                <button type="button"
                                    class="btn btn-primary btn-lg btn-block mt-2 me-1 mb-2">Save</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- * Form Action Sheet6-->
            
        @endsection
        @section('js')
            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
            <!-- current lat long -->
            <script>
                $(document).ready(function() {

                    $.getJSON("https://ipapi.co/json/", function(data) {
                            var latitude = data.latitude;
                            var longitude = data.longitude;
                            console.log("Latitude: " + latitude);
                            console.log("Longitude: " + longitude);

                            $('#current_lat').val(latitude)
                            $('#current_long').val(longitude)

                            var queryParams = new URLSearchParams(window.location.search);

                            // Get the value of a specific parameter
                            var currentLatValue = queryParams.get('currentLatValue');
                            var currentLongName = queryParams.get('currentLongName');

                            // console.log('lat:', currentLatValue);
                            // console.log('long:', currentLongName);

                            if (!currentLatValue && !currentLongName) {
                                var url = "{{ route('pwa.get.home') }}" + "?currentLatValue=" + latitude +
                                    "&currentLongName=" + longitude;
                                window.location.href = url;
                            }
                        })
                        .fail(function(jqXHR, textStatus, errorThrown) {
                            console.error("Error getting current position:", errorThrown);
                        });

                });
            </script>
            <script type="text/javascript">
                $(document).on('click', '#view_nearby_space', function() {
                    var latitude = $('#current_lat').val()
                    var longitude = $('#current_long').val()

                    var url = "{{ route('pwa.viewAllNearbySpaces') }}" + "?currentLatValue=" + latitude +
                        "&currentLongName=" + longitude;
                    window.location.href = url;
                })

                $(document).on('click', '.main_tabs', function() {

                    $('.loader-container').css('display', 'flex');

                    var latitude = $('#current_lat').val()
                    var longitude = $('#current_long').val()

                    var type = $(this).attr('type')

                    var url = "{{ route('pwa.get.home') }}" + "?type=" + type + "&currentLatValue=" + latitude +
                        "&currentLongName=" + longitude;
                    window.location.href = url;
                })

                $(document).on('click', '.fav-tab', function() {

                    $('.loader-container').css('display', 'flex');
                });
            </script>
            <script>
                function hideImg() {
                    document.getElementById("image1").style.display = "none";
                    document.getElementById("close").style.display = "none";
                }

                function hideImg2() {
                    document.getElementById("image2").style.display = "none";
                    document.getElementById("close2").style.display = "none";
                }

                function hideImg3() {
                    document.getElementById("image3").style.display = "none";
                    document.getElementById("close3").style.display = "none";
                }


            </script>
        @endsection
