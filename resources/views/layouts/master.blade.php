<!doctype html>
<html lang="en" dir="ltr">

<head>

    <!-- META DATA -->
    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="">
    <meta name="author" content="kurnia">
    <meta name="keywords" content="">
    <link rel="shortcut icon" type="image/x-icon" href="../assets/images/brand/favicon.ico"/>

    <!-- TITLE -->
    <title>{{ $config['page_title'] ?? "" }} | {{ config('app.name') }}</title>

    <!-- STYLE CSS -->
    @include('layouts.head-css')
</head>

<body class="app sidebar-mini ltr">

<!-- GLOBAL-LOADER -->
@include('layouts.preloader')
<!-- /GLOBAL-LOADER -->

<!-- PAGE -->
<div class="page">
    <div class="page-main">

        <!-- app-Header -->
    @include('layouts.topbar')
    <!-- /app-Header -->

        <!--APP-SIDEBAR-->
        <div class="sticky">
            <div class="app-sidebar__overlay" data-bs-toggle="sidebar"></div>
            <div class="app-sidebar">
                <div class="side-header" style="z-index: 999;">
                    <a class="header-brand1" href="{{ Auth::user()->roles->dashboard_url }}">
                        <img src="/assets/img/brand/logo-small.png" class="header-brand-img desktop-logo" alt="logo">
                        <img src="{{ asset('assets/img/brand/logo-small.png') }}" class="header-brand-img toggle-logo" alt="logo">
                        <img src="{{ asset('assets/img/brand/logo-small.png') }}" class="header-brand-img light-logo" alt="logo">
                        <img src="{{ asset('assets/img/brand/logo-small.png') }}"  style="width: 50px; height: 50px" class="header-brand-img light-logo1" alt="logo">
                    </a>
                    <!-- LOGO -->
                </div>
                @include('layouts.sidebar')
            </div>
            <!--/APP-SIDEBAR-->
        </div>

        <!--app-content open-->
        <div class="main-content app-content mt-0">
            <div class="side-app">
                <!-- CONTAINER -->
                <div class="main-container container-fluid">
                    <!-- PAGE-HEADER -->
                    <div class="page-header">
                        <h1 class="page-title">{{ $config['page_title'] ?? "" }}</h1>
                        <div>
                            @component('components.breadcrumb', ['page_breadcrumbs' => $page_breadcrumbs ?? array()])
                                @slot('title'){{ $config['page_title'] ?? '' }} @endslot
                            @endcomponent
                        </div>
                    </div>
                    <!-- PAGE-HEADER END -->

                    <!-- ROW OPEN -->
                    <div class="row row-cards">
                        @yield('content')
                    </div>
                    <!-- ROW CLOSED -->
                </div>
                <!-- CONTAINER CLOSED -->
            </div>
        </div>
        <!--app-content closed-->
    </div>

    <!-- Sidebar-right -->
@include('layouts.sidebar-right')
<!--/Sidebar-right-->

    <!-- Country-selector modal-->
@include('layouts.modal-language')
<!-- Country-selector modal-->

    <!-- FOOTER -->
@include('layouts.footer')
<!-- FOOTER CLOSED -->
</div>

<!-- BACK-TO-TOP -->
<a href="#top" id="back-to-top"><i class="fa fa-angle-up"></i></a>

</body>

@include('layouts.scripts')

</html>
