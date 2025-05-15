<?php
if($transaction!=null){
    $refId = $transaction->getRefId();
    switch($transaction->type){
        case \App\Helpers\Constants::TRANSACTION_TYPE_REFERRAL:
        // $user = \Modules\Booking\Models\User::where('id', $refId)->first();
        // if($user!=null)
        // {
        ?>
        <table class="table demo-table-search table-responsive-block data-table table-two-info">
            <tr>
                <th>Info</th>
                <td>Bonus received on someone signup using your referral code</td>
            </tr>
            <tr>
                <th>User Registered On</th>
                <td>{{ \App\Helpers\CodeHelper::formatDateTime($transaction->created_at) }}</td>
            </tr>
        </table>
      <?php
        // }
        break;

        case \App\Helpers\Constants::TRANSACTION_TYPE_REFERRAL_PROMO:
        // $user = \Modules\Booking\Models\User::where('id', $refId)->first();
        // if($user!=null)
        // {
        ?>
        <table class="table demo-table-search table-responsive-block data-table table-two-info">
            <tr>
                <th>Info</th>
                <td>Bonus received on using coupon while signup</td>
            </tr>
            <tr>
                <th>Registered On</th>
                <td>{{ \App\Helpers\CodeHelper::formatDateTime($transaction->created_at) }}</td>
            </tr>
        </table>
      <?php
        // }
        break;

        case \App\Helpers\Constants::TRANSACTION_TYPE_EARNINGS:
        $booking = \Modules\Booking\Models\Booking::where('id', $refId)->first();
        if($booking!=null)
        {
        ?>
        <table class="table demo-table-search table-responsive-block data-table table-two-info">
            <tr>
                <th>Booking Reference</th>
                <td><a style="color: #1B47CC !important;" class="n-link" href="{{ route('user.single.booking.detail', $booking->id) }}">#{{ $booking->id }}</a></td>
            </tr>
            <tr>
                <th>Booking Date</th>
                <td>{{ \App\Helpers\CodeHelper::formatDateTime($booking->start_date) }}</td>
            </tr>
            <tr>
                <th>Gross Sale Amount</th>
                <td>{{ \App\Helpers\CodeHelper::formatPrice($booking->price) }}</td>
            </tr>
            <tr>
                <th>Invoice Total</th>
                <td>{{ \App\Helpers\CodeHelper::formatPrice($booking->payable_amount) }}</td>
            </tr>
            <tr>
                <th>Less: Site Fees</th>
                <td style="color: #E53D12 !important;">({{ \App\Helpers\CodeHelper::formatPrice($booking->admin_amount) }})</td>
            </tr>
            <tr>
                <th>Host Revenue</th>
                <td style="font-weight: 600 !important;">{{ \App\Helpers\CodeHelper::formatPrice($booking->host_amount) }}</td>
            </tr>
            <tr>
                <th>Transaction / Reference ID</th>
                <td>#{{ $transaction->id }}</td>
            </tr>
        </table>
      <?php
        }
        break;
        case \App\Helpers\Constants::TRANSACTION_TYPE_DEPOSIT:
        ?>
         <table class="table demo-table-search table-responsive-block data-table table-two-info">
            <tr>
                <th>ID#</th>
                <td>{{ $transaction->id }}</td>
            </tr> 
            <tr>
                <th>Trans. Date / Time</th>
                <td>{{ display_datetime($transaction->created_at)}}</td>
            </tr>
            <tr>
                <th>Credits Deposited</th>
                <td>{{\App\Helpers\CodeHelper::formatPrice($transaction->amount)}}</td>
            </tr>
            <tr>
                <th>Status</th>
                <td>{{($transaction->confirmed)?'CONFIRMED':'PENDING'}}</td> 
            </tr>
        </table>
        <?php 
         if(count($details)>0)
         {
        ?>
        <table class="table demo-table-search table-responsive-block data-table table-two-info"> 
        <tr>
            <thead>
            <th>Reference#</th>
            <th>Type</th>
            <th>Method</th>
            <th>Amount</th>
            <th>Status</th>
            </thead>
            <tbody>
                <?php 
                foreach ($details as $key => $value) 
                {  ?>
                <tr>
                    <td>#{{$value->reference_id}}</td>
                    <td>{{$value->type}}</td>
                    <td>{{strtoupper($value->meta)}}</td> 
                    <td>{{\App\Helpers\CodeHelper::formatPrice($value->amount)}}</td>
                    <td>{{($value->confirmed)?'COMPLETE':'PENDING'}}</td>
                </tr>
                <?php 
                }
                ?>

            </tbody>
        </tr>
        </table>
      <?php
        }
        break;
        case \App\Helpers\Constants::TRANSACTION_TYPE_SPENDING:
        $booking = \Modules\Booking\Models\Booking::where('id', $transaction->booking_id)->first();
        if($booking!=null){
        ?>
        <table class="table demo-table-search table-responsive-block data-table table-two-info">
            <tr>
                <th>ID#</th>
                <td><a class="n-link" href="{{ route('user.single.booking.detail', $booking->id) }}">#{{ $booking->id }}</a></td>
            </tr>
            <tr>
                <th>Booking Date</th>
                <td>{{ \App\Helpers\CodeHelper::formatDateTime($booking->start_date) }}</td>
            </tr>
            <tr>
                <th>Paid Amount</th>
                <td>{{ \App\Helpers\CodeHelper::formatPrice($booking->payable_amount) }}</td>
            </tr>
           
            <tr>
                <th>Reference#</th>
                <td>{{ $transaction->id }}</td>
            </tr> 
          
            <?php  
           
            $paidAmount= \App\Helpers\CodeHelper::getPaidAmount($transaction->payment_id);
            $due_amount= $booking->payable_amount-$paidAmount;

            if($due_amount>0)
            { 
             ?>
            <tr>
            <th>Due Amount</th>
            <td><span style="color:red;">{{ \App\Helpers\CodeHelper::formatPrice($due_amount) }}</span> &nbsp;<a href="{{ route('user.wallet.due.pay', $booking->id) }}">Please Pay your Due amount </a></td>
            </tr>
            <?php } ?>
        </table>
   <?php
        }
        ?>
      <table class="table demo-table-search table-responsive-block data-table table-two-info"> 
      <tr>
        <thead>
        <th>Reference#</th>
        <th>Type</th>
        <th>Method</th>
        <th>Amount</th>
        <th>Status</th>
        </thead>
        <tbody>
            <?php 
            $details = \App\Models\UserTransactionDetails::where('payment_id', $transaction->payment_id)->get();
            if($details){
             foreach ($details as $key => $value) { 
               if($value->type!='single')
               {
              ?>
             <tr>
                <td>#{{$value->reference_id}}</td>
                <td>{{strtoupper($value->type)}}</td>
                <td>{{strtoupper($value->meta)}}</td> 
                <td>{{\App\Helpers\CodeHelper::formatPrice($value->amount)}}</td>
                <td>{{($value->confirmed)?'COMPLETE':'PENDING'}}</td>
            </tr>
            <?php 
                  }
                    }

                }
            ?>

        </tbody>
      </tr>
      </table>
      <?php
        break;
        case \App\Helpers\Constants::TRANSACTION_TYPE_WITHDRAWAL:
        $withdrawal = \Modules\Vendor\Models\VendorPayout::where('id', $refId)->first();
        if($withdrawal!=null){
        ?>
<table class="table demo-table-search table-responsive-block data-table table-two-info">
    <tr>
        <th>Status</th>
        <td><span class="badge badge-warning">{{ ucwords($withdrawal->status) }}</span></td>
    </tr>
    <tr>
        <th>Amount</th>
        <td>{{ \App\Helpers\CodeHelper::formatPrice($withdrawal->amount) }}</td>
    </tr>
    <tr>
        <th>Date of Request</th>
        <td>{{ \App\Helpers\CodeHelper::formatDateTime($withdrawal->created_at) }}</td>
    </tr>
    <tr>
        <th>Date of Completion</th>
        <td>{{ \App\Helpers\CodeHelper::formatDateTime($withdrawal->pay_date, true, 'Not Completed Yet') }}</td>
    </tr>
    <tr>
        <th>Deposit Account</th>
        <td>{{ $withdrawal->account_info }}</td>
    </tr>
    <tr>
        <th>Fee</th>
        <td>{{ \App\Helpers\CodeHelper::formatPrice($withdrawal->fee) }}</td>
    </tr>
    <tr>
        <th>Transaction / Reference ID</th>
        <td>#{{ $transaction->id }}</td>
    </tr>
</table>
<?php
        }
        break;
        case \App\Helpers\Constants::TRANSACTION_TYPE_WITHDRAWAL_CANCELLED:
        break;
        case \App\Helpers\Constants::TRANSACTION_TYPE_PROMO:
        case \App\Helpers\Constants::TRANSACTION_TYPE_REFUND:
        ?>
<table class="table demo-table-search table-responsive-block data-table table-two-info">
    <tr>
        <th>Type</th>
        <td>{{ ucfirst($transaction->type) }}</td>
    </tr>
    <tr>
        <th>Amount</th>
        <td>{{ \App\Helpers\CodeHelper::formatPrice($transaction->amount) }}</td>
    </tr>
    <?php
        $creditCouponModel = \App\Models\CreditCoupons::where('id', $refId)->first();
        if($creditCouponModel!=null){
        $booking = \Modules\Booking\Models\Booking::where('id', $creditCouponModel->object_id)->first();
        if($booking!=null){
        ?>

<?php
$space = \Modules\Space\Models\Space::where('id', $booking->object_id)->first();
if($space!=null){
    ?>
     <tr>
        <th>Space</th>
        <td>{{ ($space->title) }}</td>
    </tr>
    <?php
}
?>

<tr>
    <th>Transaction Date</th>
    <td>{{ \App\Helpers\CodeHelper::formatDateTime($transaction->created_at) }}</td>
</tr>

    <tr>
        <th>Booking#</th>
        <td><a class="n-link" href="{{ route('user.single.booking.detail', $booking->id) }}">#{{ $booking->id }}</a>
        </td>
    </tr>
    <?php }  }?>
</table>
<?php
        
        break;
    }
    ?>
<?php
}else{
    ?>
<p>No Transaction Found</p>
<?php
}
?>
