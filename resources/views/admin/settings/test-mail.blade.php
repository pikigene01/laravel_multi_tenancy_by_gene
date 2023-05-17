{!! Form::open([
    'route' => 'test.send.mail',
    'method' => 'Post',
    'enctype' => 'multipart/form-data',
    'data-validate',
]) !!}
<div class="modal-body">
    <div class="row">
        <div class="form-group col-12 ">
            <label class="form-label" class="form-label" for="email">{{ __('Email') }}</label>
            <input type="text" name="email" class="form-control" placeholder="{{ __('Enter email') }}" required>
            @error('email')
                <span class="invalid-email" role="alert">
                    <strong class="text-danger">{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>
</div>
<div class="modal-footer">
    <div class="text-end">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
        <button class="btn btn-primary" type="submit" id="save-btn">{{ __('Send') }}</button>
    </div>
</div>
{!! Form::close() !!}
