@extends('layouts.main')
@section('title', __('Create Faq'))
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('faqs.index') }}">{{ __('Faqs') }}</a></li>
    <li class="breadcrumb-item">{{ __('Create Faq') }}</li>
@endsection
@section('content')
    <div class="main-content">
        <section class="section">
            <div class="section-body">
                <div class="col-sm-6 m-auto">
                    <div class="card">
                        <div class="card-header">
                            <h5>{{ __('Create Faq') }}</h5>
                        </div>
                        <div class="card-body">
                            {!! Form::open(['route' => 'faqs.store', 'method' => 'Post', 'enctype' => 'multipart/form-data','data-validate']) !!}
                            <div class="form-group">
                                {{ Form::label('quetion', __('Quetion'), ['class' => 'form-label']) }}
                                {!! Form::text('quetion', null, ['class' => 'form-control', ' required', 'placeholder' => __('Enter quetion')]) !!}
                            </div>
                            <div class="form-group">
                                {{ Form::label('answer', __('Answer'), ['class' => 'form-label']) }}
                                {!! Form::textarea('answer', null, ['class' => 'form-control', ' required', 'data-trigger', 'placeholder' => __('Enter answer address')]) !!}
                            </div>
                            <div class="form-group">
                                {{ Form::label('order', __('Order'),['class'=>'form-label']) }}
                                {!! Form::number('order', null, ['placeholder' => __('Enter order'), 'class' => 'form-control', 'required']) !!}
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="text-end">
                                <a href="{{ route('faqs.index') }}" class="btn btn-secondary">{{ __('Cancel') }}</a>
                                {{ Form::button(__('Save'),['type' => 'submit', 'class' => 'btn btn-primary'] )  }}
                            </div>
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
@push('javascript')
    <script>
        CKEDITOR.replace('answer', {
            filebrowserUploadUrl: "{{ route('ckeditor.upload', ['_token' => csrf_token()]) }}",
            filebrowserUploadMethod: 'form'
        });
    </script>
@endpush
