<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\DataTables\Admin\RequestDomainDataTable;
use App\Facades\UtilityFacades;
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
use App\Mail\Admin\ConatctMail;
use App\Mail\DisapprovedMail;
use App\Models\Category;
use App\Models\Posts;
use Spatie\MailTemplates\Models\MailTemplate;
use Stripe\Product;
use App\Models\Faq;

class LandingController extends Controller
{
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
            $features=json_decode(UtilityFacades::getsettings('feature_setting'));
            $plans = Plan::where('active_status',1)->get();
            return view('welcome', compact('plans','features','faqs'));
        } else {
            $faqs = Faq::all();
            $features=json_decode(UtilityFacades::getsettings('feature_setting'));
            $categories = Category::all();
            $category = [];
            $category['0'] = __('Select category');
            foreach ($categories as $cate) {
                $category[$cate->id] = $cate->name;
            }
            $posts =  Posts::latest()->take(4)->get();
            if (UtilityFacades::getsettings('landing_page_status') == '1') {
                return view('welcome', compact('posts','category','features','faqs'));
            } else {
                return redirect()->route('home');
            }
        }
    }

    public function get_category_post(Request $request)
    {
        $post = Posts::where('category_id', $request->category)->get();
        return response()->json($post, 200);
    }
    public function post_details($slug, Request $request)
    {
        $post = Posts::where('slug', $slug)->first();
        $random_posts = Posts::where('slug', '!=', $slug)->limit(3)->get();
        return view('admin.posts.details', compact('post', 'random_posts'));
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
            if ($request) {
                $details = $request->all();
                Mail::to(UtilityFacades::getsettings('contact_us_email'))->send(new ConatctMail($request->all()));
                return redirect()->back()->with('success', 'Email sent successfully.');
            } else {
                return redirect()->back()->with('failed', __('Please check recaptch.'));
            }
        }
    }
}
