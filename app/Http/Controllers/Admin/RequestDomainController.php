<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\DataTables\RequestDomainDataTable;
use App\Facades\UtilityFacades;
use App\Models\Order;
use App\Models\Plan;
use App\Models\RequestDomain;
use App\Models\Setting;
use App\Models\Tenant;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Stancl\Tenancy\Database\Models\Domain;
use Stripe\Stripe;
use App\Mail\Admin\DisapprovedMail;
use App\Mail\Admin\ApproveMail;
use App\Mail\Admin\ConatctMail;
use App\Models\Category;
use App\Models\Faq;
use App\Models\Posts;
use Illuminate\Support\Facades\Mail;

class RequestDomainController extends Controller
{
    public function landingPage()
    {
        $faqs = Faq::where('tenant_id', '=', tenant('id'))->get();
        $central_domain = config('tenancy.central_domains')[0];
        $current_domain = tenant('domains');
        $categories = Category::where('tenant_id', tenant('id'))->get();
        $category = [];
        $category['0'] = __('Select Category');
        foreach ($categories as $cate) {
            $category[$cate->id] = $cate->name;
        }
        $posts = Posts::where('tenant_id', tenant('id'))->latest()->take(4)->get();
        if (UtilityFacades::getsettings('landing') == '1') {
            return view('welcome', compact('posts', 'category', 'faqs'));
        } else {
            return redirect()->route('home');
        }
    }

    public function get_category_post(Request $request)
    {
        $posts = Posts::where('category_id', $request->category)->where('tenant_id', tenant('id'))->get();
        $post = [];
        foreach ($posts as $key => $value) {
            if (Storage::exists($value->photo)) {
                $photo =  Storage::url(tenant('id') . '/' . $value->photo);
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
        $random_posts = Posts::where('slug', '!=', $slug)->where('tenant_id', tenant('id'))->limit(3)->get();
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
        if ($request) {
            Mail::to(UtilityFacades::getsettings('contact_email'))->send(new ConatctMail($request->all()));
            return redirect()->back()->with('success', __('Email sent successfully.'));
        } else {
            return redirect()->back()->with('failed', __('Please check recaptch.'));
        }
    }
}
