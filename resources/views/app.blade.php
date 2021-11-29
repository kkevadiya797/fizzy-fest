<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name') }}</title>

    <link rel="icon" href="{{ asset('assets/images/logo/fizzyfest-shape.png') }}">

    {{-- font --}}
    <link href="https://fonts.googleapis.com/css?family=Poppins:400,500,600,700,800,900&display=swap" rel="stylesheet">

    <!-- General CSS Files -->
    <link rel="stylesheet" href="{{ asset('assets/modules/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/modules/fontawesome/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/daterangepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/visitor/font/flaticon.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/css/fizzy-fest-components.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/css/fizzy-fest-style.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/css/fizzy-fest-test.css')}}">
</head>

<body>
    <div id="app">
        <router-view></router-view>
    </div>

    <script src="{{ asset('/assets/js/app.js') }}"></script>

    <script src="{{ asset('/assets/modules/nicescroll/jquery.nicescroll.min.js') }}"></script>
    <script src="{{ asset('/assets/js/fizzy-fest.js') }}"></script>
    <script src="{{ asset('/path/to/vue-tables-2.min.js') }}"></script>
</body>

</html>