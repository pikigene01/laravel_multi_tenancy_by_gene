<?php

namespace App\Http\Controllers\Superadmin;
namespace App\Http\Livewire;

use App\DataTables\Superadmin\ChangeDomainRequestDataTable;
use App\Http\Controllers\Controller;
use App\DataTables\Superadmin\RequestDomainDataTable;
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

use Livewire\Component;

class HomePage extends Component
{

    public function increment(){
        dd('gene piki');
    }


    public function render()
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
                return view('livewire.home-page', ['plans'=>$plans, 'features'=>$features, 'faqs'=>$faqs]);
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
            return view('livewire.home-page', compact('posts', 'category', 'features', 'faqs'));

            // return view('welcome', compact('posts', 'category', 'features', 'faqs'));
        }

    }
}
