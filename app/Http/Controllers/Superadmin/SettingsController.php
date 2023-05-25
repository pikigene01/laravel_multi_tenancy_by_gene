<?php

namespace App\Http\Controllers\Superadmin;

use App\Facades\Utility;
use App\Http\Controllers\Controller;
use App\Facades\UtilityFacades;
use App\Mail\Superadmin\TestMail;
use App\Models\Setting;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Spatie\MailTemplates\Models\MailTemplate;
use Str;

class SettingsController extends Controller
{
    public function index()
    {
        return view('superadmin.settings.index');
    }

    public function appNameUpdate(Request $request)
    {
        $this->validate($request, [
            'app_logo' => 'nullable|image|max:2048|mimes:png',
            'app_small_logo' => 'nullable|image|max:2048|mimes:png',
            'app_dark_logo' => 'nullable|image|max:2048|mimes:png',
            'favicon_logo' => 'nullable|image|max:2048|mimes:png',
            'app_name' => 'required'
        ]);
        $data = [];
        if ($request->app_logo) {
            Storage::delete(UtilityFacades::getsettings('app_logo'));
            $app_logo_name = 'app-logo.' . $request->app_logo->extension();
            $request->app_logo->storeAs('logo', $app_logo_name);
            $request->app_logo->move(public_path('storage/logo'), $app_logo_name);
            $data['app_logo'] = 'logo/' . $app_logo_name;
        }
        if ($request->app_dark_logo) {
            Storage::delete(UtilityFacades::getsettings('app_dark_logo'));
            $app_dark_logo_name = 'app-dark-logo.' . $request->app_dark_logo->extension();
            $request->app_dark_logo->storeAs('logo', $app_dark_logo_name);
            $request->app_dark_logo->move(public_path('storage/logo'), $app_dark_logo_name);
            $data['app_dark_logo'] = 'logo/' . $app_dark_logo_name;
        }
        if ($request->app_small_logo) {
            Storage::delete(UtilityFacades::getsettings('app_small_logo'));
            $app_small_logo_name = 'app-small-logo.' . $request->app_small_logo->extension();
            $request->app_small_logo->storeAs('logo', $app_small_logo_name);
            $request->app_small_logo->move(public_path('storage/logo'), $app_small_logo_name);
            $data['app_small_logo'] = 'logo/' . $app_small_logo_name;
        }
        if ($request->favicon_logo) {
            Storage::delete(UtilityFacades::getsettings('favicon_logo'));
            $favicon_logo_name = 'app-favicon-logo.' . $request->favicon_logo->extension();
            $request->favicon_logo->storeAs('logo', $favicon_logo_name);
            $request->favicon_logo->move(public_path('storage/logo'), $favicon_logo_name);
            $data['favicon_logo'] = 'logo/' . $favicon_logo_name;
        }
        $data['app_name'] = $request->app_name;
        foreach ($data as $key => $value) {
            UtilityFacades::storesettings(['key' => $key, 'value' => $value]);
        }
        return redirect()->back()->with('success', __('App Setting changed successfully'));
    }

