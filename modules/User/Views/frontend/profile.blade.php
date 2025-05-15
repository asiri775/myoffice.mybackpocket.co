@extends('layouts.yellow_user')
@section('head')
@endsection
@section('content')
<?php
if($dataUser->phone === "+"){
    $dataUser->phone = "";
}
?>
    <style>
        .bravo_wrap textarea.form-control {
            height: auto !important;
        }

        #map_content {
            width: 100%;
        }
    </style>
    <div class="content sm-gutter">
        <!-- START BREADCRUMBS-->
        <div class="bg-white">
            <div class="container-fluid pl-5">
                <ol class="breadcrumb breadcrumb-alt bg-white mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">{{ __('Settings') }}</li>
                </ol>
            </div>
        </div>
        <!-- END BREADCRUMBS -->
        <!-- START CONTAINER FLUID -->
        <div class="container-fluid p-5 user-form-settings">

            @include('admin.message')

            <div class="card card-default card-bordered p-4 card-radious">
                <div class="card-header ">
                    <div class="card-title">
                        <h4>
                            {{ __('Settings') }}
                        </h4>
                    </div>
                </div>
                <div class="card-body">
                    <form enctype="multipart/form-data" action="{{ route('user.profile.update') }}" method="post"
                        class="input-has-icon row-fix-col">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-title">
                                    <strong>{{ __('Personal Information') }}</strong>
                                </div>
                                <div class="row">

                                    @if ($is_vendor_access)
                                        <div class="col-md-6 col-12">
                                            <div class="form-group">
                                                <label>{{ __('Business name') }}</label>
                                                <input type="text"
                                                    value="{{ old('business_name', $dataUser->business_name) }}"
                                                    name="business_name" placeholder="{{ __('Business name') }}"
                                                    class="form-control">
                                                <i class="fa fa-user input-icon"></i>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <div class="form-group">
                                                <label>{{ __('User name') }}<span class="required">*</span></label>
                                                <input type="text" name="user_name"
                                                    value="{{ old('user_name', $dataUser->user_name) }}"
                                                    placeholder="{{ __('User name') }}" class="form-control">
                                                <i class="fa fa-user input-icon"></i>
                                            </div>
                                        </div>
                                    @else
                                        <div class="col-md-12 col-12">
                                            <div class="form-group">
                                                <label>{{ __('User name') }}<span class="required">*</span></label>
                                                <input type="text" name="user_name"
                                                    value="{{ old('user_name', $dataUser->user_name) }}"
                                                    placeholder="{{ __('User name') }}" class="form-control">
                                                <i class="fa fa-user input-icon"></i>
                                            </div>
                                        </div>
                                    @endif

                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>{{ __('First name') }}<span class="required">*</span></label>
                                            <input type="text" value="{{ old('first_name', $dataUser->first_name) }}"
                                                name="first_name" placeholder="{{ __('First name') }}"
                                                class="form-control">
                                            <i class="fa fa-user input-icon"></i>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>{{ __('Last name') }}<span class="required">*</span></label>
                                            <input type="text" value="{{ old('last_name', $dataUser->last_name) }}"
                                                name="last_name" placeholder="{{ __('Last name') }}" class="form-control">
                                            <i class="fa fa-user input-icon"></i>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>{{ __('E-mail') }}<span class="required">*</span></label>
                                            <input type="text" name="email"
                                                value="{{ old('email', $dataUser->email) }}"
                                                placeholder="{{ __('E-mail') }}" class="form-control">
                                            <i class="fa fa-envelope input-icon"></i>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mobileNoFieldDataParent">
                                            <label>{{ __('Phone Number') }}<span class="required">*</span></label>
                                            <input type="text" value="{{ $dataUser->phone }}"
                                                placeholder="{{ __('Phone Number') }}"
                                                class="form-control mobileNoFieldData">
                                            <input type="hidden" value="{{ $dataUser->phone }}" name="phone"
                                                class="real">
                                            <i class="fa fa-phone input-icon"></i>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>{{ __('Birthday') }}</label>
                                            <input type="text"
                                                value="{{ old('birthday', $dataUser->birthday ? display_date($dataUser->birthday) : '') }}"
                                                name="birthday" placeholder="{{ __('Birthday') }}"
                                                class="form-control date-picker">
                                            <i class="fa fa-birthday-cake input-icon"></i>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-title">
                                    <strong>{{ __('Social Media') }}</strong>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>{{ __('Instagram') }}</label>
                                            <input type="text"
                                                value="{{ old('instagram_link', $dataUser->instagram_link) }}"
                                                name="instagram_link" placeholder="{{ __('Instagram') }}"
                                                class="form-control">
                                            <i class="fa fa-instagram input-icon"></i>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>{{ __('Facebook') }}</label>
                                            <input type="text"
                                                value="{{ old('facebook_link', $dataUser->facebook_link) }}"
                                                name="facebook_link" placeholder="{{ __('Facebook') }}"
                                                class="form-control">
                                            <i class="fa fa-facebook input-icon"></i>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>{{ __('Website URL') }}</label>
                                            <input type="text"
                                                value="{{ old('site_link', $dataUser->site_link) }}"
                                                name="site_link" placeholder="{{ __('Website URL') }}"
                                                class="form-control">
                                            <i class="fa fa-globe input-icon"></i>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>{{ __('Public Profile') }}</label>
                                            <textarea name="bio" rows="5" class="form-control">{{ old('bio', $dataUser->bio) }}</textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-title">
                                    <strong>{{ __('Avatar') }}</strong>
                                </div>
                                <p style=" 
    margin-top: -10px;
    margin-bottom: 12px;
