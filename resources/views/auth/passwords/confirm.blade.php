@extends('layouts.app')
@section('title', __('Confirm password'))
@section('content')
    <div class="card-body">
        <div class="">
            <h2 class="mb-3 f-w-600">{{ __('Confirm Password') }}</h2>
        </div>
        <div class="">
            <small class="text-muted">{{ __('Please confirm your password before continuing.') }}</small>
            <form method="POST" data-validate action="{{ route('password.confirm') }}">
                @csrf
                <div class="form-group mb-4">
                    <label for="password" class="form-label">{{ __('Password') }}</label>
                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                        name="password" required autocomplete="current-password">
                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="d-grid row">
                    <div class="col-md-8 offset-md-4">
                        <button type="submit" class="btn btn-primary btn-block mt-2">
                            {{ __('Confirm Password') }}
                        </button>
                        @if (Route::has('password.request'))
                            <a class="btn btn-link" href="{{ route('password.request') }}">
                                {{ __('Forgot Your Password ?') }}
                            </a>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
