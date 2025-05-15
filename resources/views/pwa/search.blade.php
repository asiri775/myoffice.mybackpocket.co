@extends('pwa.layout.index')
@section('content')
    @php
        $space_map_search_fields = setting_item_array('space_map_search_fields');
        $usedAttrs = [];
        foreach ($space_map_search_fields as $field) {
            if ($field['field'] == 'attr' and !empty($field['attr'])) {
                $usedAttrs[] = $field['attr'];
            }
        }
        $selected = (array) request()->query('terms');
    @endphp
    <!-- loader -->
    <div id="loader">
        <img src="{{ url('pwa') }}/assets/img/logo_instagram.png" alt="icon" class="loading-icon">
    </div>
    <!-- * loader -->
    <!-- App Header -->
    <div class="appHeader ">
        <div class="left">
            <a href="#" class="headerButton goBack">
                <ion-icon name="chevron-back-outline"></ion-icon>
            </a>
        </div>
        <div class="pageTitle">
            <!-- <button type="button" class="btn btn-text-primary rounded shadowed"  data-bs-toggle="modal" data-bs-target="#actionSheetForm5"><ion-icon name="person-outline"></ion-icon>12:00 PM Today</button> -->
            <button type="button" class="btn btn-text-primary rounded shadowed" data-bs-toggle="modal" style=""
                data-bs-target="#actionSheetForm5">
                <?php
                $label = '';
                if (isset($_GET['capacity_with_dates']) && $_GET['capacity_with_dates'] != null) {
                    $label = $_GET['capacity_with_dates'] . ' <span class="dot-item"></span> ';
                }
                if (isset($_GET['start_date']) && $_GET['start_date'] != null) {
                    $startDate = strtotime($_GET['start_date']);
                    if (isset($_GET['end_date']) && $_GET['end_date'] != null) {
                        $endDate = strtotime($_GET['end_date']);
                        $label .= date('d M', $startDate) . ' - ' . date('d M', $endDate);
                    } else {
                        $label .= date('d M', $startDate);
                    }
                }
                if ($label == null) {
                    $label = 'Search';
                }
                // $label = $label . ' | Search';
                ?>
                <ion-icon style="font-size: 14px;" name="person-outline"></ion-icon>
                <?= $label ?>
            </button>
        </div>
        <div class="right pt-8">
            <!-- <a href="heart.html">
                         <ion-icon class="red" name="heart"></ion-icon>
                         </a>
                         <a href="bookmark.html">
                         <ion-icon class="px-2" name="bookmark-outline"></ion-icon>
                         </a> -->
        </div>
    </div>
    <!-- * App Header -->
    <!-- App Capsule -->
    <div id="appCapsule" class="full-height listing-view-item" data-view="listing">
        <div class="extraHeader">
            <form class="search-form">
                <div class="form-group searchbox  input-group">
                    <input type="text" class="form-control" id="address_input" name='search_address'
                        placeholder="Search for City or Neighbourhood" value="{{ $searched_address }}">
                    <span class="input-group-addon transparent s-map icon-item-d">
                        <a href="javascript:;" id="showMapView">
                            <ion-icon class="vl-m f-20 map-icon" name="map-outline"></ion-icon>
                            <ion-icon class="vl-m f-20 list-icon" name="list"></ion-icon>
                        </a>
                    </span>
                    <i class="input-icon icon-item-d">
                        <ion-icon name="search-outline" role="img" class="md hydrated"
                            aria-label="search outline"></ion-icon>
                    </i>
                    <input type="hidden" name="category_ids_for_search" value="{{ $selected_category_parm }}"
                        id="category_ids_for_search" class="form-control">
                    <input type="hidden" name="amenities_id_for_search" value="{{ $selected_amenities_parm }}"
                        id="amenities_id_for_search" class="form-control">
                    <input type="hidden" name="capacity_for_search" value="{{ $selected_capacity_parm }}"
                        id="capacity_for_search" class="form-control">
                    <input type="hidden" name="distance_for_search" value="{{ $selected_distance_parm }}"
                        id="distance_for_search" class="form-control">
                    <input type="hidden" name="current_lat" value="" id="current_lat" class="form-control">
                    <input type="hidden" name="current_long" value="" id="current_long" class="form-control">
                    <input type="hidden" name="searched_lat" value="" id="searched_lat" class="form-control">
                    <input type="hidden" name="searched_long" value="" id="searched_long" class="form-control">
                    <input type="hidden" name="searched_address" value="" id="searched_address" class="form-control">
                </div>
            </form>
        </div>
        <div class="section full mt-50">
            <!-- carousel small -->
            <div class="  carousel-small splide  splide--draggable " id="splide04" style="visibility: visible;">
                <div class="splide__track" id="splide04-track" style="padding-left: 16px; padding-right: 16px;">
                    <ul class="splide__list splidelist" id="splide04-list" style="transform: translateX(-1262.25px);">
                        <li class="splide__slide splide__slide--clone" style="margin-right: 16px;">
                            <a href="#" class="item" data-bs-toggle="modal" data-bs-target="#ModalBasic">
                                <div class="">
                                    <a class="px-1 btn btn-f f-11 dropdown-toggle item" type="button"
                                        data-bs-toggle="modal" data-bs-target="#actionSheetForm">
                                        Categories
                                    </a>
                                </div>
                            </a>
                        </li>
                        <li class="splide__slide splide__slide--clone" style="margin-right: 16px;">
                            <a href="#">
                                <div class="dropdown">
                                    <button class=" px-1 f-11 btn btn-f dropdown-toggle item" type="button"
                                        data-bs-toggle="modal" data-bs-target="#actionSheetForm2">
                                        Amenities
                                    </button>
                                </div>
                            </a>
                        </li>
                        <li class="splide__slide splide__slide--clone" style="margin-right: 16px;">
                            <a href="#">
                                <div class="dropdown">
                                    <button class=" px-1 f-11 btn btn-f dropdown-toggle item" type="button"
                                        data-bs-toggle="modal" data-bs-target="#actionSheetForm3">
                                        Capacity
                                    </button>
                                </div>
                            </a>
                        </li>
                        <li class="splide__slide splide__slide--clone" style="margin-right: 16px;">
                            <a href="#">
                                <div class="dropdown">
                                    <button class="px-1 btn f-11 btn-f dropdown-toggle item" type="button"
                                        data-bs-toggle="modal" data-bs-target="#actionSheetForm4">
                                        Distance
                                    </button>
                                </div>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            <!-- <div class="filtr text-end"><a href="#" data-bs-toggle="modal" data-bs-target="#ModalBasic">
                         <ion-icon class="filt" name="options-outline"></ion-icon></a>
                         </div> -->
        </div>
        <!-- * carousel small -->
        <div class="modal fade modalbox" id="ModalBasic" tabindex="-1" style="display: none;" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Filter</h5>
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
                            <!-- <div class="card">
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
                                     </div> -->
                            <?php
                            $space_attrs = Modules\Core\Models\Attributes::where('service', 'space')
                                ->where('slug', 'space-type')
                                ->orderBy('position', 'asc')
                                ->get();
                            ?>
                            <ul class="checkbox_ul row" data-text="Office Type">
                                @foreach ($space_attrs as $space_attr)
                                    @foreach ($space_attr->terms as $term)
                                        <?php
                                        $explode_selected_category_parm = explode(',', $selected_category_parm);
                                        ?>
                                        <li class="col-6 search-attribute-list">
                                            <input class="pull-left chkboxpadng category_listing_for_search"
                                                type="checkbox" value="{{ $term->name }}"
                                                category_id={{ $term->id }}
                                                @if (in_array($term->id, $explode_selected_category_parm)) checked @endif)>
                                            <label class="textlabel">{{ $term->name }}</label>
                                        </li>
                                        <!-- </a> -->
                                    @endforeach
                                @endforeach
                            </ul>
                            <button type="button"
                                class="btn btn-primary btn-lg btn-block mt-2 me-1 mb-2 filter_apply_button">Apply</button>
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
                            <!-- <div class="card"> -->
                            <!-- <div class="card-body"> -->
                            <!-- <div class="form-check mb-1">
                                     <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault1">
                                     <label class="form-check-label" for="flexRadioDefault1">
                                         Option1
                                     </label>
                                     </div>
                                     <div class="form-check mb-1">
                                     <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault2">
                                     <label class="form-check-label" for="flexRadioDefault2">
                                         Option2
                                     </label>
                                     </div>
                                     <div class="form-check mb-1">
                                     <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault3">
                                     <label class="form-check-label" for="flexRadioDefault3">
                                         Option3
                                     </label>
                                     </div>
                                     <div class="form-check">
                                     <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault4">
                                     <label class="form-check-label" for="flexRadioDefault4">
                                         Option4
                                     </label>
                                     </div> -->
                            <!-- @foreach ($data['attributes'] as $item)
    @if ($item['slug'] == 'amenities')
    @php
        if (in_array($item->id, $usedAttrs)) {
            continue;
        }
        $translate = $item->translateOrOrigin(app()->getLocale());
    @endphp
                                     
                                                                                                                                 <h2 class="mt-4 mb-4">{{ $translate->name }}</h2>
                                                                                                                                 <ul class="checkbox_ul" data-text="Office Type">
                                                                                                                                     @php $count = 1 @endphp
                                                                                                                                     @foreach ($item->terms as $term)
    @php $translate = $term->translateOrOrigin(app()->getLocale()); @endphp
                                                                                                                                         <li>
                                                                                                                                             <input @if (in_array($term->id, $selected)) checked @endif class="pull-left chkboxpadng"
                                                                                                                                                    type="checkbox" value="{{ $term->id }}"
                                                                                                                                                    name="terms[]" id="amenities{{ $count }}">
                                                                                                                                             <label for="amenities{{ $count }}" class="textlabel">{{ $translate->name }}</label>
                                                                                                                                         </li>
                                                                                                                                         @php $count++ @endphp
    @endforeach
                                                                                                                                 </ul>
    @endif
    @endforeach -->
                            <?php
                            $explode_selected_amenities_parm = explode(',', $selected_amenities_parm);
                            ?>
                            <ul class="checkbox_ul row" data-text="Office Type">
                                @foreach ($data['attributes'] as $amenity)
                                    <li class="col-6 search-attribute-list">
                                        <input class="pull-left chkboxpadng amenity_listing_for_search" type="checkbox"
                                            amenity_id="{{ $amenity->id }}"
                                            @if (in_array($amenity->id, $explode_selected_amenities_parm)) checked @endif)>
                                        <label for=" " class="textlabel">{{ $amenity->name }}</label>
                                    </li>
                                @endforeach
                            </ul>
                            <!-- </div> -->
                            <!-- </div> -->
                            <button type="button"
                                class="btn btn-primary btn-lg btn-block mt-2 me-1 mb-2 filter_apply_button">Apply</button>
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
                            <div class="">
                                <!-- <div class="row mt-2">
                                        <div class="col-6">
                                            <button type="button" capacity="0-2" class="btn bg-w opt btn-lg select_capacity @if ($selected_capacity_parm == '0-2') active @endif">0-2</button>
                                        </div>
                                        <div class="col-6">
                                            <button type="button" capacity="2-5" class="btn bg-w opt btn-lg select_capacity @if ($selected_capacity_parm == '2-5') active @endif">2-5</button>
                                        </div>
                                        </div>
                                        <div class="row mt-2">
                                        <div class="col-6">
                                            <button type="button" capacity="5-10" class="btn bg-w opt btn-lg select_capacity @if ($selected_capacity_parm == '5-10') active @endif">5-10</button>
                                        </div>
                                        <div class="col-6">
                                            <button type="button" capacity="11" class="btn bg-w opt btn-lg select_capacity @if ($selected_capacity_parm == '11') active @endif">10+</button>
                                        </div>
                                        </div> -->
                                <div class="">
                                    <!-- <div class="search-attribute-list">
                                           <input class="select_capacity" type="radio" id="" name="distance"
                                           value="0-2" @if ($selected_capacity_parm == '0-2') checked @endif>
                                           <label for="capacity1"> 0-2</label><br>
                                        </div>
                                        <div class="search-attribute-list">
                                           <input class="select_capacity" type="radio" id="" name="distance"
                                           value="2-5" @if ($selected_capacity_parm == '2-5') checked @endif>
                                           <label for="capacity2"> 2-5</label><br>
                                        </div>
                                        <div class="search-attribute-list">
                                           <input class="select_capacity" type="radio" id="" name="distance"
                                           value="5-10" @if ($selected_capacity_parm == '5-10') checked @endif>
                                           <label for="capacity3"> 5-10</label><br>
                                        </div>
                                        <div class="search-attribute-list">
                                           <input class="select_capacity" type="radio" id="" name="distance"
                                           value="11" @if ($selected_capacity_parm == '11') checked @endif>
                                           <label for="capacity4"> 10+</label>
                                        </div> -->
                                    <select class="action-sheet-field" name="capacity change-capacity  dropdown"
                                        id="change-capacity">
                                        <option class="select_capacity" value="">Select Capacity</option>
                                        <option class="select_capacity" @if ($selected_capacity_parm == '1-9') selected @endif
                                            value='1-9'>1-9</option>
                                        <option class="select_capacity" @if ($selected_capacity_parm == '10+') selected @endif
                                            value='10+'>10+</option>
                                    </select>
                                </div>
                            </div>
                            <button type="button"
                                class="btn btn-primary btn-lg btn-block mt-2 me-1 mb-2 filter_apply_button">Apply</button>
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
                        <h5 class="modal-title">Distance</h5>
                    </div>
                    <div class="modal-body bg-g">
                        <div class="action-sheet-content">
                            <div class="">
                                <div class="">
                                    <div class="search-attribute-list">
                                        <input class="distance_list_for_search" type="radio" id=""
                                            name="distance" value="0-25"
                                            @if ($selected_distance_parm == '0-25') checked @endif>
                                        <label for="vehicle1"> 0-25</label><br>
                                    </div>
                                    <div class="search-attribute-list">
                                        <input class="distance_list_for_search" type="radio" id=""
                                            name="distance" value="25-50"
                                            @if ($selected_distance_parm == '25-50') checked @endif>
                                        <label for="vehicle2"> 25-50</label><br>
                                    </div>
                                    <div class="search-attribute-list">
                                        <input class="distance_list_for_search" type="radio" id=""
                                            name="distance" value="50-100"
                                            @if ($selected_distance_parm == '50-100') checked @endif>
                                        <label for="vehicle3"> 50-100</label><br>
                                    </div>
                                    <div class="search-attribute-list">
                                        <input class="distance_list_for_search" type="radio" id=""
                                            name="distance" value="All"
                                            @if (strtolower($selected_distance_parm) == 'all') checked @endif>
                                        <label for="vehicle4"> All</label>
                                    </div>
                                </div>
                            </div>
                            <button type="button"
                                class="btn btn-primary btn-lg btn-block mt-2 me-1 mb-2 filter_apply_button">Apply</button>
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
                        <h5 class="modal-title">Search</h5>
                    </div>
                    <div class="modal-body bg-g">
                        <div class="action-sheet-content form-action-sheets">
                            <div class="form-group">
                                <label>Listing Name</label>
                                <input type="text" id="listingname" value="{{ $listingname }}">
                            </div>
                            <div class="form-group">
                                <label>From</label>
                                <input type="date" id="from_date" value="{{ $start_date }}">
                            </div>
                            <div class="form-group">
                                <label>To</label>
                                <input type="date" id="to_date" value="{{ $end_date }}">
                            </div>
                            <div class="form-group">
                                <label>Capacity</label>
                                <input type="text" id="capacity_with_dates" value="{{ $capacity_with_dates }}">
                            </div>
                            <button type="button"
                                class="btn btn-primary btn-lg btn-block mt-2 me-1 mb-2 apply_dates_filter">Apply</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- * Form Action Sheet5 -->
        <!--  -->
        <div class="section2 mt-2">
            <div id="mapView" class="results_map">
                <div class="map-loading d-none">
                    <div class="st-loader"></div>
                </div>
                <div id="bravo_results_map" class="results_map_inner"></div>
            </div>
            <div id="listView" class="transactions">
                <!-- item -->
                <?php
                // dd(count($list)); die();
                ?>
                @if (count($list) > 0)
                    @foreach ($list as $row)
                        @include('pwa.common.single_space_list_wise', ['row' => $row])
                    @endforeach
                    <div class="pwa-pagination">
                        {{ $list->appends(request()->query())->links() }}
                    </div>
                @else
                    <div class="no_listing_text">
                        <h2>OOPS!</h2>
                        <p>There are NO Listings Available based on your Search</p>
                        <p>Please try another location or remove some </br>
                            filters to expand your search
                        </p>
                    </div>
                @endif
            </div>
        </div>
    </div>
    <!-- * App Capsule --> 
    
