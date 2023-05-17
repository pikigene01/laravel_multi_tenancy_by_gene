@extends('layouts.app')
@section('title', __('Email verify'))
@section('content')
    <div class="card-body">
        <div class="">
            <h2 class="mb-3 f-w-600">{{ __('Verify Your Email Address') }}</h2>
        </div>
        <div class="">
            <small class="text-muted">{{ __('We will send a link to reset your samallassword') }}</small>
            @if (session('resent'))
                <div class="alert alert-success" role="alert">
                    {{ __('A fresh verification link has been sent to your email address.') }}
                </div>
            @endif
            {{ __('Before proceeding, please check your email for a verification link.') }}
            {{ __('If you did not receive the email') }},
            <br>
            <form class="d-inline " method="POST" action="{{ route('verification.resend') }}">
                @csrf
                <button type="submit"
                    class="btn btn-link mt-2 align-baseline">{{ __('Click here to request another') }}</button>
            </form>
        </div>
    </div>
@endsection
