<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="{{ $html_class ?? '' }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <meta http-equiv="content-type" content="text/html;charset=UTF-8" />

    <title>MyOffice {{ $page_title ? '- ' . $page_title : '' }}</title>
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, shrink-to-fit=no" />

    @php
        $favicon = setting_item('site_favicon');
    @endphp
    @if ($favicon)
        @php
            $file = (new \Modules\Media\Models\MediaFile())->findById($favicon);
        @endphp
        @if (!empty($file))
            <link rel="icon" type="{{ $file['file_type'] }}" href="{{ asset('uploads/' . $file['file_path']) }}" />
        @else:
            <link rel="icon" type="image/png" href="{{ url('images/favicon.png') }}" />
        @endif
    @endif

    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-touch-fullscreen" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta content="MyOffice - Find the right Workspace that suits you" name="description" />
    <meta content="MyOffice" name="author" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="{{ asset('fullcalendar/main.css') }}">
    <link rel="stylesheet" href="{{ asset('datetimepicker/jquery.datetimepicker.min.css') }}">

    <script src="{{ asset('fullcalendar/main.js') }}"></script>

    <link rel="stylesheet" type="text/css" href="{{ asset('libs/daterange/daterangepicker.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('libs/select2/css/select2.min.css') }}">
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&family=Roboto:wght@300;400;500;700&display=swap"
        rel="stylesheet">


    <link href="{{ asset('user_assets/plugins/pace/pace-theme-flash.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" type="text/css" href="{{ asset('css/bootstrap.min.css') }}" />
    <link href="{{ asset('user_assets/plugins/jquery-datatable/media/css/dataTables.bootstrap.min.css') }}"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="{{ asset('user_assets/plugins/jquery-scrollbar/jquery.scrollbar.css') }}" rel="stylesheet"
        type="text/css" media="screen" />
    <link rel="stylesheet" type="text/css" href="{{ asset('css/select2.css') }}" />
    <link href="{{ asset('user_assets/plugins/nvd3/nv.d3.min.css') }}" rel="stylesheet" type="text/css"
        media="screen" />
    <link href="{{ asset('user_assets/plugins/mapplic/css/mapplic.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('user_assets/plugins/rickshaw/rickshaw.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('user_assets/plugins/bootstrap-datepicker/css/datepicker3.css') }}" rel="stylesheet"
        type="text/css" media="screen">
    <link href="{{ asset('user_assets/plugins/bootstrap-daterangepicker/daterangepicker-bs3.css') }}" rel="stylesheet"
        type="text/css" media="screen">

    <link
        href="{{ asset('user_assets/plugins/jquery-datatable/extensions/FixedColumns/css/dataTables.fixedColumns.min.css') }}"
        rel="stylesheet" type="text/css" />
    <link href="{{ asset('user_assets/plugins/datatables-responsive/css/datatables.responsive.css') }}"
        rel="stylesheet" type="text/css" media="screen" />
    <link href="{{ asset('user_assets/css/dashboard.widgets.css') }}" rel="stylesheet" type="text/css"
        media="screen" />
    <link href="{{ asset('user_assets/plugins/icofont/icofont.css') }}" rel="stylesheet" type="text/css"
        media="screen" />
    <link class="main-stylesheet" href="{{ asset('user_pages/css/themes/modern.css') }}" rel="stylesheet"
        type="text/css" />
    <link href="{{ asset('user_assets/plugins/bootstrap-tag/bootstrap-tagsinput.css') }}" rel="stylesheet"
        type="text/css" />
    <link href="{{ asset('/user_assets/plugins/dropzone/css/dropzone.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('user_assets/plugins/bootstrap-timepicker/bootstrap-timepicker.min.css') }}"
        rel="stylesheet" type="text/css" media="screen">
    <link class="main-stylesheet" href="{{ asset('user_pages/css/pages-icons.css') }}" rel="stylesheet"
        type="text/css" />
    <link href="{{ asset('user_assets/css/style.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('user_assets/plugins/pace/pace-theme-flash.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('user_assets/plugins/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet"
        type="text/css" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="{{ asset('user_assets/plugins/jquery-scrollbar/jquery.scrollbar.css') }}" rel="stylesheet"
        type="text/css" media="screen" />
    <link href="{{ asset('user_assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css"
        media="screen" />
    <link href="{{ asset('libs/font-awesome/css/font-awesome.css') }}" rel="stylesheet">
    <link href="{{ asset('libs/ionicons/css/ionicons.min.css') }}" rel="stylesheet">
    <link href="{{ asset('libs/icofont/icofont.min.css') }}" rel="stylesheet">

    <link href="{{ asset('user_assets/css/style.css') }}" rel="stylesheet" type="text/css" />

    <link rel="stylesheet" href="{{ asset('fullcalendar/main.css') }}">
    <link rel="stylesheet" href="{{ asset('datetimepicker/jquery.datetimepicker.min.css') }}">

    <link rel="stylesheet" href="{{ asset('libs/toastr/toastr.min.css') }}">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>

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

    <link href="{{ asset('dist/frontend/module/user/css/user.css') }}" rel="stylesheet">


    @yield('head')
    <style type="text/css">
        .bravo_topbar,
        .bravo_header,
        .bravo_footer {
            display: none;
        }

        html,
        body,
        .bravo_wrap,
        .bravo_user_profile,
        .bravo_user_profile>.container-fluid>.row-eq-height>.col-md-3 {
            /* min-height: 100vh !important; */
        }
    </style>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@18.2.1/build/css/intlTelInput.css">

    <script>
        window.webAlerts = [];
    </script>
    <link href="{{ asset('css/web-alerts.css') }}?v=1.0.01" rel="stylesheet" type="text/css" />
    
    
    <link href="{{ asset('css/dashboard.css') }}?v={{ time() }}" rel="stylesheet">

