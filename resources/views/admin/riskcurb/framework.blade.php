@extends('layouts.main')
@section('title', __('RM Framework'))
@push('css')
@endpush
@php

@endphp

@section('content')
    <div class="row">


        <div class="col-md-12">
             <section class="section">
                <div class="col-sm-4 m-auto">
                    <div class="card">
                        <div class="card-header">
                            <h5>{{ __('RM Process') }}</h5>
                        </div>
                        <div class="card-body">
                            {!! Form::open([
                                'route' => 'framework.create',
                                'method' => 'Post',
                                'enctype' => 'multipart/form-data',
                                'data-validate',
                            ]) !!}


                            <div class={{"form-group "}}
                            style={{$step == '1' ? "": "display:none;" }}
                            >
                                {{ Form::label('organization', __('Organization'), ['class' => 'form-label']) }}

                                {!! Form::text('organization',
                               Utility::check_null($data ? $data->organization : "")
                                , ['placeholder' => __('Enter your organization name'), 'class' => 'form-control', '']) !!}

                            </div>

                            <div class="form-group"
                            style={{$step == '2' ? "": "display:none;" }}

                            >

                                {{ Form::label('organization_type', __('Organization Type'), ['class' => 'form-label']) }}

                                {!! Form::text('organization_type',
                                Utility::check_null($data ? $data->organization_type: "")
                                , ['placeholder' => __('Enter your organization type'), 'class' => 'form-control', '']) !!}

                            </div>

                          <div
                          style={{$step == '3' ? "": "display:none;" }}>
                            <div class="form-group"
                            >
                                {{ Form::label('city', __('city'), ['class' => 'form-label']) }}

                                {!! Form::text('city',
                                Utility::check_null($data ? $data->city: "")
                                , ['placeholder' => __('Enter your city'), 'class' => 'form-control', '']) !!}

                            </div>
                            <div class="form-group">
                                {{ Form::label('state', __('State'), ['class' => 'form-label']) }}

                                {!! Form::text('state',
                                Utility::check_null($data ? $data->state : "")

                                , ['placeholder' => __('Enter your state'), 'class' => 'form-control', '']) !!}

                            </div>
                            <div class="form-group">
                                {{ Form::label('country', __('Country'), ['class' => 'form-label']) }}

                                {!! Form::text('country',
                                Utility::check_null($data ? $data->country : "")

                                , ['placeholder' => __('Enter your Country'), 'class' => 'form-control', '']) !!}

                            </div>

                          </div>

                            <div class="form-group"
                            style={{$step == '4' ? "": "display:none;" }}

                            >
                                {{ Form::label('assets', __('Assets'), ['class' => 'form-label']) }}

                                {!! Form::text('assets',
                                Utility::check_null($data ? $data->assets: "")

                                , ['placeholder' => __('Enter your organization assets'), 'class' => 'form-control', '']) !!}

                            </div>

                            <div class="form-group"

                            style={{$step == '5' ? "": "display:none;" }}
                            >
                                {{ Form::label('products', __('Products'), ['class' => 'form-label']) }}

                                {!! Form::text('products',
                                Utility::check_null($data ? $data->products: "")

                                , ['placeholder' => __('Enter your organization products'), 'class' => 'form-control', '']) !!}

                            </div>


                            <div class="form-group"
                            style={{$step == '6' ? "": "display:none;" }}

                            >
                                {{ Form::label('services', __('Services'), ['class' => 'form-label']) }}

                                {!! Form::text('services',
                                Utility::check_null($data ? $data->services: "")

                                , ['placeholder' => __('Enter your services'), 'class' => 'form-control', '']) !!}

                            </div>

                            <div class="form-group"
                            style={{$step == '7' ? "": "display:none;" }}

                            >
                                {{ Form::label('structure_type', __('Structure type'), ['class' => 'form-label']) }}

                                {!! Form::text('structure_type',
                                Utility::check_null($data ? $data->structure_type: "")

                                , ['placeholder' => __('Enter your structure type'), 'class' => 'form-control', '']) !!}

                            </div>


                            <div class="form-group"
                            style={{$step == '8' ? "": "display:none;" }}

                            >
                                {{ Form::label('components', __('Components'), ['class' => 'form-label']) }}

                                {!! Form::text('components',
                                Utility::check_null($data ? $data->components: "")

                                , ['placeholder' => __('Components'), 'class' => 'form-control', '']) !!}

                            </div>


                            <div class="form-group"
                            style={{$step == '9' ? "": "display:none;" }}

                            >
                                {{ Form::label('customer_types', __('List Customer types'), ['class' => 'form-label']) }}

                                {!! Form::text('customer_types',
                                Utility::check_null($data ?$data->customer_types:"")

                                , ['placeholder' => __('Enter your customer types'), 'class' => 'form-control', '']) !!}

                            </div>


                            <div class={{"form-group" }}
                            style={{$step == '10' ? "": "display:none;" }}

                            >
                                {{ Form::label('stakeholders', __('Stakeholders'), ['class' => 'form-label']) }}

                                {!! Form::text('stakeholders',
                                Utility::check_null($data ?$data->stakeholders:"")

                                , ['placeholder' => __('Enter your stakeholders'), 'class' => 'form-control', '']) !!}

                            </div>

                            <div class="form-group"
                            style={{$step == '11' ? "": "display:none;" }}

                            >
                                {{ Form::label('workers', __('Workers'), ['class' => 'form-label']) }}

                                {!! Form::text('workers',
                                Utility::check_null($data ?$data->workers:"0")

                                , ['placeholder' => __('Enter your workers'), 'class' => 'form-control', '']) !!}

                            </div>

                        </div>
                        <div class="card-footer">
                            <div class="float-end">
                                <a href="{{ route('riskcurbKeysCancel') }}" class="btn btn-secondary">{{ __('Cancel') }}</a>
                                 @if($step >= '11')
                                  {{ Form::button(__('Finish'), ['type' => 'submit', 'class' => 'btn btn-primary']) }}
                                  @else
                                  {{ Form::button(__('Next'), ['type' => 'submit', 'class' => 'btn btn-primary']) }}

                                  @endif
                            </div>
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </section>

            <h3>{{$content}}</h3>

        </div>
    </div>
@endsection
@push('javascript')
<script src="https://cdn.tailwindcss.com"></script>
<script src="https://unpkg.com/marked" defer></script>
    <script></script>
@endpush
