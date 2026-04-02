<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Đặt Sân Bóng')</title>

    <!-- Base CSS -->
    <link rel="stylesheet" href="{{ asset('css/base.css') }}">
    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    <link rel="stylesheet" href="{{ asset('css/footer.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components.css') }}">
    <link rel="stylesheet" href="{{ asset('css/responsive.css') }}">

    @stack('styles')
</head>
<body>
    <!-- Canvas cho hiệu ứng hoa sen rơi -->
    <canvas id="falling-canvas" style="display:none;"></canvas>

    @include('layouts.header')

    <main>
        @yield('content')
    </main>

    @include('layouts.footer')

    @stack('scripts')
</body>
</html>
