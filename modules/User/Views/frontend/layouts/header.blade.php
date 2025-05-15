<?php
$user = auth()->user();
$isVendor = $user->can('dashboard_vendor_access');
if($user->email_verified_at == null){
?>
<div id="verification-banner">
    <p>Verify your email address. Your Booking requests will be in pending unless you confirmed your email Address. <a
            onclick="event.preventDefault(); document.getElementById('email-form').submit(); "
            href="{{ route('verification.resend') }}">Resend Activation Link</a></p>
    <form id="email-form" action="{{ route('verification.resend') }}" method="POST" style="display: none;">
        @csrf
    </form>
</div>
<?php } ?>

<div class="header p-r-0">
    <div class="header-inner header-md-height">
        <a href="#" class="btn-link toggle-sidebar d-lg-none text-white sm-p-l-0 btn-icon-link"
            data-toggle="horizontal-menu">
            <i class="pg-icon">menu</i>
        </a>
        <div class="">
            <div class="brand inline no-border d-sm-inline-block">
                <a href="{{ route('home') }}">
                    <img src="{{ asset('user_assets/img/logo_black.png') }}" alt="logo"
                        data-src="{{ asset('user_assets/img/logo_black.png') }}"
                        data-src-retina="{{ asset('user_assets/img/logo_black.png') }}" width="232" height="65">
                </a>
            </div>
        </div>
        <div class="header-wrap header-wrap-block justify-content-start">
            <div class="menu-bar header-sm-height">
                <a href="#" class="btn-link header-icon toggle-sidebar d-lg-none" data-toggle="horizontal-menu">
                    <i class="pg-icon">close</i>
                </a>
                <ul>
                    <?php
                    
                    if ($isVendor) {
                    ?>

                    <li class="active">
                        <a href="{{ route('vendor.dashboard') }}">Dashboard</a>
                    </li>
                    <li>
                        <a href="javascript:;"><span class="title">Bookings</span>
                            <span class=" arrow"></span></a>
                        <ul class="">
                            {{-- <li class="">
                                <a href="{{ route('user.bookings.details') . '?type=all' }} ">All</a>
                            </li>
                            @foreach (\App\Helpers\Constants::BOOKING_STATUES as $k => $txt)
                                <li class="">
                                    <a
                                        href="{{ route('user.bookings.details') . '?type=' . $k }} ">{{ $txt }}</a>
                                </li>
                            @endforeach --}}
                            <li class="">
                                <a href="{{ route('user.bookings.details') . '?type=all' }} ">All</a>
                            </li>
                            <li class="">
                                <a href="{{ route('user.bookings.details') . '?type=active' }} ">Active</a>
                            </li>
                            <li class="">
                                <a href="{{ route('user.bookings.details') . '?type=completed' }} ">Completed</a>
                            </li>
                            <li class="">
                                <a href="{{ route('user.bookings.details') . '?type=archived' }} ">Archived</a>
                            </li>
                            <li class="">
                                <a href="{{ route('user.calendar') }} ">Calendar</a>
                            </li>
                        </ul>
                    </li>
                    <li class="">
                        <a href="{{ route('space.vendor.index') }}">Spaces</a>
                    </li>
                    <li class="">
                        <a href="{{ route('space.vendor.allEarningReports') }}">Earnings</a>
                    </li>
                    {{-- <li class="">
                        <a href="{{ route('event.vendor.index') }}">Events</a>
                    </li> --}}

                    <?php
                    } else {
                    ?>

                    <li class="active">
                        <a href="{{ route('user.dashboard') }}">Dashboard</a>
                    </li>
                    <li>
                        <a href="{{ route('user.calendar') }} ">Calendar</a>
                    </li>
                    <li>
                        <a href="javascript:;"><span class="title">Bookings</span>
                            <span class=" arrow"></span></a>
                        <ul class="">
                            <li class="">
                                <a href="{{ route('user.bookings.details') . '?type=all' }} ">All</a>
                            </li>
                            <li class="">
                                <a href="{{ route('user.bookings.details') . '?type=booked' }} ">Booked</a>
                            </li>
                            <li class="">
                                <a href="{{ route('user.bookings.details') . '?type=completed' }} ">Completed</a>
                            </li>
                            <li class="">
                                <a href="{{ route('user.bookings.details') . '?type=archived' }} ">Archived</a>
                            </li>
                            {{-- @foreach (\App\Helpers\Constants::BOOKING_STATUES as $k => $txt)
                                <li class="">
                                    <a
                                        href="{{ route('user.bookings.details') . '?type=' . $k }} ">{{ $txt }}</a>
                                </li>
                            @endforeach
                            <li class="">
                                <a href="{{ route('user.calendar') }} ">Calendar</a>
                            </li> --}}
                        </ul>
                    </li>
                    <li>
                        <a href="{{ route('user.favourites') }}"><span class="title">Favourites</span></a>
                    </li>

                    <?php
                    }
                    ?>

                    <li class="">
                        <a href="{{ route('reviews.vendor.index') }}">Reviews</a>
                    </li>

                </ul>
            </div>
            <div class="search-link d-lg-inline-block d-none search-top-panel">
                <form action="{{ url('user/bookings-details') }}" method="get">
                    <input type="hidden" name="type" value="all">
                    <i class="pg-icon">search</i>
                    <span><input autocomplete="off" name="search" type="text"
                            placeholder="Search for a Booking or Space"></span>
                </form>
            </div>
        </div>
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
        $notifications = $checkNotify->orderBy('created_at', 'desc')->limit(5)->get();
        $countUnread = $checkNotify->where('read_at', null)->count();
        $emails = [];
        ?>
        <div class="header-wrap justify-content-end">
            <!-- START NOTIFICATION LIST -->
            <ul class="d-lg-inline-block d-none  notification-list no-margin b-grey b-l b-r no-style p-l-30 p-r-20">
                <li class="p-r-10 inline d-none">
                    <div class="dropdown">
                        <a href="javascript:;" id="notification-center" class="header-icon btn-icon-link"
                            data-toggle="dropdown">
                            <span class="material-icons">
                                notifications
                            </span>
                            @if (\App\Helpers\CodeHelper::totalUnreadNotifications($notifications) > 0)
                                <span class="bubble"></span>
                            @endif
                        </a>
                        <!-- START Notification Dropdown -->
                        <div class="dropdown-menu notification-toggle" role="menu"
                            aria-labelledby="notification-center">
                            <!-- START Notification -->
                            <div class="notification-panel">
                                <!-- START Notification Body-->
                                <div class="notification-body scrollable">
                                    <!-- START Notification Item-->
                                    @if (count($notifications) > 0)
                                        @foreach ($notifications as $oneNotification)
                                            @php
                                                $active = $class = '';
                                                $data = json_decode($oneNotification['data']);

                                                $idNotification = @$data->id;
                                                $forAdmin = @$data->for_admin;
                                                $usingData = @$data->notification;
                                                $event = @$usingData->event;
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
                                            <div class="notification-item unread clearfix">
                                                <!-- START Notification Item-->
                                                <div class="heading open notification-box">
                                                    <div class="notification-head">
                                                        <a href="{{ $link }}"
                                                            class="text-complete pull-left d-flex align-items-center">
                                                            <i class="pg-icon m-r-10">map</i>
                                                            <span class="bold">{{ $name }}</span>
                                                            <span class="fs-12 m-l-10">{{ $services }}</span>
                                                        </a>
                                                        <div class="notification-box-chevron">
                                                            <div
                                                                class="thumbnail-wrapper d16 circular inline m-t-15 m-r-10 toggle-more-details">
                                                                <div><i class="pg-icon">chevron_down</i>
                                                                </div>
                                                            </div>
                                                            <span
                                                                class=" time">{{ format_interval($oneNotification->created_at) }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="more-details">
                                                        <div class="more-details-inner">
                                                            <h5 class="semi-bold fs-16">“{!! $title !!}.”</h5>
                                                            <p class="small hint-text">
                                                                {{ $event }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- END Notification Item-->
                                                <!-- START Notification Item Right Side-->
                                                <div class="option" data-toggle="tooltip" data-placement="left"
                                                    title="mark as read">
                                                    <a href="#" class="mark"></a>
                                                </div>
                                                <!-- END Notification Item Right Side-->
                                            </div>
                                        @endforeach
                                    @endif

                                    <!-- END Notification Item-->
                                </div>
                                <!-- END Notification Body-->
                                <!-- START Notification Footer-->
                                <div class="notification-footer text-center">
                                    <a href="{{ route('core.notification.loadNotify') }}" class="">Read all
                                        notifications</a>
                                    <a data-toggle="refresh" class="portlet-refresh text-black pull-right"
                                        href="#">
                                        <i class="pg-refresh_new"></i>
                                    </a>
                                </div>
                                <!-- START Notification Footer-->
                            </div>
                            <!-- END Notification -->
                        </div>
                        <!-- END Notification Dropdown -->
                    </div>
                </li>
                <li class="p-r-10 inline d-none">
                    <div class="dropdown">
                        <a href="javascript:;" id="notification-center" class="header-icon btn-icon-link"
                            data-toggle="dropdown">
                            <span class="material-icons">
                                mail
                            </span>
                            @if (\App\Helpers\CodeHelper::totalUnreadEmails($emails) > 0)
                                <span class="bubble"></span>
                            @endif
                        </a>
                        <!-- START Notification Dropdown -->
                        <div class="dropdown-menu notification-toggle" role="menu"
                            aria-labelledby="notification-center">
                            <!-- START Notification -->
                            <div class="notification-panel">
                                <!-- START Notification Body-->
                                <div class="notification-body scrollable">
                                    <!-- START Notification Item-->
                                    @if (count($emails) > 0)
                                        @foreach ($emails as $oneNotification)
                                            @php
                                                $active = $class = '';
                                                $data = json_decode($oneNotification['data']);

                                                $idNotification = @$data->id;
                                                $forAdmin = @$data->for_admin;
                                                $usingData = @$data->notification;
                                                $event = @$usingData->event;
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
                                            <div class="notification-item unread clearfix">
                                                <!-- START Notification Item-->
                                                <div class="heading open notification-box">
                                                    <div class="notification-head">
                                                        <a href="{{ $link }}"
                                                            class="text-complete pull-left d-flex align-items-center">
                                                            <i class="pg-icon m-r-10">map</i>
                                                            <span class="bold">{{ $name }}</span>
                                                            <span class="fs-12 m-l-10">{{ $services }}</span>
                                                        </a>
                                                        <div class="notification-box-chevron">
                                                            <div
                                                                class="thumbnail-wrapper d16 circular inline m-t-15 m-r-10 toggle-more-details">
                                                                <div><i class="pg-icon">chevron_down</i>
                                                                </div>
                                                            </div>
                                                            <span
                                                                class=" time">{{ format_interval($oneNotification->created_at) }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="more-details">
                                                        <div class="more-details-inner">
                                                            <h5 class="semi-bold fs-16">“{!! $title !!}.”</h5>
                                                            <p class="small hint-text">
                                                                {{ $event }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- END Notification Item-->
                                                <!-- START Notification Item Right Side-->
                                                <div class="option" data-toggle="tooltip" data-placement="left"
                                                    title="mark as read">
                                                    <a href="#" class="mark"></a>
                                                </div>
                                                <!-- END Notification Item Right Side-->
                                            </div>
                                        @endforeach
                                    @endif
                                    <!-- END Notification Item-->
                                </div>
                                <!-- END Notification Body-->
                                <!-- START Notification Footer-->
                                <div class="notification-footer text-center">
                                    <a href="{{ route('core.notification.loadNotify') }}" class="">Read all
                                        notifications</a>
                                    <a data-toggle="refresh" class="portlet-refresh text-black pull-right"
                                        href="#">
                                        <i class="pg-refresh_new"></i>
                                    </a>
                                </div>
                                <!-- START Notification Footer-->
                            </div>
                            <!-- END Notification -->
                        </div>
                        <!-- END Notification Dropdown -->
                    </div>
                </li>

                <?php
            if ($isVendor) {
            ?>
                {{-- <li class="p-r-10 inline">
                    <div class="dropdown pull-right d-lg-block">
                        <a class="text-white" href="javascript:;" data-toggle="dropdown" aria-haspopup="true"
                            aria-expanded="false" aria-label="profile dropdown">
                            <span class="material-icons">
                                person
                            </span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right profile-dropdown" role="menu">
                            <a target="_blank" href="{{ route('crmRedirect', ['redirect' => '/admin/leads']) }}"
                                class="dropdown-item">Manage
                                Leads</a>
                            <a target="_blank" href="{{ route('crmRedirect', ['redirect' => '/admin/contacts/persons']) }}"
                                class="dropdown-item">Contact</a>
                            <a target="_blank" href="http://helpdesk.mybackpocket.co"
                                class="dropdown-item">Helpdesk</a>
                        </div>
                    </div>
                </li> --}}
                <?php 
        } ?>

                <li class="p-r-10 inline">
                    <div class="">
                        <a target="_blank" href="{{ route('user.inbox') }}" id="notification-center"
                            class="header-icon btn-icon-link">
                            <span class="material-icons">
                                mail
                            </span>
                            @if (\App\Helpers\CodeHelper::totalUnreadEmails($emails) > 0)
                                <span class="bubble"></span>
                            @endif
                        </a>
                    </div>
                </li>

            </ul>

            <!-- END NOTIFICATIONS LIST -->
            <div class="d-flex align-items-center">
                <!-- START User Info-->
                <div class="dropdown pull-right d-lg-block">
                    <button class="profile-dropdown-toggle profile-dropdown-toggle-head" type="button"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                        aria-label="profile dropdown">
                        <div class="pull-left p-r-10 fs-14 d-lg-inline-block d-none text-white">
                            @php $user = \Modules\User\Models\User::where('id', \Illuminate\Support\Facades\Auth::id())->first() @endphp
                            <span
                                class="semi-bold">{{ $user->name ?? $user->first_name . ' ' . $user->last_name }}</span>
                        </div>
                        <span class="thumbnail-wrapper d32 circular inline">
                            <img src="{{ $user->getAvatarUrl() }}" alt=""
                                data-src="{{ $user->getAvatarUrl() }}" data-src-retina="{{ $user->getAvatarUrl() }}"
                                width="32" height="32">
                        </span>
                    </button>
                    <div class="dropdown-menu dropdown-menu-right profile-dropdown" role="menu">
                        <a href="#" class="dropdown-item"><span>Signed in as
                                <br /><b>{{ $user->name ?? $user->first_name . ' ' . $user->last_name }}</b></span></a>
                        <div class="dropdown-divider"></div>
                        <a href="{{ route('user.profile.index') }}" class="dropdown-item">My Account</a>
                        <a href="{{ route('user.wallet') }}" class="dropdown-item">Wallet</a>

                        <a href="{{ route('user.change_password') }}" class="dropdown-item">Change Password</a>
                        <div class="dropdown-divider"></div>

                        <?php
            if ($isVendor) {
            ?>
                        <a target="_blank" href="{{ route('crmRedirect', ['redirect' => '/admin/leads']) }}"
                            class="dropdown-item">Manage
                            Leads</a>
                        <a target="_blank"
                            href="{{ route('crmRedirect', ['redirect' => '/admin/contacts/persons']) }}"
                            class="dropdown-item">Contact</a>
                        <a target="_blank" href="http://helpdesk.mybackpocket.co"
                            class="dropdown-item">Helpdesk</a>
                        <div class="dropdown-divider"></div>
                        <?php 
        } ?>

                        <a href="javascript:;" data-toggle="modal" data-target="#helpModal"
                            class="dropdown-item">Help</a>
                        <form id="logout-form-user" action="{{ route('auth.logout') }}" method="POST"
                            style="display: none;">
                            {{ csrf_field() }}
                        </form>
                        <a href="#"
                            onclick="event.preventDefault(); document.getElementById('logout-form-user').submit();"
                            class="dropdown-item">Logout</a>
                        <!-- <div class="dropdown-divider"></div>
                        <span class="dropdown-item fs-12 hint-text">Last edited by {{ $user->first_name }}<br />on
                            Friday at 5:27PM</span> -->
                    </div>
                </div>
                <!-- END User Info-->
                <div class="lotal-amount">
                    <a href="{{ route('user.wallet') }}" style="color:#fff;text-decoration:none;">
                        {{ format_money(credit_to_money(auth()->user()->balance)) }}
                    </a>
                </div>
            </div>
        </div>

    </div>
</div>


<div class="modal fade" tabindex="-1" role="dialog" id="helpModal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content ">
            <form action="{{ route('contactSubmit') }}" method="post">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('Help') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body mt-4">
                    <div class="form-group">
                        <label for="">Topic</label>
                        <select required name="topic" id="Topic" class="form-control">
                            <option value="General">General</option>
                            <option value="Account Management">Account Management</option>
                            <option value="Billing">Billing</option>
                            <option value="Bookings">Bookings</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="">Subject</label>
                        <input required type="text" class="form-control" name="subject"
                            placeholder="{{ __('Enter Subject') }}">
                    </div>
                    <div class="form-group" v-if="!enquiry_is_submit">
                        <label for="">Notes</label>
                        <textarea required rows="6" class="form-control" placeholder="{{ __('Enter Notes') }}" name="notes"></textarea>
                    </div>
                    <div class="message_box"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary"
                        data-dismiss="modal">{{ __('Close') }}</button>
                    <button type="submit" class="btn btn-primary btn-su">{{ __('Send now') }}
                        <i class="fa icon-loading fa-spinner fa-spin fa-fw" style="display: none"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@if (session('resent'))
    <script>
        window.webAlerts.push({
            type: "success",
            message: "{{ __('A fresh verification link has been sent to your email address.') }}"
        });
    </script>
@endif
