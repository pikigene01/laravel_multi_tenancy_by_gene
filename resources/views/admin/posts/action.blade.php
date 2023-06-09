@can('edit-blog')
    <a class="btn btn-sm small btn btn-warning " href="{{ route('blogs.edit', $post->id) }}"
        data-bs-toggle="tooltip" data-bs-placement="bottom" title="" data-bs-original-title="{{ __('Edit') }}"
        aria-label="{{ __('Edit') }}"><i class="ti ti-edit text-white"></i></a>
@endcan
@can('delete-blog')
    {!! Form::open([
        'method' => 'DELETE',
        'class' => 'd-inline',
        'route' => ['blogs.destroy', $post->id],
        'id' => 'delete-form-' . $post->id,
    ]) !!}
    <a href="#" class="btn btn-sm small btn btn-danger show_confirm" data-bs-toggle="tooltip"
        data-bs-placement="bottom" title="" id="delete-form-1" data-bs-original-title="{{ __('Delete') }}"
        aria-label="{{ __('Delete') }}"><i class="ti ti-trash text-white"></i></a>
    {!! Form::close() !!}
@endcan
