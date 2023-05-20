<?php

namespace App\Facades;

use App\Mail\Superadmin\ApproveMail;
use App\Models\Order;
use App\Models\Plan;
use App\Models\RequestDomain;
use App\Models\Setting;
use App\Models\Tenant;
use App\Models\User;
use App\Models\UserCoupon;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Spatie\MailTemplates\Models\MailTemplate;
use Stancl\Tenancy\Database\Models\Domain;

class Utility
{
    public function settings()
    {
        $data = DB::table('settings');
        $data = $data->get();
        $settings = [
            'date_format' => 'M j, Y',
            'time_format' => 'g:i A',
        ];
        foreach ($data as $row) {
            $settings[$row->key] = $row->value;
        }
        return $settings;
    }

    public function date_format($date)
    {
        return Carbon::parse($date)->format($this->getsettings('date_format'));
    }

    public function check_null($value = null){
        if($value){
         return $value;
        }else{
         return null;
        }
        return $value;
 }

    public function time_format($date)
    {
        return Carbon::parse($date)->format($this->getsettings('time_format'));
    }

    public function date_time_format($date)
    {
        return Carbon::parse($date)->format($this->getsettings('date_format') . ' ' . $this->getsettings('time_format'));
    }

    public function setEnvironmentValue(array $values)
    {
        $envFile = app()->environmentFilePath();
        $str = file_get_contents($envFile);
        if (count($values) > 0) {
            foreach ($values as $envKey => $envValue) {
                $keyPosition = strpos($str, "{$envKey}=");
                $endOfLinePosition = strpos($str, "\n", $keyPosition);
                $oldLine = substr($str, $keyPosition, $endOfLinePosition - $keyPosition);
                if (!$keyPosition || !$endOfLinePosition || !$oldLine) {
                    $str .= "{$envKey}='{$envValue}'\n";
                } else {
                    $str = str_replace($oldLine, "{$envKey}='{$envValue}'", $str);
                }
            }
        }
        $str = substr($str, 0, -1);
        $str .= "\n";
        if (!file_put_contents($envFile, $str)) {
            return false;
        }
        return true;
    }

    public function getValByName($key)
    {
        $setting = $this->settings();
        if (!isset($setting[$key]) || empty($setting[$key])) {
            $setting[$key] = '';
        }
        return $setting[$key];
    }

    public function languages()
    {
        $dir = base_path() . '/resources/lang/';
        $glob = glob($dir . '*', GLOB_ONLYDIR);
        $arrLang = array_map(
            function ($value) use ($dir) {
                return str_replace($dir, '', $value);
            },
            $glob
        );
        $arrLang = array_map(
            function ($value) use ($dir) {
                return preg_replace('/[0-9]+/', '', $value);
            },
            $arrLang
        );
        $arrLang = array_filter($arrLang);
        return $arrLang;
    }

    public function delete_directory($dir)
    {
        if (!file_exists($dir)) {
            return true;
        }
        if (!is_dir($dir)) {
            return unlink($dir);
        }
        foreach (scandir($dir) as $item) {
            if ($item == '.' || $item == '..') {
                continue;
            }
            if (!self::delete_directory($dir . DIRECTORY_SEPARATOR . $item)) {
                return false;
            }
        }
        return rmdir($dir);
    }
    public function getsettings($value = '')
    {
        $setting = Setting::select('value');
        if (!empty(tenant('id'))) {
            $setting->where('tenant_id', tenant('id'));
        } else {
            $setting->whereNull('tenant_id');
        }
        $set =  $setting->where('key', $value)->first();
        $val = '';
        if (!empty($set->value)) {

            $val = $set->value;
        }
        return $val;
    }

    public function storesettings($formatted_array)
    {
        if (tenant('id') == null) {
            $row = Setting::where('key', $formatted_array['key'])->whereNull('tenant_id')->first();
        } else {
            $row = Setting::where('key', $formatted_array['key'])->where('tenant_id', tenant('id'))->first();
        }
        if (empty($row)) {
            Setting::create($formatted_array);
        } else {
            $row->update($formatted_array);
        }
        $affected_row = Setting::find($formatted_array['key']);
        return $affected_row;
    }

