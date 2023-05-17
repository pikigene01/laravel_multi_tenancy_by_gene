<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Facades\UtilityFacades;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\Plan;
use App\Models\User;
use App\Models\UserCoupon;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
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
        $planID    = \Illuminate\Support\Facades\Crypt::decrypt($request->plan_id);
        $payment = Paytm::with('receive');
        $authuser  = Auth::user();
        if ($authuser->type == 'Admin') {

            $plan   = tenancy()->central(function ($tenant) use ($planID) {
                return Plan::find($planID);
            });
            $res_data =  tenancy()->central(function ($tenant) use ($plan, $request, $authuser) {
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

                $data = Order::create([
                    'plan_id' => $plan->id,
                    'user_id' => $tenant->id,
                    'amount' => $price,
                    'discount_amount' => $discount_value,
                    'coupon_code' => $coupon_code,
                    'status' => 0,
                ]);
                $res_data['user_id'] = $tenant->id;
                $res_data['email'] = $authuser->email;
                $res_data['total_price'] = $price;
                $res_data['coupon']      = $coupon_id;
                $res_data['order_id'] = $data->id;
                return $res_data;
            });
        } else {
            $plan   =  Plan::find($planID);
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

            $data = Order::create([
                'plan_id' => $plan->id,
                'user_id' => $authuser->id,
                'amount' => $price,
                'discount_amount' => $discount_value,
                'coupon_code' => $coupon_code,
                'status' => 0,
            ]);
            $res_data['user_id'] = $authuser->id;
            $res_data['email'] = $authuser->email;
            $res_data['total_price'] = $price;
            $res_data['coupon']      = $coupon_id;
            $res_data['order_id'] = $data->id;
        }
        $payment->prepare([
            'order' => rand(),
            'user' => $res_data['user_id'],
            'mobile_number' => $request->mobile_number,
            'email' => $res_data['email'],
            'amount' =>  $res_data['total_price'], // amount will be paid in INR.
            'callback_url' => route('paypaytm.callback', ['coupon' => $res_data['coupon'], 'order_id' => $res_data['order_id']]) // callback URL
        ]);
        $response =  $payment->receive();  // initiate a new payment
        return $response;
    }

    public function paymentCallback(Request $request)
    {
        if (Auth::user()->type == 'Admin') {
            $order = tenancy()->central(function ($tenant) use ($request) {
                $transaction = Paytm::with('receive');
                $response = $transaction->response();
                $order_id = $request->order_id; // return a order id
                if ($transaction->isSuccessful()) {

                    $datas = Order::find($order_id);
                    $datas->status = 1;
                    $datas->payment_id = $transaction->getTransactionId();
                    $datas->payment_type = 'paytm';
                    $datas->update();
                    $coupons = Coupon::find($request->coupon);
                    $user = User::find($tenant->id);
                    if (!empty($coupons)) {
                        $userCoupon         = new UserCoupon();
                        $userCoupon->user   = $user->id;
                        $userCoupon->coupon = $coupons->id;
                        $userCoupon->order  = $datas->id;
                        $userCoupon->save();
                        $usedCoupun = $coupons->used_coupon();
                        if ($coupons->limit <= $usedCoupun) {
                            $coupons->is_active = 0;
                            $coupons->save();
                        }
                    }
                    $plan = Plan::find($datas->plan_id);
                    $user->plan_id = $plan->id;
                    if ($plan->durationtype == 'Month' && $plan->id != '1') {
                        $user->plan_expired_date = Carbon::now()->addMonths($plan->duration)->isoFormat('YYYY-MM-DD');
                    } elseif ($plan->durationtype == 'Year' && $plan->id != '1') {
                        $user->plan_expired_date = Carbon::now()->addYears($plan->duration)->isoFormat('YYYY-MM-DD');
                    } else {
                        $user->plan_expired_date = null;
                    }
                    $user->save();
                } else if ($transaction->isFailed()) {
                    $data = Order::find($order_id);
                    $data->status = 2;
                    $data->payment_id = $transaction->getTransactionId();
                    $data->payment_type = 'paytm';
                    $data->update();
                    return redirect()->route('plans.index')->with('errors', __('Transaction failed.'));
                } else {
                    return redirect()->route('plans.index')->with('warning', __('Transaction in prossesing.'));
                }
            });
        } else {
            $transaction = Paytm::with('receive');
            $response = $transaction->response();
            $order_id = $transaction->getOrderId();
            $order_id = $request->order_id;
            if ($transaction->isSuccessful()) {
                $datas = Order::find($order_id);
                $datas->status = 1;
                $datas->payment_id = $transaction->getTransactionId();
                $datas->payment_type = 'paytm';
                $datas->update();
                $user = User::find(Auth::user()->id);
                $coupons = Coupon::find($request->coupon);
                if (!empty($coupons)) {
                    $userCoupon         = new UserCoupon();
                    $userCoupon->user   = $user->id;
                    $userCoupon->coupon = $coupons->id;
                    $userCoupon->order  = $datas->id;
                    $userCoupon->save();
                    $usedCoupun = $coupons->used_coupon();
                    if ($coupons->limit <= $usedCoupun) {
                        $coupons->is_active = 0;
                        $coupons->save();
                    }
                }
                $plan = Plan::find($datas->plan_id);
                $user->plan_id = $plan->id;
                if ($plan->durationtype == 'Month' && $plan->id != '1') {
                    $user->plan_expired_date = Carbon::now()->addMonths($plan->duration)->isoFormat('YYYY-MM-DD');
                } elseif ($plan->durationtype == 'Year' && $plan->id != '1') {
                    $user->plan_expired_date = Carbon::now()->addYears($plan->duration)->isoFormat('YYYY-MM-DD');
                } else {
                    $user->plan_expired_date = null;
                }
                $user->save();
            } else if ($transaction->isFailed()) {
                $data = Order::find($order_id);
                $data->status = 2;
                $data->payment_id = $transaction->getTransactionId();
                $data->payment_type = 'paytm';
                $data->update();
                return redirect()->route('plans.index')->with('errors', __('Transaction failed.'));
            } else {
                return redirect()->route('plans.index')->with('warning', __('Transaction in prossesing.'));
            }
        }
        return redirect()->route('plans.index')->with('status', __('Payment successfully.'));
    }
}
