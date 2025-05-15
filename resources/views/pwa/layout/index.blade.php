<?php
if (isset($_GET['currentLatValue']) && isset($_GET['currentLongName'])) {
    Session::put('lat', $_GET['currentLatValue']);
    Session::put('lng', $_GET['currentLongName']);
    Session::save();
}
?> 
<!doctype html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, viewport-fit=cover" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="theme-color" content="#000000">
    <title>MyOffice - PWA</title>
    <meta name="description" content="">
    <meta name="keywords" content="MyOffice" />
    
    {{-- <link rel="icon" type="image/png" href="{{ url('pwa') }}/assets/img/logo_instagram.png" sizes="32x32">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ url('pwa') }}/assets/img/icon/192x192.png"> --}}

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.0/css/all.min.css" rel="stylesheet">

    <link rel="stylesheet"
        href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">


    <link href="{{ url('') }}/libs/icofont/icofont.min.css" rel="stylesheet">

</head>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Swiper/4.5.0/css/swiper.min.css">

<link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">


<link rel="stylesheet" href="{{ url('pwa') }}/assets/css/style.css">
<link rel="manifest" href="{{ url('pwa') }}/__manifest.json?v=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">

<link rel="stylesheet" href="{{ asset('toastr/toastr.min.css') }}">

<link rel="stylesheet" href="{{ url('pwa') }}/assets/css/main.css?v={{ time() }}">


<link rel="manifest" href="{{asset('manifest.json')}}" />

<link rel="icon" type="image/x-icon" href="{{url('images/icons/icon-512x512.png')}}">

<!-- ios support -->
<link rel="apple-touch-icon" href="{{url('images/icons/icon-72x72.png')}}" />
<link rel="apple-touch-icon" href="{{url('images/icons/icon-96x96.png')}}" />
<link rel="apple-touch-icon" href="{{url('images/icons/icon-128x128.png')}}" />
<link rel="apple-touch-icon" href="{{url('images/icons/icon-144x144.png')}}" />
<link rel="apple-touch-icon" href="{{url('images/icons/icon-152x152.png')}}" />
<link rel="apple-touch-icon" href="{{url('images/icons/icon-192x192.png')}}" />
<link rel="apple-touch-icon" href="{{url('images/icons/icon-384x384.png')}}" />
<link rel="apple-touch-icon" href="{{url('images/icons/icon-512x512.png')}}" />

<meta name="apple-mobile-web-app-status-bar" content="#FFB403" />
<meta name="theme-color" content="#FFB403" />

<script>
    window.BASE_URL = "{{ url('') }}";
</script>


</head>

