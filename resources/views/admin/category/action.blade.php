@if ($category->status != 1)
    <a class="btn btn-sm small btn btn-success " href="category-status/{{ $category->id }}"
        data-bs-toggle="tooltip" data-bs-placement="bottom" title="" data-bs-original-title="{{ __('Active') }}"
        aria-label="{{ __('Active') }}"><i class="ti ti-checks text-white"></i></a>
@else
    <a class="btn btn-sm small btn btn-danger " href="category-status/{{ $category->id }}"
        data-bs-toggle="tooltip" data-bs-placement="bottom" title="" data-bs-original-title="{{ __('Deactive') }}"
        aria-label="{{ __('Deactive') }}"><i class="ti ti-ban text-white"></i></a>
@endif
@can('edit-category')
    <a class="btn btn-sm small btn btn-warning " href="{{ route('category.edit', $category->id) }}"
        data-bs-toggle="tooltip" data-bs-placement="bottom" title="" data-bs-original-title="{{ __('Edit') }}"
        aria-label="{{ __('Edit') }}"><i class="ti ti-edit text-white"></i></a>
@endcan
@can('delete-category')
    {!! Form::open([
        'method' => 'DELETE',
        'class' => 'd-inline',
        'route' => ['category.destroy', $category->id],
        'id' => 'delete-form-' . $category->id,
    ]) !!}
    <a href="#" class="btn btn-sm small btn btn-danger show_confirm" data-bs-toggle="tooltip"
        data-bs-placement="bottom" title="" id="delete-form-1" data-bs-original-title="{{ __('Delete') }}"
        aria-label="{{ __('Delete') }}"><i class="ti ti-trash text-white"></i></a>
    {!! Form::close() !!}
@endcan
