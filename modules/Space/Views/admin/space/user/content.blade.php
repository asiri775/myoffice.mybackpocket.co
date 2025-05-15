<div class="panel">
    <div class="panel-body pt-0">
        <div class="row">
            <div class="col-md-6 col-12">
                <div class="panel-title pl-0"><strong>{{ __('Space Content') }}</strong></div>
                <div class="row">
                    <div class="col-md-12 col-12">
                        <div class="form-group">
                            <label>{{ __('Title') }}</label>
                            <input type="text" value="{!! clean(old('title', $translation->title)) !!}"
                                placeholder="{{ __('Name of the space') }}" name="title" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-12 col-12">
                        <div class="form-group">
                            <label>{{ __('Alias') }}</label>
                            <input type="text" value="{!! clean(old('alias', $row->alias)) !!}"
                                placeholder="{{ __('Alias of Space Name') }}" name="alias" class="form-control">
                        </div>
                    </div>
                </div>
                {{-- <div class="form-group">
                    <label class="control-label">{{ __('Description') }}</label>
                    <div class="">
                        <textarea name="content" class="d-none has-ckeditor" cols="30" rows="10">{{ old('content', $translation->content) }}</textarea>
                    </div>
                </div> --}}
                <style>

#spaceSpaceType{
    margin: 15px 0;
}

                </style>
                <div id="spaceSpaceType">
                    <?php
                    if (!isset($selected_terms)) {
                        $selected_terms = new \Illuminate\Support\Collection(old('terms', []));
                    }
                    ?>
    
                    @foreach ($attributes as $attribute)
                        <?php
    if($attribute->slug == "space-type"){
    ?>
                        <div class="panels">
                            <div class=""><strong>{{ __(':name', ['name' => $attribute->name]) }}</strong>
                            </div>
                            <?php
            if(strtolower($attribute->name) === "space type"){
                ?>
                            <p style="margin: 10px 0;">Must select at least 1 Space Type</p>
                            <?php
            }
            ?>
                            <div class="" style="margin-top: 20px;">
                                <div class="terms-scrollable terms-scrollable-ux">
                                    @foreach ($attribute->terms as $term)
                                        <label class="term-item">
                                            <input @if (!empty($selected_terms) and $selected_terms->contains($term->id)) checked @endif type="checkbox"
                                                name="terms[]" value="{{ $term->id }}">
                                            <span class="term-name">{{ $term->name }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <?php
    }
    ?>
                    @endforeach

                </div>
            </div>
            <div class="col-md-6 col-12">
                <div class="panel">
                    <div class="panel-title"><strong>{{ __('Location') }}</strong></div>
                    <div class="panel-body pt-0">
                        @if (is_default_lang())
                            <div class="form-group d-none">
                                <label class="control-label">{{ __('Location') }}</label>
                                @if (!empty($is_smart_search))
                                    <div class="form-group-smart-search">
                                        <div class="form-content">
                                            <?php
                                            $location_name = '';
                                            $list_json = [];
                                            $traverse = function ($locations, $prefix = '') use (&$traverse, &$list_json, &$location_name, $row) {
                                                foreach ($locations as $location) {
                                                    $translate = $location->translateOrOrigin(app()->getLocale());
                                                    if ($row->location_id == $location->id) {
                                                        $location_name = $translate->name;
                                                    }
                                                    $list_json[] = [
                                                        'id' => $location->id,
                                                        'title' => $prefix . ' ' . $translate->name,
                                                    ];
                                                    $traverse($location->children, $prefix . '-');
                                                }
                                            };
                                            $traverse($space_location);
                                            ?>
                                            <div class="smart-search">
                                                <input type="text"
                                                    class="smart-search-location parent_text form-control"
                                                    placeholder="{{ __('-- Please Select --') }}"
                                                    value="{{ $location_name }}" data-onLoad="{{ __('Loading...') }}"
                                                    data-default="{{ json_encode($list_json) }}">
                                                <input type="hidden" class="child_id" name="location_id"
                                                    value="{{ $row->location_id ?? Request::query('location_id') }}">
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="">
                                        <select name="location_id" class="form-control">
                                            <option value="">{{ __('-- Please Select --') }}</option>
                                            <?php
                                            $traverse = function ($locations, $prefix = '') use (&$traverse, $row) {
                                                foreach ($locations as $location) {
                                                    $selected = '';
                                                    if ($row->location_id == $location->id) {
                                                        $selected = 'selected';
                                                    }
                                                    printf("<option value='%s' %s>%s</option>", $location->id, $selected, $prefix . ' ' . $location->name);
                                                    $traverse($location->children, $prefix . '-');
                                                }
                                            };
                                            $traverse($space_location);
                                            ?>
                                        </select>
                                    </div>
                                @endif
                            </div>
                        @endif
                        <div class="form-group row">
                            <div class="col-md-9 col-12">
                                <label class="control-label">{{ __('Address') }}</label>
                                <input type="text" name="address" id="addressLineOne" class="form-control"
                                    placeholder="{{ __('Address') }}" value="{{ old('address', $row->address) }}">
                            </div>
                            <div class="col-md-3 col-12">
                                <label class="control-label">{{ __('Suite/Unit#') }}</label>
                                <input type="text" maxlength="4" name="address_unit" id="customPlaceAddress2"
                                    class="form-control" placeholder="{{ __('Suite/Unit#') }}"
                                    value="{{ old('address_unit', $row->address_unit) }}">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-5 col-12">
                                <div class="form-group">
                                    <label class="control-label">{{ __('City') }}</label>
                                    <input type="text" name="city" id="customPlaceCity" class="form-control"
                                        placeholder="{{ __('City') }}" value="{{ old('city', $row->city) }}">
                                </div>
                            </div>
                            <div class="col-md-2 col-12">
                                <div class="form-group">
                                    <label class="control-label">{{ __('State') }}</label>
                                    <input type="text" name="state" id="customPlaceState" class="form-control"
                                        placeholder="{{ __('State') }}" value="{{ old('state', $row->state) }}">
                                </div>
                            </div>
                            <div class="col-md-2 col-12">
                                <div class="form-group">
                                    <label class="control-label">{{ __('Zip') }}</label>
                                    <input type="text" name="zip" id="customPlaceZip" class="form-control"
                                        placeholder="{{ __('Zip') }}" value="{{ old('zip', $row->zip) }}">
                                </div>
                            </div>


                            <div class="col-md-3 col-12">
                                <div class="form-group">
                                    <label>{{ __('Country') }}</label>
                                    <input type="text" name="country" id="customPlaceCountry"
                                        class="form-control" placeholder="{{ __('Country') }}"
                                        value="{{ old('country', $row->country) }}">
                                </div>
                            </div>

                        </div>
                        @if (is_default_lang())
                            <div class="form-group">
                                <label class="control-label">{{ __('The geographic coordinate') }}</label>
                                <div class="control-map-group">
                                    <div id="map_content"></div>
                                    <input type="text" placeholder="{{ __('Search by name...') }}"
                                        class="bravo_searchbox form-control" autocomplete="off"
                                        onkeydown="return event.key !== 'Enter';">
                                    <div class="g-control">
                                        <div class="form-group">
                                            <label>{{ __('Map Latitude') }}:</label>
                                            <input type="text" name="map_lat" class="form-control"
                                                value="{{ old('map_lat', $row->map_lat) }}"
                                                onkeydown="return event.key !== 'Enter';">
                                        </div>
                                        <div class="form-group">
                                            <label>{{ __('Map Longitude') }}:</label>
                                            <input type="text" name="map_lng" class="form-control"
                                                value="{{ old('map_lng', $row->map_lng) }}"
                                                onkeydown="return event.key !== 'Enter';">
                                        </div>
                                        <div class="form-group">
                                            <label>{{ __('Map Zoom') }}:</label>
                                            <input type="text" name="map_zoom" class="form-control"
                                                value="{{ old('map_zoom', $row->map_zoom) ?? '8' }}"
                                                onkeydown="return event.key !== 'Enter';">
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
</div>

@if (is_default_lang())
    <div class="panel">
        <div class="panel-title"><strong>{{ __('Basics') }}</strong></div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>{{ __('No. Desks') }}</label>
                        <input type="number" value="{{ old('desk', $row->desk) }}"
                            placeholder="{{ __('Example: 3') }}" name="desk" class="form-control">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>{{ __('No. Seats') }}</label>
                        <input type="number" value="{{ old('seat', $row->seat) }}"
                            placeholder="{{ __('Example: 5') }}" name="seat" class="form-control">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label">{{ __('Max Guests') }}</label>
                        <input type="number" step="any" name="max_guests" class="form-control"
                            value="{{ old('max_guests', $row->max_guests) }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>{{ __('Square') }}</label>
                        <input type="number" value="{{ old('square', $row->square) }}"
                            placeholder="{{ __('Example: 100') }}" name="square" class="form-control">
                    </div>
                </div>
                @if (is_default_lang())
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label">{{ __('Minimum advance reservations') }}</label>
                            <input type="number" name="min_day_before_booking" class="form-control"
                                value="{{ old('min_day_before_booking', $row->min_day_before_booking) }}"
                                placeholder="{{ __('Ex: 3') }}">
                            <i>{{ __('Leave blank if you dont need to use the min day option') }}</i>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label">{{ __('Minimum day stay requirements') }}</label>
                            <input type="number" name="min_day_stays" class="form-control"
                                value="{{ old('min_day_stays', $row->min_day_stays) }}"
                                placeholder="{{ __('Ex: 2') }}">
                            <i>{{ __('Leave blank if you dont need to set minimum day stay option') }}</i>
                        </div>
                    </div>
                @endif
            </div>

            <div class="row d-none">
                <div class="col-md-12 col-12">
                    <div class="form-group">
                        <label for="first_working_day">Rapidbook</label>
                        <select name="rapidbook" id="rapidbook" class="form-control">
                            <option {{ old('rapidbook', $row->rapidbook) == 0 ? 'selected' : '' }} value="0">Off
                            </option>
                            <option {{ old('rapidbook', $row->rapidbook) == 1 ? 'selected' : '' }} value="1">On
                            </option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 col-12">
                    <div class="switch-toggle-inline">
                        <div class="form-group switch-toggle">
                            <label class="switch">
                                <input type="checkbox"
                                    {{ old('accessible_workspace', $row->accessible_workspace) == 1 || old('accessible_workspace', $row->accessible_workspace) === 'on' ? 'checked' : '' }}
                                    type="checkbox" id="accessible_workspace" name="accessible_workspace">
                                <span class="slider round"></span>
                            </label>
                            <span for="accessible_workspace">
                                <span>Accessible Workspace</span>
                                <i data-toggle="tooltip" data-placement="top" class="icofont-info-circle"
                                    data-original-title="Select this option if your workspace has features and specifications that address mobility issues.  These include access to the work space, entrance and entryways, and bathroom facilities."></i>
                            </span>
                        </div>
                        <div class="form-group switch-toggle">
                            <label class="switch">
                                <input type="checkbox"
                                    {{ old('free_cancellation', $row->free_cancellation) == 1 || old('free_cancellation', $row->free_cancellation) === 'on' ? 'checked' : '' }}
                                    type="checkbox" id="free_cancellation" name="free_cancellation">
                                <span class="slider round"></span>
                            </label>
                            <span for="free_cancellation">Free Cancellation</span>
                        </div>
                    </div>
                </div>
            </div>


            <style>
                #contentInnerAmenties .panel {
                    border: none;
                    box-shadow: none;
                }
            </style>

        </div>
    </div>

    <div class="panel">

        <div id="contentInnerAmenties">
            @include('Space::admin/space/user/amenities')
        </div>

    </div>

    <div class="panel">
        <div class="panel-body">

            <div class="form-group-item mt-3" id="spaceFaqLists">
                <div class="control-inline-label">
                    <label class="control-label">{{ __('FAQs') }}</label>
                    <a href="javascript:;" id="loadDefaultFaqs">Load Default Content</a>
                </div>

                <div class="g-items-header d-none">
                    <div class="row">
                        <div class="col-md-5">{{ __('Title') }}</div>
                        <div class="col-md-5">{{ __('Content') }}</div>
                        <div class="col-md-1"></div>
                    </div>
                </div>
                <div class="g-items faq-accord-items">
                    @if (!empty(old('faqs', $translation->faqs)))
                        @php
                            if (!is_array(old('faqs', $translation->faqs))) {
                                $translation->faqs = json_decode(old('faqs', $translation->faqs));
                            }
                        @endphp
                        @foreach (old('faqs', $translation->faqs) as $key => $faq)
                            <div class="item" data-number="{{ $key }}">
                                <div class="accord-item">
                                    <div class="accord-head">
                                        <input type="text" name="faqs[{{ $key }}][title]"
                                            class="form-control title" value="{{ $faq['title'] }}"
                                            placeholder="{{ __('Eg: When and where does the tour end?') }}">
                                        <span class="btn btn-danger btn-sm btn-remove-item"><i
                                                class="fa fa-trash"></i></span>
                                    </div>
                                    <div class="accord-body">
                                        <textarea name="faqs[{{ $key }}][content]" class="form-control content" placeholder="...">{{ $faq['content'] }}</textarea>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
                <div class="text-right">
                    <span id="addMoreFaq" class="btn btn-info btn-sm btn-add-item"><i
                            class="icon ion-ios-add-circle-outline"></i>
                        {{ __('Add item') }}</span>
                </div>
                <div class="g-more hide">
                    <div class="item" data-number="__number__">
                        <div class="accord-item">
                            <div class="accord-head">
                                <input type="text" __name__="faqs[__number__][title]" class="form-control title"
                                    placeholder="{{ __('Eg: Can I bring my pet?') }}">
                                <span class="btn btn-danger btn-sm btn-remove-item"><i class="fa fa-trash"></i></span>
                            </div>
                            <div class="accord-body">
                                <textarea __name__="faqs[__number__][content]" class="form-control content" placeholder=""></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endif

