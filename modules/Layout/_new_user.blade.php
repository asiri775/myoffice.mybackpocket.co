<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="content-type" content="text/html;charset=UTF-8" />
    <meta charset="utf-8" />
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
    <link href="{{ asset('user_assets/plugins/bootstrap-timepicker/bootstrap-timepicker.min.css') }}" rel="stylesheet"
        type="text/css" media="screen">
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

    
    <script>
        window.webAlerts = [];
    </script>
    <link href="{{ asset('css/web-alerts.css') }}?v=1.0.01" rel="stylesheet" type="text/css" />
    
    
    <link href="{{ asset('css/dashboard.css') }}?v=1.0.01" rel="stylesheet" type="text/css" />

    <style>
        a {
            text-decoration: none !important;
        }
    </style>

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
                checkout: '{{ is_api() ? route('api.booking.doCheckout') : route('booking.doCheckout') }}'
            },
            module: {
                hotel: '{{ route('hotel.search') }}',
                car: '{{ route('car.search') }}',
                tour: '{{ route('tour.search') }}',
                space: '{{ route('space.search') }}',
                flight: "{{ route('flight.search') }}"
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
    </script>

</head>

<body class="fixed-header horizontal-menu horizontal-app-menu dashboard">
    <!-- START PAGE-CONTAINER -->

    
    @include('User::frontend.layouts.header')

    <div class="page-container" style="overflow-y: auto">
        <div class="page-content-wrapper">

            @include('admin.message')

            @yield('content')

            <div class="container-fluid footer">
                <div class="copyright sm-text-center">
                    <p class="small-text text-black m-0">
                        Copyright © {{ date('Y') }} <b>My Office Inc.</b>All Rights Reserved.
                    </p>
                    <div class="clearfix"></div>
                </div>
            </div>
            <!-- END COPYRIGHT -->
        </div>
        {{--   loading the datatable --}}
        <!-- END PAGE CONTENT WRAPPER -->
    </div>
    <!-- END PAGE CONTAINER -->
    <!--START QUICKVIEW -->
    <div id="quickview" class="quickview-wrapper" data-pages="quickview">
        <!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist">
            <li class="">
                <a href="#quickview-notes" data-target="#quickview-notes" data-toggle="tab" role="tab">Notes</a>
            </li>
            <li>
                <a href="#quickview-alerts" data-target="#quickview-alerts" data-toggle="tab"
                    role="tab">Alerts</a>
            </li>
        </ul>
        <a class="btn-icon-link invert quickview-toggle" data-toggle-element="#quickview" data-toggle="quickview"><i
                class="pg-icon">close</i></a>
        <!-- Tab panes -->
        <div class="tab-content">
            <!-- BEGIN Notes !-->
            <div class="tab-pane no-padding" id="quickview-notes">
                <div class="view-port clearfix quickview-notes" id="note-views">
                    <!-- BEGIN Note List !-->
                    <div class="view list" id="quick-note-list">
                        <div class="toolbar clearfix">
                            <ul class="pull-right ">
                                <li>
                                    <a href="#" class="delete-note-link"><i class="pg-icon">trash_alt</i></a>
                                </li>
                                <li>
                                    <a href="#" class="new-note-link" data-navigate="view"
                                        data-view-port="#note-views" data-view-animation="push"><i
                                            class="pg-icon">add</i></a>
                                </li>
                            </ul>
                            <button aria-label="" class="btn-remove-notes btn btn-xs btn-block hide"><i
                                    class="pg-icon">close</i>Delete
                            </button>
                        </div>
                        <ul>
                            <!-- BEGIN Note Item !-->
                            <li data-noteid="1" class="d-flex justify-space-between">
                                <div class="left">
                                    <!-- BEGIN Note Action !-->
                                    <div class="form-check warning no-margin">
                                        <input id="qncheckbox1" type="checkbox" value="1">
                                        <label for="qncheckbox1"></label>
                                    </div>
                                    <!-- END Note Action !-->
                                    <!-- BEGIN Note Preview Text !-->
                                    <p class="note-preview">Lorem ipsum dolor sit amet, consectetur adipisicing elit,
                                        sed do
                                        eiusmod
                                        tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam</p>
                                    <!-- BEGIN Note Preview Text !-->
                                </div>
                                <!-- BEGIN Note Details !-->
                                <div class="d-flex right justify-content-end">
                                    <!-- BEGIN Note Date !-->
                                    <span class="date">12/12/20</span>
                                    <a href="#" class="d-flex align-items-center" data-navigate="view"
                                        data-view-port="#note-views" data-view-animation="push">
                                        <i class="pg-icon">chevron_right</i>
                                    </a>
                                    <!-- END Note Date !-->
                                </div>
                                <!-- END Note Details !-->
                            </li>
                            <!-- END Note List !-->
                            <!-- BEGIN Note Item !-->
                            <li data-noteid="2" class="d-flex justify-space-between">
                                <div class="left">
                                    <!-- BEGIN Note Action !-->
                                    <div class="form-check warning no-margin">
                                        <input id="qncheckbox2" type="checkbox" value="1">
                                        <label for="qncheckbox2"></label>
                                    </div>
                                    <!-- END Note Action !-->
                                    <!-- BEGIN Note Preview Text !-->
                                    <p class="note-preview">Lorem ipsum dolor sit amet, consectetur adipisicing elit,
                                        sed do
                                        eiusmod
                                        tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam</p>
                                    <!-- BEGIN Note Preview Text !-->
                                </div>
                                <!-- BEGIN Note Details !-->
                                <div class="d-flex right justify-content-end">
                                    <!-- BEGIN Note Date !-->
                                    <span class="date">12/12/20</span>
                                    <a href="#" class="d-flex align-items-center" data-navigate="view"
                                        data-view-port="#note-views" data-view-animation="push"><i
                                            class="pg-icon">chevron_right</i></a>
                                    <!-- END Note Date !-->
                                </div>
                                <!-- END Note Details !-->
                            </li>
                            <!-- END Note List !-->
                            <!-- BEGIN Note Item !-->
                            <li data-noteid="2" class="d-flex justify-space-between">
                                <div class="left">
                                    <!-- BEGIN Note Action !-->
                                    <div class="form-check warning no-margin">
                                        <input id="qncheckbox3" type="checkbox" value="1">
                                        <label for="qncheckbox3"></label>
                                    </div>
                                    <!-- END Note Action !-->
                                    <!-- BEGIN Note Preview Text !-->
                                    <p class="note-preview">Lorem ipsum dolor sit amet, consectetur adipisicing elit,
                                        sed do
                                        eiusmod
                                        tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam</p>
                                    <!-- BEGIN Note Preview Text !-->
                                </div>
                                <!-- BEGIN Note Details !-->
                                <div class="d-flex right justify-content-end">
                                    <!-- BEGIN Note Date !-->
                                    <span class="date">12/12/20</span>
                                    <a href="#" class="d-flex align-items-center" data-navigate="view"
                                        data-view-port="#note-views" data-view-animation="push"><i
                                            class="pg-icon">chevron_right</i></a>
                                    <!-- END Note Date !-->
                                </div>
                                <!-- END Note Details !-->
                            </li>
                            <!-- END Note List !-->
                            <!-- BEGIN Note Item !-->
                            <li data-noteid="3" class="d-flex justify-space-between">
                                <div class="left">
                                    <!-- BEGIN Note Action !-->
                                    <div class="form-check warning no-margin">
                                        <input id="qncheckbox4" type="checkbox" value="1">
                                        <label for="qncheckbox4"></label>
                                    </div>
                                    <!-- END Note Action !-->
                                    <!-- BEGIN Note Preview Text !-->
                                    <p class="note-preview">Lorem ipsum dolor sit amet, consectetur adipisicing elit,
                                        sed do
                                        eiusmod
                                        tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam</p>
                                    <!-- BEGIN Note Preview Text !-->
                                </div>
                                <!-- BEGIN Note Details !-->
                                <div class="d-flex right justify-content-end">
                                    <!-- BEGIN Note Date !-->
                                    <span class="date">12/12/20</span>
                                    <a href="#" class="d-flex align-items-center" data-navigate="view"
                                        data-view-port="#note-views" data-view-animation="push"><i
                                            class="pg-icon">chevron_right</i></a>
                                    <!-- END Note Date !-->
                                </div>
                                <!-- END Note Details !-->
                            </li>
                            <!-- END Note List !-->
                            <!-- BEGIN Note Item !-->
                            <li data-noteid="4" class="d-flex justify-space-between">
                                <div class="left">
                                    <!-- BEGIN Note Action !-->
                                    <div class="form-check warning no-margin">
                                        <input id="qncheckbox5" type="checkbox" value="1">
                                        <label for="qncheckbox5"></label>
                                    </div>
                                    <!-- END Note Action !-->
                                    <!-- BEGIN Note Preview Text !-->
                                    <p class="note-preview">Lorem ipsum dolor sit amet, consectetur adipisicing elit,
                                        sed do
                                        eiusmod
                                        tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam</p>
                                    <!-- BEGIN Note Preview Text !-->
                                </div>
                                <!-- BEGIN Note Details !-->
                                <div class="d-flex right justify-content-end">
                                    <!-- BEGIN Note Date !-->
                                    <span class="date">12/12/20</span>
                                    <a href="#" class="d-flex align-items-center" data-navigate="view"
                                        data-view-port="#note-views" data-view-animation="push"><i
                                            class="pg-icon">chevron_right</i></a>
                                    <!-- END Note Date !-->
                                </div>
                                <!-- END Note Details !-->
                            </li>
                            <!-- END Note List !-->
                        </ul>
                    </div>
                    <!-- END Note List !-->
                    <div class="view note" id="quick-note">
                        <div>
                            <ul class="toolbar">
                                <li><a href="#" class="close-note-link"><i class="pg-icon">chevron_left</i></a>
                                </li>
                                <li><a href="#" data-action="Bold" class="fs-12"><i
                                            class="pg-icon">format_bold</i></a>
                                </li>
                                <li><a href="#" data-action="Italic" class="fs-12"><i
                                            class="pg-icon">format_italics</i></a>
                                </li>
                                <li><a href="#" class="fs-12"><i class="pg-icon">link</i></a>
                                </li>
                            </ul>
                            <div class="body">
                                <div>
                                    <div class="top">
                                        <span>21st april 2020 2:13am</span>
                                    </div>
                                    <div class="content">
                                        <div class="quick-note-editor full-width full-height js-input"
                                            contenteditable="true"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END Notes !-->
            <!-- BEGIN Alerts !-->
            <div class="tab-pane no-padding" id="quickview-alerts">
                <div class="view-port clearfix" id="alerts">
                    <!-- BEGIN Alerts View !-->
                    <div class="view bg-white">
                        <!-- BEGIN View Header !-->
                        <div class="navbar navbar-default navbar-sm">
                            <div class="navbar-inner">
                                <!-- BEGIN Header Controler !-->
                                <a href="javascript:;" class="action p-l-10 link text-color" data-navigate="view"
                                    data-view-port="#chat" data-view-animation="push-parrallax">
                                    <i class="pg-icon">more_horizontal</i>
                                </a>
                                <!-- END Header Controler !-->
                                <div class="view-heading">
                                    Notications
                                </div>
                                <!-- BEGIN Header Controler !-->
                                <a href="#" class="action p-r-10 pull-right link text-color">
                                    <i class="pg-icon">search</i>
                                </a>
                                <!-- END Header Controler !-->
                            </div>
                        </div>
                        <!-- END View Header !-->
                        <!-- BEGIN Alert List !-->
                        <div data-init-list-view="ioslist" class="list-view boreded no-top-border">
                            <!-- BEGIN List Group !-->
                            <div class="list-view-group-container">
                                <!-- BEGIN List Group Header!-->
                                <div class="list-view-group-header text-uppercase">
                                    Calendar
                                </div>
                                <!-- END List Group Header!-->
                                <ul>
                                    <!-- BEGIN List Group Item!-->
                                    <li class="alert-list">
                                        <!-- BEGIN Alert Item Set Animation using data-view-animation !-->
                                        <a href="javascript:;" class="align-items-center" data-navigate="view"
                                            data-view-port="#chat" data-view-animation="push-parrallax">
                                            <p class="">
                                                <span class="text-warning fs-10"><i
                                                        class="pg-icon">circle_fill</i></span>
                                            </p>
                                            <p class="p-l-10 overflow-ellipsis fs-12">
                                                <span class="text-color">David Nester Birthday</span>
                                            </p>
                                            <p class="p-r-10 ml-auto fs-12 text-right">
                                                <span class="text-warning">Today <br></span>
                                                <span class="text-color">All Day</span>
                                            </p>
                                        </a>
                                        <!-- END Alert Item!-->
                                        <!-- BEGIN List Group Item!-->
                                    </li>
                                    <!-- END List Group Item!-->
                                    <!-- BEGIN List Group Item!-->
                                    <li class="alert-list">
                                        <!-- BEGIN Alert Item Set Animation using data-view-animation !-->
                                        <a href="#" class="align-items-center" data-navigate="view"
                                            data-view-port="#chat" data-view-animation="push-parrallax">
                                            <p class="">
                                                <span class="text-warning fs-10"><i
                                                        class="pg-icon">circle_fill</i></span>
                                            </p>
                                            <p class="p-l-10 overflow-ellipsis fs-12">
                                                <span class="text-color">Meeting at 2:30</span>
                                            </p>
                                            <p class="p-r-10 ml-auto fs-12 text-right">
                                                <span class="text-warning">Today</span>
                                            </p>
                                        </a>
                                        <!-- END Alert Item!-->
                                    </li>
                                    <!-- END List Group Item!-->
                                </ul>
                            </div>
                            <!-- END List Group !-->
                            <div class="list-view-group-container">
                                <!-- BEGIN List Group Header!-->
                                <div class="list-view-group-header text-uppercase">
                                    Social
                                </div>
                                <!-- END List Group Header!-->
                                <ul>
                                    <!-- BEGIN List Group Item!-->
                                    <li class="alert-list">
                                        <!-- BEGIN Alert Item Set Animation using data-view-animation !-->
                                        <a href="javascript:;" class="p-t-10 p-b-10 align-items-center"
                                            data-navigate="view" data-view-port="#chat"
                                            data-view-animation="push-parrallax">
                                            <p class="">
                                                <span class="text-complete fs-10"><i
                                                        class="pg-icon">circle_fill</i></span>
                                            </p>
                                            <p class="col overflow-ellipsis fs-12 p-l-10">
                                                <span class="text-color link">Jame Smith commented on your
                                                    status<br></span>
                                                <span class="text-color">“Perfection Simplified - Company Revox"</span>
                                            </p>
                                        </a>
                                        <!-- END Alert Item!-->
                                    </li>
                                    <!-- END List Group Item!-->
                                    <!-- BEGIN List Group Item!-->
                                    <li class="alert-list">
                                        <!-- BEGIN Alert Item Set Animation using data-view-animation !-->
                                        <a href="javascript:;" class="p-t-10 p-b-10 align-items-center"
                                            data-navigate="view" data-view-port="#chat"
                                            data-view-animation="push-parrallax">
                                            <p class="">
                                                <span class="text-complete fs-10"><i
                                                        class="pg-icon">circle_fill</i></span>
                                            </p>
                                            <p class="col overflow-ellipsis fs-12 p-l-10">
                                                <span class="text-color link">Jame Smith commented on your
                                                    status<br></span>
                                                <span class="text-color">“Perfection Simplified - Company Revox"</span>
                                            </p>
                                        </a>
                                        <!-- END Alert Item!-->
                                    </li>
                                    <!-- END List Group Item!-->
                                </ul>
                            </div>
                            <div class="list-view-group-container">
                                <!-- BEGIN List Group Header!-->
                                <div class="list-view-group-header text-uppercase">
                                    Sever Status
                                </div>
                                <!-- END List Group Header!-->
                                <ul>
                                    <!-- BEGIN List Group Item!-->
                                    <li class="alert-list">
                                        <!-- BEGIN Alert Item Set Animation using data-view-animation !-->
                                        <a href="#" class="p-t-10 p-b-10 align-items-center"
                                            data-navigate="view" data-view-port="#chat"
                                            data-view-animation="push-parrallax">
                                            <p class="">
                                                <span class="text-danger fs-10"><i
                                                        class="pg-icon">circle_fill</i></span>
                                            </p>
                                            <p class="col overflow-ellipsis fs-12 p-l-10">
                                                <span class="text-color link">12:13AM GTM, 10230, ID:WR174s<br></span>
                                                <span class="text-color">Server Load Exceeted. Take action</span>
                                            </p>
                                        </a>
                                        <!-- END Alert Item!-->
                                    </li>
                                    <!-- END List Group Item!-->
                                </ul>
                            </div>
                        </div>
                        <!-- END Alert List !-->
                    </div>
                    <!-- EEND Alerts View !-->
                </div>
            </div>
            <!-- END Alerts !-->

        </div>
    </div>
    <!-- END QUICKVIEW-->
    <!-- START OVERLAY -->
    <div class="overlay hide" data-pages="search">
        <!-- BEGIN Overlay Content !-->
        <div class="overlay-content has-results m-t-20">
            <!-- BEGIN Overlay Header !-->
            <div class="container-fluid">
                <!-- BEGIN Overlay Logo !-->
                <img class="overlay-brand" src="user_assets/img/logo.png" alt="logo"
                    data-src="user_assets/img/logo.png" data-src-retina="user_assets/img/logo_2x.png" width="78"
                    height="22">
                <!-- END Overlay Logo !-->
                <!-- BEGIN Overlay Close !-->
                <a id="search_close" href="#"
                    class="close-icon-light btn-link btn-rounded  overlay-close text-black">
                    <i class="pg-icon">close</i>
                </a>
                <!-- END Overlay Close !-->
            </div>
            <!-- END Overlay Header !-->
            <div class="container-fluid">
                <!-- BEGIN Overlay Controls !-->
                <input id="search" class="no-border overlay-search bg-transparent" placeholder="Search..."
                    autocomplete="off" spellcheck="false">
                <br>
                {{--            <div class="d-flex align-items-center"> --}}
                {{--                <div class="form-check right m-b-0"> --}}
                {{--                    <input id="checkboxn" type="checkbox" value="1"> --}}
                {{--                    <label for="checkboxn">Search within page</label> --}}
                {{--                </div> --}}
                {{--                <p class="fs-13 hint-text m-l-10 m-b-0">Press enter to search</p> --}}
                {{--            </div> --}}
                <!-- END Overlay Controls !-->
            </div>
            <!-- BEGIN Overlay Search Results, This part is for demo purpose, you can add anything you like !-->
            <div class="container-fluid p-20">
                <div class="search-results m-t-20">
                    <p class="bold">Search Results: </p>
                    <div class="row">
                        <div id="results" class="col-md-6">
                        </div>
                        {{--                    <div class="col-md-6"> --}}
                        {{--                        <!-- BEGIN Search Result Item !--> --}}
                        {{--                        <div class="d-flex m-t-15"> --}}
                        {{--                            <!-- BEGIN Search Result Item Thumbnail !--> --}}
                        {{--                            <div class="thumbnail-wrapper d48 circular bg-info text-white d-flex align-items-center"> --}}
                        {{--                                <i class="pg-icon">facebook</i> --}}
                        {{--                            </div> --}}
                        {{--                            <!-- END Search Result Item Thumbnail !--> --}}
                        {{--                            <div class="p-l-10"> --}}
                        {{--                                <h5 class="no-margin "><span class="semi-bold result-name">ice cream</span> on facebook --}}
                        {{--                                </h5> --}}
                        {{--                                <p class="small-text hint-text">via facebook</p> --}}
                        {{--                            </div> --}}
                        {{--                        </div> --}}
                        {{--                        <!-- END Search Result Item !--> --}}
                        {{--                        <!-- BEGIN Search Result Item !--> --}}
                        {{--                        <div class="d-flex m-t-15"> --}}
                        {{--                            <!-- BEGIN Search Result Item Thumbnail !--> --}}
                        {{--                            <div --}}
                        {{--                                class="thumbnail-wrapper d48 circular bg-complete text-white d-flex align-items-center"> --}}
                        {{--                                <i class="pg-icon">twitter</i> --}}
                        {{--                            </div> --}}
                        {{--                            <!-- END Search Result Item Thumbnail !--> --}}
                        {{--                            <div class="p-l-10"> --}}
                        {{--                                <h5 class="no-margin ">Tweats on<span class="semi-bold result-name"> ice cream</span> --}}
                        {{--                                </h5> --}}
                        {{--                                <p class="small-text hint-text">via twitter</p> --}}
                        {{--                            </div> --}}
                        {{--                        </div> --}}
                        {{--                        <!-- END Search Result Item !--> --}}
                        {{--                        <!-- BEGIN Search Result Item !--> --}}
                        {{--                        <div class="d-flex m-t-15"> --}}
                        {{--                            <!-- BEGIN Search Result Item Thumbnail !--> --}}
                        {{--                            <div class="thumbnail-wrapper d48 circular text-white bg-danger d-flex align-items-center"> --}}
                        {{--                                <i class="pg-icon">google_plus</i> --}}
                        {{--                            </div> --}}
                        {{--                            <!-- END Search Result Item Thumbnail !--> --}}
                        {{--                            <div class="p-l-10"> --}}
                        {{--                                <h5 class="no-margin ">Circles on<span class="semi-bold result-name"> ice cream</span> --}}
                        {{--                                </h5> --}}
                        {{--                                <p class="small-text hint-text">via google plus</p> --}}
                        {{--                            </div> --}}
                        {{--                        </div> --}}
                        {{--                        <!-- END Search Result Item !--> --}}
                        {{--                    </div> --}}
                    </div>
                </div>
            </div>
            <!-- END Overlay Search Results !-->
        </div>
        <!-- END Overlay Content !-->
    </div>
    <!-- END OVERLAY -->
    <!-- BEGIN VENDOR JS -->
    <script src="{{ asset('user_assets/plugins/pace/pace.min.js') }}" type="text/javascript"></script>
    <!--  A polyfill for browsers that don't support ligatures: remove liga.js if not needed-->
    <script src="{{ asset('user_assets/plugins/liga.js') }}" type="text/javascript"></script>
    <script src="{{ asset('user_assets/plugins/jquery/jquery-3.2.1.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('user_assets/plugins/modernizr.custom.js') }}" type="text/javascript"></script>
    <script src="{{ asset('user_assets/plugins/jquery-ui/jquery-ui.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('user_assets/plugins/popper/umd/popper.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('user_assets/plugins/bootstrap/js/bootstrap.min.js') }}" type="text/javascript"></script>
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
    <script>
        $(document).ready(function() {

            $('#search_close').on('click', function() {
                $("#search").val("");
                $("#results").html("");
            });

            $('#search').keyup(function(e) {
                e.preventDefault();

                let x = $(this).val();

                $.ajax({
                    url: '{{ route('user.bookings.search') }}',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    method: 'POST',
                    data: {
                        title: x,
                    },
                    success: function(data) {
                        if (data['bookings'] != null) {
                            $("#results").html("");
                            jQuery.each(data['bookings'], function(i, val) {
                                $("#results").append(
                                    "<div class='d-flex m-t-15'><div class='p-l-10'><h5 class='no-margin'>" +
                                    val['id'] + " <a href='" +
                                    "{{ url('user/booking-details') }}" + "/" +
                                    val['id'] + "'>" + val['title'] +
                                    "</a><h5></div></div>"
                                );
                            });
                        } else {
                            $("#results").html("");
                        }
                    },
                    error: function(XMLHttpRequest, status, err) {
                        console.error(XMLHttpRequest);
                    }
                });
            });
        });
    </script>

    <script>
        $(document).on("click", ".service-wishlist", function() {
            var $this = $(this);
            let _token = $('meta[name="csrf-token"]').attr('content');

            $.ajax({
                url: bookingCore.url + '/user/wishlist',
                data: {
                    object_id: $this.attr("data-id"),
                    object_model: $this.attr("data-type"),
                    _token: _token
                },
                dataType: 'json',
                type: 'POST',
                beforeSend: function() {
                    $this.addClass("loading");
                },
                success: function(res) {
                    $this.attr('class', "service-wishlist " + res.class);
                    $("#msg-success").html("Added To Wishlist");

                },
                error: function(e) {
                    if (e.status === 401) {
                        $('#login').modal('show');
                    }
                }
            })
            
            $.ajax({
                url: bookingCore.url + '/space/add-to-favourite',
                data: {
                    space_id: $this.attr("data-id"),
                    _token: _token
                },
                dataType: 'json',
                type: 'POST',
                beforeSend: function() {
                    $this.addClass("loading");
                },
                success: function(res) {
                    $("#msg-success").html("Added To Favourite");

                },
                error: function(e) {
                    if (e.status === 401) {
                        $('#login').modal('show');
                    }
                }
            })
        });
    </script>

    <script type="text/javascript">
        $(document).ready(function(e) {

            $('.myacounthover').hover(function() {

                $(this).children('.myacountdropdown').stop().fadeIn(200);

            }, function() {
                $(this).children('.myacountdropdown ').stop().fadeOut(200);
            });

            // RESPONSIVE MENU Starts

            var menucontent = $('.rightmenu').html();
            // main menu's Html

            $('.responsive_menulist').html(menucontent);
            // adding main menu to responsive menu

            // if width > 1000px responsive menu and close btn Hide Starts

            // if width > 1000px responsive menu and close btn Hide Ends

            $('.responsivebtn').click(function(e) {
                $(this).toggleClass("active");
                $('.responsive_menulist').fadeToggle(200);
                //$('.responsive_menulist').toggle( "drop", { direction: "right" }, 200);
            });

            $('.responsivebtn, .responsive_menulist').click(function(event) {
                event.stopPropagation();

            });
            $(document).click(function(e) {
                $('.responsivebtn').removeClass("active");
                $('.responsive_menulist').fadeOut(200);
            });

            // Sub Menu showing in Responsive menu
            $(".responsive_menulist .myacounthover").click(function() {

                $(this).children('.responsive_menulist .myacountdropdown ').slideToggle(200);
            });

            // Sub Menu showing in Responsive menu	 Ends

            // RESPONSIVE MENU ENDS

        });
    </script>

    @yield('footer') 
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
