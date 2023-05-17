<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\DataTables\Admin\SalesDataTable;
use App\Facades\UtilityFacades;
use App\Models\Order;
use App\Models\Plan;
use App\Models\Role;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function landingPage()
    {

        $plans = tenancy()->central(function ($tenant) {
            return Plan::where('active_status',1)->get();
        });

        return view('welcome', compact('plans'));
    }
    public function index()
    {
        $this->middleware('auth');
        if (Auth::user()->type == 'Admin') {
            $plan_expired_date = tenancy()->central(function ($tenant) {
                $usr = User::where('email', Auth::user()->email)->first();
                return $usr->plan_expired_date;
            });
        } else {
            $usr = User::where('email', Auth::user()->email)->first();
            $plan_expired_date = $usr->plan_expired_date;
        }
        $paymenttypes = UtilityFacades::getpaymenttypes();

        $earning = Order::select(['orders.*', 'users.type'])->join('users', 'users.id', '=', 'orders.user_id')->where('users.type', '!=', 'Admin')->where('status', '=', 1)->orWhere('status', '3')->sum('orders.amount');
        $user = User::where('tenant_id', tenant('id'))->where('type', '!=', 'Admin')->where('created_by', Auth::user()->id)->count();
        $role = Role::where('tenant_id')->count();
        $plan_expired_date = $plan_expired_date;
        return view('admin.dashboard.home', compact('user', 'role', 'plan_expired_date', 'earning','paymenttypes'));
    }

    public function sales(SalesDataTable $dataTable)
    {
        if (Auth::user()->type == 'Super Admin' | Auth::user()->type == 'Admin') {
            return $dataTable->render('admin.sales.index');
        } else {
            return redirect()->back()->with('failed', __('Permission denied.'));
        }
    }
    public function chart(Request $request)
    {
        if ($request->type == 'year') {
            $arrLable = [];
            $arrValue = [];
            for ($i = 0; $i < 12; $i++) {
                $arrLable[] = Carbon::now()->subMonth($i)->format('F');
                $arrValue[Carbon::now()->subMonth($i)->format('M')] = 0;
            }
            $arrLable = array_reverse($arrLable);
            $arrValue = array_reverse($arrValue);
            if (tenant('id') != null) {
                $t = User::select(DB::raw('DATE_FORMAT(created_at,"%b") AS user_month,COUNT(id) AS usr_cnt'))
                    ->where('created_at', '>', Carbon::now()->subDays(365)->toDateString())
                    ->where('created_at', '<', Carbon::now()->toDateString())
                    ->groupBy(DB::raw('DATE_FORMAT(created_at,"%b") '))
                    ->get()
                    ->pluck('usr_cnt', 'user_month')
                    ->toArray();
            }
            foreach ($t as $key => $val) {
                $arrValue[$key] = $val;
            }
            $arrValue = array_values($arrValue);
            return response()->json(['lable' => $arrLable, 'value' => $arrValue], 200);
        }

        if ($request->type == 'month') {
            $arrLable = [];
            $arrValue = [];
            for ($i = 0; $i < 30; $i++) {
                $arrLable[] = date("d M", strtotime('-' . $i . ' days'));
                $arrValue[date("d-m", strtotime('-' . $i . ' days'))] = 0;
            }
            $arrLable = array_reverse($arrLable);
            $arrValue = array_reverse($arrValue);
            if (tenant('id') != null) {
                $t = User::select(DB::raw('DATE_FORMAT(created_at,"%d-%m") AS user_month,COUNT(id) AS usr_cnt'))
                    ->where('created_at', '>', Carbon::now()->subDays(365)->toDateString())
                    ->where('created_at', '<', Carbon::now()->toDateString())
                    ->groupBy(DB::raw('DATE_FORMAT(created_at,"%d-%m") '))
                    ->get()
                    ->pluck('usr_cnt', 'user_month')
                    ->toArray();
            }
            foreach ($t as $key => $val) {
                $arrValue[$key] = $val;
            }
            $arrValue = array_values($arrValue);
            return response()->json(['lable' => $arrLable, 'value' => $arrValue], 200);
        }
        if ($request->type == 'week') {
            $arrLable = [];
            $arrValue = [];
            for ($i = 0; $i < 7; $i++) {
                $arrLable[] = date("d M", strtotime('-' . $i . ' days'));
                $arrValue[date("d-m", strtotime('-' . $i . ' days'))] = 0;
            }
            $arrLable = array_reverse($arrLable);
            $arrValue = array_reverse($arrValue);

            if (tenant('id') != null) {
                $t = User::select(DB::raw('DATE_FORMAT(created_at,"%d-%m") AS user_month,COUNT(id) AS usr_cnt'))
                    ->where('created_at', '>', Carbon::now()->subDays(365)->toDateString())
                    ->where('created_at', '<', Carbon::now()->toDateString())
                    ->groupBy(DB::raw('DATE_FORMAT(created_at,"%d-%m") '))
                    ->get()
                    ->pluck('usr_cnt', 'user_month')
                    ->toArray();
            }
            foreach ($t as $key => $val) {
                $arrValue[$key] = $val;
            }
            $arrValue = array_values($arrValue);
            return response()->json(['lable' => $arrLable, 'value' => $arrValue], 200);
        }
    }
}
