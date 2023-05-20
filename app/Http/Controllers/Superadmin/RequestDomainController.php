<?php

namespace App\Http\Controllers\Superadmin;

use App\DataTables\Superadmin\ChangeDomainRequestDataTable;
use App\Http\Controllers\Controller;
use App\DataTables\Superadmin\RequestDomainDataTable;
use App\Facades\UtilityFacades;
use App\Facades\Utility;
use App\Models\Order;
use App\Models\Plan;
use App\Models\RequestDomain;
use App\Models\Tenant;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Stancl\Tenancy\Database\Models\Domain;
use Stripe\Stripe;
use App\Mail\ApproveMail;
use App\Mail\Superadmin\ConatctMail;
use App\Mail\Superadmin\DisapprovedMail;
use App\Mail\Superadmin\RegisterMail;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\Faq;
use App\Models\Posts;
use App\Models\Setting;
use App\Models\UserCoupon;
use Illuminate\Support\Facades\Storage;
use League\CommonMark\Node\Query\OrExpr;
use Spatie\MailTemplates\Models\MailTemplate;
use Stripe\Product;
use Inertia\Inertia;
use Auth;



class RequestDomainController extends Controller
{
    public $data_theme;
    public $dark_mode;
    public $AuthUser;
    public $csrf_token;
    public $app_currency;
    public $app_color; //UtilityFacades::getsettings('color')
    public $app_name;
    public $app_icon;
    public $apps_paragraph;
    public $tenant_id;
    public $app_image;
    public $tenant_img;
    public $menu_name;
    public $menu_subtitle;
    public $menu_title;
    public $menu_paragraph;
    public $images1;
    public $images3;
    public $images4;
    public $images5;
    public $images6;
    public $images7;
    public $images8;
    public $tenant_images1;
    public $images2;
    public $tenant_images2;
    public $tenant_images3;
    public $tenant_images4;
    public $tenant_images5;
    public $tenant_images6;
    public $tenant_images7;
    public $tenant_images8;
    public $submenu_name;
    public $submenu_subtitle;
    public $submenu_title;
    public $submenu_paragraph;
    public $feature_name;
    public $feature_title;
    public $feature_paragraph;
    public $post_name;
    public $post_title;
    public $price_title;
    public $price_paragraph;
    public $faq_title;
    public $faq_paragraph;
    public $sidefeature_name;
    public $sidefeature_title;
    public $sidefeature_subtitle;
    public $sidefeature_paragraph;
    public $app_values = [];
    public $plan_links = [];
    public $blogs_link;

