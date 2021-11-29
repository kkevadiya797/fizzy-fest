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
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@200;300;400;500;600;700&display=swap"
        rel="stylesheet">
    <link
        href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap"
        rel="stylesheet">

    <!-- General CSS Files -->
    <link rel="stylesheet" href="{{ asset('assets/modules/fontawesome/css/all.min.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/visitor/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/visitor/css/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/visitor/css/slicknav.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/visitor/css/animate.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/visitor/css/magnific-popup.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/visitor/css/fontawesome-all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/visitor/css/themify-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/visitor/css/slick.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/visitor/css/nice-select.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/visitor/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/visitor/font/flaticon.css') }}">
</head>

<body>
    <div id="app">
        <router-view></router-view>
    </div>

    <script src="{{ asset('/assets/js/app-web.js') }}"></script>
    <!-- JS here -->

    <!-- All JS Custom Plugins Link Here here -->
    <script src="{{ asset('/assets/visitor/js/vendor/modernizr-3.5.0.min.js') }}"></script>
    
    <!-- Jquery, Popper, Bootstrap -->
    <script src="{{ asset('/assets/visitor/js/vendor/jquery-1.12.4.min.js') }}"></script>
    <script src="{{ asset('/assets/visitor/js/popper.min.js') }}"></script>
    <script src="{{ asset('/assets/visitor/js/bootstrap.min.js') }}"></script>
    
    <!-- Jquery Mobile Menu -->
    <script src="{{ asset('/assets/visitor/js/jquery.slicknav.min.js') }}"></script>

    <!-- Jquery Slick , Owl-Carousel Plugins -->
    <script src="{{ asset('/assets/visitor/js/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('/assets/visitor/js/slick.min.js') }}"></script>
    
    <!-- Date Picker -->
    <script src="{{ asset('/assets/visitor/js/gijgo.min.js') }}"></script>
    
    <!-- One Page, Animated-HeadLin -->
    <script src="{{ asset('/assets/visitor/js/wow.min.js') }}"></script>
    <script src="{{ asset('/assets/visitor/js/animated.headline.js') }}"></script>
    <script src="{{ asset('/assets/visitor/js/jquery.magnific-popup.js') }}"></script>

    <!-- Scrollup, nice-select, sticky -->
    <script src="{{ asset('/assets/visitor/js/jquery.scrollUp.min.js') }}"></script>
    <script src="{{ asset('/assets/visitor/js/jquery.nice-select.min.js') }}"></script>
    <script src="{{ asset('/assets/visitor/js/jquery.sticky.js') }}"></script>
    
    <!-- contact js -->
    <script src="{{ asset('/assets/visitor/js/contact.js') }}"></script>
    <script src="{{ asset('/assets/visitor/js/jquery.form.js') }}"></script>
    <script src="{{ asset('/assets/visitor/js/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('/assets/visitor/js/mail-script.js') }}"></script>
    <script src="{{ asset('/assets/visitor/js/jquery.ajaxchimp.min.js') }}"></script>
    
    <!-- Jquery Plugins, main Jquery -->	
    <script src="{{ asset('/assets/visitor/js/plugins.js') }}"></script>
</body>

</html>