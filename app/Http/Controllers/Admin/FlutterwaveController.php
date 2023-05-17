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
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class FlutterwaveController extends Controller
{
    public function flutterwavepayment(Request $request)
    {
        $planID    = \Illuminate\Support\Facades\Crypt::decrypt($request->plan_id);
        $authuser  = \Auth::user();
        if (Auth::user()->type == 'Admin') {
            $plan   = tenancy()->central(function ($tenant) use ($planID) {
                return Plan::find($planID);
            });
            $coupouns =  tenancy()->central(function ($tenant) use ($plan, $request, $authuser) {
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
                Order::create([
                    'plan_id' => $plan->id,
                    'user_id' => $tenant->id,
                    'amount' => $price,
                    'discount_amount' => $discount_value,
                    'coupon_code' => $coupon_code,
                    'status' => 0,
                ]);
                $res_data['email']       = $authuser->email;
                $res_data['plan_name'] = $plan->name;
                $res_data['total_price'] = $price;
                $res_data['currency_symbol']    = UtilityFacades::getsettings('currency_symbol');
                $res_data['currency']    = UtilityFacades::getsettings('currency');
                $res_data['coupon']      = $coupon_id;
                $res_data['plan_id'] = $plan->id;
                return $res_data;
            });
            return $coupouns;
        } else {
            $plan  = Plan::find($planID);
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
            Order::create([
                'plan_id' => $plan->id,
                'user_id' => $authuser->id,
                'amount' => $price,
                'discount_amount' => $discount_value,
                'coupon_code' => $coupon_code,
                'status' => 0,
            ]);
            $res_data['email']       = $authuser->email;
            $res_data['plan_name'] = $plan->name;
            $res_data['total_price'] = $price;
            $res_data['currency_symbol']    = UtilityFacades::getsettings('currency_symbol');
            $res_data['currency']    = UtilityFacades::getsettings('currency');
            $res_data['coupon']      = $coupon_id;
            $res_data['plan_id'] = $plan->id;
            return $res_data;
        }
    }
    public function Flutterwavecallback(Request $request, $transaction_id, $coupon_id, $plan_id)
    {
        $planID    = $plan_id;

        if (Auth::user()->type == 'Admin') {
            $order = tenancy()->central(function ($tenant) use ($transaction_id, $coupon_id, $planID) {

                $datas = Order::orderBy('id', 'desc')->first();
                $datas->status = 1;
                $datas->payment_id = $transaction_id;
                $datas->payment_type = 'flutterwave';
                $datas->update();
                $coupons = Coupon::find($coupon_id);

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
                $plan = Plan::find($planID);
                $user->plan_id = $plan->id;
                if ($plan->durationtype == 'Month' && $plan->id != '1') {
                    $user->plan_expired_date = Carbon::now()->addMonths($plan->duration)->isoFormat('YYYY-MM-DD');
                } elseif ($plan->durationtype == 'Year' && $plan->id != '1') {
                    $user->plan_expired_date = Carbon::now()->addYears($plan->duration)->isoFormat('YYYY-MM-DD');
                } else {
                    $user->plan_expired_date = null;
                }
                $user->save();
            });
        } else {
            $datas = Order::orderBy('id', 'desc')->first();
            $datas->status = 1;
            $datas->payment_id = $transaction_id;
            $datas->payment_type = 'flutterwave';
            $datas->update();
            $user = User::find(Auth::user()->id);
            $coupons = Coupon::find($coupon_id);
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
            $plan = Plan::find($request->plan_id);
            $user->plan_id = $plan->id;
            if ($plan->durationtype == 'Month' && $plan->id != '1') {
                $user->plan_expired_date = Carbon::now()->addMonths($plan->duration)->isoFormat('YYYY-MM-DD');
            } elseif ($plan->durationtype == 'Year' && $plan->id != '1') {
                $user->plan_expired_date = Carbon::now()->addYears($plan->duration)->isoFormat('YYYY-MM-DD');
            } else {
                $user->plan_expired_date = null;
            }
            $user->save();
        }

        return redirect()->route('plans.index')->with('status', __('Payment successfully.'));
    }
}