@endsection
@section('js')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- current lat long -->
    <script>
        $(document).ready(function() {

            $.getJSON("https://ipapi.co/json/", function(data) {
                    var latitude = data.latitude;
                    var longitude = data.longitude;
                    // console.log("Latitude: " + latitude);
                    // console.log("Longitude: " + longitude);

                    $('#current_lat').val(latitude)
                    $('#current_long').val(longitude)
                })
                .fail(function(jqXHR, textStatus, errorThrown) {
                    console.error("Error getting current position:", errorThrown);
                });

        });
    </script>
    <script src='https://maps.googleapis.com/maps/api/js?key=AIzaSyCRu_qlT0HNjPcs45NXXiOSMd3btAUduSc&libraries=places'>
    </script>
    <script src='https://unpkg.com/@google/markerclustererplus@4.0.1/dist/markerclustererplus.min.js'></script>
    <script type="text/javascript">
        $(document).ready(function() {
            var input = document.getElementById('address_input');
            var options = {
                types: ['(cities)'],
                componentRestrictions: {country: ["us", "ca"]}
            };
            var autocomplete = new google.maps.places.Autocomplete(input, options);

            autocomplete.addListener('place_changed', function() {
                var place = autocomplete.getPlace();
                console.log('Selected Place:', place);
                console.log('Address:', place.formatted_address);
                console.log('Latitude:', place.geometry.location.lat());
                console.log('Longitude:', place.geometry.location.lng());

                $('#searched_lat').val(place.geometry.location.lat());
                $('#searched_long').val(place.geometry.location.lng());
                $('#searched_address').val(place.formatted_address);


                check()
            });
        });
    </script>
    <!-- Apply search -->
    <script type="text/javascript">
        function check() {
            var selected_category_ids_for_search = $('#category_ids_for_search').val();
            var selected_amenities_id_for_search = $('#amenities_id_for_search').val();

            var capacity_for_search = $('#capacity_for_search').val();

            var selected_distance_for_search = $('#distance_for_search').val();
            var current_lat = $('#current_lat').val()
            var current_long = $('#current_long').val()

            var searched_lat = $('#searched_lat').val()
            var searched_long = $('#searched_long').val()
            var searched_address = $('#searched_address').val()

            var listingname = $('#listingname').val();

            var start_date = $('#from_date').val();
            var end_date = $('#to_date').val();

            var capacity_with_dates = $('#capacity_with_dates').val();

            var baseUrl = "{{ url('/') }}";
            // Manually construct the route URL
            var routeUrl = baseUrl + "/m/search" + "?name=" + listingname + "&categories=" +
                selected_category_ids_for_search + "&amenities=" +
                selected_amenities_id_for_search + "&capacity=" + capacity_for_search + "&distance=" +
                selected_distance_for_search + "&current_lat=" + current_lat + "&current_long=" + current_long +
                "&searched_lat=" + searched_lat + "&searched_long=" + searched_long + "&searched_address=" +
                searched_address + "&start_date=" + start_date + "&end_date=" + end_date + "&capacity_with_dates=" +
                capacity_with_dates; // Replace 'your/route/url' with the actual route URL

            // Redirect to the Laravel route
            window.location.href = routeUrl;
        }
    </script>
    <script type="text/javascript">
        // For Categories
        $(document).on('click', '.category_listing_for_search', function() {
            let selected_category_id = $(this).attr('category_id');
            var already_selected_category_ids = $('#category_ids_for_search').val();
            if (already_selected_category_ids === "") {
                $('#category_ids_for_search').val(selected_category_id)
            } else {
                var explode_category_ids = already_selected_category_ids.split(',');
                var index = explode_category_ids.indexOf(selected_category_id);
                if (index !== -1) {
                    // Value already exists, remove it from the array
                    explode_category_ids.splice(index, 1);
                } else {
                    explode_category_ids.push(selected_category_id);
                }
                var updatedString = explode_category_ids.join(',');
                $('#category_ids_for_search').val(updatedString)
            }
        })



        // For Amenities
        $(document).on('click', '.amenity_listing_for_search', function() {
            let selected_amenity_id = $(this).attr('amenity_id');
            var already_selected_amenities_ids = $('#amenities_id_for_search').val();
            if (already_selected_amenities_ids === "") {
                $('#amenities_id_for_search').val(selected_amenity_id)
            } else {
                var explode_category_ids = already_selected_amenities_ids.split(',');
                var index = explode_category_ids.indexOf(selected_amenity_id);
                if (index !== -1) {
                    // Value already exists, remove it from the array
                    explode_category_ids.splice(index, 1);
                } else {
                    explode_category_ids.push(selected_amenity_id);
                }
                var updatedString = explode_category_ids.join(',');
                $('#amenities_id_for_search').val(updatedString)
            }
        })


        //For capacity
        // $(document).on('click', '.select_capacity', function() {
        //     var capacityValue = $(this).val();
        //     $('#capacity_for_search').val(capacityValue)
        // })

        document.getElementById("change-capacity").addEventListener("change", function() {
            var selectedOption = this.value;
            $('#capacity_for_search').val(selectedOption)
        });

        // For Distance
        $(document).on('click', '.distance_list_for_search', function() {
            var distance_value = $(this).val();
            $('#distance_for_search').val(distance_value);
        })


        // Apply filter button
        $(document).on('click', '.filter_apply_button', function() {
            check();
        })


        $('.apply_dates_filter').on('click', function() {
            var start_date = $('#from_date').val();
            var end_date = $('#to_date').val();


            if (!start_date) {
                alert('Please select start date')
                return
            }
            if (!end_date) {
                alert('Please select end date')
                return
            }

            check();

        });

        let bravo_map_data = {
            markers: {!! json_encode($markers) !!},
            map_lat_default: 0,
            map_lng_default: 0,
            map_zoom_default: 6,
        };

        function loadMap() {
            let mapOptions = {
                fitBounds: bookingCore.map_options.map_fit_bounds,
                center: [bravo_map_data.map_lat_default, bravo_map_data.map_lng_default],
                zoom: bravo_map_data.map_zoom_default,
                disableScripts: true,
                markerClustering: bookingCore.map_options.map_clustering,
                ready: function(engineMap) {
                    if (bravo_map_data.markers) {
                        engineMap.addMarkers2(bravo_map_data.markers);
                    }
                }
            };
            $("#bravo_results_map").html("");
            var mapEngine = new BravoMapEngine('bravo_results_map', mapOptions);
        }

        $(document).ready(function() {
            loadMap();
        });

        $(document).on("click", "#showMapView", function() {
            var element = $(".listing-view-item");
            var currentView = element.attr("data-view");
            if (currentView === "listing") {
                element.attr("data-view", "map");
                loadMap();
            } else {
                element.attr("data-view", "listing");
            }
        });
    </script>
@endsection
