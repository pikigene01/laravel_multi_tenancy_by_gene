<?php

declare(strict_types=1);

use App\Facades\UtilityFacades;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ConversionsController;
use App\Http\Controllers\Admin\EmailTemplateController;
use App\Http\Controllers\Admin\LoginSecurityController;
use App\Http\Controllers\Admin\PostsController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\OfflineRequestController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\PayPalController;
use App\Http\Controllers\Admin\PlanController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\SocialLoginController;
use App\Http\Controllers\Admin\LandingController;
use App\Http\Controllers\Admin\LanguageController;
use App\Http\Controllers\Admin\SupportTicketController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomainOrSubdomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;
use Stancl\Tenancy\Features\UserImpersonation;
use App\Http\Controllers\Admin\FlutterwaveController;
use App\Http\Controllers\Admin\PaystackController;
use App\Http\Controllers\Admin\PaytmController;
use App\Http\Controllers\Admin\CoingateController;
use App\Http\Controllers\Admin\MercadoController;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\RiskCurbApp;


/*
|--------------------------------------------------------------------------
| Tenant Routes
|--------------------------------------------------------------------------
|
| Here you can register the tenant routes for your application.
| These routes are loaded by the TenantRouteServiceProvider.
|
| Feel free to customize them however you want. Good luck!
|
*/

