<?php
$params = [
    'start' => date('m-d-Y', strtotime($booking->start_date)),
    'start_hour' => date('H:i', strtotime($booking->start_date)),
    'end' => date('m-d-Y', strtotime($booking->end_date)),
    'to_hour' => date('H:i', strtotime($booking->end_date)),
    'guests' => $booking->total_guests
];
$backUrl = url('m/space-details').'/'.$service->id."?".http_build_query($params);
?>
@extends('layouts.common_home')
@section('head')
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
    <link rel="stylesheet" href="{{ url('pwa') }}/assets/css/style.css">
    <link href="{{ asset('module/booking/css/checkout.css?_ver=' . config('app.version')) }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ url('pwa') }}/assets/css/main.css?v={{ time() }}">
    <link rel="stylesheet" href="{{ url('pwa') }}/assets/css/checkout-main.css?v={{ time() }}">
    <style>
        .bravo-booking-page * {
            font-family: 'Montserrat', sans-serif !important;
        }
        .form-title{
            font-weight: 700 !important;
        }
    </style>
@endsection
@section('content')
    <div class="appHeader br-0">
        <!-- @include('pwa.layout._top-bar') -->
        <div class="left">
            <a href="{{ route('pwa.get.home') }}" class="headerButton goBack">
                <img class="back_logo" src="{{ url('images/logo_myoffice.png') }}">
            </a>
        </div>
        <div class="pageTitle">
            <button type="button" class="btn btn-text-primary rounded shadowed" data-bs-toggle="modal"
                data-bs-target="#actionSheetForm5">Booking Details</button>
        </div>
        <div class="right pt-8">
            <a href="{{ $backUrl }}" class="edit-booking-top-pwa">
                <ion-icon name="arrow-back-outline"></ion-icon>
            </a>
        </div>
    </div>

    <div class="layout1">
        <div class="bravo-booking-page padding-content">
            <div class="container" id="bravo-checkout-page-wrapper">
                <div id="bravo-checkout-page">
                    <div class="row">
                        <div class="col-md-8">
                            <h3 class="form-title text-uppercase">{{ __('Guest Details') }}</h3>
                            <div class="booking-form">
                                @include ($service->checkout_form_file ?? 'Booking::frontend/booking/checkout-form')

                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="booking-detail booking-form">
                                @include ($service->checkout_booking_detail_file ?? '')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('pwa.layout._app_bottom')
@endsection
@section('footer')
    <script src="{{ asset('module/booking/js/checkout.js') }}?v={{ time() }}"></script>
    <script type="text/javascript">
        function initGoogleAutoCompleteField() {
            var input = document.getElementById('addressLineOne');
            var options = {
                componentRestrictions: {country: ["us", "ca"]}
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
                        window.location.href = data.redirect
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
            $(document).on("click", ".bravo_apply_coupon",function() {
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
                            window.webAlerts.push({
                                type: "error",
                                message: res.message
                            });
                        }
                    }
                });
            });
            $(document).on("click", ".bravo_remove_coupon",function(e) {
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
@endsection
