@php
    $currency = tenancy()->central(function ($tenant) {
        return Utility::getsettings('currency_symbol');
    });
@endphp
@section('title')
    {{ __('Home') }}
@endsection
@push('css')
@endpush

<!DOCTYPE html>
<html lang="en">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <!-- Favicon icon -->
    <link rel="icon"
        href="{{ Storage::exists('logo/app-favicon-logo.png') ? Utility::getpath('logo/app-favicon-logo.png') : Storage::url('logo/app-favicon-logo.png') }}"
        type="image/png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

      <!-- font css -->
      <link rel="stylesheet" href="{{ asset('assets/fonts/tabler-icons.min.css') }}">
      <link rel="stylesheet" href="{{ asset('assets/css/plugins/notifier.css') }}">
      <link rel="stylesheet" href="{{ asset('assets/fonts/feather.css') }}">
      <link rel="stylesheet" href="{{ asset('assets/fonts/fontawesome.css') }}">
      <link rel="stylesheet" href="{{ asset('assets/fonts/material.css') }}">
      @if (Utility::getsettings('rtl') == '1')
          <link rel="stylesheet" href="{{ asset('assets/css/style-rtl.css') }}" id="main-style-link">
      @endif
      @if (Utility::getsettings('dark_mode') == 'on')
          <link rel="stylesheet" href="{{ asset('assets/css/style-dark.css') }}">
      @else
          <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" id="main-style-link">
      @endif
      <!-- vendor css -->
      <link rel="stylesheet" href="{{ asset('assets/css/customizer.css') }}">
      <link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}">
