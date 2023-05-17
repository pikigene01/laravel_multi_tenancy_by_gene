<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\Admin\OfflineRequestDataTable;
use App\Facades\UtilityFacades;
use App\Http\Controllers\Controller;
use App\Mail\Admin\Approve_OfflineMail;
use App\Mail\Admin\OfflineMail;
use App\Models\Coupon;
use App\Models\OfflineRequest;
use App\Models\Order;
use App\Models\Plan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Spatie\MailTemplates\Models\MailTemplate;

class OfflineRequestController extends Controller
{
    public function index(OfflineRequestDataTable $dataTable)
    {
        return $dataTable->render('admin.offline_request.index');
        $plans = Plan::where('active_status',1)->get();
        $user = User::where('id', Auth::user()->id)->first();
        return view('admin.offline_request.index', compact('user', 'offline_request'));
    }

    public function offlinePaymentEntry(Request $request)
    {
        $planID    = \Illuminate\Support\Facades\Crypt::decrypt($request->plan_id);
        $authuser  = \Auth::user();
        if (Auth::user()->type == 'Admin') {
            $plan   = tenancy()->central(function ($tenant) use ($planID) {
                return Plan::find($planID);
            });
            $res_data =  tenancy()->central(function ($tenant) use ($plan, $request) {
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
                    'payment_type' => 'offline',
                    'discount_amount' => $discount_value,
                    'coupon_code' => $coupon_code,
                    'status' => 0,
                ]);
                $res_data['total_price'] = $price;
                $res_data['coupon']      = $coupon_id;
                $res_data['order_id'] = $data->id;
                return $res_data;
            });
            $order = tenancy()->central(function ($tenant) use ($res_data, $plan, $authuser) {
                OfflineRequest::create([
                    'order_id' => $res_data['order_id'],
                    'plan_id' => $plan->id,
                    'coupon_id' => $res_data['coupon'],
                    'user_id' =>  $tenant->id,
                    'email' => $authuser->email,
                ]);
            });
        } else {
            $plan  =  Plan::find($planID);
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
                'payment_type' => 'offline',
                'discount_amount' => $discount_value,
                'coupon_code' => $coupon_code,
                'status' => 0,
            ]);
            $res_data['total_price'] = $price;
            $res_data['coupon']      = $coupon_id;
            $res_data['order_id'] = $data->id;
            OfflineRequest::create([
                'order_id' => $res_data['order_id'],
                'plan_id' => $plan->id,
                'coupon_id' => $res_data['coupon'],
                'user_id' =>  $authuser->id,
                'email' => $authuser->email,
            ]);
        }
        return redirect()->route('plans.index')
            ->with('success',  __('Plan update request send successfully.'));
    }

    public function offlinerequeststatus($id, Request $request)
    {
        $offline = OfflineRequest::find($id);
        $user = User::find($offline->user_id);
        $plan = Plan::find($offline->plan_id);
        $order = Order::find($offline->order_id);
        $user->plan_id = $plan->id;
        if ($plan->durationtype == 'Month' && $plan->id != '1') {
            $user->plan_expired_date = Carbon::now()->addMonths($plan->duration)->isoFormat('YYYY-MM-DD');
        } elseif ($plan->durationtype == 'Year' && $plan->id != '1') {
            $user->plan_expired_date = Carbon::now()->addYears($plan->duration)->isoFormat('YYYY-MM-DD');
        } else {
            $user->plan_expired_date = null;
        }
        $user->save();
        $offline->is_approved = 1;
        $offline->update();
        $order->status = 1;
        $order->payment_type = 'offline';
        $order->update();
        $user = User::where('id', $offline->user_id)->first();
        if (MailTemplate::where('mailable', Approve_OfflineMail::class)->first()) {
            try {
                Mail::to($offline->email)->send(new Approve_OfflineMail($offline, $user));
            } catch (\Exception $e) {
                return redirect()->back()->with('errors', $e->getMessage());
            }
            return redirect()->back()->with('success',  __('Plan update request send successfully.'));
        }
    }
    public function destroy($id)
    {
        $offlinerequest = OfflineRequest::find($id);
        $offlinerequest->delete();
        return redirect()->back()->with('success', __('Offline request deleted successfully.'));
    }

    public function disapprovestatus($id)
    {
        $request_user = OfflineRequest::find($id);
        if ($request_user->is_approved == 0) {
            $view = view('admin.offline_request.offline_reason', compact('request_user'));
            return ['html' => $view->render()];
        } else {
            return redirect()->back();
        }
    }

    public function offlinedisapprove(Request $request, $id)
    {
        $validator = \Validator::make(
            $request->all(),
            [
                'disapprove_reason' => 'required',
            ]
        );
        if ($validator->fails()) {
            $messages = $validator->getMessageBag();
            return redirect()->back()->with('errors', $messages->first());
        }
        $offline = OfflineRequest::find($id);
        $order = Order::find($offline->order_id);
        $offlinerequest = OfflineRequest::find($id);
        $offlinerequest->disapprove_reason = $request->disapprove_reason;
        $offlinerequest->is_approved = 2;
        $offlinerequest->update();
        $order->status = 2;
        $order->payment_type = 'offline';
        $order->update();
        $user = User::where('id', $offline->user_id)->first();
        if (MailTemplate::where('mailable', OfflineMail::class)->first()) {
            try {
                Mail::to($offlinerequest->email)->send(new OfflineMail($offlinerequest, $user));
            } catch (\Exception $e) {
                return redirect()->back()->with('errors', $e->getMessage());
            }
        }
        return redirect()->back()->with('success', __('Domain request disapprove successfully.'));
    }
}
