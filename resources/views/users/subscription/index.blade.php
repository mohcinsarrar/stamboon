@php
    $configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Subscriptionn')

@section('page-style')
    <!-- Page -->
    <link rel="stylesheet" href="{{ asset('webshop/assets/css/style.css') }}?{{ time() }}" />
@endsection

@section('content')
    <h4>
        <i class="ti ti-credit-card h2 mb-1"></i>
        Subscription
    </h4>
    @if (Auth::user()->has_payment() == false)
        <div class="alert alert-warning d-flex align-items-center" role="alert">
            <span class="alert-icon text-secondary me-2">
                <i class="ti ti-alert-triangle ti-xs"></i>
            </span>
            You don't have any subscription plan, Order one now !!
        </div>
    @endif
    <!-- Start Pricing  Area -->

    @if (Auth::user()->has_payment() != false)
        <div class="card mb-6 bg-dark bg-gradient text-white mt-4">
            <h5 class="card-header">Current Plan</h5>
            <div class="card-body">
                <div class="row row-gap-4 row-gap-xl-0">
                    <div class="col-xl-6 order-1 order-xl-0">
                        <div class="mb-4">
                            <h6 class="mb-1  text-white">Your Current Plan is {{ $payment->product->name }}</h6>
                            <p class=" text-white">{{ $payment->product->description }}</p>
                        </div>
                        <div class="mb-4">
                            <h6 class="mb-1  text-white">Active until {{ $payment->active_until() }}</h6>
                            <p class=" text-white">We will send you a notification upon Subscription expiration</p>
                        </div>
                    </div>
                    <div class="col-xl-6 order-0 order-xl-0">
                        @if ($payment->countdown()['percentage'] > 75)
                            <div class="alert alert-warning" role="alert">
                                <h5 class="alert-heading mb-2  text-white">We need your attention!</h5>
                                <span class=" text-white">Your plan requires update</span>
                            </div>
                        @endif
                        <div class="plan-statistics ">
                            <div class="d-flex justify-content-between">
                                <h6 class="mb-1 text-white">Days</h6>
                                <h6 class="mb-1 text-white">{{ $payment->countdown()['passedDays'] }} of
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
                </div>
            </div>
        </div>

    @endif
    <div class="card mt-4">
        <section id="pricing" class="pricing-area pricing-fourteen pt-2 ">
            <div class="container">
                <div class="row">
                    @foreach ($products as $product)
                        <div class="col-lg-4 col-md-6 col-12 position-relative">
                            @if ($loop->index == 1)
                                <div class="position-absolute end-0 me-5 top-0 mt-4">
                                    <span class="badge bg-label-primary rounded-1">Popular</span>
                                </div>
                            @endif
                            <div class="h-100 pricing-style-fourteen {{ $loop->index == 1 ? 'middle' : '' }}">

                                <div class="table-head">
                                    <h6 class="title">{{ $product->name }}</h4>
                                        <p class="mb-0">{{ $product->description }}</p>
                                        <div class="price">
                                            <h3 class="amount">
                                                <span class="currency">$</span>{{ $product->price }}
                                            </h3>
                                            @if ($product->duration % 12 === 0)
                                                <span class="duration">/ {{ $product->duration / 12 }} Years </span>
                                            @else
                                                <span class="duration">/ {{ $product->duration }} Months </span>
                                            @endif
                                        </div>
                                </div>

                                <div class="light-rounded-buttons purchase-btn">

                                    @if ($payment != null)
                                        @if ($payment->product_id == $product->id)
                                            <a href="{{ route('users.subscription.payment', $product->id) }}"
                                                class="btn btn-primary current">
                                                Your Current Plan
                                            </a>
                                        @else
                                            @if ($payment->product->price < $product->price)
                                                <a href="{{ route('users.subscription.payment', $product->id) }}"
                                                    class="btn btn-primary">
                                                    Upgrade
                                                </a>
                                            @else
                                                <a href="{{ route('users.subscription.payment', $product->id) }}"
                                                    class="btn btn-primary disabled" disabled>
                                                    Upgrade
                                                </a>
                                            @endif
                                        @endif
                                    @else
                                        <a href="{{ route('users.subscription.payment', $product->id) }}"
                                            class="btn btn-primary">
                                            Purchase
                                        </a>
                                    @endif

                                </div>

                                <div class="table-content">
                                    <ul class="table-list ps-0">
                                        <li> <i class="ti ti-circle-check"></i> Chart type :
                                            {{ $product->fanchart == true ? 'Fanchart ,' : '' }}{{ $product->pedigree == true ? 'Pedigree' : '' }}
                                        </li>
                                        <li> <i class="ti ti-circle-check"></i> Max Print charts :
                                            {{ $product->print_number > 0 ? $product->print_number . '' : 'Unlimited' }}
                                        </li>
                                    </ul>
                                </div>
                                @php
                                    $max_output_png = [
                                        '1' => '1344 x 839 px',
                                        '2' => '2688 x 1678 px',
                                        '3' => '4032 x 2517 px',
                                        '4' => '5376 x 3356 px',
                                        '5' => '6720 x 4195 px',
                                    ];

                                    $max_output_pdf = [
                                        'a0' => 'A0',
                                        'a1' => 'A1',
                                        'a2' => 'A2',
                                        'a3' => 'A3',
                                        'a4' => 'A4',
                                    ];
                                @endphp

                                @if ($product->fanchart == true)
                                    <h6 class="text-start mt-3">Fanchart Features</h6>
                                    <div class="table-content">
                                        <ul class="table-list ps-0">
                                            <li> <i class="ti ti-circle-check"></i> Max generations :
                                                {{ $product->fanchart_max_generation }}</li>
                                            <li> <i class="ti ti-circle-check"></i> Output products :
                                                {{ $product->fanchart_output_png == true ? 'PNG ,' : '' }}{{ $product->fanchart_output_pdf == true ? 'PDF' : '' }}
                                            </li>
                                            <li> <i class="ti ti-circle-check"></i> Max PNG measurements :
                                                {{ $max_output_png[$product->fanchart_max_output_png] }}</li>
                                            <li> <i class="ti ti-circle-check"></i> Max PDF measurements :
                                                {{ $max_output_pdf[$product->fanchart_max_output_pdf] }}</li>
                                        </ul>
                                    </div>
                                @endif

                                @if ($product->pedigree == true)
                                    <h6 class="text-start mt-3">Pedigree Features</h6>
                                    <div class="table-content">
                                        <ul class="table-list ps-0">
                                            <li> <i class="ti ti-circle-check"></i> Max generations :
                                                {{ $product->pedigree_max_generation }}</li>
                                            <li> <i class="ti ti-circle-check"></i> Max nodes : {{ $product->max_nodes }}
                                            </li>
                                            <li> <i class="ti ti-circle-check"></i> Output products :
                                                {{ $product->pedigree_output_png == true ? 'PNG ,' : '' }}{{ $product->pedigree_output_pdf == true ? 'PDF' : '' }}
                                            </li>
                                            <li> <i class="ti ti-circle-check"></i> Max PNG measurements :
                                                {{ $max_output_png[$product->pedigree_max_output_png] }}</li>
                                            <li> <i class="ti ti-circle-check"></i> Max PDF measurements :
                                                {{ $max_output_pdf[$product->pedigree_max_output_pdf] }}</li>
                                        </ul>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    </div>
    <!--/ End Pricing  Area -->

@endsection
