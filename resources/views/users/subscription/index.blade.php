@php
    $configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Subscription')

@section('page-style')
    <!-- Page -->
    <style>
        /*===== PRICING THIRTEEN =====*/
.pricing-fourteen {
  padding: 100px 0;
}

@media only screen and (min-width: 768px) and (max-width: 991px) {
  .pricing-fourteen {
    padding: 80px;
  }
}

@media (max-width: 767px) {
  .pricing-fourteen {
    padding: 60px 0;
  }
}

.pricing-style-fourteen {
  border: 1px solid var(--bs-light);
  border-radius: 10px;
  margin-top: 30px;
  transition: all 0.4s ease;
  padding: 50px 35px;
  text-align: center;
  z-index: 0;
}

.pricing-style-fourteen:hover {
  box-shadow: var(--shadow-4);
}

.pricing-style-fourteen.middle {
  box-shadow: var(--shadow-4);
  border-color: var(--bs-primary);
}

.pricing-style-fourteen .purchase-btn a.current{
  background-color: var(--bs-primary);
  border-color: var(--bs-primary);
  color:white;
}

.pricing-style-fourteen.middle .title {
  border-color: var(--bs-primary);
  background: var(--bs-primary);
  color: var(--white);
}

.pricing-style-fourteen .title {
  font-weight: 500;
  margin-bottom: 25px;
  color: var(--bs-primary);
  padding: 8px 20px;
  border: 2px solid var(--bs-primary);
  display: inline-block;
  border-radius: 30px;
  font-size: 16px;
}

.pricing-style-fourteen .table-head p {
  color: var(--dark-3);
}

.pricing-style-fourteen .price {
  padding-top: 30px;
}

.pricing-style-fourteen .amount {
  font-weight: 600;
  display: inline-block;
  position: relative;
  padding-left: 15px;
  font-size: 55px;
}

.pricing-style-fourteen .currency {
  font-weight: 400;
  color: var(--dark-3);
  font-size: 20px;
  position: absolute;
  left: 0;
  top: 6px;
}

.pricing-style-fourteen .duration {
  display: inline-block;
  font-size: 18px;
  color: var(--dark-3);
  font-weight: 400;
  font-size: 20px;
}

.pricing-style-fourteen .light-rounded-buttons {
  margin: 0;
  margin-top: 30px;
  margin-bottom: 40px;
}

.pricing-style-fourteen .table-list li {
  position: relative;
  margin-bottom: 10px;
  color: var(--dark-3);
  text-align: left;
}

.pricing-style-fourteen .table-list li:last-child {
  margin: 0;
}

.pricing-style-fourteen .table-list li i {
  color: var(--bs-primary);
  font-size: 16px;
  padding-right: 8px;
}

.pricing-style-fourteen .table-list li i.deactive {
  color: var(--dark-3);
}


    </style>
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
        <div class="card mb-6 text-dark mt-4">
            <h5 class="card-header">Current Plan</h5>
            <div class="card-body">
                <div class="row row-gap-4 row-gap-xl-0">
                    <div class="col-xl-6 order-1 order-xl-0">
                        <div class="mb-4">
                            <h6 class="mb-1  text-dark">Your Current Plan is {{ $payment->product->name }}</h6>
                            <p class=" text-dark">{{ $payment->product->description }}</p>
                        </div>
                        <div class="mb-4">
                            <h6 class="mb-1  text-dark">Active until {{ $payment->active_until() }}</h6>
                            <p class=" text-dark">We will send you a notification upon Subscription expiration</p>
                        </div>
                    </div>
                    <div class="col-xl-6 order-0 order-xl-0">
                        @if ($payment->countdown()['percentage'] > 75)
                            <div class="alert alert-warning" role="alert">
                                <h5 class="alert-heading mb-2  text-dark">We need your attention!</h5>
                                <span class=" text-dark">Your plan requires update</span>
                            </div>
                        @endif
                        <div class="plan-statistics ">
                            <div class="d-flex justify-content-between">
                                <h6 class="mb-1 text-dark">Days</h6>
                                <h6 class="mb-1 text-dark">{{ $payment->countdown()['passedDays'] }} of
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
                                    <h6 class="title {{ $loop->index == 1 ? 'text-white' : '' }}">{{ $product->name }}</h4>
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
