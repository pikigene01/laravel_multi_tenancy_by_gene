@php
    $languages = \App\Facades\UtilityFacades::languages();
@endphp
@extends('layouts.app')
@section('title', __('Sign in'))
@section('auth-topbar')
    <li class="nav-item">
        <select class="btn btn-primary my-1 me-2 "
            onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);"
            id="language">
            @foreach ($languages as $language)
                <option class="" @if ($lang == $language) selected @endif
                    value="{{ route('login', $language) }}">{{ Str::upper($language) }}
                </option>
            @endforeach
        </select>
    </li>
@endsection
@section('content')
    <div class="card-body">
        <div class="">
            <h2 class="mb-3 f-w-600">{{ __('Sign in') }}</h2>
        </div>
        <div class="">
            <form method="POST" data-validate action="{{ route('login') }}">
                @csrf
                <div class="form-group mb-3">
                    <label class="form-label" for="email">{{ __('Email Address') }}</label>
                    <input type="email" class="form-control" placeholder="{{ __('Enter email address') }}" name="email"
                        tabindex="1" required autocomplete="email" autofocus>
                </div>
                <div class="form-group mb-3">
                    <label class="form-label" for="password">{{ __('Enter Password') }}</label>
                    <a href="{{ route('password.request') }}" class="text-small float-end">
                        {{ __('Forgot Password ?') }}
                    </a>
                    <input id="password" type="password" class="form-control" placeholder="{{ __('Enter password') }}"
                        name="password" tabindex="2" required autocomplete="current-password">
                </div>
                <div class="form-group mb-4">
                    <div class="form-check form-switch">
                        <input type="checkbox" class="form-check-input" id="customswitch1" />
                        <label class="form-check-label" for="customswitch1">{{ __('Remember me') }}</label>
                    </div>
                </div>
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary btn-block mt-2">
                        {{ __('Sign In') }}
                    </button>
                </div>
            </form>
            @if (tenant())
                <div class="my-4 text-center">
                    {{ __('Do not have an account ?') }} <a href="{{ route('register') }}">{{ __('Create One') }}</a>
                </div>
            @endif
            @if (Utility::getsettings('googlesetting') == 'on' ||
                Utility::getsettings('facebooksetting') == 'on' ||
                Utility::getsettings('githubsetting') == 'on')
                <p class="my-4 text-center">{{ __('or register with') }}</p>
            @endif
            <div class="row mb-4">
                @if (Utility::getsettings('googlesetting') == 'on')
                    <div class="col-4">
                        <a href="{{ url('/redirect/google') }}">
                            <div class="d-grid">
                                <button class="btn btn-light">
                                    <img src="{{ asset('assets/images/auth/img-google.svg') }}" alt=""
                                        class="img-fluid wid-25" />
                                </button>
                            </div>
                        </a>
                    </div>
                @endif
                @if (Utility::getsettings('facebooksetting') == 'on')
                    <div class="col-4">
                        <div class="d-grid">
                            <a href="{{ url('/redirect/facebook') }}">
                                <button class="btn btn-light">
                                    <img src="{{ asset('assets/images/auth/img-facebook.svg') }}" alt=""
                                        class="img-fluid wid-25" />
                                </button>
                            </a>
                        </div>
                    </div>
                @endif
                @if (Utility::getsettings('githubsetting') == 'on')
                    <div class="col-4">
                        <div class="d-grid">
                            <a href="{{ url('/redirect/github') }}">
                                <button class="btn btn-light">
                                    <img src="{{ asset('assets/images/auth/github.svg') }}" alt=""
                                        class="img-fluid wid-25" />
                                </button>
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
