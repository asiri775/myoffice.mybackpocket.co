@extends('layouts.yellow_user')
@section('head')
@endsection
@section('content')
    <div class="content sm-gutter">
        <!-- START BREADCRUMBS-->
        <div class="bg-white">
            <div class="container-fluid pl-5 page-breadcrumb-header">
                <ol class="breadcrumb breadcrumb-alt bg-white mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('user.wallet') }}">Wallet</a></li>
                    <li class="breadcrumb-item active">{{ __('Transaction Details') }}</li>
                </ol>
            </div>
        </div>
        <!-- END BREADCRUMBS -->
        <!-- START CONTAINER FLUID -->
        <div class="container-fluid p-5 user-form-settings">

            @include('admin.message')

            <div class="bravo-user-dashboard">

                <div class="row">
                    <div class="col-md-12 col-12">

                        <div class="card card-default card-bordered card-padding-body-zero p-4 mb-100 card-radious">
                            <div class="card-header card-header-actions">
                                <div class="card-title">
                                    <h4 class="pt-1 text-uppercase">
                                        <strong>
                                            TRANSACTION Details
                                        </strong>
                                    </h4>
                                </div>
                            </div>
                            <div class="card-body table-no-page-options">
                                <div class="table-responsive">
                                    @include('User::frontend.wallet._transaction_details')
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>



        </div>


    </div>
@endsection
