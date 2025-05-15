@extends('layouts.common_home')
@section('head')
    <link href="{{ asset('module/booking/css/checkout.css?_ver=' . config('app.version')) }}" rel="stylesheet">
    <style>
        .bravo-booking-page *:not(i) {
            font-family: 'Montserrat', sans-serif !important;
        }

        .form-title {
            font-weight: 700 !important;
            border-bottom: none !important;
            margin-bottom: 0px !important;
        }

        .booking-review-title {
            font-weight: 700 !important;
        }

        .booking-review-content {
            background-color: #FFEFC0;
        }

        .section-booking-date {
            background-color: #EEE7D6;
        }

        .quantity-section {
            background-color: white;
        }

        .service-name-booking-text {
            font-weight: 500;
        }

        .booking-form-section {
            border: 2px solid #dae1e7;
        }

        .booking-review .booking-review-content .review-list li {
            justify-content: left !important;
        }

        .booking-review .booking-review-content .review-list li .val {
            margin-left: 5px !important;
            color: black !important;
            font-weight: 500;
        }

        .service-name {
            font-weight: 700 !important;
            font-family: 'Roboto' !important;
        }

        .booking-review .booking-review-content .review-section {
            @media(max-width: 575.98px) {
                padding: 10px 15px !important;
            }
        }

        .booking-detail-wrapper {
            @media(min-width: 575.98px) {
                height: 95%;
                background-color: #FFEFC0;
                border: 1px solid #d7dce3;
                -webkit-box-shadow: 0 1px 2px 0 rgba(0, 0, 0, .1);
                box-shadow: 0 1px 2px 0 rgba(0, 0, 0, .1);
                border-radius: 3px;
            }
        }

        .booking-review .booking-review-content {
            @media(min-width: 575.98px) {
                border: none;
                -webkit-box-shadow: none;
                box-shadow: none;
                border-radius: 0px;
            }
        }
    </style>
