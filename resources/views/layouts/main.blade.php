<html lang="en" dir="ltr" data-nav-layout="vertical" loader="enable">

<head><!-- Meta Data -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>POS System</title>
    <!-- Favicon -->
    <link rel="icon" href="/noa-assets/assets/images/brand-logos/favicon.ico" type="image/x-icon">
    <!-- Choices JS -->
    {{-- <script src="/noa-assets/assets/libs/choices.js/public/assets/scripts/choices.min.js"></script> --}}
    <!-- Main Theme Js -->
    <script src="/noa-assets/assets/js/main.js"></script>
    <!-- Bootstrap Css -->
    <link id="style" href="/noa-assets/assets/libs/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <!-- Style Css -->
    <link href="/noa-assets/assets/css/styles.min.css" rel="stylesheet">
    <!-- Icons Css -->
    <link href="/noa-assets/assets/css/icons.css" rel="stylesheet">
    <!-- Node Waves Css -->
    <link href="/noa-assets/assets/libs/node-waves/waves.min.css" rel="stylesheet">
    <!-- Simplebar Css -->
    <link href="/noa-assets/assets/libs/simplebar/simplebar.min.css" rel="stylesheet">
    <!-- Color Picker Css -->
    {{-- <link rel="stylesheet" href="/noa-assets/assets/libs/flatpickr/flatpickr.min.css"> --}}

    <link rel="stylesheet" href="/noa-assets/assets/libs/@simonwep/pickr/themes/nano.min.css">
    <!-- Choices Css -->
    {{-- <link rel="stylesheet" href="/noa-assets/assets/libs/choices.js/public/assets/styles/choices.min.css"> --}}

    {{-- Remix Icon --}}
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.3.0/fonts/remixicon.css" rel="stylesheet" />

    {{-- My App CSS --}}
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    {{-- Jquery --}}
    <script src="{{ asset('noa-assets/assets/js/jquery-3.7.1.min.js') }}"></script>
</head>

<body>
    <!-- End Switcher -->
    <!-- Loader -->
    <div id="loader" class="d-flex"> <img src="/noa-assets/assets/images/media/loader.svg" alt=""> </div>
    <!-- Loader -->
    <div class="page">
        <!-- app-header -->
        @include('layouts.header')
        <!-- /app-header -->
        <!-- Start::app-sidebar -->
        @include('layouts.sidebar')
        <!-- End::app-sidebar -->
        <!-- Start::app-content -->
        <div class="main-content app-content">
            <div class="container-fluid">
                <!-- Page Header -->
                <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
                    <h1 class="page-title fw-semibold fs-20 mb-0">{{ $page_title }}</h1>
                    <div class="ms-md-1 ms-0">
                        <nav>
                            <ol class="breadcrumb mb-0">
                                @foreach ($breadcumbs as $data)
                                    @if ($last_breadcumb == $data['title'])
                                        <li class="breadcrumb-item">{{ $data['title'] }}</li>
                                    @else
                                        <li class="breadcrumb-item"><a
                                                href="{{ $data['link'] }}">{{ $data['title'] }}</a></li>
                                    @endif
                                @endforeach
                            </ol>
                        </nav>
                    </div>
                </div> <!-- Page Header Close --> <!-- Start::row-1 -->
                @yield('content')
            </div>
        </div>
        <!-- End::app-content -->
        <!-- Footer Start -->
        @include('layouts.footer')
        <!-- Footer End -->
    </div>
    <!-- Scroll To Top -->
    <div class="scrollToTop" style="display: none;">
        <span class="arrow">
            <i class="ri-arrow-up-s-fill fs-20"></i>
        </span>
    </div>
    <div id="responsive-overlay"></div>

    <script>
        function hideLoader() {
            $("#loader").addClass("d-none")
        }

        $(document).ready(function() {
            hideLoader()
        });
    </script>

    <!-- Scroll To Top --> <!-- Popper JS -->
    <script src="/noa-assets/assets/libs/@popperjs/core/umd/popper.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="/noa-assets/assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- Defaultmenu JS -->
    <script src="/noa-assets/assets/js/defaultmenu.min.js"></script>
    <!-- Node Waves JS-->
    {{-- <script src="/noa-assets/assets/libs/node-waves/waves.min.js"></script> --}}
    <!-- Sticky JS -->
    <script src="/noa-assets/assets/js/sticky.js"></script>
    <!-- Simplebar JS -->
    <script src="/noa-assets/assets/libs/simplebar/simplebar.min.js"></script>
    <script src="/noa-assets/assets/js/simplebar.js"></script>
    <!-- Color Picker JS -->
    {{-- <script src="/noa-assets/assets/libs/@simonwep/pickr/pickr.es5.min.js"></script> --}}
    <!-- Apex Charts JS -->
    {{-- <script src="/noa-assets/assets/libs/apexcharts/apexcharts.min.js"></script> --}}

    {{-- <script src="/noa-assets/assets/js/index.js"></script> --}}
    <!-- Custom-Switcher JS -->
    {{-- <script src="/noa-assets/assets/js/custom-switcher.min.js"></script> --}}
    <!-- Custom JS -->
    {{-- <script src="/noa-assets/assets/js/custom.js"></script> --}}

    @stack('scripts')
</body>

</html>
