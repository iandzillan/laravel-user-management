<aside class="left-sidebar">
    {{-- Sidebar Scroll --}}
    <div>
        {{-- Sidebar Logo --}}
        <div class="brand-logo d-flex align-items-center justify-content-between">
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
                <li class="nav-small-cap">
                    <span class="hide-menu">Home</span>
                </li>
                <li class="sidebar-item">
                    <a href="{{ route('dashboard') }}" class="sidebar-link" aria-expanded="false">
                        <span>
                            <i class="ti ti-layout-dashboard"></i>
                        </span>
                        <span class="hide-menu">Dashboard</span>
                    </a>
                </li>

                <li class="nav-small-cap">
                    <span class="hide-menu">Settings</span>
                </li>
                <li class="sidebar-item">
                    <a href="{{ route('users.index') }}" class="sidebar-link" aria-expanded="false">
                        <span>
                            <i class="ti ti-user-cog"></i>
                        </span>
                        <span class="hide-menu">User</span>
                    </a>
                </li>
            </ul>
        </nav>
        {{-- Sidebar Navigation End --}}
    </div>
    {{-- Sidebar Scroll End --}}
</aside>