<body>



    <script>
        var bookingCore = {
            url: '{{ url(app_get_locale()) }}',
            url_root: '{{ url('') }}',
            booking_decimals: {{ (int) get_current_currency('currency_no_decimal', 2) }},
            thousand_separator: '{{ get_current_currency('currency_thousand') }}',
            decimal_separator: '{{ get_current_currency('currency_decimal') }}',
            currency_position: '{{ get_current_currency('currency_format') }}',
            currency_symbol: '{{ currency_symbol() }}',
            currency_rate: '{{ get_current_currency('rate', 1) }}',
            date_format: '{{ get_moment_date_format() }}',
            map_provider: '{{ setting_item('map_provider') }}',
            map_gmap_key: '{{ setting_item('map_gmap_key') }}',
            map_options: {
                map_lat_default: '{{ setting_item('map_lat_default') }}',
                map_lng_default: '{{ setting_item('map_lng_default') }}',
                map_clustering: '{{ setting_item('map_clustering') }}',
                map_fit_bounds: '{{ setting_item('map_fit_bounds') }}',
            },
            routes: {
                login: '{{ route('auth.login') }}',
                register: '{{ route('auth.register') }}',
            },
            currentUser: {{ (int) Auth::id() }},
            isAdmin: {{ is_admin() ? 1 : 0 }},
            rtl: {{ setting_item_with_lang('enable_rtl') ? '1' : '0' }},
            markAsRead: '{{ route('core.notification.markAsRead') }}',
            markAllAsRead: '{{ route('core.notification.markAllAsRead') }}',
            loadNotify: '{{ route('core.notification.loadNotify') }}',
            pusher_api_key: '{{ setting_item('pusher_api_key') }}',
            pusher_cluster: '{{ setting_item('pusher_cluster') }}',
        };
        var i18n = {
            warning: "{{ __('Warning') }}",
            success: "{{ __('Success') }}",
            confirm_delete: "{{ __('Do you want to delete?') }}",
            confirm: "{{ __('Confirm') }}",
            cancel: "{{ __('Cancel') }}",
        };
        var daterangepickerLocale = {
            "applyLabel": "{{ __('Apply') }}",
            "cancelLabel": "{{ __('Cancel') }}",
            "fromLabel": "{{ __('From') }}",
            "toLabel": "{{ __('To') }}",
            "customRangeLabel": "{{ __('Custom') }}",
            "weekLabel": "{{ __('W') }}",
            "first_day_of_week": {{ setting_item('site_first_day_of_the_weekin_calendar', '1') }},
            "daysOfWeek": [
                "{{ __('Su') }}",
                "{{ __('Mo') }}",
                "{{ __('Tu') }}",
                "{{ __('We') }}",
                "{{ __('Th') }}",
                "{{ __('Fr') }}",
                "{{ __('Sa') }}"
            ],
            "monthNames": [
                "{{ __('January') }}",
                "{{ __('February') }}",
                "{{ __('March') }}",
                "{{ __('April') }}",
                "{{ __('May') }}",
                "{{ __('June') }}",
                "{{ __('July') }}",
                "{{ __('August') }}",
                "{{ __('September') }}",
                "{{ __('October') }}",
                "{{ __('November') }}",
                "{{ __('December') }}"
            ],
        };

        var image_editer = {
            language: '{{ app()->getLocale() }}',
            translations: {
                {{ app()->getLocale() }}: {
                    'header.image_editor_title': '{{ __('Image Editor') }}',
                    'header.toggle_fullscreen': '{{ __('Toggle fullscreen') }}',
                    'header.close': '{{ __('Close') }}',
                    'header.close_modal': '{{ __('Close window') }}',
                    'toolbar.download': '{{ __('Save Change') }}',
                    'toolbar.save': '{{ __('Save') }}',
                    'toolbar.apply': '{{ __('Apply') }}',
                    'toolbar.saveAsNewImage': '{{ __('Save As New Image') }}',
                    'toolbar.cancel': '{{ __('Cancel') }}',
                    'toolbar.go_back': '{{ __('Go Back') }}',
                    'toolbar.adjust': '{{ __('Adjust') }}',
                    'toolbar.effects': '{{ __('Effects') }}',
                    'toolbar.filters': '{{ __('Filters') }}',
                    'toolbar.orientation': '{{ __('Orientation') }}',
                    'toolbar.crop': '{{ __('Crop') }}',
                    'toolbar.resize': '{{ __('Resize') }}',
                    'toolbar.watermark': '{{ __('Watermark') }}',
                    'toolbar.focus_point': '{{ __('Focus point') }}',
                    'toolbar.shapes': '{{ __('Shapes') }}',
                    'toolbar.image': '{{ __('Image') }}',
                    'toolbar.text': '{{ __('Text') }}',
                    'adjust.brightness': '{{ __('Brightness') }}',
                    'adjust.contrast': '{{ __('Contrast') }}',
                    'adjust.exposure': '{{ __('Exposure') }}',
                    'adjust.saturation': '{{ __('Saturation') }}',
                    'orientation.rotate_l': '{{ __('Rotate Left') }}',
                    'orientation.rotate_r': '{{ __('Rotate Right') }}',
                    'orientation.flip_h': '{{ __('Flip Horizontally') }}',
                    'orientation.flip_v': '{{ __('Flip Vertically') }}',
                    'pre_resize.title': '{{ __('Would you like to reduce resolution before editing the image?') }}',
                    'pre_resize.keep_original_resolution': '{{ __('Keep original resolution') }}',
                    'pre_resize.resize_n_continue': '{{ __('Resize & Continue') }}',
                    'footer.reset': '{{ __('Reset') }}',
                    'footer.undo': '{{ __('Undo') }}',
                    'footer.redo': '{{ __('Redo') }}',
                    'spinner.label': '{{ __('Processing...') }}',
                    'warning.too_big_resolution': '{{ __('The resolution of the image is too big for the web. It can cause problems with Image Editor performance.') }}',
                    'common.x': '{{ __('x') }}',
                    'common.y': '{{ __('y') }}',
                    'common.width': '{{ __('width') }}',
                    'common.height': '{{ __('height') }}',
                    'common.custom': '{{ __('custom') }}',
                    'common.original': '{{ __('original') }}',
                    'common.square': '{{ __('square') }}',
                    'common.opacity': '{{ __('Opacity') }}',
                    'common.apply_watermark': '{{ __('Apply watermark') }}',
                    'common.url': '{{ __('URL') }}',
                    'common.upload': '{{ __('Upload') }}',
                    'common.gallery': '{{ __('Gallery') }}',
                    'common.text': '{{ __('Text') }}',
                }
            }
        };
    </script>


    @yield('content')


    <div id="myLoader">
        <div class="my-loader"></div>
    </div>

    @include('pwa.layout._app_bottom')

    <!-- ========= JS Files =========  -->
    <!-- Bootstrap -->

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"
        integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8=" crossorigin="anonymous"></script>



    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>

    <script src="{{ url('pwa') }}/main.js?v={{ time() }}"></script>


    <script src="{{ url('pwa') }}/assets/js/lib/bootstrap.bundle.min.js"></script>
    <!-- Ionicons -->
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
    <!-- Splide -->
    <script src="{{ url('pwa') }}/assets/js/plugins/splide/splide.min.js"></script>
    <!-- Base Js File -->
    <script src="{{ url('pwa') }}/assets/js/base.js?v=1.1"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.slim.min.js"
        integrity="sha256-u7e5khyithlIdTpu22PHhENmPcRdFiHRjhAuHcs05RI=" crossorigin="anonymous"></script>
    <script>
        $(".toggle-password").click(function() {

            // $(this).toggleClass("fa-eye fa-eye-slash");
            var input = $($(this).attr("toggle"));
            if (input.attr("type") == "password") {
                input.attr("type", "text");
            } else {
                input.attr("type", "password");
            }
        });
    </script>




    <!-- Add to fav -->
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>

    <script type="text/javascript">
        $(document).on('click', '.add-to-fav', function() {
            let spaceId = $(this).attr('space_id');
            // alert(spaceId);
            let _token = $('meta[name="csrf-token"]').attr('content');
            let $this = $(this);
            $.ajax({
                url: '{{ url(app_get_locale()) }}' + '/space/add-to-favourite',
                data: {
                    space_id: spaceId,
                    _token: _token
                },
                dataType: 'json',
                type: 'POST',
                beforeSend: function() {
                    $this.addClass("loading");
                },
                success: function(res) {
                    $("#msg-success").html("Added To Favourite");
                    console.log('this', $(this))
                    $this.toggleClass('active')
                },
                error: function(e) {
                    if (e.status === 401) {
                        $('#login').modal('show');
                    }
                }
            })

        })
    </script>


    <script>
        $(document).on("submit", ".supportAjaxForm", function(e) {
            e.preventDefault();
            var obj = $(this);
            var submitBtn = obj.find('button[type="submit"]');
            submitBtn.html("Sending...");

            var formData = {
                category: $("#supportSubject").val(),
                issue: $("#supportIssue").val()
            };

            $.ajax({
                type: "POST",
                url: obj.attr("action"),
                data: formData,
                dataType: "json",
                encode: true,
            }).done(function(data) {
                submitBtn.html("Submit");
                obj.addClass("sent");
                sent
            });


        });
    </script>

    <script src='https://maps.googleapis.com/maps/api/js?key=AIzaSyCRu_qlT0HNjPcs45NXXiOSMd3btAUduSc&libraries=places'>
    </script>
    <script src='https://unpkg.com/@google/markerclustererplus@4.0.1/dist/markerclustererplus.min.js'></script>
    <script src="{{ asset('libs/infobox.js') }}"></script>
    <script src="{{ asset('module/core/js/map-engine.js?_ver=2.2.0') }}"></script>

    <script src="{{ asset('libs/daterange/moment.min.js') }}"></script>
    <script src="{{ asset('libs/toastr/toastr.min.js') }}"></script>
    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        setTimeout(() => {
            $('.alert').hide();
        }, 2000)
    </script>


    @yield('js')

</body>

</html>
