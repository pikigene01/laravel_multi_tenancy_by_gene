
@extends('layouts.main')
@section('title', __('RiskCurb Admin Prompts'))
@push('css')
@endpush

@section('content')

    <div class="row">

        <div class="col-md-12">
            <h2>RiskCurb Admin Prompts</h2>

            <section class="section">
                <div class="col-sm-4 m-auto">
                    <div class="card">
                        <div class="card-header">
                            <h5>{{ __('Admin Create Prompts Per Section') }}</h5>
                            <p><span class="text-primary">Variables to add inside your prompt per section</span>: $organization, $organization_type, $city, $state, $country, $assets, $products, $services, $structure_type, $components, $customer_types, $stakeholders && $workers.</p>
                        </div>
                        <div class="card-body">
                            {!! Form::open([
                                'route' => 'AdminpromptsSave',
                                'method' => 'Post',
                                'enctype' => 'multipart/form-data',
                                'data-validate',
                            ]) !!}

                        <div class="form-group">
                            {{ Form::label('section', __('Section'), ['class' => 'form-label']) }}

                            {!! Form::select('section', ['context' => 'Context', 'criteria' => 'Criteria', 'scope' => 'Scope', 'framework' => 'Framework'], 'context', [
                                        'class' => 'form-control sectionKeyInput',
                                        'required',
                                        'data-trigger',
                                    ]) !!}

                        </div>
                            <div class="form-group">
                                {{ Form::label('prompt', __('Add Prompt'), ['class' => 'form-label']) }}

                                @if($prompt)

                                {!! Form::textarea('prompt', $prompt , ['placeholder' => __('Add Prompt'), 'class' => 'form-control promptKeyInput', 'required']) !!}

                                @else

                                {!! Form::textarea('prompt', null , ['placeholder' => __('Add Prompt'), 'class' => 'form-control', 'required']) !!}

                                @endif


                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="float-end">
                                <a href="{{ route('Adminprompts') }}" class="btn btn-secondary">{{ __('Cancel') }}</a>
                                  @if($isNew)
                                  {{ Form::button(__('Add Prompt'), ['type' => 'submit', 'class' => 'btn btn-primary promptBtn']) }}
                                 @else
                                 {{ Form::button(__('Update Prompt'), ['type' => 'submit', 'class' => 'btn btn-primary promptBtn']) }}
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
    const promptKeyInput = document.querySelector('.promptKeyInput');
    const sectionKeyInput = document.querySelector('.sectionKeyInput');

    sectionKeyInput.oninput =(e)=>{
       let value = e.target.value;
    //    alert($('.sectionKeyInput').val());
       $.ajax({
                url: "{{ route('AdminpromptsApi') }}",
                type: 'POST',
                data: {
                    data: {section: sectionKeyInput.value, prompt: promptKeyInput.value},
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(data) {
                    let response = JSON.parse(data);

                    promptKeyInput.value = response.prompt;
                    if(!response.isNew){
                        $('.promptBtn').html('Update Prompt');
                    }else{
                        $('.promptBtn').html('Add Prompt');
                    }
                },
                error: function(data) {}
            });
    }

    // promptKeyInput.value = '';
    </script>
@endpush