    public function getpath($name)
    {
        if (config('filesystems.default') == 'local'  && tenant('id') == null) {
            $src = $name ? Storage::url(tenant('id') . $name) : Storage::url('logo/app-logo.png');
        } elseif (config('filesystems.default') == 'local') {
            $src = $name ? Storage::url(tenant('id') . '/' . $name) : Storage::url('logo/app-logo.png');
        } else {
            $src = $name ? Storage::url($name) : Storage::url('logo/app-logo.png');
        }
        return $src;
    }

    public function approved_request($data, $database)
    {
        $req = RequestDomain::find($data);
        $data = Order::where('domainrequest_id', $req->id)->first();
        $input['name'] = $req->name;
        $input['email'] = $req->email;
        $input['password'] = $req->password;
        $input['type'] = 'Admin';
        $input['email_verified_at'] = Carbon::now();
        $input['plan_id'] = 1;
        $user = User::create($input);
        $user->assignRole('Admin');
        if (tenant('id') == null && Utility::getsettings('database_permission') == 1) {
            try {
                $tenant = Tenant::create([
                    'id' => $user->id,
                ]);
                $domain = Domain::create([
                    'domain' => $req->domain_name,
                    'actual_domain' => $req->actual_domain_name,
                    'tenant_id' => $tenant->id,
                ]);
                $user->tenant_id = $tenant->id;
                $user->save();
            } catch (\Exception $e) {
                return redirect()->back()->with('errors', $e->getMessage());
            }
        } else {
            try {
                $tenant = Tenant::create([
                    'id' => $user->id,
                    'tenancy_db_name' => $database['db_name'],
                    'tenancy_db_username' => $database['db_username'],
                    'tenancy_db_password' => $database['db_password'],
                ]);
                $domain = Domain::create([
                    'domain' => $req->domain_name,
                    'actual_domain' => $req->actual_domain_name,
                    'tenant_id' => $tenant->id,
                ]);
                $user->tenant_id = $tenant->id;
                $user->save();
            } catch (\Exception $e) {
                return redirect()->back()->with('errors', $e->getMessage());
            }
        }
        $usercoupon =  UserCoupon::where('domainrequest', $req->id)->first();
        if ($usercoupon) {
            $usercoupon->user = $user->id;
            $usercoupon->domainrequest = null;
            $usercoupon->save();
        }
        $user = User::find($tenant->id);
        $plan = Plan::find($data->plan_id);
        $user->plan_id = $plan->id;
        if ($plan->durationtype == 'Month' && $plan->id != '1') {
            $user->plan_expired_date = Carbon::now()->addMonths($plan->duration)->isoFormat('YYYY-MM-DD');
        } elseif ($plan->durationtype == 'Year' && $plan->id != '1') {
            $user->plan_expired_date = Carbon::now()->addYears($plan->duration)->isoFormat('YYYY-MM-DD');
        } else {
            $user->plan_expired_date = null;
        }
        $user->save();
        $req->is_approved = 1;
        $req->save();
        $data->user_id = $user->id;
        $data->save();
        if (MailTemplate::where('mailable', ApproveMail::class)->first()) {
            try {
                Mail::to($req->email)->send(new ApproveMail($req));
            } catch (\Exception $e) {
                return redirect()->back()->with('errors', $e->getMessage());
            }
        }
    }