    public function updateStateValues(){
        $this->csrf_token = csrf_token();
        $this->AuthUser = Auth::user();
        $this->dark_mode = UtilityFacades::getsettings('dark_mode');
        $this->app_currency = UtilityFacades::getsettings('currency_symbol');
        $this->data_theme = UtilityFacades::getsettings('data_theme');
        $this->app_name = UtilityFacades::getsettings('app_name');
        $this->app_icon = Storage::exists('logo/app-favicon-logo.png') ? UtilityFacades::getpath('logo/app-favicon-logo.png') : Storage::url('logo/app-favicon-logo.png');
        $this->apps_paragraph = UtilityFacades::getsettings('apps_paragraph');
        $this->tenant_id = tenant('id');
        $this->app_image = UtilityFacades::getsettings('image')
        ? Storage::url(UtilityFacades::getsettings('image'))
        : asset('assets/img/header_mokeup1.svg');
        $this->tenant_img = UtilityFacades::getsettings('image')
        ? Storage::url(tenant('id') . '/' . UtilityFacades::getsettings('image'))
        : asset('assets/img/header_mokeup1.svg');
        $this->menu_name = UtilityFacades::getsettings('menu_name');
        $this->menu_subtitle = UtilityFacades::getsettings('menu_subtitle');
        $this->menu_title = UtilityFacades::getsettings('menu_title');
        $this->menu_paragraph = UtilityFacades::getsettings('menu_paragraph');
        $this->images1 = UtilityFacades::getsettings('images1')
        ? Storage::url(UtilityFacades::getsettings('images1'))
        : asset('assets/img/dashboards.png');
        $this->tenant_images1 = UtilityFacades::getsettings('images1')
        ? Storage::url(tenant('id') . '/' . UtilityFacades::getsettings('images1'))
        : asset('assets/img/dashboard.png');
        $this->images2 = UtilityFacades::getsettings('images2')
        ? Storage::url(UtilityFacades::getsettings('images2'))
        : asset('assets/img/img_crm_dash_21.svg');
        $this->tenant_images2 = UtilityFacades::getsettings('images2')
        ? Storage::url(tenant('id') . '/' . UtilityFacades::getsettings('images2'))
        : asset('assets/img/img_crm_dash_21.svg');
        $this->submenu_name = UtilityFacades::getsettings('submenu_name');
        $this->submenu_subtitle = UtilityFacades::getsettings('submenu_subtitle');
        $this->submenu_title = UtilityFacades::getsettings('submenu_title')?UtilityFacades::getsettings('submenu_title'):__('CRM system');
        $this->submenu_paragraph = UtilityFacades::getsettings('submenu_paragraph')
        ?UtilityFacades::getsettings('submenu_paragraph')
        :__('Use these awesome forms to login or create new account in your project for free.');
        $this->feature_name = UtilityFacades::getsettings('feature_name')
        ?UtilityFacades::getsettings('feature_name')
        :__('Features');
        $this->feature_title = UtilityFacades::getsettings('feature_title')
        ?UtilityFacades::getsettings('feature_title')
        :__('Automatic Tenancy');
        $this->feature_paragraph = UtilityFacades::getsettings('feature_paragraph')
        ?UtilityFacades::getsettings('feature_paragraph')
        :__('Instead of forcing you to change how you write your code, the package by default');
        $this->post_name = UtilityFacades::getsettings('post_name')
        ?UtilityFacades::getsettings('post_name')
        :__('Posts');
        $this->post_title = UtilityFacades::getsettings('post_title')
        ?UtilityFacades::getsettings('post_title')
        :__('Use these awesome forms to login or create new account in your project for free.');
        $this->price_title = UtilityFacades::getsettings('price_title')
        ?UtilityFacades::getsettings('price_title')
        :__('Price');
        $this->price_paragraph = UtilityFacades::getsettings('price_paragraph')
        ?UtilityFacades::getsettings('price_paragraph')
        :__(' Price components are very important for SaaS projects or other projects.');
        $this->faq_title = UtilityFacades::getsettings('faq_title')
        ?UtilityFacades::getsettings('faq_title')
        :__(' Frequently Asked Questions');
        $this->faq_paragraph = UtilityFacades::getsettings('faq_paragraph')
        ?UtilityFacades::getsettings('faq_paragraph')
        :__(' Use these awesome forms to login or create new account in your');
        $this->sidefeature_name = UtilityFacades::getsettings('sidefeature_name')
        ?UtilityFacades::getsettings('sidefeature_name')
        :__('Dashboard');
        $this->sidefeature_title = UtilityFacades::getsettings('sidefeature_title')
        ?UtilityFacades::getsettings('sidefeature_title')
        :__('All in one place');
        $this->sidefeature_subtitle = UtilityFacades::getsettings('sidefeature_subtitle')
        ?UtilityFacades::getsettings('sidefeature_subtitle')
        :__('CRM system');
        $this->sidefeature_paragraph = UtilityFacades::getsettings('sidefeature_paragraph')
        ?UtilityFacades::getsettings('sidefeature_paragraph')
        :__(' Use these awesome forms to login or create new account in your project for free.');
        $this->images3 = UtilityFacades::getsettings('image3')
        ? Storage::url(UtilityFacades::getsettings('image3'))
        : asset('assets/img/front3.png');
        $this->images4 = UtilityFacades::getsettings('image4')
        ? Storage::url(UtilityFacades::getsettings('image4'))
        : asset('assets/img/front4.png');
        $this->images5 = UtilityFacades::getsettings('image5')
        ? Storage::url(UtilityFacades::getsettings('image5'))
        : asset('assets/img/front5.png');
        $this->images6 = UtilityFacades::getsettings('image6')
        ? Storage::url(UtilityFacades::getsettings('image6'))
        : asset('assets/img/front6.png');
        $this->images7 = UtilityFacades::getsettings('image7')
        ? Storage::url(UtilityFacades::getsettings('image7'))
        : asset('assets/img/front7.png');
        $this->images8 = UtilityFacades::getsettings('image8')
        ? Storage::url(UtilityFacades::getsettings('image8'))
        : asset('assets/img/front8.png');
        $this->app_color = UtilityFacades::getsettings('color');
        $this->blogs_link = route('view.post');
        $app_values = [
            "csrf_token"=>$this->csrf_token,
            "AuthUser"=>$this->AuthUser,
            "dark_mode"=>$this->dark_mode,
            "data_theme"=> $this->data_theme,
            "app_currency"=> $this->app_currency,
            "app_color"=> $this->app_color,
            "app_name"=> $this->app_name,
            "app_icon"=> $this->app_icon,
            "apps_paragraph"=> $this->apps_paragraph,
            "tenant_id"=> $this->tenant_id,
            "app_image"=> $this->app_image,
            "tenant_img"=> $this->tenant_img,
            "menu_name"=> $this->menu_name,
            "menu_subtitle"=> $this->menu_subtitle,
            "menu_title"=> $this->menu_title,
            "menu_paragraph"=> $this->menu_paragraph,
            "images1"=> $this->images1,
            "images3"=> $this->images3,
            "images4"=> $this->images4,
            "images5"=> $this->images5,
            "images6"=> $this->images6,
            "images7"=> $this->images7,
            "images8"=> $this->images8,
            "tenant_images1"=> $this->tenant_images1,
            "images2"=> $this->images2,
            "tenant_images2"=> $this->tenant_images2,
            "tenant_images3"=> $this->tenant_images3,
            "tenant_images4"=> $this->tenant_images4,
            "tenant_images5"=> $this->tenant_images5,
            "tenant_images6"=> $this->tenant_images6,
            "tenant_images7"=> $this->tenant_images7,
            "tenant_images8"=> $this->tenant_images8,
            "submenu_name"=> $this->submenu_name,
            "submenu_subtitle"=> $this->submenu_subtitle,
            "submenu_title"=> $this->submenu_title,
            "submenu_paragraph"=> $this->submenu_paragraph,
            "feature_name"=> $this->feature_name,
            "feature_title"=> $this->feature_title,
            "feature_paragraph"=> $this->feature_paragraph,
            "post_name"=> $this->post_name,
            "post_title"=> $this->post_title,
            "price_title"=> $this->price_title,
            "price_paragraph"=> $this->price_paragraph,
            "faq_title"=> $this->faq_title,
            "faq_paragraph"=> $this->faq_paragraph,
            "sidefeature_name"=> $this->sidefeature_name,
            "sidefeature_title"=> $this->sidefeature_title,
            "sidefeature_subtitle"=> $this->sidefeature_subtitle,
            "sidefeature_paragraph"=> $this->sidefeature_paragraph,
            "blogs_link"=> $this->blogs_link,
        ];
        $this->app_values = $app_values;

    }

