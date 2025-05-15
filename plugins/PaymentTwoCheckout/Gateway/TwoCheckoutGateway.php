<?php

namespace Plugins\PaymentTwoCheckout\Gateway;

use App\Helpers\Constants;
use Illuminate\Http\Request;
use Mockery\Exception;
use Modules\Booking\Models\Payment;
use Validator;
use Illuminate\Support\Facades\Log;
use Modules\Booking\Models\Booking;
use Illuminate\Support\Facades\Session;
use Bavix\Wallet\Models\Wallet;
use Illuminate\Support\Facades\Auth;
use App\Models\UserTransaction;
use Illuminate\Support\Str;
use App\Helpers\CodeHelper;
use Modules\Booking\Events\BookingCreatedEvent;

class TwoCheckoutGateway extends \Modules\Booking\Gateways\BaseGateway
{
    protected $id   = 'two_checkout_gateway';
    public    $name = 'Two Checkout';
    protected $gateway;

    public function getOptionsConfigs()
    {
        return [
            [
                'type'  => 'checkbox',
                'id'    => 'enable',
                'label' => __('Enable Two Checkout?')
            ],
            [
                'type'  => 'input',
                'id'    => 'name',
                'label' => __('Custom Name'),
                'std'   => __("Two Checkout"),
                'multi_lang' => "1"
            ],
            [
                'type'  => 'upload',
                'id'    => 'logo_id',
                'label' => __('Custom Logo'),
            ],
            [
                'type'  => 'editor',
                'id'    => 'html',
                'label' => __('Custom HTML Description'),
                'multi_lang' => "1"
            ],
            [
                'type'  => 'input',
                'id'    => 'twocheckout_account_number',
                'label' => __('Account Number'),
            ],
            [
                'type'  => 'input',
                'id'    => 'twocheckout_secret_word',
                'label' => __('Secret Word'),
            ],
            [
                'type'  => 'checkbox',
                'id'    => 'twocheckout_enable_sandbox',
                'label' => __('Enable Sandbox Mode'),
            ]
        ];
    }

    public function process(Request $request, $booking, $service, $returnVal = false)
    {
        $user = Auth::user();
        if (in_array($booking->payment_status, [
            Constants::PAYMENT_PAID
        ])) {

            throw new Exception(__("Booking status does not need to be paid"));
        }
        if (!$booking->total) {
            throw new Exception(__("Booking total is zero. Can not process payment gateway!"));
        }
        $payment = new Payment();
        $payment->booking_id = $booking->id;
        $payment->payment_gateway = $this->id;
        $payment->status = Constants::PAYMENT_UNPAID;
        $payment->save();
        $booking->payment_id = $payment->id;
        $booking->save();

       CodeHelper::addUserTransactionExtend(
        $user,
            Constants::TRANSACTION_TYPE_SPENDING,
            $booking->total,
            Constants::DEBIT,
            "BOOKING_SPENDING-" . $payment->booking_id,
            '',
            '',
            false,
            false,
            $payment->id,
            $booking->id
            
        );

        $data = $this->handlePurchaseData([], $booking, $request);  


        if ($this->getOption('twocheckout_enable_sandbox')) {
            $checkout_url_sandbox = 'https://checkout.backpocket.ca/backpocket-payment/sandbox';
        } else {
            $checkout_url_sandbox = 'https://checkout.backpocket.ca/backpocket-payment/live';
        }
        $twoco_args = http_build_query($data, '', '&');

        if($returnVal){
            return $checkout_url_sandbox . "?" . $twoco_args;
        }

        response()->json([
            'url' => $checkout_url_sandbox . "?" . $twoco_args
        ])->send();
    }

    public function processNormal($payment)
    {
        $payment->payment_gateway = $this->id;
        // $payment->status = 'draft';
        // $payment->save();

        $data = $this->handlePurchaseDataNormal($payment, \request());
        if ($this->getOption('twocheckout_enable_sandbox')) {
            $checkout_url_sandbox = 'https://checkout.backpocket.ca/backpocket-payment/sandbox';
        } else {
            $checkout_url_sandbox = 'https://checkout.backpocket.ca/backpocket-payment/live';
        }
        $twoco_args = http_build_query($data, '', '&');

        return [true, '', $checkout_url_sandbox . "?" . $twoco_args];
    }

        public function processNormalCrediBuy($payment)
    {
        $payment->payment_gateway = $this->id;
        // $payment->status = 'draft';
        // $payment->save();

        $data = $this->handlePurchaseDataNormal($payment, \request());
        if ($this->getOption('twocheckout_enable_sandbox')) {
            $checkout_url_sandbox = 'https://checkout.backpocket.ca/backpocket-payment/sandbox-credit';
        } else {
            $checkout_url_sandbox = 'https://checkout.backpocket.ca/backpocket-payment/live'; 
        }
        $twoco_args = http_build_query($data, '', '&');

        return [true, '', $checkout_url_sandbox . "?" . $twoco_args];
    }

