<?php

namespace App\Http\Controllers\Superadmin;

use App\DataTables\Superadmin\OfflineRequestDataTable;
use App\Http\Controllers\Controller;
use App\Mail\Superadmin\Approve_OfflineMail;
use App\Mail\Superadmin\OfflineMail;
use App\Models\Coupon;
use App\Models\OfflineRequest;
use App\Models\Order;
use App\Models\Plan;
use App\Models\RequestDomain;
use App\Models\User;
use App\Models\UserCoupon;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Spatie\MailTemplates\Models\MailTemplate;

class OfflineRequestController extends Controller
{
    public function index(OfflineRequestDataTable $dataTable)
    {
        return $dataTable->render('superadmin.offline_request.index');
        $plans = Plan::where('active_status',1)->get();
        $user = User::where('id', Auth::user()->id)->first();
        return view('superadmin.offline_request.index', compact('user', 'offline_request'));
    }

    public function offlinePaymentEntry(Request $request)
    {
        if (Auth::user()->type == 'Admin') {
            $order = tenancy()->central(function ($tenant) use ($request) {
                OfflineRequest::create([
                    'order_id' => $request->o_order_id,
                    'plan_id' => $request->o_plan_id,
                    'user_id' =>  User::find($tenant->id),
                    'email' => User::find($tenant->id)->email,
                ]);
            });
        } else {
            OfflineRequest::create([
                'order_id' => $request->o_order_id,
                'plan_id' => $request->o_plan_id,
                'user_id' =>  Auth::user()->id,
                'email' => Auth::user()->email,
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
        $coupons = Coupon::find($offline->coupon_id);
        if (!empty($coupons)) {
            $userCoupon         = new UserCoupon();
            $userCoupon->user   = $user->id;
            $userCoupon->coupon = $coupons->id;
            $userCoupon->order  = $order->id;
            $userCoupon->save();
            $usedCoupun = $coupons->used_coupon();
            if ($coupons->limit <= $usedCoupun) {
                $coupons->is_active = 0;
                $coupons->save();
            }
        }
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
                Mail::to($offline->email)->send(new Approve_OfflineMail($offline,$user));
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
        return redirect()->back()->with('success', __('Offlinerequest deleted successfully.'));
    }

    public function disapprovestatus($id)
    {
        $request_user = OfflineRequest::find($id);
        if ($request_user->is_approved == 0) {
            $view = view('superadmin.offline_request.offline_reason', compact('request_user'));
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
                Mail::to($offlinerequest->email)->send(new OfflineMail($offlinerequest ,$user));
            } catch (\Exception $e) {
                return redirect()->back()->with('errors', $e->getMessage());
            }
        }
        return redirect()->back()->with('success', __('Domain request disapprove successfully.'));
    }
}