    public function landingPage()
    {
        $central_domain = config('tenancy.central_domains')[0];
        $current_domain = tenant('domains');
        if (!empty($current_domain)) {
            $current_domain = $current_domain->pluck('domain')->toArray()[0];
        }
        if ($current_domain == null) {
            if (!file_exists(storage_path() . "/installed")) {
                header('location:install');
                die;
            }
            $faqs = Faq::all();
            $features = json_decode(UtilityFacades::getsettings('feature_setting'));
            $plans = Plan::where('active_status', 1)->get();
            if (UtilityFacades::getsettings('landing_page_status') == '1') {
                $utility = UtilityFacades::getsettings('currency_symbol');
                $this->updateStateValues();
               foreach($plans as $plan){
                $updated = array_push($this->plan_links, [
                    "link"=>route('requestdomain.create', Crypt::encrypt(['plan_id' => $plan->id]))
                ]);
               }
                // return Inertia::render('HomePage', ['plans'=>$plans, 'features'=>$features ,'faqs'=>$faqs,'Utility'=> $utility,"app_values"=>$this->app_values,"plan_links"=>$this->plan_links]);
                return view('welcome', compact('plans', 'features', 'faqs'));
            // return view('Home');

            } else {
                return redirect()->route('home');
            }
        } else {
            $faqs = Faq::all();
            $features = json_decode(UtilityFacades::getsettings('feature_setting'));
            $categories = Category::all();
            $category = [];
            $category['0'] = __('Select category');
            foreach ($categories as $cate) {
                $category[$cate->id] = $cate->name;
            }
            $posts =  Posts::latest()->take(4)->get();
            return view('Home');
            // return view('welcome', compact('posts', 'category', 'features', 'faqs'));
        }
    }