</head>


<body class="fixed-header horizontal-menu horizontal-app-menu dashboard">


    <div class="page-container" style="overflow-y: auto">
        <div class="page-content-wrapper">
            <div class="bravo_wrap">
                <div class="bravo_user_profile">
                    @yield('content')
                </div>
            </div>
        </div>
    </div>

    @if (!is_demo_mode())
        {!! setting_item('body_scripts') !!}
    @endif


    <script src="{{ asset('libs/filerobot-image-editor/filerobot-image-editor.min.js?_ver=' . config('app.version')) }}">
    </script>
    @if (!is_demo_mode())
        {!! setting_item('footer_scripts') !!}
    @endif

    <script src="{{ asset('datetimepicker/jquery.datetimepicker.full.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>

    <script src="{{ asset('user_assets/plugins/pace/pace.min.js') }}" type="text/javascript"></script>
    <!--  A polyfill for browsers that don't support ligatures: remove liga.js if not needed-->
    <script src="{{ asset('user_assets/plugins/liga.js') }}" type="text/javascript"></script>

    <script src="{{ asset('user_assets/plugins/modernizr.custom.js') }}" type="text/javascript"></script>
    <script src="{{ asset('user_assets/plugins/jquery-ui/jquery-ui.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('user_assets/plugins/popper/umd/popper.min.js') }}" type="text/javascript"></script>

    <script src="{{ asset('user_assets/plugins/jquery/jquery-easy.js') }}" type="text/javascript"></script>
    <script src="{{ asset('user_assets/plugins/jquery-unveil/jquery.unveil.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('user_assets/plugins/jquery-ios-list/jquery.ioslist.min.js') }}" type="text/javascript">
    </script>
    <script src="{{ asset('user_assets/plugins/jquery-actual/jquery.actual.min.js') }}"></script>
    <script src="{{ asset('user_assets/plugins/jquery-scrollbar/jquery.scrollbar.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('user_assets/plugins/select2/js/select2.full.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('user_assets/plugins/classie/classie.js') }}"></script>
    <script type="text/javascript" src="{{ asset('user_assets/plugins/dropzone/dropzone.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('user_assets/plugins/bootstrap-tag/bootstrap-tagsinput.min.js') }}">
    </script>
    <script type="text/javascript" src="{{ asset('user_assets/plugins/jquery-inputmask/jquery.inputmask.min.js') }}">
    </script>
    <script src="{{ asset('user_assets/plugins/bootstrap-form-wizard/js/jquery.bootstrap.wizard.min.js') }}"
        type="text/javascript"></script>
    <script src="{{ asset('user_assets/plugins/jquery-validation/js/jquery.validate.min.js') }}" type="text/javascript">
    </script>
    <script src="{{ asset('user_assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js') }}"
        type="text/javascript"></script>

    <script src="{{ asset('user_assets/plugins/nvd3/lib/d3.v3.js') }}" type="text/javascript"></script>
    <script src="{{ asset('user_assets/plugins/nvd3/nv.d3.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('user_assets/plugins/nvd3/src/utils.js') }}" type="text/javascript"></script>
    <script src="{{ asset('user_assets/plugins/nvd3/src/tooltip.js') }}" type="text/javascript"></script>
    <script src="{{ asset('user_assets/plugins/nvd3/src/interactiveLayer.js') }}" type="text/javascript"></script>
    <script src="{{ asset('user_assets/plugins/nvd3/src/models/axis.js') }}" type="text/javascript"></script>
    <script src="{{ asset('user_assets/plugins/nvd3/src/models/line.js') }}" type="text/javascript"></script>
    <script src="{{ asset('user_assets/plugins/nvd3/src/models/lineWithFocusChart.js') }}" type="text/javascript">
    </script>
    <script src="{{ asset('user_assets/plugins/mapplic/js/hammer.min.js') }}"></script>
    <script src="{{ asset('user_assets/plugins/mapplic/js/jquery.mousewheel.js') }}"></script>
    <script src="{{ asset('user_assets/plugins/mapplic/js/mapplic.js') }}"></script>
    <script src="{{ asset('user_assets/plugins/rickshaw/rickshaw.min.js') }}"></script>
    <script src="{{ asset('user_assets/plugins/jquery-metrojs/MetroJs.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('user_assets/plugins/jquery-sparkline/jquery.sparkline.min.js') }}" type="text/javascript">
    </script>
    <script src="{{ asset('user_assets/plugins/skycons/skycons.js') }}" type="text/javascript"></script>
    <script src="{{ asset('user_assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js') }}"
        type="text/javascript"></script>
    <script src="{{ asset('user_assets/plugins/moment/moment.min.js') }}"></script>
    <script src="{{ asset('user_assets/plugins/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
    <script src="{{ asset('user_assets/plugins/bootstrap-timepicker/bootstrap-timepicker.min.js') }}"></script>
    <script src="{{ asset('user_assets/plugins/bootstrap-typehead/typeahead.bundle.min.js') }}"></script>
    <script src="{{ asset('user_assets/plugins/bootstrap-typehead/typeahead.jquery.min.js') }}"></script>
    <script src="{{ asset('user_assets/plugins/handlebars/handlebars-v4.0.5.js') }}"></script>
    <script src="{{ asset('user_assets/plugins/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
    <script src="{{ asset('user_assets/plugins/jquery-datatable/media/js/jquery.dataTables.min.js') }}"
        type="text/javascript"></script>
    <script
        src="{{ asset('user_assets/plugins/jquery-datatable/extensions/TableTools/js/dataTables.tableTools.min.js') }}"
        type="text/javascript"></script>
    <script src="{{ asset('user_assets/plugins/jquery-datatable/media/js/dataTables.bootstrap.js') }}"
        type="text/javascript"></script>
    <script src="{{ asset('user_assets/plugins/jquery-datatable/extensions/Bootstrap/jquery-datatable-bootstrap.js') }}"
        type="text/javascript"></script>
    <script src="{{ asset('user_assets/plugins/interactjs/interact.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('user_assets/plugins/moment/moment-with-locales.min.js') }}"></script>
    <script type="text/javascript"
        src="{{ asset('user_assets/plugins/datatables-responsive/js/datatables.responsive.js') }}"></script>
    <script type="text/javascript" src="{{ asset('user_assets/plugins/datatables-responsive/js/lodash.min.js') }}">
    </script>
    <!-- END VENDOR JS -->
    <script src="{{ asset('user_pages/js/pages.min.js') }} "></script>
    <script src="{{ asset('user_pages/js/pages.calendar.min.js') }}"></script>
    <!-- END CORE TEMPLATE JS -->
    <!-- BEGIN PAGE LEVEL JS -->
    <script src="{{ asset('user_assets/js/calendar_month.js') }}" type="text/javascript"></script>
    <script src="{{ asset('user_assets/js/form_elements.js') }}" type="text/javascript"></script>
    <script src="{{ asset('user_assets/js/datatables.js') }}" type="text/javascript"></script>
    <script src="{{ asset('user_assets/js/scripts.js') }}" type="text/javascript"></script>

    <script src="https://cdn.jsdelivr.net/npm/intl-tel-input@18.2.1/build/js/intlTelInput.min.js"></script>

    <!-- END PAGE LEVEL JS -->
    <!-- END PAGE LEVEL JS -->
    <script>
        $("#ActivateAdvanceSerach").click(function() {
            $("#AdvanceFilters").show();
            $("#HideActivateAdvanceSerach").show();
            $("#ActivateAdvanceSerach").hide()
        });
        $("#HideActivateAdvanceSerach").click(function() {
            $("#AdvanceFilters").hide();
            $("#HideActivateAdvanceSerach").hide();
            $("#ActivateAdvanceSerach").show();
        });
    </script>

    
    <script src="{{ asset('js/dashboard.js') }}?v={{ time() }}"></script>

    @yield('pageEndJs')

    <script>
        @guest
            window.IS_LOGGED_IN = false;
        @else
            window.IS_LOGGED_IN = true;
        @endguest
        </script>
    <script src="{{ asset('js/web-alerts.js') }}?v={{ time() }}"></script>

</body>

</html>
