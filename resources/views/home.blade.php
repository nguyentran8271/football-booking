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
        <img src="{{ storage_url($banner) }}"
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

    @php $totalBanners = !empty($bannerArray) ? count($bannerArray) : 1; @endphp
    @if($totalBanners > 1)
    <button class="hero-arrow prev" id="hero-prev">&#8249;</button>
    <button class="hero-arrow next" id="hero-next">&#8250;</button>

    <div class="hero-dots" id="hero-dots">
        @for($i = 0; $i < $totalBanners; $i++)
        <button class="hero-dot {{ $i === 0 ? 'active' : '' }}" data-index="{{ $i }}"></button>
        @endfor
    </div>
    @endif
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
                    <img src="{{ $field->image_url }}"
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
                    <img src="{{ $field->image_url }}"
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
                <img src="{{ storage_url($tournament->banner) }}" alt="{{ $tournament->name }}" class="tournament-banner-home">
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
<section class="section news-section" style="background: #f8f9fa;">
    <div class="container">
        <h2 class="section-title">Tin Tức Bóng Đá</h2>

        <!-- Filter tabs -->
        <div class="news-filter-tabs">
            <button class="news-tab active" data-category="all" data-url="{{ route('posts.load-more') }}">Tất cả</button>
            <button class="news-tab" data-category="trong_nuoc" data-url="{{ route('posts.load-more') }}">Trong nước</button>
            <button class="news-tab" data-category="ngoai_nuoc" data-url="{{ route('posts.load-more') }}">Ngoài nước</button>
        </div>

        <div class="news-layout">
            @if($posts->isNotEmpty())
                <!-- Bài viết nổi bật (bên trái) -->
                <div class="featured-post" id="featured-post-area" style="cursor:pointer;">
                    @php $featuredPost = $posts->first(); @endphp
                    @include('partials.featured-post', ['featuredPost' => $featuredPost])
                </div>

                <!-- Danh sách bài viết (bên phải) -->
                <div class="posts-list">
                    <div id="posts-container">
                        @foreach($posts->skip(1)->take(5) as $post)
                        @include('partials.post-item', ['post' => $post])
                        @endforeach
                    </div>

                    <div style="text-align: right; margin-top: 10px;">
                        <button id="load-more-btn"
                                class="load-more-center-btn"
                                data-offset="6"
                                data-category="all"
                                data-url="{{ route('posts.load-more') }}"
                                @if($totalPosts <= 6) style="display:none" @endif>
                            Xem thêm
                        </button>
                    </div>
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
document.addEventListener('DOMContentLoaded', function() {

    // ── Hero Slider ──────────────────────────────────────
    var slides = document.querySelectorAll('.hero-slide');
    var dots   = document.querySelectorAll('.hero-dot');
    var prevBtn = document.getElementById('hero-prev');
    var nextBtn = document.getElementById('hero-next');
    var currentSlide = 0;
    var autoTimer = null;

    function goTo(index) {
        slides[currentSlide].classList.remove('active');
        if (dots[currentSlide]) dots[currentSlide].classList.remove('active');
        currentSlide = (index + slides.length) % slides.length;
        slides[currentSlide].classList.add('active');
        if (dots[currentSlide]) dots[currentSlide].classList.add('active');
    }

    function startAuto() {
        autoTimer = setInterval(function() { goTo(currentSlide + 1); }, 4000);
    }

    function resetAuto() {
        clearInterval(autoTimer);
        startAuto();
    }

    if (slides.length > 1) {
        if (nextBtn) nextBtn.addEventListener('click', function() { goTo(currentSlide + 1); resetAuto(); });
        if (prevBtn) prevBtn.addEventListener('click', function() { goTo(currentSlide - 1); resetAuto(); });
        dots.forEach(function(dot) {
            dot.addEventListener('click', function() { goTo(parseInt(this.dataset.index)); resetAuto(); });
        });
        startAuto();
    }

    // ── Load more posts ───────────────────────────────────
    var loadMoreBtn = document.getElementById('load-more-btn');
    if (loadMoreBtn) {
        loadMoreBtn.addEventListener('click', function() {
            var btn = this;
            var offset = parseInt(btn.dataset.offset);
            var url = btn.dataset.url;
            var category = btn.dataset.category || 'all';

            btn.textContent = 'Đang tải...';
            btn.disabled = true;

            fetch(url + '?offset=' + offset + '&category=' + category)
                .then(function(res) { return res.json(); })
                .then(function(data) {
                    var container = document.getElementById('posts-container');
                    container.insertAdjacentHTML('beforeend', data.html);
                    if (window.observeNewPosts) window.observeNewPosts(container);
                    btn.dataset.offset = data.nextOffset;
                    if (data.hasMore) {
                        btn.textContent = 'Xem thêm';
                        btn.disabled = false;
                    } else {
                        btn.style.display = 'none';
                    }
                })
                .catch(function() {
                    btn.textContent = 'Xem thêm';
                    btn.disabled = false;
                });
        });
    }

    // ── Filter tabs ───────────────────────────────────────
    document.querySelectorAll('.news-tab').forEach(function(tab) {
        tab.addEventListener('click', function() {
            document.querySelectorAll('.news-tab').forEach(function(t) { t.classList.remove('active'); });
            this.classList.add('active');

            var category = this.dataset.category;
            var url = this.dataset.url;
            var btn = document.getElementById('load-more-btn');

            btn.dataset.category = category;
            btn.dataset.offset = '6';

            fetch(url + '?offset=0&limit=6&category=' + category)
                .then(function(res) { return res.json(); })
                .then(function(data) {
                    var featuredArea = document.getElementById('featured-post-area');
                    if (data.featured) {
                        featuredArea.innerHTML = data.featured;
                        featuredArea.classList.add('visible');
                    } else {
                        featuredArea.innerHTML = '<p style="color:#999;padding:20px 0;">Không có bài viết.</p>';
                    }
                    var container = document.getElementById('posts-container');
                    container.innerHTML = data.html || '';
                    if (window.observeNewPosts) window.observeNewPosts(container);
                    if (data.hasMore) {
                        btn.style.display = 'inline-block';
                        btn.textContent = 'Xem thêm';
                        btn.disabled = false;
                    } else {
                        btn.style.display = 'none';
                    }
                })
                .catch(function(err) { console.error('Filter error:', err); });
        });
    });

});
</script>
@endpush
