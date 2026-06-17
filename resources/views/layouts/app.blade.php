<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" href="{{ asset('images/football-logo.png') }}">
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

    {{-- Flash messages --}}
    @if(session('success') || session('error') || session('info') || session('warning'))
    <div id="flash-toast" style="
        position: fixed; top: 80px; left: 50%; transform: translateX(-50%);
        z-index: 9999; padding: 14px 24px; border-radius: 8px;
        font-size: 15px; font-weight: 500; box-shadow: 0 4px 20px rgba(0,0,0,0.15);
        text-align: center; min-width: 280px; max-width: 500px;
        @if(session('success')) background:#d4edda; color:#155724;
        @elseif(session('error')) background:#f8d7da; color:#721c24;
        @elseif(session('info')) background:#d1ecf1; color:#0c5460;
        @else background:#fff3cd; color:#856404; @endif
    ">
        {{ session('success') ?? session('error') ?? session('info') ?? session('warning') }}
    </div>
    <script>setTimeout(function(){ var t=document.getElementById('flash-toast'); if(t){t.style.transition='opacity .5s';t.style.opacity=0;setTimeout(function(){t.remove()},500);} }, 4000);</script>
    @endif

    <main>
        @yield('content')
    </main>

    @include('layouts.footer')

    @stack('scripts')
</body>
</html>
