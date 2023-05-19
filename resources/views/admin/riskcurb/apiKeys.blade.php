
@extends('layouts.main')
@section('title', __('RiskCurb API Keys'))
@push('css')
@endpush

@section('content')

    <div class="row">

        <div class="col-md-12">
            <h2>RiskCurb Api Keys</h2>

            <section class="section">
                <div class="col-sm-4 m-auto">
                    <div class="card">
                        <div class="card-header">
                            <h5>{{ __('Api Key') }}</h5>
                        </div>
                        <div class="card-body">
                            {!! Form::open([
                                'route' => 'riskcurbKeys',
                                'method' => 'Post',
                                'enctype' => 'multipart/form-data',
                                'data-validate',
                            ]) !!}
                            <div class="form-group">
                                {{ Form::label('apikey', __('apikey'), ['class' => 'form-label']) }}
                                @if($openAi)

                                {!! Form::text('apikey', $openAi->apikey , ['placeholder' => __('Enter api key'), 'class' => 'form-control apiKeyInput', 'required']) !!}

                                @else

                                {!! Form::text('apikey', null , ['placeholder' => __('Enter api key'), 'class' => 'form-control apiKeyInput', 'required']) !!}

                                @endif


                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="float-end">
                                <a href="{{ route('riskcurbKeys') }}" class="btn btn-secondary">{{ __('Cancel') }}</a>
                                   @if($openAi)
                                {{ Form::button(__('Update'), ['type' => 'submit', 'class' => 'btn btn-primary']) }}
                                  @else
                                  {{ Form::button(__('Save'), ['type' => 'submit', 'class' => 'btn btn-primary']) }}

                                  @endif
                            </div>
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
@endsection
@push('javascript')
    <script>
    const apiKeyInput = document.querySelector('.apiKeyInput');

    // apiKeyInput.value = '';
    </script>
@endpush
