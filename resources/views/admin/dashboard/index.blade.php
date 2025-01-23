@php
    $configData = Helper::appClasses();
@endphp

@section('title', 'Dashboard')

@extends('layouts/layoutMaster')

@section('vendor-script')
    <script src="{{ asset('assets/vendor/libs/chartjs/chartjs.js') }}"></script>
@endsection

@section('page-script')
    <script>
        const purpleColor = '#836AF9',
            yellowColor = '#ffe800',
            cyanColor = '#28dac6',
            orangeColor = '#FF8132',
            orangeLightColor = '#FDAC34',
            oceanBlueColor = '#299AFF',
            greyColor = '#4F5D70',
            greyLightColor = '#EDF1F4',
            blueColor = '#2B9AFF',
            blueLightColor = '#84D0FF';

        let colors = [

            orangeColor,
            greyColor,
            blueColor,
            orangeLightColor,
            greyLightColor,
            blueLightColor,
            oceanBlueColor,
            blueLightColor,
            purpleColor,
            yellowColor,
            cyanColor,
        ]

        let cardColor, headingColor, labelColor, borderColor, legendColor;

        if (isDarkStyle) {
            cardColor = config.colors_dark.cardColor;
            headingColor = config.colors_dark.headingColor;
            labelColor = config.colors_dark.textMuted;
            legendColor = config.colors_dark.bodyColor;
            borderColor = config.colors_dark.borderColor;
        } else {
            cardColor = config.colors.cardColor;
            headingColor = config.colors.headingColor;
            labelColor = config.colors.textMuted;
            legendColor = config.colors.bodyColor;
            borderColor = config.colors.borderColor;
        }

        const doughnutChart = document.getElementById('doughnutChart');
        if (doughnutChart) {

            let products = @json($products);
            let labels = Object.keys(products);
            let data = Object.values(products);

            const doughnutChartVar = new Chart(doughnutChart, {
                type: 'doughnut',
                data: {
                    labels: labels,
                    datasets: [{
                        data: data,
                        backgroundColor: colors.slice(0, labels.length),
                        borderWidth: 0,
                        pointStyle: 'rectRounded'
                    }]
                },
                options: {
                    responsive: true,
                    animation: {
                        duration: 500
                    },
                    cutout: '68%',
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const label = context.label || '',
                                        value = context.parsed;
                                    const output = ' ' + label + ' : ' + value;
                                    return output;
                                }
                            },
                            // Updated default tooltip UI
                            rtl: isRtl,
                            backgroundColor: cardColor,
                            titleColor: headingColor,
                            bodyColor: legendColor,
                            borderWidth: 1,
                            borderColor: borderColor
                        }
                    }
                }
            });
        }

        const barChart = document.getElementById('barChart');
        if (barChart) {
            let last13j_sales = @json($last13j_sales);
            const barChartVar = new Chart(barChart, {
                type: 'bar',
                data: {
                    labels: last13j_sales.dates,
                    datasets: [{
                        data: last13j_sales.totals,
                        backgroundColor: cyanColor,
                        borderColor: 'transparent',
                        maxBarThickness: 15,
                        borderRadius: {
                            topRight: 15,
                            topLeft: 15
                        }
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    animation: {
                        duration: 500
                    },
                    plugins: {
                        tooltip: {
                            rtl: isRtl,
                            backgroundColor: cardColor,
                            titleColor: headingColor,
                            bodyColor: legendColor,
                            borderWidth: 1,
                            borderColor: borderColor
                        },
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                color: borderColor,
                                drawBorder: false,
                                borderColor: borderColor
                            },
                            ticks: {
                                color: labelColor
                            }
                        },
                        y: {
                            min: 0,
                            max: 400,
                            grid: {
                                color: borderColor,
                                drawBorder: false,
                                borderColor: borderColor
                            },
                            ticks: {
                                stepSize: 100,
                                color: labelColor
                            }
                        }
                    }
                }
            });
        }
    </script>
@endsection
@section('title', 'Home')

