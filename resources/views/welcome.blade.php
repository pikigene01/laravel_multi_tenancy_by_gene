@php
    $currency = tenancy()->central(function ($tenant) {
        return Utility::getsettings('currency_symbol');
    });
@endphp
@section('title')
    {{ __('Home') }}
@endsection
<!DOCTYPE html>
<html lang="en">
@include('layouts.front_header')
<header id="home" class="bg-primary">
    <div class="container">
        <div class="row align-items-center justify-content-between">
            <div class="col-sm-5">
                <h1 class="text-white mb-sm-4 wow animate__fadeInLeft" data-wow-delay="0.2s">
                    {{ Utility::getsettings('app_name') }}
                </h1>
                <h2 class="text-white mb-sm-4 wow animate__fadeInLeft" data-wow-delay="0.4s">
                    @if (Utility::getsettings('apps_title'))
                        {{ Utility::getsettings('apps_title') }}
                    @else
                        {{ __('Tenancy for Laravel') }}
                    @endif
                    <br />
                </h2>
                <p class="mb-sm-4 wow animate__fadeInLeft" data-wow-delay="0.6s">
                    @if (Utility::getsettings('apps_paragraph'))
                        {{ Utility::getsettings('apps_paragraph') }}
                    @else
                        {{ __('A flexible multi-tenancy package for Laravel. Single & multi-database tenancy, automatic & manual mode,
                                                                                                                                                                                                                                                                                                                            event-based architecture. Integrates perfectly with other packages.') }}
                    @endif
                </p>
            </div>
            <div class="col-sm-5">
                @if (tenant('id') == null)
                    <img src="{{ Utility::getsettings('image')
                        ? Storage::url(Utility::getsettings('image'))
                        : asset('assets/img/header_mokeup1.svg') }}"
                        alt="Datta Able Admin Template" class="img-fluid header-img wow animate__fadeInRight"
                        data-wow-delay="0.2s" />
                @else
                    <img src="{{ Utility::getsettings('image')
                        ? Storage::url(tenant('id') . '/' . Utility::getsettings('image'))
                        : asset('assets/img/header_mokeup1.svg') }}"
                        alt="Datta Able Admin Template" class="img-fluid header-img wow animate__fadeInRight"
                        data-wow-delay="0.2s" />
                @endif
            </div>
        </div>
    </div>
