<script>
    @if (session('failed'))
        notifier.show('Failed!', '{{ session('failed') }}', 'danger',
        '{{ asset('assets/images/notification/high_priority-48.png') }}', 4000);
    @endif
    @if ($errors = session('errors'))
            @if (is_object($errors))
                @foreach ($errors->all() as $error)
                    notifier.show('Error!', '{{ $error }}', 'danger',
                        '{{ asset('assets/images/notification/high_priority-48.png') }}', 4000);
                @endforeach
            @else
                notifier.show('Error!', '{{ session('errors') }}', 'danger',
                    '{{ asset('assets/images/notification/high_priority-48.png') }}', 4000);
            @endif
        @endif
    @if (session('successful'))
        notifier.show('Successfully!', '{{ session('successful') }}', 'success',
        '{{ asset('assets/images/notification/ok-48.png') }}', 4000);
    @endif
    @if (session('success'))
        notifier.show('Success!', '{{ session('success') }}', 'success',
        '{{ asset('assets/images/notification/ok-48.png') }}', 4000);
    @endif
    @if (session('warning'))
        notifier.show('Warning!', '{{ session('warning') }}', 'warning',
        '{{ asset('assets/images/notification/medium_priority-48.png') }}', 4000);
    @endif

    @if (session('status'))
        notifier.show('Great!', '{{ session('status') }}', 'info',
        '{{ asset('assets/images/notification/survey-48.png') }}', 4000);
    @endif
</script>


