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
        position: fixed; top: 80px; right: 20px; z-index: 9999;
        max-width: 380px; padding: 16px 20px; border-radius: 10px;
        font-size: 15px; font-weight: 500; box-shadow: 0 4px 20px rgba(0,0,0,0.15);
        display: flex; align-items: flex-start; gap: 12px; animation: slideIn .3s ease;
        @if(session('success')) background:#f0fff4; border-left:4px solid #28a745; color:#155724;
        @elseif(session('error')) background:#fff5f5; border-left:4px solid #dc3545; color:#721c24;
        @elseif(session('info')) background:#e8f4fd; border-left:4px solid #17a2b8; color:#0c5460;
        @else background:#fff8e1; border-left:4px solid #ffc107; color:#856404; @endif
    ">
        <span style="font-size:20px; flex-shrink:0;">
            @if(session('success')) ✅
            @elseif(session('error')) ❌
            @elseif(session('info')) ℹ️
            @else ⚠️ @endif
        </span>
        <span style="flex:1;">{{ session('success') ?? session('error') ?? session('info') ?? session('warning') }}</span>
        <button onclick="document.getElementById('flash-toast').remove()"
            style="background:none;border:none;cursor:pointer;font-size:18px;color:inherit;opacity:.6;padding:0;flex-shrink:0;">×</button>
    </div>
    <style>
        @keyframes slideIn { from { opacity:0; transform:translateX(30px); } to { opacity:1; transform:translateX(0); } }
    </style>
    <script>
        setTimeout(function(){ var t = document.getElementById('flash-toast'); if(t) t.style.opacity=0, t.style.transition='opacity .5s', setTimeout(function(){t.remove()},500); }, 5000);
    </script>
    @endif

    <main>
        @yield('content')
    </main>

    @include('layouts.footer')

    @stack('scripts')
</body>
</html>
