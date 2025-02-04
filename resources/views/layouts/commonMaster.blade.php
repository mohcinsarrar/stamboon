<!DOCTYPE html>

<html lang="{{ session()->get('locale') ?? app()->getLocale() }}"
    class="{{ $configData['style'] }}-style {{ $navbarFixed ?? '' }} {{ $menuFixed ?? '' }} {{ $menuCollapsed ?? '' }} {{ $footerFixed ?? '' }} {{ $customizerHidden ?? '' }}"
    dir="{{ $configData['textDirection'] }}" data-theme="{{ $configData['theme'] }}"
    data-assets-path="{{ asset('/assets') . '/' }}" data-base-url="{{ url('/') }}" data-framework="laravel"
    data-template="{{ $configData['layout'] . '-menu-' . $configData['theme'] . '-' . $configData['style'] }}">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>@yield('title') |
        {{ config('variables.templateName') ? config('variables.templateName') : 'TemplateName' }}
    </title>
    <meta name="description"
        content="{{ config('variables.templateDescription') ? config('variables.templateDescription') : '' }}" />
    <meta name="keywords"
        content="{{ config('variables.templateKeyword') ? config('variables.templateKeyword') : '' }}">
    <!-- laravel CRUD token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Canonical SEO -->
    <link rel="canonical" href="{{ config('variables.productPage') ? config('variables.productPage') : '' }}">
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/favicon/favicon.ico') }}" />

    <link href="https://fonts.googleapis.com/css2?family=Charm:wght@400;700&display=swap" rel="stylesheet">

    <!-- toastr -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/toastr/toastr.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/animate-css/animate.css') }}" />

    <!-- Include Styles -->
    @include('layouts/sections/styles')

    <!-- Include Scripts for customizer, helper, analytics, config -->
    @include('layouts/sections/scriptsIncludes')
</head>

<body>

    <!-- Include flash messages to show errors, infos ... -->
    @include('_partials.flash-message')


    <!-- Layout Content -->
    @yield('layoutContent')
    <!--/ Layout Content -->
    <!-- Toast with Animation -->
    <div class="bs-toast toast toast-ex animate__animated my-2" role="alert" aria-live="assertive" aria-atomic="true"
        data-bs-delay="5000">
        <div class="toast-header text-white">
            <div class="icon text-white"><i class="ti ti-bell ti-xs me-2"></i></div>
            <div class="me-auto fw-semibold title">Bootstrap</div>
            <small class="text-muted datetime"></small>
            <button type="button" class="btn-close bg-white" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body">
            Hello, world! This is a toast message.
        </div>
    </div>


    <!-- Include Scripts -->
    @include('layouts/sections/scripts')

    <script src="{{ asset('assets/js/ui-toasts.js') }}"></script>
    <script>
        function show_toast(type, title, msg, delay = "5000") {
            const toastAnimationExample = document.querySelector('.toast-ex')
            toastAnimationExample.setAttribute("data-bs-delay", delay);
            toastAnimationExample.classList.add("animate__tada");
            toastAnimationExample.querySelector('.toast-header').classList = ['toast-header text-white'];
            toastAnimationExample.querySelector('.toast-header').classList.add("bg-" + type);
            toastAnimationExample.querySelector('.title').innerHTML = title;
            toastAnimationExample.querySelector('.toast-body').innerHTML = msg;

            toastAnimation = new bootstrap.Toast(toastAnimationExample);
            toastAnimation.show();
        }
    </script>
    <script>
        var toastElList = [].slice.call(document.querySelectorAll('.toast-flash-message '))
        var toastList = toastElList.map(function(toastEl) {
            const toast = new bootstrap.Toast(toastEl)
            toast.show()

        })
    </script>
    <script>
        $(function() {
            $('[data-toggle="tooltip"]').tooltip()
        })
        // Initialize tooltip
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'))
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    </script>
    <script>
        function checkAuth() {
            fetch('/check-auth')
                .then(response => response.json())
                .then(data => {
                    if (!data.authenticated) {
                        window.location.href = "/login"; // Redirect to login page
                    }
                })
                .catch(error => console.error('Auth check failed:', error));
        }

        // Check authentication every 60 seconds (adjust as needed)
        setInterval(checkAuth, 60000);
    </script>

</body>

</html>
