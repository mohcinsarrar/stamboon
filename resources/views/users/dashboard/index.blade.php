@php
    $configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Home')

@section('vendor-style')
<link rel="stylesheet" href="{{asset('assets/vendor/libs/plyr/plyr.css')}}" />

    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/apex-charts/apex-charts.css') }}" />
@endsection

@section('vendor-script')
<script src="{{asset('assets/vendor/libs/plyr/plyr.js')}}"></script>

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
        <!--
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
    -->
        <div class="row mt-4">
            <div class="card">
                <div class="card-body">
                  <div class="d-flex justify-content-between align-items-center flex-wrap mb-6 gap-2 mb-4">
                    <div class="me-1">
                      <h5 class="mb-0">Stamboon Tools Tutorial</h5>
                    </div>
                  </div>
                  <div class="card academy-content shadow-none border">
                    <div class="p-2">
                      <div class="cursor-pointer"><div class="plyr plyr--full-ui plyr--video plyr--html5 plyr--fullscreen-enabled plyr--paused plyr--stopped plyr__poster-enabled" style="border-radius: 6px;"><div class="plyr__controls"><button class="plyr__controls__item plyr__control" type="button" data-plyr="play" aria-pressed="false" aria-label="Play"><svg class="icon--pressed" aria-hidden="true" focusable="false"><use xlink:href="#plyr-pause"></use></svg><svg class="icon--not-pressed" aria-hidden="true" focusable="false"><use xlink:href="#plyr-play"></use></svg><span class="label--pressed plyr__sr-only">Pause</span><span class="label--not-pressed plyr__sr-only">Play</span></button><div class="plyr__controls__item plyr__progress__container"><div class="plyr__progress"><input data-plyr="seek" type="range" min="0" max="100" step="0.01" value="0" autocomplete="off" role="slider" aria-label="Seek" aria-valuemin="0" aria-valuemax="183.125333" aria-valuenow="0" id="plyr-seek-2175" aria-valuetext="00:00 of 00:00" style="--value: 0%;"><progress class="plyr__progress__buffer" min="0" max="100" value="0.116495624338332" role="progressbar" aria-hidden="true">% buffered</progress><span class="plyr__tooltip">00:00</span></div></div><div class="plyr__controls__item plyr__time--current plyr__time" aria-label="Current time" role="timer">03:03</div><div class="plyr__controls__item plyr__volume"><button type="button" class="plyr__control" data-plyr="mute" aria-pressed="false"><svg class="icon--pressed" aria-hidden="true" focusable="false"><use xlink:href="#plyr-muted"></use></svg><svg class="icon--not-pressed" aria-hidden="true" focusable="false"><use xlink:href="#plyr-volume"></use></svg><span class="label--pressed plyr__sr-only">Unmute</span><span class="label--not-pressed plyr__sr-only">Mute</span></button><input data-plyr="volume" type="range" min="0" max="1" step="0.05" value="1" autocomplete="off" role="slider" aria-label="Volume" aria-valuemin="0" aria-valuemax="100" aria-valuenow="100" id="plyr-volume-2175" aria-valuetext="100.0%" style="--value: 100%;"></div><button class="plyr__controls__item plyr__control" type="button" data-plyr="captions" aria-pressed="false"><svg class="icon--pressed" aria-hidden="true" focusable="false"><use xlink:href="#plyr-captions-on"></use></svg><svg class="icon--not-pressed" aria-hidden="true" focusable="false"><use xlink:href="#plyr-captions-off"></use></svg><span class="label--pressed plyr__sr-only">Disable captions</span><span class="label--not-pressed plyr__sr-only">Enable captions</span></button><div class="plyr__controls__item plyr__menu"><button aria-haspopup="true" aria-controls="plyr-settings-2175" aria-expanded="false" type="button" class="plyr__control" data-plyr="settings" aria-pressed="false"><svg aria-hidden="true" focusable="false"><use xlink:href="#plyr-settings"></use></svg><span class="plyr__sr-only">Settings</span></button><div class="plyr__menu__container" id="plyr-settings-2175" hidden=""><div><div id="plyr-settings-2175-home"><div role="menu"><button data-plyr="settings" type="button" class="plyr__control plyr__control--forward" role="menuitem" aria-haspopup="true" hidden=""><span>Captions<span class="plyr__menu__value">Disabled</span></span></button><button data-plyr="settings" type="button" class="plyr__control plyr__control--forward" role="menuitem" aria-haspopup="true" hidden=""><span>Quality<span class="plyr__menu__value">0</span></span></button><button data-plyr="settings" type="button" class="plyr__control plyr__control--forward" role="menuitem" aria-haspopup="true"><span>Speed<span class="plyr__menu__value">Normal</span></span></button></div></div><div id="plyr-settings-2175-captions" hidden=""><button type="button" class="plyr__control plyr__control--back"><span aria-hidden="true">Captions</span><span class="plyr__sr-only">Go back to previous menu</span></button><div role="menu"></div></div><div id="plyr-settings-2175-quality" hidden=""><button type="button" class="plyr__control plyr__control--back"><span aria-hidden="true">Quality</span><span class="plyr__sr-only">Go back to previous menu</span></button><div role="menu"></div></div><div id="plyr-settings-2175-speed" hidden=""><button type="button" class="plyr__control plyr__control--back"><span aria-hidden="true">Speed</span><span class="plyr__sr-only">Go back to previous menu</span></button><div role="menu"><button data-plyr="speed" type="button" role="menuitemradio" class="plyr__control" aria-checked="false" value="0.5"><span>0.5×</span></button><button data-plyr="speed" type="button" role="menuitemradio" class="plyr__control" aria-checked="false" value="0.75"><span>0.75×</span></button><button data-plyr="speed" type="button" role="menuitemradio" class="plyr__control" aria-checked="true" value="1"><span>Normal</span></button><button data-plyr="speed" type="button" role="menuitemradio" class="plyr__control" aria-checked="false" value="1.25"><span>1.25×</span></button><button data-plyr="speed" type="button" role="menuitemradio" class="plyr__control" aria-checked="false" value="1.5"><span>1.5×</span></button><button data-plyr="speed" type="button" role="menuitemradio" class="plyr__control" aria-checked="false" value="1.75"><span>1.75×</span></button><button data-plyr="speed" type="button" role="menuitemradio" class="plyr__control" aria-checked="false" value="2"><span>2×</span></button><button data-plyr="speed" type="button" role="menuitemradio" class="plyr__control" aria-checked="false" value="4"><span>4×</span></button></div></div></div></div></div><button class="plyr__controls__item plyr__control" type="button" data-plyr="fullscreen" aria-pressed="false"><svg class="icon--pressed" aria-hidden="true" focusable="false"><use xlink:href="#plyr-exit-fullscreen"></use></svg><svg class="icon--not-pressed" aria-hidden="true" focusable="false"><use xlink:href="#plyr-enter-fullscreen"></use></svg><span class="label--pressed plyr__sr-only">Exit fullscreen</span><span class="label--not-pressed plyr__sr-only">Enter fullscreen</span></button></div><div class="plyr__video-wrapper"><video class="w-100" poster="https://cdn.plyr.io/static/demo/View_From_A_Blue_Moon_Trailer-HD.jpg" id="plyr-video-player" playsinline="" data-poster="https://cdn.plyr.io/static/demo/View_From_A_Blue_Moon_Trailer-HD.jpg">
                          <source src="https://cdn.plyr.io/static/demo/View_From_A_Blue_Moon_Trailer-576p.mp4" type="video/mp4">
                        </video><div class="plyr__poster" style="background-image: url(&quot;https://cdn.plyr.io/static/demo/View_From_A_Blue_Moon_Trailer-HD.jpg&quot;);"></div></div><div class="plyr__captions" dir="auto"></div><button type="button" class="plyr__control plyr__control--overlaid" data-plyr="play" aria-pressed="false" aria-label="Play"><svg aria-hidden="true" focusable="false"><use xlink:href="#plyr-play"></use></svg><span class="plyr__sr-only">Play</span></button></div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
        </div>

    @endif
@endsection
