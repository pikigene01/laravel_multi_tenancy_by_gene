<?php

use App\Facades\UtilityFacades;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Admin\PostsController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\Superadmin\HomeController;
use App\Http\Controllers\Superadmin\LanguageController;
use App\Http\Controllers\RiskCurbApp;
use App\Http\Controllers\Superadmin\LoginSecurityController;
use App\Http\Controllers\Superadmin\ModuleController;
use App\Http\Controllers\Superadmin\OfflineRequestController;
use App\Http\Controllers\Superadmin\PaymentController;
use App\Http\Controllers\Superadmin\PayPalController;
use App\Http\Controllers\Superadmin\PlanController;
use App\Http\Controllers\Superadmin\ProfileController;
use App\Http\Controllers\Superadmin\RazorpayPaymentController;
use App\Http\Controllers\RequestDomain;
use App\Http\Controllers\Superadmin\ChangeDomainController;
use App\Http\Controllers\Superadmin\ConversionsController;
use App\Http\Controllers\Superadmin\EmailTemplateController;
use App\Http\Controllers\Superadmin\RequestDomainController;
use App\Http\Controllers\Superadmin\RoleController;
use App\Http\Controllers\Superadmin\SupportTicketController;
use App\Http\Controllers\Superadmin\SettingsController;
// use App\Http\Controllers\SocialLoginController;
use App\Http\Controllers\Superadmin\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Database\Models\Domain;
use Stancl\Tenancy\Features\UserImpersonation;
use App\Http\Controllers\Superadmin\FlutterwaveController;
use App\Http\Controllers\Superadmin\PaystackController;
use App\Http\Controllers\Superadmin\PaytmController;
use App\Http\Controllers\Superadmin\CoingateController;
use App\Http\Controllers\Superadmin\CouponController;
use App\Http\Controllers\Superadmin\MercadoController;
use App\Http\Controllers\Superadmin\FaqController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/test-mail', [SettingsController::class, 'testMail'])->name('test.mail')->middleware(['auth', 'xss']);
Route::get('/riskcurb', [RiskCurbApp::class, 'index'])->name('riskcurb')->middleware(['auth', 'xss']);
Route::get('/riskcurb/framework', [RiskCurbApp::class, 'indexFramework'])->name('riskcurb.framework')->middleware(['auth', 'xss']);
Route::get('/riskcurb/reports', [RiskCurbApp::class, 'indexFramework'])->name('riskcurb.reports')->middleware(['auth', 'xss']);
Route::get('/riskcurb/documents', [RiskCurbApp::class, 'indexFramework'])->name('riskcurb.documents')->middleware(['auth', 'xss']);
Route::get('/riskcurb/insurance', [RiskCurbApp::class, 'indexFramework'])->name('riskcurb.insurance')->middleware(['auth', 'xss']);
Route::post('/riskcurb/framework/create', [RiskCurbApp::class, 'createFramework'])->name('framework.create')->middleware(['auth', 'xss']);
Route::get('/riskcurbKeys', [RiskCurbApp::class, 'apiKeys'])->name('riskcurbKeys')->middleware(['auth', 'xss']);
Route::get('/riskcurbprompts', [RiskCurbApp::class, 'adminPrompts'])->name('Adminprompts')->middleware(['auth', 'xss']);
Route::post('/riskcurbprompts/save', [RiskCurbApp::class, 'adminpromptsSave'])->name('AdminpromptsSave')->middleware(['auth', 'xss']);
Route::post('/riskcurbprompts/api', [RiskCurbApp::class, 'adminPromptsApi'])->name('AdminpromptsApi')->middleware(['auth', 'xss']);
Route::post('/riskcurbprompts/api/generate', [RiskCurbApp::class, 'adminPromptsApiGenerate'])->name('SectionGenerateData')->middleware(['auth', 'xss']);
Route::get('/riskcurbKeys/Cancel', [RiskCurbApp::class, 'apiKeysRemove'])->name('riskcurbKeysCancel')->middleware(['auth', 'xss']);
Route::post('/riskcurbKeys', [RiskCurbApp::class, 'apiKeysSave'])->name('riskcurbKeys')->middleware(['auth', 'xss']);
Route::post('/risks/generate', [RiskCurbApp::class, 'create'])->name('risks.generate')->middleware(['auth', 'xss']);