    public function getplansetting()
    {
        $data = [];
        $data['stripesetting'] = $this->getsettings('stripesetting');
        $data['paypalsetting'] = $this->getsettings('paypalsetting');
        $data['razorpaysetting'] = $this->getsettings('razorpaysetting');
        $data['offlinesetting'] = $this->getsettings('offlinesetting');
        $data['paystacksetting'] = $this->getsettings('paystacksetting');
        $data['flutterwavesetting'] = $this->getsettings('flutterwavesetting');
        $data['paytmsetting'] = $this->getsettings('paytmsetting');
        $data['coingatesetting'] = $this->getsettings('coingatesetting');
        $data['mercadosetting'] = $this->getsettings('mercadosetting');
        $data['stripe_key'] = $this->getsettings('stripe_key');
        $data['stripe_secret'] = $this->getsettings('stripe_secret');
        $data['flutterwave_key'] = $this->getsettings('flutterwave_key');
        $data['flutterwave_secret'] = $this->getsettings('flutterwave_secret');
        $data['razorpay_key'] = $this->getsettings('razorpay_key');
        $data['razorpay_secret'] = $this->getsettings('razorpay_secret');
        $data['paystack_key'] = $this->getsettings('paystack_public_key');
        $data['paystack_secret'] = $this->getsettings('paystack_secret_key');
        $data['paystack_currency'] = $this->getsettings('paystack_currency');
        $data['paypal_client_id'] = $this->getsettings('paypal_client_id');
        $data['paypal_client_secret'] = $this->getsettings('paypal_client_secret');
        $data['paypal_mode'] = $this->getsettings('paypal_mode');
        $data['paytm_merchant_id'] = $this->getsettings('paytm_merchant_id');
        $data['paytm_merchant_key'] = $this->getsettings('paytm_merchant_key');
        $data['coingate_environment'] = $this->getsettings('coingate_environment');
        $data['coingate_auth_token'] = $this->getsettings('coingate_auth_token');
        $data['mercado_mode'] = $this->getsettings('mercado_mode');
        $data['mercado_access_token'] = $this->getsettings('mercado_access_token');
        $data['stripe_description'] = $this->getsettings('stripe_description');
        $data['paypal_description'] = $this->getsettings('paypal_description');
        $data['razorpay_description'] = $this->getsettings('razorpay_description');
        $data['paystack_description'] = $this->getsettings('paystack_description');
        $data['flutterwave_description'] = $this->getsettings('flutterwave_description');
        $data['paytm_description'] = $this->getsettings('paytm_description');
        $data['coingate_description'] = $this->getsettings('coingate_description');
        $data['mercado_description'] = $this->getsettings('mercado_description');
        $data['payment_details'] = $this->getsettings('payment_details');
        $data['currency'] = $this->getsettings('currency');
        $data['currency_symbol'] = $this->getsettings('currency_symbol');
        return $data;
    }

    public function getadminplansetting()
    {
        $data = [];
        $data['stripesetting'] = tenancy()->central(function ($tenant) {
            return $this->getsettings('stripesetting', 1);
        });
        $data['paypalsetting'] = tenancy()->central(function ($tenant) {
            return $this->getsettings('paypalsetting', 1);
        });
        $data['razorpaysetting'] = tenancy()->central(function ($tenant) {
            return $this->getsettings('razorpaysetting', 1);
        });
        $data['offlinesetting'] = tenancy()->central(function ($tenant) {
            return $this->getsettings('offlinesetting', 1);
        });
        $data['paystacksetting'] = tenancy()->central(function ($tenant) {
            return $this->getsettings('paystacksetting', 1);
        });
        $data['flutterwavesetting'] = tenancy()->central(function ($tenant) {
            return $this->getsettings('flutterwavesetting', 1);
        });
        $data['paytmsetting'] = tenancy()->central(function ($tenant) {
            return $this->getsettings('paytmsetting', 1);
        });
        $data['coingatesetting'] = tenancy()->central(function ($tenant) {
            return $this->getsettings('coingatesetting', 1);
        });
        $data['mercadosetting'] = tenancy()->central(function ($tenant) {
            return $this->getsettings('mercadosetting', 1);
        });
        $data['stripe_key'] = tenancy()->central(function ($tenant) {
            return $this->getsettings('stripe_key');
        });
        $data['stripe_secret'] = tenancy()->central(function ($tenant) {
            return $this->getsettings('stripe_secret');
        });
        $data['flutterwave_key'] = tenancy()->central(function ($tenant) {
            return $this->getsettings('flutterwave_key');
        });
        $data['flutterwave_secret'] = tenancy()->central(function ($tenant) {
            return $this->getsettings('flutterwave_secret');
        });
        $data['razorpay_key'] = tenancy()->central(function ($tenant) {
            return $this->getsettings('razorpay_key');
        });
        $data['razorpay_secret'] = tenancy()->central(function ($tenant) {
            return $this->getsettings('razorpay_secret');
        });
        $data['paystack_key'] = tenancy()->central(function ($tenant) {
            return $this->getsettings('paystack_public_key');
        });
        $data['paystack_secret'] = tenancy()->central(function ($tenant) {
            return $this->getsettings('paystack_secret_key');
        });
        $data['paystack_currency'] = tenancy()->central(function ($tenant) {
            return $this->getsettings('paystack_currency');
        });
        $data['paypal_client_id'] = tenancy()->central(function ($tenant) {
            return $this->getsettings('paypal_client_id');
        });
        $data['paypal_client_secret'] = tenancy()->central(function ($tenant) {
            return $this->getsettings('paypal_client_secret');
        });
        $data['paypal_mode'] = tenancy()->central(function ($tenant) {
            return $this->getsettings('paypal_mode');
        });
        $data['paytm_merchant_id'] = tenancy()->central(function ($tenant) {
            return $this->getsettings('paytm_merchant_id');
        });
        $data['paytm_merchant_key'] = tenancy()->central(function ($tenant) {
            return $this->getsettings('paytm_merchant_key');
        });
        $data['coingate_environment'] = tenancy()->central(function ($tenant) {
            return $this->getsettings('coingate_environment');
        });
        $data['coingate_auth_token'] = tenancy()->central(function ($tenant) {
            return $this->getsettings('coingate_auth_token');
        });
        $data['mercado_mode'] = tenancy()->central(function ($tenant) {
            return $this->getsettings('mercado_mode');
        });
        $data['mercado_access_token'] = tenancy()->central(function ($tenant) {
            return $this->getsettings('mercado_access_token');
        });
        $data['stripe_description'] = tenancy()->central(function ($tenant) {
            return $this->getsettings('stripe_description');
        });
        $data['paypal_description'] = tenancy()->central(function ($tenant) {
            return $this->getsettings('paypal_description');
        });
        $data['razorpay_description'] = tenancy()->central(function ($tenant) {
            return $this->getsettings('razorpay_description');
        });
        $data['paystack_description'] = tenancy()->central(function ($tenant) {
            return $this->getsettings('paystack_description');
        });
        $data['flutterwave_description'] = tenancy()->central(function ($tenant) {
            return $this->getsettings('flutterwave_description');
        });
        $data['paytm_description'] = tenancy()->central(function ($tenant) {
            return $this->getsettings('paytm_description');
        });
        $data['coingate_description'] = tenancy()->central(function ($tenant) {
            return $this->getsettings('coingate_description');
        });
        $data['mercado_description'] = tenancy()->central(function ($tenant) {
            return $this->getsettings('mercado_description');
        });
        $data['payment_details'] = tenancy()->central(function ($tenant) {
            return $this->getsettings('payment_details');
        });
        $data['currency_symbol'] = tenancy()->central(function ($tenant) {
            return UtilityFacades::getsettings('currency_symbol');
        });

        $data['currency'] = tenancy()->central(function ($tenant) {
            return UtilityFacades::getsettings('currency');
        });
        return $data;
    }

