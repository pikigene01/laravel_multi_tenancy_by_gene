@php
    $lang = \App\Facades\UtilityFacades::getValByName('default_language');
    // dd($lang);
    $primary_color = \App\Facades\UtilityFacades::getsettings('color');
    if (isset($primary_color)) {
        $color = $primary_color;
    } else {
        $color = 'theme-4';
    }
@endphp
@extends('layouts.main')
@section('title', __('Settings'))
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item">{{ __('Settings') }}</li>
@endsection
@section('content')
    <div class="row">
        <!-- [ sample-page ] start -->
        <div class="col-sm-12">
            <div class="row">
                <div class="col-xl-3">
                    <div class="card sticky-top" style="top:30px">
                        <div class="list-group list-group-flush" id="useradd-sidenav">
                            <a href="#app_setting" class="list-group-item list-group-item-action ">{{ __('App Setting') }}
                                <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                            </a>
                            <a href="#general_setting"
                                class="list-group-item list-group-item-action">{{ __('General Setting') }}
                                <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                            </a>
                            <a href="#storage_setting"
                                class="list-group-item list-group-item-action">{{ __('Storage Setting') }}
                                <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                            </a>
                            <a href="#email_setting"
                                class="list-group-item list-group-item-action">{{ __('Email Setting') }}
                                <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                            </a>
                            @if (\Auth::user()->type == 'Super Admin' || \Auth::user()->type == 'Admin')
                                <a href="#payment_setting"
                                    class="list-group-item list-group-item-action">{{ __('Payment Setting') }}<div
                                        class="float-end"><i class="ti ti-chevron-right"></i></div></a>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-xl-9">
                    <div id="app_setting" class="card">
                        <div class="card-header">
                            <h5>{{ __('App Setting') }}</h5>
                        </div>
                        {!! Form::open([
                            'route' => 'settings/app_name/update',
                            'method' => 'Post',
                            'id' => 'setting-form',
                            'enctype' => 'multipart/form-data',
                            'data-validate',
                        ]) !!}
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="card">
                                        <div class="card-header">
                                            <h5>{{ __('Dark Logo') }}</h5>
                                        </div>
                                        <div class="card-body setting-card setting-logo-box p-3">
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="logo-content logo-set-bg  text-center py-2">
                                                        @if (Storage::exists('logo/app-dark-logo.png'))
                                                            <a href="{{ Utility::getpath('logo/app-dark-logo.png') }}"
                                                                target="_blank">
                                                                <img src="{{ Utility::getpath('logo/app-dark-logo.png') }}"
                                                                    class="big-logo img_setting" id="app-dark-logo">
                                                            </a>
                                                        @else
                                                            <a href="{{ Storage::url('logo/app-dark-logo.png') }}"
                                                                target="_blank">
                                                                <img src="{{ Storage::url('logo/app-dark-logo.png') }}"
                                                                    class="big-logo img_setting" id="app-dark-logo">
                                                            </a>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="choose-files mt-4">
                                                        <label for="logo" class="form-label d-block">
                                                            <div class="bg-primary m-auto">
                                                                <i
                                                                    class="ti ti-upload px-1"></i>{{ __('Choose File Here') }}
                                                                {{ Form::file('app_dark_logo', ['class' => 'form-control file', 'value' => 'Select Dark Logo']) }}
                                                            </div>
                                                        </label>
                                                        <p class="edit-logo"></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="card">
                                        <div class="card-header">
                                            <h5>{{ __('Light Logo') }}</h5>
                                        </div>
                                        <div class="card-body setting-card setting-logo-box p-3">
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="logo-content  logo-set-bg text-center py-2">
                                                        @if (Storage::exists('logo/app-logo.png'))
                                                            <a href="{{ Utility::getpath('logo/app-logo.png') }}"
                                                                target="_blank">
                                                                <img src="{{ Utility::getpath('logo/app-logo.png') }}"
                                                                    class="big-logo img_setting" id="app-logo"></a>
                                                        @else
                                                            <a href="{{ Storage::url('logo/app-logo.png') }}"
                                                                target="_blank">
                                                                <img src="{{ Storage::url('logo/app-logo.png') }}"
                                                                    class="big-logo img_setting" id="app-dark-logo">
                                                            </a>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="choose-files mt-4">
                                                        <label for="white_logo" class="form-label d-block">
                                                            <div class=" bg-primary m-auto">
                                                                <i
                                                                    class="ti ti-upload px-1"></i>{{ __('Choose File Here') }}
                                                                {{ Form::file('app_logo', ['class' => 'form-control file', 'value' => 'Select Logo']) }}
                                                            </div>

                                                        </label>
                                                        <p class="edit-white_logo"></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="card">
                                        <div class="card-header">
                                            <h5>{{ __('Favicon Logo') }}</h5>
                                        </div>
                                        <div class="card-body setting-card setting-logo-box p-3">
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="logo-content logo-set-bg text-center py-2">
                                                        @if (Storage::exists('logo/app-favicon-logo.png'))
                                                            <a href="{{ Utility::getpath('logo/app-favicon-logo.png') }}"
                                                                target="_blank">
                                                                <img src="{{ Utility::getpath('logo/app-favicon-logo.png') }}"
                                                                    class="small-logo img_setting" id="app-dark-logo"
                                                                    style="width:15%;">
                                                            </a>
                                                        @else
                                                            <a href="{{ Storage::url('logo/app-favicon-logo.png') }}"
                                                                target="_blank">
                                                                <img src="{{ Storage::url('logo/app-favicon-logo.png') }}"
                                                                    class="big-logo img_setting" id="app-dark-logo">
                                                            </a>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="choose-files mt-4">
                                                        <label for="favicon" class="form-label d-block">
                                                            <div class=" bg-primary m-auto">
                                                                <i
                                                                    class="ti ti-upload px-1"></i>{{ __('Choose File Here') }}
                                                                {{ Form::file('favicon_logo', ['class' => 'form-control file', 'value' => 'Select Favicon Logo']) }}
                                                            </div>

                                                        </label>
                                                        <p class="edit-favicon"></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        {{ Form::label('app_name', __('Application Name'), ['class' => 'form-label']) }}
                                        {!! Form::text('app_name', Utility::getsettings('app_name'), [
                                            'class' => 'form-control',
                                            'placeholder' => __('Enter application name'),
                                        ]) !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="text-end">
                                {{ Form::button(__('Save'), ['type' => 'submit', 'id' => 'save-btn', 'class' => 'btn btn-primary ']) }}
                            </div>
                        </div>
                        {!! Form::close() !!}
                    </div>
                    <div id="general_setting" class="card">
                        <div class="card-header">
                            <h5>{{ __('General Setting') }}</h5>
                        </div>
                        {!! Form::open([
                            'route' => 'settings/auth_settings/update',
                            'method' => 'Post',
                            'id' => 'setting-form',
                            'enctype' => 'multipart/form-data',
                            'data-validate',
                        ]) !!}
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-8">
                                                <strong class="d-block">{{ __('Two Factor Authentication') }}</strong>
                                                {{ !Utility::getsettings('2fa') ? 'Activate' : 'Deactivate' }}
                                                {{ __('Two Factor Authentication') }}
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-switch mt-2 float-end custom-switch-v1">
                                                    {!! Form::checkbox('two_factor_auth', null, Utility::getsettings('2fa') ? true : false, [
                                                        'class' => 'form-check-input input-primary',
                                                    ]) !!}
                                                </label>
                                            </div>
                                            @if (!extension_loaded('imagick'))
                                                <small>
                                                    {{ __('Note: for 2FA your server must have Imagick.') }} <a
                                                        href="https://www.php.net/manual/en/book.imagick.php"
                                                        target="_new">{{ __('Imagick Document') }}</a>
                                                </small>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-8">
                                                <strong class="d-block">{{ __('RTL Setting') }}</strong>
                                                {{ Utility::getsettings('rtl') == '0' ? __('Activate') : __('Deactivate') }}
                                                {{ __('Rtl Setting For Application.') }}
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-switch mt-2 float-end custom-switch-v1">
                                                    {!! Form::checkbox('rtl_setting', null, Utility::getsettings('rtl') == '1' ? true : false, [
                                                        'class' => 'form-check-input input-primary',
                                                    ]) !!}
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-8">
                                                <strong class="d-block">{{ __('Database Permission') }}</strong>
                                                {{ Utility::getsettings('database_permission') == '0' ? __('Activate') : __('Deactivate') }}
                                                {{ __('To Automatically Generate a User Database.') }}
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-switch mt-2 float-end custom-switch-v1">
                                                    {!! Form::checkbox(
                                                        'database_permission',
                                                        null,
                                                        Utility::getsettings('database_permission') == '1' ? true : false,
                                                        [
                                                            'class' => 'form-check-input input-primary',
                                                        ],
                                                    ) !!}
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-8">
                                                <strong class="d-block">{{ __('Dark Layout') }}</strong>
                                                {{ Utility::getsettings('dark_mode') == 'on' ? __('Deactivate') : __('Activate') }}
                                                {{ __('Dark Layout For Application.') }}
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-switch mt-2 float-end custom-switch-v1">
                                                    {!! Form::checkbox('dark_mode', null, Utility::getsettings('dark_mode') == 'on' ? true : false, [
                                                        'data-onstyle' => 'primary',
                                                        'id' => 'cust-darklayout',
                                                        'class' => 'form-check-input input-primary',
                                                    ]) !!}
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-8">
                                                <strong class="d-block">{{ __('Landing Page Setting') }}</strong>
                                                {{ Utility::getsettings('landing_page_status') == '1' ? __('Deactivate') : __('Activate') }}
                                                {{ __('Landing Page For Application.') }}
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-switch mt-2 float-end custom-switch-v1">
                                                    {!! Form::checkbox(
                                                        'landing_page_status',
                                                        null,
                                                        Utility::getsettings('landing_page_status') == '1' ? true : false,
                                                        [
                                                            'data-onstyle' => 'primary',
                                                            'id' => 'cust-darklayout',
                                                            'class' => 'form-check-input input-primary',
                                                        ],
                                                    ) !!}
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="align-items-center row">
                                            <div class="col-md-8">
                                                <strong class="d-block">{{ __('Primary Color Settings') }}</strong>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="theme-color themes-color float-end">
                                                    <a href="#!"
                                                        class="{{ $color == 'theme-1' ? 'active_color' : '' }}"
                                                        data-value="theme-1" onclick="check_theme('theme-1')"></a>
                                                    {!! Form::radio('color', 'theme-1', null, ['class' => 'theme_color', 'style' => 'display: none;']) !!}
                                                    <a href="#!"
                                                        class="{{ $color == 'theme-2' ? 'active_color' : '' }}"
                                                        data-value="theme-2" onclick="check_theme('theme-2')"></a>
                                                    {!! Form::radio('color', 'theme-2', null, ['class' => 'theme_color', 'style' => 'display: none;']) !!}
                                                    <a href="#!"
                                                        class="{{ $color == 'theme-3' ? 'active_color' : '' }}"
                                                        data-value="theme-3" onclick="check_theme('theme-3')"></a>
                                                    {!! Form::radio('color', 'theme-3', null, ['class' => 'theme_color', 'style' => 'display: none;']) !!}
                                                    <a href="#!"
                                                        class="{{ $color == 'theme-4' ? 'active_color' : '' }}"
                                                        data-value="theme-4" onclick="check_theme('theme-4')"></a>
                                                    {!! Form::radio('color', 'theme-4', null, ['class' => 'theme_color', 'style' => 'display: none;']) !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        {{ Form::label('name', __('Default Language'), ['class' => 'form-label']) }}
                                        <select class="form-control" data-trigger name="default_language"
                                            id="choices-single-default"
                                            placeholder="{{ __('This is a search placeholder') }}">
                                            @foreach (\App\Facades\UtilityFacades::languages() as $language)
                                                <option @if ($lang == $language) selected @endif
                                                    value="{{ $language }}">
                                                    {{ Str::upper($language) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group ">
                                        {{ Form::label('date_format', __('Date Format'), ['class' => 'form-label']) }}
                                        <select name="date_format" class="form-control" data-trigger>
                                            <option value="M j, Y"
                                                {{ Utility::getsettings('date_format') == 'M j, Y' ? 'selected' : '' }}>
                                                {{ __('Jan 1, 2020') }}</option>
                                            <option value="d-M-y"
                                                {{ Utility::getsettings('date_format') == 'd-M-y' ? 'selected' : '' }}>
                                                {{ __('01-Jan-20') }}</option>
                                        </select>
                                    </div>
                                    <div class="form-group ">
                                        {{ Form::label('time_format', __('Time Format'), ['class' => 'form-label']) }}
                                        <select name="time_format" class="form-control" data-trigger>
                                            <option value="g:i A"
                                                {{ Utility::getsettings('time_format') == 'g:i A' ? 'selected' : '' }}>
                                                {{ __('hh:mm AM/PM') }}</option>
                                            <option value="H:i:s"
                                                {{ Utility::getsettings('time_format') == 'H:i:s' ? 'selected' : '' }}>
                                                {{ __('HH:mm:ss') }}</option>
                                        </select>
                                    </div>
                                    @if (\Auth::user()->type == 'Super Admin')
                                        <div class="form-group ">
                                            {{ Form::label('gtag', __('Gtag Tracking ID'), ['class' => 'form-label']) }}
                                            {!! Html::link(
                                                'https://support.google.com/analytics/answer/1008080?hl=en#zippy=%2Cin-this-article',
                                                __('Document'),
                                                ['target' => '_blank', 'class' => 'm-2'],
                                            ) !!}
                                            {!! Form::text('gtag', Utility::getsettings('gtag'), [
                                                'class' => 'form-control',
                                                'placeholder' => __('Enter gtag tracking id'),
                                            ]) !!}
                                        </div>
                                    @endif
                                    <div class="form-group ">
                                        {{ Form::label('name', __('Currency Name'), ['class' => 'form-label']) }}
                                        {!! Form::text('currency', Utility::getsettings('currency'), [
                                            'class' => 'form-control',
                                            'placeholder' => __('Enter Currency name'),
                                            'required',
                                        ]) !!}

                                        <p>{{ __('The name of currency is to be taken frome this document.') }} <a
                                                href="https://stripe.com/docs/currencies" class="m-2"
                                                target="_blank">{{ __('Document') }}</a> </p>
                                    </div>
                                    <div class="form-group ">
                                        {{ Form::label('currency_symbol', __('Currency Symbol'), ['class' => 'form-label']) }}
                                        {!! Form::text('currency_symbol', Utility::getsettings('currency_symbol'), [
                                            'class' => 'form-control',
                                            'placeholder' => __('Enter currency symbol'),
                                            'required',
                                        ]) !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="text-end">
                                {{ Form::button(__('Save'), ['type' => 'submit', 'id' => 'save-btn', 'class' => 'btn btn-primary']) }}
                            </div>
                        </div>
                        {!! Form::close() !!}
                    </div>
                    <div id="storage_setting" class="card">
                        <div class="card-header">
                            <h5>{{ __('Storage Setting') }}</h5>
                            <small class="text-muted">{{ __('Aws,S3 Storage Configuration') }}</small>
                        </div>

                        {!! Form::open([
                            'route' => 'settings/s3_setting/update',
                            'method' => 'Post',
                            'id' => 'setting-form',
                            'enctype' => 'multipart/form-data',
                            'data-validate',
                        ]) !!}
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        {{ Form::label('locals', __('Local'), ['class' => 'form-label']) }}
                                        <label class="form-switch   custom-switch-v1 col-3 mt-2 ms-2">
                                            <input type="radio" name="settingtype" value="local" id="locals"
                                                class="form-check-input input-primary"
                                                {{ Utility::getsettings('settingtype') == 'local' ? 'checked' : 'unchecked' }}>
                                        </label>
                                        {{ Form::label('s3s', __('S3 setting'), ['class' => 'form-label']) }}
                                        <label class="form-switch   custom-switch-v1 col-3 mt-2 ms-2">
                                            <input type="radio" name="settingtype" value="s3" id="s3s"
                                                class="form-check-input input-primary"
                                                {{ Utility::getsettings('settingtype') == 's3' ? 'checked' : 'unchecked' }}>
                                        </label>
                                    </div>
                                </div>
                                <div id="s3"
                                    class="desc {{ Utility::getsettings('settingtype') == 's3' ? 'block' : 'd-none' }}">
                                    <div class=" row">
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                {{ Form::label('s3_key', __('S3 Key'), ['class' => 'form-label']) }}
                                                {!! Form::text('s3_key', Utility::getsettings('s3_key'), [
                                                    'placeholder' => __('Enter s3 key'),
                                                    'class' => 'form-control',
                                                ]) !!}
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                {{ Form::label('s3_secret', __('S3 Secret'), ['class' => 'form-label']) }}
                                                {!! Form::text('s3_secret', Utility::getsettings('s3_secret'), [
                                                    'placeholder' => __('Enter s3 secret'),
                                                    'class' => 'form-control',
                                                ]) !!}
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                {{ Form::label('s3_region', __('S3 Region'), ['class' => 'form-label']) }}
                                                {!! Form::text('s3_region', Utility::getsettings('s3_region'), [
                                                    'placeholder' => __('Enter s3 region'),
                                                    'class' => 'form-control',
                                                ]) !!}
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                {{ Form::label('s3_bucket', __('S3 Bucket'), ['class' => 'form-label']) }}
                                                {!! Form::text('s3_bucket', Utility::getsettings('s3_bucket'), [
                                                    'placeholder' => __('Enter s3 bucket'),
                                                    'class' => 'form-control',
                                                ]) !!}
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                {{ Form::label('s3_url', __('S3 URL'), ['class' => 'form-label']) }}
                                                {!! Form::text('s3_url', Utility::getsettings('s3_url'), [
                                                    'placeholder' => __('Enter s3 url'),
                                                    'class' => 'form-control',
                                                ]) !!}
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                {{ Form::label('s3_endpoint', __('S3 Endpoint'), ['class' => 'form-label']) }}
                                                {!! Form::text('s3_endpoint', Utility::getsettings('s3_endpoint'), [
                                                    'placeholder' => __('Enter s3 endpoint'),
                                                    'class' => 'form-control',
                                                ]) !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="text-end">
                                {{ Form::button(__('Save'), ['type' => 'submit', 'id' => 'save-btn', 'class' => 'btn btn-primary']) }}
                            </div>
                        </div>
                        {!! Form::close() !!}
                    </div>
                    <div id="email_setting" class="card">
                        <div class="card-header">
                            <h5>{{ __('Email Setting') }}</h5>
                            <small class="text-muted">{{ __('Email Smtp Settings, Notifications And Others Related To Email.') }}</small>
                        </div>
                        {!! Form::open([
                            'route' => 'settings/email_setting/update',
                            'method' => 'Post',
                            'id' => 'setting-form',
                            'enctype' => 'multipart/form-data',
                            'data-validate',
                        ]) !!}
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        {{ Form::label('mail_mailer', __('Mail Mailer'), ['class' => 'form-label']) }}
                                        {!! Form::text('mail_mailer', Utility::getsettings('mail_mailer'), [
                                            'class' => 'form-control',
                                            'placeholder' => __('Enter mail mailer'),
                                        ]) !!}
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        {{ Form::label('mail_host', __('Mail Host'), ['class' => 'form-label']) }}
                                        {!! Form::text('mail_host', Utility::getsettings('mail_host'), [
                                            'class' => 'form-control',
                                            'placeholder' => __('Enter mail host'),
                                        ]) !!}
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        {{ Form::label('mail_port', __('Mail Port'), ['class' => 'form-label']) }}
                                        {!! Form::text('mail_port', Utility::getsettings('mail_port'), [
                                            'class' => 'form-control',
                                            'placeholder' => __('Enter mail port'),
                                        ]) !!}
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        {{ Form::label('mail_username', __('Mail Username'), ['class' => 'form-label']) }}
                                        {!! Form::text('mail_username', Utility::getsettings('mail_username'), [
                                            'class' => 'form-control',
                                            'placeholder' => __('Enter mail username'),
                                        ]) !!}
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        {{ Form::label('mail_password', __('Mail Password'), ['class' => 'form-label']) }}
                                        <input class="form-control" value="{{ Utility::getsettings('mail_password') }}"
                                            placeholder="{{ __('Enter mail password') }}" name="mail_password"
                                            type="password" id="mail_password">
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        {{ Form::label('mail_encryption', __('Mail Encryption'), ['class' => 'form-label']) }}
                                        {!! Form::text('mail_encryption', Utility::getsettings('mail_encryption'), [
                                            'class' => 'form-control',
                                            'placeholder' => __('Enter mail encryption'),
                                        ]) !!}
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        {{ Form::label('mail_from_address', __('Mail From Address'), ['class' => 'form-label']) }}
                                        {!! Form::text('mail_from_address', Utility::getsettings('mail_from_address'), [
                                            'class' => 'form-control',
                                            'placeholder' => __('Enter mail from address'),
                                        ]) !!}
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        {{ Form::label('mail_from_name', __('Mail From Name'), ['class' => 'form-label']) }}
                                        {!! Form::text('mail_from_name', Utility::getsettings('mail_from_name'), [
                                            'class' => 'form-control',
                                            'placeholder' => __('Enter mail from name'),
                                        ]) !!}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer">
                            <div class="text-end">
                                <a class="btn btn-info send_mail" href="javascript:void(0);" id="test-mail"
                                    data-url="{{ route('test.mail') }}">
                                    {{ __('Send Test Mail') }}</a>
                                {{ Form::button(__('Save'), ['type' => 'submit', 'id' => 'save-btn', 'class' => 'btn btn-primary']) }}
                            </div>
                        </div>
                        {!! Form::close() !!}
                    </div>

                    @if (\Auth::user()->type == 'Super Admin' || \Auth::user()->type == 'Admin')
                        <div id="payment_setting" class="card">
                            <div class="card-header">
                                <h5>{{ __('Payment Settings') }}</h5>
                            </div>
                            {!! Form::open([
                                'route' => 'settings/payment_setting/update',
                                'method' => 'Post',
                                'id' => 'setting-form',
                                'enctype' => 'multipart/form-data',
                                'data-validate',
                            ]) !!}
                            <div class="card-body">
                                <div class="faq justify-content-center">
                                    <div class="col-sm-12 col-md-10 col-xxl-12">
                                        <div class="accordion accordion-flush" id="accordionExample">
                                            <!-- Strip -->
                                            <div class="accordion-item card">
                                                <h2 class="accordion-header" id="heading-2-2">
                                                    <button class="accordion-button collapsed" type="button"
                                                        data-bs-toggle="collapse" data-bs-target="#collapse1"
                                                        aria-expanded="true" aria-controls="collapse1">
                                                        <span class="d-flex align-items-center flex-1">
                                                            <i class="ti ti-credit-card text-primary"></i>
                                                            {{ __('Stripe') }}
                                                        </span>
                                                        @if (Utility::getsettings('stripesetting') == 'on')
                                                            <a
                                                                class="btn btn-sm btn-success float-end me-3 text-white">{{ __('Active') }}</a>
                                                        @endif
                                                    </button>
                                                </h2>
                                                <div id="collapse1" class="accordion-collapse collapse"
                                                    aria-labelledby="heading-2-2" data-bs-parent="#accordionExample">
                                                    <div class="accordion-body">
                                                        <div class="row">
                                                            <div class="col-6 py-2">

                                                            </div>
                                                            <div class="col-6 py-2 text-end">
                                                                <div class="form-check form-switch d-inline-block">
                                                                    {!! Form::checkbox('paymentsetting[]', 'stripe', Utility::getsettings('stripesetting') == 'on' ? true : false, [
                                                                        'class' => 'form-check-input mx-2',
                                                                        'id' => 'is_stripe_enabled',
                                                                    ]) !!}
                                                                    {{ Form::label('is_stripe_enabled', __('Enable'), ['class' => 'form-check-label']) }}
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    {{ Form::label('stripe_key', __('Stripe Key'), ['class' => 'col-form-label']) }}
                                                                    {{ Form::text('stripe_key', Utility::getsettings('stripe_key'), ['class' => 'form-control', 'placeholder' => __('Enter stripe key')]) }}

                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    {{ Form::label('stripe_secret', __('Stripe Secret'), ['class' => 'col-form-label']) }}
                                                                    {{ Form::text('stripe_secret', Utility::getsettings('stripe_secret'), ['class' => 'form-control ', 'placeholder' => __('Enter stripe secret')]) }}

                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    {{ Form::label('Description', __('Description'), ['class' => 'col-form-label']) }}
                                                                    {{ Form::text('stripe_description', Utility::getsettings('stripe_description'), ['class' => 'form-control ', 'placeholder' => __('Enter description')]) }}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Razorpay -->
                                            <div class="accordion-item card">
                                                <h2 class="accordion-header" id="heading-2-3">
                                                    <button class="accordion-button collapsed" type="button"
                                                        data-bs-toggle="collapse" data-bs-target="#collapse2"
                                                        aria-expanded="true" aria-controls="collapse2">
                                                        <span class="d-flex align-items-center flex-1">
                                                            <i class="ti ti-credit-card text-primary"></i>
                                                            {{ __('Razorpay') }}
                                                        </span>
                                                        @if (Utility::getsettings('razorpaysetting') == 'on')
                                                            <a
                                                                class="btn btn-sm btn-success float-end me-3 text-white">{{ __('Active') }}</a>
                                                        @endif
                                                    </button>
                                                </h2>
                                                <div id="collapse2" class="accordion-collapse collapse"
                                                    aria-labelledby="heading-2-3" data-bs-parent="#accordionExample">
                                                    <div class="accordion-body">
                                                        <div class="row">
                                                            <div class="col-6 py-2">
                                                            </div>
                                                            <div class="col-6 py-2 text-end">
                                                                <div class="form-check form-switch d-inline-block">
                                                                    {!! Form::checkbox(
                                                                        'paymentsetting[]',
                                                                        'razorpay',
                                                                        Utility::getsettings('razorpaysetting') == 'on' ? true : false,
                                                                        [
                                                                            'class' => 'form-check-input mx-2',
                                                                            'id' => 'is_razorpay_enabled',
                                                                        ],
                                                                    ) !!}
                                                                    {{ Form::label('is_razorpay_enabled', __('Enable'), ['class' => 'form-check-label']) }}
                                                                </div>
                                                            </div>

                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    {{ Form::label('razorpay_key', __('Razorpay Key'), ['class' => 'col-form-label']) }}
                                                                    {!! Form::text('razorpay_key', Utility::getsettings('razorpay_key'), [
                                                                        'class' => 'form-control',
                                                                        'placeholder' => __('Enter razorpay key'),
                                                                    ]) !!}
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    {{ Form::label('razorpay_secret', __('Razorpay Secret'), ['class' => 'col-form-label']) }}
                                                                    {!! Form::text('razorpay_secret', Utility::getsettings('razorpay_secret'), [
                                                                        'class' => 'form-control',
                                                                        'placeholder' => __('Enter razorpay secret'),
                                                                    ]) !!}
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    {{ Form::label('Description', __('Description'), ['class' => 'col-form-label']) }}
                                                                    {{ Form::text('razorpay_description', Utility::getsettings('razorpay_description'), ['class' => 'form-control ', 'placeholder' => __('Enter description')]) }}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Paypal -->
                                            <div class="accordion-item card">
                                                <h2 class="accordion-header" id="heading-2-4">
                                                    <button class="accordion-button collapsed" type="button"
                                                        data-bs-toggle="collapse" data-bs-target="#collapse3"
                                                        aria-expanded="true" aria-controls="collapse3">
                                                        <span class="d-flex align-items-center flex-1">
                                                            <i class="ti ti-credit-card text-primary"></i>
                                                            {{ __('Paypal') }}
                                                        </span>
                                                        @if (Utility::getsettings('paypalsetting') == 'on')
                                                            <a
                                                                class="btn btn-sm btn-success float-end me-3 text-white">{{ __('Active') }}</a>
                                                        @endif
                                                    </button>
                                                </h2>
                                                <div id="collapse3" class="accordion-collapse collapse"
                                                    aria-labelledby="heading-2-4" data-bs-parent="#accordionExample">
                                                    <div class="accordion-body">
                                                        <div class="row">
                                                            <div class="col-6 py-2">
                                                            </div>
                                                            <div class="col-6 py-2 text-end">
                                                                <div class="form-check form-switch d-inline-block">
                                                                    {!! Form::checkbox('paymentsetting[]', 'paypal', Utility::getsettings('paypalsetting') == 'on' ? true : false, [
                                                                        'class' => 'form-check-input mx-2',
                                                                        'id' => 'is_paypal_enabled',
                                                                    ]) !!}
                                                                    {{ Form::label('is_paypal_enabled', __('Enable'), ['class' => 'form-check-label']) }}
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12">
                                                                <label class="paypal-label col-form-label"
                                                                    for="paypal_mode">{{ __('Paypal Mode') }}</label>
                                                                <br>
                                                                <div class="d-flex">
                                                                    <div class="mr-2" style="margin-right: 15px;">
                                                                        <div class="border card p-3">
                                                                            <div class="form-check">
                                                                                {!! Form::radio('paypal_mode', 'sandbox', env('PAYPAL_MODE') == 'sandbox' ? true : false, [
                                                                                    'class' => 'form-check-input',
                                                                                    'id' => 'Sandbox',
                                                                                ]) !!}
                                                                                {{ Form::label('Sandbox', __('Sandbox'), ['class' => 'form-check-label text-dark']) }}
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="mr-2">
                                                                        <div class="border card p-3">
                                                                            <div class="form-check">
                                                                                {!! Form::radio('paypal_mode', 'live', env('PAYPAL_MODE') == 'live' ? true : false, [
                                                                                    'class' => 'form-check-input',
                                                                                    'id' => 'Live',
                                                                                ]) !!}
                                                                                {{ Form::label('Live', __('Live'), ['class' => 'form-check-label text-dark']) }}
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    {{ Form::label('client_id', __('Paypal Key'), ['class' => 'col-form-label']) }}
                                                                    {!! Form::text(
                                                                        'client_id',
                                                                        env('PAYPAL_MODE') == 'sandbox' ? env('PAYPAL_SANDBOX_CLIENT_ID') : env('PAYPAL_LIVE_CLIENT_ID'),
                                                                        [
                                                                            'class' => 'form-control',
                                                                            'placeholder' => __('Enter paypal key'),
                                                                        ],
                                                                    ) !!}
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    {{ Form::label('client_secret', __('Paypal Secret'), ['class' => 'col-form-label']) }}
                                                                    {!! Form::text(
                                                                        'client_secret',
                                                                        env('PAYPAL_MODE') == 'sandbox' ? env('PAYPAL_SANDBOX_CLIENT_SECRET') : env('PAYPAL_LIVE_CLIENT_SECRET'),
                                                                        [
                                                                            'class' => 'form-control',
                                                                            'placeholder' => __('Enter paypal secret'),
                                                                        ],
                                                                    ) !!}
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    {{ Form::label('Description', __('Description'), ['class' => 'col-form-label']) }}
                                                                    {{ Form::text('paypal_description', Utility::getsettings('paypal_description'), ['class' => 'form-control ', 'placeholder' => __('Enter description')]) }}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- FLUTTERWAVE -->
                                            <div class="accordion-item card">
                                                <h2 class="accordion-header" id="heading-2-5">
                                                    <button class="accordion-button collapsed" type="button"
                                                        data-bs-toggle="collapse" data-bs-target="#collapse4"
                                                        aria-expanded="true" aria-controls="collapse4">
                                                        <span class="d-flex align-items-center flex-1">
                                                            <i class="ti ti-credit-card text-primary"></i>
                                                            {{ __('Flutterwave') }}
                                                        </span>
                                                        @if (Utility::getsettings('flutterwavesetting') == 'on')
                                                            <a
                                                                class="btn btn-sm btn-success float-end me-3 text-white">{{ __('Active') }}</a>
                                                        @endif
                                                    </button>
                                                </h2>
                                                <div id="collapse4" class="accordion-collapse collapse"
                                                    aria-labelledby="heading-2-5" data-bs-parent="#accordionExample">
                                                    <div class="accordion-body">
                                                        <div class="row">
                                                            <div class="col-6 py-2">
                                                            </div>
                                                            <div class="col-6 py-2 text-end">
                                                                <div class="form-check form-switch d-inline-block">
                                                                    {!! Form::checkbox(
                                                                        'paymentsetting[]',
                                                                        'flutterwave',
                                                                        Utility::getsettings('flutterwavesetting') == 'on' ? true : false,
                                                                        [
                                                                            'class' => 'form-check-input mx-2',
                                                                            'id' => 'is_flutterwave_enabled',
                                                                        ],
                                                                    ) !!}
                                                                    {{ Form::label('is_flutterwave_enabled', __('Enable'), ['class' => 'form-check-label']) }}
                                                                </div>
                                                            </div>

                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    {{ Form::label('flutterwave_key', __('Flutterwave Key'), ['class' => 'col-form-label']) }}
                                                                    {!! Form::text('flutterwave_key', Utility::getsettings('flutterwave_key'), [
                                                                        'class' => 'form-control',
                                                                        'placeholder' => __('Enter flutterwave key'),
                                                                    ]) !!}
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    {{ Form::label('flutterwave_secret', __('Flutterwave Secret'), ['class' => 'col-form-label']) }}
                                                                    {!! Form::text('flutterwave_secret', Utility::getsettings('flutterwave_secret'), [
                                                                        'class' => 'form-control',
                                                                        'placeholder' => __('Enter flutterwave secret'),
                                                                    ]) !!}
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    {{ Form::label('Description', __('Description'), ['class' => 'col-form-label']) }}
                                                                    {{ Form::text('flutterwave_description', Utility::getsettings('flutterwave_description'), ['class' => 'form-control ', 'placeholder' => __('Enter description')]) }}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- paystack -->
                                            <div class="accordion-item card">
                                                <h2 class="accordion-header" id="heading-2-6">
                                                    <button class="accordion-button collapsed" type="button"
                                                        data-bs-toggle="collapse" data-bs-target="#collapse5"
                                                        aria-expanded="true" aria-controls="collapse5">
                                                        <span class="d-flex align-items-center flex-1">
                                                            <i class="ti ti-credit-card text-primary"></i>
                                                            {{ __('Paystack') }}
                                                        </span>
                                                        @if (Utility::getsettings('paystacksetting') == 'on')
                                                            <a
                                                                class="btn btn-sm btn-success float-end me-3 text-white">{{ __('Active') }}</a>
                                                        @endif
                                                    </button>
                                                </h2>
                                                <div id="collapse5" class="accordion-collapse collapse"
                                                    aria-labelledby="heading-2-6" data-bs-parent="#accordionExample">
                                                    <div class="accordion-body">
                                                        <div class="row">
                                                            <div class="col-6 py-2">
                                                            </div>
                                                            <div class="col-6 py-2 text-end">
                                                                <div class="form-check form-switch d-inline-block">
                                                                    {!! Form::checkbox(
                                                                        'paymentsetting[]',
                                                                        'paystack',
                                                                        Utility::getsettings('paystacksetting') == 'on' ? true : false,
                                                                        [
                                                                            'class' => 'form-check-input mx-2',
                                                                            'id' => 'is_paystack_enabled',
                                                                        ],
                                                                    ) !!}
                                                                    {{ Form::label('is_paystack_enabled', __('Enable'), ['class' => 'form-check-label']) }}
                                                                </div>
                                                            </div>

                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    {{ Form::label('public', __('Public key'), ['class' => 'col-form-label']) }}
                                                                    {!! Form::text('public_key', Utility::getsettings('paystack_public_key'), [
                                                                        'class' => 'form-control',
                                                                        'placeholder' => __('Enter public key'),
                                                                    ]) !!}
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    {{ Form::label('secret', __('Secret key'), ['class' => 'col-form-label']) }}
                                                                    {!! Form::text('secret_key', Utility::getsettings('paystack_secret_key'), [
                                                                        'class' => 'form-control',
                                                                        'placeholder' => __('Enter secret key'),
                                                                    ]) !!}
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    {{ Form::label('Description', __('Description'), ['class' => 'col-form-label']) }}
                                                                    {{ Form::text('paystack_description', Utility::getsettings('paystack_description'), ['class' => 'form-control ', 'placeholder' => __('Enter description')]) }}
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    {{ Form::label('currency', __('Paystack Currency'), ['class' => 'col-form-label']) }}
                                                                    <select name="paystack_currency" data-trigger
                                                                        class="form-control">
                                                                        <option value="NGN"> {{ __('NGN') }}
                                                                        </option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Paytm -->
                                            <div class="accordion-item card">
                                                <h2 class="accordion-header" id="heading-2-7">
                                                    <button class="accordion-button collapsed" type="button"
                                                        data-bs-toggle="collapse" data-bs-target="#collapse6"
                                                        aria-expanded="true" aria-controls="collapse6">
                                                        <span class="d-flex align-items-center flex-1">
                                                            <i class="ti ti-credit-card text-primary"></i>
                                                            {{ __('Paytm') }}
                                                        </span>
                                                        @if (Utility::getsettings('paytmsetting') == 'on')
                                                            <a
                                                                class="btn btn-sm btn-success float-end me-3 text-white">{{ __('Active') }}</a>
                                                        @endif
                                                    </button>
                                                </h2>
                                                <div id="collapse6" class="accordion-collapse collapse"
                                                    aria-labelledby="heading-2-7" data-bs-parent="#accordionExample">
                                                    <div class="accordion-body">
                                                        <div class="row">
                                                            <div class="col-6 py-2">

                                                            </div>
                                                            <div class="col-6 py-2 text-end">
                                                                <div class="form-check form-switch d-inline-block">
                                                                    {!! Form::checkbox('paymentsetting[]', 'paytm', Utility::getsettings('paytmsetting') == 'on' ? true : false, [
                                                                        'class' => 'form-check-input mx-2',
                                                                        'id' => 'is_paytm_enabled',
                                                                    ]) !!}
                                                                    {{ Form::label('is_paytm_enabled', __('Enable'), ['class' => 'form-check-label']) }}
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12 pb-4">
                                                                {{ Form::label('paytm_environment', __('Paytm Environment'), ['class' => 'form-label']) }}
                                                                </br>
                                                                <div class="d-flex">
                                                                    <div class="mr-2" style="margin-right: 15px;">
                                                                        <div class="border card p-3">
                                                                            <div class="form-check">
                                                                                <label class="form-check-labe text-dark">
                                                                                    {!! Form::radio(
                                                                                        'paytm_environment',
                                                                                        'local',
                                                                                        Utility::getsettings('paytm_environment') == 'local' ? true : false,
                                                                                        [
                                                                                            'class' => 'form-check-input',
                                                                                        ],
                                                                                    ) !!}{{ __('Local') }}
                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="mr-2">
                                                                        <div class="border card p-3">
                                                                            <div class="form-check">
                                                                                <label class="form-check-labe text-dark">
                                                                                    {!! Form::radio(
                                                                                        'paytm_environment',
                                                                                        'production',
                                                                                        Utility::getsettings('paytm_environment') == 'production' ? true : false,
                                                                                        [
                                                                                            'class' => 'form-check-input',
                                                                                        ],
                                                                                    ) !!}{{ __('Production') }}
                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    {{ Form::label('name', __('Paytm Id'), ['class' => 'form-label']) }}
                                                                    {!! Form::text('merchant_id', env('PAYTM_MERCHANT_ID'), [
                                                                        'class' => 'form-control',
                                                                        'placeholder' => __('Enter paytm id'),
                                                                    ]) !!}

                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    {{ Form::label('name', __('Paytm Key'), ['class' => 'form-label']) }}
                                                                    {!! Form::text('merchant_key', env('PAYTM_MERCHANT_KEY'), [
                                                                        'class' => 'form-control',
                                                                        'placeholder' => __('Enter paytm key'),
                                                                    ]) !!}

                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    {{ Form::label('paytm_description', __('Description'), ['class' => 'form-label']) }}
                                                                    {!! Form::text('paytm_description', Utility::getsettings('paytm_description'), [
                                                                        'class' => 'form-control',
                                                                        'placeholder' => __('Enter description'),
                                                                    ]) !!}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Coingate -->
                                            <div class="accordion-item card">
                                                <h2 class="accordion-header" id="heading-2-8">
                                                    <button class="accordion-button collapsed" type="button"
                                                        data-bs-toggle="collapse" data-bs-target="#collapse7"
                                                        aria-expanded="true" aria-controls="collapse7">
                                                        <span class="d-flex align-items-center flex-1">
                                                            <i class="ti ti-credit-card text-primary"></i>
                                                            {{ __('Coingate') }}
                                                        </span>
                                                        @if (Utility::getsettings('coingatesetting') == 'on')
                                                            <a
                                                                class="btn btn-sm btn-success float-end me-3 text-white">{{ __('Active') }}</a>
                                                        @endif
                                                    </button>
                                                </h2>
                                                <div id="collapse7" class="accordion-collapse collapse"
                                                    aria-labelledby="heading-2-8" data-bs-parent="#accordionExample">
                                                    <div class="accordion-body">
                                                        <div class="row">
                                                            <div class="col-6 py-2">
                                                            </div>
                                                            <div class="col-6 py-2 text-end">
                                                                <div class="form-check form-switch d-inline-block">
                                                                    {!! Form::checkbox(
                                                                        'paymentsetting[]',
                                                                        'coingate',
                                                                        Utility::getsettings('coingatesetting') == 'on' ? true : false,
                                                                        [
                                                                            'class' => 'form-check-input mx-2',
                                                                            'id' => 'is_coingate_enabled',
                                                                        ],
                                                                    ) !!}
                                                                    {{ Form::label('is_coingate_enabled', __('Enable'), ['class' => 'form-check-label']) }}
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12 pb-4">
                                                                {{ Form::label('coingate_mode', __('CoinGate Mode'), ['class' => 'col-form-label']) }}
                                                                <br>
                                                                <div class="d-flex">
                                                                    <div class="mr-2" style="margin-right: 15px;">
                                                                        <div class="border card p-3">
                                                                            <div class="form-check">
                                                                                <label class="form-check-labe text-dark">
                                                                                    {!! Form::radio(
                                                                                        'coingate_mode',
                                                                                        'sandbox',
                                                                                        Utility::getsettings('coingate_environment') == 'sandbox' ? true : false,
                                                                                        ['class' => 'form-check-input'],
                                                                                    ) !!}{{ __('Sandbox') }}
                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="mr-2">
                                                                        <div class="border card p-3">
                                                                            <div class="form-check">
                                                                                <label class="form-check-labe text-dark">
                                                                                    {!! Form::radio('coingate_mode', 'live', Utility::getsettings('coingate_environment') == 'live' ? true : false, [
                                                                                        'class' => 'form-check-input',
                                                                                    ]) !!}{{ __('Live') }}
                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    {{ Form::label('coingate_auth_token', __('CoinGate Auth Token'), ['class' => 'form-label']) }}
                                                                    {!! Form::text('coingate_auth_token', Utility::getsettings('coingate_auth_token'), [
                                                                        'class' => 'form-control',
                                                                        'placeholder' => __('Enter coingate auth token'),
                                                                        'id' => 'coingate_auth_token',
                                                                    ]) !!}
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    {{ Form::label('coingate_description', __('Description'), ['class' => 'form-label']) }}
                                                                    {!! Form::text('coingate_description', Utility::getsettings('coingate_description'), [
                                                                        'class' => 'form-control',
                                                                        'placeholder' => __('Enter description'),
                                                                    ]) !!}

                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- mercado -->
                                            <div class="accordion-item card">
                                                <h2 class="accordion-header" id="heading-2-9">
                                                    <button class="accordion-button collapsed" type="button"
                                                        data-bs-toggle="collapse" data-bs-target="#collapse8"
                                                        aria-expanded="true" aria-controls="collapse8">
                                                        <span class="d-flex align-items-center flex-1">
                                                            <i class="ti ti-credit-card text-primary"></i>
                                                            {{ __('Mercado Pago') }}
                                                        </span>
                                                        @if (Utility::getsettings('mercadosetting') == 'on')
                                                            <a
                                                                class="btn btn-sm btn-success float-end me-3 text-white">{{ __('Active') }}</a>
                                                        @endif
                                                    </button>
                                                </h2>
                                                <div id="collapse8" class="accordion-collapse collapse"
                                                    aria-labelledby="heading-2-9" data-bs-parent="#accordionExample">
                                                    <div class="accordion-body">
                                                        <div class="row">
                                                            <div class="col-6 py-2">
                                                            </div>
                                                            <div class="col-6 py-2 text-end">
                                                                <div class="form-check form-switch d-inline-block">
                                                                    {!! Form::checkbox(
                                                                        'paymentsetting[]',
                                                                        'mercado',
                                                                        Utility::getsettings('mercadosetting') == 'on' ? true : false,
                                                                        [
                                                                            'class' => 'form-check-input mx-2',
                                                                            'id' => 'is_mercado_enabled',
                                                                        ],
                                                                    ) !!}
                                                                    {{ Form::label('is_mercado_enabled', __('Enable'), ['class' => 'form-check-label']) }}
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12 pb-4">
                                                                {{ Form::label('mercado_mode', __('Mercado Mode'), ['class' => 'col-form-label']) }}
                                                                <br>
                                                                <div class="d-flex">
                                                                    <div class="mr-2" style="margin-right: 15px;">
                                                                        <div class="border card p-3">
                                                                            <div class="form-check">
                                                                                <label class="form-check-labe text-dark">
                                                                                    {!! Form::radio('mercado_mode', 'sandbox', Utility::getsettings('mercado_mode') == 'sandbox' ? true : false, [
                                                                                        'class' => 'form-check-input',
                                                                                    ]) !!}{{ __('Sandbox') }}
                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="mr-2">
                                                                        <div class="border card p-3">
                                                                            <div class="form-check">
                                                                                <label class="form-check-labe text-dark">
                                                                                    {!! Form::radio('mercado_mode', 'live', Utility::getsettings('mercado_mode') == 'live' ? true : false, [
                                                                                        'class' => 'form-check-input',
                                                                                    ]) !!}{{ __('Live') }}
                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    {{ Form::label('mercado_access_token', __('Access Token'), ['class' => 'form-label']) }}
                                                                    {!! Form::text('mercado_access_token', Utility::getsettings('mercado_access_token'), [
                                                                        'class' => 'form-control',
                                                                        'placeholder' => __('Enter access token'),
                                                                        'id' => 'mercado_access_token',
                                                                    ]) !!}
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    {{ Form::label('mercado_description', __('Description'), ['class' => 'form-label']) }}
                                                                    {{ Form::text('mercado_description', Utility::getsettings('mercado_description'), [
                                                                        'class' => 'form-control ',
                                                                        'placeholder' => __('Enter description'),
                                                                    ]) }}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- OFFLINE -->
                                            <div class="accordion-item card">
                                                <h2 class="accordion-header" id="heading-2-10">
                                                    <button class="accordion-button collapsed" type="button"
                                                        data-bs-toggle="collapse" data-bs-target="#collapse9"
                                                        aria-expanded="true" aria-controls="collapse9">
                                                        <span class="d-flex align-items-center flex-1">
                                                            <i class="ti ti-credit-card text-primary"></i>
                                                            {{ __('Offline') }}
                                                        </span>
                                                        @if (Utility::getsettings('offlinesetting') == 'on')
                                                            <a
                                                                class="btn btn-sm btn-success float-end me-3 text-white">{{ __('Active') }}</a>
                                                        @endif
                                                    </button>
                                                </h2>
                                                <div id="collapse9" class="accordion-collapse collapse"
                                                    aria-labelledby="heading-2-10" data-bs-parent="#accordionExample">
                                                    <div class="accordion-body">
                                                        <div class="row">
                                                            <div class="col-6 py-2">
                                                                {{-- <h5 class="h5">{{ __('Flutterwave') }}</h5> --}}
                                                            </div>
                                                            <div class="col-6 py-2 text-end">
                                                                <div class="form-check form-switch d-inline-block">
                                                                    {!! Form::checkbox(
                                                                        'paymentsetting[]',
                                                                        'offline',
                                                                        Utility::getsettings('offlinesetting') == 'on' ? true : false,
                                                                        [
                                                                            'class' => 'form-check-input mx-2',
                                                                            'id' => 'is_offline_enabled',
                                                                        ],
                                                                    ) !!}
                                                                    {{ Form::label('is_offline_enabled', __('Enable'), ['class' => 'form-check-label']) }}
                                                                </div>
                                                            </div>
                                                            <div>
                                                                <div class="form-group">
                                                                    {{ Form::label('name', __('Payment Details'), ['class' => 'col-form-label']) }}
                                                                    {!! Form::textarea('payment_details', Utility::getsettings('payment_details'), [
                                                                        'class' => 'form-control',
                                                                        'rows' => '3',
                                                                        'placeholder' => __('Enter payment details'),
                                                                    ]) !!}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="text-end">
                                    {{ Form::button(__('Save'), ['type' => 'submit', 'id' => 'save-btn', 'class' => 'btn btn-primary']) }}
                                </div>
                            </div>
                            {!! Form::close() !!}
                        </div>
                    @endif
                </div>
            </div>
            <!-- [ sample-page ] end -->
        </div>
        <!-- [ Main Content ] end -->
    </div>

@endsection



@push('javascript')
    <script>
        function check_theme(color_val) {
            $('.theme-color').prop('checked', false);
            $('input[value="' + color_val + '"]').prop('checked', true);
        }
    </script>
    <script>
        var scrollSpy = new bootstrap.ScrollSpy(document.body, {
            target: '#useradd-sidenav',
            offset: 300,

        })
        $(".list-group-item").click(function() {
            $('.list-group-item').filter(function() {
                return this.href == id;
            }).parent().removeClass('text-primary');
        });
    </script>

    <script>
        $(document).on('change', ".paymenttsetting", function() {

            var test = $(this).val();


            if ($(this).is(':checked')) {

                if (test == 'razorpay') {
                    $("#razorpay").fadeIn(500);
                    $("#razorpay").removeClass('d-none');
                } else if (test == 'stripe') {
                    $("#stripe").fadeIn(500);
                    $("#stripe").removeClass('d-none');
                } else if (test == 'paypal') {
                    $("#paypal").fadeIn(500);
                    $("#paypal").removeClass('d-none');
                } else if (test == 'offline') {
                    $("#offline").fadeIn(500);
                    $("#offline").removeClass('d-none');
                }

            } else {

                if (test == 'razorpay') {
                    $("#razorpay").fadeOut(500);
                    $("#razorpay").addClass('d-none');
                } else if (test == 'stripe') {
                    $("#stripe").fadeOut(500);
                    $("#stripe").addClass('d-none');
                } else if (test == 'paypal') {
                    $("#paypal").fadeOut(500);
                    $("#paypal").addClass('d-none');
                } else if (test == 'offline') {
                    $("#offline").fadeOut(500);
                    $("#offline").addClass('d-none');
                }
            }

        });


        $(document).on('change', ".socialsetting", function() {

            var test = $(this).val();


            if ($(this).is(':checked')) {

                if (test == 'google') {
                    $("#google").fadeIn(500);
                    $("#google").removeClass('d-none');
                } else if (test == 'facebook') {
                    $("#facebook").fadeIn(500);
                    $("#facebook").removeClass('d-none');
                } else if (test == 'github') {
                    $("#github").fadeIn(500);
                    $("#github").removeClass('d-none');
                } else if (test == 'linkedin') {
                    $("#linkedin").fadeIn(500);
                    $("#linkedin").removeClass('d-none');
                }

            } else {

                if (test == 'google') {
                    $("#google").fadeOut(500);
                    $("#google").addClass('d-none');
                } else if (test == 'facebook') {
                    $("#facebook").fadeOut(500);
                    $("#facebook").addClass('d-none');
                } else if (test == 'github') {
                    $("#github").fadeOut(500);
                    $("#github").addClass('d-none');
                } else if (test == 'linkedin') {
                    $("#linkedin").fadeOut(500);
                    $("#linkedin").addClass('d-none');
                }
            }

        });
    </script>
    <script src="{{ asset('assets/js/plugins/choices.min.js') }}"></script>
    <script>
        $('body').on('click', '.send_mail', function() {
            var action = $(this).data('url');
            var modal = $('#common_modal');
            $.get(action, function(response) {
                modal.find('.modal-title').html('{{ __('Test Mail') }}');
                modal.find('.body').html(response);
                modal.modal('show');
            })
        });
    </script>
    <script>
        $(document).on('click', "input[name='settingtype']", function() {
            var test = $(this).val();
            if (test == 's3') {
                $("#s3").fadeIn(500);
                $("#s3").removeClass('d-none');
            } else {
                $("#s3").fadeOut(500);
            }
        });
    </script>
    <script>
        function check_theme(color_val) {
            $('.theme-color').prop('checked', false);
            $('input[value="' + color_val + '"]').prop('checked', true);
        }
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var genericExamples = document.querySelectorAll('[data-trigger]');
            for (i = 0; i < genericExamples.length; ++i) {
                var element = genericExamples[i];
                new Choices(element, {
                    placeholderValue: 'This is a placeholder set in the config',
                    searchPlaceholderValue: 'Select Option',
                });
            }
        });
    </script>
@endpush