    public function s3SettingUpdate(Request $request)
    {
        if ($request->settingtype == 's3') {
            $this->validate($request, [
                's3_key' => 'required',
                's3_secret' => 'required',
                's3_region' => 'required',
                's3_bucket' => 'required',
                's3_url' => 'required',
                's3_endpoint' => 'required',
            ], [
                's3_key.regex' => 'Invalid Entry! The s3 key only letters, underscore and numbers are allowed',
                's3_secret.regex' => 'Invalid Entry! The s3 secret only letters, underscore and numbers are allowed',
            ]);
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
        return redirect()->back()->with('success', __('S3 API Keys updated successfully'));
    }

    public function emailSettingUpdate(Request $request)
    {
        $this->validate($request, [
            'mail_mailer' => 'required',
            'mail_host' => 'required',
            'mail_port' => 'required',
            'mail_username' => 'required|email',
            'mail_password' => 'required',
            'mail_encryption' => 'required',
            'mail_from_address' => 'required',
            'mail_from_name' => 'required',
        ], [
            'mail_mailer.regex' => 'Required Entry! The Mail Mailer Not allow empty',
            'mail_host.regex' => 'Required Entry! The Mail Host Not allow empty',
            'mail_port.regex' => 'Required Entry! The Mail Port Not allow empty',
            'mail_username.regex' => 'Required Entry! The Username Mailer Not allow empty',
            'mail_password.regex' => 'Required Entry! The Password Mailer Not allow empty',
            'mail_encryption.regex' => 'Invalid Entry! The Mail encryption Mailer Not allow empty',
            'mail_from_address.regex' => 'Invalid Entry! The Mail From Address Not allow empty',
            'mail_from_name.regex' => 'Invalid Entry! The From name Not allow empty',
        ]);
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
        return redirect()->back()->with('success', __('Email Setting updated successfully'));
    }

    public function paymentSettingUpdate(Request $request)
    {
        $this->validate($request, [
            'paymentsetting' => 'required|min:1'
        ]);
        $stripestatus = 'off';
        $paypalstatus = 'off';
        $razorpaystatus = 'off';
        $Offlinestatus = 'off';
        $flutterwavestatus = 'off';
        $paystackstatus = 'off';
        $paytmstatus = 'off';
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
            $validator = \Validator::make($request->all(), [
                'client_id' => 'required',
                'client_secret' => 'required',

            ]);
            if ($validator->fails()) {
                $messages = $validator->errors();
                return redirect()->back()->with('errors', $messages->first());
            }

            $datas = [
                'PAYPAL_SANDBOX_CLIENT_ID' => $request->client_id,
                'PAYPAL_SANDBOX_CLIENT_SECRET' => $request->client_secret,
            ];
            $paypalstatus = 'on';
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
                'merchant_description' => $request->merchant_description,
            ];
            $merchantstatus = 'on';
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

            // 'PAYPAL_MODE'=>$request->paypal_mode,
            // 'PAYPAL_SANDBOX_CLIENT_ID' => $request->client_id,
            // 'PAYPAL_SANDBOX_CLIENT_SECRET' => $request->client_secret,

            'coingate_environment' => $request->coingate_mode,
            'coingate_auth_token' => $request->coingate_auth_token,
            'paytm_environment' => $request->paytm_environment,
            'paytm_description' => $request->paytm_description,
            'coingate_description' => $request->coingate_description,

            'paypal_description' => $request->paypal_description,
            'paypal_mode' => $request->paypal_mode,
            'paypal_client_id' => $request->client_id,
            'paypal_client_secret' => $request->client_secret,

            'mercado_mode' => $request->mercado_mode,
            'mercado_access_token' => $request->mercado_access_token,
            'mercado_description' => $request->mercado_description,

            'mercadosetting' => (in_array('mercado', $request->get('paymentsetting'))) ? 'on' : 'off',
            'paytmsetting' => (in_array('paytm', $request->get('paymentsetting'))) ? 'on' : 'off',
            'stripesetting' => (in_array('stripe', $request->get('paymentsetting'))) ? 'on' : 'off',
            'paypalsetting' => (in_array('paypal', $request->get('paymentsetting'))) ? 'on' : 'off',
            'razorpaysetting' => (in_array('razorpay', $request->get('paymentsetting'))) ? 'on' : 'off',
            'offlinesetting' => (in_array('offline', $request->get('paymentsetting'))) ? 'on' : 'off',
            'flutterwavesetting' => (in_array('flutterwave', $request->get('paymentsetting'))) ? 'on' : 'off',
            'paystacksetting' => (in_array('paystack', $request->get('paymentsetting'))) ? 'on' : 'off',
            'coingatesetting' => (in_array('coingate', $request->get('paymentsetting'))) ? 'on' : 'off',
        ];
        foreach ($data as $key => $value) {
            UtilityFacades::storesettings(['key' => $key, 'value' => $value]);
        }
        if ($request->paypal_mode == 'sandbox') {
            $datas = [
                'PAYPAL_MODE' => $request->paypal_mode,
                'PAYPAL_SANDBOX_CLIENT_ID' => $request->client_id,
                'PAYPAL_SANDBOX_CLIENT_SECRET' => $request->client_secret,
                'PAYTM_MERCHANT_ID' => $request->merchant_id,
                'PAYTM_MERCHANT_KEY' => $request->merchant_key,
                'PAYTM_ENVIRONMENT' => $request->paytm_environment,
                'PAYTM_MERCHANT_WEBSITE' => 'local',
                'PAYTM_CHANNEL' => 'WEB',
            ];
        } else {
            $datas = [
                'PAYPAL_MODE' => $request->paypal_mode,
                'PAYPAL_LIVE_CLIENT_ID' => $request->client_id,
                'PAYPAL_LIVE_CLIENT_SECRET' => $request->client_secret,
                'PAYTM_MERCHANT_ID' => $request->merchant_id,
                'PAYTM_MERCHANT_KEY' => $request->merchant_key,
                'PAYTM_ENVIRONMENT' =>  $request->paytm_environment,
                'PAYTM_MERCHANT_WEBSITE' => 'local',
                'PAYTM_CHANNEL' => 'WEB',
            ];
        }
        foreach ($datas as $key => $value) {
            UtilityFacades::setEnvironmentValue([$key => $value]);
        }
        return redirect()->back()->with('success', __('Payment setting updated successfully'));
    }

    public function authSettingsUpdate(Request $request)
    {
        if ($request->database_permission == 'on') {
            try {
                DB::statement('create database test_db');
            } catch (\Exception $e) {
                return redirect()->back()->with('errors', __('Please give permission to create database to user'));
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
            'landing_page_status' => ($request->landing_page_status == 'on') ? '1' : '0',
        ];
        foreach ($data as $key => $value) {
            UtilityFacades::storesettings(['key' => $key, 'value' => $value]);
        }
        return redirect()->back()->with('success', __('General settings updated successfully'));
    }

    function loadsetting($type)
    {
        $t =  ucfirst(str_replace('-', ' ', $type));
        $tenant_id = tenant('id');
        return view('settings.' . $type, compact('t', 'tenant_id'));
    }

    public function testMail()
    {
        return view('superadmin.settings.test-mail');
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
                // dd($e->getMessage());
                return redirect()->back()->with('errors', $e->getMessage());
            }
        }
        return redirect()->back()->with('success', __('Email send successfully.'));
    }

    // public function landingPage(Request $request)
    // {
    //     return view('superadmin.settings.landing');
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
        return view('superadmin.settings.froentend');
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
            $images1_name = 'dashboards.' . $request->images1->extension();
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

    public function pricesettingstore(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'price_title' => 'required',
            'price_paragraph' => 'required',
        ]);
        if ($validator->fails()) {
            $messages = $validator->errors();
            return redirect()->back()->with('errors', $messages->first());
        }
        $data = [
            'price_title' => $request->price_title,
            'price_paragraph' => $request->price_paragraph,
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
}
