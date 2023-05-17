@php
    use Carbon\Carbon;
    $users = \Auth::user();
    $currantLang = $users->currentLanguage();
    $primary_color = \App\Facades\UtilityFacades::getsettings('color');
    if (isset($primary_color)) {
        $color = $primary_color;
    } else {
        $color = 'theme-1';
    }
    if ($color == 'theme-1') {
        $chatcolor = '#51459D';
    } elseif ($color == 'theme-2') {
        $chatcolor = '#4EBBD3';
    } elseif ($color == 'theme-3') {
        $chatcolor = '#6FD943';
    } else {
        $chatcolor = '#685EE5';
    }
@endphp
@extends('layouts.main')
@section('title', __('Dashboard'))
@section('content')
    <div class="row">
        @if (!$paymenttypes)
            <div class="col-12">
                <div class="alert alert-warning">{{ __('Please set your payment key & payment secret') }} - <a
                        href="{{ url('/settings') }}/#payment_setting">{{ __('Click') }}</a> </div>
            </div>
        @endif
        @can('manage-user')
            <div class="col-xl-3 col-6">
                <a href="users">
                    <div class="card comp-card">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col">
                                    <h6 class="m-b-20">{{ __('Total Admin') }}</h6>
                                    <h3 class="text-primary">{{ $user }}</h3>
                                </div>
                                <div class="col-auto">
                                    <i class="ti ti-users bg-primary text-white"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        @endcan

        @can('manage-plan')
            <div class="col-xl-3 col-6">
                <a href="plans">
                    <div class="card comp-card">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col">
                                    <h6 class="m-b-20">{{ __('Total Plan') }}</h6>
                                    <h3 class="text-primary">{{ $plan }}</h3>
                                </div>
                                <div class="col-auto">
                                    <i class="ti ti-businessplan bg-success text-white"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        @endcan

        @can('manage-langauge')
            <div class="col-xl-3 col-6">
                <a href="{{ route('manage.language', [$currantLang]) }}">
                    <div class="card comp-card">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col">
                                    <h6 class="m-b-20">{{ __('Total Language') }}</h6>
                                    <h3 class="text-primary">{{ $languages }}</h3>
                                </div>
                                <div class="col-auto">
                                    <i class="ti ti-world bg-warning text-white"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        @endcan

        @if (Auth::user()->type == 'Super Admin' || Auth::user()->type == 'Admin')
            <div class="col-xl-3 col-6">
                <div class="card comp-card">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col">
                                <h6 class="m-b-20">{{ __('Total Earning') }}</h6>
                                <h3 class="text-primary">{{ Utility::amount_format($earning) }}</h3>
                            </div>
                            <div class="col-auto">
                                <i class="ti ti-calendar bg-danger text-white"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    @can('manage-user')
        <div class="col">
            <div class="card">
                <div class="text-end">
                    <div class="card-body">
                        <div class="btn-group" role="group">
                            <input type="radio" class="btn-check" id="option1" autocomplete="off" checked=""
                                name="options">
                            <label class="btn btn-light-primary" for="option1">{{ __('Week') }}</label>
                            <input type="radio" class="btn-check" id="option2" autocomplete="off" checked=""
                                name="options">
                            <label class="btn btn-light-primary" for="option2">{{ __('Month') }}</label>
                            <input type="radio" class="btn-check" id="option3" autocomplete="off" name="options">
                            <label class="btn btn-light-primary" for="option3">{{ __('Year') }}</label>
                        </div>
                    </div>
                </div>
                <div class="card-body ">
                    <canvas id="myChart" height="75"></canvas>
                </div>
            </div>
        </div>
    @endcan
@endsection
@push('javascript')
    <script>
        "use strict";
        var statistics_chart = document.getElementById("myChart").getContext('2d');
        var myChart = new Chart(statistics_chart, {
            type: 'line',
            data: {
                labels: [],
                datasets: [{
                    label: '{{ __('Total Earning') }}',
                    data: [],
                    borderWidth: 5,
                    borderColor: '{{ $chatcolor }}',
                    backgroundColor: 'transparent',
                    pointBackgroundColor: '#fff',
                    pointBorderColor: '{{ $chatcolor }}',
                    pointRadius: 4
                }]
            },
            options: {
                legend: {
                    display: false
                },
                scales: {
                    yAxes: [{
                        gridLines: {
                            display: false,
                            drawBorder: false,
                        },
                    }],
                    xAxes: [{
                        gridLines: {
                            color: '#fbfbfb',
                            lineWidth: 2
                        }
                    }]
                },
            }
        })
        getChartData('month');
        $(document).on("click", "#option3", function() {
            getChartData('year');
        });
        $(document).on("click", "#option2", function() {
            getChartData('month');
        });
        $(document).on("click", "#option1", function() {
            getChartData('week');
        });

        function getChartData(type) {
            $.ajax({
                url: "{{ route('get.chart.data') }}",
                type: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    type: type
                },
                success: function(result) {
                    myChart.data.labels = result.lable;
                    myChart.data.datasets[0].data = result.value;
                    myChart.update()
                },
                error: function(data) {}
            });
        }
    </script>
@endpush
