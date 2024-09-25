@php
    $configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Home')

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/apex-charts/apex-charts.css') }}" />
@endsection

@section('vendor-script')
    <script src="{{ asset('assets/vendor/libs/apex-charts/apexcharts.js') }}"></script>
@endsection

@section('page-script')
    <script>
        let cardColor, labelColor, headingColor, borderColor, legendColor;

        if (isDarkStyle) {
            cardColor = config.colors_dark.cardColor;
            labelColor = config.colors_dark.textMuted;
            legendColor = config.colors_dark.bodyColor;
            headingColor = config.colors_dark.headingColor;
            borderColor = config.colors_dark.borderColor;
        } else {
            cardColor = config.colors.cardColor;
            labelColor = config.colors.textMuted;
            legendColor = config.colors.bodyColor;
            headingColor = config.colors.headingColor;
            borderColor = config.colors.borderColor;
        }

        // Donut Chart Colors
        const chartColors = {
            donut: {
                series1: config.colors.success,
                series2: '#28c76fb3',
                series3: '#28c76f80',
                series4: config.colors_label.success
            }
        };
        const expensesRadialChartEl = document.querySelector('#expensesChart'),
            expensesRadialChartConfig = {
                chart: {
                    height: 145,
                    sparkline: {
                        enabled: true
                    },
                    parentHeightOffset: 0,
                    type: 'radialBar'
                },
                colors: [config.colors.warning],
                series: [78],
                plotOptions: {
                    radialBar: {
                        offsetY: 0,
                        startAngle: -90,
                        endAngle: 90,
                        hollow: {
                            size: '65%'
                        },
                        track: {
                            strokeWidth: '45%',
                            background: borderColor
                        },
                        dataLabels: {
                            name: {
                                show: false
                            },
                            value: {
                                fontSize: '22px',
                                color: headingColor,
                                fontWeight: 600,
                                offsetY: -5
                            }
                        }
                    }
                },
                grid: {
                    show: false,
                    padding: {
                        bottom: 5
                    }
                },
                stroke: {
                    lineCap: 'round'
                },
                labels: ['Progress'],
                responsive: [{
                        breakpoint: 1442,
                        options: {
                            chart: {
                                height: 120
                            },
                            plotOptions: {
                                radialBar: {
                                    dataLabels: {
                                        value: {
                                            fontSize: '18px'
                                        }
                                    },
                                    hollow: {
                                        size: '60%'
                                    }
                                }
                            }
                        }
                    },
                    {
                        breakpoint: 1025,
                        options: {
                            chart: {
                                height: 136
                            },
                            plotOptions: {
                                radialBar: {
                                    hollow: {
                                        size: '65%'
                                    },
                                    dataLabels: {
                                        value: {
                                            fontSize: '18px'
                                        }
                                    }
                                }
                            }
                        }
                    },
                    {
                        breakpoint: 769,
                        options: {
                            chart: {
                                height: 120
                            },
                            plotOptions: {
                                radialBar: {
                                    hollow: {
                                        size: '55%'
                                    }
                                }
                            }
                        }
                    },
                    {
                        breakpoint: 426,
                        options: {
                            chart: {
                                height: 145
                            },
                            plotOptions: {
                                radialBar: {
                                    hollow: {
                                        size: '65%'
                                    }
                                }
                            },
                            dataLabels: {
                                value: {
                                    offsetY: 0
                                }
                            }
                        }
                    },
                    {
                        breakpoint: 376,
                        options: {
                            chart: {
                                height: 105
                            },
                            plotOptions: {
                                radialBar: {
                                    hollow: {
                                        size: '60%'
                                    }
                                }
                            }
                        }
                    }
                ]
            };
        if (typeof expensesRadialChartEl !== undefined && expensesRadialChartEl !== null) {
            const expensesRadialChart = new ApexCharts(expensesRadialChartEl, expensesRadialChartConfig);
            expensesRadialChart.render();
        }
    </script>

