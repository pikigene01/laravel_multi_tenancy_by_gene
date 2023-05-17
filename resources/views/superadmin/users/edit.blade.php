@extends('layouts.main')
@section('title', __('Edit Admin'))
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('users.index') }}">{{ __('Admins') }}</a></li>
    <li class="breadcrumb-item">{{ __('Edit Admin') }}</li>
@endsection
@section('content')
    <div class="main-content">
        <section class="section">

            <div class="col-sm-4 m-auto">
                <div class="card">
                    <div class="card-header">
                        <h5>{{ __('Edit Admin') }}</h5>
                    </div>
                    <div class="card-body">
                        {!! Form::model($user, [
                            'route' => ['users.update', $user->id],
                            'method' => 'Put',
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

                        <div class="form-group " id="domain">
                            {{ Form::label('domains', __('Domain'), ['class' => 'form-label']) }}
                            {!! Form::text('domains', isset($user_domain->domain) ? $user_domain->domain : '', [
                                'class' => 'form-control',
                                'id' => 'domain',
                                ' required',
                                'placeholder' => __('Enter domain name'),
                            ]) !!}
                        </div>
                        <div class="form-group">
                            {{ Form::label('actual_domain', __('Actual Domain'), ['class' => 'form-label']) }}
                            {!! Form::text('actual_domain', isset($user_domain->actual_domain) ? $user_domain->actual_domain : '', [
                                'class' => 'form-control',
                                'required',
                                'placeholder' => __('Enter actual domain'),
                            ]) !!}
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="text-end">
                            <a href="{{ route('users.index') }}" class="btn btn-secondary">{{ __('Cancel') }}</a>
                            {{ Form::button(__('Save'), ['type' => 'submit', 'class' => 'btn btn-primary']) }}
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
@push('javascript')
    <script>
        $(document).on('change', '#role', function() {
            var roles = $(this).val();
            if (roles == 'Super Admin') {
                $('#domain').hide();
                $('#domain').val('');

            } else {
                $('#domain').show();
            }
        });
    </script>
@endpush
