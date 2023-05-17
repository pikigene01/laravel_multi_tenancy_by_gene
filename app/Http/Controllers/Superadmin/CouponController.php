<?php

namespace App\Http\Controllers\Superadmin;

use App\DataTables\Superadmin\CouponDataTable;
use App\DataTables\Superadmin\UserCouponDatatable;
use App\Facades\Utility;
use App\Facades\UtilityFacades;
use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\Plan;
use App\Models\UserCoupon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\CssSelector\Parser\Reader;

class CouponController extends Controller
{
    public function index(CouponDataTable $dataTable)
    {
        if (\Auth::user()->can('manage-coupon')) {
            $total_coupon = Coupon::count();
            $expiered_coupon = Coupon::where('is_active', '0')->count();
            $total_used_coupon = UserCoupon::count();
            $total_use_amount = Order::where('status', 1)->sum('discount_amount');
            return $dataTable->render('superadmin.coupon.index', compact('total_coupon', 'expiered_coupon', 'total_used_coupon', 'total_use_amount'));
        } else {
            return redirect()->back()->with('failed', __('Permission denied.'));
        }
    }

    public function create()
    {
        if (\Auth::user()->can('create-coupon')) {
            return view('superadmin.coupon.create');
        } else {
            return redirect()->back()->with('failed', __('Permission denied.'));
        }
    }

    public function store(Request $request)
    {
         if (\Auth::user()->can('create-coupon')) {
            request()->validate([
                'discount' => 'required',
                'discount_type' => 'required',
                'limit' => 'required',
            ]);
            $coupon           = new Coupon();
            $coupon->discount = $request->discount;
            $coupon->discount_type     = $request->discount_type;
            $coupon->limit    = $request->limit;
            if ($request->icon_input == 'manual') {
                if (!empty($request->manualCode)) {
                    $coupon->code = strtoupper($request->manualCode);
                } else {
                    return redirect()->back()->with('errors', __('Manual code is required.'));
                }
            } else {
                if (!empty($request->autoCode)) {
                    $coupon->code = $request->autoCode;
                } else {
                    return redirect()->back()->with('errors', __('Auto code is required.'));
                }
            }
            $coupon->save();
            return redirect()->route('coupon.index')->with('success', __('Coupon created Successfully.'));
        } else {
            return redirect()->back()->with('failed', __('Permission denied.'));
        }
    }

    public function show(UserCouponDatatable $dataTable)
    {
        if (\Auth::user()->can('show-coupon')) {
            return $dataTable->render('superadmin.coupon.show');
        } else {
            return redirect()->back()->with('failed', __('Permission denied.'));
        }
    }

    public function edit($id)
    {
        if (\Auth::user()->can('edit-coupon')) {
            $coupon = Coupon::find($id);
            return view('superadmin.coupon.edit', compact('coupon'));
        } else {
            return redirect()->back()->with('failed', __('Permission denied.'));
        }
    }

    public function update(Request $request, $id)
    {
        if (\Auth::user()->can('edit-coupon')) {
            request()->validate([
                'discount' => 'required',
                'discount_type' => 'required',
                'limit' => 'required',
            ]);
            $coupon           = Coupon::find($id);
            $coupon->discount = $request->discount;
            $coupon->discount_type     = $request->discount_type;
            $coupon->limit    = $request->limit;
            $coupon->code     = $request->code;
            $coupon->save();
            return redirect()->route('coupon.index')
                ->with('success',  __('Coupon updated successfully'));
        } else {
            return redirect()->back()->with('failed', __('Permission denied.'));
        }
    }

    public function destroy($id)
    {
        if (\Auth::user()->can('delete-coupon')) {
            $coupon = Coupon::find($id);
            $coupon->delete();

            return redirect()->route('coupon.index')
                ->with('success',  __('Coupon deleted successfully'));
        } else {
            return redirect()->back()->with('failed', __('Permission denied.'));
        }
    }

    public function upload_csv()
    {
        return view('superadmin.coupon.upload_coupon');
    }

    public function upload_csv_store(Request $request)
    {
        request()->validate([
            'file' => 'required|file|mimes:csv'
        ]);
        if ($request->hasFile('file')) {
            $file = $request->file;
            $fileName = time() . '.' . $file->extension();
            $path = $file->storeAs('/coupon', $fileName);
            $data = array_map('str_getcsv', file(Storage::path($path)));

            array_shift($data);
            foreach ($data as $val) {
                $coupon = new Coupon();
                $coupon->discount_type = $val[0];
                $coupon->code = $val[1];
                $coupon->discount = $val[2];
                $coupon->limit = $val[3];
                $coupon->is_active = 1;
                $coupon->save();
            }
        }
        return redirect()->route('coupon.index')
            ->with('success',  __('Coupon created successfully.'));
    }

    public function masscreate()
    {
        if (\Auth::user()->can('mass-create-coupon')) {
            return view('superadmin.coupon.mass_create');
        } else {
            return redirect()->back()->with('failed', __('Permission denied.'));
        }
    }

    public function masscreate_store(Request $request)
    {
        if (\Auth::user()->can('mass-create-coupon')) {
            request()->validate([
                'discount' => 'required',
                'discount_type' => 'required',
                'mass_create' => 'required',
                'limit' => 'required',
            ]);
            $mass_create = $request->mass_create;
            for ($i = 1; $i <= $mass_create; $i++) {
                $coupon = new Coupon();
                $coupon->discount = $request->discount;
                $coupon->discount_type     = $request->discount_type;
                $coupon->limit    = $request->limit;
                $coupon->code = strtoupper(Str::random(10));
                $coupon->save();
            }
            return redirect()->route('coupon.index')->with('success', __('Mass coupon created successfully.'));
        } else {
            return redirect()->back()->with('failed', __('Permission denied.'));
        }
    }

