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
use CoinGate\CoinGate;
use Illuminate\Support\Facades\Crypt;
use Inertia\Inertia;


class CoingateController extends Controller
{
    public function coingatePrepare(Request $request)
    {
        $planID    = \Illuminate\Support\Facades\Crypt::decrypt($request->plan_id);
        $authuser  = \Auth::user();
        if (Auth::user()->type == 'Admin') {
            $coingate_environment = tenancy()->central(function ($tenant) {
                return UtilityFacades::getsettings('coingate_environment');
            });
            $coingate_auth_token = tenancy()->central(function ($tenant) {
                return UtilityFacades::getsettings('coingate_auth_token');
            });
            $currency = tenancy()->central(function ($tenant) {
                return UtilityFacades::getsettings('currency');
            });
            $plan   = tenancy()->central(function ($tenant) use ($planID) {
                return Plan::find($planID);
            });
            $res_data =  tenancy()->central(function ($tenant) use ($plan, $request, $authuser) {
                $coupon_id = '0';
                $price = $plan->price;
                $coupon_code = null;
                $discount_value = null;
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
                        $price  = $price - $discount_value;
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
                $res_data['total_price'] = $price;
                $res_data['coupon']      = $coupon_id;
                $res_data['order_id'] = $data->id;
                return $res_data;
            });
        } else {
            $coingate_environment = UtilityFacades::getsettings('coingate_environment');
            $coingate_auth_token = UtilityFacades::getsettings('coingate_auth_token');
            $currency = UtilityFacades::getsettings('currency');
            $plan   =  Plan::find($planID);
            $coupon_id = '0';
            $price = $plan->price;
            $coupon_code = null;
            $discount_value = null;
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
                    $price    = $price - $discount_value;
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
            $res_data['total_price'] = $price;
            $res_data['coupon']      = $coupon_id;
            $res_data['order_id'] = $data->id;
        }
        CoinGate::config(
            array(
                'environment' => $coingate_environment,
                // sandbox OR live
                'auth_token' => $coingate_auth_token,
                'curlopt_ssl_verifypeer' => FALSE
                // default is false
            )
        );
        $params = array(
            'order_id' => rand(),
            'price_amount' => $res_data['total_price'],
            'price_currency' => $currency,
            'receive_currency' => $currency,
            'callback_url' => route('coingate.payment.callback', Crypt::encrypt(['order_id' => $res_data['order_id'], 'coupon_id' => $res_data['coupon'], 'plan_id' => $planID])),
            'cancel_url' => route('coingate.payment.callback', Crypt::encrypt(['order_id' => $res_data['order_id'], 'coupon_id' => $res_data['coupon'], 'plan_id' => $planID, 'status' => 'failed'])),
            'success_url' => route('coingate.payment.callback', Crypt::encrypt(['order_id' => $res_data['order_id'], 'coupon_id' => $res_data['coupon'], 'plan_id' => $planID, 'status' => 'successfull'])),
        );
        $order = \CoinGate\Merchant\Order::create($params);
        if ($order) {
            if (Auth::user()->type == 'Admin') {
                $central_order = tenancy()->central(function ($tenant) use ($order, $res_data) {
                    $payment_id = Order::find($res_data['order_id']);
                    $payment_id->payment_id = $order->id;
                    $payment_id->update();
                });
            } else {
                $payment_id = Order::find($res_data['order_id']);
                $payment_id->payment_id = $order->id;
                $payment_id->update();
            }
            return redirect($order->payment_url);
        } else {
            return redirect()->back()->with('error', __('Opps something went wrong.'));
        }
    }

    public function coingateCallback(Request $request, $data)
    {
        $data = Crypt::decrypt($data);

        if (Auth::user()->type == 'Admin') {
            $order = tenancy()->central(function ($tenant) use ($data) {
                if ($data['status'] == 'successfull') {
                    $datas = Order::find($data['order_id']);
                    $datas->status = 1;
                    $datas->payment_type = 'coingate';
                    $datas->update();

                    $coupons = Coupon::find($data['coupon_id']);

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
                    $plan = Plan::find($data['plan_id']);

                    $user->plan_id = $plan->id;
                    if ($plan->durationtype == 'Month' && $plan->id != '1') {
                        $user->plan_expired_date = Carbon::now()->addMonths($plan->duration)->isoFormat('YYYY-MM-DD');
                    } elseif ($plan->durationtype == 'Year' && $plan->id != '1') {
                        $user->plan_expired_date = Carbon::now()->addYears($plan->duration)->isoFormat('YYYY-MM-DD');
                    } else {
                        $user->plan_expired_date = null;
                    }
                    $user->save();
                } else {
                    $order = Order::find($data['order_id']);
                    $order->status = 2;
                    $order->payment_type = 'coingate';
                    $order->update();
                    return redirect()->route('plans.index')->with('error', __('Opps something went wrong.'));
                }
            });
        } else {
            if ($data['status'] == 'successfull') {
                $datas = Order::find($data['order_id']);
                $datas->status = 1;
                $datas->payment_type = 'coingate';
                $datas->update();


                $user = User::find(Auth::user()->id);
                $coupons = Coupon::find($data['coupon_id']);
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
                $plan = Plan::find($data['plan_id']);

                $user->plan_id = $plan->id;
                if ($plan->durationtype == 'Month' && $plan->id != '1') {
                    $user->plan_expired_date = Carbon::now()->addMonths($plan->duration)->isoFormat('YYYY-MM-DD');
                } elseif ($plan->durationtype == 'Year' && $plan->id != '1') {
                    $user->plan_expired_date = Carbon::now()->addYears($plan->duration)->isoFormat('YYYY-MM-DD');
                } else {
                    $user->plan_expired_date = null;
                }
                $user->save();
                return redirect()->route('plans.index')->with('status', __('Payment successfully.'));
            } else {
                $order = Order::find($data['order_id']);
                $order->status = 2;
                $order->payment_type = 'coingate';
                $order->update();
                return redirect()->route('plans.index')->with('error', __('Opps something went wrong.'));
            }
        }
        return redirect()->route('plans.index')->with('status', __('Payment successfully.'));
    }
}
