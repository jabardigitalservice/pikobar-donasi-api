<!-- Bootstrap 3.3.7 -->
<link rel="stylesheet" href="{{ asset('css/bootstrap/css/bootstrap.min.css') }}"/>

<!-- Font Awesome 4.7.0 -->
<link rel="stylesheet" href="{{ asset('css/font-awesome/css/font-awesome.min.css') }}"/>

<!-- Theme style -->
<link rel="stylesheet" href="{{ asset('css/adminlte/adminlte.min.css') }}"/>
<link rel="stylesheet" href="{{ asset('css/admin-skin/skin-purple.css') }}"/>

<link rel="stylesheet" href="{{ asset('css/sweet-alert/sweetalert2.min.css') }}"/>

<link rel="stylesheet" href="{{ asset('css/jquery-ui/jquery-ui.min.css') }}"/>

<!-- Select2 -->
<link rel="stylesheet" href="{{ asset('css/select2/select2.min.css') }}"/>

<link rel="stylesheet" href="{{ asset('css/adminlte/adminlte-select2.min.css') }}"/>

<!-- toastr v2.1.3 -->
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css"/>

<!-- custom css -->
<link rel="stylesheet" href="{{ asset('css/cms-styles.css') }}"/>

@yield('custom-style')
@stack('custom-style')
