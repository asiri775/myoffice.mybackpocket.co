<?php

namespace Modules\Coupon\Controllers;

use App\Helpers\CodeHelper;
use App\Helpers\Constants;
use App\Http\Controllers\Controller;
use App\Models\CreditCoupons;
use Illuminate\Http\Request;
use Modules\Booking\Models\Booking;
use Modules\Coupon\Models\Coupon;
use Modules\Space\Models\Space;

class CouponController extends Controller
{
    public function __construct()
    {
    }

    public function applyCoupon($code, Request $request)
    {

        $validator = \Validator::make($request->all(), [
            'coupon_code' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first());
        }

        $booking = Booking::where('code', $code)->first();
        if (!empty($booking) and !in_array($booking->status, ['draft', 'unpaid'])) {
            return $this->sendError(__("Booking not found!"));
        }

        //find credit coupon
        CodeHelper::deleteOldUnUsedCoupon();
        if (auth()->check()) {
            //find credit coupon
            $userId = auth()->user()->id;
            if ($userId != null) {
                $creditCoupon = CreditCoupons::where('code', $request->input('coupon_code'))
                    ->where('user_id', $userId)
                    ->where(function ($query) {
                        $query->where('expired_at', '>=', date('Y-m-d'))
                            ->orWhereNull('expired_at'); 
                    })->first();
                if ($creditCoupon != null) {
                    if ($creditCoupon->object_model === "booking") {
                        $bookingModel = Booking::where('id', $creditCoupon->object_id)->first();
                        if ($bookingModel != null) {
                            if ($bookingModel->vendor_id == $booking->vendor_id) {
                                $model = new Coupon();
                                $model->code = $creditCoupon->code;
                                $model->amount = $creditCoupon->pending;
                                $model->discount_type = 'fixed';
                                $model->only_for_user = $userId;
                                $model->usage = 'credit_coupon';
                                $model->status = 'publish';
                                $model->end_date = date(Constants::PHP_DATE_FORMAT, strtotime($creditCoupon->expired_at));
                                $model->save();
                            }else{
                                return $this->sendError(__("This Code is only applicable to Spaces owned by the Host who issued this code."), [
                                    'type' => 'invalid_space',
                                    'vendor' => $bookingModel->vendor_id
                                ]);
                            }
                        }
                    } else {
                        $model = new Coupon();
                        $model->code = $creditCoupon->code;
                        $model->amount = $creditCoupon->pending;
                        $model->discount_type = 'fixed';
                        $model->only_for_user = $userId;
                        $model->usage = 'credit_coupon';
                        $model->status = 'publish';
                        $model->end_date = date(Constants::PHP_DATE_FORMAT, strtotime($creditCoupon->expired_at));
                        $model->save();
                    }
                }
            }
        }

        $coupon = Coupon::where('code', $request->input('coupon_code'))
            ->where(function ($query) {
                $query->where('end_date', '>=', date('Y-m-d') . ' 00:00:00')
                    ->orWhereNull('end_date');
            })
            ->where("status", "publish")->first();
        if (empty($coupon)) {
            return $this->sendError(__("Invalid coupon code!"));
        }

        $res = $coupon->applyCoupon($booking, 'add');
        if ($res['status'] == 1) {
            $res['reload'] = 1;
        }
        return $this->sendSuccess($res);
    }

    public function removeCoupon($code, Request $request)
    {
        $coupon = Coupon::where('code', $request->input('coupon_code'))->where("status", "publish")->first();
        if (empty($coupon)) {
            return $this->sendError(__("Invalid coupon code!"));
        }
        $booking = Booking::where('code', $code)->first();
        if (!empty($booking) and !in_array($booking->status, ['draft', 'unpaid'])) {
            return $this->sendError(__("Booking not found!"));
        }
        $res = $coupon->applyCoupon($booking, 'remove');
        if ($res['status'] == 1) {
            $res['reload'] = 1;
        }
        return $this->sendSuccess($res);
    }
}