    public function couponStatus($id)
    {
        $coupon = Coupon::find($id);
        if ($coupon->is_active == 1) {
            $coupon->is_active = 0;
            $coupon->save();
            return redirect()->route('coupon.index')->with('success', __('Coupon deactivate successfully.'));
        } else {
            $coupon->is_active = 1;
            $coupon->save();
            return redirect()->route('coupon.index')->with('success', __('Coupon activate successfully.'));
        }
    }

    public function applyCoupon(Request $request)
    {
        if (Auth::user() && Auth::user()->type == 'Admin') {
            $plan =  tenancy()->central(function ($tenant) use ($request) {
                return Plan::find(\Illuminate\Support\Facades\Crypt::decrypt($request->plan_id));
            });

            if ($plan && $request->coupon != '') {
                $original_price = UtilityFacades::amount_format($plan->price);
                $coupons   =  tenancy()->central(function ($tenant) use ($request) {
                    return Coupon::where('code', strtoupper($request->coupon))->where('is_active', '1')->first();
                });
                if (!empty($coupons)) {
                    $usedCoupun = $coupons->used_coupon();
                    if ($coupons->limit == $usedCoupun) {
                        return response()->json(
                            [
                                'is_success' => false,
                                'final_price' => $original_price,
                                'price' => number_format($plan->price, 2),
                                'message' => __('This coupon code has expired.'),
                            ]
                        );
                    } else {
                        $discount_type = $coupons->discount_type;
                        $discount_value =  UtilityFacades::calculateDiscount($plan->price, $coupons->discount, $discount_type);
                        $plan_price     = $plan->price - $discount_value;
                        $price          = UtilityFacades::amount_format($plan->price - $discount_value);
                        $discount_value = '-' . UtilityFacades::amount_format($discount_value);
                        if ($plan_price < 0) {
                            return response()->json(
                                [
                                    'is_success' => false,
                                    'discount_price' => UtilityFacades::amount_format(0),
                                    'currency_symbol' => UtilityFacades::getsettings('currency_symbol'),
                                    'final_price' => UtilityFacades::amount_format($plan->price),
                                    'price' => number_format($plan->price, 2),
                                    'message' => __('Price is negetive please enter currect coupon code.'),
                                ]
                            );
                        } else {
                            return response()->json(
                                [
                                    'is_success' => true,
                                    'discount_price' => $discount_value,
                                    'currency_symbol' => UtilityFacades::getsettings('currency_symbol'),
                                    'final_price' => $price,
                                    'price' => number_format($plan_price, 2),
                                    'message' => __('Coupon code has applied successfully.'),
                                ]
                            );
                        }
                    }
                } else {
                    return response()->json(
                        [
                            'is_success' => false,
                            'final_price' => $original_price,
                            'price' => number_format($plan->price, 2),
                            'message' => __('This coupon code is invalid or has expired.'),
                        ]
                    );
                }
            }
        } else {
            $plan = Plan::find(\Illuminate\Support\Facades\Crypt::decrypt($request->plan_id));
            if ($plan && $request->coupon != '') {
                $original_price = UtilityFacades::amount_format($plan->price);
                $coupons       =  Coupon::where('code', strtoupper($request->coupon))->where('is_active', '1')->first();
                if (!empty($coupons)) {
                    $usedCoupun = $coupons->used_coupon();
                    if ($coupons->limit == $usedCoupun) {
                        return response()->json(
                            [
                                'is_success' => false,
                                'final_price' => $original_price,
                                'price' => number_format($plan->price, 2),
                                'message' => __('This coupon code has expired.'),
                            ]
                        );
                    } else {
                        $discount_type = $coupons->discount_type;
                        $discount_value =  UtilityFacades::calculateDiscount($plan->price, $coupons->discount, $discount_type);
                        $plan_price     = $plan->price - $discount_value;
                        $price          = UtilityFacades::amount_format($plan->price - $discount_value);
                        $discount_value = '-' . UtilityFacades::amount_format($discount_value);
                        if ($plan_price < 0) {
                            return response()->json(
                                [
                                    'is_success' => false,
                                    'discount_price' => UtilityFacades::amount_format(0),
                                    'currency_symbol' => UtilityFacades::getsettings('currency_symbol'),
                                    'final_price' => UtilityFacades::amount_format($plan->price),
                                    'price' => number_format($plan->price, 2),
                                    'message' => __('Price is negetive please enter currect coupon code.'),
                                ]
                            );
                        } else {
                            return response()->json(
                                [
                                    'is_success' => true,
                                    'discount_price' => $discount_value,
                                    'currency_symbol' => UtilityFacades::getsettings('currency_symbol'),
                                    'final_price' => $price,
                                    'price' => number_format($plan_price, 2),
                                    'message' => __('Coupon code has applied successfully.'),
                                ]
                            );
                        }
                    }
                } else {
                    return response()->json(
                        [
                            'is_success' => false,
                            'final_price' => $original_price,
                            'price' => number_format($plan->price, 2),
                            'message' => __('This coupon code is invalid or has expired.'),
                        ]
                    );
                }
            }
        }
    }
}
