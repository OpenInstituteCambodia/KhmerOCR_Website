<!DOCTYPE html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="keyword" content="html5, css, bootstrap, property, real-estate theme , bootstrap template">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title> Khmer OCR </title>
        <!-- Bootstrap -->
        <link href="{{ URL::asset('vendors/bootstrap4.0-b3/css/bootstrap.min.css') }}" rel="stylesheet">
        <link href="{{ URL::asset('vendors/bootstrap4.0-b3/css/scrolling-nav.css') }}" rel="stylesheet">
        <link href="{{ URL::asset('css/font-awesome.min.css') }}" rel="stylesheet">
        <link href="{{ URL::asset('vendors/bootstrap4.0-b3/css/custom.css') }}" rel="stylesheet">
    </head>
    <body>
        @include('layouts.header')
        @yield('content')
        <!-- footer content -->
        @include('layouts.footer')
        <!-- /footer content -->
        <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
        <script src="{{ URL::asset('vendors/bootstrap4.0-b3/js/bootstrap.min.js')}}"></script>
        <script src="{{ URL::asset('vendors/bootstrap4.0-b3/js/scrolling-nav.js')}}"></script>

        @stack('script')
    </body>

</html>
