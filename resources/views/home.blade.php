@extends('layouts.app')

@section('title', 'Trang chủ - Đặt Sân Bóng')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/home.css') }}">
<link rel="stylesheet" href="{{ asset('css/home-animations.css') }}">
<link rel="stylesheet" href="{{ asset('css/fields.css') }}">
@endpush

@section('content')
<!-- Hero Section -->
<section class="hero">
    @php
        $heroBanners = App\Models\SiteSetting::get('hero_banners');
        $bannerArray = $heroBanners ? json_decode($heroBanners, true) : [];
    @endphp

    @if(!empty($bannerArray))
        @foreach($bannerArray as $index => $banner)
        <img src="{{ asset('storage/' . $banner) }}"
             alt="Hero Banner {{ $index + 1 }}"
             class="hero-slide {{ $index === 0 ? 'active' : '' }}"
             data-slide="{{ $index }}">
        @endforeach
    @else
        <img src="{{ asset('images/hero-banner.jpg') }}" alt="Hero Banner" class="hero-slide active">
    @endif

    <div class="hero-content">
        <h1>{{ App\Models\SiteSetting::get('hero_title', 'Đặt Sân Bóng Dễ Dàng') }}</h1>
        <p>{{ App\Models\SiteSetting::get('hero_description', 'Tìm và đặt sân bóng chất lượng gần bạn') }}</p>
        <a href="{{ route('fields.index') }}" class="btn btn-primary">Đặt sân ngay</a>
    </div>
</section>

<!-- About Section -->
<section class="section">
    <div class="container">
        <h2 class="section-title">{{ App\Models\SiteSetting::get('about_title', 'Về Chúng Tôi') }}</h2>
        <p style="text-align: center; max-width: 800px; margin: 0 auto 40px;">
            {{ App\Models\SiteSetting::get('about_description', 'Chúng tôi cung cấp dịch vụ đặt sân bóng trực tuyến tiện lợi, nhanh chóng và đáng tin cậy.') }}
        </p>

        <div class="about-cards">
            @php
                $homeCards = App\Models\HomeCard::orderBy('order')->get();
            @endphp
            @if($homeCards->count() > 0)
                @foreach($homeCards as $card)
                <div class="about-card">
                    <h3>{{ $card->title }}</h3>
                    <p>{{ $card->description }}</p>
                </div>
                @endforeach
            @else
                <div class="about-card">
                    <h3>Dễ dàng đặt sân</h3>
                    <p>Chỉ với vài thao tác đơn giản, bạn có thể đặt sân bóng yêu thích</p>
                </div>
                <div class="about-card">
                    <h3>Giá cả hợp lý</h3>
                    <p>Nhiều mức giá phù hợp với mọi túi tiền</p>
                </div>
                <div class="about-card">
                    <h3>Sân chất lượng</h3>
                    <p>Tất cả sân đều được kiểm duyệt kỹ lưỡng</p>
                </div>
            @endif
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="section" style="background: #f8f9fa;">
    <div class="container">
        <div class="stats">
            @php
                $homeStats = App\Models\HomeStat::orderBy('order')->get();
            @endphp
            @if($homeStats->count() > 0)
                @foreach($homeStats as $stat)
                <div class="stat-item">
                    <span class="stat-number">{{ $stat->value }}</span>
                    <span class="stat-label">{{ $stat->title }}</span>
                </div>
                @endforeach
            @else
                <div class="stat-item">
                    <span class="stat-number">{{ App\Models\User::where('role', 'user')->count() }}</span>
                    <span class="stat-label">Người dùng</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number">{{ App\Models\Field::count() }}</span>
                    <span class="stat-label">Sân bóng</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number">{{ App\Models\Booking::count() }}</span>
                    <span class="stat-label">Lượt đặt</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number">{{ App\Models\User::where('role', 'owner')->count() }}</span>
                    <span class="stat-label">Chủ sân</span>
                </div>
            @endif
        </div>
    </div>
</section>

<!-- Featured Fields -->
<section class="section">
    <div class="container">
        <h2 class="section-title">Sân Nổi Bật</h2>
        <div class="fields-grid">
            @php
                $customFeaturedFields = App\Models\FeaturedField::orderBy('order')->get();
            @endphp
            @if($customFeaturedFields->count() > 0)
                @foreach($customFeaturedFields as $field)
                <div class="field-card">
                    <img src="{{ $field->image ? asset('storage/' . $field->image) : asset('images/default-field.jpg') }}"
                         alt="{{ $field->title }}"
                         class="field-image">
                    <div class="field-info">
                        <h3 class="field-name">{{ $field->title }}</h3>
                        <p class="field-address">📍 {{ $field->description }}</p>
                        <p class="field-price">{{ number_format((float)$field->price) }}đ/giờ</p>
                        @if($field->hotline)
                        <p class="field-hotline">📞 Hotline: {{ $field->hotline }}</p>
                        @endif
                        <a href="{{ route('fields.index') }}" class="btn btn-primary" style="width: 100%;">
                            Đặt sân ngay
                        </a>
                    </div>
                </div>
                @endforeach
            @else
                @foreach($featuredFields as $field)
                <div class="field-card">
                    <img src="{{ $field->image ? asset('storage/' . $field->image) : asset('images/default-field.jpg') }}"
                         alt="{{ $field->name }}"
                         class="field-image">
                    <div class="field-info">
                        <h3 class="field-name">{{ $field->name }}</h3>
                        <p class="field-address">📍 {{ $field->address }}</p>
                        <div class="field-rating">
                            ⭐ {{ number_format($field->reviews_avg_rating ?? 0, 1) }}
                            ({{ $field->reviews_count }} đánh giá)
                        </div>
                        <p class="field-price">{{ number_format($field->price_per_hour) }}đ/giờ</p>
                        <a href="{{ route('fields.show', $field->id) }}" class="btn btn-primary" style="width: 100%;">
                            Xem chi tiết
                        </a>
                    </div>
                </div>
                @endforeach
            @endif
        </div>
    </div>
