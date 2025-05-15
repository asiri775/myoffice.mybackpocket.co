@extends('layouts.new_user')
@section('content')
    <div class="content sm-gutter">
        <!-- START BREADCRUMBS-->
        <div class="bg-white">
            <div class="container-fluid pl-5">
                <ol class="breadcrumb breadcrumb-alt bg-white mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Notifications</li>
                </ol>
            </div>
        </div>

        <div class="container-fluid p-5">
            <div id="bravo_notify">
                <div class="row">

                    <div class="col-12 col-md-3">
                        <div class="card card-default card-bordered p-4 card-radious">
                            <ul class="dropdown-list-items notification-dropdown-options p-0">
                                <li class="notification @if (empty($type)) active @endif">
                                    <i class="fa fa-inbox fa-lg mr-2"></i> <a
                                        href="{{ route('core.notification.loadNotify') }}">&nbsp;{{ __('All') }}</a>
                                </li>
                                <li class="notification @if (!empty($type) && $type == 'unread') active @endif">
                                    <i class="fa fa-envelope-o fa-lg mr-2"></i> <a
                                        href="{{ route('core.notification.loadNotify', ['type' => 'unread']) }}">{{ __('Unread') }}</a>
                                </li>
                                <li class="notification @if (!empty($type) && $type == 'read') active @endif">
                                    <i class="fa fa-envelope-open-o fa-lg mr-2"></i> <a
                                        href="{{ route('core.notification.loadNotify', ['type' => 'read']) }}">{{ __('Read') }}</a>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="col-12 col-md-9">
                        <div class="card card-default card-bordered notification-list p-4 card-radious">
                            @include('Core::frontend.notification.list')
                        </div>
                    </div>

                </div>


            </div>

        </div>

    </div>
@endsection
