@extends('layouts.yellow_user')
@section('head')
    {{-- <link rel="stylesheet" href="{{ asset('module/booking/css/checkout.css') }}"> --}}
@endsection
@section('content')

    <div class="content sm-gutter">
        <!-- START BREADCRUMBS-->
        <div class="bg-white">
            <div class="container-fluid pl-5 page-breadcrumb-header">
                <ol class="breadcrumb breadcrumb-alt bg-white mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">{{ __('Buy Credits') }}</li>
                </ol>



            </div>
        </div>


        <div class="container-fluid p-5 user-form-settings">

            @include('admin.message')

            <div class="bravo-user-dashboard">

                <div class="row">
                    <div class="col-12 mb-50">

                        <div class="card card-no-card card-bordered p-2 card-radious pt-0">
                            <div class="card-header card-header-actions ">
                                <div class="card-title">
                                    <h4 class="text-uppercase">
                                        <strong>
                                            Purchase Credits
                                        </strong>
                                    </h4>
                                    <p class="card-sub-title">Select one of the Credit Package below</p>
                                </div>
                            </div>
                        </div>

                        <form action="{{ route('user.wallet.buyProcess') }}" id="buyCreditForm" method="post">

                            <div class="credit-wallet-packages">
                                @foreach ($wallet_deposit_lists as $k => $item)
                                    <div class="credit-wallet-package">
                                        <div class="head">
                                            <h1>{{ $item['credit'] }}</h1>
                                            <h5>Credits</h5>
                                        </div>
                                        <div class="body">
                                            <input type="radio" id="deposit_amount_<?= $k ?>" name="deposit_option"
                                                value="{{ $k }}"
                                                deposit_amount_<?= $k ?>="{{ $item['amount'] }}">
                                            <h4>{{ format_money($item['amount']) }}</h4>
                                            <p>{{ $item['name'] }}</p>
                                            <button type="button" class="btn selectPlan btn-primary">Select</button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="bravo-user-dashboard d-none">

                                <div class="card card-default card-bordered p-2 card-radious">
                                    <div class="card-header card-header-actions">
                                        <div class="card-title">
                                            <h4 class="text-uppercase">
                                                <strong>
                                                    {{ __('Buy') }}
                                                </strong>
                                            </h4>
                                        </div>
                                        <div class="card-actions"></div>
                                    </div>
                                    <div class="card-body">
                                        @csrf

                                        @if (setting_item('wallet_deposit_type') == 'list')
                                            @if (!empty($wallet_deposit_lists))
                                                <div class="table-responsive">
                                                    <table
                                                        class="table demo-table-search table-responsive-block data-table dataTable no-footer theme-normal-table">
                                                        <thead>
                                                            <tr>
                                                                <th scope="col">#</th>
                                                                <th scope="col">{{ __('Name') }}</th>
                                                                <th scope="col">{{ __('Price') }}</th>
                                                                <th scope="col">{{ __('Credit') }}</th>
                                                                <th scope="col"></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @php
                                                                //print_r($wallet_deposit_lists);die;
                                                            @endphp
                                                            @foreach ($wallet_deposit_lists as $k => $item)
                                                                {{-- <tr>
                                                                    <td>{{ $k + 1 }}</td>
                                                                    <td>{{ $item['name'] }}</td>
                                                                    <td>{{ format_money($item['amount']) }}</td>
                                                                    <td>{{ $item['credit'] }}</td>
                                                                    <td><label class="btn btn-primary btn-sm]">
                                                                            <input type="radio"
                                                                                id="deposit_amount_<?= $k ?>"
                                                                                name="deposit_option"
                                                                                value="{{ $k }}"
                                                                                deposit_amount_<?= $k ?>="{{ $item['amount'] }}">
                                                                            {{ __('Select') }} </label></td>
                                                                </tr> --}}
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            @else
                                                <div class="alert alert-warning">{{ __('Sorry, no options found') }}</div>
                                            @endif
                                        @else
                                            <div class="form-section mt-3">
                                                <h4 class="form-section-title">
                                                    {{ __('How much would you like to deposit?') }}
                                                </h4>
                                                <div class="input-group mb-3">
                                                    <input type="text" class="form-control update_exchange_value"
                                                        name="deposit_amount" placeholder="{{ __('Deposit amount') }}"
                                                        aria-describedby="basic-addon2">
                                                    <div class="input-group-append">
                                                        <span class="input-group-text deposit_exchange_value"
                                                            data-rate="{{ (float) setting_item('wallet_deposit_rate', 1) }}"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                        <div class="form-section mt-3">
                                            <h4 class="form-section-title">{{ __('Select Payment Method') }}</h4>
                                            <div class="gateways-table accordion mt-3" id="accordionExample">
                                                @foreach ($gateways as $k => $gateway)
                                                    <div class="card">
                                                        <div class="card-header">
                                                            <strong class="mb-0">
                                                                <label class="" data-toggle="collapse"
                                                                    data-target="#gateway_{{ $k }}">
                                                                    <input type="radio" name="payment_gateway"
                                                                        value="{{ $k }}">
                                                                    @if ($logo = $gateway->getDisplayLogo())
                                                                        <img src="{{ $logo }}"
                                                                            alt="{{ $gateway->getDisplayName() }}">
                                                                    @endif
                                                                    {{ $gateway->getDisplayName() }}
                                                                </label>
                                                            </strong>
                                                        </div>
                                                        <div id="gateway_{{ $k }}" class="collapse"
                                                            aria-labelledby="headingOne" data-parent="#accordionExample">
                                                            <div class="card-body">
                                                                <div class="gateway_name">
                                                                    {!! $gateway->getDisplayName() !!}
                                                                </div>
                                                                {!! $gateway->getDisplayHtml() !!}
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"
                                            integrity="sha512-pumBsjNRGGqkPzKHndZMaAG+bir374sORyzM3uulLV14lN5LyykqNk8eEeUlUkB3U0M4FApyaHraT65ihJhDpQ=="
                                            crossorigin="anonymous" referrerpolicy="no-referrer"></script>
                                        <script>
                                            $('input[type=radio][name=deposit_option]').on('change', function() {
                                                checkDepositNow(); 
                                            });

                                            function checkDepositNow() {
                                                var deposit_id = $("input[name=deposit_option]:checked").val();
                                                var deposit_to_pay = $("#deposit_amount_" + deposit_id).attr("deposit_amount_" + deposit_id);
                                                $.ajax({
                                                    url: '{{ route('gateway.update') }}',
                                                    data: {
                                                        "amount": deposit_to_pay
                                                    },
                                                    type: 'GET',
                                                    success: function(data) {
                                                        var json = $.parseJSON(data);
                                                        $('#amount').val(json.amount);
                                                        $('#orderId').val(json.orderId);
                                                        $('#invoiceNumber').val(json.orderId);
                                                        $('#txnToken').val(json.txnToken);
                                                        //console.log(data);
                                                        $("#buyCreditForm").submit();
                                                    }
                                                });

                                            }
                                        </script>
                                        @php
                                            $user = auth()->user();
                                        @endphp

                                        <input type="hidden" id="merchantPgIdentifier" name="merchantPgIdentifier"
                                            value="205">
                                        <input type="hidden" id="secret_id" name="secret_id" value="2001">
                                        <input type="hidden" id="currency" name="currency" value="CAD">
                                        <input type="hidden" id="amount" name="amount" value="">
                                        <input type="hidden" id="orderId" name="orderId" value="">
                                        <input type="hidden" id="invoiceNumber" name="invoiceNumber" value="">
                                        <input type="hidden" id="successUrl" name="successUrl"
                                            value="{{\App\Helpers\CodeHelper::withAppUrl('gateway/confirm/two_checkout_gateway')}}">
                                        <input type="hidden" id="errorUrl" name="errorUrl"
                                            value="{{\App\Helpers\CodeHelper::withAppUrl('gateway/cancel/two_checkout_gateway')}}">
                                        <input type="hidden" id="storeName" name="storeName" value="name205">
                                        <input type="hidden" id="transactionType" name="transactionType"
                                            value="">
                                        <input type="hidden" id="timeout" name="timeout" value="">
                                        <input type="hidden" id="transactionDateTime" name="transactionDateTime"
                                            value="{{ date('Y-m-d') }}">
                                        <input type="hidden" id="language" name="language" value="EN">
                                        <input type="hidden" id="credits" name="credits"
                                            value="{{ $user->balance }}">
                                        <input type="hidden" id="txnToken" name="txnToken" value=""">
                                        <input type="hidden" id="itemList" name="itemList" value="Deposit">
                                        <input type="hidden" id="otherInfo" name="otherInfo" value="">
                                        <input type="hidden" id="merchantCustomerPhone" name="merchantCustomerPhone"
                                            value="04353563535">
                                        <input type="hidden" id="merchantCustomerEmail" name="merchantCustomerEmail"
                                            value="customer@gmail.com">
                                        <input type="hidden" id="customer_id" name="customer_id" value=""> 
                                        @php
                                            $term_conditions = setting_item('booking_term_conditions');
                                        @endphp

                                        <div class="form-group mt-3">
                                            <label class="term-conditions-checkbox">
                                                <input checked type="checkbox" name="term_conditions">
                                                {{ __('I have read and accept the') }} <a target="_blank"
                                                    href="{{ get_page_url($term_conditions) }}">{{ __('terms and conditions') }}</a>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <button class="btn btn-primary d-none" type="submit">{{ __('Process now') }}</button>

                        </form>
                    </div>
                </div>

            </div>

        </div>

    </div>
@endsection
@section('footer')
<script>
    $(document).on("click", ".selectPlan", function(){
        $('input[name="payment_gateway"]').click();
        $(".credit-wallet-package").removeClass("active");
        $(this).parent().find("input").click();
        $(this).parent().parent().addClass("active");
    });
</script>
@endsection
