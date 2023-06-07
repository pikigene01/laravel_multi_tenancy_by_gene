@extends('layouts.main')
@section('title', __('Risk Dashboard'))
@push('css')
@endpush

@section('content')
    <div class="row">

        @if (tenant('id') != null && isset($plan_expired_date))
            <div class="col-xl-3 col-6">
                <a href="plans">
                    <div class="card comp-card">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col">
                                    <h6 class="m-b-20">{{ __('Plan Expired Date') }}</h6>
                                    <h3 class="text-primary">{{ Carbon::parse($plan_expired_date)->format('d/m/Y') }}</h3>
                                </div>
                                <div class="col-auto">
                                    <i class="ti ti-calendar bg-success text-white"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        @endif

        <div class="col-md-12">
            <h2>Our Risk App comes here</h2>

            <div
                class="relative flex items-top justify-center min-h-screen bg-gray-100 dark:bg-gray-900 sm:items-center py-4 sm:pt-0">
                <div class="max-w-6xl w-full mx-auto sm:px-6 lg:px-8 space-y-4 py-4">


                    <div class="w-full rounded-md bg-white border-2 border-gray-600 p-4 min-h-[60px] h-full text-gray-600">
                        <form action="/risks/generate" method="post" class="inline-flex gap-2 w-full">
                            @csrf
                            <input required name="title" class="w-full outline-none text-2xl font-bold"
                                placeholder="Type your organization info...." />
                            <button class="rounded-md bg-emerald-500 px-4 py-2 text-white font-semibold">Generate</button>
                        </form>
                    </div>
                    <div class="w-full rounded-md bg-white border-2 border-gray-600 p-4 min-h-[720px] h-full text-gray-600">
                        <textarea class="min-h-[720px] h-full w-full outline-none" spellcheck="false">{{ $content }}</textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('javascript')
<script src="https://cdn.tailwindcss.com"></script>
<script src="https://unpkg.com/marked" defer></script>
    <script></script>
@endpush
