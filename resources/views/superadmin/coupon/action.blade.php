@if ($coupon->is_active != 1)
    <a class="btn btn-sm small btn btn-success " href="coupon-status/{{ $coupon->id }}" data-bs-toggle="tooltip"
        data-bs-placement="bottom" title="" data-bs-original-title="{{ __('Active') }}" aria-label="{{ __('Active') }}"><i
            class="ti ti-checks text-white"></i></a>
@else
    <a class="btn btn-sm small btn btn-danger " href="coupon-status/{{ $coupon->id }}" data-bs-toggle="tooltip"
        data-bs-placement="bottom" title="" data-bs-original-title="{{ __('Deactive') }}"
        aria-label="{{ __('Deactive') }}"><i class="ti ti-ban text-white"></i></a>
@endif
<a class="btn btn-sm small btn btn-info " href="{{ route('coupons.show', ['id' => $coupon->id]) }}"
    data-bs-toggle="tooltip" data-bs-placement="bottom" title="" data-bs-original-title="{{ __('Show') }}"
    aria-label="{{ __('Show') }}"><i class="ti ti-eye text-white"></i></a>

<a class="btn btn-sm small btn btn-warning " href="{{ route('coupon.edit', $coupon->id) }}"
    data-bs-toggle="tooltip" data-bs-placement="bottom" title="" data-bs-original-title="{{ __('Edit') }}"
    aria-label="{{ __('Edit') }}"><i class="ti ti-edit text-white"></i></a>

{!! Form::open([
    'method' => 'DELETE',
    'class' => 'd-inline',
    'route' => ['coupon.destroy', $coupon->id],
    'id' => 'delete-form-' . $coupon->id,
]) !!}
<a href="#" class="btn btn-sm small btn btn-danger show_confirm" data-bs-toggle="tooltip"
    data-bs-placement="bottom" title="" id="delete-form-1" data-bs-original-title="{{ __('Delete') }}" aria-label="{{ __('Delete') }}"><i
        class="ti ti-trash text-white"></i></a>
{!! Form::close() !!}
