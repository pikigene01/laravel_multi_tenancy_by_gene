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

class FlutterwaveController extends Controller
{
    public function flutterwavepayment(Request $request)
    {
        $planID    = \Illuminate\Support\Facades\Crypt::decrypt($request->plan_id);
        $order = Order::find($request->order_id);
        $requestdomain = RequestDomain::find($order->domainrequest_id);
        $plan   =  Plan::find($planID);
        $coupon_id = 0;
        $coupon_code = null;
        $discount_value = null;
        $price  = $plan->price;
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
        $res_data['email']       = $requestdomain->email;
        $res_data['currency']       = UtilityFacades::getsettings('currency');
        $res_data['domainrequest_id'] = $requestdomain->id;
        $res_data['order_id'] = $order->id;
        $res_data['total_price'] = $price;
        $res_data['coupon']      = $coupon_id;
        $res_data['plan_name']      = $plan->name;
        $res_data['plan_id'] = $plan->id;
        return $res_data;
    }

    public function flutterwavecallback(Request $request, $order_id, $transaction_id, $requestdomain_id, $coupon_id)
    {
        $data = Order::find($order_id);
        $data->status = 1;
        $data->payment_id = $transaction_id;
        $data->payment_type = 'flutterwave';
        $data->update();
        $coupons = Coupon::find($coupon_id);
        $requestdomain =  RequestDomain::find($requestdomain_id);
        if (!empty($coupons)) {
            $userCoupon         = new UserCoupon();
            $userCoupon->domainrequest   = $requestdomain->id;
            $userCoupon->coupon = $coupons->id;
            $userCoupon->order  = $data->id;
            $userCoupon->save();
            $usedCoupun = $coupons->used_coupon();
            if ($coupons->limit <= $usedCoupun) {
                $coupons->is_active = 0;
                $coupons->save();
            }
        }
        if (UtilityFacades::getsettings('approve_type') == 'Auto') {
            UtilityFacades::approved_request($data);
        }
        return redirect()->route('landingpage')->with('status', __('Thanks for registration, Your account is in review and you get email when your account active.'));
    }
}