<style type="text/css">
    :root {
        --gray: #64748b;
        --gray-l-1: #748297;
        --gray-l-2: #8390a3;
        --gray-l-3: #a2acba;
        --gray-l-4: #c1c8d1;
        --gray-l-5: #e0e4e8;
        --gray-l-6: #f0f2f4;
        --gray-d-1: #5a697e;
        --gray-d-2: #505d70;
        --gray-d-3: #3c4654;
        --gray-d-4: #282f38;
        --gray-d-5: #14181c;
        --gray-d-6: #0a0c0e;
        --rose: #db2777;
        --rose-l-1: #df3d85;
        --rose-l-2: #e35393;
        --rose-l-3: #ea7eae;
        --rose-l-4: #f1a9c9;
        --rose-l-5: #f8d4e4;
        --rose-l-6: #fceaf2;
        --rose-d-1: #c6246c;
        --rose-d-2: #b02060;
        --rose-d-3: #841848;
        --rose-d-4: #581030;
        --rose-d-5: #2c0818;
        --rose-d-6: #16040c;
        --indigo: #4f46e5;
        --indigo-l-1: #6159e8;
        --indigo-l-2: #736beb;
        --indigo-l-3: #9690f0;
        --indigo-l-4: #b9b5f5;
        --indigo-l-5: #dcdafa;
        --indigo-l-6: #eeedfd;
        --indigo-d-1: #483fcf;
        --indigo-d-2: #4038b8;
        --indigo-d-3: #302a8a;
        --indigo-d-4: #201c5c;
        --indigo-d-5: #100e2e;
        --indigo-d-6: #080717;
        --gap-3xs: clamp(0.5rem, 0.5rem + 0vw, 0.5rem);
        --gap-2xs: clamp(0.9rem, 0.8643rem + 0.0992vw, 1rem);
        --gap-xs: clamp(1.4rem, 1.3643rem + 0.0992vw, 1.5rem);
        --gap-s: clamp(1.8rem, 1.7286rem + 0.1984vw, 2rem);
        --gap-m: clamp(2.7rem, 2.5929rem + 0.2976vw, 3rem);
        --gap-l: clamp(3.6rem, 3.4571rem + 0.3968vw, 4rem);
        --gap-xl: clamp(5.4rem, 5.1857rem + 0.5952vw, 6rem);
        --gap-2xl: clamp(7.2rem, 6.9143rem + 0.7937vw, 8rem);
        --gap-3xl: clamp(10.8rem, 10.3714rem + 1.1905vw, 12rem);
        --radius-img: clamp(1.6rem, 1.3143rem + 0.7937vw, 2.4rem);
        --radius-card: clamp(0.6rem, 0.3857rem + 0.5952vw, 1.2rem);
        --radius-btn: clamp(0.4rem, 0.2571rem + 0.3968vw, 0.8rem);
        --dk-box-shdw: 0px 10px 30px var(--gray-l-5);
        --lt-box-shdw: 0px 10px 30px var(--gray-d-5);
        --font-h1: clamp(5.375rem, 4.57rem + 2.2361vw, 7.629rem);
        --font-h2: clamp(4.479rem, 3.8986rem + 1.6121vw, 6.104rem);
        --font-h3: clamp(3.732rem, 3.3209rem + 1.1419vw, 4.883rem);
        --font-h4: clamp(3.11rem, 2.8257rem + 0.7897vw, 3.906rem);
        --font-h5: clamp(2.592rem, 2.4016rem + 0.5288vw, 3.125rem);
        --font-h6: clamp(2.16rem, 2.0386rem + 0.3373vw, 2.5rem);
        --font-body: clamp(1.8rem, 1.7286rem + 0.1984vw, 2rem);
    }

    body {
        background: var(--gray-l-5);
    }

    .app-header {
        display: flex;
        justify-content: space-around;
        margin: 20px 0px;
        width: 100%;
        height: 60px;
        box-shadow: 0 0 0.5 rgb(0, 0, 0.5);
        /* position: fixed; */
        border-bottom: 2px solid var(--gray-d-1);
    }

    .menu-link {
        font-size: 20px;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        font-weight: 100;
        text-decoration: none;
        padding: 20px;
    }

    .app-btn {
        border: 3px solid var(--indigo);
        border-radius: 10px;
        padding: 10px 20px;
        font-weight: bolder;
        font-size: 2ch;
    }

    .app-btn:hover {
        cursor: pointer;
        transform: scale(1.1);
    }

    .btn-solid {
        background: var(--indigo);
        color: var(--indigo-l-6);
    }

    .btn-outline {
        color: var(--indigo);
    }

    .risk-hero {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        width: 100%;
    }

    .hero-pink-title {
        text-align: center;
        font-size: var(--font-h1);
        color: var(--rose);
        font-weight: 800;
        line-height: 1;
    }

    .hero-text {
        text-align: center;
        font-size: var(--font-h3);
        line-height: 1;
    }

    .arrow-img-top {
        display: absolute;
        transform: rotate(360deg);

    }

    .arrow-img-bottom {
        display: absolute;
        transform: scaleY(-1);
        margin-left: -200px;
        margin-top: 10px;
    }

    #brxe-mjrhwz {
    display: flex;
    flex-direction: row;
    align-items: center;
    column-gap: var(--gap-xs);
    justify-content: center;
    font-weight: 600;
    color: var(--indigo);
     }
     h4{
    font-family: "poppins";
    font-size: var(--font-h4);
     }
     h5{
    font-family: "poppins";
    font-size: var(--font-h5);
     }

     .text-bg-primary{
        background: var(--indigo);
     }
     .border-primary{
        border: 1px solid var(--indigo);
     }
</style>
<header>
    <section class="app-header">
        <div class="app-logo">
            <a href="/">
                <img style="width:100px;"
                    src="{{ Storage::exists('logo/app-logo.png') ? Utility::getpath('logo/app-logo.png') : Storage::url('logo/app-logo.png') }}"
                    class="app-logo img_setting">
            </a>
        </div>
        <div class="app-right-menus">
            <a class="menu-link" href="#">Home</a>
            <a class="menu-link" href="/#pricing">Pricing</a>
            @if (\Auth::user())
                <a class="menu-link" href="/riskcurb/framework"><span class="app-btn btn-outline">Account </span></a>
            @else
                <a class="menu-link" href="/login"><span class="app-btn btn-solid">Start Today </span></a>
            @endif
        </div>

    </section>