Route::middleware([
    'web',
    InitializeTenancyByDomainOrSubdomain::class,
    PreventAccessFromCentralDomains::class,
])->group(function () {
    Route::get('/riskcurb', [RiskCurbApp::class, 'index'])->name('riskcurb')->middleware(['auth', 'xss']);
    Route::get('/riskcurbKeys', [RiskCurbApp::class, 'apiKeys'])->name('riskcurbKeys')->middleware(['auth', 'xss']);
    Route::post('/riskcurbKeys', [RiskCurbApp::class, 'apiKeysSave'])->name('riskcurbKeys')->middleware(['auth', 'xss']);
    Route::post('/risks/generate', [RiskCurbApp::class, 'create'])->name('risks.generate')->middleware(['auth', 'xss']);

    Route::get('/test-mail', [SettingsController::class, 'testMail'])->name('test.mail')->middleware(['auth', 'xss']);
    // Route::get('landing-page', [SettingsController::class, 'landingPage'])->name('landing.page');
    // Route::post('landing-page/store', [SettingsController::class, 'landingPagestore'])->name('landing.page.store')->middleware(['auth', 'Setting']);
    Route::resource('blogs', PostsController::class)->middleware(['auth', 'Setting', '2fa']);

    //froentend
    Route::get('froentend-setting', [SettingsController::class, 'froentendsetting'])->name('froentend.page')->middleware(['auth', 'xss']);
    Route::post('froentend-setting/store', [SettingsController::class, 'froentendsettingstore'])->name('froentend.page.store')->middleware(['auth', 'xss']);
    Route::post('menu-setting/store', [SettingsController::class, 'menusettingstore'])->name('menu.page.store')->middleware(['auth', 'xss']);
    Route::post('faq-setting/store', [SettingsController::class, 'faqsettingstore'])->name('faq.page.store')->middleware(['auth', 'xss']);
    Route::post('feature-setting/store', [SettingsController::class, 'featuresettingstore'])->name('feature.page.store')->middleware(['auth', 'xss']);
    Route::post('sidefeature-setting/store', [SettingsController::class, 'sidefeaturesettingstore'])->name('sidefeature.page.store')->middleware(['auth', 'xss']);
    Route::post('post-setting/store', [SettingsController::class, 'postsettingstore'])->name('post.page.store')->middleware(['auth', 'Setting']);
    Route::resource('faqs', FaqController::class)->middleware(['auth', 'Setting', 'xss', '2fa', 'verified']);

    Route::post('privacy-setting/store', [SettingsController::class, 'privacysettingstore'])->name('privacy.page.store');
    Route::post('contactus-setting/store', [SettingsController::class, 'contactussettingstore'])->name('contactus.page.store');
    Route::post('termcondition-setting/store', [SettingsController::class, 'termconditionsettingstore'])->name('termcondition.page.store');


    Route::get('/tenant-impersonate/{token}', function ($token) {
        return UserImpersonation::makeResponse($token);
    });

    Route::group(['middleware' => ['Setting', 'xss']], function () {
        Auth::routes(['verify' => true]);
        Route::get('/register/{lang?}', [RegisterController::class, 'index'])->name('register');
        Route::get('/login/{lang?}', [LoginController::class, 'showLoginForm'])->name('login');
        Route::get('/redirect/{provider}', [SocialLoginController::class, 'redirect']);
        Route::get('/callback/{provider}', [SocialLoginController::class, 'callback'])->name('social.callback');
        Route::get('/', [LandingController::class, 'landingPage'])->name('landingpage');
        Route::get('contact-us', [LandingController::class, 'contactus'])->name('contactus');
        Route::get('terms-conditions', [LandingController::class, 'termsandconditions'])->name('termsandconditions');
        Route::get('privacy-policy', [LandingController::class, 'privacypolicy'])->name('privacypolicy');
        Route::get('faq', [LandingController::class, 'faq'])->name('faq');
        Route::post('contact_mail', [LandingController::class, 'contact_mail'])->name('contact.mail');
        Route::post('get-category-blog', [LandingController::class, 'get_category_post'])->name('get.category.post');
        Route::get('blog-detail/{slug}', [LandingController::class, 'post_details'])->name('post.details');
        Route::get('view-blog', [PostsController::class, 'all_post'])->name('view.post');
        Route::get('/register', [RegisterController::class, 'index'])->name('register');
    });

    Route::group(['middleware' => ['auth', 'Setting', 'xss', '2fa', 'verified']], function () {
        Route::impersonate();

        Route::resource('category', CategoryController::class);
        Route::get('category-status/{id}', [CategoryController::class, 'categorystatus'])->name('category.status');
        Route::resource('plans', PlanController::class);
        Route::resource('users', UserController::class);
        Route::resource('roles', RoleController::class);
        Route::post('/role-permission/{id}', [RoleController::class, 'assignPermission'])->name('roles_permit');
        Route::resource('support-ticket', SupportTicketController::class);
        Route::resource('profile', ProfileController::class);
        Route::resource('Offline', OfflineRequestController::class);
        Route::get('/home', [HomeController::class, 'index'])->name('home');
        Route::post('/chart', [HomeController::class, 'chart'])->name('get.chart.data');
        Route::get('sales', [HomeController::class, 'sales'])->name('sales.index');
        Route::post('update/{id}', [ProfileController::class, 'update'])->name('profile.update');
        Route::post('update-avatar/{id}', [ProfileController::class, 'updateAvatar']);
        Route::get('/apply-coupon', [CouponController::class, 'applyCoupon'])->name('apply.coupon');
        Route::resource('coupon', CouponController::class);
        Route::get('/coupon-status/{id}', [CouponController::class, 'couponStatus'])->name('coupon.status');
        Route::get('coupon/show', [CouponController::class, 'show'])->name('coupons.show');
        Route::get('coupon/csv/upload', [CouponController::class, 'upload_csv'])->name('coupon.upload');
        Route::post('coupon/csv/upload/store', [CouponController::class, 'upload_csv_store'])->name('coupon.upload.store');
        Route::get('coupon/mass/create', [CouponController::class, 'masscreate'])->name('coupon.mass.create');
        Route::post('coupon/mass/store', [CouponController::class, 'masscreate_store'])->name('coupon.mass.store');
        Route::resource('email-template', EmailTemplateController::class);

        Route::group(['prefix' => '2fa'], function () {
            Route::get('/', [LoginSecurityController::class, 'show2faForm']);
            Route::post('/generateSecret', [LoginSecurityController::class, 'generate2faSecret'])->name('generate2faSecret');
            Route::post('/enable2fa', [LoginSecurityController::class, 'enable2fa'])->name('enable2fa');
            Route::post('/disable2fa', [LoginSecurityController::class, 'disable2fa'])->name('disable2fa');
            Route::post('/2faVerify', function () {
                return redirect(URL()->previous());
            })->name('2faVerify')->middleware('2fa');
        });

        Route::get('stripe', [PaymentController::class, 'stripe'])->name('stripe.pay');
        Route::post('stripe/pending', [PaymentController::class, 'stripePostpending'])->name('stripe.pending');
        Route::post('stripe/session', [PaymentController::class, 'stripeSession'])->name('stripe.session');
        Route::get('/payment-success/{id}', [PaymentController::class, 'paymentSuccess'])->name('stripe.success.pay');
        Route::get('/payment-cancel/{id}', [PaymentController::class, 'paymentCancel'])->name('stripe.cancel.pay');


        Route::get('create-transaction', [PaymentController::class, 'createTransaction'])->name('paycreateTransaction');
        Route::post('process-transaction', [PaymentController::class, 'processTransaction'])->name('payprocessTransaction');
        Route::get('success-transaction/{data}', [PaymentController::class, 'successTransaction'])->name('paysuccessTransaction');
        Route::get('cancel-transaction/{data}', [PaymentController::class, 'cancelTransaction'])->name('paycancelTransaction');
        Route::get('myplans', [PlanController::class, 'myPlan'])->name('plans.myplan');
        Route::get('myplans-create', [PlanController::class, 'createmyPlan'])->name('plans.createmyplan');
        Route::get('myplans/{id}/edit', [PlanController::class, 'editmyplan'])->name('requestdomain.editplan');
        Route::get('users/{id}/impersonate', [UserController::class, 'impersonate'])->name('users.impersonate');
        Route::get('user-emailverified/{id}', [UserController::class, 'useremailverified'])->name('user.verified');
        Route::get('user-status/{id}', [UserController::class, 'userstatus'])->name('user.status');
        Route::post('support-ticket/{id}/conversion', [ConversionsController::class, 'store'])->name('conversion.store');
        Route::get('myplan-status/{id}', [PlanController::class, 'planStatus'])->name('plan.status');
        Route::get('/payment/{code}', [PlanController::class, 'payment'])->name('payment');
        Route::post('process-transactionadmin', [PaymentController::class, 'processTransactionadmin'])->name('payprocessTransactionadmin');
        //razorpay
        Route::post('razorpay/payment', [PaymentController::class, 'razorPaypayment'])->name('payrazorpay.payment');
        Route::get('/razorpay/transaction/callback/{transaction_id}/{coupoun_id}/{plan_id}', [PaymentController::class, 'RazorpayCallback']);
        //flutterwave
        Route::post('/flutterwave/payment', [FlutterwaveController::class, 'flutterwavepayment'])->name('payflutterwave.payment');
        Route::get('/flutterwave/transaction/callback/{transaction_id}/{coupoun_id}/{plan_id}', [FlutterwaveController::class, 'Flutterwavecallback']);
        //paystack
        Route::post('/paystack/payment', [PaystackController::class, 'paystackpayment'])->name('paypaystack.payment');
        Route::get('/paystack/transaction/callback/{transaction_id}/{coupoun_id}/{plan_id}', [PaystackController::class, 'paystackcallback']);

        //coingate
        Route::post('coingate/prepare', [CoingateController::class, 'coingatePrepare'])->name('coingate.payment.prepare');
        Route::get('coingate-success/{id}', [CoingateController::class, 'coingateCallback'])->name('coingate.payment.callback');

        Route::post('mercado/prepare', [MercadoController::class, 'mercadoPrepare'])->name('mercado.payment.prepare');
        Route::any('mercado-payment-callback/{id}', [MercadoController::class, 'mercadoCallback'])->name('mercado.payment.callback');

        Route::get('profile-status', [ProfileController::class, 'profileStatus'])->name('profile.status');
        Route::delete('/profile-destroy/{id}/delete', [ProfileController::class, 'destroy'])->name('profile.delete');
        Route::get('update-avatar/{id}', [ProfileController::class, 'showAvatar'])->name('update_avatar');
        Route::get('design/{id}', [ProfileController::class, 'design'])->name('forms.design');
        Route::post('update-profile-login/{id}', [ProfileController::class, 'updateLogin'])->name('update_login');
        Route::post('/verify-2fa', [ProfileController::class, 'verify'])->name('verify_2fa');
        Route::post('/activate-2fa', [ProfileController::class, 'activate'])->name('activate_2fa');
        Route::post('/enable-2fa', [ProfileController::class, 'enable'])->name('enable_2fa');
        Route::post('/disable-2fa', [ProfileController::class, 'disable'])->name('disable_2fa');
        Route::get('/2fa/instruction', [ProfileController::class, 'instruction']);
        Route::get('change-language/{lang}', [LanguageController::class, 'changeLanquage'])->name('change.language');

        Route::get('/offline-request/{id}', [OfflineRequestController::class, 'offlinerequeststatus'])->name('offlinerequest.status');
        Route::get('/offline-request/disapprove/{id}', [OfflineRequestController::class, 'disapprovestatus'])->name('offline.disapprove.status');
        Route::post('/offline-request/disapprove-update/{id}', [OfflineRequestController::class, 'offlinedisapprove'])->name('requestuser.disapprove.update');
        Route::post('offline-payment', [OfflineRequestController::class, 'offlinePaymentEntry'])->name('offline.payment.request');

        Route::post('process-transactions', [PayPalController::class, 'processTransaction'])->name('processTransaction');
        Route::get('success-transactions/{data}', [PayPalController::class, 'successTransaction'])->name('successTransaction');
        Route::get('cancel-transactions/{data}', [PayPalController::class, 'cancelTransaction'])->name('cancelTransaction');

        Route::get('/settings', [SettingsController::class, 'index'])->name('settings');
        Route::post('settings/app-name/update', [SettingsController::class, 'appNameUpdate'])->name('settings/app_name/update');
        Route::post('settings/app-logo/update', [SettingsController::class, 'appNameUpdate'])->name('settings/app_name/update');
        Route::post('settings/pusher-setting/update', [SettingsController::class, 'pusherSettingUpdate'])->name('settings/pusher_setting/update');
        Route::post('settings/s3-setting/update', [SettingsController::class, 's3SettingUpdate'])->name('settings/s3_setting/update');
        Route::post('settings/email-setting/update', [SettingsController::class, 'emailSettingUpdate'])->name('settings/email_setting/update');
        Route::post('settings/payment-setting/update', [SettingsController::class, 'paymentSettingUpdate'])->name('settings/payment_setting/update');
        Route::post('settings/social-setting/update', [SettingsController::class, 'socialSettingUpdate'])->name('settings/social_setting/update');

        Route::post('settings/auth-settings/update', [SettingsController::class, 'authSettingsUpdate'])->name('settings/auth_settings/update');
        Route::post('test-mail', [SettingsController::class, 'testSendMail'])->name('test.send.mail');
        Route::post('approve-mail', [LandingController::class, 'approveSendMail'])->name('approve.send.mail');
        Route::post('ckeditor/upload', [SettingsController::class, 'upload'])->name('ckeditor.upload');
        Route::get('setting/{id}', [SettingsController::class, 'loadsetting'])->name('setting');
        Route::post('settings/change-domain', [SettingsController::class, 'changeDomainRequest'])->name('settings/change_domain');
    });
    Route::post('process-transactionadmin', [PaymentController::class, 'processTransactionadmin'])->name('payprocessTransactionadmin');
    Route::post('process-transactions', [PayPalController::class, 'processTransaction'])->name('processTransaction');
    Route::get('success-transactions/{data}', [PayPalController::class, 'successTransaction'])->name('successTransaction');
    Route::get('cancel-transactions/{data}', [PayPalController::class, 'cancelTransaction'])->name('cancelTransaction');
    //paytm
    Route::post('/paypayment', [PaytmController::class, 'pay'])->name('paypaytm.payment');
    Route::post('/paypayment/callback', [PaytmController::class, 'paymentCallback'])->name('paypaytm.callback');
});
