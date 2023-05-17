
@extends('layouts.main')
@section('title', __('RiskCurb Dashboard'))
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
<h2>Our RiskCurb App comes here</h2>
        </div>
    </div>
@endsection
@push('javascript')
    <script>

    </script>
@endpush
