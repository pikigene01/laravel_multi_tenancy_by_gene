@extends('layouts.main')
@section('title', __('Edit Domain Request'))
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('requestdomain.index') }}">{{ __('Domain Requests') }}</a></li>
    <li class="breadcrumb-item">{{ __('Edit Domain Request') }}</li>
@endsection
@section('content')
    <div class="main-content">
        <section class="section">
            <div class="col-sm-4 m-auto">
                <div class="card">
                    <div class="card-header">
                        <h5>{{ __('Edit Domain Request') }}</h5>
                    </div>
                    {!! Form::model($requestdomain, [
                        'route' => ['requestdomain.update', $requestdomain->id],
                        'method' => 'POST',
                        'enctype' => 'multipart/form-data','data-validate'
                    ]) !!}
                    <div class="card-body">
                        <div class="form-group ">
                            <label for="name" class="form-label">{{ __('Name') }}</label>
                            <input id="name" value="{{ $requestdomain->name }}" type="text" class="form-control"
                                name="name" required autocomplete="name" placeholder="{{ __('Enter name') }}" autofocus>
                        </div>
                        <div class="form-group">
                            <label for="email" class="form-label">{{ __('Email') }}</label>
                            <input id="email" type="email" class="form-control" value="{{ $requestdomain->email }}"
                                name="email" required placeholder="{{ __('Enter email') }}" autocomplete="email">
                        </div>
                        <div class="form-group">
                            <label for="password" class="d-block" class="form-label">{{ __('Password') }}</label>
                            <input id="password" type="password" class="form-control pwstrength"
                                placeholder="{{ __('Enter password') }}" data-indicator="pwindicator" name="password">
                            <div id="pwindicator" class="pwindicator">
                                <div class="bar"></div>
                                <div class="label"></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="password2" class="d-block"
                                class="form-label">{{ __('Password Confirmation') }}</label>
                            <input id="password-confirm" type="password" class="form-control"
                                placeholder="{{ __('Enter confirm password') }}" name="password_confirmation"
                                autocomplete="new-password">
                        </div>
                        <div class="form-group">
                            {{ Form::label('domains', __('Domain Configration'), ['class' => 'form-label']) }}
                            {!! Form::text('domains', $requestdomain->domain_name, [
                                'class' => 'form-control',
                                ' required',
                                'placeholder' => __('Enter domain name'),
                            ]) !!}
                        </div>
                        <div class="form-group">
                            {{ Form::label('actual_domain', __('Actual Domain'), ['class' => 'form-label']) }}
                            {!! Form::text('actual_domain', $requestdomain->actual_domain_name, [
                                'class' => 'form-control',
                                'required',
                                'placeholder' => __('Enter actual domain name'),
                            ]) !!}
                            <span>{{ __('how to add-on domain in your hosting panel.') }}<a
                                    href="{{ Storage::url('pdf/adddomain.pdf') }}" class="m-2"
                                    target="_blank">{{ __('Document') }}</a></span>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="text-end">
                            <a href="{{ route('requestdomain.index') }}" class="btn btn-secondary">{{ __('Cancel') }}</a>
                            {{ Form::button(__('Save'), ['type' => 'submit', 'class' => 'btn btn-primary']) }}
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </section>
    </div>
@endsection