</header>
<section class="">
    <div class="container">
        <div class="row align-items-center justify-content-end mb-5">
            <div class="col-sm-4">
                <h1 class="mb-sm-4 f-w-600 wow animate__fadeInLeft" data-wow-delay="0.2s">
                    @if (Utility::getsettings('menu_name'))
                        {{ Utility::getsettings('menu_name') }}
                    @else
                        {{ __('Dashboard') }}
                    @endif
                </h1>
                <h2 class="mb-sm-4 wow animate__fadeInLeft" data-wow-delay="0.4s">
                    @if (Utility::getsettings('menu_subtitle'))
                        {{ Utility::getsettings('menu_subtitle') }}
                    @else
                        {{ __('All in one place') }}
                    @endif
                    <br />
                    @if (Utility::getsettings('menu_title'))
                        {{ Utility::getsettings('menu_title') }}
                    @else
                        {{ __('CRM system') }}
                    @endif
                </h2>
                <p class="mb-sm-4 wow animate__fadeInLeft" data-wow-delay="0.6s">
                    @if (Utility::getsettings('menu_title'))
                        {{ Utility::getsettings('menu_paragraph') }}
                    @else
                        {{ __('Use these awesome forms to login or create new account in your project for free.') }}
                    @endif
                </p>
            </div>
            @if (tenant('id') == null)
                <div class="col-sm-6">
                    <img src="{{ Utility::getsettings('images1')
                        ? Storage::url(Utility::getsettings('images1'))
                        : asset('assets/img/dashboards.png') }}"
                        alt="Datta Able Admin Template" class="img-fluid header-img wow animate__fadeInRight"
                        data-wow-delay="0.2s" />
                </div>
            @else
                <div class="col-sm-6">
                    <img src="{{ Utility::getsettings('images1')
                        ? Storage::url(tenant('id') . '/' . Utility::getsettings('images1'))
                        : asset('assets/img/dashboard.png') }}"
                        alt="Datta Able Admin Template" class="img-fluid header-img wow animate__fadeInRight"
                        data-wow-delay="0.2s" />
                </div>
            @endif
        </div>
        <div class="row align-items-center justify-content-start">
            <div class="col-sm-6">
                @if (tenant('id') == null)
                    <img src="{{ Utility::getsettings('images2')
                        ? Storage::url(Utility::getsettings('images2'))
                        : asset('assets/img/img_crm_dash_21.svg') }}"
                        alt="Datta Able Admin Template" class="img-fluid header-img wow animate__fadeInLeft"
                        data-wow-delay="0.2s" />
                @else
                    <img src="{{ Utility::getsettings('images2')
                        ? Storage::url(tenant('id') . '/' . Utility::getsettings('images2'))
                        : asset('assets/img/img_crm_dash_21.svg') }}"
                        alt="Datta Able Admin Template" class="img-fluid header-img wow animate__fadeInLeft"
                        data-wow-delay="0.2s" />
                @endif
            </div>
            <div class="col-sm-4">
                <h1 class="mb-sm-4 f-w-600 wow animate__fadeInRight" data-wow-delay="0.2s">
                    @if (Utility::getsettings('submenu_name'))
                        {{ Utility::getsettings('submenu_name') }}
                    @else
                        {{ __('Dashboard') }}
                    @endif
                </h1>
                <h2 class="mb-sm-4 wow animate__fadeInRight" data-wow-delay="0.4s">
                    @if (Utility::getsettings('submenu_subtitle'))
                        {{ Utility::getsettings('submenu_subtitle') }}
                    @else
                        {{ __('All in one place') }}
                    @endif
                    <br />
                    @if (Utility::getsettings('submenu_title'))
                        {{ Utility::getsettings('submenu_title') }}
                    @else
                        {{ __('CRM system') }}
                    @endif
                </h2>
                <p class="mb-sm-4 wow animate__fadeInRight" data-wow-delay="0.6s">
                    @if (Utility::getsettings('submenu_paragraph'))
                        {{ Utility::getsettings('submenu_paragraph') }}
                    @else
                        {{ __('Use these awesome forms to login or create new account in your project for free.') }}
                    @endif
                </p>
            </div>
        </div>
    </div>
</section>

