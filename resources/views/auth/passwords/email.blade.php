@extends('layouts.app')
@section('title', __('Email verify'))
@section('content')
    <div class="card-body">
        <div class="">
            <h2 class="mb-3 f-w-600">{{ __('Email verify') }}</h2>
        </div>
        <div class="">
            <small class="text-muted">{{ __('We will send a link to reset your password') }}</small>
            <form method="POST" data-validate action="{{ route('password.email') }}">
                @csrf
                <div class="form-group mb-4">
                    <label class="form-label" for="email">{{ 'Enter Email address' }}</label>
                    <input type="email" class="form-control" placeholder="Email address" name="email" tabindex="1"
                        required autocomplete="email" autofocus>
                </div>
                <div class="text-center">
                    <button type="submit" class="btn btn-primary">{{ __('Email verify') }}</button>
                    <a href="{{ url('/home') }}" class="btn btn-secondary text-white">{{ __('Back') }}</a>
                </div>
            </form>
        </div>
    </div>
@endsection
