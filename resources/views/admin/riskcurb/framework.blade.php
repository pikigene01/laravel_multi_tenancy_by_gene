@extends('layouts.main')
@section('title', __('RiskCurb'))
@push('css')
    <style type="text/css">
        .risk-border-tiny {
            border: 1px solid black;
            height: 80vh;
            overflow: auto;
        }

        .risk-border-huge {
            border: 2px solid black;
            height: 80vh;
            margin: 0px 10px;
        }

        .risk-item {
            padding: 0px 10px;
            cursor: pointer;
        }

        .risk-header {
            width: 100%;
            background: #ccc;
            padding: 10px 20px
        }
        .risk-menus{
            display: flex;
            flex-direction: column;
        }
        .risk-menus li{
            padding: 10px 0px;
            border-bottom: 2px solid #ccc;
            cursor: pointer;
        }
    </style>
@endpush

@section('content')
    <div class="row">


        <div class="col-md-12">
            <div class="row">
                <div class="col-md-8 risk-border-huge card">
                    <div class="card-body">
                        <div style="display: flex;" class="risk-header">
                            <div class="risk-item">
                                Basic Progress
                            </div>
                            <div class="risk-item">
                                Context
                            </div>
                            <div class="risk-item">
                                Criteria
                            </div>
                            <div class="risk-item">
                                Scope
                            </div>
                            <div class="risk-item">
                                Framework
                            </div>
                        </div>

                        <h3>{{ $content }}</h3>

                    </div>
                </div>
                <div class="col-md-3 card">
                    <div class="card-header">
                        Risk Bot
                    </div>
                    <div class="card-body" style="height: 50%;">
                        {!! Form::open([
                            'route' => 'framework.create',
                            'method' => 'Post',
                            'enctype' => 'multipart/form-data',
                            'data-validate',
                        ]) !!}
                        <div class={{ 'form-group ' }} style={{ $step == '1' ? '' : 'display:none;' }}>
                            {{ Form::label('organization', __('Organization'), ['class' => 'form-label']) }}

                            {!! Form::text('organization', Utility::check_null($data ? $data->organization : ''), [
                                'placeholder' => __('Enter your organization name'),
                                'class' => 'form-control',
                                '',
                            ]) !!}

                        </div>

                        <div class="form-group" style={{ $step == '2' ? '' : 'display:none;' }}>

                            {{ Form::label('organization_type', __('Organization Type'), ['class' => 'form-label']) }}

                            {!! Form::text('organization_type', Utility::check_null($data ? $data->organization_type : ''), [
                                'placeholder' => __('Enter your organization type'),
                                'class' => 'form-control',
                                '',
                            ]) !!}

                        </div>

                        <div style={{ $step == '3' ? '' : 'display:none;' }}>
                            <div class="form-group">
                                {{ Form::label('city', __('city'), ['class' => 'form-label']) }}

                                {!! Form::text('city', Utility::check_null($data ? $data->city : ''), [
                                    'placeholder' => __('Enter your city'),
                                    'class' => 'form-control',
                                    '',
                                ]) !!}

                            </div>
                            <div class="form-group">
                                {{ Form::label('state', __('State'), ['class' => 'form-label']) }}

                                {!! Form::text(
                                    'state',
                                    Utility::check_null($data ? $data->state : ''),

                                    ['placeholder' => __('Enter your state'), 'class' => 'form-control', ''],
                                ) !!}

                            </div>
                            <div class="form-group">
                                {{ Form::label('country', __('Country'), ['class' => 'form-label']) }}

                                {!! Form::text(
                                    'country',
                                    Utility::check_null($data ? $data->country : ''),

                                    ['placeholder' => __('Enter your Country'), 'class' => 'form-control', ''],
                                ) !!}

                            </div>

                        </div>

                        <div class="form-group" style={{ $step == '4' ? '' : 'display:none;' }}>
                            {{ Form::label('assets', __('Assets'), ['class' => 'form-label']) }}

                            {!! Form::text(
                                'assets',
                                Utility::check_null($data ? $data->assets : ''),

                                ['placeholder' => __('Enter your organization assets'), 'class' => 'form-control', ''],
                            ) !!}

                        </div>

                        <div class="form-group" style={{ $step == '5' ? '' : 'display:none;' }}>
                            {{ Form::label('products', __('Products'), ['class' => 'form-label']) }}

                            {!! Form::text(
                                'products',
                                Utility::check_null($data ? $data->products : ''),

                                ['placeholder' => __('Enter your organization products'), 'class' => 'form-control', ''],
                            ) !!}

                        </div>


                        <div class="form-group" style={{ $step == '6' ? '' : 'display:none;' }}>
                            {{ Form::label('services', __('Services'), ['class' => 'form-label']) }}

                            {!! Form::text(
                                'services',
                                Utility::check_null($data ? $data->services : ''),

                                ['placeholder' => __('Enter your services'), 'class' => 'form-control', ''],
                            ) !!}

                        </div>

                        <div class="form-group" style={{ $step == '7' ? '' : 'display:none;' }}>
                            {{ Form::label('structure_type', __('Structure type'), ['class' => 'form-label']) }}

                            {!! Form::text(
                                'structure_type',
                                Utility::check_null($data ? $data->structure_type : ''),

                                ['placeholder' => __('Enter your structure type'), 'class' => 'form-control', ''],
                            ) !!}

                        </div>


                        <div class="form-group" style={{ $step == '8' ? '' : 'display:none;' }}>
                            {{ Form::label('components', __('Components'), ['class' => 'form-label']) }}

                            {!! Form::text(
                                'components',
                                Utility::check_null($data ? $data->components : ''),

                                ['placeholder' => __('Components'), 'class' => 'form-control', ''],
                            ) !!}

                        </div>


                        <div class="form-group" style={{ $step == '9' ? '' : 'display:none;' }}>
                            {{ Form::label('customer_types', __('List Customer types'), ['class' => 'form-label']) }}

                            {!! Form::text(
                                'customer_types',
                                Utility::check_null($data ? $data->customer_types : ''),

                                ['placeholder' => __('Enter your customer types'), 'class' => 'form-control', ''],
                            ) !!}

                        </div>


                        <div class={{ 'form-group' }} style={{ $step == '10' ? '' : 'display:none;' }}>
                            {{ Form::label('stakeholders', __('Stakeholders'), ['class' => 'form-label']) }}

                            {!! Form::text(
                                'stakeholders',
                                Utility::check_null($data ? $data->stakeholders : ''),

                                ['placeholder' => __('Enter your stakeholders'), 'class' => 'form-control', ''],
                            ) !!}

                        </div>

                        <div class="form-group" style={{ $step == '11' ? '' : 'display:none;' }}>
                            {{ Form::label('workers', __('Workers'), ['class' => 'form-label']) }}

                            {!! Form::text(
                                'workers',
                                Utility::check_null($data ? $data->workers : '0'),

                                ['placeholder' => __('Enter your workers'), 'class' => 'form-control', ''],
                            ) !!}

                        </div>

                    </div>
                    <div class="card-footer">

                        <div class="form-group">
                            <div class="float-end">
                                <a href="{{ route('riskcurbKeysCancel') }}"
                                    class="btn btn-secondary">{{ __('Start') }}</a>
                                @if ($step >= '11')
                                    {{ Form::button(__('Finish'), ['type' => 'submit', 'class' => 'btn btn-primary']) }}
                                @else
                                    {{ Form::button(__('send'), ['type' => 'submit', 'class' => 'btn btn-primary']) }}
                                @endif
                            </div>
                            {!! Form::close() !!}
                        </div>

                    </div>

                    {!! Form::close() !!}
                </div>
            </div>

        </div>
    </div>
    </div>
@endsection
@push('javascript')
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/marked" defer></script>
    <script></script>
@endpush
