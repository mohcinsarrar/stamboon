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
        Subscription</h4>
    @if(!Auth::user()->hasSubcription())
    <div class="alert alert-warning d-flex align-items-center" role="alert">
        <span class="alert-icon text-secondary me-2">
            <i class="ti ti-alert-triangle ti-xs"></i>
        </span>
        You don't have any subscription plan, Order one now !!
    </div>
    @endif
    <!-- Start Pricing  Area -->
    @if(Auth::user()->hasSubcription())
    <div class="card p-4 mb-4">
        <h5 class="card-header p-0">Your Subcriptions</h5>
        <div class="border rounded p-3 mt-4 ">
            <div class="row gap-4 gap-sm-0">
                <div class="col-12">
                    <div class="row">
                        <div class="d-flex gap-2 align-items-center pb-3">
                            <div class="badge rounded bg-label-primary p-1"><i class="ti ti-currency-dollar ti-sm"></i></div>
                            <h4 class="mb-0">{{$payment->product->name}}</h4>
                          </div>
                    </div>
                    <div class="row">
                        <div class="col-12 col-sm-4">
                
                            <div class="pb-1">
                                <span class="text-muted">1 year</span>
                            </div>
                            <div class="">
                                <span class="text-muted">ID : {{$payment->payment_id}}</span>
                            </div>
                            
                          </div>
                          <div class="col-12 col-sm-4">
                            <div class="pb-1">
                                <span class="text-muted">Issued Date</span>
                            </div>
                            <div class="">
                                <span class="text-dark">{{$payment->created_at}}</span>
                            </div>
                            
                          </div>
                          <div class="col-12 col-sm-4">
                            <div class="pb-1">
                                <span class="text-muted">Expiration Date</span>
                            </div>
                            <div class="">
                                <span class="text-dark">{{$payment->created_at}}</span>
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
                <div class="col-lg-4 col-md-6 col-12">
                    <div class="pricing-style-fourteen">
                        <div class="table-head">
                            <h6 class="title">Basic</h4>
                                <p>Lorem Ipsum is simply dummy text of the printing and industry.</p>
                                <div class="price">
                                    <h2 class="amount">
                                        <span class="currency">$</span>0<span class="duration">/mo </span>
                                    </h2>
                                </div>
                        </div>

                        <div class="light-rounded-buttons">
                            <a href="{{ route('familytree.subscription.payment', 1) }}" class="btn primary-btn-outline">
                                @if($payment!=null )
                                    @if($payment->product_id == 1)
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
                                <li> <i class="lni lni-checkmark-circle"></i> Cras justo odio.</li>
                                <li> <i class="lni lni-checkmark-circle"></i> Dapibus ac facilisis in.</li>
                                <li> <i class="lni lni-checkmark-circle deactive"></i> Morbi leo risus.</li>
                                <li> <i class="lni lni-checkmark-circle deactive"></i> Excepteur sint occaecat velit.
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-12">
                    <div class="pricing-style-fourteen middle">
                        <div class="table-head">
                            <h6 class="title">Standard</h4>
                                <p>Lorem Ipsum is simply dummy text of the printing and industry.</p>
                                <div class="price">
                                    <h2 class="amount">
                                        <span class="currency">$</span>99<span class="duration">/mo </span>
                                    </h2>
                                </div>
                        </div>

                        <div class="light-rounded-buttons">
                            <a href="{{ route('familytree.subscription.payment', 2) }}" class="btn primary-btn">
                                @if($payment!=null )
                                    @if($payment->product_id == 2)
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
                                <li> <i class="lni lni-checkmark-circle"></i> Cras justo odio.</li>
                                <li> <i class="lni lni-checkmark-circle"></i> Dapibus ac facilisis in.</li>
                                <li> <i class="lni lni-checkmark-circle"></i> Morbi leo risus.</li>
                                <li> <i class="lni lni-checkmark-circle deactive"></i> Excepteur sint occaecat velit.
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-12">
                    <div class="pricing-style-fourteen">
                        <div class="table-head">
                            <h6 class="title">Plus</h4>
                                <p>Lorem Ipsum is simply dummy text of the printing and industry.</p>
                                <div class="price">
                                    <h2 class="amount">
                                        <span class="currency">$</span>150<span class="duration">/mo </span>
                                    </h2>
                                </div>
                        </div>

                        <div class="light-rounded-buttons">
                            <a href="{{ route('familytree.subscription.payment', 3) }}" class="btn primary-btn-outline">
                                @if($payment!=null )
                                    @if($payment->product_id == 3)
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
                                <li> <i class="lni lni-checkmark-circle"></i> Cras justo odio.</li>
                                <li> <i class="lni lni-checkmark-circle"></i> Dapibus ac facilisis in.</li>
                                <li> <i class="lni lni-checkmark-circle"></i> Morbi leo risus.</li>
                                <li> <i class="lni lni-checkmark-circle"></i> Excepteur sint occaecat velit.</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    </div>
    <!--/ End Pricing  Area -->
    
@endsection
