<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Facades\UtilityFacades;
use App\Mail\Admin\TestMail;
use App\Models\ChangeDomainRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use PHPUnit\Util\Test;
use Spatie\MailTemplates\Models\MailTemplate;
use Str;

class SettingsController extends Controller
{
    public function index()
    {
        $user = Auth::user()->tenant_id;
        $order = tenancy()->central(function ($tenant) use ($user) {
            $change_domain_request = ChangeDomainRequest::where('tenant_id', $user)->latest()->first();
            return $change_domain_request;
        });

        return view('admin.settings.index', compact('order'));
    }

    public function appNameUpdate(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'app_logo' => 'nullable|image|max:2048|mimes:png',
            'app_small_logo' => 'nullable|image|max:2048|mimes:png',
            'app_dark_logo' => 'nullable|image|max:2048|mimes:png',
            'favicon_logo' => 'nullable|image|max:2048|mimes:png',
            'app_name' => 'required'
        ]);
        if ($validator->fails()) {
            $messages = $validator->errors();
            return redirect()->back()->with('errors', $messages->first());
        }
        $data = [];
        if ($request->app_logo) {
            Storage::delete(UtilityFacades::getsettings('app_logo'));
            $app_logo_name = 'app-logo.' . $request->app_logo->extension();
            $request->app_logo->storeAs('logo', $app_logo_name);
            $data['app_logo'] = 'logo/' . $app_logo_name;
        }
        if ($request->app_dark_logo) {
            Storage::delete(UtilityFacades::getsettings('app_dark_logo'));
            $app_dark_logo_name = 'app-dark-logo.' . $request->app_dark_logo->extension();
            $request->app_dark_logo->storeAs('logo', $app_dark_logo_name);
            $data['app_dark_logo'] = 'logo/' . $app_dark_logo_name;
        }
        if ($request->app_small_logo) {
            Storage::delete(UtilityFacades::getsettings('app_small_logo'));
            $app_small_logo_name = 'app-small-logo.' . $request->app_small_logo->extension();
            $request->app_small_logo->storeAs('logo', $app_small_logo_name);
            $data['app_small_logo'] = 'logo/' . $app_small_logo_name;
        }
        if ($request->favicon_logo) {
            Storage::delete(UtilityFacades::getsettings('favicon_logo'));
            $favicon_logo_name = 'app-favicon-logo.' . $request->favicon_logo->extension();
            $request->favicon_logo->storeAs('logo', $favicon_logo_name);
            $data['favicon_logo'] = 'logo/' . $favicon_logo_name;
        }
        $data['app_name'] = $request->app_name;
        foreach ($data as $key => $value) {
            UtilityFacades::storesettings(['key' => $key, 'value' => $value]);
        }
        return redirect()->back()->with('success', __('App Setting updated successfully.'));
    }

    public function pusherSettingUpdate(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'pusher_id' => 'required|regex:/^[0-9]+$/',
            'pusher_key' => 'required|regex:/^[A-Za-z0-9_.,()]+$/',
            'pusher_secret' => 'required|regex:/^[A-Za-z0-9_.,()]+$/',
            'pusher_cluster' => 'required|regex:/^[A-Za-z0-9_.,()]+$/',
        ], [
            'pusher_id.regex' => 'Invalid Entry! The pusher id only letters, underscore and numbers are allowed',
            'pusher_key.regex' => 'Invalid Entry! The pusher key only letters, underscore and numbers are allowed',
            'pusher_secret.regex' => 'Invalid Entry! The pusher secret only letters, underscore and numbers are allowed',
            'pusher_cluster.regex' => 'Invalid Entry! The pusher cluster only letters, underscore and numbers are allowed',
        ]);
        if ($validator->fails()) {
            $messages = $validator->errors();
            return redirect()->back()->with('errors', $messages->first());
        }
        $data = [
            'pusher_id' => $request->pusher_id,
            'pusher_key' => $request->pusher_key,
            'pusher_secret' => $request->pusher_secret,
            'pusher_cluster' => $request->pusher_cluster,
            'pusher_status' => ($request->pusher_status == 'on') ? 1 : 0,
        ];
        foreach ($data as $key => $value) {
            UtilityFacades::storesettings(['key' => $key, 'value' => $value]);
        }
        return redirect()->back()->with('success', __('Pusher API Keys updated successfully.'));
    }

    public function s3SettingUpdate(Request $request)
    {
        if ($request->settingtype == 's3') {
            $validator = \Validator::make($request->all(), [
                's3_key' => 'required',
                's3_secret' => 'required',
                's3_region' => 'required',
                's3_bucket' => 'required',
                's3_url' => 'required',
                's3_endpoint' => 'required',
            ], [
                's3_key.regex' => 'Invalid Entry! The s3 key only letters, underscore and numbers are allowed.',
                's3_secret.regex' => 'Invalid Entry! The s3 secret only letters, underscore and numbers are allowed.',
            ]);
            if ($validator->fails()) {
                $messages = $validator->errors();
                return redirect()->back()->with('errors', $messages->first());
            }
            $data = [
                's3_key' => $request->s3_key,
                's3_secret' => $request->s3_secret,
                's3_region' => $request->s3_region,
                's3_bucket' => $request->s3_bucket,
                's3_url' => $request->s3_url,
                's3_endpoint' => $request->s3_endpoint,
                'settingtype' => $request->settingtype,
            ];
            foreach ($data as $key => $value) {
                UtilityFacades::storesettings(['key' => $key, 'value' => $value]);
            }
        } else {
            UtilityFacades::storesettings(['key' => 'settingtype', 'value' => $request->settingtype]);
        }
        return redirect()->back()->with('success', __('S3 API Keys updated successfully.'));
    }

    public function emailSettingUpdate(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'mail_mailer' => 'required',
            'mail_host' => 'required',
            'mail_port' => 'required',
            'mail_username' => 'required|email',
            'mail_password' => 'required',
            'mail_encryption' => 'required',
            'mail_from_address' => 'required',
            'mail_from_name' => 'required',
        ], [
            'mail_mailer.regex' => 'Required Entry! The Mail Mailer Not allow empty.',
            'mail_host.regex' => 'Required Entry! The Mail Host Not allow empty.',
            'mail_port.regex' => 'Required Entry! The Mail Port Not allow empty.',
            'mail_username.regex' => 'Required Entry! The Username Mailer Not allow empty.',
            'mail_password.regex' => 'Required Entry! The Password Mailer Not allow empty.',
            'mail_encryption.regex' => 'Invalid Entry! The Mail encryption Mailer Not allow empty.',
            'mail_from_address.regex' => 'Invalid Entry! The Mail From Address Not allow empty.',
            'mail_from_name.regex' => 'Invalid Entry! The From name Not allow empty.',
        ]);
        if ($validator->fails()) {
            $messages = $validator->errors();
            return redirect()->back()->with('errors', $messages->first());
        }
        $data = [
            'mail_mailer' => $request->mail_mailer,
            'mail_host' => $request->mail_host,
            'mail_port' => $request->mail_port,
            'mail_username' => $request->mail_username,
            'mail_password' => $request->mail_password,
            'mail_encryption' => $request->mail_encryption,
            'mail_from_address' => $request->mail_from_address,
            'mail_from_name' => $request->mail_from_name,
        ];
        foreach ($data as $key => $value) {
            UtilityFacades::storesettings(['key' => $key, 'value' => $value]);
        }
        return redirect()->back()->with('success', __('Email Setting updated successfully.'));
    }

    public function socialSettingUpdate(Request $request)
    {
        if ($request->socialsetting) {
            if (in_array('google', $request->get('socialsetting'))) {
                $validator = \Validator::make($request->all(), [
                    'google_client_id' => 'required',
                    'google_client_secret' => 'required',
                    'google_redirect' => 'required',

                ]);
                if ($validator->fails()) {
                    $messages = $validator->errors();
                    return redirect()->back()->with('errors', $messages->first());
                }
                $data = [
                    'google_client_id' => $request->google_client_id,
                    'google_client_secret' => $request->google_client_secret,
                    'google_redirect' => $request->google_redirect,
                    'googlesetting' => (!empty($request->googlesetting)) ? 'on' : 'off',
                ];
                $googlestatus = 'on';
            }
            if (in_array('facebook', $request->get('socialsetting'))) {
                $validator = \Validator::make($request->all(), [
                    'facebook_client_id' => 'required',
                    'facebook_client_secret' => 'required',
                    'facebook_redirect' => 'required',

                ]);
                if ($validator->fails()) {
                    $messages = $validator->errors();
                    return redirect()->back()->with('errors', $messages->first());
                }
                $data = [
                    'facebook_client_id' => $request->facebook_client_id,
                    'facebook_client_secret' => $request->facebook_client_secret,
                    'facebook_redirect' => $request->facebook_redirect,
                    'facebooksetting' => (!empty($request->facebooksetting)) ? 'on' : 'off',
                ];
                $facebookstatus = 'on';
            }
            if (in_array('github', $request->get('socialsetting'))) {

                $validator = \Validator::make($request->all(), [
                    'github_client_id' => 'required',
                    'github_client_secret' => 'required',
                    'github_redirect' => 'required',
                ]);
                if ($validator->fails()) {
                    $messages = $validator->errors();
                    return redirect()->back()->with('errors', $messages->first());
                }
                $data = [
                    'github_client_id' => $request->github_client_id,
                    'github_client_secret' => $request->github_client_secret,
                    'github_redirect' => $request->github_redirect,
                    'githubsetting' => (!empty($request->githubsetting)) ? 'on' : 'off',
                ];
                $githubstatus = 'on';
            }
            if (in_array('linkedin', $request->get('socialsetting'))) {
                $validator = \Validator::make($request->all(), [

                    'linkedin_client_id' => 'required',
                    'linkedin_client_secret' => 'required',
                    'linkedin_redirect' => 'required',
                ]);
                if ($validator->fails()) {
                    $messages = $validator->errors();
                    return redirect()->back()->with('errors', $messages->first());
                }
                $data = [
                    'linkedin_client_id' => $request->linkedin_client_id,
                    'linkedin_client_secret' => $request->linkedin_client_secret,
                    'linkedin_redirect' => $request->linkedin_redirect,
                    'linkedinsetting' => (!empty($request->linkedinsetting)) ? 'on' : 'off',
                ];
                $linkedinstatus = 'on';
            }
            $data = [
                'google_client_id' => $request->google_client_id,
                'google_client_secret' => $request->google_client_secret,
                'google_redirect' => $request->google_redirect,
                'facebook_client_id' => $request->facebook_client_id,
                'facebook_client_secret' => $request->facebook_client_secret,
                'facebook_redirect' => $request->facebook_redirect,
                'github_client_id' => $request->github_client_id,
                'github_client_secret' => $request->github_client_secret,
                'github_redirect' => $request->github_redirect,
                'linkedin_client_id' => $request->linkedin_client_id,
                'linkedin_client_secret' => $request->linkedin_client_secret,
                'linkedin_redirect' => $request->linkedin_redirect,
                'googlesetting' => (in_array('google', $request->get('socialsetting'))) ? 'on' : 'off',
                'facebooksetting' => (in_array('facebook', $request->get('socialsetting'))) ? 'on' : 'off',
                'githubsetting' => (in_array('github', $request->get('socialsetting'))) ? 'on' : 'off',
                'linkedinsetting' => (in_array('linkedin', $request->get('socialsetting'))) ? 'on' : 'off',
            ];
        } else {
            $data = [
                'googlesetting' => 'off',
                'facebooksetting' => 'off',
                'githubsetting' => 'off',
                'linkedinsetting' => 'off',
            ];
        }
        foreach ($data as $key => $value) {
            UtilityFacades::storesettings(['key' => $key, 'value' => $value]);
        }
        return redirect()->back()->with('success', __('Social setting updated successfully.'));
    }

    public function paymentSettingUpdate(Request $request)
    {
        $this->validate($request, [
            'paymentsetting' => 'required|min:1'
        ]);
        if (in_array('stripe', $request->get('paymentsetting'))) {

            $validator = \Validator::make($request->all(), [
                'stripe_key' => 'required',
                'stripe_secret' => 'required',
            ]);
            if ($validator->fails()) {
                $messages = $validator->errors();
                return redirect()->back()->with('errors', $messages->first());
            }
            $data = [
                'stripe_key' => $request->stripe_key,
                'stripe_secret' => $request->stripe_secret,
                'stripesetting' => (in_array('stripe', $request->get('paymentsetting'))) ? 'on' : 'off',
            ];
            $stripestatus = 'on';
        }

        if (in_array('paypal', $request->paymentsetting)) {
            if ($request->paypal_mode == 'sandbox') {

                $validator = \Validator::make($request->all(), [
                    'client_id' => 'required',
                    'client_secret' => 'required',

                ]);
                if ($validator->fails()) {
                    $messages = $validator->errors();
                    return redirect()->back()->with('errors', $messages->first());
                }
                $data = [
                    'paypal_sandbox_client_id' => $request->client_id,
                    'paypal_sandbox_client_secret' => $request->client_secret,
                    'paypalsetting' => (in_array('paypal', $request->get('paymentsetting'))) ? 'on' : 'off',
                ];
                $paypalstatus = 'on';
            } else {
                $validator = \Validator::make($request->all(), [
                    'client_id' => 'required',
                    'client_secret' => 'required',
                ]);
                if ($validator->fails()) {
                    $messages = $validator->errors();
                    return redirect()->back()->with('errors', $messages->first());
                }
                $data = [
                    'paypal_live_client_id' => $request->client_id,
                    'paypal_live_client_secret' => $request->client_secret,
                    'paypalsetting' => (in_array('paypal', $request->get('paymentsetting'))) ? 'on' : 'off',
                ];
                $paypalstatus = 'on';
            }
        }

        if (in_array('razorpay', $request->paymentsetting)) {

            $validator = \Validator::make($request->all(), [
                'razorpay_key' => 'required',
                'razorpay_secret' => 'required',
            ]);
            if ($validator->fails()) {
                $messages = $validator->errors();
                return redirect()->back()->with('errors', $messages->first());
            }
            $data = [
                'razorpay_key' => $request->razorpay_key,
                'razorpay_secret' =>  $request->razorpay_secret,
                'razorpaysetting' => (in_array('razorpay', $request->get('paymentsetting'))) ? 'on' : 'off',
            ];
            $razorpaystatus = 'on';
        }

        if (in_array('offline', $request->paymentsetting)) {

            $validator = \Validator::make($request->all(), [
                'payment_details' => 'required',

            ]);
            if ($validator->fails()) {
                $messages = $validator->errors();
                return redirect()->back()->with('errors', $messages->first());
            }
            $data = [
                'payment_details' =>  $request->payment_details,
                'offlinesetting' => (in_array('offline', $request->get('paymentsetting'))) ? 'on' : 'off',
            ];
            $Offlinestatus = 'on';
        }

        if (in_array('flutterwave', $request->get('paymentsetting'))) {

            $validator = \Validator::make($request->all(), [
                'flutterwave_key' => 'required',
                'flutterwave_secret' => 'required',

            ]);
            if ($validator->fails()) {
                $messages = $validator->errors();
                return redirect()->back()->with('errors', $messages->first());
            }
            $data = [
                'flutterwave_key' => $request->flutterwave_key,
                'flutterwave_secret' => $request->flutterwave_secret,
                'flutterwavesetting' => (in_array('flutterwave', $request->get('paymentsetting'))) ? 'on' : 'off',
            ];
            $flutterwavestatus = 'on';
        }

        if (in_array('paystack', $request->get('paymentsetting'))) {

            $validator = \Validator::make($request->all(), [
                'public_key' => 'required',
                'secret_key' => 'required',

            ]);
            if ($validator->fails()) {
                $messages = $validator->errors();
                return redirect()->back()->with('errors', $messages->first());
            }
            $data = [
                'paystack_public_key' => $request->public_key,
                'paystack_secret_key' => $request->secret_key,
                'paystacksetting' => (in_array('paystack', $request->get('paymentsetting'))) ? 'on' : 'off',
            ];
            $paystackstatus = 'on';
        }
        if (in_array('paytm', $request->get('paymentsetting'))) {

            $validator = \Validator::make($request->all(), [
                'merchant_id' => 'required',
                'merchant_key' => 'required',
                'paytm_environment' => 'required',
            ]);
            if ($validator->fails()) {
                $messages = $validator->errors();
                return redirect()->back()->with('errors', $messages->first());
            }
            $data = [
                'PAYTM_MERCHANT_ID' => $request->merchant_id,
                'PAYTM_MERCHANT_KEY' => $request->merchant_key,
                'PAYTM_ENVIRONMENT' =>  $request->paytm_environment,
                'paytmsetting' => (in_array('merchant', $request->get('paymentsetting'))) ? 'on' : 'off',
                'PAYTM_DESCRIPTION' => $request->merchant_description,
            ];
            $paytmstatus = 'on';
        }
        if (in_array('coingate', $request->get('paymentsetting'))) {
            $validator = \Validator::make($request->all(), [
                'coingate_mode' => 'required',
                'coingate_auth_token' => 'required',

            ]);
            if ($validator->fails()) {
                $messages = $validator->errors();
                return redirect()->back()->with('errors', $messages->first());
            }
            $data = [

                'coingate_environment' => $request->coingate_mode,
                'coingate_auth_token' => $request->coingate_auth_token,
                'coingatesetting' => (in_array('coingate', $request->get('paymentsetting'))) ? 'on' : 'off',
            ];
            $stripestatus = 'on';
        }
        if (in_array('mercado', $request->get('paymentsetting'))) {
            $validator = \Validator::make($request->all(), [
                'mercado_mode' => 'required',
                'mercado_access_token' => 'required',
            ]);
            if ($validator->fails()) {
                $messages = $validator->errors();
                return redirect()->back()->with('errors', $messages->first());
            }
            $data = [
                'mercado_mode' => $request->mercado_mode,
                'mercado_access_token' => $request->mercado_access_token,
                'mercadosetting' => (in_array('mercado', $request->get('paymentsetting'))) ? 'on' : 'off',
            ];
            $stripestatus = 'on';
        }
        $data = [
            'stripe_key' => $request->stripe_key,
            'stripe_secret' => $request->stripe_secret,
            'stripe_description' => $request->stripe_description,

            'paypal_description' => $request->paypal_description,

            'paypal_mode' => $request->paypal_mode,
            'paypal_client_id' => $request->client_id,
            'paypal_client_secret' => $request->client_secret,

            'razorpay_key' => $request->razorpay_key,
            'razorpay_secret' =>  $request->razorpay_secret,
            'razorpay_description' => $request->razorpay_description,

            'payment_details' =>  $request->payment_details,

            'flutterwave_key' => $request->flutterwave_key,
            'flutterwave_secret' => $request->flutterwave_secret,
            'flutterwave_description' => $request->flutterwave_description,

            'paystack_public_key' => $request->public_key,
            'paystack_secret_key' => $request->secret_key,
            'paystack_description' => $request->paystack_description,
            'paystack_currency' => $request->paystack_currency,

            'paytm_merchant_id' => $request->merchant_id,
            'paytm_merchant_key' => $request->merchant_key,
            'paytm_environment' =>  $request->paytm_environment,
            'paytm_merchant_website' => 'local',
            'paytm_channel' => 'WEB',
            'paytm_description' => $request->paytm_description,

            'coingate_environment' => $request->coingate_mode,
            'coingate_auth_token' => $request->coingate_auth_token,
            'coingate_description' => $request->coingate_description,

            'mercado_mode' => $request->mercado_mode,
            'mercado_access_token' => $request->mercado_access_token,
            'mercado_description' => $request->mercado_description,

            'mercadosetting' => (in_array('mercado', $request->get('paymentsetting'))) ? 'on' : 'off',
            'stripesetting' => (in_array('stripe', $request->get('paymentsetting'))) ? 'on' : 'off',
            'paypalsetting' => (in_array('paypal', $request->get('paymentsetting'))) ? 'on' : 'off',
            'razorpaysetting' => (in_array('razorpay', $request->get('paymentsetting'))) ? 'on' : 'off',
            'offlinesetting' => (in_array('offline', $request->get('paymentsetting'))) ? 'on' : 'off',
            'flutterwavesetting' => (in_array('flutterwave', $request->get('paymentsetting'))) ? 'on' : 'off',
            'paystacksetting' => (in_array('paystack', $request->get('paymentsetting'))) ? 'on' : 'off',
            'paytmsetting' => (in_array('paytm', $request->get('paymentsetting'))) ? 'on' : 'off',
            'coingatesetting' => (in_array('coingate', $request->get('paymentsetting'))) ? 'on' : 'off',

        ];
        if (Auth::user()->type == 'Admin') {
            foreach ($data as $key => $value) {
                UtilityFacades::storesettings(['key' => $key, 'value' => $value]);
            }
        } else {
            foreach ($data as $key => $value) {
                UtilityFacades::setEnvironmentValue([$key => $value]);
            }
        }

        return redirect()->back()->with('success', __('Payment setting updated successfully.'));
    }

    public function authSettingsUpdate(Request $request)
    {
        if ($request->database_permission == 'on') {
            try {

                DB::statement('create database test_db');
            } catch (\Exception $e) {
                return redirect()->back()->with('errors', __('Please give permission to create database to user.'));
            }
            DB::statement('drop database test_db');
        }
        $data = [
            '2fa' => ($request->two_factor_auth == 'on') ? 1 : 0,
            'rtl' => ($request->rtl_setting == 'on') ? '1' : '0',
            'date_format' => $request->date_format,
            'time_format' => $request->time_format,
            'gtag' => $request->gtag,
            'default_language' => $request->default_language,
            'currency' => $request->currency,
            'currency_symbol' => $request->currency_symbol,
            'dark_mode' => $request->dark_mode,
            'color' => ($request->color) ? $request->color : UtilityFacades::getsettings('color'),
            'database_permission' => ($request->database_permission == 'on') ? '1' : '0',
            'email_verification' => ($request->email_verification == 'on') ? '1' : '0',
            'roles' => $request->roles,
            'landing_page_status' => ($request->landing_page_status == 'on') ? '1' : '0',
        ];
        foreach ($data as $key => $value) {
            UtilityFacades::storesettings(['key' => $key, 'value' => $value]);
        }
        return redirect()->back()->with('success', __('General settings updated successfully.'));
    }


    public function backupFiles()
    {
        Artisan::call('backup:run', ['--only-files' => true]);
        $output = Artisan::output();
        if (Str::contains($output, 'Backup completed!')) {
            return redirect()->back()->with('success', __('Application files backed-up successfully.'));
        } else {
            return redirect()->back()->with('errors', __('Application files backed-up failed.'));
        }
    }

    public function backupDb()
    {
        Artisan::call('backup:run', ['--only-db' => true]);
        $output = Artisan::output();
        if (Str::contains($output, 'Backup completed!')) {
            return redirect()->back()->with('success', __('Application database backed-up successfully.'));
        } else {
            return redirect()->back()->with('errors', __('Application database backed-up failed.'));
        }
    }

    private function getBackups()
    {
        $path = storage_path('app/app-backups');
        if (!File::isDirectory($path)) {
            File::makeDirectory($path, 0777, true, true);
        }
        $files = File::allFiles($path);
        $backups = collect([]);
        foreach ($files as $dt) {
            $backups->push([
                'filename' => pathinfo($dt->getFilename(), PATHINFO_FILENAME),
                'extension' => pathinfo($dt->getFilename(), PATHINFO_EXTENSION),
                'path' => $dt->getPath(),
                'size' => $dt->getSize(),
                'time' => $dt->getMTime(),
            ]);
        }
        return $backups;
    }

    public function downloadBackup($name, $ext)
    {
        $path = storage_path('app/app-backups');
        $file = $path . '/' . $name . '.' . $ext;
        $status = Storage::disk('backup')->download($name . '.' . $ext, $name . '.' . $ext);
        return $status;
    }
    public function deleteBackup($name, $ext)
    {
        $path = storage_path('app/app-backups');
        $file = $path . '/' . $name . '.' . $ext;
        $status = File::delete($file);
        if ($status) {
            return redirect()->back()->with('success', __('Backup deleted successfully.'));
        } else {
            return redirect()->back()->with('errors', __('Opps! an error occured, Try Again.'));
        }
    }

    function loadsetting($type)
    {
        $t =  ucfirst(str_replace('-', ' ', $type));
        $tenant_id = tenant('id');
        return view('settings.' . $type, compact('t', 'tenant_id'));
    }
    public function testMail()
    {
        return view('admin.settings.test-mail');
    }
    public function testSendMail(Request $request)
    {
        $validator = \Validator::make($request->all(), ['email' => 'required|email']);
        if ($validator->fails()) {
            $messages = $validator->getMessageBag();
            return redirect()->back()->with('errors', $messages->first());
        }
        if (MailTemplate::where('mailable', TestMail::class)->first()) {
            try {
                Mail::to($request->email)->send(new TestMail());
            } catch (\Exception $e) {
                return redirect()->back()->with('errors', $e->getMessage());
            }
        }
        return redirect()->back()->with('success', __('Email send successfully.'));
    }

    // public function landingPage(Request $request)
    // {
    //     return view('admin.settings.landing');
    // }

    // public function landingPagestore(Request $request)
    // {
    //     $data = [
    //         'footer_page_content' => $request->footer_page_content,
    //         'privacy' => $request->privacy,
    //         'contact_us' => $request->contact_us,
    //         'term_condition' => $request->term_condition,
    //         'faq_page_content' => $request->faq_page_content,
    //         'latitude' => $request->latitude,
    //         'longitude' => $request->longitude,
    //         'recaptcha_key' => $request->recaptcha_key,
    //         'recaptcha_secret' => $request->recaptcha_secret,
    //         'contact_us_email' => $request->contact_us_email,
    //         'captcha_status' => ($request->captcha_status == 'on') ? 1 : 0,
    //     ];
    //     foreach ($data as $key => $value) {
    //         UtilityFacades::storesettings(['key' => $key, 'value' => $value]);
    //     }
    //     return redirect()->back()->with('success', __('Landing page setting updated successfully.'));
    // }

    public function froentendsetting(Request $request)
    {
        return view('admin.settings.froentend');
    }

    public function froentendsettingstore(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'apps_title' => 'required',
            'apps_paragraph' => 'required',
            'image' => 'image|mimes:svg',
        ]);
        if ($validator->fails()) {
            $messages = $validator->errors();
            return redirect()->back()->with('errors', $messages->first());
        }
        $data = [
            'apps_title' => $request->apps_title,
            'apps_paragraph' => $request->apps_paragraph,
        ];
        if ($request->image) {
            Storage::delete(UtilityFacades::getsettings('image'));
            $image_name = 'header_mokeup1.' . $request->image->extension();
            $request->image->storeAs('landingpage', $image_name);
            $data['image'] = 'landingpage/' . $image_name;
        }
        foreach ($data as $key => $value) {
            UtilityFacades::storesettings(['key' => $key, 'value' => $value]);
        }
        return redirect()->back()->with('success', __('Frontend page setting updated successfully.'));
    }

    public function menusettingstore(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'menu_name' => 'required',
            'menu_title' => 'required',
            'menu_subtitle' => 'required',
            'menu_paragraph' => 'required',
            'images1' => 'image|mimes:png,jpg,jpeg',

            'submenu_name' => 'required',
            'submenu_title' => 'required',
            'submenu_subtitle' => 'required',
            'submenu_paragraph' => 'required',
            'images2' => 'image|mimes:svg',
        ]);
        if ($validator->fails()) {
            $messages = $validator->errors();
            return redirect()->back()->with('errors', $messages->first());
        }
        $data = [
            'menu_name' => $request->menu_name,
            'menu_title' => $request->menu_title,
            'menu_subtitle' => $request->menu_subtitle,
            'menu_paragraph' => $request->menu_paragraph,
            'submenu_name' => $request->submenu_name,
            'submenu_title' => $request->submenu_title,
            'submenu_subtitle' => $request->submenu_subtitle,
            'submenu_paragraph' => $request->submenu_paragraph,
        ];
        if ($request->images1) {
            Storage::delete(UtilityFacades::getsettings('images1'));
            $images1_name = 'dashboard.' . $request->images1->extension();
            $request->images1->storeAs('landingpage', $images1_name);
            $data['images1'] = 'landingpage/' . $images1_name;
        }
        if ($request->images2) {
            Storage::delete(UtilityFacades::getsettings('images2'));
            $images2_name = 'img_crm_dash_21.' . $request->images2->extension();
            $request->images2->storeAs('landingpage', $images2_name);
            $data['images2'] = 'landingpage/' . $images2_name;
        }
        foreach ($data as $key => $value) {
            UtilityFacades::storesettings(['key' => $key, 'value' => $value]);
        }
        return redirect()->back()->with('success', __('Frontend page setting updated successfully.'));
    }

    public function featuresettingstore(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'feature_name' => 'required',
            'feature_title' => 'required',
            'feature_paragraph' => 'required',
        ]);
        if ($validator->fails()) {
            $messages = $validator->errors();
            return redirect()->back()->with('errors', $messages->first());
        }
        $data = [
            'feature_name' => $request->feature_name,
            'feature_title' => $request->feature_title,
            'feature_paragraph' => $request->feature_paragraph,
            'feature_setting' => json_encode($request->feature_setting),
        ];
        foreach ($data as $key => $value) {
            UtilityFacades::storesettings(['key' => $key, 'value' => $value]);
        }
        return redirect()->back()->with('success', __('Frontend page setting updated successfully.'));
    }

    public function postsettingstore(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'post_name' => 'required',
            'post_title' => 'required',
        ]);
        if ($validator->fails()) {
            $messages = $validator->errors();
            return redirect()->back()->with('errors', $messages->first());
        }
        $data = [
            'post_name' => $request->post_name,
            'post_title' => $request->post_title,
        ];
        foreach ($data as $key => $value) {
            UtilityFacades::storesettings(['key' => $key, 'value' => $value]);
        }
        return redirect()->back()->with('success', __('Frontend page setting updated successfully.'));
    }

    public function faqsettingstore(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'faq_title' => 'required',
            'faq_paragraph' => 'required',
            'faq_page_content' => 'required',
        ]);
        if ($validator->fails()) {
            $messages = $validator->errors();
            return redirect()->back()->with('errors', $messages->first());
        }
        $data = [
            'faq_title' => $request->faq_title,
            'faq_paragraph' => $request->faq_paragraph,
            'faq_page_content' => $request->faq_page_content,
        ];
        foreach ($data as $key => $value) {
            UtilityFacades::storesettings(['key' => $key, 'value' => $value]);
        }
        return redirect()->back()->with('success', __('Frontend page setting updated successfully.'));
    }

    public function sidefeaturesettingstore(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'sidefeature_name' => 'required',
            'sidefeature_title' => 'required',
            'sidefeature_subtitle' => 'required',
            'sidefeature_paragraph' => 'required',
            'image1' => 'image|mimes:png,jpg,jpeg',
            'image2' => 'image|mimes:png,jpg,jpeg',
            'image3' => 'image|mimes:png,jpg,jpeg',
            'image4' => 'image|mimes:png,jpg,jpeg',
            'image5' => 'image|mimes:png,jpg,jpeg',
            'image6' => 'image|mimes:png,jpg,jpeg',
            'image7' => 'image|mimes:png,jpg,jpeg',
            'image8' => 'image|mimes:png,jpg,jpeg',
        ]);
        if ($validator->fails()) {
            $messages = $validator->errors();
            return redirect()->back()->with('errors', $messages->first());
        }
        $data = [
            'sidefeature_name' => $request->sidefeature_name,
            'sidefeature_title' => $request->sidefeature_title,
            'sidefeature_subtitle' => $request->sidefeature_subtitle,
            'sidefeature_paragraph' => $request->sidefeature_paragraph,
        ];
        if ($request->image1) {
            Storage::delete(UtilityFacades::getsettings('image1'));
            $image1_name = 'front1.' . $request->image1->extension();
            $request->image1->storeAs('landingpage', $image1_name);
            $data['image1'] = 'landingpage/' . $image1_name;
        }
        if ($request->image2) {
            Storage::delete(UtilityFacades::getsettings('image2'));
            $image2_name = 'front2.' . $request->image2->extension();
            $request->image2->storeAs('landingpage', $image2_name);
            $data['image2'] = 'landingpage/' . $image2_name;
        }
        if ($request->image3) {
            Storage::delete(UtilityFacades::getsettings('image3'));
            $image3_name = 'front3.' . $request->image3->extension();
            $request->image3->storeAs('landingpage', $image3_name);
            $data['image3'] = 'landingpage/' . $image3_name;
        }
        if ($request->image4) {
            Storage::delete(UtilityFacades::getsettings('image4'));
            $image4_name = 'front4.' . $request->image4->extension();
            $request->image4->storeAs('landingpage', $image4_name);
            $data['image4'] = 'landingpage/' . $image4_name;
        }
        if ($request->image5) {
            Storage::delete(UtilityFacades::getsettings('image5'));
            $image5_name = 'front5.' . $request->image5->extension();
            $request->image5->storeAs('landingpage', $image5_name);
            $data['image5'] = 'landingpage/' . $image5_name;
        }
        if ($request->image6) {
            Storage::delete(UtilityFacades::getsettings('image6'));
            $image6_name = 'front6.' . $request->image6->extension();
            $request->image6->storeAs('landingpage', $image6_name);
            $data['image6'] = 'landingpage/' . $image6_name;
        }
        if ($request->image7) {
            Storage::delete(UtilityFacades::getsettings('image7'));
            $image7_name = 'front7.' . $request->image7->extension();
            $request->image7->storeAs('landingpage', $image7_name);
            $data['image7'] = 'landingpage/' . $image7_name;
        }
        if ($request->image8) {
            Storage::delete(UtilityFacades::getsettings('image8'));
            $image8_name = 'front8.' . $request->image8->extension();
            $request->image8->storeAs('landingpage', $image8_name);
            $data['image8'] = 'landingpage/' . $image8_name;
        }
        foreach ($data as $key => $value) {
            UtilityFacades::storesettings(['key' => $key, 'value' => $value]);
        }
        return redirect()->back()->with('success', __('Frontend page setting updated successfully.'));
    }

    public function privacysettingstore(Request $request)
    {
        $validator = \Validator::make($request->all(), [
        ]);
        if ($validator->fails()) {
            $messages = $validator->errors();
            return redirect()->back()->with('errors', $messages->first());
        }
        $data = [
            'privacy' => $request->privacy,
        ];
        foreach ($data as $key => $value) {
            UtilityFacades::storesettings(['key' => $key, 'value' => $value]);
        }
        return redirect()->back()->with('success', __('Privacy setting updated successfully.'));
    }

    public function contactussettingstore(Request $request)
    {
        $validator = \Validator::make($request->all(), [
        ]);
        if ($validator->fails()) {
            $messages = $validator->errors();
            return redirect()->back()->with('errors', $messages->first());
        }
        $data = [
            'footer_page_content' => $request->footer_page_content,
            'contact_us' => $request->contact_us,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'recaptcha_key' => $request->recaptcha_key,
            'recaptcha_secret' => $request->recaptcha_secret,
            'contact_us_email' => $request->contact_us_email,
            'captcha_status' => ($request->captcha_status == 'on') ? 1 : 0,
        ];
        foreach ($data as $key => $value) {
            UtilityFacades::storesettings(['key' => $key, 'value' => $value]);
        }
        return redirect()->back()->with('success', __('Contact us setting updated successfully.'));
    }

    public function termconditionsettingstore(Request $request)
    {
        $validator = \Validator::make($request->all(), [
        ]);
        if ($validator->fails()) {
            $messages = $validator->errors();
            return redirect()->back()->with('errors', $messages->first());
        }
        $data = [
            'term_condition' => $request->term_condition,
        ];
        foreach ($data as $key => $value) {
            UtilityFacades::storesettings(['key' => $key, 'value' => $value]);
        }
        return redirect()->back()->with('success', __('Term & condition setting updated successfully.'));
    }

    public function upload(Request $request)
    {
        if ($request->hasFile('upload')) {
            $originName = $request->file('upload')->getClientOriginalName();
            $fileName = pathinfo($originName, PATHINFO_FILENAME);
            $extension = $request->file('upload')->getClientOriginalExtension();
            $fileName = $fileName . '_' . time() . '.' . $extension;
            $request->file('upload')->move(public_path('images'), $fileName);
            $CKEditorFuncNum = $request->input('CKEditorFuncNum');
            $url = asset('public/images/' . $fileName);
            $msg = 'Image uploaded successfully';
            $response = "<script>window.parent.CKEDITOR.tools.callFunction($CKEditorFuncNum, '$url', '$msg')</script>";
            @header('Content-type: text/html; charset=utf-8');
            echo $response;
        }
    }

    public function changeDomainRequest(Request $request)
    {
        $order = tenancy()->central(function ($tenant) use ($request) {
            $validator = \Validator::make($request->all(), [
                'domain_name' => 'required',
                'actual_domain_name' => 'required'
            ]);
            if ($validator->fails()) {
                $messages = $validator->errors();
                return redirect()->back()->with('errors', $messages->first());
            }
            $data['name'] = Auth::user()->name;
            $data['email'] = Auth::user()->email;
            $data['domain_name'] = $request->domain_name;
            $data['actual_domain_name'] = $request->actual_domain_name;
            $data['tenant_id'] = Auth::user()->tenant_id;
            $data['status'] = 0;
            $datas = ChangeDomainRequest::create($data);
        });
        return redirect()->back()->with('success', __('Change domain request send successfully.'));
    }
}
