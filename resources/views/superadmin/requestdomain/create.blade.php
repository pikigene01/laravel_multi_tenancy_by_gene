@php
    $languages = \App\Facades\UtilityFacades::languages();
@endphp
@extends('layouts.app')
@section('title', __('Register'))
@section('auth-topbar')
    <li class="nav-item">
        <select class="btn btn-primary my-1 me-2 "
            onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);"
            id="language">
            @foreach ($languages as $language)
                <option class="" @if ($lang == $language) selected @endif
                    value="{{ route('requestdomain.create', [$data, $language]) }}">{{ Str::upper($language) }}
                </option>
            @endforeach
        </select>
    </li>
@endsection
@section('content')
    <div class="card-body">
        <div class="">
            <h2 class="mb-3 f-w-600">{{ __('Register') }}</h2>
        </div>
        <div class="">
            {!! Form::open(['route' => 'requestdomain.store', 'method' => 'POST', 'id' => 'request_form', 'data-validate']) !!}
            <div class="form-group mb-3">
                {{ Form::label('name', __('Name'), ['class' => 'form-label']) }}
                {!! Form::text('name', null, [
                    'class' => 'form-control',
                    'id' => 'name',
                    'placeholder' => __('Enter name'),
                    'required',
                    'autofocus',
                ]) !!}
            </div>
            <div class="form-group mb-3">
                {{ Form::label('email', __('Email Address'), ['class' => 'form-label']) }}
                {!! Form::email('email', null, [
                    'class' => 'form-control',
                    'id' => 'email',
                    'placeholder' => __('Enter email'),
                    'required',
                ]) !!}
            </div>
            <div class="form-group mb-3">
                {{ Form::label('password', __('Password'), ['class' => 'd-block form-label']) }}
                {!! Form::password('password', [
                    'class' => 'form-control pwstrength',
                    'id' => 'password',
                    'placeholder' => __('Enter password'),
                    'data-indicator' => 'pwindicator',
                    'required',
                ]) !!}
                <div id="pwindicator" class="pwindicator">
                    <div class="bar"></div>
                    <div class="label"></div>
                </div>
            </div>
            <div class="form-group mb-3">
                {{ Form::label('password2', __('Password Confirmation'), ['class' => 'd-block form-label']) }}
                {!! Form::password('password_confirmation', [
                    'class' => 'form-control',
                    'id' => 'password-confirm',
                    'placeholder' => __('Enter confirm password'),
                    'required',
                ]) !!}
            </div>
            <div class="form-group mb-3">
                {{ Form::label('domains', __('Domain Configration'), ['class' => 'form-label']) }}
                {!! Form::text('domains', null, [
                    'class' => 'form-control companyname-change',
                    ' required',
                    'placeholder' => __('choose your app name'),
                ]) !!} <span>https://<span class="companyname">companyname</span>.riskcurb.app</span>
            </div>
            {{-- <div class="form-group mb-3">
                {{ Form::label('actual_domain', __('Actual Domain'), ['class' => 'form-label']) }}
                {!! Form::text('actual_domain', null, [
                    'class' => 'form-control',
                    'required',
                    'placeholder' => __('Enter actual domain name'),
                ]) !!}
                <span>{{ __('how to add-on domain in your hosting panel.') }}<a
                        href="{{ Storage::url('pdf/adddomain.pdf') }}" class="m-2"
                        target="_blank">{{ __('Document') }}</a></span>
            </div> --}}
            <div class="form-group mb-4">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="agree" id="agree">
                    <label class="form-check-label" for="agree">
                        {{ __('I agree with ') }}<a href="{{ route('termsandconditions') }}"
                            target="_blank">{{ __('terms and conditions') }}</a>
                    </label>
                </div>
            </div>
            <div class="d-grid">
                <input type="hidden" id="plan_id" name="plan_id" value="{{ $plan_id }}">
                <button type="submit" class="btn btn-primary btn-block mt-2">
                    {{ __('Register') }}
                </button>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
    <script>
    const companyname_change = document.querySelector('.companyname-change');
    companyname_change.oninput =(e)=>{
       let value = e.target.value;
       document.querySelector('.companyname').innerHTML = value?.replace(" ","_").toLowerCase();
       document.querySelector('.companyname').style.color = 'blue';
    }

    </script>
@endsection
