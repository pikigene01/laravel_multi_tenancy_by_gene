@php
    $languages = \App\Facades\UtilityFacades::languages();
@endphp
@extends('layouts.app')
@section('title', __('Sign Up'))
@section('auth-topbar')
    <li class="nav-item">
        <select class="btn btn-primary my-1 me-2 "
            onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);"
            id="language">
            @foreach ($languages as $language)
                <option class="" @if ($lang == $language) selected @endif
                    value="{{ route('register', $language) }}">{{ Str::upper($language) }}
                </option>
            @endforeach
        </select>
    </li>
@endsection
@section('content')
    <div class="card-body">
        <div class="">
            <h2 class="mb-3 f-w-600">{{ __('Sign Up') }}</h2>
        </div>
        <div class="">
            <form method="POST" data-validate action="{{ route('register') }}">
                @csrf
                <div class="form-group mb-3">
                    <label class="form-label">{{ __('Name') }}</label>
                    <input id="name" type="text" class="form-control" name="name" required autocomplete="name"
                        autofocus placeholder="{{ __('Enter name') }}">
                </div>
                <div class="form-group mb-3">
                    <label class="form-label">{{ __('Email') }}</label>
                    <input id="email" type="email" class="form-control" name="email" required autocomplete="email"
                        placeholder="{{ __('Enter email address') }}">
                </div>
                <div class="form-group mb-3">
                    <label class="form-label">{{ __('Password') }}</label>
                    <input id="password" type="password" class="form-control pwstrength" data-indicator="pwindicator"
                        name="password" placeholder="{{ __('Enter password') }}" required>
                </div>
                <div class="form-group mb-3">
                    <label class="form-label">{{ __('Password Confirmation') }}</label>
                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required
                        autocomplete="new-password" placeholder="{{ __('Enter password confirmation') }}">
                </div>
                <div class="form-group mb-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="" id="flexCheckChecked">
                        <label class="form-check-label" for="flexCheckChecked">
                            {{ __('I accept the ') }}<a href="{{ route('termsandconditions') }}">
                                {{ __('Term & condition') }}</a>
                        </label>
                    </div>
                </div>
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary btn-block mt-2">
                        {{ __('Sign Up') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
