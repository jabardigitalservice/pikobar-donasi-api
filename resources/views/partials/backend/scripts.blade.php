<!-- jQuery v3.4.1 -->
<script src="{{ asset('js/jquery/jquery.min.js') }}"></script>

<script src="{{ asset('js/jquery/jquery-ui/jquery-ui.min.js') }}"></script>

<!-- Bootstrap 3.3.7 -->
<script src="{{ asset('js/bootstrap/bootstrap.min.js') }}"></script>

<!-- FastClick -->
<script src="{{ asset('js/fastclick/fastclick.js') }}"></script>

<!-- AdminLTE App -->
<script src="{{ asset('js/adminlte/adminlte.min.js') }}"></script>

<!-- SWEET ALERT -->
<script src="{{ asset('js/sweet-alert/sweetalert2.min.js') }}"></script>

<script src="{{ asset('js/icheck/icheck.min.js') }}"></script>

<!-- Select2 -->
<script src="{{ asset('js/select2/select2.full.min.js') }}"></script>

<script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<!-- CMS SCRIPTS (must on buttom)-->
<script src="{{ asset('js/cms-scripts.js') }}"></script>

@yield('scripts')
@stack('scripts')