    public function get_category_post(Request $request)
    {
        $posts = Posts::where('category_id', $request->category)->get();
        $post = [];
        foreach ($posts as $key => $value) {
            if ($value->photo) {
                if (Storage::exists($value->photo)) {
                    $photo =  Storage::url(tenant('id') . '/' . $value->photo);
                } else {
                    $photo =  Storage::url('test_image/350x250.png');
                }
            } else {
                $photo =  Storage::url('test_image/350x250.png');
            }
            $post[] = ['title' => $value->title, 'short_description' => $value->short_description, 'description' => $value->description, 'slug' => $value->slug, 'photo' => $photo];
        }
        return response()->json($post, 200);
    }

    public function post_details($slug, Request $request)
    {
        $post = Posts::where('slug', $slug)->first();
        $random_posts = Posts::where('slug', '!=', $slug)->limit(3)->get();
        return view('posts.details', compact('post', 'random_posts'));
    }

    public function index(RequestDomainDataTable $dataTable)
    {
        if (\Auth::user()->can('manage-domain-request')) {
            return $dataTable->render('superadmin.requestdomain.index');
        } else {
            return redirect()->back()->with('failed', __('Permission denied.'));
        }
    }

    public function create($data, $lang = '')
    {
        try {
            if ($lang == '') {
                $lang = UtilityFacades::getValByName('default_language');
            }
            \App::setLocale($lang);
            $datas = Crypt::decrypt($data);
            $plan_id = $datas['plan_id'];
        } catch (DecryptException $e) {
            return redirect()->back()->with('failed', $e->getMessage());
        }
        return view('superadmin.requestdomain.create', compact('plan_id','data', 'lang'));
    }

    public function store(Request $request)
    {
        $validator = \Validator::make(
            $request->all(),
            [
                'name' => 'required',
                'email' => 'required|email|unique:users,email,',
                'domains' => 'required|unique:domains,domain',
                // 'actual_domain' => 'required|unique:domains,actual_domain',
                'password' => 'same:password_confirmation',
                'agree' => 'required'
            ]
        );
        if ($validator->fails()) {
            $messages = $validator->getMessageBag();
            return redirect()->back()->with('failed', $messages->first());
        }
        $domain = new RequestDomain();
        $domain->name = $request->name;
        $domain->email = $request->email;
        $domain->password = Hash::make($request->password);
        $domain->domain_name = $request->domains;
        $domain->actual_domain_name = $request->domains;
        $domain->plan_id = $request->plan_id;
        $domain->type = 'Admin';
        $domain->save();
        $central_domain = config('tenancy.central_domains')[0];
        $central_domainip = gethostbyname($central_domain);
        if (UtilityFacades::getsettings('mail_host') == true) {
            if (MailTemplate::where('mailable', RegisterMail::class)->first()) {
                try {
                    Mail::to($request->email)->send(new RegisterMail($request, $central_domainip));
                } catch (\Exception $e) {
                    return redirect()->back()->with('errors', $e->getMessage());
                }
            }
        }
        $order = tenancy()->central(function ($tenant) use ($request, $domain) {
            $data['plan_details'] = Plan::find($request->plan_id);
            $payment_type = '';
            $payment_status = 0;
            $data = Order::create([
                'plan_id' => $request->plan_id,
                'domainrequest_id' => $domain->id,
                'amount' => $data['plan_details']->price,
                'payment_type' => $payment_type,
                'status' => $payment_status,
            ]);
            return $data;
        });

        $database = $request->all();
        if ($request->plan_id != 1) {
            return redirect()->route('requestdomain.payment', $order->id);
        } else {
            if (UtilityFacades::getsettings('database_permission') == 1) {
                UtilityFacades::approved_request($domain->id, $database);
            }
            return redirect()->route('landingpage')->with('status', __('Thanks for registration, Your account is in review and you get email when your account active.'));
        }
    }

