<?php


namespace Modules\User\Controllers;


use Illuminate\Http\Request;
use Modules\FrontendController;
use Modules\Booking\Models\Booking;
use Modules\Space\Models\Space;

class NormalCheckoutController extends FrontendController
{
    public function showInfo()
    {
        return view("Booking::frontend.normal-checkout.info");
    }
    public function confirmPayment(Request $request, $gateway)
    {
        $gateways = get_payment_gateways();
        if (empty($gateways[$gateway]) or !class_exists($gateways[$gateway])) {
            return $this->sendError(__("Payment gateway not found"));
        }
        $gatewayObj = new $gateways[$gateway]($gateway);
        if (!$gatewayObj->isAvailable()) {
            return $this->sendError(__("Payment gateway is not available"));
        }
        $res = $gatewayObj->confirmNormalPayment($request);

        $status = $res[0] ?? null;
        $message = $res[1] ?? null;
        $redirect_url = $res[2] ?? null;

        if (empty($redirect_url)) $redirect_url = route('user.wallet');

        return redirect()->to($redirect_url)->with($status ? "success" : "error", $message);
    }

    public function confirmExtendPayment(Request $request, $param) 
    {
        $param=explode("-", $param);
        $id=$param[0];
        $extendtime=$param[1];

        $booking = Booking::find($id);
        $gateway='two_checkout_gateway';
        $gateways = get_payment_gateways();
        if (empty($gateways[$gateway]) or !class_exists($gateways[$gateway])) {
            return $this->sendError(__("Payment gateway not found"));
        }
        $gatewayObj = new $gateways[$gateway]($gateway);
        if (!$gatewayObj->isAvailable()) {
            return $this->sendError(__("Payment gateway is not available"));
        }
        $res = $gatewayObj->confirmNormalPayment($request);
        $status = $res[0] ?? null;
        $message = $res[1] ?? null;
        $redirect_url = $res[2] ?? null;

         if ($extendtime=="1 Hour")
            {
                $booking->end_date = date("Y-m-d H:i:s", strtotime($booking->end_date.' +1 Hour '));
            }
            if ($extendtime=="2 Hours")
            {
                $booking->end_date = date("Y-m-d H:i:s", strtotime($booking->end_date.' +2 Hours '));
            }
            if ($extendtime=="3 Hours")
            {
                $booking->end_date = date("Y-m-d H:i:s", strtotime($booking->end_date.' +3 Hours '));
            }
            $booking->save();

        if(empty($redirect_url)) 
            { 
                $redirect_url = route('user.single.booking.detail',$id);
            }

        return redirect()->to($redirect_url)->with($status ? "success" : "error", $message);
    }

    public function cancelExtendPayment(Request $request, $id) 
    {

        $gateway='two_checkout_gateway';
        $gateways = get_payment_gateways();
        if (empty($gateways[$gateway]) or !class_exists($gateways[$gateway])) {
            return $this->sendError(__("Payment gateway not found"));
        }
        $gatewayObj = new $gateways[$gateway]($gateway);
        if (!$gatewayObj->isAvailable()) {
            return $this->sendError(__("Payment gateway is not available"));
        }
        $res =  $gatewayObj->cancelNormalPayment($request);
        $status = $res[0] ?? null;
        $message = $res[1] ?? null;
        $redirect_url = $res[2] ?? null;

        if (empty($redirect_url)) $redirect_url = route('user.single.booking.detail',$id);

        return redirect()->to($redirect_url)->with($status ? "success" : "error", $message);
    }

    public function sendError($message, $data = [])
    {
        return  redirect()->to(route('user.wallet'))->with('error', $message);
    }

    public function cancelPayment(Request $request, $gateway)
    {

        $gateways = get_payment_gateways();
        if (empty($gateways[$gateway]) or !class_exists($gateways[$gateway])) {
            return $this->sendError(__("Payment gateway not found"));
        }
        $gatewayObj = new $gateways[$gateway]($gateway);
        if (!$gatewayObj->isAvailable()) {
            return $this->sendError(__("Payment gateway is not available"));
        }
        $res =  $gatewayObj->cancelNormalPayment($request);
        $status = $res[0] ?? null;
        $message = $res[1] ?? null;
        $redirect_url = $res[2] ?? null;

        if (empty($redirect_url)) $redirect_url = route('user.wallet');

        return redirect()->to($redirect_url)->with($status ? "success" : "error", $message);
    }

    public function updateAmount(Request $request)
    {
        $amount = $request['amount'];
        $today = date("Ymd");
        $rand = rand(1, 10000);
        $orderId = $today . $rand;
        $updatedAmount = str_replace("$", "", format_money($amount));
        $token = "name205" . "CAD" . "2001" . date("Y-m-d") . $updatedAmount . $orderId;
        $hashedToken = hash('sha256', $token);
        $response = array('amount' => $updatedAmount, 'txnToken' => $hashedToken, 'orderId' => $orderId);
        return json_encode($response);
    }

    public function updateAmountSpace(Request $request)
    {
        $amount = $request['amount'];
        $orderId = $request['orderId'];
        $updatedAmount = str_replace("$", "", format_money($amount));
        $token = "name205" . "CAD" . "2001" . date("Y-m-d") . $updatedAmount . $orderId;
        $hashedToken = hash('sha256', $token);
        $response = array('amount' => $updatedAmount, 'txnToken' => $hashedToken, 'orderId' => $orderId);
        return json_encode($response);
    }

        public function updateExtendAmount(Request $request)
    {

        $id = $request->booking_id;
        $extendtime =$request->extendtime;
        $extendtime = substr($extendtime,0,1);
        $booking = Booking::find($id);
        $space = Space::find($booking->object_id);
        $extendprice=0;

        if (($space->hourly * $extendtime) > $space->daily)
        {
            $extendprice=$space->daily;
        }
        else
        {
            $extendprice=$space->hourly * $extendtime;
        }

        $amount =$extendprice;
        $today = date("Ymd");
        $rand = rand(1, 10000);
        $orderId = $today . $rand;
        $updatedAmount = str_replace("$", "", format_money($amount));
        $token = "name205" . "CAD" . "2001" . date("Y-m-d") . $updatedAmount . $orderId;
        $hashedToken = hash('sha256', $token);
        $successUrl=\App\Helpers\CodeHelper::withAppUrl('gateway/confirm/extend/two_checkout_gateway/'.$booking->id.'-'.$request->extendtime);
        $response = array('amount' => $updatedAmount, 'txnToken' => $hashedToken, 'orderId' => $orderId,'successUrl'=>$successUrl);
        return json_encode($response);
    }
}
