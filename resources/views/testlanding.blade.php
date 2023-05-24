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
            <a class="menu-link" href="/">Home</a>
            <a class="menu-link" href="/#pricing">Pricing</a>
            @if (\Auth::user())
                <a class="menu-link" href="/home"><span class="app-btn btn-outline">Account </span></a>
            @else
                <a class="menu-link" href="/login"><span class="app-btn btn-solid">Start Today </span></a>
            @endif
        </div>

    </section>
</header>

<section class="risk-hero">
    <div class="risk-hero-top">
        <span class="hero-text"> This is </span>
        <img src="{{ asset('images/arrow.png') }}" alt="icon" class="arrow-img-top" />

    </div>
    <div class="risk-hero-middle hero-pink-title">
        Risk Management Software
    </div>
    <div class="risk-hero-bottom">
        <span class="hero-text"> at it's best </span>
        <a href="/login"><span class="app-btn btn-solid" style="display: block;">
                Get Started - It's Free
            </span></a>

        <img src="{{ asset('images/arrow.png') }}" alt="icon" class="arrow-img-bottom" />
    </div>

</section>


<div id="brxe-mjrhwz">
    <h5 class="brxe-text-basic">RiskCurb will take your ERM from 0 to 100.</h5>
    <h4 class="brxe-text-basic">Guaranteed.</h4>
</div>
</body>

</html>
