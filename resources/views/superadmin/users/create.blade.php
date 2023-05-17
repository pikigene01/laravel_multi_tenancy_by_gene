@extends('layouts.main')
@section('title', __('Create Admin'))
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('users.index') }}">{{ __('Admins') }}</a></li>
    <li class="breadcrumb-item">{{ __('Create Admin') }}</li>
@endsection
@section('content')
    <div class="main-content">
        <section class="section">
            <div class="section-body">
                @if (tenant('id') == null)
                    @if ($dbpermission == null)
                        <div class="alert alert-warning">
                            {{ __('Please on your database permission to create auto generate DATABASE.') }}<a
                                href="{{ route('settings') }}" class=""
                                target="_blank">{{ __('On database permission') }}</a>
                        </div>
                    @else
                        <div class="alert alert-warning">
                            {{ __('Please off your database permission to create your own DATABASE.') }}<a
                                href="{{ route('settings') }}" class=""
                                target="_blank">{{ __('Off database permission') }}</a>
                        </div>
                    @endif
                @endif

                <div class="col-sm-4 m-auto">
                    <div class="card">
                        <div class="card-header">
                            <h5>{{ __('Create Admin') }}</h5>
                        </div>
                        <div class="card-body">
                            {!! Form::open([
                                'route' => 'users.store',
                                'method' => 'Post',
                                'enctype' => 'multipart/form-data',
                                'data-validate',
                            ]) !!}
                            <div class="form-group ">
                                {{ Form::label('name', __('Name'), ['class' => 'form-label']) }}
                                {!! Form::text('name', null, ['class' => 'form-control', ' required', 'placeholder' => __('Enter name')]) !!}
                            </div>
                            <div class="form-group">
                                {{ Form::label('email', __('Email'), ['class' => 'form-label']) }}
                                {!! Form::text('email', null, [
                                    'class' => 'form-control',
                                    ' required',
                                    'placeholder' => __('Enter email address'),
                                ]) !!}
                            </div>
                            <div class="form-group ">
                                {{ Form::label('password', __('Password'), ['class' => 'form-label']) }}
                                {!! Form::password('password', ['class' => 'form-control', ' required', 'placeholder' => __('Enter password')]) !!}
                            </div>
                            <div class="form-group ">
                                {{ Form::label('confirm-password', __('Confirm Password'), ['class' => 'form-label']) }}
                                {{ Form::password('confirm-password', ['class' => 'form-control', ' required', 'placeholder' => __('Enter confirm password')]) }}
                            </div>

                            <div class="form-group">
                                {{ Form::label('domains', __('Domain Configration'), ['class' => 'form-label']) }}
                                {!! Form::text('domains', null, [
                                    'class' => 'form-control',
                                    'required',
                                    'placeholder' => __('Enter domain name'),
                                ]) !!}
                                <span>{{ __('how to add-on domain in your hosting panel.') }}<a
                                        href="{{ Storage::url('pdf/adddomain.pdf') }}" class="m-2"
                                        target="_blank">{{ __('Document') }}</a></span>
                            </div>
                            <div class="form-group">
                                {{ Form::label('actual_domain', __('Actual Domain'), ['class' => 'form-label']) }}
                                {!! Form::text('actual_domain', null, [
                                    'class' => 'form-control',
                                    'required',
                                    'placeholder' => __('Actual domain'),
                                ]) !!}
                            </div>

                            @if ($dbpermission == null)
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
                                    {{ Form::label('db_password', __('Database Password:'), ['class' => 'form-label']) }}
                                    <div class="input-group-prepend">
                                    </div>
                                    {!! Form::password('db_password', [
                                        'class' => 'form-control',
                                        ' required',
                                        'placeholder' => __('Enter database password'),
                                    ]) !!}
                                </div>
                            @endif

                        </div>
                        <div class="card-footer">
                            <div class="float-end">
                                <a href="{{ route('users.index') }}" class="btn btn-secondary">{{ __('Cancel') }}</a>
                                {{ Form::button(__('Save'), ['type' => 'submit', 'class' => 'btn btn-primary']) }}
                            </div>
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
