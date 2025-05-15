<form action="{{ url(app_get_locale(false, false, '/') . config('space.space_route_prefix')) }}"
    class="form bravo_form d-flex justify-content-start" method="get" onsubmit="return false;">

    <input type="hidden" name="booking_type" value="{{ Request::query('booking_type') }}">
    <input type="hidden" name="long_term_rental" value="{{ Request::query('long_term_rental') }}">
    <input type="hidden" name="search_type" value="{{ Request::query('search_type') }}">

    <?php
    /*
        ?>
    ?>
    <input type="hidden" name="map_lat" value="{{ Request::query('map_lat') }}">
    <input type="hidden" name="map_lgn" value="{{ Request::query('map_lgn') }}">
    <?php
    */
    ?>

    @include('Space::frontend.layouts.search-map.fields.location')
    @include('Space::frontend.layouts.search-map.fields.date')
    @include('Space::frontend.layouts.search-map.fields.guest')
    @include('Space::frontend.layouts.search-map.fields.office_type')
    @include('Space::frontend.layouts.search-map.fields.attr')
    @include('Space::frontend.layouts.search-map.fields.price')
    @include('Space::frontend.layouts.search-map.fields.rapidbook')


    <div class="filter-item filter-simple">
        <div class="form-group">
            <span class="filter-title toggle-advance-filter" data-target="#advance_filters">{{ __('More filters') }}
                <i class="fa fa-angle-down"></i></span>
        </div>
    </div>

    @include('Space::frontend.layouts.search-map.advance-filter')


</form>
