<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\DataTables\Superadmin\SalesDataTable;
use App\Facades\UtilityFacades;
use App\Models\Order;
use App\Models\Plan;
use App\Models\Role;
use App\Models\Setting;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function landingPage()
    {
        $plans = tenancy()->central(function ($tenant) {
            return Plan::where('active_status',1)->get();
        });
        return view('welcome', compact('plans', 'datas'));
    }

    public function index()
    {
        $this->middleware('auth');
        if (!file_exists(storage_path() . "/installed")) {
            header('location:install');
            die;
        } else {
            $user = User::where('type', 'Admin')->count();
            $plan = Plan::count();
            $languages = count(UtilityFacades::languages());
            $earning = Order::where('status', '=', 1)->orWhere('status', '3')
                ->where('created_at', '>=', Carbon::now()->subDays(365)->toDateString())
                ->where('created_at', '<=', Carbon::now()->toDateString())->sum('amount');
            $paymenttypes = UtilityFacades::getpaymenttypes();
            // return Inertia::render('Admin/Dashboard', compact('user', 'plan', 'languages', 'earning', 'paymenttypes'));
            return view('superadmin.dashboard.home', compact('user', 'plan', 'languages', 'earning', 'paymenttypes'));
        }
    }

    public function sales(SalesDataTable $dataTable)
    {
        if (Auth::user()->type == 'Super Admin') {
            return $dataTable->render('superadmin.sales.index');
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
            if (tenant('id') == null) {

                $t = Order::select(DB::raw('DATE_FORMAT(created_at,"%b") AS user_month,SUM(amount) AS usr_cnt'))
                    ->where('status', '=', 1)
                    ->where('created_at', '>=', Carbon::now()->subDays(365)->toDateString())
                    ->where('created_at', '<=', Carbon::now()->toDateString())
                    ->groupBy(DB::raw('DATE_FORMAT(created_at,"%b") '))
                    ->get()
                    ->pluck('usr_cnt', 'user_month')
                    ->toArray();
            }
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
            if (tenant('id') == null) {
                $t = Order::select(DB::raw('DATE_FORMAT(created_at,"%d-%m") AS user_month,SUM(amount) AS usr_cnt'))
                    ->where('status', '=', 1)
                    ->where('created_at', '>=', Carbon::now()->subDays(365)->toDateString())
                    ->where('created_at', '<=', Carbon::now()->toDateString())
                    ->groupBy(DB::raw('DATE_FORMAT(created_at,"%d-%m") '))
                    ->get()
                    ->pluck('usr_cnt', 'user_month')
                    ->toArray();
            }
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
            if (tenant('id') == null) {
                $t = Order::select(DB::raw('DATE_FORMAT(created_at,"%d-%m") AS user_month,SUM(amount) AS usr_cnt'))
                    ->where('status', 1)
                    ->where('created_at', '>=', Carbon::now()->subDays(365)->toDateString())
                    ->where('created_at', '<=', Carbon::now()->toDateString())
                    ->groupBy(DB::raw('DATE_FORMAT(created_at,"%d-%m") '))
                    ->get()
                    ->pluck('usr_cnt', 'user_month')
                    ->toArray();
            }
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
