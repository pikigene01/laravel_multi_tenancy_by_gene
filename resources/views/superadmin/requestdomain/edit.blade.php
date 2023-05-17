@extends('layouts.main')
@section('title', __('Approve User'))
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('requestdomain.index') }}">{{ __('Domain Requests') }}</a></li>
    <li class="breadcrumb-item">{{ __('Approve User') }}</li>
@endsection
@section('content')
    <div class="main-content">
        <section class="section">
            <div class="col-sm-4 m-auto">
                <div class="card">
                    <div class="card-header">
                        <h5>{{ __('Approve User') }}</h5>
                    </div>
                    {!! Form::model($requestdomain, [
                        'route' => ['create.user'],
                        'method' => 'POST',
                        'enctype' => 'multipart/form-data','data-validate'
                    ]) !!}
                    <div class="card-body">
                        {!! Form::hidden('type', $requestdomain->type, ['class' => 'form-control']) !!}
                        {!! Form::hidden('password', $requestdomain->password, ['class' => 'form-control']) !!}
                        <div class="form-group ">
                            {{ Form::label('name', __('Name'), ['class' => 'form-label']) }}
                            {!! Form::text('name', null, ['class' => 'form-control', ' required', 'placeholder' => __('Enter name')]) !!}
                        </div>
                        <div class="form-group">
                            {{ Form::label('email', __('Email'), ['class' => 'form-label']) }}
                            {!! Form::text('email', null, [
                                'class' => 'form-control',
                                ' required',
                                'placeholder' => __('Enter email'),
                            ]) !!}
                        </div>
                        <div class="form-group">
                            {{ Form::label('domains', __('Domain configration'), ['class' => 'form-label']) }}
                            {!! Form::text('domains', isset($requestdomain->domain_name) ? $requestdomain->domain_name : '', [
                                'class' => 'form-control',
                                ' required',
                                'placeholder' => __('Enter domain name'),
                            ]) !!}
                        </div>
                        <div class="form-group">
                            {{ Form::label('actual_domain', __('Actual Domain'), ['class' => 'form-label']) }}
                            {!! Form::text(
                                'actual_domain',
                                isset($requestdomain->actual_domain_name) ? $requestdomain->actual_domain_name : '',
                                ['class' => 'form-control', ' required', 'placeholder' => __('Enter actual domain name')],
                            ) !!}
                            <span>{{ __('how to add-on domain in your hosting panel.') }}<a
                                    href="{{ Storage::url('pdf/adddomain.pdf') }}" class="m-2"
                                    target="_blank">{{ __('Document') }}</a></span>
                        </div>
                        <div class="form-group">
                            {{ Form::label('db_name', __('Database Name'), ['class' => 'form-label']) }}
                            {!! Form::text('db_name', null, [
                                'class' => 'form-control',
                                ' required',
                                'placeholder' => __('Enter database name'),
                            ]) !!}
                        </div>
                        <div class="form-group">
                            {{ Form::label('db_username', __('Database User'), ['class' => 'form-label']) }}
                            {!! Form::text('db_username', null, [
                                'class' => 'form-control',
                                ' required',
                                'placeholder' => __('Enter database username'),
                            ]) !!}
                        </div>
                        <div class="form-group ">
                            {{ Form::label('db_password', __('Database Password'), ['class' => 'form-label']) }}
                            {!! Form::password('db_password', [
                                'class' => 'form-control',
                                'required',
                                'placeholder' => __('Enter database password'),
                            ]) !!}
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
        </section>
    </div>
@endsection
