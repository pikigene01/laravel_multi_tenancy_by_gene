@extends('layouts.main')
@section('title', __('Create User'))
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('users.index') }}">{{ __('Users') }}</a></li>
    <li class="breadcrumb-item">{{ __('Create User') }}</li>
@endsection
@section('content')
    <div class="main-content">
        <section class="section">
            <div class="section-body">
                @if (tenant('id') == null)
                    <div class="alert alert-warning">
                        {{ __('Your database user must have permission to CREATE DATABASE, because we need to create database when new tenant create.') }}
                    </div>
                @endif
                <div class="col-sm-4 m-auto">
                    <div class="card">
                        <div class="card-header">
                            <h5>{{ __('Create User') }}</h5>
                        </div>
                        <div class="card-body">
                            {!! Form::open(['route' => 'users.store', 'method' => 'Post', 'enctype' => 'multipart/form-data','data-validate']) !!}
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
                                {{ Form::label('roles', __('Role'), ['class' => 'form-label']) }}
                                {!! Form::select('roles', $roles, null, ['class' => 'form-control','required', 'data-trigger']) !!}
                            </div>
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
<script src="{{ asset('assets/js/plugins/choices.min.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var genericExamples = document.querySelectorAll('[data-trigger]');
        for (i = 0; i < genericExamples.length; ++i) {
            var element = genericExamples[i];
            new Choices(element, {
                placeholderValue: 'This is a placeholder set in the config',
                searchPlaceholderValue: 'This is a search placeholder',
            });
        }
    });
</script>
