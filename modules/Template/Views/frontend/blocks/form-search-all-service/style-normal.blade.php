@if (!empty($style) and $style == 'carousel' and !empty($list_slider))
    <div class="effect">
        <div class="owl-carousel">
            @foreach ($list_slider as $item)
                @php $img = get_file_url($item['bg_image'],'full') @endphp
                <div class="item">
                    <div class="item-bg"
                         style="background-image: linear-gradient(0deg,rgba(0, 0, 0, 0.2),rgba(0, 0, 0, 0.2)),url('{{ $img }}') !important">
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endif
<style>
    .searchloactionwrp.bravo-form-search-all input.form-control.border-0.pac-target-input {
    margin-left: -10px;
    padding-left: 10px;
}
.searchloactionwrp.bravo-form-search-all input.form-control.border-0.pac-target-input:focus{
    box-shadow: none;
}
.searchloactionwrp.bravo-form-search-all  .g-field-search .search-field-g .form-content {
    padding-left: 55px;
}
</style>
<div id="slides" class="mainslider">
    <div class="contentslider">
        <div class="middle">
            <div class="inner">
                <h1 class="josfinsanbold scontentheading">{{ $title }}</h1>
                <p class="josfinsanregular scontentp">
                    {{ $sub_title }} </p>

                <div class="bookingwraper bravo_wrap">
                    <div class="bookingcontainer">
                        @if (empty($hide_form_search))
                            <div class="bookingcontainerin">
                                <div class="searchindex robotoregular">
                                    <div class="searchloactionwrp bravo-form-search-all">
                                        <div class="g-form-control tab-content-wrapper">  
                                            <ul class="nav nav-tabs" role="tablist">
                                                <li role="bravo_space" class="active">
                                                    <a href="#bravo_space" aria-controls="bravo_space"
                                                       role="tab" data-toggle="tab" aria-selected="false">
                                                        <i class="icofont-clock-time"></i>
                                                        Hourly
                                                    </a>
                                                </li>
                                                <li role="bravo_long_term">
                                                    <a href="#bravo_long_term" class="" aria-controls="bravo_long_term"
                                                       role="tab" data-toggle="tab" aria-selected="true">
                                                        <i class="icofont-building-alt"></i>
                                                        Long Term
                                                    </a>
                                                </li>
                                                <li role="bravo_parking">
                                                    <a href="#bravo_parking" class=""
                                                       aria-controls="bravo_parking" role="tab" data-toggle="tab"
                                                       aria-selected="false">
                                                        <i class="icofont-cab"></i>
                                                        Parking
                                                    </a>
                                                </li>
                                                <li role="bravo_coffee">
                                                    <a href="#bravo_coffee" class=""
                                                       aria-controls="bravo_coffee" role="tab" data-toggle="tab">
                                                        <i class="icofont-coffee-mug"></i>
                                                        Let’s Do Coffee
                                                    </a>
                                                </li>
                                            </ul>
                                            <div class="tab-content">
                                                <div role="tabpanel" class="tab-pane active" id="bravo_space">
                                                    <form action="{{route('space.search')}}" class="form bravo_form"
                                                          method="get">
                                                        <input type="hidden" name="booking_type" value="hourly"/>
                                                        <input type="hidden" name="_layout" value="map"/>
                                                        <input type="hidden" name="long_term_rental" value="0">
                                                        <div class="g-field-search">
                                                            <div class="row">
                                                                <div class="col-md-4 search-field-g border-right">
                                                                    <div class="form-group">
                                                                        <i class="field-icon fa icofont-map"></i>
                                                                        <div class="form-content">
                                                                            <label>Location</label>
                                                                            <div class="g-map-place">
                                                                                <input type="hidden" name="search_type"
                                                                                       value="1">
                                                                                <input type="text" name="map_place"
                                                                                       placeholder="Where are you going?"
                                                                                       value=""
                                                                                       class="form-control border-0 pac-target-input"
                                                                                       autocomplete="off">
                                                                                <div class="map d-none"
                                                                                     id="map-x6MsYQC3rN"
                                                                                     style="position: relative; overflow: hidden;">
                                                                                    <div
                                                                                        style="height: 100%; width: 100%; position: absolute; top: 0px; left: 0px; background-color: rgb(229, 227, 223);">
                                                                                        <div style="overflow: hidden;">
                                                                                        </div>
                                                                                        <div class="gm-style"
                                                                                             style="position: absolute; z-index: 0; left: 0px; top: 0px; height: 100%; width: 100%; padding: 0px; border-width: 0px; margin: 0px;">
                                                                                            <div tabindex="0"
                                                                                                 aria-label="Map"
                                                                                                 aria-roledescription="map"
                                                                                                 role="region"
                                                                                                 style="position: absolute; z-index: 0; left: 0px; top: 0px; height: 100%; width: 100%; padding: 0px; border-width: 0px; margin: 0px; cursor: url(&quot;https://maps.gstatic.com/mapfiles/openhand_8_8.cur&quot;), default; touch-action: pan-x pan-y;">
                                                                                                <div
                                                                                                    style="z-index: 1; position: absolute; left: 50%; top: 50%; width: 100%;">
                                                                                                    <div
                                                                                                        style="position: absolute; left: 0px; top: 0px; z-index: 100; width: 100%;">
                                                                                                        <div
                                                                                                            style="position: absolute; left: 0px; top: 0px; z-index: 0;">
                                                                                                        </div>
                                                                                                    </div>
                                                                                                    <div
                                                                                                        style="position: absolute; left: 0px; top: 0px; z-index: 101; width: 100%;">
                                                                                                    </div>
                                                                                                    <div
                                                                                                        style="position: absolute; left: 0px; top: 0px; z-index: 102; width: 100%;">
                                                                                                    </div>
                                                                                                    <div
                                                                                                        style="position: absolute; left: 0px; top: 0px; z-index: 103; width: 100%;">
                                                                                                    </div>
                                                                                                    <div
                                                                                                        style="position: absolute; left: 0px; top: 0px; z-index: 0;">
                                                                                                    </div>
                                                                                                </div>
                                                                                                <div
                                                                                                    style="z-index: 3; position: absolute; height: 100%; width: 100%; padding: 0px; border-width: 0px; margin: 0px; left: 0px; top: 0px; touch-action: pan-x pan-y;">
                                                                                                    <div
                                                                                                        style="z-index: 4; position: absolute; left: 50%; top: 50%; width: 100%;">
                                                                                                        <div
                                                                                                            style="position: absolute; left: 0px; top: 0px; z-index: 104; width: 100%;">
                                                                                                        </div>
                                                                                                        <div
                                                                                                            style="position: absolute; left: 0px; top: 0px; z-index: 105; width: 100%;">
                                                                                                        </div>
                                                                                                        <div
                                                                                                            style="position: absolute; left: 0px; top: 0px; z-index: 106; width: 100%;">
                                                                                                            <span
                                                                                                                id="569B4312-A8CC-4D53-9A07-794C41C1CC87"
                                                                                                                style="display: none;">To
                                                                                                                navigate,
                                                                                                                press
                                                                                                                the
                                                                                                                arrow
                                                                                                                keys.</span>
                                                                                                        </div>
                                                                                                        <div
                                                                                                            style="position: absolute; left: 0px; top: 0px; z-index: 107; width: 100%;">
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>
                                                                                                <div
                                                                                                    class="gm-style-moc"
                                                                                                    style="z-index: 4; position: absolute; height: 100%; width: 100%; padding: 0px; border-width: 0px; margin: 0px; left: 0px; top: 0px; opacity: 0;">
                                                                                                    <p
                                                                                                        class="gm-style-mot">
                                                                                                    </p>
                                                                                                </div>
                                                                                            </div>
                                                                                            <iframe
                                                                                                aria-hidden="true"
                                                                                                frameborder="0"
                                                                                                tabindex="-1"
                                                                                                style="z-index: -1; position: absolute; width: 100%; height: 100%; top: 0px; left: 0px; border: none;"></iframe>
                                                                                            <div
                                                                                                style="pointer-events: none; width: 100%; height: 100%; box-sizing: border-box; position: absolute; z-index: 1000002; opacity: 0; border: 2px solid rgb(26, 115, 232);">
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <input type="hidden" name="map_lat"
                                                                                       value="">
                                                                                <input type="hidden" name="map_lgn"
                                                                                       value="">
                                                                            </div>

                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-3 border-right">
                                                                    <div class="form-group">
                                                                        <i class="field-icon icofont-wall-clock"></i>
                                                                        <div class="form-content">
                                                                            <div class="form-date-search">
                                                                                <div class="date-wrapper">
                                                                                    <div class="check-in-wrapper">
                                                                                        @php
                                                                                            $now = date('m/d/Y', strtotime(now()));
                                                                                        @endphp
                                                                                        <label>From - To</label>
                                                                                        <div
                                                                                            class="render check-in-render">{{$now}}</div>
                                                                                        <span> - </span>
                                                                                        <div
                                                                                            class="render check-out-render">{{$now}}</div>
                                                                                    </div>
                                                                                </div>
                                                                                <input type="hidden"
                                                                                       class="check-in-input"
                                                                                       value="{{$now}}" name="start">
                                                                                <input type="hidden"
                                                                                       class="check-out-input"
                                                                                       value="{{$now}}" name="end">
                                                                                <input type="text" class="check-in-out"
                                                                                       name="date"
                                                                                       value="{{$now}} - {{$now}}">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-3 border-right">
                                                                    <style>
                                                                        .select-time-dropdown {
                                                                            transform: none !important;
                                                                            border-top-left-radius: 0;
                                                                            border-top-right-radius: 0;
                                                                            top: 100% !important;
                                                                            margin-top: 0;
                                                                            right: 0;
                                                                            border-color: #dee2e6;
                                                                            width: 100%;
                                                                        }

                                                                        .select-time-dropdown .dropdown-item-row {
                                                                            display: flex;
                                                                            align-items: center;
                                                                            margin: 10px 15px;
                                                                        }

                                                                        .select-time-dropdown .dropdown-item-row .val {
                                                                            margin-right: 0;
                                                                            margin-left: auto;
                                                                        }

                                                                    </style>
                                                                    <div class="form-select-time">
                                                                        <div class="form-group">
                                                                            <i
                                                                                class="field-icon icofont-wall-clock"></i>
                                                                            <div class="form-content dropdown-toggle"
                                                                                 data-toggle="dropdown"
                                                                                 aria-expanded="false">
                                                                                <div class="wrapper-more">
                                                                                    <label>Time From - Time To</label>
                                                                                    <div class="render">
                                                                                        <span class="from_hour">
                                                                                            <span class="multi"
                                                                                                  data-html=":fromHourData">Any</span></span>
                                                                                        -
                                                                                        <span class="to_hour">
                                                                                            <span class="multi"
                                                                                                  data-html=":toHourData">Any</span>
                                                                                        </span>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div
                                                                                class="dropdown-menu select-time-dropdown"
                                                                                x-placement="bottom-start"
                                                                                style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(15px, 75px, 0px);">
                                                                                <div class="dropdown-item-row">
                                                                                    <div class="label">From
                                                                                    </div>
                                                                                    <div class="val">
                                                                                        <select name="from_hour"
                                                                                                id="from_hour"
                                                                                                class="form-control">
                                                                                            <option value="">From
                                                                                            </option>
                                                                                            {!! \App\Helpers\Constants::getTimeOptions() !!}
                                                                                        </select>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="dropdown-item-row">
                                                                                    <div class="label">To</div>
                                                                                    <div class="val">
                                                                                        <select name="to_hour"
                                                                                                id="to_hour"
                                                                                                class="form-control">
                                                                                            <option value="">To</option>
                                                                                            {!! \App\Helpers\Constants::getTimeOptions() !!}
                                                                                        </select>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-2 border-right">
                                                                    <div class="form-select-guests">
                                                                        <div class="form-group">
                                                                            <i class="field-icon icofont-travelling"></i>
                                                                            <div class="form-content dropdown-toggle"
                                                                                 data-toggle="dropdown"
                                                                                 aria-expanded="false">
                                                                                <div class="wrapper-more">
                                                                                    <label>Guests</label>
                                                                                    <div class="render">
                                                                                        <span class="adults">
                                                                                            <span class="one d-none">1 Guest</span>
                                                                                            <span class="multi"
                                                                                                  data-html=":count Guests">1 Guest</span>
                                                                                        </span>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div
                                                                                class="dropdown-menu select-guests-dropdown"
                                                                                x-placement="bottom-start"
                                                                                style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(15px, 75px, 0px);">
                                                                                <div class="dropdown-item-row">
                                                                                    <div class="label">Adults</div>
                                                                                    <div class="val">
                                                                                        <span class="btn-minus"
                                                                                              data-input="adults"><i
                                                                                                class="icon ion-md-remove"></i></span>
                                                                                        <span
                                                                                            class="count-display"><input
                                                                                                type="number"
                                                                                                name="adults" value="1"
                                                                                                min="1"></span>
                                                                                        <span class="btn-add"
                                                                                              data-input="adults"><i
                                                                                                class="icon ion-ios-add"></i></span>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="g-button-submit">
                                                            <button class="btn btn-primary btn-search"
                                                                    type="submit">Search
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                                <div role="tabpanel" class="tab-pane" id="bravo_long_term">
                                                    <form action="{{route('space.search')}}"
                                                          class="form bravo_form" method="get">
                                                        <input type="hidden" name="_layout" value="map"/>
                                                        <input type="hidden" name="long_term_rental" value="1">
                                                        <input type="hidden" name="booking_type" value="long_term"/>
                                                        <div class="g-field-search">
                                                            <div class="row">
                                                                <div class="col-md-4 search-field-g border-right">
                                                                    <div class="form-group">
                                                                        <i class="field-icon fa icofont-map"></i>
                                                                        <div class="form-content">
                                                                            <label>Location</label>
                                                                            <div class="g-map-place">
                                                                                <input type="hidden" name="search_type"
                                                                                       value="2">
                                                                                <input type="text" name="map_place"
                                                                                       placeholder="Where are you going?"
                                                                                       value=""
                                                                                       class="form-control border-0 pac-target-input"
                                                                                       autocomplete="off">
                                                                                <div class="map d-none"
                                                                                     id="map-08o9isUBky"
                                                                                     style="position: relative; overflow: hidden;">
                                                                                    <div
                                                                                        style="height: 100%; width: 100%; position: absolute; top: 0px; left: 0px; background-color: rgb(229, 227, 223);">
                                                                                        <div style="overflow: hidden;">
                                                                                        </div>
                                                                                        <div class="gm-style"
                                                                                             style="position: absolute; z-index: 0; left: 0px; top: 0px; height: 100%; width: 100%; padding: 0px; border-width: 0px; margin: 0px;">
                                                                                            <div tabindex="0"
                                                                                                 aria-label="Map"
                                                                                                 aria-roledescription="map"
                                                                                                 role="region"
                                                                                                 style="position: absolute; z-index: 0; left: 0px; top: 0px; height: 100%; width: 100%; padding: 0px; border-width: 0px; margin: 0px; cursor: url(&quot;https://maps.gstatic.com/mapfiles/openhand_8_8.cur&quot;), default; touch-action: pan-x pan-y;">
                                                                                                <div
                                                                                                    style="z-index: 1; position: absolute; left: 50%; top: 50%; width: 100%;">
                                                                                                    <div
                                                                                                        style="position: absolute; left: 0px; top: 0px; z-index: 100; width: 100%;">
                                                                                                        <div
                                                                                                            style="position: absolute; left: 0px; top: 0px; z-index: 0;">
                                                                                                        </div>
                                                                                                    </div>
                                                                                                    <div
                                                                                                        style="position: absolute; left: 0px; top: 0px; z-index: 101; width: 100%;">
                                                                                                    </div>
                                                                                                    <div
                                                                                                        style="position: absolute; left: 0px; top: 0px; z-index: 102; width: 100%;">
                                                                                                    </div>
                                                                                                    <div
                                                                                                        style="position: absolute; left: 0px; top: 0px; z-index: 103; width: 100%;">
                                                                                                    </div>
                                                                                                    <div
                                                                                                        style="position: absolute; left: 0px; top: 0px; z-index: 0;">
                                                                                                    </div>
                                                                                                </div>
                                                                                                <div
                                                                                                    style="z-index: 3; position: absolute; height: 100%; width: 100%; padding: 0px; border-width: 0px; margin: 0px; left: 0px; top: 0px; touch-action: pan-x pan-y;">
                                                                                                    <div
                                                                                                        style="z-index: 4; position: absolute; left: 50%; top: 50%; width: 100%;">
                                                                                                        <div
                                                                                                            style="position: absolute; left: 0px; top: 0px; z-index: 104; width: 100%;">
                                                                                                        </div>
                                                                                                        <div
                                                                                                            style="position: absolute; left: 0px; top: 0px; z-index: 105; width: 100%;">
                                                                                                        </div>
                                                                                                        <div
                                                                                                            style="position: absolute; left: 0px; top: 0px; z-index: 106; width: 100%;">
                                                                                                            <span
                                                                                                                id="5E637401-1BF3-451C-8FE4-B3548D238BB0"
                                                                                                                style="display: none;">To
                                                                                                                navigate,
                                                                                                                press
                                                                                                                the
                                                                                                                arrow
                                                                                                                keys.</span>
                                                                                                        </div>
                                                                                                        <div
                                                                                                            style="position: absolute; left: 0px; top: 0px; z-index: 107; width: 100%;">
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>
                                                                                                <div
                                                                                                    class="gm-style-moc"
                                                                                                    style="z-index: 4; position: absolute; height: 100%; width: 100%; padding: 0px; border-width: 0px; margin: 0px; left: 0px; top: 0px; opacity: 0;">
                                                                                                    <p
                                                                                                        class="gm-style-mot">
                                                                                                    </p>
                                                                                                </div>
                                                                                            </div>
                                                                                            <iframe
                                                                                                aria-hidden="true"
                                                                                                frameborder="0"
                                                                                                tabindex="-1"
                                                                                                style="z-index: -1; position: absolute; width: 100%; height: 100%; top: 0px; left: 0px; border: none;"></iframe>
                                                                                            <div
                                                                                                style="pointer-events: none; width: 100%; height: 100%; box-sizing: border-box; position: absolute; z-index: 1000002; opacity: 0; border: 2px solid rgb(26, 115, 232);">
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <input type="hidden" name="map_lat"
                                                                                       value="">
                                                                                <input type="hidden" name="map_lgn"
                                                                                       value="">
                                                                            </div>

                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4 border-right">
                                                                    <div class="form-group">
                                                                        <i class="field-icon icofont-wall-clock"></i>
                                                                        <div class="form-content">
                                                                            <div class="form-date-search">
                                                                                <div class="date-wrapper">
                                                                                    <div class="check-in-wrapper">
                                                                                        <label>From - To</label>
                                                                                        <div
                                                                                            class="render check-in-render">{{$now}}</div>
                                                                                        <span> - </span>
                                                                                        <div
                                                                                            class="render check-out-render">{{$now}}</div>
                                                                                    </div>
                                                                                </div>
                                                                                <input type="hidden"
                                                                                       class="check-in-input"
                                                                                       value="{{$now}}" name="start">
                                                                                <input type="hidden"
                                                                                       class="check-out-input"
                                                                                       value="{{$now}}" name="end">
                                                                                <input type="text" class="check-in-out"
                                                                                       name="date"
                                                                                       value="{{$now}} - {{$now}}">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4 border-right">
                                                                    <div class="form-select-guests">
                                                                        <div class="form-group">
                                                                            <i
                                                                                class="field-icon icofont-travelling"></i>
                                                                            <div class="form-content dropdown-toggle"
                                                                                 data-toggle="dropdown"
                                                                                 aria-expanded="false">
                                                                                <div class="wrapper-more">
                                                                                    <label>Guests</label>
                                                                                    <div class="render">
                                                                                        <span class="adults">
                                                                                            <span class="one d-none">1 Guest</span>
                                                                                            <span class="multi"
                                                                                                  data-html=":count Guests">1 Guest</span>
                                                                                        </span>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div
                                                                                class="dropdown-menu select-guests-dropdown"
                                                                                x-placement="bottom-start"
                                                                                style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(15px, 75px, 0px);">
                                                                                <div class="dropdown-item-row">
                                                                                    <div class="label">Adults</div>
                                                                                    <div class="val">
                                                                                        <span class="btn-minus"
                                                                                              data-input="adults"><i
                                                                                                class="icon ion-md-remove"></i></span>
                                                                                        <span
                                                                                            class="count-display"><input
                                                                                                type="number"
                                                                                                name="adults" value="1"
                                                                                                min="1"></span>
                                                                                        <span class="btn-add"
                                                                                              data-input="adults"><i
                                                                                                class="icon ion-ios-add"></i></span>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="g-button-submit">
                                                            <button class="btn btn-primary btn-search"
                                                                    type="submit">Search
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                                <div role="tabpanel" class="tab-pane" id="bravo_parking">
                                                    <form action="{{route('space.search')}}" class="form bravo_form"
                                                          method="get">
                                                        <input type="hidden" name="_layout" value="map"/>
                                                        <input type="hidden" name="have" value="parking">
                                                        <input type="hidden" name="search_type" value="3">
                                                        <input type="hidden" name="booking_type" value="parking"/>
                                                        <div class="g-field-search">
                                                            <div class="row">
                                                                <div class="col-md-4 search-field-g border-right">
                                                                    <div class="form-group">
                                                                        <i class="field-icon fa icofont-map"></i>
                                                                        <div class="form-content">
                                                                            <label>Location</label>
                                                                            <div class="g-map-place">
                                                                                <input type="text" name="map_place"
                                                                                       placeholder="Where are you going?"
                                                                                       value=""
                                                                                       class="form-control border-0 pac-target-input"
                                                                                       autocomplete="off">
                                                                                <div class="map d-none"
                                                                                     id="map-HKRAwJJOWl"
                                                                                     style="position: relative; overflow: hidden;">
                                                                                    <div
                                                                                        style="height: 100%; width: 100%; position: absolute; top: 0px; left: 0px; background-color: rgb(229, 227, 223);">
                                                                                        <div style="overflow: hidden;">
                                                                                        </div>
                                                                                        <div class="gm-style"
                                                                                             style="position: absolute; z-index: 0; left: 0px; top: 0px; height: 100%; width: 100%; padding: 0px; border-width: 0px; margin: 0px;">
                                                                                            <div tabindex="0"
                                                                                                 aria-label="Map"
                                                                                                 aria-roledescription="map"
                                                                                                 role="region"
                                                                                                 style="position: absolute; z-index: 0; left: 0px; top: 0px; height: 100%; width: 100%; padding: 0px; border-width: 0px; margin: 0px; cursor: url(&quot;https://maps.gstatic.com/mapfiles/openhand_8_8.cur&quot;), default; touch-action: pan-x pan-y;">
                                                                                                <div
                                                                                                    style="z-index: 1; position: absolute; left: 50%; top: 50%; width: 100%;">
                                                                                                    <div
                                                                                                        style="position: absolute; left: 0px; top: 0px; z-index: 100; width: 100%;">
                                                                                                        <div
                                                                                                            style="position: absolute; left: 0px; top: 0px; z-index: 0;">
                                                                                                        </div>
                                                                                                    </div>
                                                                                                    <div
                                                                                                        style="position: absolute; left: 0px; top: 0px; z-index: 101; width: 100%;">
                                                                                                    </div>
                                                                                                    <div
                                                                                                        style="position: absolute; left: 0px; top: 0px; z-index: 102; width: 100%;">
                                                                                                    </div>
                                                                                                    <div
                                                                                                        style="position: absolute; left: 0px; top: 0px; z-index: 103; width: 100%;">
                                                                                                    </div>
                                                                                                    <div
                                                                                                        style="position: absolute; left: 0px; top: 0px; z-index: 0;">
                                                                                                    </div>
                                                                                                </div>
                                                                                                <div
                                                                                                    style="z-index: 3; position: absolute; height: 100%; width: 100%; padding: 0px; border-width: 0px; margin: 0px; left: 0px; top: 0px; touch-action: pan-x pan-y;">
                                                                                                    <div
                                                                                                        style="z-index: 4; position: absolute; left: 50%; top: 50%; width: 100%;">
                                                                                                        <div
                                                                                                            style="position: absolute; left: 0px; top: 0px; z-index: 104; width: 100%;">
                                                                                                        </div>
                                                                                                        <div
                                                                                                            style="position: absolute; left: 0px; top: 0px; z-index: 105; width: 100%;">
                                                                                                        </div>
                                                                                                        <div
                                                                                                            style="position: absolute; left: 0px; top: 0px; z-index: 106; width: 100%;">
                                                                                                            <span
                                                                                                                id="02177CDD-B281-4C16-ABC3-19A478B8A7B2"
                                                                                                                style="display: none;">To
                                                                                                                navigate,
                                                                                                                press
                                                                                                                the
                                                                                                                arrow
                                                                                                                keys.</span>
                                                                                                        </div>
                                                                                                        <div
                                                                                                            style="position: absolute; left: 0px; top: 0px; z-index: 107; width: 100%;">
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>
                                                                                                <div
                                                                                                    class="gm-style-moc"
                                                                                                    style="z-index: 4; position: absolute; height: 100%; width: 100%; padding: 0px; border-width: 0px; margin: 0px; left: 0px; top: 0px; opacity: 0;">
                                                                                                    <p
                                                                                                        class="gm-style-mot">
                                                                                                    </p>
                                                                                                </div>
                                                                                            </div>
                                                                                            <iframe
                                                                                                aria-hidden="true"
                                                                                                frameborder="0"
                                                                                                tabindex="-1"
                                                                                                style="z-index: -1; position: absolute; width: 100%; height: 100%; top: 0px; left: 0px; border: none;"></iframe>
                                                                                            <div
                                                                                                style="pointer-events: none; width: 100%; height: 100%; box-sizing: border-box; position: absolute; z-index: 1000002; opacity: 0; border: 2px solid rgb(26, 115, 232);">
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <input type="hidden" name="map_lat"
                                                                                       value="">
                                                                                <input type="hidden" name="map_lgn"
                                                                                       value="">
                                                                            </div>

                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-3 border-right">
                                                                    <div class="form-group">
                                                                        <i class="field-icon icofont-wall-clock"></i>
                                                                        <div class="form-content">
                                                                            <div class="form-date-search">
                                                                                <div class="date-wrapper">
                                                                                    <div class="check-in-wrapper">
                                                                                        <label>From - To</label>
                                                                                        <div
                                                                                            class="render check-in-render">{{$now}}</div>
                                                                                        <span> - </span>
                                                                                        <div
                                                                                            class="render check-out-render">{{$now}}</div>
                                                                                    </div>
                                                                                </div>
                                                                                <input type="hidden"
                                                                                       class="check-in-input"
                                                                                       value="{{$now}}" name="start">
                                                                                <input type="hidden"
                                                                                       class="check-out-input"
                                                                                       value="{{$now}}" name="end">
                                                                                <input type="text" class="check-in-out"
                                                                                       name="date"
                                                                                       value="{{$now}} - {{$now}}">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-3 border-right">
                                                                    <style>
                                                                        .select-time-dropdown {
                                                                            transform: none !important;
                                                                            border-top-left-radius: 0;
                                                                            border-top-right-radius: 0;
                                                                            top: 100% !important;
                                                                            margin-top: 0;
                                                                            right: 0;
                                                                            border-color: #dee2e6;
                                                                            width: 100%;
                                                                        }

                                                                        .select-time-dropdown .dropdown-item-row {
                                                                            display: flex;
                                                                            align-items: center;
                                                                            margin: 10px 15px;
                                                                        }

                                                                        .select-time-dropdown .dropdown-item-row .val {
                                                                            margin-right: 0;
                                                                            margin-left: auto;
                                                                        }

                                                                    </style>
                                                                    <div class="form-select-time">
                                                                        <div class="form-group">
                                                                            <i
                                                                                class="field-icon icofont-wall-clock"></i>
                                                                            <div class="form-content dropdown-toggle"
                                                                                 data-toggle="dropdown">
                                                                                <div class="wrapper-more">
                                                                                    <label>Time From - Time To</label>
                                                                                    <div class="render">
                                                                                        <span class="from_hour">
                                                                                            <span
                                                                                                class="multi"
                                                                                                data-html=":fromHourData">Any</span></span>
                                                                                        -
                                                                                        <span class="to_hour">
                                                                                            <span
                                                                                                class="multi"
                                                                                                data-html=":toHourData">Any</span>
                                                                                        </span>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div
                                                                                class="dropdown-menu select-time-dropdown">
                                                                                <div class="dropdown-item-row">
                                                                                    <div class="label">From
                                                                                    </div>
                                                                                    <div class="val">
                                                                                        <select name="from_hour"
                                                                                                id="from_hour"
                                                                                                class="form-control">
                                                                                            <option value="">From
                                                                                            </option>
                                                                                            {!! \App\Helpers\Constants::getTimeOptions() !!}
                                                                                        </select>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="dropdown-item-row">
                                                                                    <div class="label">To
                                                                                    </div>
                                                                                    <div class="val">
                                                                                        <select name="to_hour"
                                                                                                id="to_hour"
                                                                                                class="form-control">
                                                                                            <option value="">To</option>
                                                                                            {!! \App\Helpers\Constants::getTimeOptions() !!}
                                                                                        </select>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-2 border-right">
                                                                    <div class="form-select-guests">
                                                                        <div class="form-group">
                                                                            <i
                                                                                class="field-icon icofont-travelling"></i>
                                                                            <div class="form-content dropdown-toggle"
                                                                                 data-toggle="dropdown"
                                                                                 aria-expanded="false">
                                                                                <div class="wrapper-more">
                                                                                    <label>Guests</label>
                                                                                    <div class="render">
                                                                                        <span class="adults">
                                                                                            <span class="one d-none">1 Guest</span>
                                                                                            <span class="multi"
                                                                                                  data-html=":count Guests">1 Guest</span>
                                                                                        </span>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div
                                                                                class="dropdown-menu select-guests-dropdown"
                                                                                x-placement="bottom-start"
                                                                                style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(15px, 75px, 0px);">
                                                                                <div class="dropdown-item-row">
                                                                                    <div class="label">Adults</div>
                                                                                    <div class="val">
                                                                                        <span class="btn-minus"
                                                                                              data-input="adults"><i
                                                                                                class="icon ion-md-remove"></i></span>
                                                                                        <span
                                                                                            class="count-display"><input
                                                                                                type="number"
                                                                                                name="adults" value="1"
                                                                                                min="1"></span>
                                                                                        <span class="btn-add"
                                                                                              data-input="adults"><i
                                                                                                class="icon ion-ios-add"></i></span>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="g-button-submit">
                                                            <button class="btn btn-primary btn-search"
                                                                    type="submit">Search
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                                <div role="tabpanel" class="tab-pane " id="bravo_coffee">
                                                    <form action="{{route('space.search')}}"
                                                          class="form bravo_form" method="get">
                                                        <input type="hidden" name="_layout" value="map"/>
                                                        <input type="hidden" name="have" value="cafe">
                                                        <input type="hidden" name="search_type" value="4">
                                                        <input type="hidden" name="booking_type" value="coffee"/>
                                                        <div class="g-field-search">
                                                            <div class="row">
                                                                <div class="col-md-4 search-field-g border-right">
                                                                    <div class="form-group">
                                                                        <i class="field-icon fa icofont-map"></i>
                                                                        <div class="form-content">
                                                                            <label>Location</label>
                                                                            <div class="g-map-place">
                                                                                <input type="text" name="map_place"
                                                                                       placeholder="Where are you going?"
                                                                                       value=""
                                                                                       class="form-control border-0 pac-target-input"
                                                                                       autocomplete="off">
                                                                                <div class="map d-none"
                                                                                     id="map-dVT0gzjJ5L"
                                                                                     style="position: relative; overflow: hidden;">
                                                                                    <div
                                                                                        style="height: 100%; width: 100%; position: absolute; top: 0px; left: 0px; background-color: rgb(229, 227, 223);">
                                                                                        <div style="overflow: hidden;">
                                                                                        </div>
                                                                                        <div class="gm-style"
                                                                                             style="position: absolute; z-index: 0; left: 0px; top: 0px; height: 100%; width: 100%; padding: 0px; border-width: 0px; margin: 0px;">
                                                                                            <div tabindex="0"
                                                                                                 aria-label="Map"
                                                                                                 aria-roledescription="map"
                                                                                                 role="region"
                                                                                                 style="position: absolute; z-index: 0; left: 0px; top: 0px; height: 100%; width: 100%; padding: 0px; border-width: 0px; margin: 0px; cursor: url(&quot;https://maps.gstatic.com/mapfiles/openhand_8_8.cur&quot;), default; touch-action: pan-x pan-y;">
                                                                                                <div
                                                                                                    style="z-index: 1; position: absolute; left: 50%; top: 50%; width: 100%;">
                                                                                                    <div
                                                                                                        style="position: absolute; left: 0px; top: 0px; z-index: 100; width: 100%;">
                                                                                                        <div
                                                                                                            style="position: absolute; left: 0px; top: 0px; z-index: 0;">
                                                                                                        </div>
                                                                                                    </div>
                                                                                                    <div
                                                                                                        style="position: absolute; left: 0px; top: 0px; z-index: 101; width: 100%;">
                                                                                                    </div>
                                                                                                    <div
                                                                                                        style="position: absolute; left: 0px; top: 0px; z-index: 102; width: 100%;">
                                                                                                    </div>
                                                                                                    <div
                                                                                                        style="position: absolute; left: 0px; top: 0px; z-index: 103; width: 100%;">
                                                                                                    </div>
                                                                                                    <div
                                                                                                        style="position: absolute; left: 0px; top: 0px; z-index: 0;">
                                                                                                    </div>
                                                                                                </div>
                                                                                                <div
                                                                                                    style="z-index: 3; position: absolute; height: 100%; width: 100%; padding: 0px; border-width: 0px; margin: 0px; left: 0px; top: 0px; touch-action: pan-x pan-y;">
                                                                                                    <div
                                                                                                        style="z-index: 4; position: absolute; left: 50%; top: 50%; width: 100%;">
                                                                                                        <div
                                                                                                            style="position: absolute; left: 0px; top: 0px; z-index: 104; width: 100%;">
                                                                                                        </div>
                                                                                                        <div
                                                                                                            style="position: absolute; left: 0px; top: 0px; z-index: 105; width: 100%;">
                                                                                                        </div>
                                                                                                        <div
                                                                                                            style="position: absolute; left: 0px; top: 0px; z-index: 106; width: 100%;">
                                                                                                            <span
                                                                                                                id="9C36793F-D0BB-494C-949B-F68CFBD1069A"
                                                                                                                style="display: none;">To
                                                                                                                navigate,
                                                                                                                press
                                                                                                                the
                                                                                                                arrow
                                                                                                                keys.</span>
                                                                                                        </div>
                                                                                                        <div
                                                                                                            style="position: absolute; left: 0px; top: 0px; z-index: 107; width: 100%;">
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>
                                                                                                <div
                                                                                                    class="gm-style-moc"
                                                                                                    style="z-index: 4; position: absolute; height: 100%; width: 100%; padding: 0px; border-width: 0px; margin: 0px; left: 0px; top: 0px; opacity: 0;">
                                                                                                    <p
                                                                                                        class="gm-style-mot">
                                                                                                    </p>
                                                                                                </div>
                                                                                            </div>
                                                                                            <iframe
                                                                                                aria-hidden="true"
                                                                                                frameborder="0"
                                                                                                tabindex="-1"
                                                                                                style="z-index: -1; position: absolute; width: 100%; height: 100%; top: 0px; left: 0px; border: none;"></iframe>
                                                                                            <div
                                                                                                style="pointer-events: none; width: 100%; height: 100%; box-sizing: border-box; position: absolute; z-index: 1000002; opacity: 0; border: 2px solid rgb(26, 115, 232);">
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <input type="hidden" name="map_lat"
                                                                                       value="">
                                                                                <input type="hidden" name="map_lgn"
                                                                                       value="">
                                                                            </div>

                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-3 border-right">
                                                                    <div class="form-group">
                                                                        <i class="field-icon icofont-wall-clock"></i>
                                                                        <div class="form-content">
                                                                            <div class="form-date-search">
                                                                                <div class="date-wrapper">
                                                                                    <div class="check-in-wrapper">
                                                                                        <label>From - To</label>
                                                                                        <div
                                                                                            class="render check-in-render">{{$now}}</div>
                                                                                        <span> - </span>
                                                                                        <div
                                                                                            class="render check-out-render">{{$now}}</div>
                                                                                    </div>
                                                                                </div>
                                                                                <input type="hidden"
                                                                                       class="check-in-input"
                                                                                       value="{{$now}}" name="start">
                                                                                <input type="hidden"
                                                                                       class="check-out-input"
                                                                                       value="{{$now}}" name="end">
                                                                                <input type="text"
                                                                                       class="check-in-out" name="date"
                                                                                       value="{{$now}} - {{$now}}">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-3 border-right">
                                                                    <style>
                                                                        .select-time-dropdown {
                                                                            transform: none !important;
                                                                            border-top-left-radius: 0;
                                                                            border-top-right-radius: 0;
                                                                            top: 100% !important;
                                                                            margin-top: 0;
                                                                            right: 0;
                                                                            border-color: #dee2e6;
                                                                            width: 100%;
                                                                        }

                                                                        .select-time-dropdown .dropdown-item-row {
                                                                            display: flex;
                                                                            align-items: center;
                                                                            margin: 10px 15px;
                                                                        }

                                                                        .select-time-dropdown .dropdown-item-row .val {
                                                                            margin-right: 0;
                                                                            margin-left: auto;
                                                                        }

                                                                    </style>
                                                                    <div class="form-select-time">
                                                                        <div class="form-group">
                                                                            <i
                                                                                class="field-icon icofont-wall-clock"></i>
                                                                            <div class="form-content dropdown-toggle"
                                                                                 data-toggle="dropdown">
                                                                                <div class="wrapper-more">
                                                                                    <label>Time From - Time To</label>
                                                                                    <div class="render">
                                                                                        <span class="from_hour">
                                                                                            <span
                                                                                                class="multi"
                                                                                                data-html=":fromHourData">Any</span></span>
                                                                                        -
                                                                                        <span class="to_hour">
                                                                                            <span
                                                                                                class="multi"
                                                                                                data-html=":toHourData">Any</span>
                                                                                        </span>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div
                                                                                class="dropdown-menu select-time-dropdown">
                                                                                <div class="dropdown-item-row">
                                                                                    <div class="label">From
                                                                                    </div>
                                                                                    <div class="val">
                                                                                        <select name="from_hour"
                                                                                                id="from_hour"
                                                                                                class="form-control">
                                                                                            <option value="">From
                                                                                            </option>
                                                                                            {!! \App\Helpers\Constants::getTimeOptions() !!}
                                                                                        </select>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="dropdown-item-row">
                                                                                    <div class="label">To
                                                                                    </div>
                                                                                    <div class="val">
                                                                                        <select name="to_hour"
                                                                                                id="to_hour"
                                                                                                class="form-control">
                                                                                            <option value="">To</option>
                                                                                            {!! \App\Helpers\Constants::getTimeOptions() !!}
                                                                                        </select>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-2 border-right">
                                                                    <div class="form-select-guests">
                                                                        <div class="form-group">
                                                                            <i
                                                                                class="field-icon icofont-travelling"></i>
                                                                            <div class="form-content dropdown-toggle"
                                                                                 data-toggle="dropdown"
                                                                                 aria-expanded="false">
                                                                                <div class="wrapper-more">
                                                                                    <label>Guests</label>
                                                                                    <div class="render">
                                                                                        <span class="adults">
                                                                                            <span class="one d-none">1 Guest</span>
                                                                                            <span class="multi"
                                                                                                  data-html=":count Guests">1 Guest</span>
                                                                                        </span>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div
                                                                                class="dropdown-menu select-guests-dropdown"
                                                                                x-placement="bottom-start"
                                                                                style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(15px, 75px, 0px);">
                                                                                <div class="dropdown-item-row">
                                                                                    <div class="label">Adults</div>
                                                                                    <div class="val">
                                                                                        <span class="btn-minus"
                                                                                              data-input="adults"><i
                                                                                                class="icon ion-md-remove"></i></span>
                                                                                        <span
                                                                                            class="count-display"><input
                                                                                                type="number"
                                                                                                name="adults" value="1"
                                                                                                min="1"></span>
                                                                                        <span class="btn-add"
                                                                                              data-input="adults"><i
                                                                                                class="icon ion-ios-add"></i></span>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="g-button-submit">
                                                            <button class="btn btn-primary btn-search"
                                                                    type="submit">Search
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class='slides-container'>
        @foreach ($list_slider as $item)
            @php $img = get_file_url($item['bg_image'],'full') @endphp
            <div class="item">
                <div class='sangar-content'><img src='{{ $img }}'></div>
            </div>
        @endforeach

    </div>
    <nav class="slides-navigation">
        <a href="#" class="next slidernav"></a>
        <a href="#" class="prev slidernav"></a>
    </nav>
</div>

<script>
    function getRoundedTime() {
  const now = new Date();
  let hours = now.getHours();
  let minutes = now.getMinutes();

  if (minutes > 0) {
    minutes = 0;
    hours = (hours + 1) % 24;
  }

  // Format hours and minutes as two digits
  const formattedHours = hours.toString().padStart(2, '0');
  const formattedMinutes = minutes.toString().padStart(2, '0');

  return `${formattedHours}:${formattedMinutes}`;
}


    $(document).ready(function(){
        console.log(getRoundedTime());
        $('select[name="from_hour"]').val(getRoundedTime());
    });
</script>