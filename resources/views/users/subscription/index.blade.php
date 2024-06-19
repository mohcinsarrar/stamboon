@php
    $configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Subscriptionn')

@section('page-style')
    <!-- Page -->
    <link rel="stylesheet" href="{{ asset('webshop/assets/css/style.css') }}" />
@endsection

@section('content')
    <h4>
        <i class="ti ti-credit-card h2 mb-1"></i>
        Subscription
    </h4>
    @if (!Auth::user()->hasSubcription())
        <div class="alert alert-warning d-flex align-items-center" role="alert">
            <span class="alert-icon text-secondary me-2">
                <i class="ti ti-alert-triangle ti-xs"></i>
            </span>
            You don't have any subscription plan, Order one now !!
        </div>
    @endif
    <!-- Start Pricing  Area -->
    @if (Auth::user()->hasSubcription())
        <div class="card p-4 mb-4">
            <h5 class="card-header p-0">Your Subcriptions</h5>
            <div class="border rounded p-3 mt-4 ">
                <div class="row gap-4 gap-sm-0">
                    <div class="col-12">
                        <div class="row">
                            <div class="d-flex gap-2 align-items-center pb-3">
                                <div class="badge rounded bg-label-primary p-1"><i class="ti ti-currency-dollar ti-sm"></i>
                                </div>
                                <h4 class="mb-0">{{ $payment->product->name }}</h4>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 col-sm-4">

                                <div class="pb-1">
                                    <span class="text-muted">1 year</span>
                                </div>
                                <div class="">
                                    <span class="text-muted">ID : {{ $payment->payment_id }}</span>
                                </div>

                            </div>
                            <div class="col-12 col-sm-4">
                                <div class="pb-1">
                                    <span class="text-muted">Issued Date</span>
                                </div>
                                <div class="">
                                    <span class="text-dark">{{ $payment->created_at }}</span>
                                </div>

                            </div>
                            <div class="col-12 col-sm-4">
                                <div class="pb-1">
                                    <span class="text-muted">Expiration Date</span>
                                </div>
                                <div class="">
                                    <span class="text-dark">{{ $payment->created_at }}</span>
                                </div>

                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    @endif
    <div class="card bg-white">
        <section id="pricing" class="pricing-area pricing-fourteen pt-2 ">
            <div class="container">
                <div class="row">
                    @foreach ($products as $product)
                        <div class="col-lg-4 col-md-6 col-12">
                            <div class="h-100 pricing-style-fourteen {{ $loop->index == 1 ? 'middle' : '' }}">
                                <div class="table-head">
                                    <h6 class="title">{{ $product->name }}</h4>
                                        <p>{{ $product->description }}</p>
                                        <div class="price">
                                            <h2 class="amount">
                                                <span class="currency">$</span>{{ $product->amount }}<span
                                                    class="duration">/mo </span>
                                            </h2>
                                        </div>
                                </div>

                                <div class="light-rounded-buttons">
                                    <a href="{{ route('users.subscription.payment', $product->id) }}" class="btn primary-btn-outline">
                                        @if ($payment != null)
                                            @if ($payment->product_id == $product->id)
                                                Your Current Plan
                                            @else
                                                Upgrade
                                            @endif
                                        @else
                                            Purchase
                                        @endif
                                    </a>
                                </div>

                                <div class="table-content">
                                    <ul class="table-list">
                                        @if ($product->features != null)
                                            @foreach ($product->features as $feature)
                                                <li> <i class="lni lni-checkmark-circle"></i> {{ $feature }}</li>
                                            @endforeach
                                        @endif
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    </div>
    <!--/ End Pricing  Area -->

@endsection
