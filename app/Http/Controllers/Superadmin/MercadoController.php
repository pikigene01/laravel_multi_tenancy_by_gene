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
use Exception;
use Illuminate\Support\Facades\Crypt;

class MercadoController extends Controller
{
    public function mercadopagopayment(Request $request)
    {
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
            $res_data['requestdomain_id'] = $requestdomain->id;
            $res_data['total_price'] = $price;
            $res_data['coupon']      = $coupon_id;
            $res_data['order_id'] = $order->id;
            return $res_data;
        });
        $mercado_access_token = UtilityFacades::getsettings('mercado_access_token');
        \MercadoPago\SDK::setAccessToken($mercado_access_token);
        try {
            // Create a preference object
            $preference = new \MercadoPago\Preference();
            // Create an item in the preference
            $item              = new \MercadoPago\Item();
            $item->title       = "Plan : " . $plan->name;
            $item->quantity    = 1;
            $item->unit_price  = $res_data['total_price'];
            $preference->items = array($item);
            $success_url       = route('mercado.callback', [Crypt::encrypt(['order_id' => $res_data['order_id'], 'requestdomain_id' => $res_data['requestdomain_id'], 'coupon' => $res_data['coupon'], 'flag' => 'success'])]);
            $failure_url       = route('mercado.callback', [Crypt::encrypt(['order_id' => $res_data['order_id'], 'requestdomain_id' => $res_data['requestdomain_id'], 'coupon' => $res_data['coupon'], 'flag' => 'failure'])]);
            $pending_url       = route('mercado.callback', [Crypt::encrypt(['order_id' => $res_data['order_id'], 'requestdomain_id' => $res_data['requestdomain_id'], 'coupon' => $res_data['coupon'], 'flag' => 'pending'])]);

            $preference->back_urls = array(
                "success" => $success_url,
                "failure" => $failure_url,
                "pending" => $pending_url,
            );
            $preference->auto_return = "approved";
            $preference->save();
            if (UtilityFacades::getsettings('mercado_mode') == 'live') {
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

    public function mercadopagoPaymentCallback(Request $request, $data)
    {
        $data = Crypt::decrypt($data);
        if ($data['flag'] == 'success') {
            $datas = Order::find($data['order_id']);
            $datas->status = 1;
            $datas->payment_id = $request->payment_id;
            $datas->payment_type = 'mercadopago';
            $datas->update();
            $coupons = Coupon::find($data['coupon']);

            if (!empty($coupons)) {
                $userCoupon         = new UserCoupon();
                $userCoupon->domainrequest   = $data['requestdomain_id'];
                $userCoupon->coupon = $coupons->id;
                $userCoupon->order  = $datas->id;
                $userCoupon->save();
                $usedCoupun = $coupons->used_coupon();
                if ($coupons->limit <= $usedCoupun) {
                    $coupons->is_active = 0;
                    $coupons->save();
                }
            }

            if (UtilityFacades::getsettings('approve_type') == 'Auto') {
                UtilityFacades::approved_request($datas);
            }
            return redirect()->route('landingpage')->with('status', __('Thanks for registration, Your account is in review and you get email when your account active.'));
        } else {
            $order = Order::find($data['order_id']);
            $order->status = 2;
            $order->payment_id = $request->transaction_id;
            $order->payment_type = 'mercadopago';
            $order->update();
            return redirect()->back()->with('failed', __('Payment Failed.'));
        }
    }
}
