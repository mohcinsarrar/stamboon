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
                                <li class="nav-item">
                                    <a class="page-scroll active" href="#about-us">About Us</a>
                                </li>
                                <li class="nav-item">
                                    <a class="page-scroll" href="#features">Features</a>
                                </li>

                                <li class="nav-item">
                                    <a class="page-scroll" href="#pricing">Pricing</a>
                                </li>
                                <li class="nav-item">
                                    <a class="page-scroll" href="#contact">Contact</a>
                                </li>
                            </ul>
                        </div>
                    </nav>

                    <!-- navbar -->
                </div>
                <div class="col-auto d-lg-block d-none">
                    @if (Auth::check())
                        <div class="dropdown">
                            <a class="btn btn-outline-primary dropdown-toggle" href="#" role="button"
                                id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
                                {{ Auth::user()->name }}
                            </a>

                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                <li><a class="dropdown-item" href="{{ route('familytree.dashboard.index')}}">Dashboard</a></li>
                                <li><a class="dropdown-item" href="{{ route('logout') }}"
                                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
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
                    <li><a href="#about-us">About Us</a></li>
                    <li><a href="#features">Features</a></li>
                    <li><a href="#pricing">Pricing</a></li>
                    <li><a href="#contact">Contact</a></li>
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
    <section id="hero-area" class="header-area header-eight">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 col-md-12 col-12">
                    <div class="header-content">
                        <h1>Create your family history</h1>
                        <p>
                            Every person deserves to be remembered.
                        </p>
                        <div class="button">
                            <a href="javascript:void(0)" class="btn primary-btn">Get Started</a>
                            <a href="https://www.youtube.com/watch?v=r44RKWyfcFw&fbclid=IwAR21beSJORalzmzokxDRcGfkZA1AtRTE__l5N4r09HcGS5Y6vOluyouM9EM"
                                class="glightbox video-button">
                                <span class="btn icon-btn rounded-full">
                                    <i class="lni lni-play"></i>
                                </span>
                                <span class="text">Watch Intro</span>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-12 col-12">
                    <div class="header-image">
                        <img src="{{ asset('webshop/assets/images/hero2.webp') }}" alt="#" />
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End header Area -->

    <!--====== ABOUT FIVE PART START ======-->

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
                        <img src="{{ asset('webshop/assets/images/about.webp') }}" alt="about" />
                    </div>
                </div>
                <div class="col-lg-6 col-12">
                    <div class="about-five-content">
                        <h6 class="small-title text-lg">About Us</h6>
                        <h2 class="main-title fw-bold">Unique approach of your family tree</h2>
                        <div class="about-five-tab mt-0">
                            <div class="tab-content" id="nav-tabContent">
                                <div class="tab-pane fade show active" id="nav-who" role="tabpanel"
                                    aria-labelledby="nav-who-tab">
                                    <p>The Stamboom lets you create your familytree with great ease, in an attractive
                                        overview. The tool enables easy input of family data and results in a
                                        show-worthy result.</p>
                                    <p class="fw-bold">How The Stamboom was created</p>
                                    <p>When working on the family history, you will gather a lot of data. At some point
                                        you want to put all that in an attractive overiew. The web offers many methods,
                                        but to find the right one turned out to be diffiult. That is why The Stamboom
                                        had a smart tool develloped to do so. The tool saves a lot of time and offers an
                                        attractive solution to show your familytree.</p>
                                    <p class="fw-bold">The added value of The Stamboom</p>
                                    <p>By enabling this tool for everybody, we can keep the prices relatively low. In
                                        this way many people can save time and create a desirable result. We use the
                                        feedback of our users to constantly improve our website.</p>
                                    <p class="fw-bold">We would appreciate you contacting us!</p>
                                    <p>Do you want to discuss any matters on family trees or family history with us,
                                        feel free to contact us. We like that and we will always try to reply as soon as
                                        possible. Contact us here.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- container -->
    </section>

    <!--====== ABOUT FIVE PART ENDS ======-->

    <!-- ===== service-area start ===== -->
    <section id="features" class="services-area services-eight">
        <!--======  Start Section Title Five ======-->
        <div class="section-title-five">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="content">
                            <h6>Features</h6>
                            <h2 class="fw-bold">Our Features</h2>
                            <p>
                                Easily ceate an attractive familytree of your ancestry. The Stamboom tool generates a
                                showworthy result to be used as overview, walldecoration or gift. Easy to set up and
                                adapt to your own wishes.
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
            <div class="row">
                <div class="col-lg-4 col-md-6">
                    <div class="single-services">
                        <div class="service-icon">
                            <i class="lni lni-capsule"></i>
                        </div>
                        <div class="service-content">
                            <h4>Visualize and Annoate Family Tree</h4>
                            <p>
                                Lorem ipsum dolor sit amet, adipscing elitr, sed diam nonumy
                                eirmod tempor ividunt labor dolore magna.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="single-services">
                        <div class="service-icon">
                            <i class="lni lni-bootstrap"></i>
                        </div>
                        <div class="service-content">
                            <h4>Printing Options</h4>
                            <p>
                                Lorem ipsum dolor sit amet, adipscing elitr, sed diam nonumy
                                eirmod tempor ividunt labor dolore magna.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="single-services">
                        <div class="service-icon">
                            <i class="lni lni-shortcode"></i>
                        </div>
                        <div class="service-content">
                            <h4>Only 4 easy steps </h4>
                            <p>
                                Lorem ipsum dolor sit amet, adipscing elitr, sed diam nonumy
                                eirmod tempor ividunt labor dolore magna.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- ===== service-area end ===== -->


    <!-- Start Pricing  Area -->
    <section id="pricing" class="pricing-area pricing-fourteen">
        <!--======  Start Section Title Five ======-->
        <div class="section-title-five">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="content">
                            <h6>Pricing</h6>
                            <h2 class="fw-bold">Pricing</h2>
                            <p>
                                There are many variations of passages of Lorem Ipsum available,
                                but the majority have suffered alteration in some form.
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
                            <a href="{{ route('register') }}" class="btn primary-btn-outline">
                                Purchase
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
                            <a href="{{ route('register') }}" class="btn primary-btn">
                                Purchase
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
                            <a href="{{ route('register') }}" class="btn primary-btn-outline">
                                Purchase
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
    <!--/ End Pricing  Area -->



    <!-- Start Cta Area -->
    <section id="call-action" class="call-action">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xxl-6 col-xl-7 col-lg-8 col-md-9">
                    <div class="inner-content">
                        <h2>We love to make perfect <br />solutions for your business</h2>
                        <p>
                            Why I say old chap that is, spiffing off his nut cor blimey
                            guvnords geeza<br />
                            bloke knees up bobby, sloshed arse William cack Richard. Bloke
                            fanny around chesed of bum bag old lost the pilot say there
                            spiffing off his nut.
                        </p>
                        <div class="light-rounded-buttons">
                            <a href="javascript:void(0)" class="btn primary-btn-outline">Get Started</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End Cta Area -->


    <!-- ========================= contact-section start ========================= -->
    <section id="contact" class="contact-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-8">
                    <div class="contact-form-wrapper">
                        <div class="row">
                            <div class="col-xl-10 col-lg-8 mx-auto">
                                <div class="section-title text-center">
                                    <span> Get in Touch </span>
                                    <h2>
                                        Ready to Get Started
                                    </h2>
                                    <p>
                                        At vero eos et accusamus et iusto odio dignissimos ducimus
                                        quiblanditiis praesentium
                                    </p>
                                </div>
                            </div>
                        </div>
                        <form action="#" class="contact-form">
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
                                <div class="col-md-6">
                                    <input type="text" name="phone" id="phone" placeholder="Phone"
                                        required />
                                </div>
                                <div class="col-md-6">
                                    <input type="text" name="subject" id="email" placeholder="Subject"
                                        required />
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <textarea name="message" id="message" placeholder="Type Message" rows="5"></textarea>
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
    <!-- ========================= contact-section end ========================= -->


    <!-- Start Footer Area -->
    <footer class="footer-area footer-eleven">
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
