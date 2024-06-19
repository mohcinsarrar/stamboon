<!DOCTYPE html>
<html lang="en">

<head>
    <!--====== Required meta tags ======-->
    <meta charset="utf-8" />
    <meta http-equiv="x-ua-compatible" content="ie=edge" />
    <meta name="description" content="" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

    <!--====== Title ======-->
    <title>Business | Bootstrap 5 Business Template</title>

    <!--====== Favicon Icon ======-->
    <link rel="shortcut icon" href="{{ asset('webshop/assets/images/favicon.svg') }}" type="image/svg" />

    <!--====== Bootstrap css ======-->
    <link rel="stylesheet" href="{{ asset('webshop/assets/css/bootstrap.min.css') }}" />

    <!--====== Line Icons css ======-->
    <link rel="stylesheet" href="{{ asset('webshop/assets/css/lineicons.css') }}" />

    <!--====== Tiny Slider css ======-->
    <link rel="stylesheet" href="{{ asset('webshop/assets/css/tiny-slider.css') }}" />

    <!--====== gLightBox css ======-->
    <link rel="stylesheet" href="{{ asset('webshop/assets/css/glightbox.min.css') }}" />

    <link rel="stylesheet" href="{{ asset('webshop/assets/css/style.css') }}" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
        integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>

    <!--====== NAVBAR NINE PART START ======-->

    <section class="navbar-area navbar-nine">
        <div class="container">
            <div class="row justify-content-between align-items-center">
                <div class="col">
                    <nav class="navbar navbar-expand-lg">
                        <a class="navbar-brand" href="index.html">
                            <img src="{{ asset('webshop/assets/images/white-logo.svg') }}" alt="Logo" />
                        </a>
                        <button class="navbar-toggler" type="button" id="side-menu-left">
                            <span class="toggler-icon"></span>
                            <span class="toggler-icon"></span>
                            <span class="toggler-icon"></span>
                        </button>

                        <div class="collapse navbar-collapse sub-menu-bar" id="navbarNine">
                            <ul class="navbar-nav me-auto">

                                @if ($data['aboutus']['enable'] == true)
                                    <li class="nav-item">
                                        <a class="page-scroll active" href="#about-us">About Us</a>
                                    </li>
                                @endif

                                @if ($data['features']['enable'] == true)
                                    <li class="nav-item">
                                        <a class="page-scroll" href="#features">Features</a>
                                    </li>
                                @endif

                                @if ($data['pricing']['enable'] == true)
                                    <li class="nav-item">
                                        <a class="page-scroll" href="#pricing">Pricing</a>
                                    </li>
                                @endif

                                @if ($data['faq']['enable'] == true)
                                    <li class="nav-item">
                                        <a class="page-scroll" href="#faq">FAQ</a>
                                    </li>
                                @endif

                                @if ($data['contact']['enable'] == true)
                                    <li class="nav-item">
                                        <a class="page-scroll" href="#contact">Contact</a>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </nav>

                    <!-- navbar -->
                </div>
                <div class="col-auto d-lg-block d-none login-dropdown">
                    @if (Auth::check())
                        <div class="dropdown">
                            <a class="btn btn-outline-primary dropdown-toggle" href="#" role="button"
                                id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
                                {{ Auth::user()->name }}
                            </a>

                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                <li><a class="dropdown-item"
                                        href="{{ Auth::user()->hasRole('admin') ? route('admin.dashboard.index') : route('users.dashboard.index') }}"><i class="lni lni-grid-alt pe-1"></i> Dashboard</a>
                                </li>
                                <li><a class="dropdown-item" href="{{ route('logout') }}"
                                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();"> <i class="lni lni-power-switch pe-1"></i> Logout</a>
                                </li>
                                <form method="POST" id="logout-form" action="{{ route('logout') }}">
                                    @csrf
                                </form>
                            </ul>
                        </div>
                    @else
                        <a type="button" class="btn login-btn px-3 py-2" href="{{ route('login') }}">
                            <span><i class="fa-solid fa-right-to-bracket pe-lg-2 "></i></span>
                            <span class="d-none d-lg-inline-block">Login/Register</span>
                        </a>
                    @endif

                </div>
            </div>
            <!-- row -->
        </div>
        <!-- container -->
    </section>

    <!--====== NAVBAR NINE PART ENDS ======-->

    <!--====== SIDEBAR PART START ======-->

    <div class="sidebar-left">
        <div class="sidebar-close">
            <a class="close"><i class="lni lni-close"></i></a>
        </div>
        <div class="sidebar-content">
            <div class="sidebar-logo">
                <a href="index.html"><img src="{{ asset('webshop/assets/images/logo.svg') }}" alt="Logo" /></a>
            </div>
            <!-- logo -->
            <div class="">
                @if (Auth::check())
                    <a class="text-dark" href="">{{ Auth::user()->name }}</a>
                @else
                    <a type="button" class="login-btn py-2 text-dark my-2" href="{{ route('register') }}">
                        <span><i class="fa-solid fa-right-to-bracket pe-lg-2 "></i></span>
                        <span class="">Login/Register</span>
                    </a>
                @endif
            </div>
            <div class="sidebar-menu mt-0">
                <ul>
                    @if ($data['aboutus']['enable'] == true)
                        <li>
                            <a class="page-scroll active" href="#about-us">About Us</a>
                        </li>
                    @endif

                    @if ($data['features']['enable'] == true)
                        <li>
                            <a class="page-scroll" href="#features">Features</a>
                        </li>
                    @endif

                    @if ($data['pricing']['enable'] == true)
                        <li>
                            <a class="page-scroll" href="#pricing">Pricing</a>
                        </li>
                    @endif

                    @if ($data['faq']['enable'] == true)
                        <li>
                            <a class="page-scroll" href="#faq">FAQ</a>
                        </li>
                    @endif

                    @if ($data['contact']['enable'] == true)
                        <li>
                            <a class="page-scroll" href="#contact">Contact</a>
                        </li>
                    @endif
                </ul>
            </div>

            <!-- menu -->
            <div class="sidebar-social align-items-center justify-content-center">
                <h5 class="social-title">Follow Us On</h5>
                <ul>
                    <li>
                        <a href="javascript:void(0)"><i class="lni lni-facebook-filled"></i></a>
                    </li>
                    <li>
                        <a href="javascript:void(0)"><i class="lni lni-twitter-original"></i></a>
                    </li>
                    <li>
                        <a href="javascript:void(0)"><i class="lni lni-linkedin-original"></i></a>
                    </li>
                    <li>
                        <a href="javascript:void(0)"><i class="lni lni-youtube"></i></a>
                    </li>
                </ul>
            </div>
            <!-- sidebar social -->
        </div>
        <!-- content -->
    </div>
    <div class="overlay-left"></div>

    <!--====== SIDEBAR PART ENDS ======-->

    <!-- Start header Area -->
    @if ($data['hero']['enable'] == true)
        <section id="hero-area" class="header-area header-eight">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-6 col-md-12 col-12">
                        <div class="header-content">
                            <h1>{{ $data['hero']['title'] }}</h1>
                            <p>
                                {{ $data['hero']['subTitle'] }}
                            </p>
                            <div class="button">
                                <a href="javascript:void(0)"
                                    class="btn primary-btn">{{ $data['hero']['buttonTitle'] }}</a>
                                <a href="https://www.youtube.com/watch?v=r44RKWyfcFw&fbclid=IwAR21beSJORalzmzokxDRcGfkZA1AtRTE__l5N4r09HcGS5Y6vOluyouM9EM"
                                    class="glightbox video-button">
                                    <span class="btn icon-btn rounded-full">
                                        <i class="lni lni-play"></i>
                                    </span>
                                    <span class="text">{{ $data['hero']['videoTitle'] }}</span>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-12 col-12">
                        <div class="header-image">
                            <img src="{{ asset('storage/' . $data['hero']['image']) }}" alt="#" />
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif
    <!-- End header Area -->

    <!--====== ABOUT FIVE PART START ======-->
    @if ($data['aboutus']['enable'] == true)
        <section id="about-us" class="about-area about-five">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-6 col-12">
                        <div class="about-image-five">
                            <svg class="shape" width="106" height="134" viewBox="0 0 106 134" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <circle cx="1.66654" cy="1.66679" r="1.66667" fill="#DADADA" />
                                <circle cx="1.66654" cy="16.3335" r="1.66667" fill="#DADADA" />
                                <circle cx="1.66654" cy="31.0001" r="1.66667" fill="#DADADA" />
                                <circle cx="1.66654" cy="45.6668" r="1.66667" fill="#DADADA" />
                                <circle cx="1.66654" cy="60.3335" r="1.66667" fill="#DADADA" />
                                <circle cx="1.66654" cy="88.6668" r="1.66667" fill="#DADADA" />
                                <circle cx="1.66654" cy="117.667" r="1.66667" fill="#DADADA" />
                                <circle cx="1.66654" cy="74.6668" r="1.66667" fill="#DADADA" />
                                <circle cx="1.66654" cy="103" r="1.66667" fill="#DADADA" />
                                <circle cx="1.66654" cy="132" r="1.66667" fill="#DADADA" />
                                <circle cx="16.3333" cy="1.66679" r="1.66667" fill="#DADADA" />
                                <circle cx="16.3333" cy="16.3335" r="1.66667" fill="#DADADA" />
                                <circle cx="16.3333" cy="31.0001" r="1.66667" fill="#DADADA" />
                                <circle cx="16.3333" cy="45.6668" r="1.66667" fill="#DADADA" />
                                <circle cx="16.333" cy="60.3335" r="1.66667" fill="#DADADA" />
                                <circle cx="16.333" cy="88.6668" r="1.66667" fill="#DADADA" />
                                <circle cx="16.333" cy="117.667" r="1.66667" fill="#DADADA" />
                                <circle cx="16.333" cy="74.6668" r="1.66667" fill="#DADADA" />
                                <circle cx="16.333" cy="103" r="1.66667" fill="#DADADA" />
                                <circle cx="16.333" cy="132" r="1.66667" fill="#DADADA" />
                                <circle cx="30.9998" cy="1.66679" r="1.66667" fill="#DADADA" />
                                <circle cx="74.6665" cy="1.66679" r="1.66667" fill="#DADADA" />
                                <circle cx="30.9998" cy="16.3335" r="1.66667" fill="#DADADA" />
                                <circle cx="74.6665" cy="16.3335" r="1.66667" fill="#DADADA" />
                                <circle cx="30.9998" cy="31.0001" r="1.66667" fill="#DADADA" />
                                <circle cx="74.6665" cy="31.0001" r="1.66667" fill="#DADADA" />
                                <circle cx="30.9998" cy="45.6668" r="1.66667" fill="#DADADA" />
                                <circle cx="74.6665" cy="45.6668" r="1.66667" fill="#DADADA" />
                                <circle cx="31" cy="60.3335" r="1.66667" fill="#DADADA" />
                                <circle cx="74.6668" cy="60.3335" r="1.66667" fill="#DADADA" />
                                <circle cx="31" cy="88.6668" r="1.66667" fill="#DADADA" />
                                <circle cx="74.6668" cy="88.6668" r="1.66667" fill="#DADADA" />
                                <circle cx="31" cy="117.667" r="1.66667" fill="#DADADA" />
                                <circle cx="74.6668" cy="117.667" r="1.66667" fill="#DADADA" />
                                <circle cx="31" cy="74.6668" r="1.66667" fill="#DADADA" />
                                <circle cx="74.6668" cy="74.6668" r="1.66667" fill="#DADADA" />
                                <circle cx="31" cy="103" r="1.66667" fill="#DADADA" />
                                <circle cx="74.6668" cy="103" r="1.66667" fill="#DADADA" />
                                <circle cx="31" cy="132" r="1.66667" fill="#DADADA" />
                                <circle cx="74.6668" cy="132" r="1.66667" fill="#DADADA" />
                                <circle cx="45.6665" cy="1.66679" r="1.66667" fill="#DADADA" />
                                <circle cx="89.3333" cy="1.66679" r="1.66667" fill="#DADADA" />
                                <circle cx="45.6665" cy="16.3335" r="1.66667" fill="#DADADA" />
                                <circle cx="89.3333" cy="16.3335" r="1.66667" fill="#DADADA" />
                                <circle cx="45.6665" cy="31.0001" r="1.66667" fill="#DADADA" />
                                <circle cx="89.3333" cy="31.0001" r="1.66667" fill="#DADADA" />
                                <circle cx="45.6665" cy="45.6668" r="1.66667" fill="#DADADA" />
                                <circle cx="89.3333" cy="45.6668" r="1.66667" fill="#DADADA" />
                                <circle cx="45.6665" cy="60.3335" r="1.66667" fill="#DADADA" />
                                <circle cx="89.3333" cy="60.3335" r="1.66667" fill="#DADADA" />
                                <circle cx="45.6665" cy="88.6668" r="1.66667" fill="#DADADA" />
                                <circle cx="89.3333" cy="88.6668" r="1.66667" fill="#DADADA" />
                                <circle cx="45.6665" cy="117.667" r="1.66667" fill="#DADADA" />
                                <circle cx="89.3333" cy="117.667" r="1.66667" fill="#DADADA" />
                                <circle cx="45.6665" cy="74.6668" r="1.66667" fill="#DADADA" />
                                <circle cx="89.3333" cy="74.6668" r="1.66667" fill="#DADADA" />
                                <circle cx="45.6665" cy="103" r="1.66667" fill="#DADADA" />
                                <circle cx="89.3333" cy="103" r="1.66667" fill="#DADADA" />
                                <circle cx="45.6665" cy="132" r="1.66667" fill="#DADADA" />
                                <circle cx="89.3333" cy="132" r="1.66667" fill="#DADADA" />
                                <circle cx="60.3333" cy="1.66679" r="1.66667" fill="#DADADA" />
                                <circle cx="104" cy="1.66679" r="1.66667" fill="#DADADA" />
                                <circle cx="60.3333" cy="16.3335" r="1.66667" fill="#DADADA" />
                                <circle cx="104" cy="16.3335" r="1.66667" fill="#DADADA" />
                                <circle cx="60.3333" cy="31.0001" r="1.66667" fill="#DADADA" />
                                <circle cx="104" cy="31.0001" r="1.66667" fill="#DADADA" />
                                <circle cx="60.3333" cy="45.6668" r="1.66667" fill="#DADADA" />
                                <circle cx="104" cy="45.6668" r="1.66667" fill="#DADADA" />
                                <circle cx="60.333" cy="60.3335" r="1.66667" fill="#DADADA" />
                                <circle cx="104" cy="60.3335" r="1.66667" fill="#DADADA" />
                                <circle cx="60.333" cy="88.6668" r="1.66667" fill="#DADADA" />
                                <circle cx="104" cy="88.6668" r="1.66667" fill="#DADADA" />
                                <circle cx="60.333" cy="117.667" r="1.66667" fill="#DADADA" />
                                <circle cx="104" cy="117.667" r="1.66667" fill="#DADADA" />
                                <circle cx="60.333" cy="74.6668" r="1.66667" fill="#DADADA" />
                                <circle cx="104" cy="74.6668" r="1.66667" fill="#DADADA" />
                                <circle cx="60.333" cy="103" r="1.66667" fill="#DADADA" />
                                <circle cx="104" cy="103" r="1.66667" fill="#DADADA" />
                                <circle cx="60.333" cy="132" r="1.66667" fill="#DADADA" />
                                <circle cx="104" cy="132" r="1.66667" fill="#DADADA" />
                            </svg>
                            <img src="{{ asset('storage/' . $data['aboutus']['image']) }}" alt="about" />
                        </div>
                    </div>
                    <div class="col-lg-6 col-12">
                        <div class="about-five-content">
                            <h6 class="small-title text-lg">About Us</h6>
                            <h2 class="main-title fw-bold">{{ $data['aboutus']['title'] }}</h2>
                            <div class="about-five-tab mt-0">
                                <div class="tab-content" id="nav-tabContent">
                                    <div class="tab-pane fade show active" id="nav-who" role="tabpanel"
                                        aria-labelledby="nav-who-tab">
                                        <p>{{ $data['aboutus']['subTitle'] }}</p>
                                        @foreach ($data['aboutus']['paragraphs'] as $paragraph)
                                            <p class="fw-bold">{{ $paragraph['title'] }}</p>
                                            <p>{{ $paragraph['content'] }}</p>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- container -->
        </section>
    @endif
    <!--====== ABOUT FIVE PART ENDS ======-->

    <!-- ===== service-area start ===== -->
    @if ($data['features']['enable'] == true)
        <section id="features" class="services-area services-eight">
            <!--======  Start Section Title Five ======-->
            <div class="section-title-five">
                <div class="container">
                    <div class="row">
                        <div class="col-12">
                            <div class="content">
                                <h6>Features</h6>
                                <h2 class="fw-bold">{{ $data['features']['title'] }}</h2>
                                <p>
                                    {{ $data['features']['subTitle'] }}
                                </p>
                            </div>
                        </div>
                    </div>
                    <!-- row -->
                </div>
                <!-- container -->
            </div>
            <!--======  End Section Title Five ======-->
            <div class="container">
                <div class="row row-cols-1 row-cols-md-3 g-4">
                    @foreach ($data['features']['features'] as $feature)
                        <div class="col">
                            <div class="single-services h-100">
                                <div class="service-content">
                                    <h4>{{ $feature['title'] }}</h4>
                                    <p>
                                        {{ $feature['description'] }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
    <!-- ===== service-area end ===== -->


    <!-- Start Pricing  Area -->
    @if ($data['pricing']['enable'] == true)
        <section id="pricing" class="pricing-area pricing-fourteen">
            <!--======  Start Section Title Five ======-->
            <div class="section-title-five">
                <div class="container">
                    <div class="row">
                        <div class="col-12">
                            <div class="content">
                                <h6>Pricing</h6>
                                <h2 class="fw-bold">{{ $data['pricing']['title'] }}</h2>
                                <p>
                                    {{ $data['pricing']['subTitle'] }}
                                </p>
                            </div>
                        </div>
                    </div>
                    <!-- row -->
                </div>
                <!-- container -->
            </div>
            <!--======  End Section Title Five ======-->
            <div class="container">
                <div class="row row-cols-1 row-cols-md-3 g-4">
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
                                    @if(Auth::check())
                                    <a href="{{ Auth::user()->hasRole('admin') ? route('admin.dashboard.index') : route('users.dashboard.index') }}" class="btn primary-btn-outline">
                                        Purchase
                                    </a>
                                    @else
                                    <a href="{{ route('register') }}" class="btn primary-btn-outline">
                                        Purchase
                                    </a>
                                    @endif
                                </div>

                                <div class="table-content">
                                    <ul class="table-list">
                                        @if($product->features != null)
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
    @endif
    <!--/ End Pricing  Area -->



    <!-- Start Cta Area -->
    @if ($data['cta']['enable'] == true)
        <section id="call-action" class="call-action">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-xxl-6 col-xl-7 col-lg-8 col-md-9">
                        <div class="inner-content">
                            <h2>{{ $data['cta']['title'] }}</h2>
                            <p>
                                {{ $data['cta']['subTitle'] }}
                            </p>
                            <div class="light-rounded-buttons">
                                <a href="{{ route('register') }}"
                                    class="btn primary-btn-outline">{{ $data['cta']['buttonTitle'] }}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif
    <!-- End Cta Area -->

    <!-- ========================= faq-section start ========================= -->
    @if ($data['faq']['enable'] == true)
        <section id="faq" class="contact-section bsb-faq-2">
            <div class="section-title-five">
                <div class="container">
                    <div class="row">
                        <div class="col-12">
                            <div class="content">
                                <h6>FAQ</h6>
                                <h2 class="fw-bold">{{ $data['faq']['title'] }}</h2>
                            </div>
                        </div>
                    </div>
                    <!-- row -->
                </div>
                <!-- container -->
            </div>
            <div class="container mb-5">
                <div class="row justify-content-xl-end">
                    <div class="col-12 col-xl-11">
                        <div class="accordion accordion-flush" id="accordionExample">
                            @foreach ($data['faq']['questions'] as $question)
                                <div class="accordion-item mb-4 shadow-sm">
                                    <h2 class="accordion-header" id="headingOne">
                                        <button class="accordion-button collapsed bg-transparent fw-bold"
                                            type="button" data-bs-toggle="collapse"
                                            data-bs-target="#collapse{{ $loop->index }}" aria-expanded="false"
                                            aria-controls="collapse{{ $loop->index }}">
                                            {{ $question['question'] }}
                                        </button>
                                    </h2>
                                    <div id="collapse{{ $loop->index }}" class="accordion-collapse collapse"
                                        aria-labelledby="heading{{ $loop->index }}"
                                        data-bs-parent="#accordionExample">
                                        <div class="accordion-body">
                                            <p>{{ $question['response'] }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif
    <!-- ========================= faq-section end ========================= -->

    <!-- ========================= contact-section start ========================= -->
    @if ($data['contact']['enable'] == true)
        <section id="contact" class="contact-section about-five">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-xl-8">
                        <div class="contact-form-wrapper">
                            <div class="row">
                                <div class="col-xl-10 col-lg-8 mx-auto">
                                    <div class="section-title text-center">
                                        <span> Contact </span>
                                        <h2>
                                            {{ $data['contact']['title'] }}
                                        </h2>
                                        <p>
                                            {{ $data['contact']['subTitle'] }}
                                        </p>
                                    </div>
                                    @if ($message = Session::get('error'))
                                        <div class="alert alert-danger" role="alert">
                                            {{ $message }}
                                        </div>
                                    @endif
                                    @if ($message = Session::get('success'))
                                        <div class="alert alert-success" role="alert">
                                            {{ $message }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <form action="{{ route('contact') }}" class="contact-form" method="POST">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6">
                                        <input type="text" name="name" id="name" placeholder="Name"
                                            required />
                                    </div>
                                    <div class="col-md-6">
                                        <input type="email" name="email" id="email" placeholder="Email"
                                            required />
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <input type="text" name="subject" id="subject" placeholder="Subject"
                                            required />
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <textarea name="message" id="message" placeholder="Type Message" rows="5" minlength="10" required></textarea>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="button text-center rounded-buttons">
                                            <button type="submit" class="btn primary-btn rounded-full">
                                                Send Message
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif
    <!-- ========================= contact-section end ========================= -->


    <!-- Start Footer Area -->
    @if(false == true)
    <footer class="footer-area footer-eleven mt-0">
        <!-- Start Footer Top -->
        <div class="footer-top">
            <div class="container">
                <div class="inner-content">
                    <div class="row justify-content-between">
                        <div class="col-lg-4 col-md-6 col-12">
                            <!-- Single Widget -->
                            <div class="footer-widget f-about">
                                <div class="logo">
                                    <a href="index.html">
                                        <img src="{{ asset('webshop/assets/images/logo.svg') }}" alt="#"
                                            class="img-fluid" />
                                    </a>
                                </div>
                                <p>
                                    Making the world a better place through constructing elegant
                                    hierarchies.
                                </p>
                            </div>
                            <!-- End Single Widget -->
                        </div>
                        <div class="col-lg-2 col-md-6 col-12">
                            <!-- Single Widget -->
                            <div class="sidebar-social align-items-center justify-content-center">
                                <h5 class="social-title">Follow Us On</h5>
                                <ul>
                                    <li>
                                        <a href="javascript:void(0)"><i class="lni lni-facebook-filled"></i></a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0)"><i class="lni lni-twitter-original"></i></a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0)"><i class="lni lni-linkedin-original"></i></a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0)"><i class="lni lni-youtube"></i></a>
                                    </li>
                                </ul>
                            </div>
                            <!-- End Single Widget -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--/ End Footer Top -->
    </footer>
    @endif
    <!--/ End Footer Area -->



    <a href="#" class="scroll-top btn-hover">
        <i class="lni lni-chevron-up"></i>
    </a>

    <!--====== js ======-->
    <script src="{{ asset('webshop/assets/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('webshop/assets/js/glightbox.min.js') }}"></script>
    <script src="{{ asset('webshop/assets/js/main.js') }}"></script>
    <script src="{{ asset('webshop/assets/js/tiny-slider.js') }}"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/js/all.min.js"
        integrity="sha512-u3fPA7V8qQmhBPNT5quvaXVa1mnnLSXUep5PS1qo5NRzHwG19aHmNJnj1Q8hpA/nBWZtZD4r4AX6YOt5ynLN2g=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>
        //===== close navbar-collapse when a  clicked
        /*
        let navbarTogglerNine = document.querySelector(
          ".navbar-nine .navbar-toggler"
        );
        navbarTogglerNine.addEventListener("click", function () {
          navbarTogglerNine.classList.toggle("active");
        });
        */
        // ==== left sidebar toggle
        let sidebarLeft = document.querySelector(".sidebar-left");
        let overlayLeft = document.querySelector(".overlay-left");
        let sidebarClose = document.querySelector(".sidebar-close .close");

        overlayLeft.addEventListener("click", function() {
            sidebarLeft.classList.toggle("open");
            overlayLeft.classList.toggle("open");
        });
        sidebarClose.addEventListener("click", function() {
            sidebarLeft.classList.remove("open");
            overlayLeft.classList.remove("open");
        });

        // ===== navbar nine sideMenu
        let sideMenuLeftNine = document.querySelector("#side-menu-left");

        sideMenuLeftNine.addEventListener("click", function() {
            sidebarLeft.classList.add("open");
            overlayLeft.classList.add("open");
        });

        //========= glightbox
        GLightbox({
            'href': 'https://www.youtube.com/watch?v=r44RKWyfcFw&fbclid=IwAR21beSJORalzmzokxDRcGfkZA1AtRTE__l5N4r09HcGS5Y6vOluyouM9EM',
            'type': 'video',
            'source': 'youtube', //vimeo, youtube or local
            'width': 900,
            'autoplayVideos': true,
        });
    </script>
</body>

</html>