</header>

<section class="risk-hero">
    <div class="risk-hero-top">
        <span class="hero-text"> This is </span>
        <img src="{{ asset('public/images/arrow.png') }}" alt="icon" class="arrow-img-top" />

    </div>
    <div class="risk-hero-middle hero-pink-title">
        Risk Management Software
    </div>
    <div class="risk-hero-bottom">
        <span class="hero-text"> at it's best </span>
        <a href="/login"><span class="app-btn btn-solid" style="display: block;">
                Get Started - It's Free
            </span></a>

        <img src="{{ asset('public/images/arrow.png') }}" alt="icon" class="arrow-img-bottom" />
    </div>

</section>


<div id="brxe-mjrhwz">
    <h5 class="brxe-text-basic">RiskCurb will take your ERM from 0 to 100.</h5>
    <h4 class="brxe-text-basic">Guaranteed.</h4>
</div>

<div class="container py-3">

    <main id="pricing">
      <div class="row row-cols-1 row-cols-md-3 mb-3 text-center">
        @foreach ($plans as $plan)
        @if ($plan->active_status == 1)
        <div class="col">
            @if ($plan->id == 1)
            <div class="card mb-4 rounded-3 shadow-sm">
                <div class="card-header py-3">
                  <h4 class="my-0 fw-normal">{{ $plan->name }}</h4>
                </div>
            @else
            <div class="card mb-4 rounded-3 shadow-sm border-primary">
                <div class="card-header py-3 text-bg-primary border-primary">
                  <h4 class="my-0 fw-normal">{{ $plan->name }}</h4>
                </div>
            @endif

            <div class="card-body">
              <h1 class="card-title pricing-card-title">{{ $currency . '' . $plan->price }}<small class="text-body-secondary fw-light"> / {{ $plan->duration . ' ' . $plan->durationtype }}</small></h1>
              <ul class="list-unstyled mt-3 mb-4">
                <li> {{ __('You have Free Unlimited Updates and') }} <br />
                    {{ __('Premium Support on each package.') }}</li>
                    @if ($plan->id == 1)
                <li>limited storage upload</li>
                @else
                <li>unlimited storage upload</li>
                @endif
                <li>Email support</li>
                <li>Risk Management</li>
                <li>Help center access</li>
              </ul>
              @if ($plan->id == 1)

              <a href="{{ route('requestdomain.create', Crypt::encrypt(['plan_id' => $plan->id])) }}"
                data-id="{{ $plan->id }}"
                data-amount="{{ $plan->price }}">
                <button type="button" class="w-100 btn btn-lg btn-outline-primary">Sign up for {{ __('Free') }}</button></i></a>
              @elseif ($plan->id != 1)
              <a href="{{ route('requestdomain.create', Crypt::encrypt(['plan_id' => $plan->id])) }}"
                data-id="{{ $plan->id }}"
                data-amount="{{ $plan->price }}">
                <button type="button" class="w-100 btn btn-lg btn-outline-primary">{{ __('Subscribe') }}</button></i></a>
              @endif
            </div>
          </div>
        </div>

        @endif
        @endforeach

      </div>

    </main>
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
                            {{ __(" RiskCurb Faq Content Goes Here.") }}
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
    @include('layouts.front_footer')
  </div>
</body>
<script src="{{ asset('assets/js/jquery.min.js') }}"></script>
<script src="{{ asset('assets/js/vendor-all.js') }}"></script>
<script src="{{ asset('assets/js/plugins/bootstrap.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/feather.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/notifier.js') }}"></script>
<script src="{{ asset('assets/js/plugins/bouncer.min.js') }}"></script>
<script src="{{ asset('assets/js/pages/form-validation.js') }}"></script>
</html>
