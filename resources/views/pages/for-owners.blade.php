@extends('layouts.app')

@section('title', App\Models\SiteSetting::get('owners_title') ?: 'Dành cho chủ sân')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/for-owners.css') }}">
@endpush

@section('content')
<!-- Hero Section -->
<section class="owners-hero" style="background-image: url('{{ App\Models\SiteSetting::get('owner_banner') ? asset('storage/' . App\Models\SiteSetting::get('owner_banner')) : '' }}');">
    <div class="hero-overlay"></div>
    <div class="hero-content">
        <h1>{{ App\Models\SiteSetting::get('owners_title') ?: 'Dành Cho Chủ Sân' }}</h1>
        <p class="hero-subtitle">{{ App\Models\SiteSetting::get('owners_description') ?: 'Đăng ký trở thành đối tác của chúng tôi' }}</p>
        @guest
        <a href="{{ route('register') }}" class="btn btn-hero">Đăng ký ngay</a>
        @endguest
    </div>
</section>

<!-- Benefits Section -->
@if($benefits->count() > 0)
<section class="section benefits-section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Lợi Ích Khi Trở Thành Đối Tác</h2>
            <p class="section-subtitle">Tham gia cùng hàng trăm chủ sân đang phát triển doanh thu</p>
        </div>

        <div class="benefits-grid">
            @foreach($benefits as $benefit)
            <div class="benefit-card">
                @if($benefit->image)
                <div class="benefit-icon">
                    <img src="{{ asset('storage/' . $benefit->image) }}" alt="{{ $benefit->title }}">
                </div>
                @endif
                <h3>{{ $benefit->title }}</h3>
                <p>{{ $benefit->description }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- Content Sections (Text + Image) -->
@foreach($sections as $section)
<section class="section content-section">
    <div class="container">
        <div class="content-row {{ $section->image_position == 'left' ? 'reverse' : '' }}">
            <div class="content-text">
                <h2>{{ $section->title }}</h2>
                <p>{{ $section->content }}</p>
            </div>
            @if($section->image)
            <div class="content-image">
                <img src="{{ asset('storage/' . $section->image) }}" alt="{{ $section->title }}">
            </div>
            @endif
        </div>
    </div>
</section>
@endforeach

<!-- How It Works Section -->
@if($steps->count() > 0)
<section class="section how-it-works-section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Cách Thức Hoạt Động</h2>
            <p class="section-subtitle">{{ $steps->count() }} bước đơn giản để bắt đầu kinh doanh</p>
        </div>

        <div class="steps-container">
            @foreach($steps as $index => $step)
            <div class="step-card">
                <div class="step-number">{{ $step->step_number }}</div>
                <div class="step-content">
                    <h3>{{ $step->title }}</h3>
                    <p>{{ $step->description }}</p>
                </div>
            </div>
            @if(!$loop->last)
            <div class="step-arrow">→</div>
            @endif
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- Stats Section -->
@if($stats->count() > 0)
<section class="stats-section">
    <div class="container">
        <div class="stats-grid">
            @foreach($stats as $stat)
            <div class="stat-box">
                <div class="stat-number">{{ $stat->number }}</div>
                <div class="stat-label">{{ $stat->label }}</div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- CTA Section -->
<section class="cta-section-owners">
    <div class="container">
        <div class="cta-content">
            <h2>Sẵn Sàng Phát Triển Doanh Nghiệp?</h2>
            <p>Tham gia cùng hàng trăm chủ sân đang thành công trên nền tảng của chúng tôi</p>
            @guest
            <a href="{{ route('register') }}" class="btn btn-cta">Đăng ký miễn phí ngay</a>
            @else
            <a href="{{ route('owner.dashboard') }}" class="btn btn-cta">Vào trang quản lý</a>
            @endguest
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script src="{{ asset('js/for-owners-animations.js') }}"></script>
<script src="{{ asset('js/counter-animation.js') }}"></script>
@endpush
