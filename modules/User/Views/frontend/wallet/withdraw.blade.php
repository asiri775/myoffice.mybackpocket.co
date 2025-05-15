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
                    <li class="breadcrumb-item active">{{ __('Wallet') }}</li>
                </ol>



            </div>
        </div>
        <!-- END BREADCRUMBS -->
        <!-- START CONTAINER FLUID -->
        <div class="container-fluid p-5 user-form-settings">

            @include('admin.message')

            <div class="bravo-user-dashboard">

                <div class="row">
                    <div class="col-md-7 col-12">

                        <div class="card card-no-card card-bordered p-2 card-radious pt-0">
                            <div class="card-header card-header-actions ">
                                <div class="card-title">
                                    <h4 class="text-uppercase">
                                        <strong>
                                            Withdrawal
                                        </strong>
                                    </h4>
                                </div>
                                <div class="card-actions">
                                    <a href="{{ route('user.wallet.buy') }}"
                                        class="btn btn-primary">{{ __('Buy Credits') }}</a>
                                    <a href="{{ route('user.wallet') }}"
                                        class="btn btn-primary ms-5px">{{ __('Back to Wallet') }}</a>
                                </div>
                            </div>
                        </div>

                        <!-- START card -->
                        <div class="card card-default card-bordered p-4 mb-15 card-radious">

                            <div class="card-body ">
                                <form action="{{route('user.wallet.processWithdraw')}}" method="post" class="withdrawal-form">
                                    @csrf
                                    <ul>
                                        <li>
                                            <div class="left">
                                                <h5>Cash Balance:</h5>
                                            </div>
                                            <div class="right">
                                                <h5 class="bold">${{ __(':amount', ['amount' => $row->balance]) }}</h5>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="left">
                                                <h5>Withdrawal Amount:</h5>
                                            </div>
                                            <div class="right">
                                                <input required name="amount" id="witdrawalAmount" type="text"
                                                    class="form-control h5" placeholder="Enter Amount">
                                            </div>
                                        </li>
                                        <li>
                                            <div class="left">
                                                <h5>Service Fee:</h5>
                                            </div>
                                            <div class="right">
                                                <h5 class="bold">${{ \App\Helpers\Constants::SERVICE_FEE }}</h5>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="left">
                                                <h5>Total:</h5>
                                            </div>
                                            <div class="right">
                                                <input readonly id="witdrawalAmountTotal" type="text"
                                                    class="form-control h5" placeholder="Total">
                                            </div>
                                        </li>
                                        <li>
                                            <div class="left">
                                                <h5>Deposit Account To:</h5>
                                            </div>
                                            <div class="right">
                                                <input name="deposit_to" required type="text" class="form-control" placeholder="Deposit details">
                                            </div>
                                        </li>
                                        <li>
                                            <div class="left">

                                            </div>
                                            <div class="right">
                                                <button type="submit" class="btn btn-primary">Confirm</button>
                                            </div>
                                        </li>
                                    </ul>
                                </form>
                            </div>

                        </div>

                        <div class="card card-default card-bordered p-4 mb-100 card-radious withdrawal-notice">
                            <p>The typical processing time for Withdrawal requests are five business dats within
                                MyOffice platform. Once the funfs have been transferred from our accounts, it may take
                                anywhere from 24 hours to 10 days for the funds to post to your account, depending on
                                the method.</p>
                            <p>The fastest methods are typically payments directly to your credit card on file, or by
                                E-Transfers.</p>
                        </div>


                    </div>
                    <div class="col-md-5 col-12">
                        @include('User::frontend.wallet._sidebar')
                    </div>
                </div>

            </div>



        </div>


    </div>
@endsection

@section('footer')
    <script>
        const WITHDRAWAL_FEE = {{ \App\Helpers\Constants::SERVICE_FEE }};
        $(document).on("keyup", "#witdrawalAmount", function() {
            var amount = $(this).val().toString().trim();
            amount = parseFloat(amount);
            var total = amount + WITHDRAWAL_FEE;
            $("#witdrawalAmountTotal").val("$" + total);
        });
    </script>
@endsection
