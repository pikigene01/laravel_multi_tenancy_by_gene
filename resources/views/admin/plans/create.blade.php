@extends('layouts.main')
@section('title', __('Create MyPlan'))
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('plans.myplan') }}">{{ __('MyPlans') }}</a></li>
    <li class="breadcrumb-item">{{ __('Create MyPlan') }}</li>
@endsection
@section('content')
    <div class="main-content">
        <section class="section">
            <div class="col-sm-4 m-auto">
                <div class="card">
                    <div class="card-header">
                        <h5>{{ __('Create MyPlan') }}</h5>
                    </div>
                    <div class="card-body">
                        {!! Form::open([
                            'route' => 'plans.store',
                            'method' => 'Post',
                            'enctype' => 'multipart/form-data',
                            'data-validate',
                        ]) !!}
                        <div class="form-group">
                            {{ Form::label('name', __('Name'), ['class' => 'form-label']) }}
                            {!! Form::text('name', null, ['placeholder' => __('Enter name'), 'class' => 'form-control', 'required']) !!}
                        </div>
                        <div class="form-group">
                            {{ Form::label('price', __('Price'), ['class' => 'form-label']) }}
                            {!! Form::text('price', null, ['placeholder' => __('Enter price'), 'class' => 'form-control', 'required']) !!}
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    {{ Form::label('duration', __('Duration'), ['class' => 'form-label']) }}
                                    {!! Form::number('duration', null, [
                                        'placeholder' => __('Enter duration'),
                                        'class' => 'form-control',
                                        'required',
                                    ]) !!}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    {{ Form::label('duration', __('Duration'), ['class' => 'form-label']) }}
                                    {!! Form::select('durationtype', ['Month' => 'Month', 'Year' => 'Year'], 'Month', [
                                        'class' => 'form-control',
                                        'required',
                                        'data-trigger',
                                    ]) !!}
                                </div>
                            </div>
                        </div>
                        @if (Auth::user()->type != 'Super Admin')
                            <div class="form-group">
                                {{ Form::label('max_users', __('Maximum users'), ['class' => 'form-label']) }}
                                {!! Form::number('max_users', null, [
                                    'placeholder' => __('Enter maximum users'),
                                    'class' => 'form-control',
                                    'required',
                                ]) !!}
                            </div>
                        @endif
                        <div class="form-group">
                            {{ Form::label('description', __('Description'), ['class' => 'form-label']) }}
                            {!! Form::text('description', null, [
                                'placeholder' => __('Enter description'),
                                'class' => 'form-control',
                            ]) !!}
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="float-end">
                            <a href="{{ route('plans.myplan') }}" class="btn btn-secondary">{{ __('Cancel') }}</a>
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
@endpush