    public function getpaymenttypes()
    {
        $payment_type = [];
        if ($this->getsettings('stripesetting') == 'on') {
            $payment_type['stripe'] = 'Stripe';
        }
        if ($this->getsettings('paypalsetting') == 'on') {
            $payment_type['paypal'] = 'Paypal';
        }
        if ($this->getsettings('razorpaysetting') == 'on') {
            $payment_type['razorpay'] = 'Razorpay';
        }
        if ($this->getsettings('paytmsetting') == 'on') {
            $payment_type['paytm'] = 'Paytm';
        }
        if ($this->getsettings('paystacksetting') == 'on') {
            $payment_type['paystack'] = 'Paystack';
        }
        if ($this->getsettings('flutterwavesetting') == 'on') {
            $payment_type['flutterwave'] = 'Flutterwave';
        }
        if ($this->getsettings('coingatesetting') == 'on') {
            $payment_type['coingate'] = 'Coingate';
        }
        if ($this->getsettings('mercadosetting') == 'on') {
            $payment_type['mercado'] = 'Mercado Pago';
        }
        if ($this->getsettings('offlinesetting') == 'on') {
            $payment_type['offline'] = 'Offline';
        }
        return $payment_type;
    }

    public function amount_format($amount)
    {
        return $this->getsettings('currency_symbol') . number_format($amount, 2);
    }

    public function calculateDiscount($price = "", $discount = "", $discount_type = "")
    {
        $discountedAmount = 0;
        if ($discount != "" && $price != "" && $discount_type != "") {
            if ($discount_type == "percentage") {
                $discountedAmount = ($price / 100) * $discount;
            }
            if ($discount_type == "flat") {
                $discountedAmount = $discount;
            }
        }
        return $discountedAmount;
    }
}