    public function handlePurchaseData($data, $booking, $request)
    {
        $twocheckout_args = array();
        $twocheckout_args['sid'] = $booking->payment_id;
        $twocheckout_args['paypal_direct'] = 'Y';
        $twocheckout_args['cart_order_id'] = $booking->code;
        $twocheckout_args['merchant_order_id'] = $booking->code;
        $twocheckout_args['total'] = (float)$booking->pay_now;
        $twocheckout_args['credits'] = $request->input("credits");
        $twocheckout_args['return_url'] = $this->getCancelUrl() . '?c=' . $booking->code;
        $twocheckout_args['x_receipt_link_url'] = $this->getReturnUrl() . '?c=' . $booking->code;
        $twocheckout_args['currency_code'] = setting_item('currency_main');
        $twocheckout_args['card_holder_name'] = $request->input("first_name") . ' ' . $request->input("last_name");
        $twocheckout_args['street_address'] = $request->input("address_line_1");
        $twocheckout_args['street_address2'] = $request->input("address_line_1");
        $twocheckout_args['city'] = $request->input("city");
        $twocheckout_args['state'] = $request->input("state");
        $twocheckout_args['country'] = $request->input("country");
        $twocheckout_args['zip'] = $request->input("zip_code");
        $twocheckout_args['phone'] = "";
        $twocheckout_args['email'] = $request->input("email");
        $twocheckout_args['lang'] = app()->getLocale();
        $twocheckout_args['merchantPgIdentifier'] = $request->input("merchantPgIdentifier");
        $twocheckout_args['secret_id'] = $request->input("secret_id");
        $twocheckout_args['currency'] = $request->input("currency");
        $twocheckout_args['amount'] = $request->input("amount");
        $twocheckout_args['orderId'] = $booking->id;
        $twocheckout_args['invoiceNumber'] = $booking->id;
        $twocheckout_args['successUrl'] = $request->input("successUrl");
        $twocheckout_args['errorUrl'] = $request->input("errorUrl");
        $twocheckout_args['storeName'] = $request->input("storeName");
        $twocheckout_args['transactionType'] = $request->input("transactionType");
        $twocheckout_args['timeout'] = $request->input("timeout");
        $twocheckout_args['transactionDateTime'] = $request->input("transactionDateTime");
        $twocheckout_args['language'] = $request->input("language");
        $twocheckout_args['txnToken'] = $request->input("txnToken");
        $twocheckout_args['itemList'] = $request->input("itemList");
        $twocheckout_args['otherInfo'] = $request->input("otherInfo");
        $twocheckout_args['merchantCustomerPhone'] = $request->input("merchantCustomerPhone");
        $twocheckout_args['merchantCustomerEmail'] = $request->input("merchantCustomerEmail");
        $twocheckout_args['customer_id'] = $request->input("customer_id");

        return $twocheckout_args;
    }
    public function handlePurchaseDataNormal($payment, $request)
    {
        $twocheckout_args = array();
        $twocheckout_args['sid'] = $payment->id;
        $twocheckout_args['paypal_direct'] = 'Y';
        $twocheckout_args['cart_order_id'] = $payment->id;
        $twocheckout_args['merchant_order_id'] = $payment->id;
        $twocheckout_args['total'] = (float)$payment->amount;
        $twocheckout_args['credits'] = $request->input("credits");
        $twocheckout_args['return_url'] = $this->getCancelUrl(true) . '?pid=' . $payment->id;
        $twocheckout_args['x_receipt_link_url'] = $this->getReturnUrl(true) . '?pid=' . $payment->id;
        $twocheckout_args['currency_code'] = setting_item('currency_main');
        $twocheckout_args['lang'] = app()->getLocale();
        $twocheckout_args['merchantPgIdentifier'] = $request->input("merchantPgIdentifier");
        $twocheckout_args['secret_id'] = $request->input("secret_id");
        $twocheckout_args['currency'] = $request->input("currency");
        $twocheckout_args['amount'] = $request->input("amount");
        $twocheckout_args['orderId'] = $request->input("orderId");
        $twocheckout_args['invoiceNumber'] = $request->input("invoiceNumber");
        $twocheckout_args['successUrl'] = $request->input("successUrl");
        $twocheckout_args['errorUrl'] = $request->input("errorUrl");
        $twocheckout_args['storeName'] = $request->input("storeName");
        $twocheckout_args['transactionType'] = $request->input("transactionType");
        $twocheckout_args['timeout'] = $request->input("timeout");
        $twocheckout_args['transactionDateTime'] = $request->input("transactionDateTime");
        $twocheckout_args['language'] = $request->input("language");
        $twocheckout_args['txnToken'] = $request->input("txnToken");
        $twocheckout_args['itemList'] = $request->input("itemList");
        $twocheckout_args['otherInfo'] = $request->input("otherInfo");
        $twocheckout_args['merchantCustomerPhone'] = $request->input("merchantCustomerPhone");
        $twocheckout_args['merchantCustomerEmail'] = $request->input("merchantCustomerEmail");
        $twocheckout_args['customer_id'] = $request->input("customer_id");
        // echo '<pre>'; 
        // print_r( $twocheckout_args);die;
        return $twocheckout_args;
    }


