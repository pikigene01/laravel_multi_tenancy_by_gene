<!DOCTYPE html>
<html lang="en">

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

    <!-- Meta -->
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <!-- Favicon icon -->
    <link rel="icon"
        href="{{ Storage::exists('logo/app-favicon-logo.png') ? Utility::getpath('logo/app-favicon-logo.png') : Storage::url('logo/app-favicon-logo.png') }}"
        type="image/png">
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
    @vite('resources/js/app.js')
    @inertiaHead
</head>

<body class="{{ $color }}">
    <!-- [ auth-signup ] start -->
    @inertia

    {{-- <div class="auth-wrapper auth-v3">
        <div class="bg-auth-side bg-primary"></div>
        <div class="auth-content">
            <nav class="navbar navbar-expand-md navbar-light default">
                <div class="container-fluid pe-2">
                    <a class="navbar-brand" href="#">
                        @if (Utility::getsettings('dark_mode') == 'on')
                            <img src="{{ Storage::exists('logo/app-logo.png') ? Utility::getpath('logo/app-logo.png') : Storage::url('logo/app-logo.png') }}"
                                class="cust-logo img_setting" />
                        @else
                            <img src="{{ Storage::exists('logo/app-dark-logo.png') ? Utility::getpath('logo/app-dark-logo.png') : Storage::url('logo/app-dark-logo.png') }}"
                                class="cust-logo img_setting" />
                        @endif
                    </a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                        data-bs-target="#navbarTogglerDemo01" aria-controls="navbarTogglerDemo01" aria-expanded="false"
                        aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarTogglerDemo01">
                         <ul class="navbar-nav align-items-center ms-auto mb-2 mb-lg-0">
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('privacypolicy') }}">{{ __('Privacy Policy') }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('contactus') }}">{{ __('Contact Us') }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('faq') }}">{{ __('FAQs') }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link"
                                    href="{{ route('termsandconditions') }}">{{ __('Terms And Conditions') }}</a>
                            </li>
                            @yield('auth-topbar')
                        </ul>
                    </div>
                </div>
            </nav>
            <div class="card">
                <div class="row align-items-center text-start">
                    <div class="col-xl-6">
                        @yield('content')
                    </div>
                    <div class="col-xl-6 img-card-side">
                        <div class="auth-img-content">
                            <img src="{{ asset('assets/images/auth/img-auth-3.svg') }}" alt=""
                                class="img-fluid" />
                            <h3 class="text-white mb-4 mt-5">
                                {{ __('“Attention is the new currency”') }}
                            </h3>
                            <p class="text-white">
                                {{ __('The more effortless the writing looks, the more effort the writer actually put into the process.') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="auth-footer">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-6">
                            <p>
                                © {{ date('Y') }}, {{ config('app.name') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}

@include('layouts.front_footer')

</body>

<!-- [ auth-signup ] end -->

<!-- Required Js -->
<script src="{{ asset('assets/js/jquery.min.js') }}"></script>
<script src="{{ asset('assets/js/vendor-all.js') }}"></script>
<script src="{{ asset('assets/js/plugins/bootstrap.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/feather.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/notifier.js') }}"></script>
<script src="{{ asset('assets/js/plugins/bouncer.min.js') }}"></script>
<script src="{{ asset('assets/js/pages/form-validation.js') }}"></script>
<script>
    @if (session('failed'))
        notifier.show('Failed!', '{{ session('failed') }}', 'danger',
            '{{ asset('assets/images/notification/high_priority-48.png') }}', 4000);
    @endif
    @if ($errors = session('errors'))
        @if (is_object($errors))
            @foreach ($errors->all() as $error)
                notifier.show('Error!', '{{ $error }}', 'danger',
                    '{{ asset('assets/images/notification/high_priority-48.png') }}', 4000);
            @endforeach
        @else
            notifier.show('Error!', '{{ session('errors') }}', 'danger',
                '{{ asset('assets/images/notification/high_priority-48.png') }}', 4000);
        @endif
    @endif
    @if (session('successful'))
        notifier.show('Successfully!', '{{ session('successful') }}', 'success',
            '{{ asset('assets/images/notification/ok-48.png') }}', 4000);
    @endif
    @if (session('success'))
        notifier.show('Success!', '{{ session('success') }}', 'success',
            '{{ asset('assets/images/notification/ok-48.png') }}', 4000);
    @endif
    @if (session('warning'))
        notifier.show('Warning!', '{{ session('warning') }}', 'warning',
            '{{ asset('assets/images/notification/medium_priority-48.png') }}', 4000);
    @endif

    @if (session('status'))
        notifier.show('Great!', '{{ session('status') }}', 'info',
            '{{ asset('assets/images/notification/survey-48.png') }}', 4000);
    @endif
</script>

@stack('javascript')
<script>
    feather.replace();
</script>


</html>
