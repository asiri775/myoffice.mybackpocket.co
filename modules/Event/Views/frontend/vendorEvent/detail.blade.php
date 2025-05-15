@extends('layouts.new_user')
@section('head')
@endsection
@section('content')
    <div class="content sm-gutter">
        <!-- START BREADCRUMBS-->
        <div class="bg-white">
            <div class="container-fluid pl-5">
                <ol class="breadcrumb breadcrumb-alt bg-white mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('event.vendor.index') }}">Events</a></li>
                    <li class="breadcrumb-item active">{{ $row->id ? __('Edit: ') . $row->title : __('Add new event') }}</li>
                </ol>
            </div>
        </div>

        <div class="container-fluid p-5">
            @if ($row->id)
                @include('Language::admin.navigation')
            @endif
            <div class="lang-content-box">
                <form
                    action="{{ route('event.vendor.store', ['id' => $row->id ? $row->id : '-1', 'lang' => request()->query('lang')]) }}"
                    method="post">
                    @csrf
                    <div class="form-add-service">
                        <div class="nav nav-tabs nav-fill" id="nav-tab" role="tablist">
                            <a data-toggle="tab" href="#nav-tour-content" aria-selected="true"
                                class="active">{{ __('1. Content') }}</a>
                            <a data-toggle="tab" href="#nav-tour-location"
                                aria-selected="false">{{ __('2. Locations') }}</a>
                            <a data-toggle="tab" href="#nav-tour-pricing" aria-selected="false">{{ __('3. Pricing') }}</a>
                            @if (is_default_lang())
                                <a data-toggle="tab" href="#nav-attribute"
                                    aria-selected="false">{{ __('4. Attributes') }}</a>
                            @endif
                        </div>
                        <div class="tab-content" id="nav-tabContent">
                            <div class="tab-pane fade show active" id="nav-tour-content">
                                @include('Event::admin/event/content')
                                @if (is_default_lang())
                                    <div class="form-group">
                                        <label>{{ __('Featured Image') }}</label>
                                        {!! \Modules\Media\Helpers\FileHelper::fieldUpload('image_id', $row->image_id) !!}
                                    </div>
                                @endif
                            </div>
                            <div class="tab-pane fade" id="nav-tour-location">
                                @include('Event::admin/event/location', ['is_smart_search' => '1'])
                                @include('Hotel::admin.hotel.surrounding')

                            </div>
                            <div class="tab-pane fade" id="nav-tour-pricing">
                                <div class="panel">
                                    <div class="panel-title"><strong>{{ __('Default State') }}</strong></div>
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <select name="default_state" class="custom-select">
                                                        <option value="1"
                                                            @if (old('default_state', $row->default_state ?? 0) == 1) selected @endif>
                                                            {{ __('Always available') }}</option>
                                                        <option value="0"
                                                            @if (old('default_state', $row->default_state ?? 0) == 0) selected @endif>
                                                            {{ __('Only available on specific dates') }}</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @include('Event::admin/event/pricing')
                            </div>
                            @if (is_default_lang())
                                <div class="tab-pane fade" id="nav-attribute">
                                    @include('Event::admin/event/attributes')
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="d-flex justify-content-between">
                        <button class="btn btn-primary" type="submit"><i class="fa fa-save"></i>
                            {{ __('Save Changes') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('footer')
    <script type="text/javascript" src="{{ asset('libs/tinymce/js/tinymce/tinymce.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/condition.js?_ver=' . config('app.version')) }}"></script>
    <script type="text/javascript" src="{{ url('module/core/js/map-engine.js?_ver=' . config('app.version')) }}"></script>
    {!! App\Helpers\MapEngine::scripts() !!}
    <script>
        jQuery(function($) {
            new BravoMapEngine('map_content', {
                fitBounds: true,
                center: [{{ $row->map_lat ?? setting_item('map_lat_default') }},
                    {{ $row->map_lng ?? setting_item('map_lng_default') }}
                ],
                zoom: {{ $row->map_zoom ?? '8' }},
                ready: function(engineMap) {
                    @if ($row->map_lat && $row->map_lng)
                        engineMap.addMarker([{{ $row->map_lat }}, {{ $row->map_lng }}], {
                            icon_options: {}
                        });
                    @endif
                    engineMap.on('click', function(dataLatLng) {
                        engineMap.clearMarkers();
                        engineMap.addMarker(dataLatLng, {
                            icon_options: {}
                        });
                        $("input[name=map_lat]").attr("value", dataLatLng[0]);
                        $("input[name=map_lng]").attr("value", dataLatLng[1]);
                    });
                    engineMap.on('zoom_changed', function(zoom) {
                        $("input[name=map_zoom]").attr("value", zoom);
                    });

                    if (bookingCore.map_provider === "gmap") {
                        engineMap.searchBox($('#customPlaceAddress'), function(dataLatLng) {
                            engineMap.clearMarkers();
                            engineMap.addMarker(dataLatLng, {
                                icon_options: {}
                            });
                            $("input[name=map_lat]").attr("value", dataLatLng[0]);
                            $("input[name=map_lng]").attr("value", dataLatLng[1]);
                        });
                    }
                    engineMap.searchBox($('.bravo_searchbox'), function(dataLatLng) {
                        engineMap.clearMarkers();
                        engineMap.addMarker(dataLatLng, {
                            icon_options: {}
                        });
                        $("input[name=map_lat]").attr("value", dataLatLng[0]);
                        $("input[name=map_lng]").attr("value", dataLatLng[1]);
                    });
                }
            });
        })
    </script>
@endsection
