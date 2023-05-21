@extends('layouts.main')
@section('title', __('RM Reports'))
@push('css')
    <style type="text/css">
        .risk-border-tiny {
            border: 1px solid black;
            height: 80vh;
            overflow: auto;
        }

        .risk-border-huge {
            border: 2px solid black;
            height: 80vh;
            margin: 0px 10px;
        }

        .risk-item {
            padding: 0px 10px;
            cursor: pointer;
        }

        .risk-header {
            width: 100%;
            background: #ccc;
            padding: 10px 20px
        }
    </style>
@endpush

@section('content')
    <div class="row">


        <div class="col-md-12">


        </div>
    </div>
    </div>
@endsection
@push('javascript')
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/marked" defer></script>
    <script></script>
@endpush