</section>

<!-- Tournaments Section -->
@if(isset($tournaments) && $tournaments->count() > 0)
<section class="section">
    <div class="container">
        <div class="section-header-with-link">
            <h2 class="section-title">Giải Đấu Đang Mở</h2>
            <a href="{{ route('tournaments.index') }}" class="view-all-link">Xem tất cả →</a>
        </div>

        <p class="section-subtitle">Tham gia các giải đấu bóng đá chuyên nghiệp</p>

        <div class="tournaments-grid-home">
            @foreach($tournaments as $tournament)
            <div class="tournament-card-home">
                @if($tournament->banner)
                <img src="{{ asset('storage/' . $tournament->banner) }}" alt="{{ $tournament->name }}" class="tournament-banner-home">
                @else
                <div class="tournament-banner-home tournament-banner-default"></div>
                @endif

                <div class="tournament-content-home">
                    <span class="tournament-status-home">Sắp diễn ra</span>
                    <h3 class="tournament-name-home">{{ $tournament->name }}</h3>

                    <div class="tournament-info-home">
                        <div class="info-row-home">
                            <span class="info-label">Sân:</span>
                            <span class="info-value">{{ $tournament->field->name }}</span>
                        </div>
                        <div class="info-row-home">
                            <span class="info-label">Thời gian:</span>
                            <span class="info-value">{{ $tournament->start_date->format('d/m/Y') }}</span>
                        </div>
                        <div class="info-row-home">
                            <span class="info-label">Phí tham gia:</span>
                            <span class="info-value">{{ number_format($tournament->entry_fee) }}đ</span>
                        </div>
                    </div>

                    <div class="tournament-footer-home">
                        <div class="teams-count-home">
                            <strong>{{ $tournament->teams->where('status', 'approved')->count() }}</strong>/{{ $tournament->max_teams }} đội
                        </div>
                        <a href="{{ route('tournaments.show', $tournament->id) }}" class="btn-view-tournament">Xem chi tiết</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- News Section -->
<section class="section" style="background: #f8f9fa;">
    <div class="container">
        <h2 class="section-title">Tin Tức Bóng Đá</h2>

        <div class="news-layout">
            @if($posts->isNotEmpty())
                <!-- Bài viết nổi bật (bên trái) -->
                <div class="featured-post">
                    @php $featuredPost = $posts->first(); @endphp
                    <div class="featured-badge">TIN TỨC</div>
                    <h3 class="featured-title">{{ $featuredPost->title }}</h3>

                    @if($featuredPost->image)
                    <div class="featured-image-wrapper">
                        <img src="{{ asset('storage/' . $featuredPost->image) }}" alt="{{ $featuredPost->title }}" class="featured-image">
                    </div>
                    @endif

                    <p class="featured-excerpt">{{ Str::limit(strip_tags($featuredPost->content), 200) }}</p>
                    <a href="#" class="read-more-link">Đọc thêm →</a>
                </div>

                <!-- Danh sách bài viết (bên phải) -->
                <div class="posts-list">
                    @foreach($posts->skip(1)->take(5) as $post)
                    <div class="post-item">
                        @if($post->image)
                        <img src="{{ asset('storage/' . $post->image) }}" alt="{{ $post->title }}" class="post-thumb">
                        @endif
                        <div class="post-info">
                            <h4 class="post-item-title">{{ $post->title }}</h4>
                            <span class="post-date">{{ $post->created_at->format('d/m/Y') }}</span>
                        </div>
                    </div>
                    @endforeach

                    <a href="#" class="view-all-btn">Xem thêm</a>
                </div>
            @endif
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script src="{{ asset('js/scroll-reveal.js') }}"></script>
<script src="{{ asset('js/counter-animation.js') }}"></script>
<script>
// Auto slide hero banners
document.addEventListener('DOMContentLoaded', function() {
    const slides = document.querySelectorAll('.hero-slide');
    if (slides.length > 1) {
        let currentSlide = 0;

        setInterval(function() {
            slides[currentSlide].classList.remove('active');
            currentSlide = (currentSlide + 1) % slides.length;
            slides[currentSlide].classList.add('active');
        }, 4000); // Chuyển sau 4 giây
    }
});
</script>
@endpush
