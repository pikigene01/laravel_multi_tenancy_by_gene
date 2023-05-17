@can('edit-support-ticket')
    <a class="btn btn-sm small btn btn-warning " href="{{ route('support-ticket.edit', $support_ticket->id) }}"
        data-bs-toggle="tooltip" data-bs-placement="bottom" title="" data-bs-original-title="{{ __('Replay') }}"
        aria-label="{{ __('Replay') }}"><i class="ti ti-corner-up-left text-white"></i></a>
@endcan
@can('delete-support-ticket')
    {!! Form::open([
        'method' => 'DELETE',
        'class' => 'd-inline',
        'route' => ['support-ticket.destroy', $support_ticket->id],
        'id' => 'delete-form-' . $support_ticket->id,
    ]) !!}
    <a href="#" class="btn btn-sm small btn btn-danger show_confirm" data-bs-toggle="tooltip" data-bs-placement="bottom"
        title="" id="delete-form-1" data-bs-original-title="{{ __('Delete') }}" aria-label="{{ __('Delete') }}"><i
            class="ti ti-trash text-white"></i></a>
    {!! Form::close() !!}
@endcan

