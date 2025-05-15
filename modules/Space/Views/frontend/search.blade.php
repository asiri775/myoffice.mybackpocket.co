@extends('layouts.common_space')

@section('content') 

    <div class="layout1 bravo_wrap">
        <div class="bravo_search_tour bravo_search_space">
            <h1 class="d-none">
                {{setting_item_with_lang("space_page_search_title")}}
            </h1>
            <div class="bravo_form_search_map"> 
                @include('Space::frontend.layouts.search-map.form-search-map')
            </div>
            <div class="bravo_search_map {{ setting_item_with_lang("space_layout_map_option",false,"map_left") }}">
                <div class="results_item">
                    @include('Space::frontend.layouts.search-map.advance-filter')
                    <div class="listing_items">
                        @include('Space::frontend.layouts.search-map.list-item')
                    </div>
                </div>
            </div>
        </div>

        <div sty class="container-fluid footer">
            <div class="copyright sm-text-center">
                <p class="small-text text-black m-0">
                    Copyright © {{date('Y')}} {{env('APP_NAME')}}. All Rights Reserved.
                </p>
                <div class="clearfix"></div>
            </div>
        </div>

        <!-- Booking ends -->
        <div class="clearfix"></div>

    </div>


@endsection
