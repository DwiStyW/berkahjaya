<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @include('layout.title-meta')
    @include('layout.head')
</head>

@section('body')

    <body data-layout="horizontal" data-topbar="dark">
    @show

    <!-- Begin page -->
    <div id="layout-wrapper">
        @include('layout.horizontal')
        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="main-content">
            <div class="page-content">
                <!-- Start content -->
                <div class="container-fluid">
                    @yield('content')
                </div> <!-- content -->
            </div>
            @include('layout.footer')
        </div>
        <!-- ============================================================== -->
        <!-- End Right content here -->
        <!-- ============================================================== -->
    </div>
    <!-- END wrapper -->

    <!-- Right Sidebar -->
    @include('layout.right-sidebar')
    <!-- END Right Sidebar -->

    @include('layout.vendor-scripts')
</body>

</html>
