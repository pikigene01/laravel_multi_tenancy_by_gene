@extends('layouts.main')
@section('title', __('Landing Page'))
@section('breadcrumb')
    <ul class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Dashboard') }}</a></li>
        <li class="breadcrumb-item">{{ __('Landing Page') }}</li>
    </ul>
@endsection
@section('content')

    <div class="main-content">
        <section class="section">
            <div class="col-sm-6 m-auto">
                <div class="card">
                    <div class="card-header">
                        <h5>{{ __('Landing Page') }}</h5>
                    </div>
                    {!! Form::open([
                        'route' => 'landing.page.store',
                        'method' => 'Post',
                        'enctype' => 'multipart/form-data',
                        'data-validate',
                    ]) !!}
                    <div class="card-body">
                        <div class="form-group">
                            {{ Form::label('privacy', __('Privacy Page Content'), ['class' => 'col-form-label']) }}
                            {!! Form::textarea('privacy', Utility::getsettings('privacy'), [
                                'class' => 'form-control',
                                'placeholder' => __('Enter privacy page content'),
                            ]) !!}
                        </div>
                        <div class="form-group ">
                            {{ Form::label('contact_us', __('Contact Us Page Content'), ['class' => 'col-form-label']) }}
                            {!! Form::textarea('contact_us', Utility::getsettings('contact_us'), [
                                'class' => 'form-control',
                                'placeholder' => __('Enter contact us page content'),
                            ]) !!}
                        </div>
                        <div class="form-group ">
                            {{ Form::label('term_condition', __('Term & Condition page Content'), ['class' => 'col-form-label']) }}
                            {!! Form::textarea('term_condition', Utility::getsettings('term_condition'), [
                                'class' => 'form-control',
                                'placeholder' => __('Enter term & condition page content'),
                            ]) !!}
                        </div>
                        <div class="form-group">
                            {{ Form::label('faq_page_content', __('FAQ'), ['class' => 'col-form-label']) }}
                            {!! Form::textarea('faq_page_content', Utility::getsettings('faq_page_content'), [
                                'class' => 'form-control',
                                'placeholder' => __('Enter faq content'),
                            ]) !!}
                        </div>
                        <div class="form-group">
                            {{ Form::label('contact_us_email', __('Contact Email'), ['class' => 'col-form-label']) }}
                            {!! Form::text('contact_us_email', Utility::getsettings('contact_us_email'), [
                                'class' => 'form-control',
                                'placeholder' => __('Enter contact email'),
                            ]) !!}
                            <p>{{ __('This email is for receive email when user submit contact us form.') }}</p>
                        </div>
                        <div class="form-group">
                            {{ Form::label('latitude', __('Latitude'), ['class' => 'col-form-label']) }}
                            {!! Form::text('latitude', Utility::getsettings('latitude'), [
                                'class' => 'form-control',
                                'placeholder' => __('Enter latitude'),
                            ]) !!}
                        </div>
                        <div class="form-group">
                            {{ Form::label('longitude', __('Longitude'), ['class' => 'col-form-label']) }}
                            {!! Form::text('longitude', Utility::getsettings('longitude'), [
                                'class' => 'form-control',
                                'placeholder' => __('Enter longitude'),
                            ]) !!}
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group row">
                                <div class="col-md-8">
                                    <label for="captcha_status">{{ __('Captcha Status') }}</label>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-switch mt-2 float-end custom-switch-v1">
                                        <input type="checkbox" name="captcha_status" class="form-check-input input-primary"
                                            id="captcha_status"
                                            {{ Utility::getsettings('captcha_status') ? 'checked' : 'unchecked' }}>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div id="captcha_setting"
                            class="{{ Utility::getsettings('captcha_status') == 0 ? 'd-none' : 'd-block' }}">
                            <div class="form-group">
                                {{ Form::label('recaptcha_key', __('Recaptcha Key'), ['class' => 'col-form-label']) }}
                                {!! Form::text('recaptcha_key', Utility::getsettings('recaptcha_key'), [
                                    'class' => 'form-control',
                                    'placeholder' => __('Enter recaptcha key'),
                                ]) !!}
                            </div>
                            <div class="form-group">
                                {{ Form::label('recaptcha_secret', __('Recaptcha Secret'), ['class' => 'col-form-label']) }}
                                {!! Form::text('recaptcha_secret', Utility::getsettings('recaptcha_secret'), [
                                    'class' => 'form-control',
                                    'placeholder' => __('Enter recaptcha secret'),
                                ]) !!}
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="text-end">
                            <a href="{{ route('home') }}" class="btn btn-secondary">{{ __('Cancel') }}</a>
                            {{ Form::button(__('Save'), ['type' => 'submit', 'class' => 'btn btn-primary']) }}
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </section>
    </div>
@endsection

@push('javascript')
    <script>
        CKEDITOR.replace('footer_page_content', {
            filebrowserUploadUrl: "{{ route('ckeditor.upload', ['_token' => csrf_token()]) }}",
            filebrowserUploadMethod: 'form'
        });
    </script>
    <script>
        CKEDITOR.replace('privacy', {
            filebrowserUploadUrl: "{{ route('ckeditor.upload', ['_token' => csrf_token()]) }}",
            filebrowserUploadMethod: 'form'
        });
    </script>
    <script>
        CKEDITOR.replace('contact_us', {
            filebrowserUploadUrl: "{{ route('ckeditor.upload', ['_token' => csrf_token()]) }}",
            filebrowserUploadMethod: 'form'
        });
    </script>
    <script>
        CKEDITOR.replace('term_condition', {
            filebrowserUploadUrl: "{{ route('ckeditor.upload', ['_token' => csrf_token()]) }}",
            filebrowserUploadMethod: 'form'
        });
    </script>
    <script>
        CKEDITOR.replace('faq_page_content', {
            filebrowserUploadUrl: "{{ route('ckeditor.upload', ['_token' => csrf_token()]) }}",
            filebrowserUploadMethod: 'form'
        });
    </script>
    <script>
        $(document).on('click', "input[name$='captcha_status']", function() {
            if (this.checked) {
                $('#captcha_setting').fadeIn(500);
                $("#captcha_setting").removeClass('d-none');
                $("#captcha_setting").addClass('d-block');
            } else {
                $('#captcha_setting').fadeOut(500);
                $("#captcha_setting").removeClass('d-block');
                $("#captcha_setting").addClass('d-none');
            }
        });
    </script>
@endpush
