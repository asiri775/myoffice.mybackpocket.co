@extends('layouts.common_space')

@section('content')

    <div class="layout1 bravo_wrap">
        <div class="bravo_search_tour bravo_search_space z">
            <h1 class="d-none">
                {{ setting_item_with_lang('space_page_search_title') }}
            </h1>
            <div class="bravo_form_search_map">
                @include('Space::frontend.layouts.search-map.form-search-map')
            </div>
            <div class="bravo_search_map {{ setting_item_with_lang('space_layout_map_option', false, 'map_left') }}">
                <div class="results_map">
                    <div class="map-loading d-none">
                        <div class="st-loader"></div>
                    </div>
                    <div id="bravo_results_map" class="results_map_inner"></div>
                </div>
                <div class="results_item">
                    <div class="listing_items loading" id="space-search-listings">

                        <div class="listing-loading-loader">
                            <i class="fa fa-spin fa-spinner"></i>
                        </div>

                        @include('Space::frontend.layouts.search-map.list-item')

                    </div>
                </div>
            </div>
        </div>

        <div sty class="container-fluid footer">
            <div class="copyright sm-text-center">
                <p class="small-text text-black m-0">
                    Copyright © {{ date('Y') }} {{ env('APP_NAME') }}. All Rights Reserved.
                </p>
                <div class="clearfix"></div>
            </div>
        </div>

        <!-- Booking ends -->
        <div class="clearfix"></div>

    </div>
@endsection
