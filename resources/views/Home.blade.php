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
    @livewireStyles
</head>

<body class="{{ $color }}">
@livewire('home-page');
@livewireScripts

</body>
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
