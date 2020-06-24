<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="author" content="{{ $app_site_title  }}">
    <link rel="icon" href="{{ asset('images/favico.ico') }}">
    @hasSection('title')
        <title>{{ $app_site_title }} | @yield('title')</title>
    @else
        <title>{{ $app_site_title }}</title>
    @endif

   <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('css/font-awesome/css/font-awesome.min.css') }}"/>

    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="{{ asset('css/bootstrap/css/bootstrap.min.css') }}"/>

    <!-- Ionicons -->
    <link rel="stylesheet" href="{{ asset('css/ionicons/css/ionicons.min.css') }}"/>

    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('css/adminlte/adminlte.min.css') }}"/>

    <!-- AdminLTE Skins. Choose a skin from the css/skins
         folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="{{ asset('css/admin-skin/_all-skins.min.css') }}"/>

    <link rel="stylesheet" href="{{ asset('css/pace/pace.min.css') }}"/>

    <link rel="stylesheet" href="{{ asset('css/icheck/square/blue.css') }}"/>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body class="hold-transition login-page">

<div id="preloader">
    <span class="spinner"></span>
</div>

<div class="wrapper">
    @yield('content')
</div>

<!-- jQuery 3 -->
<script src="{{ asset('js/jquery/jquery.min.js') }}"></script>

<!-- Bootstrap 3.3.7 -->
<script src="{{ asset('js/bootstrap/bootstrap.min.js') }}"></script>

<!-- PACE -->
<script src="{{ asset('js/pace/pace.min.js') }}"></script>

<!-- Slimscroll -->
<script src="{{ asset('js/jquery/jquery.slimscroll/jquery.slimscroll.min.js') }}"></script>

<!-- FastClick -->
<script src="{{ asset('js/fastclick/fastclick.js') }}"></script>

<!-- AdminLTE App -->
<script src="{{ asset('js/adminlte/adminlte.min.js') }}"></script>

<!-- jQuery UI 1.11.4 -->
<script src="{{ asset('js/jquery/jquery-ui/jquery-ui.min.js') }}"></script>

<script src="{{ asset('js/icheck/icheck.min.js') }}"></script>

<!-- Select2 -->
<script src="{{ asset('js/select2/select2.full.min.js') }}"></script>

<script src="{{ asset('js/jquery/jquery-validate/jquery.validate.min.js') }}"></script>

<script src="{{ asset('js/cms-scripts.js') }}"></script>

<script>
    $(document).ready(function () {

        // Login
        $("#login").validate({
            rules: {
                email: "required",
                password: "required",
            },
            messages: {
                email: "Please enter your email",
                password: {
                    required: "Please provide a password"
                }
            },
            errorElement: "em",
            errorPlacement: function (error, element) {
                // Add the `help-block` class to the error element
                error.addClass("help-block");

                if (element.prop("type") === "checkbox") {
                    error.insertAfter(element.parent("label"));
                } else {
                    error.insertAfter(element);
                }
            },
            highlight: function (element, errorClass, validClass) {
                $(element).parent().addClass("has-error").removeClass("has-success");
            },
            unhighlight: function (element, errorClass, validClass) {
                $(element).parent().addClass("has-success").removeClass("has-error");
            }
        });

        // Register
        $("#register").validate({
            rules: {
                first_name: "required",
                last_name: "required",
                username: "required",
                password: "required",
                email: "required",
                chk_agree: "required",
                password_confirmation: "required"
            },
            messages: {
                first_name: "Please enter your first name",
                last_name: "Please enter your last name",
                username: "Please enter your username",
                email: "Please enter your email",
                password: "Please enter password",
                password_confirmation: "Please enter password",
                chk_agree: "You must agree"
            },
            errorElement: "em",
            errorPlacement: function (error, element) {
                // Add the `help-block` class to the error element
                error.addClass("help-block");

                if (element.prop("type") === "checkbox") {
                    error.insertAfter(element.parent("label"));
                } else {
                    error.insertAfter(element);
                }
            },
            highlight: function (element, errorClass, validClass) {
                $(element).parent().addClass("has-error").removeClass("has-success");
            },
            unhighlight: function (element, errorClass, validClass) {
                $(element).parent().addClass("has-success").removeClass("has-error");
            }
        });

        // Forgot password
        $("#forgot").validate({
            rules: {
                email: "required",
            },
            messages: {
                email: "Please enter your email",
            },
            errorElement: "em",
            errorPlacement: function (error, element) {
                // Add the `help-block` class to the error element
                error.addClass("help-block");

                if (element.prop("type") === "checkbox") {
                    error.insertAfter(element.parent("label"));
                } else {
                    error.insertAfter(element);
                }
            },
            highlight: function (element, errorClass, validClass) {
                $(element).parent().addClass("has-error").removeClass("has-success");
            },
            unhighlight: function (element, errorClass, validClass) {
                $(element).parent().addClass("has-success").removeClass("has-error");
            }
        });

        // Password change
        $("#form_pass_change").validate({
            rules: {
                email: "required",
                password: "required",
                password_confirmation: "required"
            },
            messages: {
                email: "Please enter your email",
                password: "Please enter your password",
                password_confirmation: "Please enter your password"
            },
            errorElement: "em",
            errorPlacement: function (error, element) {
                // Add the `help-block` class to the error element
                error.addClass("help-block");

                if (element.prop("type") === "checkbox") {
                    error.insertAfter(element.parent("label"));
                } else {
                    error.insertAfter(element);
                }
            },
            highlight: function (element, errorClass, validClass) {
                $(element).parent().addClass("has-error").removeClass("has-success");
            },
            unhighlight: function (element, errorClass, validClass) {
                $(element).parent().addClass("has-success").removeClass("has-error");
            }
        });

        // Confirm
        $("#form_confirm").validate({
            rules: {
                email: "required",
                password: "required",
                password_confirmation: "required"
            },
            messages: {
                email: "Please enter your email",
                password: "Please enter your password",
                password_confirmation: "Please enter your password"
            },
            errorElement: "em",
            errorPlacement: function (error, element) {
                // Add the `help-block` class to the error element
                error.addClass("help-block");

                if (element.prop("type") === "checkbox") {
                    error.insertAfter(element.parent("label"));
                } else {
                    error.insertAfter(element);
                }
            },
            highlight: function (element, errorClass, validClass) {
                $(element).parent().addClass("has-error").removeClass("has-success");
            },
            unhighlight: function (element, errorClass, validClass) {
                $(element).parent().addClass("has-success").removeClass("has-error");
            }
        });
    })
</script>

@yield('scripts')
@stack('scripts')

</body>

</html>

