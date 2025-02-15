@php
    $containerNav = $containerNav ?? 'container-fluid';
    $navbarDetached = $navbarDetached ?? '';
@endphp

<!-- Navbar -->
@if (isset($navbarDetached) && $navbarDetached == 'navbar-detached')
    <nav class="layout-navbar {{ $containerNav }} navbar navbar-expand-xl {{ $navbarDetached }} align-items-center bg-navbar-theme"
        id="layout-navbar">
@endif
@if (isset($navbarDetached) && $navbarDetached == '')
    <nav class="layout-navbar navbar navbar-expand-xl align-items-center bg-navbar-theme" id="layout-navbar">
        <div class="{{ $containerNav }}">
@endif

<!--  Brand demo (display only for navbar-full and hide on below xl) -->
@if (isset($navbarFull))
    <div class="navbar-brand app-brand demo d-none d-xl-flex py-0 me-4">
        <a href="{{ url('/') }}" class="app-brand-link gap-2">
            <span class="app-brand-logo demo">
                @include('_partials.macros', ['height' => 20])
            </span>
        </a>
    </div>
@endif

<!-- ! Not required for layout-without-menu -->
@if (!isset($navbarHideToggle))
    <div
        class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0{{ isset($menuHorizontal) ? ' d-xl-none ' : '' }} {{ isset($contentNavbar) ? ' d-xl-none ' : '' }}">
        <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
            <i class="ti ti-menu-2 ti-sm"></i>
        </a>
    </div>
@endif

<div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">

    <!-- Style Switcher -->
    <!--
    <div class="navbar-nav align-items-center">
        <a class="nav-link style-switcher-toggle hide-arrow" href="javascript:void(0);">
            <i class='ti ti-sm'></i>
        </a>
    </div>
    -->
    <!--/ Style Switcher -->

    <ul class="navbar-nav flex-row align-items-center ms-auto">
        <li class="nav-item dropdown-notifications navbar-dropdown dropdown me-3 me-xl-1">
            <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown"
                data-bs-auto-close="outside" aria-expanded="false">
                <i class="ti ti-bell ti-md"></i>
                @if (auth()->user()->getNotificationUnread()->count() > 0)
                    <span
                        class="badge bg-danger rounded-pill badge-notifications">{{ auth()->user()->getNotificationUnread()->count() }}</span>
                @endif
            </a>
            <ul class="dropdown-menu dropdown-menu-end py-0">
                <li class="dropdown-menu-header border-bottom">
                    <div class="dropdown-header d-flex align-items-center py-3">
                        <h5 class="text-body mb-0 me-auto">Notification</h5>
                    </div>
                </li>
                <li class="dropdown-notifications-list scrollable-container">
                    <ul class="list-group list-group-flush">
                        @foreach (auth()->user()->getNotificationUnread() as $notification)
                            <li class="list-group-item list-group-item-action dropdown-notifications-item">
                                @role('user')
                                    <form id="markasread-{{ $notification->id }}"
                                        action="{{ route('users.profile.notifications.markasread') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="notification" value="{{ $notification->id }}">
                                    </form>
                                    <a href="javascript:;"
                                        onclick="document.getElementById('markasread-{{ $notification->id }}').submit()">
                                    @endrole
                                    @role('admin')
                                        <form id="markasread-admin-{{ $notification->id }}"
                                            action="{{ route('admin.notifications.markasread') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="notification" value="{{ $notification->id }}">
                                        </form>
                                        <a href="javascript:;"
                                            onclick="document.getElementById('markasread-admin-{{ $notification->id }}').submit()">
                                        @endrole
                                        <div class="d-flex">
                                            <div class="flex-grow-1">
                                                <h6 class="mb-1">{{ $notification->title }}</h6>
                                                <p class="mb-0">{{ $notification->subtitle }}</p>
                                                <small class="text-muted">{{ $notification->created_at }}</small>
                                            </div>
                                        </div>
                                    </a>

                            </li>
                        @endforeach
                    </ul>
                </li>
                <li class="dropdown-menu-footer border-top">
                    @role('user')
                        <a href="{{ route('users.profile.notifications') }}"
                            class="dropdown-item d-flex justify-content-center text-primary p-2 h-px-40 mb-1 align-items-center">
                            View all notifications
                        </a>
                    @endrole
                    @role('admin')
                        <a href="{{ route('admin.notifications') }}"
                            class="dropdown-item d-flex justify-content-center text-primary p-2 h-px-40 mb-1 align-items-center">
                            View all notifications
                        </a>
                    @endrole
                </li>
            </ul>
        </li>
        <!-- User -->
        <li class="nav-item navbar-dropdown dropdown-user dropdown">
            <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                <div class="avatar avatar-online">
                    @if (auth()->user()->image != null)
                        <img class="rounded-circle" src="{{ asset('storage/' . auth()->user()->image) }}"
                            alt="User avatar" />
                    @else
                        <span class="avatar-initial rounded-circle bg-label-primary">
                            {{ mb_substr(auth()->user()->name, 0, 1) }}
                        </span>
                    @endif
                </div>
            </a>
            <ul class="dropdown-menu dropdown-menu-end">

                <li>
                    <a class="dropdown-item" href="{{ route('users.profile.index') }}">
                        <div class="d-flex justify-items-center align-items-center">
                            <div class="flex-shrink-0 me-2">
                                <div class="avatar avatar-md me-2">
                                    @if (auth()->user()->image != null)
                                        <img class="rounded-circle"
                                            src="{{ asset('storage/' . auth()->user()->image) }}" alt="User avatar" />
                                    @else
                                        <span class="avatar-initial rounded-circle bg-label-primary">
                                            {{ mb_substr(auth()->user()->name, 0, 1) }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <span class="fw-semibold d-block">
                                    @if (Auth::check())
                                        {{ ucwords(str_replace('.', ' ', auth()->user()->name)) }}
                                    @else
                                        John Doe
                                    @endif
                                </span>
                                @role('admin')
                                    <small class="text-muted">Admin</small>
                                @endrole
                                @role('superuser')
                                    <small class="text-muted">Super User</small>
                                @endrole
                            </div>
                        </div>
                    </a>
                </li>

                @role('user')
                    <li>
                        <div class="dropdown-divider"></div>
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{ route('users.profile.index') }}">
                            <i class="ti ti-user-check me-2 ti-sm"></i>
                            <span class="align-middle">My Profile</span>
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{ route('users.subscription.index') }}">
                            <span class="d-flex align-items-center align-middle">
                                <i class="flex-shrink-0 ti ti-credit-card me-2 ti-sm"></i>
                                <span class="flex-grow-1 align-middle">Subscription</span>
                            </span>
                        </a>
                    </li>
                @endrole
                <li>
                    <div class="dropdown-divider"></div>
                </li>
                @if (Auth::check())
                    <li>
                        <a class="dropdown-item" href="{{ route('logout') }}"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class='ti ti-logout me-2'></i>
                            <span class="align-middle">Logout</span>
                        </a>
                    </li>
                    <form method="POST" id="logout-form" action="{{ route('logout') }}">
                        @csrf
                    </form>
                @else
                    <li>
                        <a class="dropdown-item"
                            href="{{ Route::has('login') ? route('login') : url('auth/login-basic') }}">
                            <i class='ti ti-login me-2'></i>
                            <span class="align-middle">Login</span>
                        </a>
                    </li>
                @endif
            </ul>
        </li>
        <!--/ User -->
    </ul>
</div>

@if (!isset($navbarDetached))
    </div>
@endif
</nav>
<!-- / Navbar -->
