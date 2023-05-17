@extends('layouts.main')
@section('title', __('Profile'))
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item">{{ __('Profile') }}</li>
@endsection
@section('content')
    <div class="row">
        <!-- [ sample-page ] start -->
        <div class="col-sm-12">
            <div class="row">
                <div class="col-xl-3">
                    <div class="card sticky-top" style="top:30px">
                        <div class="list-group list-group-flush" id="useradd-sidenav">
                            <a href="#profile" class="list-group-item list-group-item-action">{{ __('Profile') }}<div
                                    class="float-end"><i class="ti ti-chevron-right"></i></div></a>
                            <a href="#basic_info" class="list-group-item list-group-item-action">{{ __('Basic Info') }}
                                <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                            </a>
                            <a href="#update_login" class="list-group-item list-group-item-action">{{ __('Update Login') }}
                                <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                            </a>
                            @if (Utility::getsettings('2fa'))
                                <a href="#twofaauth" class="list-group-item list-group-item-action">{{ __('2fa') }}<div
                                        class="float-end"><i class="ti ti-chevron-right"></i></div></a>
                            @endif
                            <a href="#delete_account"
                                class="list-group-item list-group-item-action">{{ __('Delete Account') }}<div
                                    class="float-end"><i class="ti ti-chevron-right"></i></div></a>
                        </div>
                    </div>
                </div>
                <div class="col-xl-9">
                    <div id="profile" class="card bg-primary text-white">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="avatar me-3">
                                    <img id="avatar-img" class="img-user wid-80 rounded-circle"
                                        src="{{ $user->avatar_image }}">
                                </div>
                                <div class="d-block d-sm-flex align-items-center justify-content-between w-100">
                                    <div class="mb-3 mb-sm-0">
                                        <h4 class="mb-1 text-white">{{ $user->name }}</h4>
                                        <p class="mb-0 text-sm">{{ $user->email }}</p>
                                        <p class="mb-0 text-sm">{{ $role ? $role->name : 'Role Not Set' }}</p>
                                        @if (\Auth::user()->social_type != null)
                                            <p class="mb-0 text-sm"><b>{{ __('Login with:') }}</b>
                                                {{ ucfirst($user->social_type) }}
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="basic_info" class="card">
                        <div class="card-header">
                            <h5>{{ __('Basic info') }}</h5>
                        </div>
                        {!! Form::open([
                            'route' => ['profile.update', $user->id],
                            'data-validate',
                            'method' => 'POST',
                            'class' => 'form-horizontal',
                            'data-validate',
                        ]) !!}

                        <div class="card-body">
                            <div class=" row mt-3">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        {{ Form::label('FullName', __('Full Name'), ['class' => 'form-label']) }}
                                        {!! Form::text('fullname', $user->name, [
                                            'placeholder' => __('Enter full name'),
                                            'class' => 'form-control',
                                            'required',
                                        ]) !!}
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        {{ Form::label('phone', __('Phone'), ['class' => 'form-label']) }}
                                        {!! Form::text('phone', $user->phone, [
                                            'placeholder' => __('Enter phone number'),
                                            'class' => 'form-control',
                                            'required',
                                        ]) !!}
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        {{ Form::label('country', __('Country'), ['class' => 'form-label']) }}
                                        <select class="form-control form-control-inline-block" data-trigger name="country">
                                            <option value="">{{ __('Select country') }}</option>
                                            @foreach ($countries as $val)
                                                <option value="{{ $val['name'] }}"
                                                    {{ $user->country == $val['name'] ? 'selected' : '' }}>
                                                    {{ $val['name'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        {{ Form::label('address', __('Address'), ['class' => 'form-label']) }}
                                        {!! Form::text('address', $user->address, [
                                            'placeholder' => __('Enter address'),
                                            'class' => 'form-control',
                                            'required',
                                        ]) !!}
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        {{ Form::label('role', __('Role'), ['class' => 'form-label']) }}
                                        {!! Form::text('role', $role ? $role->name : __('Role Not Set'), ['class' => 'form-control', 'disabled']) !!}
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="avatar_crop btn btn-primary btn-lg d-block mx-auto col-sm-12 mb-0"
                                            for="avatarCrop">
                                            {{ __('Update Avatar') }}
                                            <input type="file" class="d-none" id="avatarCrop">
                                        </label>
                                    </div>
                                    <div id="avatar-updater" class="col-xs-12 d-none">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="image-preview"></div>
                                            </div>
                                            <div class="col-md-12">
                                                {!! Form::text('avatar-url', route('update_avatar', Auth::user()->id), ['class' => 'd-none']) !!}
                                                {!! Form::button(__('Rotate Image'), [
                                                    'class' => 'btn btn-gradient-info col-sm-12 mb-1',
                                                    'id' => 'rotate-image',
                                                ]) !!}
                                                {!! Form::button(__('Crop Image'), [
                                                    'class' => 'btn btn-gradient-primary col-sm-12',
                                                    'id' => 'crop_image',
                                                ]) !!}
                                                {!! Form::button(__('Cancel'), [
                                                    'class' => 'btn btn-gradient-secondary col-sm-12 mt-1',
                                                    'id' => 'avatar-cancel-btn',
                                                ]) !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="text-end">
                                {{ Form::button(__('Save'), ['type' => 'submit', 'class' => 'btn btn-primary']) }}
                            </div>
                        </div>
                        {!! Form::close() !!}
                    </div>
                    <div id="update_login" class="card">
                        <div class="card-header">
                            <h5>{{ __('Update Login') }}</h5>
                        </div>
                        {!! Form::open([
                            'route' => ['update_login', $user->id],
                            'method' => 'Post',
                            'class' => 'form-horizontal',
                            'enctype' => 'multipart/form-data',
                            'data-validate',
                        ]) !!}
                        <div class="card-body">
                            <div class="row form-group">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        {{ Form::label('email', __('Email'), ['class' => 'form-label']) }}
                                        {!! Form::email('email', $user->email, [
                                            'placeholder' => __('Email'),
                                            'class' => 'form-control',
                                            'required',
                                        ]) !!}
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        {{ Form::label('password', __('Password'), ['class' => 'form-label']) }}
                                        {!! Form::password('password', [
                                            'class' => 'form-control',
                                            'placeholder' => __('Leave blank if you dont want to change'),
                                            'autocomplete' => 'off',
                                        ]) !!}
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        {{ Form::label('password_confirmation', __('Confirm Password'), ['class' => 'form-label']) }}
                                        {!! Form::password('password_confirmation', [
                                            'class' => 'form-control',
                                            'placeholder' => __('Leave blank if you dont want to change'),
                                            'autocomplete' => 'off',
                                        ]) !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="text-end">
                                {{ Form::button(__('Save'), ['type' => 'submit', 'class' => 'btn btn-primary']) }}
                            </div>
                        </div>
                        {!! Form::close() !!}
                    </div>

                    @if (Utility::getsettings('2fa'))
                        <div id="twofaauth" class="card">
                            <div class="card-header">
                                <h5>{{ __('2fa') }}</h5>
                            </div>
                            <div class="card-body">
                                @if (Utility::getsettings('2fa'))
                                    <div class="tab-pane" id="tfa-settings" role="tabpanel"
                                        aria-labelledby="tfa-settings-tab">
                                        <!--Google Two Factor Authentication card-->
                                        <div class="col-md-12">
                                            @if (empty(auth()->user()->loginSecurity))
                                                <!--=============Generate QRCode for Google 2FA Authentication=============-->
                                                <div class="row p-0">
                                                    <div class="col-md-12">
                                                        <p>{{ __('To activate Two factor Authentication Generate QRCode') }}
                                                        </p>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <form class=""
                                                            action="
                                                                                                                            {{ route('generate2faSecret') }}"
                                                            method="post">
                                                            @csrf
                                                            <button
                                                                class="btn btn-primary col-md-2 float-end">{{ __('Activate 2fa') }}</button>
                                                            {{-- <a class="btn btn-secondary col-md-5" data-toggle="collapse"
                                                        href="#collapseExample" role="button" aria-expanded="false"
                                                        aria-controls="collapseExample">{{ __('Setup Instruction') }}</a> --}}
                                                        </form>
                                                    </div>
                                                    <div class="col-md-12 mt-3 collapse" id="collapseExample">
                                                        <hr>
                                                        <h3 class="">
                                                            {{ __('Two Factor Authentication(2FA) Setup Instruction') }}
                                                        </h3>
                                                        <hr>
                                                        <div class="
                                            mt-4">
                                                            <h4>{{ __('Below is a step by step instruction on setting up Two Factor Authentication') }}
                                                            </h4>
                                                            <p><label>{{ __('Step 1') }}:</label>
                                                                {{ __('download') }}
                                                                <strong>{{ __('Google Authenticator App') }}</strong>
                                                                {{ __('Application for Andriod or iOS') }}
                                                            </p>
                                                            <p class="text-center">
                                                                <a href="https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2&hl=en"
                                                                    target="_blank"
                                                                    class="btn btn-success">{{ __('Download for Andriod') }}<i
                                                                        class="fa fa-android fa-2x ml-2"></i></a>
                                                                <a href="https://apps.apple.com/us/app/google-authenticator/id388497605"
                                                                    target="_blank"
                                                                    class="btn btn-dark ml-2">{{ __('Download for iPhones') }}<i
                                                                        class="fa fa-apple fa-2x ml-2"></i></a>
                                                            </p>
                                                            <p><label>{{ __('Step 2') }}:</label>
                                                                {{ __('Click on Generate Secret Key on the platform to generate a QRCode') }}
                                                            </p>
                                                            <p><label>{{ __('Step 3') }}:</label>
                                                                {{ __('Open the') }}
                                                                <strong>{{ __('Google Authenticator App') }}</strong>
                                                                {{ __('and clcik on') }}
                                                                <strong>{{ __('Begin') }}</strong>
                                                                {{ __('on the mobile app') }}
                                                            </p>
                                                            <p><label>{{ __('Step 4') }}:</label>
                                                                {{ __('After which click on') }}
                                                                <strong>{{ __('Scan a QRcode') }}</strong>
                                                            </p>
                                                            <p><label>{{ __('Step 5') }}:</label>
                                                                {{ __('Then scan the barcode on the platform') }}</p>
                                                            <p><label>{{ __('Step 6') }}:</label>
                                                                {{ __('Enter the verification code generated on the platform and Enable 2FA') }}
                                                            </p>
                                                            <hr>
                                                            <p><label>{{ __('Note') }}:</label>
                                                                {{ __('To disable 2FA enter code from the Google Authenticator App and account password to disable 2FA') }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!--=============Generate QRCode for Google 2FA Authentication=============-->
                                            @elseif(!auth()->user()->loginSecurity->google2fa_enable)
                                                <!--=============Enable Google 2FA Authentication=============-->
                                                <form class="form-horizontal" method="POST"
                                                    action="{{ route('enable2fa') }}">
                                                    @csrf
                                                    <div class="row form-group">
                                                        <div class="col-md-12">
                                                            <p><strong>{{ __('Scan the QRcode with') }}
                                                                    <dfn>{{ __('Google Authenticator App') }}</dfn>
                                                                    {{ __('Enter the generated code below') }}</strong>
                                                            </p>
                                                        </div>
                                                        <div class="col-md-12"><img src="{{ $google2fa_url }}" />
                                                        </div>
                                                        <div class="col-md-12">
                                                            <p>{{ __('To enable 2-Factor Authentication verify QRCode') }}
                                                            </p>
                                                        </div>
                                                        <div class="col-sm-12">
                                                            <label for="address"
                                                                class="form-label">{{ __('Verification code') }}</label>
                                                            <input type="password" name="secret" class="form-control"
                                                                id="code"
                                                                placeholder="{{ __('Enter the verification code generated on the platform and Enable 2FA') }}">
                                                            @if ($errors->has('verify-code'))
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $errors->first('verify-code') }}</strong>
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <hr>
                                                    <div>
                                                        <button type="submit"
                                                            class="btn btn-primary col-sm-2 float-end">{{ __('Enable 2FA') }}</button>
                                                    </div>
                                                </form>
                                                <!--=============Enable Google 2FA Authentication=============-->
                                            @elseif(auth()->user()->loginSecurity->google2fa_enable)
                                                <!--=============Disable Google 2FA Authentication=============-->
                                                <form class="form-horizontal" method="POST"
                                                    action="{{ route('disable2fa') }}">
                                                    @csrf
                                                    <div class="row form-group">
                                                        <div class="col-md-12"><img src="{{ $google2fa_url }}" />
                                                        </div>
                                                        <div class="col-md-12">
                                                            <p>{{ __('To disable 2-Factor Authentication verify QRCode') }}
                                                            </p>
                                                        </div>
                                                        <div class="col-sm-12">
                                                            <label for="address"
                                                                class="form-label">{{ __('Current Password') }}</label>
                                                            <input id="password" type="password"
                                                                placeholder="{{ __('Current Password') }}"
                                                                class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}"
                                                                name="current-password" required>
                                                            @error('password')
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $error('password') }}</strong>
                                                                </span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <hr>
                                                    <div>
                                                        <button type="submit"
                                                            class="btn btn-danger col-sm-2 float-end">{{ __('Disable 2FA') }}</button>
                                                    </div>
                                                </form>
                                                <!--=============Disable Google 2FA Authentication=============-->
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif

                    <div id="delete_account" class="card">
                        <div class="card-header">
                            <h5>{{ __('Delete Account') }}</h5>
                            <small
                                class="text-muted">{{ __('Once you delete your account, there is no going back. Please be certain.') }}</small>
                        </div>
                        <div class="card-body">
                            <div class="row justify-content-between mt-3 float-end">
                                <div class="col-sm-auto text-sm-end d-flex">
                                    {!! Form::open([
                                        'method' => 'DELETE',
                                        'route' => ['profile.delete', $user->id],
                                        'id' => 'delete-form-' . $user->id,
                                    ]) !!}
                                    <a class="btn btn-danger show_confirm d-flex" data-toggle="tooltip"
                                        href="#!">{{ __('Delete Account') }}<i
                                            class="ti ti-chevron-right ms-1 ms-sm-2"></i></a>
                                    {!! Form::close() !!}

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- [ sample-page ] end -->
        </div>
        <!-- [ Main Content ] end -->
    </div>
@endsection
@push('css')
    <link rel="stylesheet" href="{{ asset('vendor/croppie/css/croppie.min.css') }}">
@endpush
@push('javascript')
    <script src="{{ asset('vendor/croppie/js/croppie.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/choices.min.js') }}"></script>
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
    <script>
        $(document).ready(function() {
            window.setTimeout(function() {}, 9e3), $image_crop = $(".image-preview").croppie({
                enableExif: !0,
                enforceBoundary: !1,
                enableOrientation: !0,
                viewport: {
                    width: 200,
                    height: 200,
                    type: "square"
                },
                boundary: {
                    width: 300,
                    height: 300
                }
            }), $(" #avatarCrop").change(function() {
                $("#avatar-holder").addClass("d-none"), $("#avatar-updater").removeClass("d-none");
                var e = new FileReader;
                e.onload = function(e) {
                    $image_crop.croppie("bind", {
                        url: e.target.result
                    })
                }, e.readAsDataURL(this.files[0])
            }), $("#toggleClose").click(function() {
                $("#toggleClose").css("display", "none"), $(".app-logo").css("display", "none"), $(
                    ".toggleopen").css("display", "block")
            }), $(".toggleopen").click(function() {
                $(".toggleopen").css("display", "none"), $(".app-logo").css("display", "block"), $(
                    "#toggleClose").css("display", "block")
            }), $("#rotate-image").click(function(e) {
                $image_crop.croppie("rotate", 90)
            }), $("#crop_image").click(function() {
                $image_crop.croppie("result", {
                    type: "canvas",
                    size: "viewport"
                }).then(function(e) {
                    var a = $("input[name=avatar-url]").val(),
                        t = $('meta[name="csrf-token"]').attr("content"),
                        o = $("#crop_image");
                    o.html("Saving Avatar..."), o.attr("disabled", "disabled"), $.ajaxSetup({
                        headers: {
                            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
                        }
                    }), $.ajax({
                        url: a,
                        type: "POST",
                        data: {
                            avatar: e,
                            _token: t
                        },
                        dataType: "json",
                        success: function(e) {},
                        complete: function(e) {
                            new swal({
                                text: e.responseText,
                                icon: "success"
                            }).then(() => {
                                location.reload()
                            })
                        }
                    })
                })
            }), $("#avatar-cancel-btn").click(function() {
                $("#avatar-holder").removeClass("d-none"), $("#avatar-updater").addClass("d-none")
            });
            $("#backup-file-btn").click(function() {
                swal({
                    text: "Application file backup is disabled by Administrator",
                    icon: 'error',
                });
            });
            $("#backup-database-btn").click(function() {
                swal({
                    text: "Database backup is disabled by Administrator",
                    icon: 'error',
                });
            });
        });
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
@endpush
