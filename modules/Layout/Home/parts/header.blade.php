<?php
$checkNotify = \Modules\Core\Models\NotificationPush::query();
if (is_admin()) {
    $checkNotify->where(function ($query) {
        $query->where('data', 'LIKE', '%"for_admin":1%');
        $query->orWhere('notifiable_id', Auth::id());
    });
} else {
    $checkNotify->where('data', 'LIKE', '%"for_admin":0%');
    $checkNotify->where('notifiable_id', Auth::id());
}
$notifications = $checkNotify
    ->orderBy('created_at', 'desc')
    ->limit(5)
    ->get();
$countUnread = $checkNotify->where('read_at', null)->count();
?>
<style>
    .indexheaderwrper .menu li a.signinbtn:hover,
    .searchheader .menu li a.signinbtn:hover {
        background-color: #fff;
        color: #000;
        padding: 6px 15px;

    }
</style>
<div class="indexheaderwrper innerheader whitebg">
    <div class="container-fluid header-menu-web">
        <div class="indexlogo pull-left ">
            <a href="{{ route('home') }}" class="fulwidthm left"><img src="{{ asset('user_assets/img/logo_black.png') }}"
                    alt="MyOffice Logo" title="MyOffice"></a>
        </div>
        <div class="responsivehomebtn"> 
            <i class="fa fa-bars"></i>
        </div>
        <div id="topHeadSearch">
            <form method="get" action="{{ route('space.search') }}">
                <input type="hidden" class="check-in-input" value="<?=date('m/d/Y')?>" name="start">
                <input type="hidden" class="check-in-input" value="<?=date('m/d/Y')?>" name="end">
                {{-- <input type="hidden" name="map_lat" value="" id="mapLatV">
                <input type="hidden" name="map_lgn" value="" id="mapLangV"> --}}
                <input type="hidden" name="_layout" value="map">
                <input value="{{ isset($_GET['q']) ? $_GET['q'] : '' }}" name="q" type="text"
                    id="topHeadSearchInput" class="form-control" placeholder="Search for an Office or Business Name">
                <a href="javascript:;"><i class="fa fa-search"></i></a>
            </form>
        </div>
        <ul class="menu">
            <li class="has-submenu">
                <a href="#" title="Find MyOffice">Find MyOffice</a>
                <ul class="sub-menu">
                    <?php
                    $space_attrs = Modules\Core\Models\Attributes::where('service', 'space')
                        ->where('slug', 'space-type')
                        ->orderBy('position', 'asc')
                        ->get();
                    ?>
                    @foreach ($space_attrs as $space_attr)
                        @foreach ($space_attr->terms as $term)
                            <li><a
                                    href="{{ \App\Helpers\CodeHelper::withAppUrl('/') }}space?_layout=map&long_term_rental=&search_type=&map_place=&map_lat=&map_lgn=&start=&end=&date=&from_hour=&to_hour=&adults=&terms[]={{ $term->id }}">{{ $term->name }}</a>
                            </li>
                        @endforeach
                    @endforeach
                </ul>
            </li>
            <li class="has-submenu">
                <a class="" href="{{ \App\Helpers\CodeHelper::withAppUrl('/page/guests-how-it-works') }}"
                    title="How It Works">How It Works</a>
                <ul class="sub-menu">
                    <li>
                        <a href="{{ \App\Helpers\CodeHelper::withAppUrl('/page/guests-how-it-works') }}">Guests</a>
                    </li>
                    <li>
                        <a href="{{ \App\Helpers\CodeHelper::withAppUrl('/page/how-it-works-hosts') }}">Hosts</a>
                    </li>
                </ul>
            </li>
            <li>
                <a class="" href="{{ \App\Helpers\CodeHelper::withAppUrl('/page/concierge') }}"
                    title="Concierge">Concierge</a>
            </li>
            @if (!Auth::id())
                <li class="">
                    <a href="javascript:;" class="signinbtn signinclickmain">Log In </a>
                </li>
                <li class="">
                    <a href="javascript:;" class="signupbtn signupclickmain">List your space</a>
                </li>
            @else
            @if (empty(setting_item('wallet_module_disable')))
                    <li class="credit_amount">
                                <a href="{{ route('user.wallet') }}">
                                    {{ __('Wallet') }}</a>
                    </li>
                @endif
                <li class="dropdown-notifications dropdown p-0">
                    <a href="#" data-toggle="dropdown" class="is_login login-notification" aria-expanded="true">
                        <i class="fa fa-bell m-1 p-1"></i>
                        <span class="badge badge-danger notification-icon">{{ $countUnread }}</span>
                    </a>
                    <ul
                        class="dropdown-menu overflow-auto notify-items dropdown-container dropdown-menu-right dropdown-large">
                        <div class="dropdown-toolbar">
                            <h3 class="dropdown-toolbar-title text-center">{{ __('Notifications') }} (<span
                                    class="notif-count">{{ $countUnread }}</span>)</h3>
                        </div>
                        <div class="p-3 mt-8 bg-opacity-90 flex drop-notifications scrollbar">
                            @if (count($notifications) > 0)
                                @foreach ($notifications as $oneNotification)
                                    @php
                                        $active = $class = '';
                                        $data = json_decode($oneNotification['data']);
                                        
                                        $idNotification = @$data->id;
                                        $forAdmin = @$data->for_admin;
                                        $usingData = @$data->notification;
                                        
                                        $services = @$usingData->type;
                                        $idServices = @$usingData->id;
                                        $title = @$usingData->message;
                                        $name = @$usingData->name;
                                        $avatar = @$usingData->avatar;
                                        $link = @$usingData->link;
                                        
                                        if (empty($oneNotification->read_at)) {
                                            $class = 'markAsRead';
                                            $active = 'active';
                                        }
                                    @endphp
                                    <div class="p-3 media shadow white-bg rounded">
                                        <div class="media-left m-0">
                                            <div class="media-object">
                                                @if ($avatar)
                                                    <img class="image-responsive" src="{{ $avatar }}"
                                                        alt="{{ $name }}">
                                                @else
                                                    <span class="avatar-text">{{ ucfirst($name[0]) }}</span>
                                                @endif

                                            </div>
                                        </div>
                                        <div class="media-body">
                                            <p>{!! $title !!}</p>
                                            <div class="notification-meta">
                                                <small
                                                    class="timestamp text-gray-500">{{ format_interval($oneNotification->created_at) }}</small>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                            <div class="dropdown-footer text-center">
                                <a href="{{ route('core.notification.loadNotify') }}">{{ __('View More') }}</a>
                            </div>
                    </ul>
                </li>
                <li class="dropdown loged">
                    <a href="#" data-toggle="dropdown" class="login"
                        style="text-transform: capitalize;">{{ __('Hi, :name', ['name' => Auth::user()->first_name]) }}
                        <i class="fa fa-angle-down"></i>
                    </a>
                    <ul class="dropdown-menu text-left width-auto">
                        @if (!is_vendor() && !is_admin())
                            <li class="menu-hr"><a href="{{ route('user.dashboard') }}" class="menu-hr"><i
                                        class="icon ion-md-analytics"></i> {{ __('Dashboard') }}</a></li>
                        @endif
                        @if (empty(setting_item('wallet_module_disable')))
                            <li class="credit_amount">
                                <a href="{{ route('user.wallet') }}"><i class="fa fa-money"></i>
                                    {{ __('Credit: :amount', ['amount' => auth()->user()->balance]) }}</a>
                            </li>
                        @endif  
                        @if (is_vendor())
                            <li class="menu-hr"><a href="{{ route('vendor.dashboard') }}" class="menu-hr"><i
                                        class="icon ion-md-analytics"></i> {{ __('Host Dashboard') }}</a></li>
                        @endif
                        <li class="@if (is_vendor()) menu-hr @endif">
                            <a href="{{ route('user.profile.index') }}"><i class="icon ion-md-construct"></i>
                                {{ __('My profile') }}</a>
                        </li>
                        @if (setting_item('inbox_enable'))
                            <li class="menu-hr">
                                <a href="{{ route('user.chat') }}"><i class="fa fa-comments"></i> {{ __('Messages') }}
                                    @if ($count = auth()->user()->unseen_message_count)
                                        <span class="badge badge-danger">{{ $count }}</span>
                                    @endif
                                </a>
                            </li>
                        @endif
                        <li class="menu-hr"><a href="{{ route('user.bookings.details') . '?type=all' }}"><i
                                    class="fa fa-clock-o"></i>
                                {{ __('Booking History') }}</a></li>
                        <li class="menu-hr"><a href="{{ route('user.change_password') }}"><i class="fa fa-lock"></i>
                                {{ __('Change password') }}</a></li>
                        @if (is_admin())
                            <li class="menu-hr"><a href="{{ url('/admin') }}"><i class="icon ion-ios-ribbon"></i>
                                    {{ __('Admin Dashboard') }}</a></li>
                        @endif
                        <li class="menu-hr">
                            <a href="#"
                                onclick="event.preventDefault(); document.getElementById('logout-form-topbar').submit();"><i
                                    class="fa fa-sign-out"></i> {{ __('Logout') }}</a>
                        </li>
                    </ul>
                    <form id="logout-form-topbar" action="{{ route('auth.logout') }}" method="POST"
                        style="display: none;">
                        {{ csrf_field() }}
                    </form>
                </li>
            @endif
        </ul>

        <div class="right responsivemwnu">
            <!-- <div class="responsivebtn "><i class="fa fa-bars"></i></div>-->
            <div class="responsive_menulist">

            </div>
        </div>
    </div>
</div>
