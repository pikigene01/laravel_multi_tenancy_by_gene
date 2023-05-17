<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\DataTables\Superadmin\PlanDataTable;
use App\Facades\UtilityFacades;
use App\Models\Order;
use App\Models\Plan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PlanController extends Controller
{
    public function index(PlanDataTable $dataTable)
    {
        if (Auth::user()->can('manage-plan')) {
            return $dataTable->render('superadmin.plans.index');
        } else {
            return redirect()->back()->with('failed', __('Permission denied.'));
        }
    }

    public function myPlan(PlanDataTable $dataTable)
    {
        if (Auth::user()->can('manage-plan')) {
            $plans = Plan::where('tenant_id', null)->get();
            $user = User::where('tenant_id', tenant('id'))->where('type', 'Admin')->first();
            return view('plans.index', compact('user', 'plans'));
        } else {
            return redirect()->back()->with('failed', __('Permission denied.'));
        }
    }

    public function create()
    {
        if (Auth::user()->can('create-plan')) {
            return view('superadmin.plans.create');
        } else {
            return redirect()->back()->with('failed', __('Permission denied.'));
        }
    }

    public function store(Request $request)
    {
        if (Auth::user()->can('create-plan')) {
            request()->validate([
                'name' => 'unique:plans,name|required',
                'price' => 'required',
                'duration' => 'required',
                'durationtype' => 'required',
                'description' => 'max:50',
            ]);
            $paymenttypes = UtilityFacades::getpaymenttypes();
            if (!$paymenttypes) {
                return redirect()->route('plans.index')->with('errors', __('Please on at list one payment type.'));
            }
            Plan::create([
                'name' => $request->name,
                'price' => $request->price,
                'duration' => $request->duration,
                'durationtype' => $request->durationtype,
                'description' => $request->description,
            ]);

            return redirect()->route('plans.index')->with('success', __('Plan created successfully.'));
        } else {
            return redirect()->back()->with('failed', __('Permission denied.'));
        }
    }

    public function show(Plan $plan)
    {
        if (Auth::user()->can('show-plan')) {
            $lan = Plan::find($plan);
            return view('plans.show', compact('plan'));
        } else {
            return redirect()->back()->with('failed', __('Permission denied.'));
        }
    }

    public function edit($id)
    {
        if (Auth::user()->can('edit-plan')) {
            $plan = Plan::find($id);
            return view('superadmin.plans.edit', compact('plan'));
        } else {
            return redirect()->back()->with('failed', __('Permission denied.'));
        }
    }

    public function update(Request $request, $id)
    {
        if (Auth::user()->can('edit-plan')) {

            request()->validate([
                'name' => 'unique:plans,name|required',
                'price' => 'required',
                'duration' => 'required',
                'description' => 'max:50',
            ]);
            $plan = Plan::find($id);
            $plan->name = $request->input('name');
            $plan->price = $request->input('price');
            $plan->duration = $request->input('duration');
            $plan->durationtype = $request->input('durationtype');
            $plan->description = $request->input('description');
            $plan->save();


            return redirect()->route('plans.index')->with('success', __('Plan updated successfully.'));
        } else {
            return redirect()->back()->with('failed', __('Permission denied.'));
        }
    }

    public function destroy($id)
    {
        if (Auth::user()->can('delete-plan')) {
            $plan = Plan::find($id);

            if ($plan->id != 1) {
                $plan_exist_in_order = Order::where('plan_id', $plan->id)->first();
                if (empty($plan_exist_in_order)) {
                    $plan->delete();
                    return redirect()->route('plans.index')->with('success', __('Plan deleted successfully.'));
                } else {
                    return redirect()->back()->with('failed', __('Can not delete this plan Because its Purchased by users.'));
                }
            } else {
                return redirect()->back()->with('failed', __('Can not delete this plan Because its free plan.'));
            }
        } else {
            return redirect()->back()->with('failed', __('Permission denied.'));
        }
    }

    public function planStatus($id)
    {
        $plan = Plan::find($id);
        if ($plan->active_status == 1) {
            $plan->active_status = 0;
            $plan->save();
            return redirect()->back()->with('success', __('Plan deactiveted successfully.'));
        } else {
            $plan->active_status = 1;
            $plan->save();
            return redirect()->back()->with('success', __('Plan activeted successfully.'));
        }
    }
}
