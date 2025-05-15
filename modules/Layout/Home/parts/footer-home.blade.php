@if (!is_api())
    <footer class="mainfooter">
        <div class="container">
            <div class="footcol">
                <h4>COMPANY</h4>
                <ul>
                    <li>
                        <a href="http://myoffice.mybackpocket.co/page/about-us">About Us</a>
                    </li>
                    <li>
                        <a href="http://myoffice.mybackpocket.co/page/terms-of-service">Terms and Conditions</a>
                    </li>
                    <li>
                        <a href="http://myoffice.mybackpocket.co/page/privacy-policy">Privacy Policy </a>
                    </li>
                </ul>


            </div>
            <div class="footcol">
                <h4>HOST SERVICES</h4>
                <ul>
                    <li>
                        <a href="http://myoffice.mybackpocket.co/page/share-your-space">Share Your Space </a>
                    </li>
                    <li>
                        <a href="http://myoffice.mybackpocket.co/page/host-faqs">FAQs</a>
                    </li>
                    <li>
                        <a href="http://myoffice.mybackpocket.co/register">Sign Up</a>
                    </li>
                    <li>
                        <a href="http://myoffice.mybackpocket.co/contact">Support</a>
                    </li>
                </ul>
            </div>
            <div class="footcol">
                <h4>GUEST SERVICES</h4>
                <ul>
                    <li>
                        <a href="http://myoffice.mybackpocket.co/page/guests-how-it-works">Be Our Guests</a>
                    </li>
                    <li>
                        <a href="http://myoffice.mybackpocket.co/contact">Support</a>
                    </li>
                </ul>
            </div>

            <div class="sociallinks">
                Join Us On
                <ul class="socialul text-center">
                    <li class="sociali left">
                        <a class="sociala" href="https://www.facebook.com/myofficeinc" target="_blank"><i
                                class="fa fa-facebook"></i></a>
                    </li>
                    <li class="sociali left">
                        <a class="sociala" href="https://www.instagram.com/myofficeinc/" target="_blank"><i
                                class="fa fa-instagram"></i></a>
                    </li>
                </ul>

            </div>
            <div class="clearfix"></div>
            <div class="copyright">
                Copyright &copy; {{date('Y')}} My Office Inc. All Rights Reserved. </div>
            <div style="display:none;">
            </div>
        </div>
        </div>
    </footer>
    <div class="coprightbtm fulwidthm left text-center footerbg">

    </div>
@endif

@include('Layout::Home.parts.login-register-modal')
@include('Layout::Home.parts.chat')
@if (Auth::id())
    @include('Media::browser')
@endif
<link rel="stylesheet" href="{{ asset('libs/flags/css/flag-icon.min.css') }}">
<script type="text/javascript" src="{{ asset('js/common.js') }}?v={{time()}}"></script>
{!! \App\Helpers\Assets::css(true) !!}


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
<script type="text/javascript" src="{{ asset('js/signinpopup.js') }}?v={{config('app.version')}}"></script>
{{-- Lazy Load --}}
<script src="{{ asset('libs/lazy-load/intersection-observer.js') }}"></script>
<script async src="{{ asset('libs/lazy-load/lazyload.min.js') }}"></script>
<script>
    // Set the options to make LazyLoad self-initialize
    window.lazyLoadOptions = {
        elements_selector: ".lazy",
        // ... more custom settings?
    };

    // Listen to the initialization event and get the instance of LazyLoad
    window.addEventListener('LazyLoad::Initialized', function(event) {
        window.lazyLoadInstance = event.detail.instance;
    }, false);
</script>

<link rel="stylesheet" href="{{ asset('css/superslides.css') }}">

<script type="text/javascript" src="{{ asset('js/jquery.superslides.js') }}"></script>
<script type='text/javascript'>
    $(document).ready(function() {
        $('#slides').superslides({
            animation: 'fade',
            pagination: false,
            play: 3000
        });

    })
</script>
<script src="{{ asset('libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('libs/bootbox/bootbox.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('libs/daterange/moment.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('libs/daterange/daterangepicker.min.js') }}"></script>
<script src="{{ asset('libs/select2/js/select2.min.js') }}"></script>
<script src="{{ asset('js/functions.js?_ver=' . config('app.version')) }}"></script>
<script src="{{ asset('js/home.js?_ver=' . config('app.version')) }}"></script>
<script src="{{ asset('libs/toastr/toastr.min.js') }}"></script>
<script src="{{ asset('libs/lodash.min.js') }}"></script>
{{-- <script src="{{ asset('libs/vue/vue' . (!env('APP_DEBUG') ? '.min' : '') . '.js') }}"></script> --}}
<script src="{{ asset('libs/carousel-2/owl.carousel.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/animate/animate.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/animate/wow.js') }}"></script>
<script src='https://unpkg.com/@google/markerclustererplus@4.0.1/dist/markerclustererplus.min.js'></script>
<script type="text/javascript" src="{{ asset('js/select2.js') }}"></script>

@if (Auth::id())
    <script src="{{ asset('module/media/js/browser.js?_ver=' . config('app.version')) }}"></script>
@endif





@if (setting_item('tour_location_search_style') == 'autocompletePlace' || setting_item('hotel_location_search_style') == 'autocompletePlace' || setting_item('car_location_search_style') == 'autocompletePlace' || setting_item('space_location_search_style') == 'autocompletePlace' || setting_item('hotel_location_search_style') == 'autocompletePlace' || setting_item('event_location_search_style') == 'autocompletePlace')
    {!! App\Helpers\MapEngine::scripts() !!}
