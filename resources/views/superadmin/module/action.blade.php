<a class="btn btn-sm small btn btn-warning " href="{{ route('modules.edit', $module->id) }}" data-bs-toggle="tooltip"
    data-bs-placement="bottom" title="" data-bs-original-title="{{ __('Edit') }}"
    aria-label="{{ __('Edit') }}"><i class="ti ti-edit text-white"></i></a>

{!! Form::open([
    'method' => 'DELETE',
    'class' => 'd-inline',
    'route' => ['modules.destroy', $module->id],
    'id' => 'delete-form-' . $module->id,
]) !!}
<a href="#" class="btn btn-sm small btn btn-danger show_confirm" data-bs-toggle="tooltip"
    data-bs-placement="bottom" title="" id="delete-form-1" data-bs-original-title="{{ __('Delete') }}"
    aria-label="{{ __('Delete') }}"><i class="ti ti-trash text-white"></i></a>
{!! Form::close() !!}
