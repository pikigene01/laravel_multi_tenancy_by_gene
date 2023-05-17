<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Facades\UtilityFacades;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\Plan;
use App\Models\RequestDomain;
use App\Models\UserCoupon;
use Illuminate\Support\Facades\Validator;
use Paytm\JsCheckout\Facades\Paytm;

class PaytmController extends Controller
{
    public function pay(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'mobile_number' => 'required|numeric|digits:10',
            ]
        );
        if ($validator->fails()) {
            $messages = $validator->getMessageBag();
            $errors['errors'] = $messages->first();
            return $errors;
        }

        $payment = Paytm::with('receive');
        $planID    = \Illuminate\Support\Facades\Crypt::decrypt($request->plan_id);
        $plan   = tenancy()->central(function ($tenant) use ($planID) {
            return Plan::find($planID);
        });

        $res_data =  tenancy()->central(function ($tenant) use ($plan, $request) {
            $order = Order::find($request->order_id);
            $requestdomain = RequestDomain::find($order->domainrequest_id);
            $coupon_id = '0';
            $coupon_code = null;
            $discount_value = null;
            $price = $plan->price;
            $coupons = Coupon::where('code', $request->coupon)->where('is_active', '1')->first();
            if ($coupons) {
                $coupon_code = $coupons->code;
                $usedCoupun     = $coupons->used_coupon();
                if ($coupons->limit == $usedCoupun) {
                    $res_data['error'] = __('This coupon code has expired.');
                } else {
                    $discount = $coupons->discount;
                    $discount_type = $coupons->discount_type;
                    $discount_value =  UtilityFacades::calculateDiscount($price, $discount, $discount_type);
                    $price          = $price - $discount_value;
                    if ($price < 0) {
                        $price = $plan->price;
                    }
                    $coupon_id = $coupons->id;
                }
            }
            $order->plan_id = $plan->id;
            $order->domainrequest_id = $requestdomain->id;
            $order->amount = $price;
            $order->discount_amount = $discount_value;
            $order->coupon_code = $coupon_code;
            $order->status = 0;
            $order->save();
            $res_data['user'] = $requestdomain->id;
            $res_data['email'] = $requestdomain->email;
            $res_data['total_price'] = $price;
            $res_data['coupon']      = $coupon_id;
            $res_data['order_id'] = $order->id;
            return $res_data;
        });
        $payment->prepare([
            'order' => rand(),
            'user' => $res_data['user'],
            'mobile_number' => $request->mobile_number,
            'email' => $res_data['email'],
            'amount' =>  $res_data['total_price'], // amount will be paid in INR.
            'callback_url' => route('paytm.callback', ['coupon' => $res_data['coupon'], 'order_id' => $res_data['order_id'], 'requestdomain_id' => $res_data['user']]) // callback URL
        ]);
        $response =  $payment->receive();  // initiate a new payment
        return $response;
    }

    public function paymentCallback(Request $request)
    {
        $transaction = Paytm::with('receive');
        $response = $transaction->response();
        $order_id = $request->order_id; // return a order id
        if ($transaction->isSuccessful()) {
            $data = Order::find($order_id);
            $data->status = 1;
            $data->payment_id = $transaction->getTransactionId();
            $data->payment_type = 'paytm';
            $data->update();
            $coupons = Coupon::find($request->coupon);
            if (!empty($coupons)) {
                $userCoupon         = new UserCoupon();
                $userCoupon->domainrequest   = $data->domainrequest_id;
                $userCoupon->coupon = $coupons->id;
                $userCoupon->order  = $order_id;
                $userCoupon->save();
                $usedCoupun = $coupons->used_coupon();
                if ($coupons->limit <= $usedCoupun) {
                    $coupons->is_active = 0;
                    $coupons->save();
                }
            }
        } else if ($transaction->isFailed()) {
            $data = Order::find($order_id);
            $data->status = 2;
            $data->payment_id = $transaction->getTransactionId();
            $data->payment_type = 'paytm';
            $data->update();
            return redirect()->route('landingpage')->with('errors', __('Transaction failed.'));
        } else {
            return redirect()->route('landingpage')->with('warning', __('Transaction in prossesing.'));
        }
        if (UtilityFacades::getsettings('approve_type') == 'Auto') {
            UtilityFacades::approved_request($data);
        }
        return redirect()->route('landingpage')->with('status', __('Thanks for registration, Your account is in review and you get email when your account active.'));
    }
}