    public function offlinePaymentEntry(Request $request)
    {
        $planID    = \Illuminate\Support\Facades\Crypt::decrypt($request->plan_id);
        $order = Order::find($request->order_id);
        $requestdomain = RequestDomain::find($order->domainrequest_id);
        $plan   = Plan::find($planID);
        $coupon_id = 0;
        $coupon_code = null;
        $discount_value = null;
        $price  = $plan->price;
        $coupons = Coupon::where('code', $request->coupon)->where('is_active', '1')->first();
        if ($coupons) {
            $coupon_code = $coupons->code;
            $usedCoupun     = $coupons->used_coupon();
            if ($coupons->limit == $usedCoupun) {
                $res_data['error'] = __('This coupon code has expired.');
            } else {
                $discount = $coupons->discount;
                $discount_type = $coupons->discount_type;
                $discount_value =  UtilityFacades::calculateDiscount($price, $discount, $discount_type);
                $price          = $price - $discount_value;
                if ($price < 0) {
                    $price = $plan->price;
                }
                $coupon_id = $coupons->id;
            }
        }
        $order->plan_id = $plan->id;
        $order->domainrequest_id = $requestdomain->id;
        $order->amount = $price;
        $order->payment_type = 'offline';
        $order->discount_amount = $discount_value;
        $order->coupon_code = $coupon_code;
        $order->status = 3;
        $order->save();
        return redirect()->route('landingpage')->with('status', __('Thanks for registration, Your account is in review and you get email when your account active.'));
    }

    public function approveStatus($id)
    {
        if (\Auth::user()->can('edit-domain-request')) {
            $requestdomain = RequestDomain::find($id);
            if ($requestdomain->is_approved == 0) {
                return view('superadmin.requestdomain.edit', compact('requestdomain'));
            } else {
                return redirect()->back();
            }
        } else {
            return redirect()->back()->with('failed', __('Permission denied.'));
        }
    }
    public function disapproveStatus($id)
    {
        if (\Auth::user()->can('edit-domain-request')) {
            $requestdomain = RequestDomain::find($id);
            if ($requestdomain->is_approved == 0) {
                $view =   view('superadmin.requestdomain.reason', compact('requestdomain'));
                return ['html' => $view->render()];
            } else {
                return redirect()->back();
            }
        } else {
            return redirect()->back()->with('failed', __('Permission denied.'));
        }
    }

