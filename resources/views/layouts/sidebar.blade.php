<aside class="left-sidebar">
    {{-- Sidebar Scroll --}}
    <div>
        {{-- Sidebar Logo --}}
        <div class="brand-logo d-flex align-items-center justify-content-between">
            <div>
                <i class="ti ti-api-app h1"></i>
                <span class="h1 align-middle">UPM</span>
            </div>
            {{-- <a href="#" class="text-nowrap logo-img">
                <img src="{{ asset('assets/images/logos/dark-logo.svg') }}" alt="UMA" width="180">
            </a> --}}
            <div class="close-btn d-xl-none d-block sidebartoggler cursor-pointer" id="sidebarCollapse">
                <i class="ti ti-x fs-8"></i>
            </div>
        </div>
        {{-- Sidebar Logo End --}}

        {{-- Sidebar Navigation --}}
        <nav class="sidebar-nav scroll-sidebar" data-simplebar="">
            <ul id="sidebarnav">
                @foreach (Auth::user()->modules->sortBy('sequence') as $modul)
                    <li class="nav-small-cap">
                        <span class="hide-menu">{{ $modul->name }}</span>
                    </li>
                    @foreach ($modul->menus->sortBy('code') as $menu)
                        <li class="sidebar-item">
                            <a href="{{ route($menu->route_name) }}" class="sidebar-link" aria-expanded="false">
                                <span>
                                    <i class="ti ti-{{ $menu->icon }}"></i>
                                </span>
                                <span class="hide-menu">{{ $menu->name }}</span>
                            </a>
                        </li>
                    @endforeach
                @endforeach
            </ul>
        </nav>
        {{-- Sidebar Navigation End --}}
    </div>
    {{-- Sidebar Scroll End --}}
</aside>