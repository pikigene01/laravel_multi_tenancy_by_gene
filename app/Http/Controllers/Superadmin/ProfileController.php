<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Facades\UtilityFacades;
use App\Models\RequestDomain;
use Illuminate\Http\Request;
use App\Models\User;
use Auth;
use File;
use Hash;
use Stancl\Tenancy\Database\Models\Domain;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    protected $country;
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            $this->user = \Auth::user();
            return $next($request);
        });
        $path = storage_path() . "/json/country.json";
        $this->countries = json_decode(file_get_contents($path), true);    }

    public function index()
    {
        if (!UtilityFacades::getsettings('2fa')) {
            $user = auth()->user();
            $role = $user->roles->first();
            $tenant_id = tenant('id');
            $countries = $this->countries;
            return view('superadmin.profile.index', [
                'user' => $user,
                'role' => $role,
                'tenant_id' => $tenant_id,
                'countries' => $countries,
            ]);
        }
        return $this->activeTwoFactor();
    }

    private function activeTwoFactor()
    {
        $user = Auth::user();
        $google2fa_url = "";
        $secret_key = "";
        if ($user->loginSecurity()->exists()) {
            $google2fa = (new \PragmaRX\Google2FAQRCode\Google2FA());
            $google2fa_url = $google2fa->getQRCodeInline(
                @UtilityFacades::getsettings('app_name'),
                $user->name,
                $user->loginSecurity->google2fa_secret
            );
            $secret_key = $user->loginSecurity->google2fa_secret;
        }
        $user = auth()->user();
        $role = $user->roles->first();
        $tenant_id = tenant('id');
        $countries = $this->countries;
        $data = array(
            'user' => $user,
            'secret' => $secret_key,
            'google2fa_url' => $google2fa_url,
            'tenant_id' => $tenant_id,
            'countries' => $countries

        );
        return view('superadmin.profile.index', [
            'user' => $user,
            'role' => $role,
            'secret' => $secret_key,
            'google2fa_url' => $google2fa_url,
            'tenant_id' => $tenant_id,
            'countries' => $countries

        ]);
    }

    public function updateLogin(Request $request)
    {
        $userDetail = Auth::user();
        $user       = User::findOrFail($userDetail['id']);
        $validator = \Validator::make(
            $request->all(),
            [
                'email' => 'required|email|unique:users,email,' . $userDetail['id'],
                'avatar' => 'image|mimes:jpeg,png,jpg,svg|max:3072',
                'password' => 'same:password_confirmation',

            ]
        );
        if ($validator->fails()) {
            $messages = $validator->getMessageBag();
            return redirect()->back()->with('errors', $messages->first());
        }
        if ($request->hasFile('avatar')) {
            $filenameWithExt = $request->file('avatar')->getClientOriginalName();
            $filename        = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension       = $request->file('avatar')->getClientOriginalExtension();
            $fileNameToStore = $filename . '_' . time() . '.' . $extension;
            $dir             = storage_path('avatar/');
            $image_path      = $dir . $userDetail['avatar'];

            if (File::exists($image_path)) {
                //File::delete($image_path);
            }
            if (!file_exists($dir)) {
                mkdir($dir, 0777, true);
            }
            $path = $request->file('avatar')->storeAs('avatar/', $fileNameToStore);
        }
        if (!empty($request->avatar)) {
            $user['avatar'] = 'avatar/' . $fileNameToStore;
        }
        if (!empty($request->password)) {
            $user->password = bcrypt($request->password);
        }
        $user['email'] = $request['email'];
        $user->save();
        return redirect()->back()->with('success', __('successfully updated.'));
    }

    private function generateCode()
    {
        $google2fa = app('pragmarx.google2fa');
        $generated = $google2fa->getQRCodeInline(
            config('app.name'),
            auth()->user()->name,
            auth()->user()->google2fa->google2fa_secret
        );
        return $generated;
    }

    public function activate()
    {
        $user = Auth::user();
        $google2fa = app('pragmarx.google2fa');
        $google2fa = $google2fa->generateSecretKey();
        TwoFactor::create([
            'user_id' => $user->id,
            'google2fa_enable' => 0,
            'google2fa_secret' => $google2fa
        ]);
        return redirect()->back()->with('success', __('2FA Activated.'));
    }

    public function enable(Request $request)
    {
        $this->validate($request, [
            'code' => 'required',
        ]);
        $user = Auth::user();
        $google2fa = app('pragmarx.google2fa');
        $verified = $google2fa->verifyKey($user->google2fa->google2fa_secret, $request->code);
        if ($verified) {
            $user->google2fa->google2fa_enable = 1;
            $user->google2fa->save();
            return redirect()->back()->with('success', __('2FA Enabled.'));
        }
        return redirect()->back()->with('fail', __('Verification Code is Invalid.'));
    }

    public function disable(Request $request)
    {
        $this->validate($request, [
            'code' => 'required',
            'password' => 'required',
        ]);
        $user = Auth::user();
        $google2fa = app('pragmarx.google2fa');
        if (Hash::check($request->password, $user->password)) {
            $verified = $google2fa->verifyKey($user->google2fa->google2fa_secret, $request->code);
            if ($verified) {
                $user->google2fa->delete();
                return redirect()->back()->with('success', '2FA Disabled');
            }
            return redirect()->back()->with('fail', __('Verification Code is Invalid.'));
        } else {
            return redirect()->back()->with('fail', __('Invalid Password! Check Password and try again.'));
        }
    }

    public function destroy($id)
    {
        if (\Auth::user()->can('delete-user')) {
            $user = User::find($id);
            $domain = Domain::where('tenant_id', $user->tenant_id)->first();
            $requestdomain = RequestDomain::where('email', $user->email)->first();
            if ($domain) {
                $domain->delete();
            }
            if ($requestdomain) {
                $requestdomain->delete();
            }
            return redirect()->route('users.index')->with('success', __('User deleted successfully'));
        } else {
            return redirect()->back()->with('failed', __('Permission denied.'));
        }
    }

    public function verify()
    {
        return redirect(URL()->previous());
    }

    public function instruction()
    {
        return view('superadmin.google2fa.instruction');
    }

    public function profileStatus()
    {
        $user = tenancy()->central(function ($tenant) {
            $central_user = User::find($tenant->id);
            $central_user->active_status = 0;
            $central_user->save();
        });
        $user = User::find(Auth::user()->id);
        $user->active_status = 0;
        $user->save();
        auth()->logout();
        return redirect()->route('home');
    }

    public function updateAvatar(Request $request, $id)
    {
        $disk = Storage::disk();
        $user = User::find($id);
        $this->validate($request, [
            'avatar' => 'required|',
        ]);
        $image = $request->avatar;
        $image = str_replace('data:image/png;base64,', '', $image);
        $image = str_replace(' ', '+', $image);
        $imagename = time() . '.' . 'png';
        $imagepath = "uploads/avatar/" . $imagename;
        $disk->put($imagepath, base64_decode($image));
        // $request->avatar->move(public_path('storage/profile'), $request->avatar);
        $user->avatar = $imagepath;
        if ($user->save()) {
            return __("Avatar updated successfully");
        }
        return __("Avatar updated failed");
    }

    public function update(Request $request, $id)
    {
        $user = User::find($id);
        $this->validate($request, [
            'fullname' => 'required|regex:/^[A-Za-z0-9_.,() ]+$/|max:255',
            'address' => 'nullable|regex:/^[A-Za-z0-9_.,() ]+$/|string',
            'country' => 'nullable|string',
            'phone' => 'nullable|string',
        ], [
            'fullname.regex' =>  __('Invalid Entry! The fullname only letter and numbers are allowed'),
            'address.regex' =>  __('Invalid Entry! The address only letter and numbers are allowed'),
        ]);
        $user->name = $request->fullname;
        $user->address = $request->address;
        $user->country = $request->country;
        $user->phone = $request->phone;
        $user->save();
        return redirect()->back()->with('success',  __('Account details updated successfully.'));
    }
}
