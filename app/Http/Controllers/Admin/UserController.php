<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\Admin\UsersDataTable;
use App\Facades\UtilityFacades;
use App\Models\Setting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Models\RequestDomain;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use App\Models\Role;
use App\Models\Tenant;
use DB;
use Hash;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Stancl\Tenancy\Database\Models\Domain;
use Stancl\Tenancy\Features\UserImpersonation;
use Lab404\Impersonate\Services\ImpersonateManager;

class UserController extends Controller
{
    public function index(UsersDataTable $dataTable)
    {
        if (\Auth::user()->can('manage-user')) {
            return $dataTable->render('admin.users.index');
        } else {
            return redirect()->back()->with('failed', __('Permission denied.'));
        }
    }

    public function create()
    {
        if (\Auth::user()->can('create-user')) {
            if (Auth::user()->type == 'Admin') {
                $roles = Role::where('name', '!=', 'Super Admin')->where('name', '!=', 'Admin')->pluck('name', 'name');
                $domains = Domain::pluck('domain', 'domain')->all();
            } else {
                $roles = Role::where('name', '!=', 'Admin')->where('name', Auth::user()->type)->pluck('name', 'name');
                $domains = Domain::pluck('domain', 'domain')->all();
            }
            return view('admin.users.create', compact('roles', 'domains'));
        } else {
            return redirect()->back()->with('failed', __('Permission denied.'));
        }
    }

    public function store(Request $request)
    {
        if (\Auth::user()->can('create-user')) {
            if (\Auth::user()->type == 'Admin') {
                $this->validate($request, [
                    'name' => 'required',
                    'email' => 'required|email|unique:users,email,',
                    'password' => 'same:confirm-password',
                    'roles' => 'required',
                ]);
                $input = $request->all();
                $input['password'] = Hash::make($input['password']);
                $input['type'] = $input['roles'];
                $input['created_by'] = Auth::user()->id;
                $input['plan_id'] = 1;
                $input['email_verified_at'] = (UtilityFacades::getsettings('email_verification') == '1')?null:Carbon::now()->toDateTimeString();
                $user = User::create($input);
                $user->assignRole($request->input('roles'));
                $user->update();
            } else {
                $this->validate($request, [
                    'name' => 'required',
                    'email' => 'required|email|unique:users,email,',
                    'password' => 'same:confirm-password',
                    'roles' => 'required',
                ]);
                $users = User::where('tenant_id', tenant('id'))->where('created_by', Auth::user()->id)->count();
                $usr = Auth::user();
                $user = user::where('email', $usr->email)->first();
                $plan = Plan::find($user->plan_id);
                if ($users < $plan->max_users) {
                    $input = $request->all();
                    $input['password'] = Hash::make($input['password']);
                    $input['type'] = $input['roles'];
                    $input['email_verified_at'] = (UtilityFacades::getsettings('email_verification') == '1')?null:Carbon::now()->toDateTimeString();
                    $input['created_by'] = Auth::user()->id;
                    $user = User::create($input);
                    $user->assignRole($request->input('roles'));
                    $user->update();
                } else {
                    return redirect()->back()->with('failed', __('Your user limit is over, Please upgrade plan.'));
                }
            }
            return redirect()->route('users.index')->with('success', __('User created successfully.'));
        } else {
            return redirect()->back()->with('failed', __('Permission denied.'));
        }
    }

    public function show($id)
    {
        if (\Auth::user()->can('show-user')) {
            $user = User::find($id);
            return view('users.show', compact('user'));
        } else {
            return redirect()->back()->with('failed', __('Permission denied.'));
        }
    }

    public function edit($id)
    {
        if (\Auth::user()->can('edit-user')) {
            $user = User::find($id);
            if (Auth::user()->type == 'Admin') {
                $roles = Role::where('name', '!=', 'Super Admin')->where('name', '!=', 'Admin')->pluck('name', 'name');
                $domains = Domain::pluck('domain', 'domain')->all();
            } else {
                $roles = Role::where('name', '!=', 'Admin')->where('name', Auth::user()->type)->pluck('name', 'name');
                $domains = Domain::pluck('domain', 'domain')->all();
            }

            return view('admin.users.edit', compact('user', 'roles', 'domains'));
        } else {
            return redirect()->back()->with('failed', __('Permission denied.'));
        }
    }

    public function update(Request $request, $id)
    {
        if (\Auth::user()->can('edit-user')) {
                $this->validate($request, [
                    'name' => 'required',
                    'email' => 'required|email|unique:users,email,' . $id,
                    'roles' => 'required',
                ]);
                $input = $request->all();
                $input['type'] = $input['roles'];
                $user = User::find($id);
                $current_date = Carbon::now();
                $newEndingDate = date("Y-m-d", strtotime(date("Y-m-d", strtotime($user->created_at)) . " + 1 year"));
                if ($current_date <= $newEndingDate) {
                }
                $user->update($input);
                DB::table('model_has_roles')->where('model_id', $id)->delete();
                $user->assignRole($request->input('roles'));
            return redirect()->route('users.index')->with('success', __('User updated successfully.'));
        } else {
            return redirect()->back()->with('failed', __('Permission denied.'));
        }
    }

    public function destroy($id)
    {
        if (\Auth::user()->can('delete-user')) {
            $user = User::find($id);

            if ($user->id != 1) {
                $user->delete();
            }
            return redirect()->route('users.index')->with('success', __('User deleted successfully.'));
        } else {
            return redirect()->back()->with('failed', __('Permission denied.'));
        }
    }

    public function useremailverified($id)
    {
        $user = User::find($id);
        if($user->email_verified_at)
        {
            $user->email_verified_at = null;
            $user->save();
            return redirect()->back()->with('success',__('User deactiveted successfully.'));
        }
        else
        {
            $user->email_verified_at = Carbon::now();
            $user->save();
            return redirect()->back()->with('success',__('User activeted successfully.'));
        }
    }

    public function userstatus($id)
    {
        $user = User::find($id);
        // dd($id);
        if($user->active_status == 1)
        {
            $user->active_status = 0;
            $user->save();
            return redirect()->back()->with('success',__('User deactiveted successfully.'));
        }
        else
        {
            $user->active_status = 1;
            $user->save();
            return redirect()->back()->with('success',__('User activeted successfully.'));
        }
    }
    // public function impersonate($id)
    // {
    //     if (\Auth::user()->can('impersonate-user')) {
    //         $user = User::find($id);
    //         $current_domain = $user->tenant->domains->first()->actual_domain;
    //         $redirectUrl = '/risk/framework';
    //         $token = tenancy()->impersonate($user->tenant, $id, $redirectUrl);
    //         // dd($token->token);
    //         return redirect("http://$current_domain/impersonate/{$token->token}");
    //     } else {
    //         return redirect()->back()->with('failed', __('Permission denied.'));
    //     }
    // }
}