//froentend
Route::get('froentend-setting', [SettingsController::class, 'froentendsetting'])->name('froentend.page')->middleware(['Setting', 'xss']);
Route::post('froentend-setting/store', [SettingsController::class, 'froentendsettingstore'])->name('froentend.page.store')->middleware(['Setting', 'xss']);
Route::post('menu-setting/store', [SettingsController::class, 'menusettingstore'])->name('menu.page.store')->middleware(['Setting', 'xss']);
Route::post('price-setting/store', [SettingsController::class, 'pricesettingstore'])->name('price.page.store')->middleware(['Setting', 'xss']);
Route::post('faq-setting/store', [SettingsController::class, 'faqsettingstore'])->name('faq.page.store')->middleware(['Setting', 'xss']);
Route::post('feature-setting/store', [SettingsController::class, 'featuresettingstore'])->name('feature.page.store')->middleware(['Setting', 'xss']);
Route::post('sidefeature-setting/store', [SettingsController::class, 'sidefeaturesettingstore'])->name('sidefeature.page.store')->middleware(['Setting', 'xss']);
Route::resource('faqs', FaqController::class)->middleware(['auth', 'Setting', 'xss', '2fa', 'verified']);



Route::post('privacy-setting/store', [SettingsController::class, 'privacysettingstore'])->name('privacy.page.store');
Route::post('contactus-setting/store', [SettingsController::class, 'contactussettingstore'])->name('contactus.page.store');
Route::post('termcondition-setting/store', [SettingsController::class, 'termconditionsettingstore'])->name('termcondition.page.store');

 // Route::get('landing-page', [SettingsController::class, 'landingPage'])->name('landing.page');
// Route::post('landing-page/store', [SettingsController::class, 'landingPagestore'])->name('landing.page.store')->middleware(['auth', 'Setting']);


Route::group(['middleware' => ['Setting', 'xss',]], function () {
    Auth::routes(['verify' => true]);
    Route::get('/register/{lang?}', [RegisterController::class, 'index'])->name('register');
    Route::get('/login/{lang?}', [LoginController::class, 'showLoginForm'])->name('login');
    Route::get('/', [RequestDomainController::class, 'landingPage'])->name('landingpage');
    Route::post('landingpage/payment', [RequestDomainController::class, 'landingPagePayment'])->name('landingpage.payment');
    Route::get('contact-us', [RequestDomainController::class, 'contactus'])->name('contactus');
    Route::get('terms-conditions', [RequestDomainController::class, 'termsandconditions'])->name('termsandconditions');
    Route::get('privacy-policy', [RequestDomainController::class, 'privacypolicy'])->name('privacypolicy');
    Route::get('faq', [RequestDomainController::class, 'faq'])->name('faq');
    Route::post('contact-mail', [RequestDomainController::class, 'contact_mail'])->name('contact.mail');
    Route::get('/request-domain/create/{id}/{lang?}', [RequestDomainController::class, 'create'])->name('requestdomain.create');
    Route::get('/request-domain/payment/{id}', [RequestDomainController::class, 'payment'])->name('requestdomain.payment');
    Route::post('/request-domain/store', [RequestDomainController::class, 'store'])->name('requestdomain.store');
    Route::post('pre-stripe/pending', [RequestDomainController::class, 'stripePostpending'])->name('pre.stripe.pending');

    Route::get('/pre-payment-success/{id}', [RequestDomainController::class, 'prepaymentSuccess'])->name('pre.stripe.success.pay');
    Route::get('/pre-payment-cancel/{id}', [RequestDomainController::class, 'prepaymentCancel'])->name('pre.stripe.cancel.pay');
    Route::post('pre-stripe', [RequestDomainController::class, 'prestripeSession'])->name('pre.stripe.session');
    Route::post('get-category-blog', [RequestDomainController::class, 'get_category_post'])->name('get.category.post');
    Route::get('blog-detail/{slug}', [RequestDomainController::class, 'post_details'])->name('post.details');
    Route::get('view-blog', [PostsController::class, 'all_post'])->name('view.post');
    Route::get('/register', [RegisterController::class, 'index'])->name('register');
    Route::post('offline-paysuccess', [RequestDomainController::class, 'offlinePaymentEntry'])->name('offline.payment.entry');
    Route::get('/apply-coupon', [CouponController::class, 'applyCoupon'])->name('apply.coupon');
    Route::get('/coupon-status/{id}', [CouponController::class, 'couponStatus'])->name('coupon.status');
    Route::resource('email-template', EmailTemplateController::class);

    //razopay
    Route::post('razorpay/payment', [RazorpayPaymentController::class, 'razorPaypayment'])->name('razorpay.payment');
    Route::get('/razorpay/callback/{order_id}/{transaction_id}/{requestdomain_id}/{coupon_id}', [RazorpayPaymentController::class, 'razorPaycallback']);

    //flutterwave
    Route::post('/flutterwave/payment', [FlutterwaveController::class, 'flutterwavepayment'])->name('flutterwave.payment');
    Route::get('/flutterwave/callback/{order_id}/{transaction_id}/{requestdomain_id}/{coupon_id}', [FlutterwaveController::class, 'flutterwavecallback']);

    //paystack
    Route::post('/paystack/payment', [PaystackController::class, 'paystackpayment'])->name('paystack.payment');
    Route::get('/paystack/callback/{order_id}/{transaction_id}/{requestdomain_id}/{coupon_id}', [PaystackController::class, 'paystackcallback']);
});
Route::post('/payment', [PaytmController::class, 'pay'])->name('paytm.payment');
Route::post('/payment/callback', [PaytmController::class, 'paymentCallback'])->name('paytm.callback');
Route::post('coingate/payment', [CoingateController::class, 'coingatepayment'])->name('coingate.payment');
Route::get('coingate-payment/{id}', [CoingateController::class, 'coingatePlanGetPayment'])->name('coingatecallback');

