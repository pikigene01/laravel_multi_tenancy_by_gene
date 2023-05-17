{!! Form::open([
    'route' => 'coupon.upload.store',
    'method' => 'Post',
    'class' => 'form-horizontal',
    'enctype' => 'multipart/form-data',
    'data-validate',
]) !!}
<div class="modal-body">
    <div class="row">
        <div class="col-md-12 mb-4">
            <a href="{{ Storage::url('coupon/coupon.csv') }}" class="btn btn-primary btn-sm"><i class="ti ti-download"></i>
                {{ __('Sample File') }}</a>
        </div>
        <div class="form-group">
            {{ Form::label('file', __('CSV Upload'), ['class' => 'form-label']) }}
            {!! Form::file('file', [
                'class' => 'form-control font-style',
                'placeholder' => __('CSV Upload'),
                'required' => 'required',
            ]) !!}
        </div>
    </div>
</div>
<div class="modal-footer">
    <div class="text-end">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
        <button class="btn btn-primary" type="submit" id="save-btn">{{ __('Save') }}</button>
    </div>
</div>
{{ Form::close() }}