<section id="feature" class="feature">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-6 col-md-9 title">
                <h2>
                    <span class="d-block mb-3">
                        @if (Utility::getsettings('feature_name'))
                            {{ Utility::getsettings('feature_name') }}
                        @else
                            {{ __('Features') }}
                        @endif
                    </span>
                    @if (Utility::getsettings('feature_title'))
                        {{ Utility::getsettings('feature_title') }}
                    @else
                        {{ __('Automatic Tenancy') }}
                    @endif
                </h2>
                <p class="m-0">
                    @if (Utility::getsettings('feature_paragraph'))
                        {{ Utility::getsettings('feature_paragraph') }}
                    @else
                        {{ __('Instead of forcing you to change how you write your code, the package by default
                                                                                                                                                                                                                                                                                                                        bootstraps tenancy automatically, in the background. Database connections are switched,
                                                                                                                                                                                                                                                                                                                        caches are separated, filesystems are prefixed, etc.') }}
                    @endif
                </p>
            </div>
        </div>
        <div class="row justify-content-center">
            @if ($features)
                @foreach ($features as $feature)
                    <div class="col-lg-3 col-md-6">
                        <div class="card wow animate__fadeInUp" data-wow-delay="0.2s"
                            style="
                            visibility: visible;
                            animation-delay: 0.2s;
                            animation-name: fadeInUp;">
                            <div class="card-body">
                                <div class="theme-avtar {{ $feature->theme_color }}">
                                    <i class="{{ $feature->avtar_format }}"></i>
                                </div>
                                <h6 class="text-muted mt-4">
                                    {{ $feature->feature_subname }}
                                </h6>
                                <h4 class="my-3 f-w-600">
                                    {{ $feature->feature_subtitle }}
                                </h4>
                                <p class="mb-0">
                                    {{ $feature->feature_subparagraph }}
                                </p>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</section>

@if (tenant('id') != null)
    <section id="feature" class="feature">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-6 col-md-9 title">
                    <h2>
                        <span class="d-block mb-3">
                            @if (Utility::getsettings('post_name'))
                                {{ Utility::getsettings('post_name') }}
                            @else
                                {{ __('Posts') }}
                            @endif
                        </span>
                    </h2>
                    <p class="m-0">
                        @if (Utility::getsettings('post_title'))
                            {{ Utility::getsettings('post_title') }}
                        @else
                            {{ __(' Use these awesome forms to login or create new account in your project for free.') }}
                        @endif
                    </p>
                </div>
            </div>
            <div class="row justify-content-center">
                @foreach ($posts as $post)
                    <div class="col-lg-3 col-md-6">
                        <div class="card wow animate__fadeInUp" data-wow-delay="0.2s"
                            style="
                            visibility: visible;
                            animation-delay: 0.2s;
                            animation-name: fadeInUp;">
                            <img class="img-fluid card-img-top card-img-custom"
                                src="{{ Storage::url(tenant('id') . '/' . $post->photo) }}" alt="Card image cap">
                            <div class="card-body">
                                <h5 class="card-title">{{ $post->title }}</h5>
                                <p class="card-text">
                                    {{ substr($post->short_description, 0, 75) . (strlen($post->short_description) > 75 ? '...' : '') }}
                                </p>
                                <a href="{{ route('post.details', $post->slug) }}">{{ __('Read More') }}<i
                                        class="ti ti-chevron-right ms-2"></i></a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endif
@if (tenant('id') == null)
    <section id="plans" class="price-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-6 col-md-9 title">
                    <h2>
                        <span class="d-block mb-3">
                            @if (Utility::getsettings('price_title'))
                                {{ Utility::getsettings('price_title') }}
                            @else
                                {{ __('Price') }}
                            @endif
                        </span>
                    </h2>
                    <p class="m-0">
                        @if (Utility::getsettings('price_paragraph'))
                            {{ Utility::getsettings('price_paragraph') }}
                        @else
                            {{ __(' Price components are very important for SaaS projects or other projects.') }}
                        @endif
                    </p>
                </div>
            </div>
            <div class="row justify-content-center">
                @foreach ($plans as $plan)
                    @if ($plan->active_status == 1)
                        <div class="col-lg-4 col-md-6">
                            <div class="card price-card price-1 wow animate__fadeInUp" data-wow-delay="0.2s"
                                style="
                                visibility: visible;
                                animation-delay: 0.2s;
                                animation-name: fadeInUp;">
                                <div class="card-body">
                                    <span class="price-badge bg-primary">{{ $plan->name }}</span>
                                    <span class="mb-4 f-w-600 p-price">{{ $currency . '' . $plan->price }}<small
                                            class="text-sm">/{{ $plan->duration . ' ' . $plan->durationtype }}</small></span>
                                    <p class="mb-0">
                                        {{ __('You have Free Unlimited Updates and') }} <br />
                                        {{ __('Premium Support on each package.') }}
                                    </p>
                                    <ul class="list-unstyled my-5">
                                        <li>
                                            <span class="theme-avtar">
                                            </span>
                                        </li>
                                    </ul>
                                    <div class="d-grid text-center">

                                        @if ($plan->id == 1)
                                            <div class="pricing-cta">
                                                <a href="{{ route('requestdomain.create', Crypt::encrypt(['plan_id' => $plan->id])) }}"
                                                    class="subscribe_plan btn btn-primary btn-block mt-2 btn btn-primary btn-block mt-2"
                                                    data-id="{{ $plan->id }}"
                                                    data-amount="{{ $plan->price }}">{{ __('Free') }}
                                                    <i class="ti ti-chevron-right ms-2"></i></a>
                                            </div>
                                        @elseif ($plan->id != 1)
                                            <div class="pricing-cta">
                                                <a href="{{ route('requestdomain.create', Crypt::encrypt(['plan_id' => $plan->id])) }}"
                                                    class="subscribe_plan btn btn-primary btn-block mt-2 btn btn-primary btn-block mt-2"
                                                    data-id="{{ $plan->id }}"
                                                    data-amount="{{ $plan->price }}">{{ __('Subscribe') }}
                                                    <i class="ti ti-chevron-right ms-2"></i></a>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    </section>
@endif
<section class="faq">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-6 col-md-9 title">
                <h2>
                    @if (Utility::getsettings('faq_title'))
                        {{ Utility::getsettings('faq_title') }}
                    @else
                        {{ __('Frequently Asked Questions') }}
                    @endif
                </h2>
                <p class="m-0">
                    @if (Utility::getsettings('faq_paragraph'))
                        {{ Utility::getsettings('faq_paragraph') }}
                    @else
                        {{ __("Use these awesome forms to login or create new account in your
                                                                                                                                                                                                                                                                                                                            project for free.") }}
                    @endif
                </p>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-sm-12 col-md-10 col-xxl-8">
                <div class="accordion accordion-flush" id="accordionExample">
                    @foreach ($faqs as $faq)
                        <div class="accordion-item card">
                            <h2 class="accordion-header" id="headingTwo">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapseTwo{{ $faq->id }}" aria-expanded="false"
                                    aria-controls="collapseTwo{{ $faq->id }}">
                                    <span class="d-flex align-items-center">
                                        <i class="ti ti-info-circle text-primary"></i>
                                        {{ $faq->quetion }}
                                    </span>
                                </button>
                            </h2>
                            <div id="collapseTwo{{ $faq->id }}" class="accordion-collapse collapse"
                                aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    {!! $faq->answer !!}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
<section class="side-feature">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-sm-3">
                <h1 class="mb-sm-4 f-w-600 wow animate__fadeInLeft" data-wow-delay="0.2s">
                    @if (Utility::getsettings('sidefeature_name'))
                        {{ Utility::getsettings('sidefeature_name') }}
                    @else
                        {{ __('Dashboard') }}
                    @endif
                </h1>
                <h2 class="mb-sm-4 wow animate__fadeInLeft" data-wow-delay="0.4s">
                    @if (Utility::getsettings('sidefeature_title'))
                        {{ Utility::getsettings('sidefeature_title') }}
                    @else
                        {{ __('All in one place') }}
                    @endif
                    <br />
                    @if (Utility::getsettings('sidefeature_subtitle'))
                        {{ Utility::getsettings('sidefeature_subtitle') }}
                    @else
                        {{ __('CRM system') }}
                    @endif
                </h2>
                <p class="mb-sm-4 wow animate__fadeInLeft" data-wow-delay="0.6s">
                    @if (Utility::getsettings('sidefeature_paragraph'))
                        {{ Utility::getsettings('sidefeature_paragraph') }}
                    @else
                        {{ __(' Use these awesome forms to login or create new account in your project for free.') }}
                    @endif
                </p>
            </div>
            <div class="col-sm-9">
                <div class="row gy-4 feature-img-row">
                    @if (tenant('id') == null)
                        <div class="col-3">
                            <img src="{{ Utility::getsettings('image1')
                                ? Storage::url(Utility::getsettings('image1'))
                                : asset('assets/img/front1.png') }}"
                                class="img-fluid header-img wow animate__fadeInRight card-img-top card-img-custom m-2"
                                data-wow-delay="0.2s" alt="Admin" />
                        </div>
                        <div class="col-3">
                            <img src="{{ Utility::getsettings('image2')
                                ? Storage::url(Utility::getsettings('image2'))
                                : asset('assets/img/front2.png') }}"
                                class="img-fluid header-img wow animate__fadeInRight card-img-top card-img-custom m-2"
                                data-wow-delay="0.4s" alt="Admin" />
                        </div>
                        <div class="col-3">
                            <img src="{{ Utility::getsettings('image3')
                                ? Storage::url(Utility::getsettings('image3'))
                                : asset('assets/img/front3.png') }}"
                                class="img-fluid header-img wow animate__fadeInRight card-img-top card-img-custom m-2"
                                data-wow-delay="0.6s" alt="Admin" />
                        </div>
                        <div class="col-3">
                            <img src="{{ Utility::getsettings('image4')
                                ? Storage::url(Utility::getsettings('image4'))
                                : asset('assets/img/front4.png') }}"
                                class="img-fluid header-img wow animate__fadeInRight card-img-top card-img-custom m-2"
                                data-wow-delay="0.8s" alt="Admin" />
                        </div>
                        <div class="col-3">
                            <img src="{{ Utility::getsettings('image5')
                                ? Storage::url(Utility::getsettings('image5'))
                                : asset('assets/img/front5.png') }}"
                                class="img-fluid header-img wow animate__fadeInRight card-img-top card-img-custom m-2"
                                data-wow-delay="0.3s" alt="Admin" />
                        </div>
                        <div class="col-3">
                            <img src="{{ Utility::getsettings('image6')
                                ? Storage::url(Utility::getsettings('image6'))
                                : asset('assets/img/front6.png') }}"
                                class="img-fluid header-img wow animate__fadeInRight card-img-top card-img-custom m-2"
                                data-wow-delay="0.5s" alt="Admin" />
                        </div>
                        <div class="col-3">
                            <img src="{{ Utility::getsettings('image7')
                                ? Storage::url(Utility::getsettings('image7'))
                                : asset('assets/img/front7.png') }}"
                                class="img-fluid header-img wow animate__fadeInRight card-img-top card-img-custom m-2"
                                data-wow-delay="0.7s" alt="Admin" />
                        </div>
                        <div class="col-3">
                            <img src="{{ Utility::getsettings('image8')
                                ? Storage::url(Utility::getsettings('image8'))
                                : asset('assets/img/front8.png') }}"
                                class="img-fluid header-img wow animate__fadeInRight card-img-top card-img-custom m-2"
                                data-wow-delay="0.9s" alt="Admin" />
                        </div>
                    @else
                        <div class="col-3">
                            <img src="{{ Utility::getsettings('image1')
                                ? Storage::url(tenant('id') . '/' . Utility::getsettings('image1'))
                                : asset('assets/img/front1.png') }}"
                                class="img-fluid header-img wow animate__fadeInRight card-img-top card-img-custom m-2"
                                data-wow-delay="0.2s" alt="Admin" />
                        </div>
                        <div class="col-3">
                            <img src="{{ Utility::getsettings('image2')
                                ? Storage::url(tenant('id') . '/' . Utility::getsettings('image2'))
                                : asset('assets/img/front2.png') }}"
                                class="img-fluid header-img wow animate__fadeInRight card-img-top card-img-custom m-2"
                                data-wow-delay="0.4s" alt="Admin" />
                        </div>
                        <div class="col-3">
                            <img src="{{ Utility::getsettings('image3')
                                ? Storage::url(tenant('id') . '/' . Utility::getsettings('image3'))
                                : asset('assets/img/front3.png') }}"
                                class="img-fluid header-img wow animate__fadeInRight card-img-top card-img-custom m-2"
                                data-wow-delay="0.6s" alt="Admin" />
                        </div>
                        <div class="col-3">
                            <img src="{{ Utility::getsettings('image4')
                                ? Storage::url(tenant('id') . '/' . Utility::getsettings('image4'))
                                : asset('assets/img/front4.png') }}"
                                class="img-fluid header-img wow animate__fadeInRight card-img-top card-img-custom m-2"
                                data-wow-delay="0.8s" alt="Admin" />
                        </div>
                        <div class="col-3">
                            <img src="{{ Utility::getsettings('image5')
                                ? Storage::url(tenant('id') . '/' . Utility::getsettings('image5'))
                                : asset('assets/img/front5.png') }}"
                                class="img-fluid header-img wow animate__fadeInRight card-img-top card-img-custom m-2"
                                data-wow-delay="0.3s" alt="Admin" />
                        </div>
                        <div class="col-3">
                            <img src="{{ Utility::getsettings('image6')
                                ? Storage::url(tenant('id') . '/' . Utility::getsettings('image6'))
                                : asset('assets/img/front6.png') }}"
                                class="img-fluid header-img wow animate__fadeInRight card-img-top card-img-custom m-2"
                                data-wow-delay="0.5s" alt="Admin" />
                        </div>
                        <div class="col-3">
                            <img src="{{ Utility::getsettings('image7')
                                ? Storage::url(tenant('id') . '/' . Utility::getsettings('image7'))
                                : asset('assets/img/front7.png') }}"
                                class="img-fluid header-img wow animate__fadeInRight card-img-top card-img-custom m-2"
                                data-wow-delay="0.7s" alt="Admin" />
                        </div>
                        <div class="col-3">
                            <img src="{{ Utility::getsettings('image8')
                                ? Storage::url(tenant('id') . '/' . Utility::getsettings('image8'))
                                : asset('assets/img/front8.png') }}"
                                class="img-fluid header-img wow animate__fadeInRight card-img-top card-img-custom m-2"
                                data-wow-delay="0.9s" alt="Admin" />
                        </div>
                    @endif
                </div>
            </div>
        </div>

    </div></section>

@include('layouts.front_footer')
</body>

</html>