@endsection
@section('content')
    <div class="layout1">
        <div class="bravo-booking-page padding-content">
            <div class="container" id="bravo-checkout-page-wrapper">
                <div id="bravo-checkout-page">
                    <div class="row">
                        <div class="col-md-8">
                            <h3 class="form-title text-uppercase">{{ __('Guest Details') }}</h3>
                            <div class="timer-box" id="timerBoxMain" style="display: none;">
                                Booking should be completed in next&nbsp;<div id="timerCheckout" class="timer"
                                    style="font-weight: 600;"
                                    data-seconds="<?= \App\Helpers\CodeHelper::secondsLeftInBooking($booking) ?>">-
                                </div>. Otherwise your booking will be cancelled.
                            </div>
                            <div class="booking-form booking-form-section p-3">
                                @include ($service->checkout_form_file ?? 'Booking::frontend/booking/checkout-form')

                            </div>
                        </div>
                        <div class="col-md-4">
                            <h3 class="form-title text-uppercase">{{ __('Booking Details') }}</h3>
                            <div class=booking-detail-wrapper>
                                <div class="booking-detail booking-form">
                                    @include ($service->checkout_booking_detail_file ?? '')
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="availabilityCalendar" class="modal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Availability Calendar</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p style="text-align: center;padding: 15px;font-size: 17px;font-weight: 600;">Unavailable times are
                        marked in calendar, rest time can be booked.</p>
                    <div id='availabilityTimeCalendar'></div>
                </div>
            </div>
        </div>
    </div>

    <div id="invalidSpaceCoupon" class="modal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Invalid Coupon</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p style="text-align: center;padding: 15px;font-size: 17px;font-weight: 600;">This Code is only
                        applicable to Spaces owned by the Host who issued this code. <a id="targetListingBySpace" href="" target="_blank">Click Here</a> to view the Spaces where you
                        can use this code.</p>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('footer')
    <script src="{{ asset('module/booking/js/checkout.js') }}?v={{ time() }}"></script>
    <script type="text/javascript">
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
                // console.log(place);
                document.querySelector("#form-checkout #city").value = '';
                document.querySelector("#form-checkout #state").value = '';
                document.querySelector("#form-checkout #country").value = '';
                document.querySelector("#form-checkout #addressLineTwo").value = '';
                document.querySelector("#form-checkout #zipCode").value = '';
                console.log(place.address_components);
                for (let addressComponent of place.address_components) {
                    if (addressComponent['types'].includes("locality")) {
                        document.querySelector("#form-checkout #city").value = addressComponent.long_name;
                    } else if (addressComponent['types'].includes("administrative_area_level_1")) {
                        document.querySelector("#form-checkout #state").value = addressComponent.short_name;
                    } else if (addressComponent['types'].includes("administrative_area_level_2")) {
                        document.querySelector("#form-checkout #addressLineTwo").value = addressComponent.long_name;
                    } else if (addressComponent['types'].includes("country")) {
                        $("#form-checkout #country").val(addressComponent.short_name);
                    } else if (addressComponent['types'].includes("postal_code")) {
                        $("#form-checkout #zipCode").val(addressComponent.short_name);
                    }
                }
                document.querySelector("#form-checkout #addressLineOne").value = place.name;
            });
        }

        $(function() {
            initGoogleAutoCompleteField();
        });

        jQuery(function() {
            $.ajax({
                'url': bookingCore.url +
                    '{{ $is_api ? '/api' : '' }}/booking/{{ $booking->code }}/check-status',
                'cache': false,
                'type': 'GET',
                success: function(data) {
                    if (data.redirect !== undefined && data.redirect) {
                        window.location.href = data.redirect;
                    }
                }
            });
        })

        $('.deposit_amount').on('change', function() {
            checkPaynow();
        });

        $('input[type=radio][name=how_to_pay]').on('change', function() {
            checkPaynow();
        });

        function checkPaynow() {
            var credit_input = $('.deposit_amount').val();
            var orderId = $('#orderId').val();
            var credit_input = $('.deposit_amount').val();
            var how_to_pay = $("input[name=how_to_pay]:checked").val();
            var convert_to_money = credit_input * {{ setting_item('wallet_credit_exchange_rate', 1) }};

            if (how_to_pay == 'full') {
                var pay_now_need_pay = parseFloat({{ floatval($booking->total) }}) - convert_to_money;
            } else {
                var pay_now_need_pay = parseFloat(
                        {{ floatval($booking->deposit == null ? $booking->total : $booking->deposit) }}) -
                    convert_to_money;
            }

            if (pay_now_need_pay < 0) {
                pay_now_need_pay = 0;
            }

            $('.convert_pay_now').html(bravo_format_money(pay_now_need_pay));
            $('.convert_deposit_amount').html(bravo_format_money(convert_to_money));
            var formatedprice = bravo_format_money(pay_now_need_pay);
            formatedprice = formatedprice.replace("$", "");

            $.ajax({
                url: '{{ route('gateway.update.space') }}',
                data: {
                    "amount": formatedprice,
                    "orderId": orderId
                },
                type: 'GET',
                success: function(data) {
                    var json = $.parseJSON(data);
                    $('#amount').val(json.amount);
                    $('#txnToken').val(json.txnToken);
                    //console.log(data);
                }
            });
        }

        function reloadPriceSummary() {
            // window.location.reload();
            $("#checkout-booking-review").load(`${window.location.href} #checkout-booking-review-inner`);
            $("#accordionExampleWrapper").load(`${window.location.href} #accordionExample`);
        }

        jQuery(function() {
            $(document).on("click", ".bravo_apply_coupon", function() {
                var parent = $(this).closest('.section-coupon-form');
                parent.find(".group-form .fa-spin").removeClass("d-none");
                parent.find(".message").html('');
                $.ajax({
                    'url': bookingCore.url + '/booking/{{ $booking->code }}/apply-coupon',
                    'data': parent.find('input,textarea,select').serialize(),
                    'cache': false,
                    'method': "post",
                    success: function(res) {
                        parent.find(".group-form .fa-spin").addClass("d-none");
                        if (res.reload !== undefined) {
                            reloadPriceSummary();
                        }
                        if (res.message && res.status === 1) {
                            window.webAlerts.push({
                                type: "success",
                                message: res.message
                            });
                        }
                        if (res.message && res.status === 0) {
                            if (res?.type && res?.type === "invalid_space") {  
                                $("#targetListingBySpace").attr("href", "{{url('/user/p/profile')}}/"+res?.vendor);
                                $("#invalidSpaceCoupon").modal("show");
                            } else {
                                window.webAlerts.push({
                                    type: "error",
                                    message: res.message
                                });
                            }
                        }
                    }
                });
            });
            $(document).on("click", ".bravo_remove_coupon", function(e) {
                e.preventDefault();
                var parent = $(this).closest('.section-coupon-form');
                var parentItem = $(this).closest('.item');
                parentItem.find(".fa-spin").removeClass("d-none");
                $.ajax({
                    'url': bookingCore.url + '/booking/{{ $booking->code }}/remove-coupon',
                    'data': {
                        coupon_code: $(this).attr('data-code')
                    },
                    'cache': false,
                    'method': "post",
                    success: function(res) {
                        parentItem.find(".fa-spin").addClass("d-none");
                        if (res.reload !== undefined) {
                            reloadPriceSummary();
                        }
                        if (res.message && res.status === 1) {
                            window.webAlerts.push({
                                type: "success",
                                message: "Coupon code has been removed"
                            });
                        }
                        if (res.message && res.status === 0) {
                            window.webAlerts.push({
                                type: "error",
                                message: res.message
                            });
                        }
                    }
                });
            });
        })
    </script>
    <script>
        $(document).on("click", "#openCalendar", function() {
            showAvailabilityCalendarModal();
        });

        function showAvailabilityCalendarModal() {
            $("#availabilityCalendar").modal("show");
            availabilityTimeCalendar = new FullCalendar.Calendar(document.getElementById('availabilityTimeCalendar'), {
                eventSources: [{
                    url: '{{ route('space.vendor.availability.availableDates') }}?id={{ $booking->vendor_id }}',
                }],
                headerToolbar: {
                    left: 'prevYear,prev,next,nextYear today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                initialView: 'dayGridMonth',
                dayMaxEvents: true,
                navLinks: true,
                eventClick: function(eventInfo) {
                    let eventId = eventInfo.event.id;
                },
                eventContent: function(arg) {
                return {
                    html: `<div class="fc-event-title">${arg.event.title.replace(/<\/br>/g, '<br>')}</div>`
                };
            }
            });
            availabilityTimeCalendar.render();
        }
    </script>
    <script>
        function formatTime(seconds) {
            if (typeof seconds !== 'number' || isNaN(seconds)) {
                return "Invalid input";
            }

            const minutes = Math.floor(seconds / 60);
            const remainingSeconds = seconds % 60;

            const formattedTime = `${String(minutes).padStart(2, '0')}:${String(remainingSeconds).padStart(2, '0')}`;

            return formattedTime;
        }

        $(document).ready(function() {
            let seconds = $("#timerCheckout").attr("data-seconds");
            seconds = seconds * 1;
            setInterval(() => {
                seconds--;
                if (seconds < 0) {
                    seconds = 0;
                    window.location.replace(bookingCore.url);
                }
                $("#timerBoxMain").show();
                $("#timerCheckout").html(formatTime(seconds));
            }, 1000);
        });
    </script>
@endsection
