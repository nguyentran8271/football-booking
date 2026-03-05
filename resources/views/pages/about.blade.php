@extends('layouts.app')

@section('title', App\Models\SiteSetting::get('about_page_title') ?: 'Giới thiệu')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/about.css') }}">
<link rel="stylesheet" href="{{ asset('css/home.css') }}">
@endpush

@push('scripts')
<script src="{{ asset('js/about-animations.js') }}"></script>
<script src="{{ asset('js/counter-animation.js') }}"></script>
@endpush

@section('content')
<section class="hero" style="height: 400px;">
    @php
        $aboutBanner = App\Models\SiteSetting::get('about_banner');
        $aboutPageTitle = App\Models\SiteSetting::get('about_page_title') ?: 'Giới Thiệu';
    @endphp
    <img src="{{ $aboutBanner ? asset('storage/' . $aboutBanner) : asset('images/about-banner.jpg') }}" alt="Giới thiệu" class="hero-slide active">
    <div class="hero-content">
        <h1 style="color: #1a5c2e;">{{ $aboutPageTitle }}</h1>
    </div>
</section>

<section class="section">
    <div class="container">
        @if(isset($sections) && $sections->count() > 0)
            @foreach($sections as $section)
            <div class="about-section {{ $section->layout }}">
                <div class="about-section-content">
                    <div class="about-section-image">
                        <img src="{{ $section->image ? asset('storage/' . $section->image) : asset('images/default-about.jpg') }}" alt="{{ $section->title }}">
                    </div>
                    <div class="about-section-text">
                        <h2>{{ $section->title }}</h2>
                        <div class="content">
                            {!! nl2br(e($section->content)) !!}
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        @else
            <div class="card">
                @php
                    $aboutContent = App\Models\SiteSetting::get('about_content');
                @endphp

                @if($aboutContent)
                    {!! nl2br(e($aboutContent)) !!}
                @else
                    <h2 class="card-title">Về Chúng Tôi</h2>
                    <p>Chúng tôi là nền tảng đặt sân bóng trực tuyến hàng đầu tại Việt Nam, mang đến trải nghiệm đặt sân nhanh chóng, tiện lợi và đáng tin cậy.</p>

                    <h3 style="margin-top: 30px;">Sứ mệnh</h3>
                    <p>Kết nối người chơi bóng đá với các sân bóng chất lượng, tạo điều kiện thuận lợi nhất cho việc tổ chức các trận đấu.</p>

                    <h3 style="margin-top: 30px;">Tầm nhìn</h3>
                    <p>Trở thành nền tảng đặt sân bóng số 1 Việt Nam, phục vụ hàng triệu người chơi bóng đá mỗi năm.</p>
                @endif
            </div>
        @endif

        <!-- Stats Section -->
        @php
            $homeStats = App\Models\HomeStat::orderBy('order')->get();
        @endphp
        @if($homeStats->count() > 0)
        <div style="margin: 80px 0;">
            <div class="stats">
                @foreach($homeStats as $stat)
                <div class="stat-item">
                    <span class="stat-number">{{ $stat->value }}</span>
                    <span class="stat-label">{{ $stat->title }}</span>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</section>

<!-- CTA Section -->
<section class="cta-section-wrapper">
    <div class="container">
        <div class="cta-section">
            <h2 class="cta-title">Sẵn sàng đặt sân?</h2>
            <p class="cta-description">Tìm và đặt sân bóng chất lượng ngay hôm nay</p>
            <a href="{{ route('fields.index') }}" class="btn btn-primary btn-lg">Đặt sân ngay</a>
        </div>
    </div>
</section>
@endsection
