@extends('pwa.layout.index')
@section('content')
    <div id="loader">
        <img src="{{ url('pwa') }}/assets/img/logo_instagram.png" alt="icon" class="loading-icon">
    </div>
    <!-- * loader -->


    <!-- App Capsule -->
    <div id="appCapsule" class="full-height">


        <!-- App Header -->
        <div class="appHeader">
            <div class="left">
                <a href="#" class="headerButton goBack">
                    <ion-icon name="chevron-back-outline"></ion-icon>
                </a>
            </div>
            <div class="pageTitle pwaTitlePage">
                <button type="button" class="btn btn-text-primary rounded shadowed" data-bs-toggle="modal"
                    data-bs-target="#booking_secah_form">My Bookings</button>
                <div class="dropdown filter-dropdown-pwa">
                    <a class="dropdown-toggle" href="javascript:;" id="dropdownMenuButton1" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        <i class="las la-bars"></i>
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                        <li><a class="dropdown-item triggerFilterD" data-val="name" href="javascript:;">Sort By Name</a>
                        </li>
                        <li><a class="dropdown-item triggerFilterD" data-val="oldest" href="javascript:;">Sort By Date
                                (Oldest)</a></li>
                        <li><a class="dropdown-item triggerFilterD" data-val="newest" href="javascript:;">Sort By Date
                                (Newest)</a></li>
                        <li><a class="dropdown-item triggerFilterD" data-val="costly" href="javascript:;">Sort by Amount
                                (Largest)</a></li>
                        <li><a class="dropdown-item triggerFilterD" data-val="cheap" href="javascript:;">Sort by Amount
                                (Smallest)</a></li>
                    </ul>
                </div>
            </div>
            <div class="right pt-8">
            </div>
        </div>
        <!-- * App Header -->



        <div class="section2 mt-2">

            <div class="transactions p-2">
                <!-- item -->

                @foreach ($booking_list as $row)
                    @include('pwa.common.single_booking_list_wise', ['row' => $row, 'publicView' => false])

                    <hr class="solid">
                @endforeach
                <!-- item -->
                <!-- <div class="section mt-2 mb-2 text-center">
                                          <a href="#" id="more" class="f-12 btn btn-more btn-lg rounded">Load More Items</a>
                                       </div>
                                       <div class="text-center mt-2 mb-2 p-3">
                                          <button class="btn btn-primary btn-block btn-lg" type="button">
                                          MAKE CHANGES
                                          </button>
                                       </div> -->
            </div>

        </div>




        <!-- Booking search form -->
        <div class="modal fade action-sheet" id="booking_secah_form" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Search</h5>
                    </div>
                    <div class="modal-body bg-g">
                        <div class="action-sheet-content form-action-sheets">
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label>From</label>
                                        <input type="date" id="from_date"
                                            value="{{ $searched_data['searched_from_date'] }}">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label>To</label>
                                        <input type="date" id="to_date"
                                            value="{{ $searched_data['searched_to_date'] }}">
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="sort" id="sortOption" value="">
                            <div class="form-group">
                                <label>Space Name</label>
                                <input type="text" id="space_name" value="{{ $searched_data['searched_space_name'] }}">
                            </div>
                            <div class="form-group">
                                <label>City</label>
                                <input type="text" id="city" value="{{ $searched_data['searched_city'] }}">
                            </div>
                            <div class="form-group">
                                <label>Listing ID</label>
                                <input type="text" id="list_id" value="{{ $searched_data['searched_list_id'] }}">
                            </div>
                            <div class="form-group">
                                <label>Amount From</label>
                                <input type="text" id="amount_from" value="{{ $searched_data['searched_amount_from'] }}">
                            </div>
                            <div class="form-group">
                                <label>Amount To</label>
                                <input type="text" id="amount_to" value="{{ $searched_data['searched_amount_to'] }}">
                            </div>
                            <button type="reset" id="resetBookingFormBtn"
                                class="btn btn-dark btn-lg btn-block mt-2 me-1 mb-2">Reset</button>
                            <button type="button"
                                class="btn btn-primary btn-lg btn-block me-1 mb-2 apply_booking_search">Apply</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </div>
    <!-- * App Capsule -->
@endsection

@section('js')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src='https://maps.googleapis.com/maps/api/js?key=AIzaSyCRu_qlT0HNjPcs45NXXiOSMd3btAUduSc&libraries=places'>
    </script>

    <script type="text/javascript">
        $(document).ready(function() {
            var input = document.getElementById('city');
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

            });
        });
    </script>

    <script type="text/javascript">
        $(document).on('click', '.apply_booking_search', function() {
            var from_date = $('#from_date').val();
            var to_date = $('#to_date').val();
            var space_name = $('#space_name').val();
            var city = $('#city').val()
            var list_id = $('#list_id').val()
            var amount_from = $('#amount_from').val()
            var amount_to = $('#amount_to').val()
            var sort = $('#sortOption').val()

            var baseUrl = "{{ url('/') }}";
            // Manually construct the route URL
            var routeUrl = baseUrl + "/m/booking-list" + "?from_date=" + from_date + "&to_date=" +
                to_date + "&space_name=" + space_name + "&city=" + city + "&list_id=" + list_id + "&amount_from=" +
                amount_from +
                "&amount_to=" + amount_to + "&sort=" + sort;

            // Redirect to the Laravel route
            window.location.href = routeUrl;
        });

        $(document).on("click", "#resetBookingFormBtn", function() {
            $('#from_date').val("");
            $('#to_date').val("");
            $('#space_name').val("");
            $('#city').val("");
            $('#list_id').val("");
            $('#amount_from').val("");
            $('#amount_to').val("");
            $('#sortOption').val("");
        });

        $(document).on("click", ".triggerFilterD", function() {
            $("#sortOption").val($(this).attr("data-val"));
            $(".apply_booking_search").click();
        });
    </script>
@endsection
