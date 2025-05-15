
<?php
$checkNotify = \Modules\Core\Models\NotificationPush::query();
if(is_admin()){
    $checkNotify->where(function($query){
        $query->where('data', 'LIKE', '%"for_admin":1%');
        $query->orWhere('notifiable_id', Auth::id());
    });
}else{
    $checkNotify->where('data', 'LIKE', '%"for_admin":0%');
    $checkNotify->where('notifiable_id', Auth::id());
}
$notifications = $checkNotify->orderBy('created_at', 'desc')->limit(5)->get();
$countUnread = $checkNotify->where('read_at', null)->count();
?>

<div class="indexheaderwrper innerheader whitebg">
    <div class="container-fluid header-menu-web">
        <div class="indexlogo pull-left ">
            <a href="{{route('home')}}" class="fulwidthm left"><img src="user_assets/img/logo_black.png" alt="MyOffice Logo" title="MyOffice"></a>
        </div>
        <div class="responsivehomebtn">
            <i class="fa fa-bars"></i>
        </div>
        <div id="topHeadSearch">
            <form method="get" action="{{ route('space.search') }}">
                <input type="hidden" name="_layout" value="map">
                <input type="hidden" name="map_lat" value="" id="mapLatV">
                <input type="hidden" name="map_lgn" value="" id="mapLangV">
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
                    $space_attrs = Modules\Core\Models\Terms::where('attr_id', 3)->get();    
                    ?>
                    @foreach( $space_attrs as $space_attr)
                        <li><a href="{{route('space.search')}}?terms[]={{ $space_attr->id }}">{{ $space_attr->name }}</a></li>
                    @endforeach
                </ul>
            </li>
            <li class="has-submenu">
                <a class="" href="#" title="How It Works">How It Works</a>
                <ul class="sub-menu">
                    <li>
                        <a href="http://myoffice.mybackpocket.co/page/guests-how-it-works">Guests</a>
                    </li>
                    <li>
                        <a href="#">Hosts</a>
                    </li>
                </ul>
            </li>
            <li>
                <a class="" href="http://myoffice.mybackpocket.co/page/concierge" title="Concierge">Concierge</a>
            </li>
            @if(!Auth::id())
            <li class="">
                <a href="javascript:;" class="signinbtn signinclickmain">Log In </a>
            </li>
            <li class="">
                <a href="javascript:;" class="signupbtn signupclickmain">List your space</a>
            </li>
            @else
                <li class="dropdown-notifications dropdown p-0">
                    <a href="#" data-toggle="dropdown" class="is_login">
                        <i class="fa fa-bell mr-2"></i>
                        <span class="badge badge-danger notification-icon">{{$countUnread}}</span>
                        <i class="fa fa-angle-down"></i>
                    </a>
                    <ul class="dropdown-menu overflow-auto notify-items dropdown-container dropdown-menu-right dropdown-large">
                        <div class="dropdown-toolbar">
                            <div class="dropdown-toolbar-actions">
                                <a href="#" class="markAllAsRead">{{__('Mark all as read')}}</a>
                            </div>
                            <h3 class="dropdown-toolbar-title">{{__('Notifications')}} (<span class="notif-count">{{$countUnread}}</span>)</h3>
                        </div>
                        <ul class="dropdown-list-items p-0">
                            @if(count($notifications)> 0)
                                @foreach($notifications as $oneNotification)
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

                                        if(empty($oneNotification->read_at)){
                                            $class = 'markAsRead';
                                            $active = 'active';
                                        }
                                    @endphp
                                    <li class="notification {{$active}}">
                                        <a class="{{$class}} p-0" data-id="{{$idNotification}}" href="{{$link}}">
                                            <div class="media">
                                                <div class="media-left">
                                                    <div class="media-object">
                                                        @if($avatar)
                                                            <img class="image-responsive" src="{{$avatar}}" alt="{{$name}}">
                                                        @else
                                                            <span class="avatar-text">{{ucfirst($name[0])}}</span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="media-body">
                                                    {!! $title !!}
                                                    <div class="notification-meta">
                                                        <small class="timestamp">{{format_interval($oneNotification->created_at)}}</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                @endforeach
                            @endif
                        </ul>
                        <div class="dropdown-footer text-center">
                            <a href="{{route('core.notification.loadNotify')}}">{{__('View More')}}</a>
                        </div>
                    </ul>
                </li>
                <li class="login-item dropdown">
                    <a href="#" data-toggle="dropdown" class="login" style="text-transform: capitalize;">{{__("Hi, :name",['name'=>Auth::user()->first_name])}}
                        <i class="fa fa-angle-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-user text-left">
                        @if(empty( setting_item('wallet_module_disable') ))
                            <li class="credit_amount">
                                <a href="{{route('user.wallet')}}"><i class="fa fa-money"></i> {{__("Credit: :amount",['amount'=>auth()->user()->balance])}}</a>
                            </li>
                        @endif
                        @if(is_vendor())
                            <li class="menu-hr"><a href="{{route('vendor.dashboard')}}" class="menu-hr"><i class="icon ion-md-analytics"></i> {{__("Host Dashboard")}}</a></li>
                        @endif
                        <li class="@if(is_vendor()) menu-hr @endif">
                            <a href="{{route('user.profile.index')}}"><i class="icon ion-md-construct"></i> {{__("My profile")}}</a>
                        </li>
                        @if(setting_item('inbox_enable'))
                            <li class="menu-hr">
                                <a href="{{route('user.chat')}}"><i class="fa fa-comments"></i> {{__("Messages")}}
                                    @if($count = auth()->user()->unseen_message_count)
                                        <span class="badge badge-danger">{{$count}}</span>
                                    @endif
                                </a>
                            </li>
                        @endif
                        <li class="menu-hr"><a href="{{ route('user.bookings.details').'?type=history'.'?type=history' }}"><i class="fa fa-clock-o"></i> {{__("Booking History")}}</a></li>
                        <li class="menu-hr"><a href="{{route('user.change_password')}}"><i class="fa fa-lock"></i> {{__("Change password")}}</a></li>
                        @if(is_admin())
                            <li class="menu-hr"><a href="{{url('/admin')}}"><i class="icon ion-ios-ribbon"></i> {{__("Admin Dashboard")}}</a></li>
                        @endif
                        <li class="menu-hr">
                            <a  href="#" onclick="event.preventDefault(); document.getElementById('logout-form-topbar').submit();"><i class="fa fa-sign-out"></i> {{__('Logout')}}</a>
                        </li>
                    </ul>
                    <form id="logout-form-topbar" action="{{ route('auth.logout') }}" method="POST" style="display: none;">
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

