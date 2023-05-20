<head>
    <title>@yield('title') | {{ Utility::getsettings('app_name') }}</title>

    @php
        $primary_color = \App\Facades\UtilityFacades::getsettings('color');
        if (isset($primary_color)) {
            $color = $primary_color;
        } else {
            $color = 'theme-1';
        }
    @endphp
    <!-- Favicon icon -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="icon" href="{{ Storage::exists('logo/app-favicon-logo.png') ? Utility::getpath('logo/app-favicon-logo.png') : Storage::url('logo/app-favicon-logo.png') }}" type="image/png">

    <link rel="stylesheet" href="{{ asset('assets/css/plugins/animate.min.css') }}" />
    <!-- font css -->
    <link rel="stylesheet" href="{{ asset('assets/fonts/tabler-icons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/fonts/feather.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/fonts/fontawesome.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/fonts/material.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/notifier.css') }}">




    @if (Utility::getsettings('dark_mode') == 'on')
        <link rel="stylesheet" href="{{ asset('assets/css/style-dark.css') }}">
    @else
        <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" id="main-style-link">
    @endif
    <!-- vendor css -->
    <link rel="stylesheet" href="{{ asset('assets/css/customizer.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/css/landing.css') }}" />



</head>

<body class="{{ $color }}">
    <!-- [ Nav ] start -->
    <nav class="navbar navbar-expand-md navbar-dark default top-nav-collapse">
        <div class="container">
            <a class="navbar-brand bg-transparent" href="{{ route('landingpage') }}">
                <img style="width:100px;" src="{{ Storage::exists('logo/app-logo.png') ? Utility::getpath('logo/app-logo.png') : Storage::url('logo/app-logo.png') }}"
                    class="app-logo img_setting">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarTogglerDemo01" aria-controls="navbarTogglerDemo01" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarTogglerDemo01">
                <ul class="navbar-nav align-items-center ms-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('landingpage') }}">{{ __('Home') }}</a>
                    </li>
                    @if (tenant('id') == null)
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('/') }}/#plans">{{ __('Plans') }}</a>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('view.post') }}">{{ __('Blogs') }}</a>
                        </li>
                    @endif
                    <li class="nav-item">
                        @if (\Auth::user())
                            <a class="nav-link" href="{{ url('/home') }}">{{ __('Dashboard') }}</a>
                        @else
                            <a class="nav-link" href="{{ url('/home') }}">{{ __('Login') }}</a>
                        @endif
                    </li>
                </ul>
            </div>
        </div>
    </nav>


    <!-- [ Nav ] start -->
    <!-- [ Header ] start -->
