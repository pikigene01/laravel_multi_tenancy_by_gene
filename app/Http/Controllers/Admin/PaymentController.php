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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Redirect;
use Stripe\Charge;
use Stripe\Stripe as StripeStripe;
use Srmklive\PayPal\Services\PayPal as PayPalClient;

class PaymentController extends Controller
{
    public function stripe()
    {
        $view =  view('payment.PaymentStripe');
        return ['html' => $view->render()];
    }
    public function stripePostpending(Request $request)
    {
        $planID    = \Illuminate\Support\Facades\Crypt::decrypt($request->plan_id);
        $authuser  = Auth::user();
        if ($authuser->type == 'Admin') {
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
                $res_data['plan_id'] = $plan->id;
                $res_data['coupon']      = $coupon_id;
                $res_data['order_id'] = $data->id;
                return $res_data;
            });
            return $res_data;
        } else {
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
            $res_data['plan_id'] = $plan->id;
            $res_data['coupon']      = $coupon_id;
            $res_data['order_id'] = $data->id;
            return $res_data;
        }
    }
    public function stripeSession(Request $request)
    {
        if (Auth::user()->type != 'Admin') {
            StripeStripe::setApiKey(UtilityFacades::getsettings('stripe_secret'));
            $currency = UtilityFacades::getsettings('currency');
        } else {
            $currency = tenancy()->central(function ($tenant) {
                return UtilityFacades::getsettings('currency');
            });
            $stripe_secret = tenancy()->central(function ($tenant) {
                return UtilityFacades::getsettings('stripe_secret');
            });
            StripeStripe::setApiKey($stripe_secret);
        }

        if (!empty($request->createCheckoutSession)) {
            if (Auth::user()->type == 'Admin') {
                $plan_details = tenancy()->central(function ($tenant) use ($request) {
                    return Plan::find($request->plan_id);
                });
            } else {
                $plan_details =  Plan::find($request->plan_id);
            }
            try {
                $checkout_session = \Stripe\Checkout\Session::create([
                    'line_items' => [[
                        'price_data' => [
                            'product_data' => [
                                'name' => $plan_details->name,
                                'metadata' => [
                                    'plan_id' => $request->plan_id,
                                    'user_id' => Auth::user()->id
                                ]
                            ],
                            'unit_amount' => $request->amount * 100,
                            'currency' => $currency,
                        ],
                        'quantity' => 1,
                        'description' => $plan_details->name,
                    ]],
                    'mode' => 'payment',
                    'success_url' => route('stripe.success.pay', Crypt::encrypt(['coupon' => $request->coupon, 'plan_id' => $plan_details->id, 'price' => $request->amount, 'user_id' => Auth::user()->id, 'order_id' => $request->order_id, 'type' => 'stripe'])),
                    'cancel_url' => route('stripe.cancel.pay', Crypt::encrypt(['coupon' => $request->coupon, 'plan_id' => $plan_details->id, 'price' => $request->amount, 'user_id' => Auth::user()->id, 'order_id' => $request->order_id, 'type' => 'stripe'])),
                ]);
            } catch (Exception $e) {
                $api_error = $e->getMessage();
            }

            if (empty($api_error) && $checkout_session) {
                $response = array(
                    'status' => 1,
                    'message' => 'Checkout session created successfully.',
                    'sessionId' => $checkout_session->id
                );
            } else {
                $response = array(
                    'status' => 0,
                    'errors' => array(
                        'message' => 'Checkout session creation failed.' . $api_error
                    )
                );
            }
        }
        echo json_encode($response);
        die;
    }
    function paymentPending(Request $request)
    {
        if (Auth::user()->type == 'Admin') {
            $user = User::find(Auth::user()->id);
            $order = tenancy()->central(function ($tenant) use ($request, $user) {
                $data['plan_details'] = Plan::find($request->plan_id);
                $user = User::where('email', $user->email)->first();
                $data['order'] = Order::create([
                    'plan_id' => $request->plan_id,
                    'user_id' => $user->id,
                    'amount' => $data['plan_details']->price,
                    'status' => 0,
                ]);
                return $data;
            });
            $response = array(
                'status' => 0,
                'order_id' => $order['order']->id,
                'amount' => $order['order']->amount,
                'plan_name' => $order['plan_details']->name,
                'currency' => $request->currency,
                'currency_symbol' => $request->currency_symbol,
            );
            echo json_encode($response);
            die;
        } else {

            $user = User::find(Auth::user()->id); {
                $plan_details = Plan::find($request->plan_id);
                $user = User::where('email', $user->email)->first();
                $data = Order::create([
                    'plan_id' => $request->plan_id,
                    'user_id' => Auth::user()->id,
                    'amount' => $plan_details->price,
                    'status' => 0,
                ]);
            }
            $response = array(
                'status' => 0,
                'order_id' => $data->id,
                'amount' => $plan_details->price,
                'plan_name' => $plan_details->name,
                'currency' => $request->currency,
                'currency_symbol' => $request->currency_symbol,
            );
            echo json_encode($response);
            die;
        }
    }

    function paymentCancel($data)
    {
        $data = Crypt::decrypt($data);
        if (Auth::user()->type == 'Admin') {
            $order = tenancy()->central(function ($tenant) use ($data) {
                $datas = Order::find($data['order_id']);
                $datas->status = 2;
                $datas->payment_type = 'stripe';
                $datas->update();
            });
        } else {
            $datas = Order::find($data['order_id']);
            $datas->status = 2;
            $datas->payment_type = 'stripe';
            $datas->update();
        }
        return redirect()->route('plans.index')->with('errors', __('Payment canceled.'));
    }

    function paymentSuccess($data)
    {
        $data = Crypt::decrypt($data);
        if (Auth::user()->type == 'Admin') {
            $order = tenancy()->central(function ($tenant) use ($data) {
                $datas = Order::find($data['order_id']);
                $datas->status = 1;
                $datas->payment_type = 'stripe';
                $datas->update();
                $coupons = Coupon::find($data['coupon']);
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
            });
        } else {
            $datas = Order::find($data['order_id']);
            $datas->status = 1;
            $datas->payment_type = 'stripe';
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
        }
        return redirect()->route('plans.index')->with('status', __('Payment successfully!'));
    }

    public function razorPaypayment(Request $request)
    {
        $planID    = \Illuminate\Support\Facades\Crypt::decrypt($request->plan_id);
        $authuser  = Auth::user();
        if ($authuser->type == 'Admin') {
            $plan   = tenancy()->central(function ($tenant) use ($planID) {
                return Plan::find($planID);
            });
            $coupouns =  tenancy()->central(function ($tenant) use ($plan, $request, $authuser) {
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
            $plan   =    Plan::find($planID);
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
    public function RazorpayCallback(Request $request, $transaction_id, $coupon_id, $plan_id)
    {
        $planID    = $plan_id;
        if (Auth::user()->type == 'Admin') {
            $order = tenancy()->central(function ($tenant) use ($transaction_id, $coupon_id, $planID) {
                $datas = Order::orderBy('id', 'desc')->first();
                $datas->status = 1;
                $datas->payment_id = $transaction_id;
                $datas->payment_type = 'razorpay';
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
            $datas->payment_type = 'razorpay';
            $datas->update();
            $user = User::find(Auth::user()->id);
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
    public function processTransaction(Request $request)
    {
        $currency = UtilityFacades::getsettings('currency');
        if (Auth::user()->type == 'Admin') {
            $pro_detials = tenancy()->central(function ($tenant) use ($request) {
                return Plan::find($request->p_plan_id);
            });
        } else {
            $pro_detials =  Plan::find($request->p_plan_id);
        }
        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $paypalToken = $provider->getAccessToken();
        $response = $provider->createOrder([
            "intent" => "CAPTURE",
            "application_context" => [
                'return_url' => route('paysuccessTransaction', Crypt::encrypt(['product_name' => $pro_detials->name, 'price' => $pro_detials->price, 'user_id' => $request->r_user_id, 'currency' => $pro_detials->currency, 'product_id' => $request->p_plan_id, 'order_id' => $request->p_order_id])),
                'cancel_url' => route('paycancelTransaction', Crypt::encrypt(['product_name' => $pro_detials->name, 'price' => $pro_detials->price, 'user_id' => $request->r_user_id, 'currency' => $pro_detials->currency, 'product_id' => $request->p_plan_id, 'order_id' => $request->p_order_id])),

            ],
            "purchase_units" => [
                0 => [
                    "amount" => [
                        "currency_code" => $currency,
                        "value" => $pro_detials->price,
                    ]
                ]
            ]
        ]);
        if (isset($response['id']) && $response['id'] != null) {
            foreach ($response['links'] as $links) {
                if ($links['rel'] == 'approve') {
                    return redirect()->away($links['href']);
                }
            }
            return redirect()->back()->with('failed',  __('Something went wrong.'));
        } else {
            return redirect()->back()->with('failed',  __('Something went wrong.'));
        }
    }

    public function processTransactionadmin(Request $request)
    {
        $authuser  = Auth::user();
        $planID    = \Illuminate\Support\Facades\Crypt::decrypt($request->plan_id);
        if ($authuser->type == 'Admin') {
            $currency   = tenancy()->central(function ($tenant) use ($planID) {
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
        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $paypalToken = $provider->getAccessToken();
        $response = $provider->createOrder([
            "intent" => "CAPTURE",
            "application_context" => [
                'return_url' => route('paysuccessTransaction', Crypt::encrypt(['coupon' => $res_data['coupon'], 'product_name' => $plan->name, 'price' => $res_data['total_price'], 'user_id' => $authuser->id, 'currency' => $plan->currency, 'coupon' => $res_data['coupon'], 'product_id' => $plan->id, 'order_id' => $res_data['order_id']])),
                'cancel_url' => route('paycancelTransaction', Crypt::encrypt(['coupon' => $res_data['coupon'], 'product_name' => $plan->name, 'price' => $res_data['total_price'], 'user_id' => $authuser->id, 'currency' => $plan->currency, 'coupon' => $res_data['coupon'], 'product_id' => $plan->id, 'order_id' => $res_data['order_id']])),

            ],
            "purchase_units" => [
                0 => [
                    "amount" => [
                        "currency_code" => $currency,
                        "value" => $res_data['total_price'],
                    ]
                ]
            ]
        ]);
        if (isset($response['id']) && $response['id'] != null) {
            foreach ($response['links'] as $links) {
                if ($links['rel'] == 'approve') {
                    return redirect()->away($links['href']);
                }
            }
            return redirect()->back()->with('failed',  __('Something went wrong.'));
        } else {

            return redirect()->back()->with('failed',  __('Something went wrong.'));
        }
    }

    public function successTransaction($data, Request $request)
    {
        $data = Crypt::decrypt($data);
        if (Auth::user()->type == 'Admin') {
            $order = tenancy()->central(function ($tenant) use ($request, $data) {
                $datas = Order::find($data['order_id']);
                $datas->status = 1;
                $datas->payment_id = $request['PayerID'];
                $datas->payment_type = 'paypal';
                $datas->update();
                $coupons = Coupon::find($data['coupon']);
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
            });
        } else {
            $datas = Order::find($data['order_id']);
            $datas->status = 1;
            $datas->payment_id = $request['PayerID'];
            $datas->payment_type = 'paypal';
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
        }
        return redirect()->route('plans.index')->with('status', __('Payment successfully.'));
    }

    public function cancelTransaction($data)
    {
        $data = Crypt::decrypt($data);
        if (Auth::user()->type == 'Admin') {
            $order = tenancy()->central(function ($tenant) use ($data) {
                $data = Order::find($data['order_id']);
                $data->status = 2;
                $data->payment_type = 'paypal';
                $data->update();
            });
        } else {
            $data = Order::find($data['order_id']);
            $data->status = 2;
            $data->payment_type = 'paypal';

            $data->update();
        }
        return redirect()->route('plans.index')->with('failed', __('Payment canceled.'));
    }
}
