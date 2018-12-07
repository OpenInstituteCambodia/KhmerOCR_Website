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

        <!-- Font-awesome -->
        <link href="{{URL::asset('vendors/fontsawesome5.5.0/css/all.min.css')}}" rel="stylesheet">
        <link href="{{ URL::asset('vendors/bootstrap4.1.3/css/bootstrap.min.css') }}" rel="stylesheet">

        <!-- Custom -->
        <link href="{{ URL::asset('vendors/custom.css') }}" rel="stylesheet">

    </head>
    <body>
        @include('layouts.header')
        @yield('content')
        <!-- footer content -->
        @include('layouts.footer')
        <!-- /footer content -->
        {{--<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>--}}
        <script src="{{ URL::asset('vendors/jquery-3.3.1.min.js')}}"></script>
        <script src="{{ URL::asset('vendors/bootstrap4.1.3/js/bootstrap.min.js')}}"></script>
        <script src="{{ URL::asset('vendors/pagination1.4.2/jquery.twbsPagination.js')}}"></script>


        @stack('script')
    </body>

</html>