Route::post('mercadopago/payment', [MercadoController::class, 'mercadopagopayment'])->name('mercadopago.payment');
Route::any('mercadopago-callback/{id}', [MercadoController::class, 'mercadopagoPaymentCallback'])->name('mercado.callback');
Route::group(['middleware' => ['auth', 'Setting', 'xss', '2fa']], function () {

    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::post('/chart', [HomeController::class, 'chart'])->name('get.chart.data');
    Route::resource('roles', RoleController::class);
    Route::post('/role-permission/{id}', [RoleController::class, 'assignPermission'])->name('roles_permit');
    Route::resource('users', UserController::class);
    Route::resource('support-ticket', SupportTicketController::class);
    Route::resource('modules', ModuleController::class);
    Route::resource('profile', ProfileController::class);
    Route::resource('plans', PlanController::class);
    Route::resource('coupon', CouponController::class);

    Route::get('coupon/show', [CouponController::class, 'show'])->name('coupons.show');
    Route::get('coupon/csv/upload', [CouponController::class, 'upload_csv'])->name('coupon.upload');
    Route::post('coupon/csv/upload/store', [CouponController::class, 'upload_csv_store'])->name('coupon.upload.store');
    Route::get('coupon/mass/create', [CouponController::class, 'masscreate'])->name('coupon.mass.create');
    Route::post('coupon/mass/store', [CouponController::class, 'masscreate_store'])->name('coupon.mass.store');

    Route::resource('Offline', OfflineRequestController::class);
    Route::post('update/{id}', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('update-avatar/{id}', [ProfileController::class, 'updateAvatar']);

    Route::get('/request-domain/{id}/edit', [RequestDomainController::class, 'edit'])->name('requestdomain.edit');
    Route::post('/request-domain/{id}/update', [RequestDomainController::class, 'requestdomainupdate'])->name('requestdomain.update');
    Route::delete('/request-domain/{id}/delete', [RequestDomainController::class, 'destroy'])->name('requestdomain.delete');
    Route::get('profile-status', [ProfileController::class, 'profileStatus'])->name('profile.status');
    Route::delete('/profile-destroy/{id}/delete', [ProfileController::class, 'destroy'])->name('profile.delete');

    Route::post('user/update', [RequestDomainController::class, 'update'])->name('create.user');
    Route::get('/request-domain', [RequestDomainController::class, 'index'])->name('requestdomain.index');
    Route::get('/request-domain/approve/{id}', [RequestDomainController::class, 'approveStatus'])->name('requestdomain.approve.status');
    Route::get('/offline-request/{id}', [OfflineRequestController::class, 'offlinerequeststatus'])->name('offlinerequest.status');
    Route::get('/offline-request/disapprove/{id}', [OfflineRequestController::class, 'disapprovestatus'])->name('offline.disapprove.status');
    Route::post('/offline-request/disapprove-update/{id}', [OfflineRequestController::class, 'offlinedisapprove'])->name('requestuser.disapprove.update');
    Route::get('/change-domain', [ChangeDomainController::class, 'changeDomainIndex'])->name('changedomain');
    Route::post('/change-domain/approve/{id}', [ChangeDomainController::class, 'changeDomainApprove'])->name('changedomain.approve');
    Route::get('/change-domain/disapprove/{id}', [ChangeDomainController::class, 'domainDisapprove'])->name('changedomain.disapprove');
    Route::post('/change-domain/disapprove-status-update/{id}', [ChangeDomainController::class, 'domainDisapproveUpdate'])->name('domain.update');

    Route::get('/request-domain/disapprove/{id}', [RequestDomainController::class, 'disapproveStatus'])->name('requestdomain.disapprove.status');
    Route::post('/request-domain/disapprove-status-update/{id}', [RequestDomainController::class, 'updateStatus'])->name('status.update');
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
    Route::post('approve-mail', [RequestDomainController::class, 'approveSendMail'])->name('approve.send.mail');
    Route::post('ckeditor/upload', [SettingsController::class, 'upload'])->name('ckeditor.upload');
    Route::get('users/{id}/impersonate', [UserController::class, 'impersonate'])->name('users.impersonate');
    Route::get('users/{id}/leave', [UserController::class, 'leave'])->name('users.leave');
    Route::get('user-status/{id}', [UserController::class, 'userstatus'])->name('user.status');
    Route::post('support-ticket/{id}/conversion', [ConversionsController::class, 'store'])->name('conversion.store');
    Route::get('plan-status/{id}', [PlanController::class, 'planStatus'])->name('plan.status');

    Route::get('setting/{id}', [SettingsController::class, 'loadsetting'])->name('setting');
    Route::group(['prefix' => '2fa'], function () {
        Route::get('/', [LoginSecurityController::class, 'show2faForm']);
        Route::post('/generateSecret', [LoginSecurityController::class, 'generate2faSecret'])->name('generate2faSecret');
        Route::post('/enable2fa', [LoginSecurityController::class, 'enable2fa'])->name('enable2fa');
        Route::post('/disable2fa', [LoginSecurityController::class, 'disable2fa'])->name('disable2fa');
        Route::post('/2faVerify', function () {
            return redirect(URL()->previous());
        })->name('2faVerify')->middleware('2fa');
    });

    Route::get('update-avatar/{id}', [ProfileController::class, 'showAvatar'])->name('update_avatar');
    Route::get('design/{id}', [ProfileController::class, 'design'])->name('forms.design');
    Route::post('update-profile-login/{id}', [ProfileController::class, 'updateLogin'])->name('update_login');
    Route::post('/verify-2fa', [ProfileController::class, 'verify'])->name('verify_2fa');
    Route::post('/activate-2fa', [ProfileController::class, 'activate'])->name('activate_2fa');
    Route::post('/enable-2fa', [ProfileController::class, 'enable'])->name('enable_2fa');
    Route::post('/disable-2fa', [ProfileController::class, 'disable'])->name('disable_2fa');
    Route::get('/2fa/instruction', [ProfileController::class, 'instruction']);
    Route::get('change-language/{lang}', [LanguageController::class, 'changeLanquage'])->name('change.language');
    Route::get('manage-language/{lang}', [LanguageController::class, 'manageLanguage'])->name('manage.language');
    Route::post('store-language-data/{lang}', [LanguageController::class, 'storeLanguageData'])->name('store.language.data');
    Route::get('create-language', [LanguageController::class, 'createLanguage'])->name('create.language');
    Route::post('store-language', [LanguageController::class, 'storeLanguage'])->name('store.language');
    Route::delete('/lang/{lang}', [LanguageController::class, 'destroyLang'])->name('lang.destroy');
    Route::get('language', [LanguageController::class, 'index'])->name('index');
    Route::post('offline-payment', [OfflineRequestController::class, 'offlinePaymentEntry'])->name('offline.payment.request');
    Route::get('myplans', [PlanController::class, 'myPlan'])->name('plans.myplan');
    Route::get('sales', [HomeController::class, 'sales'])->name('sales.index');
});
Route::post('process-transactionadmin', [PaymentController::class, 'processTransactionadmin'])->name('payprocessTransactionadmin');
Route::post('process-transactions', [PayPalController::class, 'processTransaction'])->name('processTransaction');
Route::get('success-transactions/{data}', [PayPalController::class, 'successTransaction'])->name('successTransaction');
Route::get('cancel-transactions/{data}', [PayPalController::class, 'cancelTransaction'])->name('cancelTransaction');