    public function updateStatus(Request $request, $id)
    {
        if (\Auth::user()->can('edit-domain-request')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'reason' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('errors', $messages->first());
            }
            $requestdomain = RequestDomain::find($id);
            $requestdomain->reason = $request->reason;
            $requestdomain->is_approved = 2;
            $requestdomain->update();
            if (MailTemplate::where('mailable', DisapprovedMail::class)->first()) {
                try {
                    Mail::to($requestdomain->email)->send(new DisapprovedMail($requestdomain));
                } catch (\Exception $e) {
                    return redirect()->back()->with('errors', $e->getMessage());
                }
                return redirect()->back()->with('success', __('Domain request disapprove successfully'));
            }
        } else {
            return redirect()->back()->with('failed', __('Permission denied.'));
        }
    }
    public function stripePostpending(Request $request)
    {
        $planID    = \Illuminate\Support\Facades\Crypt::decrypt($request->plan_id);
        $plan   = tenancy()->central(function ($tenant) use ($planID) {
            return Plan::find($planID);
        });
        $res_data =  tenancy()->central(function ($tenant) use ($plan, $request) {
            $order = Order::find($request->order_id);
            $requestdomain = RequestDomain::find($order->domainrequest_id);
            $coupon_id = '0';
            $price = $plan->price;
            $coupon_code = null;
            $discount_value = null;
            $coupons = Coupon::where('code', $request->coupon)->where('is_active', '1')->first();
            if ($coupons) {
                $coupon_code = $coupons->code;
                $usedCoupun     = $coupons->used_coupon();
                if ($coupons->limit == $usedCoupun) {
                    $res_data['error'] = __('This coupon code has expired.');
                } else {
                    $discount = $coupons->discount;
                    $discount_type = $coupons->discount_type;
                    $discount_value =  UtilityFacades::calculateDiscount($price, $discount, $discount_type);
                    $price          = $price - $discount_value;
                    if ($price < 0) {
                        $price = $plan->price;
                    }
                    $coupon_id = $coupons->id;
                }
            }
            $order->plan_id = $plan->id;
            $order->domainrequest_id = $requestdomain->id;
            $order->amount = $price;
            $order->discount_amount = $discount_value;
            $order->coupon_code = $coupon_code;
            $order->status = 0;
            $order->save();
            $res_data['total_price'] = $price;
            $res_data['domainrequest_id'] = $requestdomain->id;
            $res_data['plan_id'] = $plan->id;
            $res_data['coupon']      = $coupon_id;
            $res_data['order_id'] = $order->id;
            return $res_data;
        });
        return $res_data;
    }
    public function prestripeSession(Request $request)
    {
        Stripe::setApiKey(UtilityFacades::getsettings('stripe_secret'));
        $currency = UtilityFacades::getsettings('currency');
        if (!empty($request->createCheckoutSession)) {
            $plan_details = tenancy()->central(function ($tenant) use ($request) {
                return Plan::find($request->plan_id);
            });
            // Create new Checkout Session for the order
            try {
                $checkout_session = \Stripe\Checkout\Session::create([
                    'line_items' => [[
                        'price_data' => [
                            'product_data' => [
                                'name' => $plan_details->name,
                                'metadata' => [
                                    'plan_id' => $request->plan_id,
                                    'domainrequest_id' => $request->domainrequest_id
                                ]
                            ],
                            'unit_amount' => $request->amount * 100,
                            'currency' => $currency,
                        ],
                        'quantity' => 1,
                        'description' => $plan_details->name,
                    ]],
                    'mode' => 'payment',
                    'success_url' => route('pre.stripe.success.pay', Crypt::encrypt(['coupon' => $request->coupon, 'plan_id' => $plan_details->id, 'price' => $request->amount, 'domainrequest_id' => $request->domainrequest_id, 'order_id' => $request->order_id, 'type' => 'stripe'])),
                    'cancel_url' => route('pre.stripe.cancel.pay', Crypt::encrypt(['coupon' => $request->coupon, 'plan_id' => $plan_details->id, 'price' => $request->amount, 'domainrequest_id' => $request->domainrequest_id, 'order_id' => $request->order_id, 'type' => 'stripe'])),

                ]);
            } catch (Exception $e) {
                $api_error = $e->getMessage();
            }

            if (empty($api_error) && $checkout_session) {
                $response = array(
                    'status' => 1,
                    'message' => 'Checkout session created successfully.',
                    'sessionId' => $checkout_session->id
                );
            } else {
                $response = array(
                    'status' => 0,
                    'error' => array(
                        'message' => 'Checkout session creation failed. ' . $api_error
                    )
                );
            }
        }
        echo json_encode($response);
        die;
    }

    function prepaymentCancel($data)
    {
        $data = Crypt::decrypt($data);
        $order = tenancy()->central(function ($tenant) use ($data) {
            $datas = Order::find($data['order_id']);
            $datas->status = 2;
            $datas->payment_type = 'stripe';

            $datas->update();
        });
        return redirect()->route('landingpage')->with('errors', 'Payment canceled.');
    }

    function prepaymentSuccess($data)
    {
        $data = Crypt::decrypt($data);
        $database = $data;
        $order = tenancy()->central(function ($tenant) use ($data) {
            $datas = Order::find($data['order_id']);
            $datas->status = 1;
            $datas->payment_type = 'stripe';
            $datas->update();
            $coupons = Coupon::find($data['coupon']);
            if (!empty($coupons)) {
                $userCoupon         = new UserCoupon();
                $userCoupon->domainrequest   = $data['domainrequest_id'];
                $userCoupon->coupon = $coupons->id;
                $userCoupon->order  = $datas->id;
                $userCoupon->save();
                $usedCoupun = $coupons->used_coupon();
                if ($coupons->limit <= $usedCoupun) {
                    $coupons->is_active = 0;
                    $coupons->save();
                }
            }
        });
        if (UtilityFacades::getsettings('database_permission') == 1) {
            UtilityFacades::approved_request($data['domainrequest_id'], $database);
        }
        return redirect()->route('landingpage')->with('status', __('Thanks for registration, Your account is in review and you get email when your account active.'));
    }

    public function edit($id)
    {
        if (\Auth::user()->can('edit-domain-request')) {
            $requestdomain = RequestDomain::find($id);
            return view('superadmin.requestdomain.data_edit', compact('requestdomain'));
        } else {
            return redirect()->back()->with('failed', __('Permission denied.'));
        }
    }

    public function requestdomainupdate(Request $request, $id)
    {
        if (\Auth::user()->can('edit-domain-request')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'name' => 'required',
                    'email' => 'required|email|unique:users,email,',
                    'domains' => 'required|unique:domains,domain',
                    'actual_domain' => 'required|unique:domains,actual_domain',
                    'password' => 'same:password_confirmation',

                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('errors', $messages->first());
            }
            $requestdomain = RequestDomain::find($id);
            $requestdomain['name'] = $request->name;
            $requestdomain['email'] = $request->email;
            $requestdomain['domain_name'] = $request->domains;
            $requestdomain['actual_domain_name'] = $request->actual_domain;
            if (!empty($request->password)) {
                $requestdomain->password = Hash::make($request->password);
            }
            $requestdomain->update();
            return redirect()->route('requestdomain.index')->with('success', __('Domain request updated successfully.'));
        } else {
            return redirect()->back()->with('failed', __('Permission denied.'));
        }
    }

    public function destroy($id)
    {
        if (\Auth::user()->can('delete-domain-request')) {
            $requestdomain = RequestDomain::find($id);
            $requestdomain->delete();
            return redirect()->route('requestdomain.index')->with('success', __('Domain Request deleted successfully.'));
        } else {
            return redirect()->back()->with('failed', __('Permission denied.'));
        }
    }

    public function update(Request $request)
    {
        if (\Auth::user()->can('edit-domain-request')) {
            $data = RequestDomain::where('email', $request->email)->first();
            $validator = \Validator::make(
                $request->all(),
                [
                    'name' => 'required',
                    'email' => 'required|email|unique:users,email,',
                    'domains' => 'required|unique:domains,domain',
                    'actual_domain' => 'required|unique:domains,actual_domain',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('errors', $messages->first());
            }
            $database = $request->all();
            UtilityFacades::approved_request($data->id, $database);

            return redirect()->route('requestdomain.index')->with('success', __('User created successfully.'));
        } else {
            return redirect()->back()->with('failed', __('Permission denied.'));
        }
    }

    public function payment(Request $request, $id)
    {
        $order = Order::find($id);
        $requestdomain = RequestDomain::find($order->domainrequest_id);
        $plan = Plan::find($requestdomain->plan_id);
        $paymenttypes = UtilityFacades::getpaymenttypes();
        $admin_payment_setting = UtilityFacades::getadminplansetting();
        return view('superadmin.requestdomain.front-payment', compact('requestdomain', 'admin_payment_setting', 'paymenttypes', 'order', 'plan'));
    }

    public function contactus()
    {
        return view('contactus');
    }

    public function termsandconditions()
    {
        return view('termsandconditions');
    }

    public function privacypolicy()
    {
        return view('privacypolicy');
    }

    public function faq()
    {
        return view('faq');
    }

    public function contact_mail(Request $request)
    {
        if (MailTemplate::where('mailable', ConatctMail::class)->first()) {
            try {
                if ($request) {
                    $details = $request->all();
                    Mail::to(UtilityFacades::getsettings('contact_us_email'))->send(new ConatctMail($request->all()));
                } else {
                    return redirect()->back()->with('failed', __('Please check recaptch.'));
                }
            } catch (\Exception $e) {
                return redirect()->back()->with('errors', $e->getMessage());
            }
            return redirect()->back()->with('success', __('Email sent successfully.'));
        }
    }
}
