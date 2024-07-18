<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @include('layout.title-meta')
    @include('layout.head')
</head>

@section('body')

    <body class="authentication-bg">

    @show
    @yield('content')
    @include('layout.login-scripts')
</body>

</html>