">
                                    <small>JPEG, PNG images are supported of max 2 MB. Should be square max
                                        1000x1000.</small>
                                </p>

                                <div class="form-group avatar-selector-main">
                                    <div class="avatar-selector">
                                        <div class="img-box"
                                            style="background-image: url('{{ $dataUser->getAvatarUrl() }}')">
                                        </div>
                                        <span class="or">OR</span>
                                        <div class="avatar-selections">
                                            <div class="selections">

                                            </div>
                                            <div class="actions">
                                                <button class="btn btn-primary uploadFromFiles"
                                                    type="button">{{ __('Upload Image') }}</button>
                                                <button class="btn btn-primary reverse chooseFromGallery"
                                                    data-toggle="modal" data-target="#profile-pic-gallery-modal"
                                                    type="button">{{ __('Select from Gallery') }}</button>
                                            </div>
                                        </div> 
                                    </div>
                                    <div class="upload-btn-wrapper d-none">
                                        <div class="input-group">
                                            <span class="input-group-btn">
                                                <span class="btn btn-default btn-file">
                                                    {{ __('Browse') }}… <input type="file">
                                                </span>
                                            </span>
                                            <input type="text" data-error="{{ __('Error upload...') }}"
                                                data-loading="{{ __('Loading...') }}" class="form-control text-view"
                                                readonly
                                                value="{{ get_file_url(old('avatar_id', $dataUser->avatar_id)) ?? ($dataUser->getAvatarUrl() ?? __('No Image')) }}">
                                        </div>
                                        <input type="hidden" class="form-control" name="avatar_id"
                                            value="{{ old('avatar_id', $dataUser->avatar_id) ?? '' }}">
                                        <img class="image-demo"
                                            src="{{ get_file_url(old('avatar_id', $dataUser->avatar_id)) ?? ($dataUser->getAvatarUrl() ?? '') }}" />
                                    </div>
                                </div>
                            </div> 
                            <div class="col-md-6">
                                <div class="form-title">
                                    <strong>{{ __('Location Information') }}</strong>
                                </div>
                                <div class="row">
                                    <div class="col-md-9 col-12">
                                        <div class="form-group">
                                            <label>{{ __('Address Line 1') }}<span class="required">*</span></label>
                                            <input type="text" id="addressLineOne"
                                                value="{{ old('address', $dataUser->address) }}" name="address"
                                                placeholder="{{ __('Address') }}" class="form-control">
                                            <i class="fa fa-location-arrow input-icon"></i>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-12">
                                        <div class="form-group">
                                            <label>{{ __('Suite/Unit#') }}</label>
                                            <input type="text" maxlength="4" id="addressLineTwo"
                                                value="{{ old('address2', $dataUser->address2) }}" name="address2"
                                                placeholder="{{ __('Address2') }}" class="form-control">
                                            <i class="fa fa-location-arrow input-icon"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">

                                    <div class="col-md-5 col-12">
                                        <div class="form-group">
                                            <label>{{ __('City') }}</label>
                                            <input type="text" id="city"
                                                value="{{ old('city', $dataUser->city) }}" name="city"
                                                placeholder="{{ __('City') }}" class="form-control">
                                            <i class="fa fa-street-view input-icon"></i>
                                        </div>
                                    </div>

                                    <div class="col-md-2 col-12">
                                        <div class="form-group">
                                            <label>{{ __('State') }}</label>
                                            <input type="text" id="state"
                                                value="{{ old('state', $dataUser->state) }}" name="state"
                                                placeholder="{{ __('State') }}" class="form-control">
                                            <i class="fa fa-map-signs input-icon"></i>
                                        </div>
                                    </div>

                                    <div class="col-md-2 col-12">
                                        <div class="form-group">
                                            <label>{{ __('Zip Code') }}</label>
                                            <input type="text" value="{{ old('zip_code', $dataUser->zip_code) }}"
                                                id="zipCode" name="zip_code" placeholder="{{ __('Zip Code') }}"
                                                class="form-control">
                                            <i class="fa fa-map-pin input-icon"></i>
                                        </div>
                                    </div>

                                    <div class="col-md-3 col-12">
                                        <div class="form-group">
                                            <label>{{ __('Country') }}</label>
                                            <select name="country" id="country" class="form-control">
                                                <option value="">{{ __('-- Select --') }}</option>
                                                @foreach (get_country_lists() as $id => $name)
                                                    <option @if (old('country', $dataUser->country ?? '') == $id) selected @endif
                                                        value="{{ $id }}">
                                                        {{ $name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <div class="control-map-group">
                                                <div id="map_content"></div>
                                                <div class="g-control d-none">
                                                    <div class="form-group">
                                                        <label>{{ __('Map Latitude') }}:</label>
                                                        <input type="text" name="map_lat" class="form-control"
                                                            value="{{ old('map_lat', $dataUser->map_lat) }}"
                                                            onkeydown="return event.key !== 'Enter';">
                                                    </div>
                                                    <div class="form-group">
                                                        <label>{{ __('Map Longitude') }}:</label>
                                                        <input type="text" name="map_lng" class="form-control"
                                                            value="{{ old('map_lng', $dataUser->map_lng) }}"
                                                            onkeydown="return event.key !== 'Enter';">
                                                    </div>
                                                    <div class="form-group">
                                                        <label>{{ __('Map Zoom') }}:</label>
                                                        <input type="text" name="map_zoom" class="form-control"
                                                            value="{{ old('map_zoom', $dataUser->map_zoom) ?? '8' }}"
                                                            onkeydown="return event.key !== 'Enter';">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <hr>
                                <button class="btn btn-primary" type="submit"><i class="fa fa-save"></i>
                                    {{ __('Save Changes') }}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

        </div>

    </div>

    <div class="modal fade" id="profile-pic-gallery-modal">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"
                        style="font-size: 24px;font-weight: 700;text-transform: uppercase;font-family: 'MONTSERRAT';">
                        {{ __('Select Avatar') }}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="gallery-showcase-items">
                        <?php
                        for ($i = 1; $i <= 24; $i++) {
                            ?>
                        <div class="gallery-showcase-item">
                            <a class="choose-gallery-item"
                                data-image="{{ asset('images/avatars/avatar-' . $i . '-min.png') }}">
                                <img src="{{ asset('images/avatars/avatar-' . $i . '-min.png') }}"
                                    alt="Avatar {{ $i }}">
                            </a>
                        </div>
                        <?php
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('pageEndJs')
    <script>
        async function fetchImageAsFile(url) {
            try {
                const response = await fetch(url);
                const blob = await response.blob();

                // Get the filename from the URL or set a default name
                const filename = url.substring(url.lastIndexOf('/') + 1) || 'image.jpg';

                // Create a File object
                const file = new File([blob], filename, {
                    type: blob.type
                });

                // You can now use this 'file' object as needed
                return file;
            } catch (error) {
                console.error('Error fetching the image:', error);
                return null;
            }
        }

        $(document).on("click", ".choose-gallery-item", function() {
            let imageUrl = $(this).attr("data-image");
            $("#profile-pic-gallery-modal").modal("hide");
            fetchImageAsFile(imageUrl)
                .then(file => {
                    var d = new FormData();
                    d.append('file', file);
                    d.append('type', "image");
                    $.ajax({
                        url: bookingCore.url + '/admin/module/media/store',
                        data: d,
                        dataType: 'json',
                        type: 'post',
                        contentType: false,
                        processData: false,
                        success: function(res) {
                            $('input[name="avatar_id"]').val(res?.data?.id);
                            $('.image-demo').attr("src", res?.url);
                            $('.avatar-selector .img-box').attr("style",
                                `background-image: url('${res?.url}')`);
                            $("#profile-pic-gallery-modal").modal("hide");
                        },
                        error: function(e) {
                            console.log(e);
                        }
                    });
                });
        });
    </script>

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

        @if ($dataUser->map_lat != '' && $dataUser->map_lng != '')
            jQuery(function($) {
                renderMapBox({
                    map_lat: {{ $dataUser->map_lat }},
                    map_lng: {{ $dataUser->map_lng }},
                    map_zoom: {{ $dataUser->map_zoom }}
                });
            })
        @endif
    </script>

    <script>
        function initGoogleAutoCompleteField() {
            var input = document.getElementById('addressLineOne');
            var options = {
                componentRestrictions: {country: ["us", "ca"]}
            };
            var autocomplete = new google.maps.places.Autocomplete(input, options);
            google.maps.event.addListener(autocomplete, 'place_changed', function() {
                var place = autocomplete.getPlace();
                document.getElementById("city").value = '';
                document.getElementById("state").value = '';
                document.getElementById("country").value = '';
                // document.getElementById("addressLineTwo").value = '';
                document.getElementById("zipCode").value = '';
                for (let addressComponent of place.address_components) {
                    if (addressComponent['types'].includes("locality")) {
                        document.getElementById("city").value = addressComponent.long_name;
                    } else if (addressComponent['types'].includes("administrative_area_level_1")) {
                        document.getElementById("state").value = addressComponent.short_name;
                    } else if (addressComponent['types'].includes("administrative_area_level_2")) {
                        // document.getElementById("addressLineTwo").value = addressComponent.long_name;
                    } else if (addressComponent['types'].includes("country")) {
                        $("#country").val(addressComponent.short_name);
                    } else if (addressComponent['types'].includes("postal_code")) {
                        $("#zipCode").val(addressComponent.short_name);
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

        $(document).on("click", ".avatar-selector .uploadFromFiles", function() {
            var mainBox = $(this).closest(".avatar-selector-main");
            // console.log(mainBox);
            // console.log(mainBox.find(".btn-file"));
            // console.log(mainBox.find(".btn-file input"));
            mainBox.find(".btn-file input").click();
        });

        $(document).on("bravo-file-update-success", ".avatar-selector-main :file", function(event, data) {
            let url = data.url;
            var mainBox = $(this).closest(".avatar-selector-main");
            mainBox.find('.img-box').attr('style', 'background-image: url("' + url + '");');
        });
    </script>
@endsection
