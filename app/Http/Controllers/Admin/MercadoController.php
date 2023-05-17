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
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class MercadoController extends Controller
{
    public function mercadoPrepare(Request $request)
    {
        $planID    = \Illuminate\Support\Facades\Crypt::decrypt($request->plan_id);
        $authuser  = \Auth::user();
        if (Auth::user()->type == 'Admin') {
            $mercado_mode = tenancy()->central(function ($tenant) {
                return UtilityFacades::getsettings('mercado_mode');
            });
            $mercado_access_token = tenancy()->central(function ($tenant) {
                return UtilityFacades::getsettings('mercado_access_token');
            });
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
                $res_data['total_price'] = $price;
                $res_data['coupon']      = $coupon_id;
                $res_data['order_id'] = $data->id;
                return $res_data;
            });
        } else {
            $mercado_mode = UtilityFacades::getsettings('mercado_mode');
            $mercado_access_token = UtilityFacades::getsettings('mercado_access_token');
            $plan   = Plan::find($planID);
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
            $res_data['total_price'] = $price;
            $res_data['coupon']      = $coupon_id;
            $res_data['order_id'] = $data->id;
        }
        \MercadoPago\SDK::setAccessToken($mercado_access_token);
        try {
            $preference = new \MercadoPago\Preference();
            // Create an item in the preference
            $item              = new \MercadoPago\Item();
            $item->title       = "Plan : " . $plan->name;
            $item->quantity    = 1;
            $item->unit_price  = $res_data['total_price'];
            $preference->items = array($item);
            $success_url       = route('mercado.payment.callback', [Crypt::encrypt(['order_id' => $res_data['order_id'], 'coupon' => $res_data['coupon'], 'flag' => 'success'])]);
            $failure_url       = route('mercado.payment.callback', [Crypt::encrypt(['order_id' => $res_data['order_id'], 'coupon' => $res_data['coupon'], 'flag' => 'failure'])]);
            $pending_url       = route('mercado.payment.callback', [Crypt::encrypt(['order_id' => $res_data['order_id'], 'coupon' => $res_data['coupon'], 'flag' => 'pending'])]);

            $preference->back_urls = array(
                "success" => $success_url,
                "failure" => $failure_url,
                "pending" => $pending_url,
            );
            $preference->auto_return = "approved";
            $preference->save();
            if ($mercado_mode == 'live') {
                $redirectUrl = $preference->init_point;
                return redirect($redirectUrl);
            } else {
                $redirectUrl = $preference->sandbox_init_point;
                return redirect($redirectUrl);
            }
        } catch (Exception $e) {
            return redirect()->back()->with('failed', __('Something went wrong.'));
        }
    }

    public function mercadoCallback(Request $request, $data)
    {
        $data = Crypt::decrypt($data);
        if ($data['flag'] == 'success') {
            if (Auth::user()->type == 'Admin') {
                $order = tenancy()->central(function ($tenant) use ($data, $request) {
                    $datas = Order::find($data['order_id']);
                    $datas->status = 1;
                    $datas->payment_id = $request->payment_id;
                    $datas->payment_type = 'mercadopago';
                    $datas->update();
                    $user = User::find($tenant->id);
                    $coupons = Coupon::find($data['coupon']);
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
                    return redirect()->route('plans.index')->with('status', 'Payment successfull.');
                });
            } else {
                $datas = Order::find($data['order_id']);
                $datas->status = 1;
                $datas->payment_id = $request->payment_id;
                $datas->payment_type = 'mercadopago';
                $datas->update();
                $user = User::find(Auth::user()->id);
                $coupons = Coupon::find($data['coupon']);
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
                $plan = Plan::find($datas['plan_id']);
                $user->plan_id = $plan->id;
                if ($plan->durationtype == 'Month' && $plan->id != '1') {
                    $user->plan_expired_date = Carbon::now()->addMonths($plan->duration)->isoFormat('YYYY-MM-DD');
                } elseif ($plan->durationtype == 'Year' && $plan->id != '1') {
                    $user->plan_expired_date = Carbon::now()->addYears($plan->duration)->isoFormat('YYYY-MM-DD');
                } else {
                    $user->plan_expired_date = null;
                }
                $user->save();
                return redirect()->route('plans.index')->with('status', 'Payment successfull.');
            }
        } else {
            if (Auth::user()->type == 'Admin') {
                $central = tenancy()->central(function ($tenant) use ($data) {
                    $order = Order::find($data['order_id']);
                    $order->status = 2;
                    $order->payment_type = 'mercadopago';
                    $order->update();
                    return redirect()->route('plans.index')->with('errors', __('Payment failed.'));
                });
            } else {
                $order = Order::find($data['order_id']);
                $order->status = 2;
                $order->payment_type = 'mercadopago';
                $order->update();
                return redirect()->route('plans.index')->with('errors', __('Payment failed.'));
            }
        }
    }
}
