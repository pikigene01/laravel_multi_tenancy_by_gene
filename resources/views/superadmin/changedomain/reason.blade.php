{!! Form::model($requestdomain, [
    'route' => ['domain.update', $requestdomain->id],
    'method' => 'post',
    'enctype' => 'multipart/form-data',
    'data-validate',
]) !!}
<div class="modal-body">
    <div class="form-group">
        {{ Form::label('reason', __('Disapprove Reason'), ['class' => 'form-label']) }}
        {!! Form::textarea('reason', null, [
            'class' => 'form-control',
            ' required',
            'placeholder' => __('Enter disapprove reason'),
        ]) !!}
    </div>
</div>
<div class="modal-footer">
    <div class="btn-flt float-end">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
        <button type="sub" class="btn btn-primary">{{ __('Save') }}</button>
    </div>
</div>
{!! Form::close() !!}
