<!DOCTYPE html>
<html lang="en">

<head>
    <!--====== Required meta tags ======-->
    <meta charset="utf-8" />
    <meta http-equiv="x-ua-compatible" content="ie=edge" />
    <meta name="description" content="" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

    <!--====== Title ======-->
    <title>The Stamboom</title>

    <!--====== Favicon Icon ======-->
    <link rel="shortcut icon" href="{{ asset('webshop/assets/images/favicon.ico') }}" type="image/svg" />

    <!--====== Bootstrap css ======-->
    <link rel="stylesheet" href="{{ asset('webshop/assets/css/bootstrap.min.css') }}" />

    <!--====== Line Icons css ======-->
    <link rel="stylesheet" href="{{ asset('webshop/assets/css/lineicons.css') }}" />

    <!--====== Tiny Slider css ======-->
    <link rel="stylesheet" href="{{ asset('webshop/assets/css/tiny-slider.css') }}" />

    <!--====== gLightBox css ======-->
    <link rel="stylesheet" href="{{ asset('webshop/assets/css/glightbox.min.css') }}" />

    <link rel="stylesheet" href="{{ asset(mix('assets/vendor/fonts/tabler-icons.css')) }}" />
    @include('website.style')

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
        integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/quill/editor.css') }}" />

    <style>
        html,
        body {
            height: 100%;
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            margin-top: 15px;
            margin-bottom: 10px;
        }

        p {
            margin-top: 10px;
            margin-bottom: 10px;
        }
    </style>
</head>

<body class="d-flex flex-column h-100">

    <!--====== NAVBAR NINE PART START ======-->

    <section class="navbar-area navbar-nine">
        <div class="container">
            <div class="row justify-content-between align-items-center">
                <div class="col">
                    <nav class="navbar navbar-expand-lg">
                        <a class="navbar-brand" href="{{ route('webshop.index') }}">
                            <img src="{{ asset('storage/' . $data['colors']['logo']) }}" alt="Logo" width="184"
                                height="40" style="object-fit: contain" />
                        </a>
                        <button class="navbar-toggler" type="button" id="side-menu-left">
                            <span class="toggler-icon"></span>
                            <span class="toggler-icon"></span>
                            <span class="toggler-icon"></span>
                        </button>

                        <div class="collapse navbar-collapse sub-menu-bar" id="navbarNine">

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
                                        href="{{ Auth::user()->hasRole('admin') ? route('admin.dashboard.index') : route('users.dashboard.index') }}"><i
                                            class="lni lni-grid-alt pe-1"></i> Dashboard</a>
                                </li>
                                <li><a class="dropdown-item" href="{{ route('logout') }}"
                                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <i class="lni lni-power-switch pe-1"></i> Logout</a>
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
            <div class="sidebar-logo mb-4">
                <a href="{{ route('webshop.index') }}"><img src="{{ asset('storage/' . $data['colors']['logo']) }}"
                        alt="Logo" width="184" height="40" style="object-fit: contain" /></a>
            </div>
            <!-- logo -->
            <div class="">
                @if (Auth::check())
                    <ul>
                        <li>
                            <h4 class="dropdown-item"> {{ Auth::user()->name }}</h4>
                        </li>
                        <li><a class="dropdown-item"
                                href="{{ Auth::user()->hasRole('admin') ? route('admin.dashboard.index') : route('users.dashboard.index') }}"><i
                                    class="lni lni-grid-alt pe-1"></i> Dashboard</a>
                        </li>
                        <li><a class="dropdown-item" href="{{ route('logout') }}"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="lni lni-power-switch pe-1"></i> Logout</a>
                        </li>
                        <form method="POST" id="logout-form" action="{{ route('logout') }}">
                            @csrf
                        </form>
                    </ul>
                @else
                    <a type="button" class="login-btn py-2 text-dark my-2" href="{{ route('register') }}">
                        <span><i class="fa-solid fa-right-to-bracket pe-lg-2 "></i></span>
                        <span class="">Login/Register</span>
                    </a>
                @endif
            </div>
            <div class="sidebar-menu mt-0">

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

    <section class="" style="padding: 50px 0 15px 0;">
        <div class="page-title" style="background-color:var(--primary-dark)">
            <div class="container py-5">
                <div class="row">
                    <div class="col">
                        <h2 class="text-white">{{ $page['title'] }}</h2>
                    </div>
                </div>
            </div>
        </div>
        <div class="page-content py-5">
            <div class="container">
                <div class="row">
                    <div class="col">
                        {!! $page['content'] !!}
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- Start Footer Area -->

    <footer class="footer-area footer-eleven mt-0">
        <!-- Start Footer Top -->
        <div class="container">
            <div class="d-flex flex-wrap justify-content-between align-items-center">
                <div class="col-md-4 d-flex align-items-center">
                    <a href="/" class="mb-3 me-2 mb-md-0  text-decoration-none lh-1">
                        <svg class="bi" width="30" height="24">
                            <use xlink:href="#bootstrap"></use>
                        </svg>
                    </a>
                    <span class="mb-3 mb-md-0 ">© <span id="copyright">
                            <script>
                                document.getElementById('copyright').appendChild(document.createTextNode(new Date().getFullYear()))
                            </script>
                        </span> The Stamboom,</span>
                </div>
                <ul class="nav justify-content-center pages">
                    @foreach ($pages as $page)
                        <li class="nav-item"><a href="{{ route('webshop.pages', $page['slug']) }}"
                                class="nav-link px-2">{{ $page['title'] }}</a></li>
                    @endforeach
                </ul>
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
    <script src="{{ asset('webshop/assets/js/main.js') }}"></script>
    <script src="{{ asset('webshop/assets/js/tiny-slider.js') }}"></script>

    <script src="{{ asset('assets/vendor/libs/font-awesome/all.min.js') }}"
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
    </script>
</body>

</html>