    public function getDisplayHtml()
    {
        return $this->getOption('html', '');
    }

    public function confirmPayment(Request $request)
    {
        $user = Auth::user();
        $booking_id = $request->input('invoice_number');
        $booking = Booking::where('id', $booking_id)->first();

        $platform =  Session::get('platform');

        if (!empty($booking) and in_array($booking->payment_status, [Constants::PAYMENT_UNPAID])) {
            $compare_hash1 = "name205CAD2001" . date("Y-m-d") . number_format((float)$request->input('amount'), 2, '.', '') . $request->input('invoice_number');
            $compare_hash1 = hash('sha256', $compare_hash1);
            $compare_hash2 = $request->input("txnToken");


            if ($compare_hash1 != $compare_hash2) {

                $payment = $booking->payment;
                if ($payment) {
                    $payment->status = 'fail';
                    $payment->logs = \GuzzleHttp\json_encode($request->input());
                    $payment->save();
                }
                try {
                    $booking->markAsPaymentFailed();
                } catch (\Swift_TransportException $e) {
                    Log::warning($e->getMessage());
                }

                if($platform == 'mobile'){
                    return redirect(route('pwa.bookingList'))->with("error", __("Payment Failed"));
                }

                return redirect($booking->getDetailUrl())->with("error", __("Payment Failed"));
            } else {

                $payment = $booking->payment;
                if ($payment) {
                    $payment->status = 'completed';
                    $payment->logs = \GuzzleHttp\json_encode($request->input());
                    $payment->save();
                }
                try {
                    $booking->paid += (float)$booking->pay_now;
                    $booking->markAsPaid();
                    event(new BookingCreatedEvent($booking));
                    CodeHelper::addUserTransactionExtend(
                        $user,
                        Constants::TRANSACTION_TYPE_SPENDING,
                        $booking->total,
                        Constants::DEBIT,
                        'SPENDING_COMPLETED-'.$payment->id,
                        [],
                        [],
                        false,
                        true,
                        $payment->id,
                        $booking->id
                    );

                    $userTransaction = UserTransaction::where('payment_id',$payment->id)->where('confirmed',0)->first();
                    if ($userTransaction) {
                        // Delete the record
                        $userTransaction->delete();
                    }

                } catch (\Swift_TransportException $e) {
                    Log::warning($e->getMessage());
                }

                if($platform == 'mobile'){
                    return redirect(route('pwa.bookingList'))->with("success", __("You payment has been processed successfully"));
                }

                return redirect($booking->getDetailUrl())->with("success", __("You payment has been processed successfully"));
            }
        }
        if (!empty($booking)) 
        {
            if($platform == 'mobile'){
                return redirect(route('pwa.bookingList'));
            }
        } else {
            $redirect_url = route('user.single.booking.detail',$booking_id);
            return redirect($redirect_url)->with("success", __("You payment has been processed successfully")); 
        }
    }
    public function confirmNormalPayment()
    {
        /**
         * @var Payment $payment
         */
        $user = Auth::user();
        $request = \request();
        $c = $request->query('pid');
        
        $sid=$request->input("sid");
    
        $payment = Payment::where('id', $sid)->first();
          if (!empty($payment) and in_array($payment->status, ['draft'])) 
          {
            if ($request->input("response") == '00') 
            {

                $payment->pay_type = $request->input("response");
                $payment->referance = $request->input("reference_id");   
                $payment->pay_status = 'completed';
                $payment->pay_type=$request->input("pay_type");  
                $payment->status='completed';
                $payment->pay_type=$request->input("pay_type");
                $payment->amount=$request->input("amount");
                $payment->save();

                $wallet = Wallet::where('holder_type', 'App\User') ->where('holder_id', $user->id)->first();

                if(!empty($request->input("credits")))
                {
                    $wallet->balance=$request->input("credits"); 
                    $wallet->update();
                    
                    $userTransaction = UserTransaction::where('payment_id',$payment->id)->first();
                    if ($userTransaction) {
                        $userTransaction->confirmed=1;
                        $userTransaction->update();
                    }
                }

                
                if($payment->object_model='wallet_deposit')
                {
                    $wallet->balance=$wallet->balance+$payment->amount; 
                    $wallet->update();
                }

                return $payment->markAsCompletedCredit();
            } else {
                return $payment->markAsFailed();
            }
        }
        return [false];
    }

    public function cancelPayment(Request $request)
    {
        $c = $request->query('c');
        $booking = Booking::where('code', $c)->first();
        if (!empty($booking) and in_array($booking->status, [$booking::UNPAID])) {
            $payment = $booking->payment;
            if ($payment) {
                $payment->status = 'cancel';
                $payment->logs = \GuzzleHttp\json_encode([
                    'customer_cancel' => 1
                ]);
                $payment->save();

                // Refund without check status
                $booking->tryRefundToWallet(false);
            }
            return redirect($booking->getDetailUrl())->with("error", __("You cancelled the payment"));
        }
        if (!empty($booking)) {
            return redirect($booking->getDetailUrl());
        } else {
            return redirect(url('/'));
        }
    }
}
