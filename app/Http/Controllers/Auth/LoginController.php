<?php
namespace App\Http\Controllers\Auth;
use App\Facades\UtilityFacades;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Superadmin\RequestDomainController;
use App\Models\Plan;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;


class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */
    use AuthenticatesUsers;
    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
   public $languages;

   public $app_values = [];


    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $data = new RequestDomainController();
        $data->updateStateValues();
        $this->app_values = $data->app_values;
    }

    public function showLoginForm($lang = '')
    {
        $this->languages = UtilityFacades::languages();
        $languages = $this->languages;
        $app_values = $this->app_values;
        if ($lang == '') {
            $lang = UtilityFacades::getValByName('default_language');
        }
        \App::setLocale($lang);
        // return Inertia::render('Auth/Login', compact('lang','languages','app_values'));

        return view('auth.login', compact('lang','languages'));
    }

    public function login(Request $request)
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
                    $app_url = 'riskcurb.app';
                    return redirect("https://$current_domain/tenant-impersonate/{$token->token}");
                }
            }
        } else {
            return redirect()->back()->with('errors', __('user not found'));
        }
    }
}












