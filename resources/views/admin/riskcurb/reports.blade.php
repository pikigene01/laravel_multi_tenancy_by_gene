@extends('layouts.main')
@section('title', __('RM Reports'))
@push('css')
@endpush

@section('content')
    <div class="row">


        <div class="col-md-12">
             <h3>{{$content}}</h3>
        </div>
    </div>
@endsection
@push('javascript')
<script src="https://cdn.tailwindcss.com"></script>
<script src="https://unpkg.com/marked" defer></script>
    <script></script>
@endpush
