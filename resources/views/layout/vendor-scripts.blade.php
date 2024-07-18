<!-- JAVASCRIPT -->
<script src="{{ URL::asset('/assets/libs/jquery/jquery.min.js') }}"></script>
<script src="{{ URL::asset('/assets/libs/bootstrap/bootstrap.min.js') }}"></script>
<script src="{{ URL::asset('/assets/libs/metismenu/metismenu.min.js') }}"></script>
<script src="{{ URL::asset('/assets/libs/simplebar/simplebar.min.js') }}"></script>
<script src="{{ URL::asset('/assets/libs/node-waves/node-waves.min.js') }}"></script>
<script src="{{ URL::asset('/assets/libs/waypoints/waypoints.min.js') }}"></script>
<script src="{{ URL::asset('/assets/libs/jquery-counterup/jquery-counterup.min.js') }}"></script>
<script src="{{ URL::asset('/assets/js/moment.js') }}"></script>
<script src="{{ URL::asset('/assets/js/app.min.js') }}"></script>
{{-- <script src="{{ URL::asset('/build/assets/app-bbacdb4c.js') }}"></script> --}}
<script>
    // $('body .dropdown-toggle').dropdown();
    var url = window.location.pathname;
    // console.log(url);
    @if (Auth::user()->role == '1')
        if (url == '/produksi') {
            // $('body').toggleClass('sidebar-enable');
            if ($(window).width() >= 992) {
                $('body').toggleClass('vertical-collpsed');
            } else {
                $('body').removeClass('vertical-collpsed');
            }
        }
    @endif
</script>

@yield('script')

@yield('script-notif')

@yield('script-ticket')

@yield('script-datatable')

@yield('script-selectize')
