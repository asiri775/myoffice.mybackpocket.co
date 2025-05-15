@extends('layouts.yellow_user')
@section('head')
@endsection
@section('content')
    <style>
        .bravo_wrap textarea.form-control {
            height: auto !important;
        }

        #map_content {
            width: 100%;
        }
    </style>
    <div class="content sm-gutter">
        <!-- START BREADCRUMBS-->
        <div class="bg-white">
            <div class="container-fluid pl-5">
                <ol class="breadcrumb breadcrumb-alt bg-white mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">{{ __('Change Password') }}</li>
                </ol>
            </div>
        </div>
        <!-- END BREADCRUMBS -->
        <!-- START CONTAINER FLUID -->
        <div class="container-fluid p-5 user-form-settings">

            @include('admin.message')

            <div class="card card-default card-bordered p-4 card-radious">
                <div class="card-header ">
                    <div class="card-title">
                        <h4>
                            {{ __('Change Password') }}
                        </h4>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('user.change_password.update') }}" method="post"
                        class="input-has-icon row-fix-col">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group password">
                                            <label>{{ __('New Password') }}</label>
                                            <div class="form-field">
                                                <input type="password" name="new-password"
                                                    placeholder="{{ __('Enter New Password') }}" class="form-control">
                                                <a href="javascript:;" class="togglePassField"><i
                                                        class="input-icon icofont-eye"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group password">
                                            <label>{{ __('Confirm Password') }}</label>
                                            <div class="form-field">
                                                <input type="password" name="new-password_confirmation"
                                                    placeholder="{{ __('Enter Confirm Password') }}" class="form-control">
                                                <a href="javascript:;" class="togglePassField"><i
                                                        class="input-icon icofont-eye"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <hr>
                                <button class="btn btn-primary" type="submit"><i class="fa fa-save"></i>
                                    {{ __('Save Changes') }}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

        </div>

    </div>
@endsection
@section('footer')
@endsection