@endsection
@section('content')
    <!-- Start Pricing  Area -->
    <div class="row mt-4">
        <div class="col">
            <div class="card bg-transparent shadow-none my-6 border-0">
                <div class="card-body row p-0 pb-6 g-6">
                    <div class="col-12 col-lg-8 card-separator">
                        <h5 class="mb-2">Welcome back,<span class="h4">
                                {{ ucwords(str_replace('.', ' ', auth()->user()->name)) }} </span></h5>
                        <div class="col-12 col-lg-5 mb-4">
                            <p>Continue working on your family tree !</p>
                        </div>
                        <div class="d-flex justify-content-start flex-wrap gap-4 me-12">
                            <div class="col-4 d-flex align-items-center gap-3 me-6 me-sm-0">
                                <div class="avatar avatar-md">
                                    <div class="avatar-initial bg-label-primary rounded">
                                        <div>
                                            <i class="h4 mb-0 ti ti-chart-arcs-3 text-primary"></i>
                                        </div>
                                    </div>
                                </div>
                                <a href="{{ route('users.fanchart.index') }}">
                                    <div class="content-right">
                                        <h5 class="text-primary mb-0">Fanchart</h5>
                                    </div>
                                </a>
                            </div>
                            <div class="col-4 d-flex align-items-center gap-3">
                                <div class="avatar avatar-md">
                                    <div class="avatar-initial bg-label-info rounded">
                                        <div>
                                            <i class="h4 mb-0 ti ti-growth text-info"></i>
                                        </div>
                                    </div>
                                </div>
                                <a href="{{ route('users.pedigree.index') }}">
                                    <div class="content-right">
                                        <h5 class="text-info mb-0">Pedigree</h5>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-lg-4 ps-md-4 ps-lg-6">
                        <div class="row justify-content-between align-items-center">
                            <div class="col-auto">
                                <h5 class="mb-1">Your current plan</h5>
                                <p class="mb-0">{{ $payment->product->name }}</p>
                            </div>
                            <div class="col-auto">
                                <span>Active until</span>
                                <h6 class="mb-1">{{ $payment->active_until() }}</h6>
                            </div>
                        </div>
                        <div class="row mt-4">
                            <div class="plan-statistics">
                                <div class="d-flex justify-content-between">
                                    <h6 class="mb-1"></h6>
                                    <h6 class="mb-1">{{ $payment->countdown()['passedDays'] }} of
                                        {{ $payment->countdown()['totalDays'] }} Days</h6>
                                </div>
                                <div class="progress mb-1 bg-label-primary" style="height: 10px;">
                                    <div class="progress-bar" style="width: {{ $payment->countdown()['percentage'] }}%;"
                                        role="progressbar" aria-valuenow="{{ $payment->countdown()['percentage'] }}"
                                        aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                @if ($payment->countdown()['percentage'] > 75)
                                    <small>Your plan requires update</small>
                                @endif
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-12 order-2 order-xl-0 d-flex flex-wrap row-gap-4">
                                <a href="{{ route('users.subscription.index') }}"
                                    class="btn-text-primary waves-effect waves-light">Upgrade
                                    Plan</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @if (Auth::user()->has_payment() != false)

        <div class="row mt-4">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header pb-0">
                        <h5 class="card-title mb-0">Fanchart Stats</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="w-100 mt-3">
                                <h6 class="mb-2">Generation number</h6>
                                <div class="progress" style="height: 5px;">
                                    <div class="progress-bar bg-primary" role="progressbar" style="width: 95%;"
                                        aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                            <div class="w-100 mt-3">
                                <h6 class="mb-2">Person number</h6>
                                <div class="progress" style="height: 5px;">
                                    <div class="progress-bar bg-success" role="progressbar" style="width: 95%;"
                                        aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header pb-0">
                        <h5 class="card-title mb-0">Pedigree Stats</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="w-100 mt-3">
                                <h6 class="mb-2">Generation number</h6>
                                <div class="progress" style="height: 5px;">
                                    <div class="progress-bar bg-primary" role="progressbar" style="width: 95%;"
                                        aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                            <div class="w-100 mt-3">
                                <h6 class="mb-2">Person number</h6>
                                <div class="progress" style="height: 5px;">
                                    <div class="progress-bar bg-success" role="progressbar" style="width: 95%;"
                                        aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card  h-100">
                    <div class="card-header pb-0">
                        <h5 class="card-title mb-0">Total prints</h5>
                    </div>
                    <div class="card-body pb-2">
                        <div class="row">
                            <div class="col">
                                <div class="mt-lg-4 mt-lg-2 mb-lg-6 mb-2">
                                    <h3 class="mb-0">{{ $payment->product->print_number }}</h3>
                                    <p class="mb-0">Total Prints</p>
                                </div>
                                
                            </div>
                            <div class="col">
                                <ul class="p-0 m-0">
                                    @if ($payment->product->fanchart == true)
                                        <li class="d-flex gap-4 align-items-center mb-lg-3 pb-1">
                                            <div class="badge rounded bg-label-primary p-1_5"><i
                                                    class="ti ti-ticket ti-md"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-0 text-nowrap">Fanchart</h6>
                                                <small class="text-muted">{{$fanchart->print_number}}</small>
                                            </div>
                                        </li>
                                    @endif
                                    @if ($payment->product->pedigree == true)
                                        <li class="d-flex gap-4 align-items-center mb-lg-3 pb-1">
                                            <div class="badge rounded bg-label-info p-1_5"><i
                                                    class="ti ti-circle-check ti-md"></i></div>
                                            <div>
                                                <h6 class="mb-0 text-nowrap">Pedigree</h6>
                                                <small class="text-muted">{{$pedigree->print_number}}</small>
                                            </div>
                                        </li>
                                    @endif
                                </ul>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-1">
            
        </div>

    @endif
@endsection
