@extends('admin.layouts.app')
@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between mb20">
            <h1 class="title-bar">{{ __('Listing Image Generator') }}</h1>
            <div class="d-flex" style="gap: 15px;">
                <a class="btn btn-success" href="{{ route('reGenerateAllImages') }}">Re-Generate All Images</a>
                <a class="btn btn-primary" href="{{ route('loadImages') }}">Load Images</a>
            </div>
        </div>
        @include('admin.message')

        <div class="row m-0">
            <div class="panel col-5"> 
                <form action="{{ route('space.admin.imageGenerator') }}?method=1" method="post" id="citysearch">
                    @csrf
                    <div class="panel-title mb-2 text-center"><strong>{{ __('Search for Listings') }}</strong></div>
                    @foreach ($attributes as $attribute)
                        @if ($attribute->slug == 'space-type')
                            <label class="control-label h6">Step 1: Select Category (Must select at least 1)<span
                                    class="text-danger">*</span></label>

                            <div class="terms-scrollable terms-scrollable-ux" style="max-height: max-content;height: 100%;">
                                @foreach ($attribute->terms as $term)
                                    <label class="term-item">
                                        <input @if (!empty($selected_terms) and $selected_terms->contains($term->id)) checked @endif type="checkbox"
                                            name="terms[]" value="{{ $term->id }}">
                                        <span class="term-name">{{ $term->name }}</span>
                                    </label>
                                @endforeach
                            </div><button class="btn btn-secondary btn-sm pt-0 pb-0 float-right" id="selectNone">Select
                                None</button>
                            <button class="btn btn-secondary btn-sm pt-0 pb-0 mr-1 float-right" id="selectAll">Select
                                All</button>
                        @endif
                    @endforeach
                    <br />
                    <div class="form-group">
                        <label class="control-label mt-2 h6">Step 2: Select City <span
                                class="text-danger">*</span></label><input type="text" id="citySearch"
                            placeholder="{{ __('Search by City...') }}" class="bravo_searchbox form-control"
                            autocomplete="off" onkeydown="return event.key !== 'Enter';">
                        <input type="hidden" id="selected_city" name="selected_city">
                        <input type="hidden" id="selected_country" name="selected_country">
                    </div>


                    <!--<div class="form-group switch-toggle">
                                                                                                            <label class="switch">

                                                                                                                <input type="checkbox" id="replace" name="replace">
                                                                                                                <span class="slider round"></span>
                                                                                                            </label>
                                                                                                            <span for="replace">{{ __('Replace Existing Images') }}</span>
                                                                                                        </div>-->

                    <div class="text-right mb-2">
                        <button class="btn btn-primary" id="submitButton"> {{ __('Next') }}</button>
                    </div>
                </form>
            </div>
            <div class="col-2 d-flex align-items-center justify-content-center">
                <span class="bg-warning rounded-circle text-white p-4 h3 align-items-center">OR</span>
            </div>
            <div class="col-5 d-flex align-items-center">
                <div class="panel col-12 m-0 p-0">
                    <div class="panel-title mb-2 text-center"><strong>{{ __('Search for Specific Listing') }}</strong>
                    </div>
                    <div class="panel-body">

                        <form action="{{ route('space.admin.imageGenerator') }}?method=2" method="post">
                            @csrf
                            <div class="form-group">
                                <input type="text" required placeholder="{{ __('Name of the space') }}" name="title"
                                    class="form-control">
                            </div>
                            <!-- <div class="form-group switch-toggle">
                                                                                                                    <label class="switch">

                                                                                                                        <input type="checkbox" id="replace" name="replace">
                                                                                                                        <span class="slider round"></span>
                                                                                                                    </label>
                                                                                                                    <span for="replace">{{ __('Replace Existing Images') }}</span>
                                                                                                                </div> -->
                            <div class="text-right mb-2">
                                <button class="btn btn-primary" type="submit"> {{ __('Next') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="toast-container position-fixed top-2 right-0 p-3" style="z-index: 5;left: 50%; top: 50%;">
        <!-- Toasts will be appended here -->
    </div>
@endsection

@section('script.body')
    {!! App\Helpers\MapEngine::scripts() !!}
    <script></script>

    <script type="text/javascript" src="{{ url('module/core/js/map-engine.js?_ver=' . config('app.version')) }}"></script>


    <script>
        $(document).ready(function() {


            $('#submitButton').click(function(e) {
                console.log("Here")
                e.preventDefault();
                // Validate selected terms
                var selectedTerms = $('input[name="terms[]"]:checked').length;

                if (selectedTerms === 0) {
                    showToast('Please Select a Category.');
                    return;
                }

                // Validate selected city
                var selectedCity = $('#selected_city').val();
                if (!selectedCity) {
                    showToast('Please select a city.');
                    return;
                }
                // If validation passes, submit the form
                $('#citysearch').submit();
            });

            function showToast(message) {
                var toast = $(
                    '<div class="toast" role="alert" aria-live="assertive" aria-atomic="true" data-delay="3000">\
                                <div class="toast-header bg-danger">\
                                    <strong class="mr-auto text-white ">Validation Error</strong>\
                                    <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">\
                                        <span aria-hidden="true">&times;</span>\
                                    </button>\
                                </div>\
                                <div class="toast-body">' +
                    message +
                    '</div>\
                                                                                                                                                                        </div>'
                );

                $('.toast-container').append(toast);
                toast.toast('show');

            }


            $('#selectAll').click(function(e) {
                e.preventDefault();
                $('input[name="terms[]"]').prop('checked', true);
            });
            $('#selectNone').click(function(e) {
                e.preventDefault();
                $('input[name="terms[]"]').prop('checked', false);
            });
        });

        function initGoogleAutoCompleteField() {
            var input = document.getElementById('citySearch');
            var options = {
                componentRestrictions: {
                    country: ["us", "ca"]
                }
            };
            var autocomplete = new google.maps.places.Autocomplete(input, options);
            google.maps.event.addListener(autocomplete, 'place_changed', function() {
                var place = autocomplete.getPlace();
                for (let addressComponent of place.address_components) {
                    if (addressComponent['types'].includes("locality"))
                        $("#selected_city").val(addressComponent.short_name);
                    else if (addressComponent['types'].includes("country"))
                        $("#selected_country").val(addressComponent.short_name);
                }

            });
        }
        $(function() {
            initGoogleAutoCompleteField();
        });
    </script>
@endsection
