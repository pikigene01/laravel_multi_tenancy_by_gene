<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     *
     * @param  \App\Http\Requests\Auth\LoginRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(LoginRequest $request)
    {
        $central_domain = config('tenancy.central_domains')[0];
        $current_domain = tenant('domains');
        if (!empty($current_domain)) {
            $current_domain = $current_domain->pluck('domain')->toArray()[0];
        }
        $user = User::where('email', $request->email)->first();
        if (!empty($user)) {
            if ($user->type == 'Super Admin' && empty($user->tenant_id)) {
                if ($this->attemptLogin($request)) {
                    return $this->sendLoginResponse($request);
                } else {
                    return redirect()->back()->with('errors', __('Invalid username or password'));
                }
            } elseif (!empty($current_domain) && !empty($user->tenant_id)) {
                $user_admin = tenancy()->central(function ($tenant) {
                    return User::where('tenant_id', $tenant->id)->where('type', 'Admin')->first();
                });
                if ($user_admin->active_status == 0) {
                    return redirect()->back()->with('errors', __('Please Contact to administrator'));
                }
                if ($user_admin->plan_id != '1' && !empty($user_admin->plan_expired_date) && Carbon::now() >= $user_admin->plan_expired_date) {
                    $user_admin->assignPlan(1);
                }
                $users = User::where('email', $request->email)->first();
                if ($users->active_status == 1) {
                    if ($this->attemptLogin($request)) {
                        return $this->sendLoginResponse($request);
                    } else {
                        return redirect()->back()->with('errors', __('Invalid username or password'));
                    }
                } else {
                    return redirect()->back()->with('errors', __('Please Contact to administrator'));
                }
            } else {
                // dd($request->all());
                $user = User::where('email', $request->email)->first();
                if (!Hash::check($request['password'], $user->password)) {
                    return redirect()->back()->with('errors', __('Invalid username or password'));
                } else {
                    // dd($redirect->passwod)
                    $current_domain = $user->tenant->domains->first()->domain;
                    $redirectUrl = '/riskcurb/framework';
                    $token = tenancy()->impersonate($user->tenant, 1, $redirectUrl);
                    // dd($token->token);
                    return redirect("http://$current_domain/tenant-impersonate/{$token->token}");
                }
            }
        } else {
            return redirect()->back()->with('errors', __('user not found'));
        }
    }

    /**
     * Destroy an authenticated session.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