@endif
<script src="{{ asset('libs/pusher.min.js') }}"></script>


@if (!empty($is_user_page))
    <script src="{{ asset('module/user/js/user.js?_ver=' . config('app.version')) }}"></script>
@endif
@if (setting_item('cookie_agreement_enable') == 1 and request()->cookie('booking_cookie_agreement_enable') != 1 and !is_api() and !isset($_COOKIE['booking_cookie_agreement_enable']))
    <div class="booking_cookie_agreement p-3 d-flex fixed-bottom">
        <div class="content-cookie">{!! clean(setting_item_with_lang('cookie_agreement_content')) !!}</div>
        <button class="btn save-cookie">{!! clean(setting_item_with_lang('cookie_agreement_button_text')) !!}</button>
    </div>
    <script>
        var save_cookie_url = '{{ route('core.cookie.check') }}';
    </script>
    <script src="{{ asset('js/cookie.js?_ver=' . config('app.version')) }}"></script>
@endif

{!! \App\Helpers\Assets::js(true) !!}
<script type="text/javascript">
    $(document).ready(function(e) {
        //map mobile click
        function viewport() {
            var e = window,
                a = 'inner';
            if (!('innerWidth' in window)) {
                a = 'client';
                e = document.documentElement || document.body;
            }
            return {
                width: e[a + 'Width'],
                height: e[a + 'Height']
            };
        }


        $(document).on('click', '.indexheaderwrper .menu li.has-submenu>a', function() {
            var w = viewport();
            if (w.width < 992) {
                $(this).closest('li').find('.sub-menu').slideToggle();
                $(this).closest('li').toggleClass('open');
            }
        });
        $(document).on('click', '.indexheaderwrper .responsivehomebtn', function() {
            $('.indexheaderwrper .menu').slideToggle();
        });
        $(document).on('click', '.tabsproperty .tabs a', function() {
            var id = $(this).attr('data-id');
            $('.tabsproperty .tabcontainer .tabwrapper').hide();
            $('.tabsproperty .tabcontainer #' + id).show();
            $('.tabsproperty .tabs a').removeClass('active')
            $(this).addClass('active');
        });
        var id = $('.tabsproperty .tabs a.active').attr('data-id');
        $('.tabsproperty .tabcontainer .tabwrapper').hide();
        $('.tabsproperty .tabcontainer #' + id).show();

        // footer fixed
        var windowwidth = $(window).width();
        var footerheight = $('.fulfooter').outerHeight(true);
        if (windowwidth > 1000) {
            $('.footergap').css({
                "height": footerheight
            });
        } else {
            $('.footergap').css({
                "height": 0
            });
        }
        $(window).resize(function() {

            var windowwidth = $(window).width();
            var footerheight = $('.fulfooter').outerHeight(true);
            if (windowwidth > 1000) {
                $('.footergap').css({
                    "height": footerheight
                });
            } else {
                $('.footergap').css({
                    "height": 0
                });
            }

        });
    });
</script>
@yield('footer')

@php \App\Helpers\ReCaptchaEngine::scripts() @endphp

<script type="text/javascript">
    $(document).ready(function(e) {

        $(".formselect").select2();

        $(".selectsearch, .footerselect").select2();

        wow = new WOW({
            animateClass: 'animated',
            mobile: false,

            offset: 100
        });
        wow.init();
        // slect style

    });
</script>
<script type="text/javascript">
    (function(d, src, c) {
        var t = d.scripts[d.scripts.length - 1],
            s = d.createElement('script');
        s.id = 'la_x2s6df8d';
        s.async = true;
        s.src = src;
        s.onload = s.onreadystatechange = function() {
            var rs = this.readyState;
            if (rs && (rs != 'complete') && (rs != 'loaded')) {
                return;
            }
            c(this);
        };
        t.parentElement.insertBefore(s, t.nextSibling);
    })(document, 'https://shredding.ladesk.com/scripts/track.js', function(e) {
        LiveAgent.createButton('af5fe7b7', e);
    });
</script>

<script type="text/javascript">
    (function(d, src, c) {
        var t = d.scripts[d.scripts.length - 1],
            s = d.createElement('script');
        s.id = 'la_x2s6df8d';
        s.async = true;
        s.src = src;
        s.onload = s.onreadystatechange = function() {
            var rs = this.readyState;
            if (rs && (rs != 'complete') && (rs != 'loaded')) {
                return;
            }
            c(this);
        };
        t.parentElement.insertBefore(s, t.nextSibling);
    })(document, 'https://shredding.ladesk.com/scripts/track.js', function(e) {});
</script>
<script type="text/javascript">
    (function(d, src, c) {
        var t = d.scripts[d.scripts.length - 1],
            s = d.createElement('script');
        s.id = 'la_x2s6df8d';
        s.async = true;
        s.src = src;
        s.onload = s.onreadystatechange = function() {
            var rs = this.readyState;
            if (rs && (rs != 'complete') && (rs != 'loaded')) {
                return;
            }
            c(this);
        };
        t.parentElement.insertBefore(s, t.nextSibling);
    })(document, 'https://shredding.ladesk.com/scripts/track.js', function(e) {});
</script>