@section('content')
    <div class="row">
        <div class="col-lg-3 col-sm-6">
            <div class="card card-border-shadow-primary h-100">
                <div class="card-body border-bottom border-primary border-3">
                    <div class="d-flex align-items-center mb-2">
                        <div class="avatar me-4">
                            <span class="avatar-initial rounded bg-label-primary"><i class="ti ti-users ti-28px"></i></span>
                        </div>
                        <h4 class="mb-0">{{ $total_users }}</h4>
                    </div>
                    <p class="mb-1">Total Users</p>
                    <p class="mb-0">
                        <span class="text-heading fw-medium me-2">{{ $last_month_users }}%</span>
                        <small class="text-muted">than last month</small>
                    </p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6">
            <div class="card card-border-shadow-info h-100">
                <div class="card-body border-bottom border-info border-3">
                    <div class="d-flex align-items-center mb-2">
                        <div class="avatar me-4">
                            <span class="avatar-initial rounded bg-label-info"><i
                                    class="ti ti-currency-dollar ti-28px"></i></span>
                        </div>
                        <h4 class="mb-0">${{ $total_sales }} </h4>
                    </div>
                    <p class="mb-1">Total Sales</p>
                    <p class="mb-0">
                        <span class="text-heading fw-medium me-2">{{ $last_month_sales }}%</span>
                        <small class="text-muted">than last month</small>
                    </p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6">
            <div class="card card-border-shadow-danger h-100">
                <div class="card-body border-bottom border-danger border-3">
                    <div class="d-flex align-items-center mb-2">
                        <div class="avatar me-4">
                            <span class="avatar-initial rounded bg-label-danger"><i
                                    class="ti ti-chart-arcs-3 ti-28px"></i></span>
                        </div>
                        <h4 class="mb-0">{{ $total_fantree_print }}</h4>
                    </div>
                    <p class="mb-1">Total Fanchart prints</p>

                </div>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6">
            <div class="card card-border-shadow-warning h-100">
                <div class="card-body border-bottom border-warning border-3">
                    <div class="d-flex align-items-center mb-2">
                        <div class="avatar me-4">
                            <span class="avatar-initial rounded bg-label-warning"><i
                                    class="ti ti-growth ti-28px"></i></span>
                        </div>
                        <h4 class="mb-0">{{ $total_pedigree_print }}</h4>
                    </div>
                    <p class="mb-1">Total Pedigree prints</p>

                </div>
            </div>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-xl-4 col-lg-4 col-12 mb-4">
            <div class="card">
                <h5 class="card-header">Sales by Product</h5>
                <div class="card-body">
                    <canvas id="doughnutChart" class="chartjs mb-4" data-height="350"></canvas>
                    <ul class="doughnut-legend d-flex justify-content-around ps-0 mb-2 pt-1">
                        @foreach ($products as $name => $product)
                            <li class="ct-series-0 d-flex flex-column">
                                <h5 class="mb-0 fw-bold">{{ $name }}</h5>
                                <span class="badge badge-dot my-2 cursor-pointer rounded-pill"
                                    style="background-color: {{ $colors[$loop->index] }};width:35px; height:6px;"></span>
                                <div class="text-muted">{{ ($product / array_sum($products)) * 100 }} %</div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-xl-8 col-lg-8 col-12 mb-4">
            <div class="card h-100">
                <div class="card-header header-elements">
                    <h5 class="card-title mb-0">Sales in last 13J</h5>
                    <div class="card-action-element ms-auto py-0">
                        <div class="dropdown">
                            <button type="button" class="btn dropdown-toggle px-0" data-bs-toggle="dropdown"
                                aria-expanded="false"><i class="ti ti-calendar"></i></button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a href="javascript:void(0);" class="dropdown-item d-flex align-items-center">Today</a>
                                </li>
                                <li><a href="javascript:void(0);"
                                        class="dropdown-item d-flex align-items-center">Yesterday</a></li>
                                <li><a href="javascript:void(0);" class="dropdown-item d-flex align-items-center">Last 7
                                        Days</a></li>
                                <li><a href="javascript:void(0);" class="dropdown-item d-flex align-items-center">Last 30
                                        Days</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a href="javascript:void(0);" class="dropdown-item d-flex align-items-center">Current
                                        Month</a></li>
                                <li><a href="javascript:void(0);" class="dropdown-item d-flex align-items-center">Last
                                        Month</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <canvas id="barChart" class="chartjs"></canvas>
                </div>
            </div>
        </div>
    </div>
@endsection