<script type="text/javascript" src="{{ asset('libs/tinymce/js/tinymce/tinymce.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/condition.js?_ver=' . config('app.version')) }}"></script>
<script type="text/javascript" src="{{ url('module/core/js/map-engine.js?_ver=' . config('app.version')) }}"></script>
{!! App\Helpers\MapEngine::scripts() !!}
<script>
    function renderMapBox(mapData) {
        $("#map_content").html("");
        new BravoMapEngine('map_content', {
            disableScripts: true,
            fitBounds: true,
            center: [mapData.map_lat, mapData.map_lng],
            zoom: mapData.map_zoom,
            ready: function(engineMap) {
                engineMap.addMarker([mapData.map_lat, mapData.map_lng], {
                    icon_options: {}
                });
                engineMap.on('zoom_changed', function(zoom) {
                    $("input[name=map_zoom]").attr("value", zoom);
                });
            }
        });
    }

    @if ($row->map_lat != '' && $row->map_lng != '')
        jQuery(function($) {
            renderMapBox({
                map_lat: {{ $row->map_lat }},
                map_lng: {{ $row->map_lng }},
                map_zoom: {{ $row->map_zoom }}
            });
        })
    @endif
</script>

<script>
    function initGoogleAutoCompleteField() {
        var input = document.getElementById('addressLineOne');
        var options = {
            componentRestrictions: {
                country: ["us", "ca"]
            }
        };
        var autocomplete = new google.maps.places.Autocomplete(input, options);
        google.maps.event.addListener(autocomplete, 'place_changed', function() {
            var place = autocomplete.getPlace();
            console.log(place);
            document.getElementById("customPlaceCity").value = '';
            document.getElementById("customPlaceState").value = '';
            document.getElementById("customPlaceCountry").value = '';
            // document.getElementById("addressLineTwo").value = '';
            document.getElementById("customPlaceZip").value = '';
            for (let addressComponent of place.address_components) {
                if (addressComponent['types'].includes("locality")) {
                    document.getElementById("customPlaceCity").value = addressComponent.long_name;
                } else if (addressComponent['types'].includes("administrative_area_level_1")) {
                    document.getElementById("customPlaceState").value = addressComponent.short_name;
                } else if (addressComponent['types'].includes("administrative_area_level_2")) {
                    // document.getElementById("addressLineTwo").value = addressComponent.long_name;
                } else if (addressComponent['types'].includes("country")) {
                    $("#customPlaceCountry").val(addressComponent.short_name);
                } else if (addressComponent['types'].includes("postal_code")) {
                    $("#customPlaceZip").val(addressComponent.short_name);
                }
            }
            document.getElementById("addressLineOne").value = place.name;
            let dataLatLng = [
                place.geometry.location.lat(),
                place.geometry.location.lng(),
                15
            ];
            renderMapBox({
                map_lat: dataLatLng[0],
                map_lng: dataLatLng[1],
                map_zoom: dataLatLng[2]
            });
            $("input[name=map_lat]").attr("value", dataLatLng[0]);
            $("input[name=map_lng]").attr("value", dataLatLng[1]);
            $("input[name=map_zoom]").attr("value", dataLatLng[2]);
        });
    }

    $(function() {
        initGoogleAutoCompleteField();
    });
</script>
