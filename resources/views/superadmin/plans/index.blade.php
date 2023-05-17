@php
use Carbon\Carbon;

if (Auth::user()->type == 'Admin') {
    $currency_symbol = tenancy()->central(function ($tenant) {
        return Utility::getsettings('currency_symbol');
    });
} else {
    $currency_symbol = Utility::getsettings('currency_symbol');
}
if (Auth::user()->type != 'Admin') {
    $currency = Utility::getsettings('currency');
} else {
    $currency = tenancy()->central(function ($tenant) {
        return Utility::getsettings('currency');
    });
}
$payment_type = [];

@endphp

@extends('layouts.main')
@if (Auth::user()->type == 'Super Admin')
    @section('title', __('Plans'))
@else
    @section('title', __('Pricing'))
@endif
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item">{{ __('Plans') }}</li>
@endsection
@section('content')
    @hasrole('Super Admin')
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive pt-5 pb-2 dropdown_2">
                            <div class="container-fluid">
                                {{ $dataTable->table(['width' => '100%']) }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endhasrole

    @if (Auth::user()->type != 'Super Admin')
        <section id="price" class="price-section">
            <div class="container">
                <div class="row ">
                    @foreach ($plans as $plan)
                        <div class="col-lg-3">
                            <div class="card price-card price-1 wow animate__fadeInUp" data-wow-delay="0.2s"
                                style=" visibility: visible; animation-delay: 0.2s; animation-name: fadeInUp;">
                                <div class="card-body">
                                    <span class="price-badge bg-primary">{{ $plan->name }}</span>
                                    <span class="mb-4 f-w-600 p-price"> {{ $currency_symbol . '' . $plan->price }}<small
                                            class="text-sm">/{{ $plan->duration . ' ' . $plan->durationtype }}</small></span>
                                    <p class="mb-0">
                                    </p>
                                    @if (Auth::user()->type == 'Admin')
                                        <ul class="list-unstyled my-5">
                                            <li>
                                                <span class="theme-avtar">
                                                    </span>
                                            </li>
                                        </ul>
                                    @endif
                                    @if (Auth::user()->type != 'Admin')
                                        <ul class="list-unstyled my-5">
                                            <li>
                                                <span class="theme-avtar">
                                                    <i class="text-primary ti ti-circle-plus"></i></span>
                                                {{ $plan->max_users . ' ' . __('users') }}
                                            </li>
                                        </ul>
                                    @endif

                                    <div class="d-grid text-center">
                                        @if ($plan->id != 1)
                                            <div class="pricing-cta">
                                                @if ($plan->id == $user->plan_id && !empty($user->plan_expired_date))
                                                    <a href="javascript:void(0)" data-id="{{ $plan->id }}"
                                                        class="btn mb-3 btn-primary d-flex justify-content-center align-items-center mx-sm-5"
                                                        data-amount="{{ $plan->price }}">{{ __('Expire at') }}
                                                        {{ Carbon::parse($user->plan_expired_date)->format('d/m/Y') }}</a>
                                                @else
                                                    <a href="javascript:void(0)"
                                                        class="subscribe_plan  btn mb-3 btn-primary d-flex justify-content-center align-items-center mx-sm-5"
                                                        data-id="{{ $plan->id }}" data-currency="{{ $currency }}"
                                                        data-currency_symbol="{{ $currency_symbol }}"
                                                        data-amount="{{ $plan->price }}">{{ __('Subscribe') }}
                                                        <i class="ti ti-chevron-right ms-2"></i></a>
                                                @endif
                                            </div>
                                        @endif

                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
@endsection
@hasrole('Super Admin')
    @push('css')
        @include('layouts.includes.datatable_css')
    @endpush
    @push('javascript')
        @include('layouts.includes.datatable_js')
        {{ $dataTable->scripts() }}
    @endpush
@endhasrole
