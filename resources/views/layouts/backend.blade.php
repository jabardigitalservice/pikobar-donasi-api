<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('images/favico.ico') }}">

    @hasSection('title')
        <title>{{ $app_site_title }} | @yield('title')</title>
    @else
        <title>{{ $app_site_title }}</title>
    @endif

    @include('partials.backend.styles')
</head>

<body class="hold-transition wysihtml5-supported skin-purple sidebar-mini">

<div id="preloader">
    <span class="spinner"></span>
</div>

<div class="wrapper">

    <!--Navbar-->
    @include('partials.backend.nav-header')

    <aside class="main-sidebar">
        <section class="sidebar">
            <!-- Sidebar-->
        @include('partials.backend.sidebar')
        <!-- /Sidebar-->
        </section>
    </aside>

    <!--Content-Wrapper & Main Content-->
    <div class="content-wrapper">
        @yield('content')
    </div>
    <!--Content-Wrapper & Main Content-->

    <!--Footer-->
    <footer class="main-footer">
        <div class="pull-right hidden-xs">
            <b>Version</b> 1.0.0
        </div>
        <strong>Copyright &copy; 2020 <a href="https://odenktools.com">Odenktools</a>. Big Credits to <a
                    href="https://adminlte.io">Almsaeed Studio.</a></strong> All rights
        reserved
    </footer>
    <!--/Footer-->

    <div class="modal fade" id="modal-form" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="fa fa-times"></span>
                    </button>
                    <h4 class="modal-title"></h4>
                </div>
                <div class="modal-body"></div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-action" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="fa fa-times"></span>
                    </button>
                    <h4 class="modal-title"></h4>
                </div>
                <div class="modal-body"></div>
            </div>
        </div>
    </div>

</div>

<!-- GLOBAL JAVASCRIPT VARIABLE -->

@include('partials.backend.scripts')


</body>

</html>
