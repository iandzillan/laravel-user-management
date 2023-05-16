<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>{{ $title }}</title>
    <link rel="shortcut icon" href="favicon.ico" type="{{ asset('assets/images/logos/favicon.png') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/styles.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/icons/tabler-icons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/sweetalert2.min.css') }}">
    <script src="{{ asset('assets/libs/jquery/dist/jquery.min.js') }}"></script>
</head>
<body>
    {{-- Body Wrapper --}}
    <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full" data-sidebar-position="fixed" data-header-position="fixed">

        {{-- Sidebar Start --}}
        @include('layouts.sidebar')
        {{-- Sidebar End --}}

        {{-- Main Warpper --}}
        <div class="body-wrapper">
            {{-- Header Start --}}
            @include('layouts.navbar')
            {{-- Header End --}}

            {{-- Content Start --}}
            <div class="container-fluid">
                @yield('content')
            </div>
            {{-- Content End --}}
        </div>
        {{-- Main Warpper End --}}
    </div>

    <script src="{{ asset('assets/js/app.min.js') }}"></script>
    <script src="{{ asset('assets/js/sidebarmenu.js') }}"></script>
    <script src="{{ asset('assets/libs/simplebar/dist/simplebar.js') }}"></script>
    <script src="{{ asset('assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/libs/jquery/dist/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/libs/jquery/dist/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('assets/js/sweetalert2.all.min.js') }}"></script>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>
</body>
</html>