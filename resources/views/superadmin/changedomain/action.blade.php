@if ($requestdomain->status == 0)
    {!! Form::open([
        'method' => 'POST',
        'class' => 'd-inline',
        'route' => ['changedomain.approve', $requestdomain->id],
        'id' => 'delete-form-' . $requestdomain->id,
    ]) !!}
    <a class="btn btn-sm small btn btn-success show_confirm " data-bs-toggle="tooltip" data-bs-placement="bottom"
        title="" data-bs-original-title="{{ __('Approved') }}" aria-label="{{ __('Approved') }}"><i
            class="ti ti-access-point text-white"></i></a>
    {!! Form::close() !!}

    <a class="btn btn-sm small btn btn-danger reason "
        data-url="/change-domain/disapprove/{{ $requestdomain->id }}" href="javascript:void(0)" data-bs-toggle="tooltip"
        data-bs-placement="bottom" title="" data-bs-original-title="{{ __('Disapproved') }}"
        aria-label="{{ __('Disapproved') }}"><i class="ti ti-access-point-off text-white"></i></a>
@endif
