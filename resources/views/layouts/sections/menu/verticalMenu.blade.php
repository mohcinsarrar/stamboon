@php
    $configData = Helper::appClasses();
@endphp

@php
    $path = resource_path('views/website/website.json');

    // Get the file contents
    $json = File::get($path);

    // Decode the JSON data
    $data = json_decode($json, true);

    $primary_color = $data['colors']['primary_color'];
    $secondary_color = $data['colors']['secondary_color'];

@endphp

<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme"
    style="background-color:{{ $primary_color }} !important;">

    <!-- ! Hide app brand if navbar-full -->
    @if (!isset($navbarFull))
        <div class="app-brand demo my-3">
            <a href="{{ url('/') }}" class="app-brand-link align-items-start">
                <span class="app-brand-logo demo">
                    @include('_partials.macros')
                </span>
            </a>

            <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
                <i class="ti menu-toggle-icon d-none d-xl-block ti-sm align-middle"></i>
                <i class="ti ti-x d-block d-xl-none ti-sm align-middle"></i>
            </a>
        </div>
    @endif


    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">
        @foreach ($menuData[0]->menu as $menu)
            {{-- test if user has acces to this route --}}
            @if ($menu->slug == 'users.fantree.index')
                @if (auth()->user()->has_one_payment('fantree') == false)
                    @continue
                @endif
            @endif

            @if ($menu->slug == 'users.pedigree.index')
                @if (auth()->user()->has_one_payment('pedigree') == false)
                    @continue
                @endif
            @endif

            @if (isset($menu->role) && $menu->role == 'superuser')
                @if (auth()->user()->hasRole('superuser') == false)
                    @continue
                @endif
            @endif

            {{-- adding active and open class if child is active --}}

            {{-- menu headers --}}
            @if (isset($menu->menuHeader))
                <li class="menu-header small text-uppercase">
                    <span class="menu-header-text">{{ $menu->menuHeader }}</span>
                </li>
            @else
                {{-- active menu method --}}
                @php
                    $activeClass = null;
                    $currentRouteName = Route::currentRouteName();

                    if ($currentRouteName === $menu->slug) {
                        $activeClass = 'active';
                    } elseif (isset($menu->submenu)) {
                        if (gettype($menu->slug) === 'array') {
                            foreach ($menu->slug as $slug) {
                                if (str_contains($currentRouteName, $slug) and strpos($currentRouteName, $slug) === 0) {
                                    $activeClass = 'active open';
                                }
                            }
                        } else {
                            if (
                                str_contains($currentRouteName, $menu->slug) and
                                strpos($currentRouteName, $menu->slug) === 0
                            ) {
                                $activeClass = 'active open';
                            }
                        }
                    }
                @endphp

                {{-- main menu --}}

                <li class="menu-item ">
                    <a style=" color: black ; {{ $activeClass != null ? 'color: white ; background-color:' . $secondary_color . ' !important;' : '' }}"
                        href="{{ isset($menu->url) ? url($menu->url) : 'javascript:void(0);' }}"
                        class="{{ isset($menu->submenu) ? 'menu-link menu-toggle' : 'menu-link' }}"
                        @if (isset($menu->target) and !empty($menu->target)) target="_blank" @endif>
                        @isset($menu->icon)
                            <i class="{{ $menu->icon }}"></i>
                        @endisset
                        <div>{{ isset($menu->name) ? __($menu->name) : '' }}</div>
                        @isset($menu->badge)
                            <div class="badge bg-label-{{ $menu->badge[0] }} rounded-pill ms-auto">{{ $menu->badge[1] }}
                            </div>
                        @endisset
                    </a>

                    {{-- submenu --}}
                    @isset($menu->submenu)
                        @include('layouts.sections.menu.submenu', ['menu' => $menu->submenu])
                    @endisset
                </li>
            @endif
        @endforeach
    </ul>

</aside>
