<?php

namespace App\Http\Controllers\Auth;

use App\Facades\UtilityFacades;
use App\Http\Controllers\Controller;
use App\Mail\Admin\RegisterMail;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\Role;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Spatie\MailTemplates\Models\MailTemplate;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string',],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */

    public function index($lang = '')
    {
        $roles = Role::whereNotIn('name', ['Super Admin', 'Admin'])->pluck('name', 'name')->all();
        if ($lang == '') {
            $lang = UtilityFacades::getValByName('default_language');
        }
        \App::setLocale($lang);
        return view('auth.register', compact('roles','lang'));
    }

    protected function create(array $data)
    {

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'tenant_id' => tenant('id'),
            'type' => UtilityFacades::getsettings('roles'),
            'created_by' => 1,
            'plan_id' => 1,
            'email_verified_at' => (UtilityFacades::getsettings('email_verification') == '1')?null:Carbon::now()->toDateTimeString()
        ]);

        $user->assignRole(UtilityFacades::getsettings('roles'));

        // if (MailTemplate::where('mailable', RegisterMail::class)->first()) {
        //     try {
        //         Mail::to($data['email'])->send(new RegisterMail($data));
        //     } catch (\Exception $e) {
        //         Session::flash('error', $e->getMessage());
        //     }
        // }
        Session::flash('success', 'User registered');
        return $user;

    }
}

