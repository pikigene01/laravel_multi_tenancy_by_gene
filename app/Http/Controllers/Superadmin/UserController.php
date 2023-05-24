<?php

namespace App\Http\Controllers\Superadmin;

use App\DataTables\Superadmin\UsersDataTable;
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
            return $dataTable->render('superadmin.users.index');
        } else {
            return redirect()->back()->with('failed', __('Permission denied.'));
        }
    }

    public function create()
    {
        if (\Auth::user()->can('create-user')) {
            $roles = Role::pluck('name', 'name');
            $domains = Domain::pluck('domain', 'domain')->all();
            $dbpermission = UtilityFacades::getsettings('database_permission');
            return view('superadmin.users.create', compact('roles', 'domains', 'dbpermission'));
        } else {
            return redirect()->back()->with('failed', __('Permission denied.'));
        }
    }

    public function store(Request $request)
    {
        if (\Auth::user()->can('create-user')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'name' => 'required',
                    'email' => 'required|email|unique:users,email',
                    'password' => 'same:confirm-password',
                    'domains' => 'required|unique:domains,domain',
                    'actual_domain' => 'required|unique:domains,actual_domain',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('errors', $messages->first());
            }
            $input = $request->all();
            $input['password'] = Hash::make($input['password']);
            $input['type'] = 'Admin';
            $input['email_verified_at'] = Carbon::now();
            $input['plan_id'] = 1;
            $input['created_by'] = Auth::user()->id;
            $user = User::create($input);
            $user->assignRole('Admin');

            if (UtilityFacades::getsettings('database_permission') == '1') {

                $tenant = Tenant::create([
                    'id' => $user->id,
                ]);
                $domain = Domain::create([
                    'domain' => $request->domains,
                    'actual_domain' => $request->actual_domain,
                    'tenant_id' => $tenant->id,
                ]);
                $user->tenant_id = $tenant->id;
                $user->created_by = Auth::user()->id;
                $user->save();
            } else {
                try {
                    $tenant = Tenant::create([
                        'id' => $user->id,
                        'tenancy_db_name' => $request->db_name,
                        'tenancy_db_username' => $request->db_username,
                        'tenancy_db_password' => $request->db_password,
                    ]);
                    $domain = Domain::create([
                        'domain' => $request->domains,
                        'actual_domain' => $request->actual_domain,
                        'tenant_id' => $tenant->id,
                    ]);
                    $user->tenant_id = $tenant->id;
                    $user->created_by = Auth::user()->id;
                    $user->save();
                } catch (\Exception $e) {
                    return redirect()->back()->with('errors', $e->getMessage());
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
            return view('superadmin.users.show', compact('user'));
        } else {
            return redirect()->back()->with('failed', __('Permission denied.'));
        }
    }

    public function edit($id)
    {
        if (\Auth::user()->can('edit-user')) {
            $user = User::find($id);
            $roles = Role::pluck('name', 'name');
            $domains = Domain::pluck('domain', 'domain')->all();

            $user_domain = Domain::where('tenant_id', $user->tenant_id)->first();
            $userRole = $user->roles->pluck('name', 'name')->all();
            return view('superadmin.users.edit', compact('user', 'roles', 'domains', 'user_domain', 'userRole'));
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
                'domains' => 'required',
                'actual_domain' => 'required',
            ]);
            $user = User::find($id);
            $user->name = $request->name;
            $user->email = $request->email;
            $user->save();
            $domain = Domain::where('tenant_id', $user->tenant_id)->first();
            if ($domain) {
                $domain->domain = $request->domains;
                $domain->actual_domain = $request->actual_domain;
                $domain->save();
            }
            tenancy()->initialize($user->tenant_id);
            $users = User::where('tenant_id', $user->tenant_id)->first();
            $users->name = $request->name;
            $users->email = $request->email;
            $users->save();
            return redirect()->route('users.index')->with('success', __('User updated successfully.'));
        } else {
            return redirect()->back()->with('failed', __('Permission denied.'));
        }
    }

    public function destroy($id)
    {
        if (\Auth::user()->can('delete-user')) {
            $user = User::find($id);
            if (Auth::user()->type == 'Super Admin') {
                $domain = Domain::where('tenant_id', $user->tenant_id)->first();
                $requestdomain = RequestDomain::where('email', $user->email)->first();
                if ($domain) {
                    $domain->delete();
                }
                if ($requestdomain) {
                    $requestdomain->delete();
                }
            }
            if ($user->id != 1) {
                $user->delete();
            }
            return redirect()->route('users.index')->with('success', __('User deleted successfully.'));
        } else {
            return redirect()->back()->with('failed', __('Permission denied.'));
        }
    }

    public function impersonate($id)
    {
        if (\Auth::user()->can('impersonate-user')) {
            $user = User::find($id);
            $current_domain = $user->tenant->domains->first()->actual_domain;
            $redirectUrl = '/riskcurb/framework';
            $token = tenancy()->impersonate($user->tenant, 1, $redirectUrl);
            return redirect("http://$current_domain/tenant-impersonate/{$token->token}");
        } else {
            return redirect()->back()->with('failed', __('Permission denied.'));
        }
    }

    public function userstatus($id)
    {
        $user = User::find($id);
        if ($user->active_status == 1) {
            $user->active_status = 0;
            $user->save();
            return redirect()->back()->with('success', __('User deactiveted successfully.'));
        } else {
            $user->active_status = 1;
            $user->save();
            return redirect()->back()->with('success', __('User activeted successfully.'));
        }
    }
}
