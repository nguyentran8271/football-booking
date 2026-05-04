@extends('layouts.app')

@section('title', 'Đánh giá từ khách hàng')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/reviews.css') }}">
@endpush

@section('content')
<!-- Hero Banner -->
<section class="reviews-hero" style="background-image: url('{{ App\Models\SiteSetting::get('reviews_banner') ? storage_url(App\Models\SiteSetting::get('reviews_banner')) : '' }}');">
    <div class="hero-overlay"></div>
    <div class="hero-content">
        <h1>{{ App\Models\SiteSetting::get('reviews_title') ?: 'Đánh Giá Từ Khách Hàng' }}</h1>
        <p>{{ App\Models\SiteSetting::get('reviews_description') ?: 'Chia sẻ trải nghiệm thực tế từ cộng đồng người chơi' }}</p>
    </div>
</section>

<!-- Tổng quan đánh giá -->
<section class="section">
    <div class="container">
        <div class="review-overview">
            <div class="overview-main">
                <div class="rating-score">
                    <div class="score-number">{{ number_format($averageRating, 1) }}</div>
                    <div class="score-stars">
                        @for($i = 1; $i <= 5; $i++)
                            @if($i <= floor($averageRating))
                                <span class="star filled">★</span>
                            @elseif($i - 0.5 <= $averageRating)
                                <span class="star half">★</span>
                            @else
                                <span class="star">★</span>
                            @endif
                        @endfor
                    </div>
                    <div class="score-text">{{ number_format($totalReviews) }} lượt đánh giá</div>
                </div>
            </div>

            <div class="overview-stats">
                <div class="stat-item">
                    <span class="stat-label">Khách hàng hài lòng</span>
                    <span class="stat-value">{{ $satisfactionRate }}%</span>
                </div>
                <div class="stat-item">
                    <span class="stat-label">Tổng đánh giá</span>
                    <span class="stat-value">{{ number_format($totalReviews) }}</span>
                </div>
            </div>
        </div>

        <!-- Đánh giá chi tiết -->
        <div class="detailed-ratings">
            <h3>Đánh giá chi tiết về website</h3>
            <div class="rating-bars">
                <div class="rating-bar-item">
                    <span class="rating-label">Giao diện & Thiết kế</span>
                    <div class="rating-bar-container">
                        <div class="rating-bar" style="width: {{ ($detailedRatings['field_quality'] / 5) * 100 }}%"></div>
                    </div>
                    <span class="rating-value">{{ number_format($detailedRatings['field_quality'], 1) }}</span>
                </div>
                <div class="rating-bar-item">
                    <span class="rating-label">Tính năng đặt sân</span>
                    <div class="rating-bar-container">
                        <div class="rating-bar" style="width: {{ ($detailedRatings['lighting'] / 5) * 100 }}%"></div>
                    </div>
                    <span class="rating-value">{{ number_format($detailedRatings['lighting'], 1) }}</span>
                </div>
                <div class="rating-bar-item">
                    <span class="rating-label">Tốc độ tải trang</span>
                    <div class="rating-bar-container">
                        <div class="rating-bar" style="width: {{ ($detailedRatings['hygiene'] / 5) * 100 }}%"></div>
                    </div>
                    <span class="rating-value">{{ number_format($detailedRatings['hygiene'], 1) }}</span>
                </div>
                <div class="rating-bar-item">
                    <span class="rating-label">Hỗ trợ khách hàng</span>
                    <div class="rating-bar-container">
                        <div class="rating-bar" style="width: {{ ($detailedRatings['staff'] / 5) * 100 }}%"></div>
                    </div>
                    <span class="rating-value">{{ number_format($detailedRatings['staff'], 1) }}</span>
                </div>
                <div class="rating-bar-item">
                    <span class="rating-label">Dễ sử dụng</span>
                    <div class="rating-bar-container">
                        <div class="rating-bar" style="width: {{ ($detailedRatings['price'] / 5) * 100 }}%"></div>
                    </div>
                    <span class="rating-value">{{ number_format($detailedRatings['price'], 1) }}</span>
                </div>
            </div>
        </div>

        <!-- Form đánh giá trải nghiệm web -->
        @auth
        @if($hasWebReviewed)
        <div style="margin-bottom: 30px; padding: 20px; background: #e8f5e9; border-radius: 10px;">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:10px;">
                <strong style="color:#2e7d32;">Đánh giá trải nghiệm của bạn</strong>
                <div style="display:flex; gap:8px; align-items:center;">
                    <button onclick="document.getElementById('edit-web-review').style.display='block'; document.getElementById('web-review-display').style.display='none';" class="btn btn-secondary" style="padding:5px 14px; font-size:13px; height:34px; line-height:1;">Sửa</button>
                    <form action="{{ route('reviews.destroy', $userWebReview->id) }}" method="POST" style="margin:0;" onsubmit="return confirm('Xóa đánh giá này?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" style="padding:5px 14px; font-size:13px; height:34px; line-height:1;">Xóa</button>
                    </form>
                </div>
            </div>
            <div id="web-review-display">
                <span style="color:#ffc107;">@for($i=0;$i<$userWebReview->rating;$i++)⭐@endfor</span>
                @if($userWebReview->comment)<p style="color:#555; margin-top:8px;">{{ $userWebReview->comment }}</p>@endif
            </div>
            <form id="edit-web-review" action="{{ route('reviews.update', $userWebReview->id) }}" method="POST" style="display:none; margin-top:15px;">
                @csrf
                @method('PUT')
                <div style="margin-bottom: 16px;">
                    <label style="display: block; font-weight: 600; margin-bottom: 6px;">Đánh giá tổng thể</label>
                    <select name="rating" class="form-control" required style="max-width: 300px;">
                        @for($i=5;$i>=1;$i--)
                        <option value="{{ $i }}" {{ $userWebReview->rating == $i ? 'selected' : '' }}>
                            {{ str_repeat('⭐', $i) }} {{ ['','Rất kém','Kém','Trung bình','Tốt','Xuất sắc'][$i] }}
                        </option>
                        @endfor
                    </select>
                </div>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 12px; margin-bottom: 16px;">
                    @foreach([
                        ['field_quality_rating', 'Giao diện & Thiết kế'],
                        ['lighting_rating', 'Tính năng đặt sân'],
                        ['hygiene_rating', 'Tốc độ tải trang'],
                        ['staff_rating', 'Hỗ trợ khách hàng'],
                        ['price_rating', 'Dễ sử dụng'],
                    ] as [$field, $label])
                    <div>
                        <label style="display: block; font-size: 13px; color: #666; margin-bottom: 4px;">{{ $label }}</label>
                        <select name="{{ $field }}" class="form-control">
                            <option value="">-- Chọn --</option>
                            @for($i=5;$i>=1;$i--)
                            <option value="{{ $i }}" {{ $userWebReview->$field == $i ? 'selected' : '' }}>{{ $i }} sao</option>
                            @endfor
                        </select>
                    </div>
                    @endforeach
                </div>
                <div style="margin-bottom: 16px;">
                    <label style="display: block; font-weight: 600; margin-bottom: 6px;">Nhận xét</label>
                    <textarea name="comment" class="form-control" rows="3">{{ $userWebReview->comment }}</textarea>
                </div>
                <button type="submit" class="btn btn-primary">Lưu</button>
                <button type="button" onclick="document.getElementById('edit-web-review').style.display='none'; document.getElementById('web-review-display').style.display='block';" class="btn btn-secondary">Hủy</button>
            </form>
        </div>
        @else
        <div class="card" style="margin-bottom: 40px; padding: 30px;">
            <h3 style="margin-bottom: 20px;">✍️ Chia sẻ trải nghiệm của bạn</h3>
            @if(session('success'))
            <div style="padding: 12px 16px; background: #e8f5e9; border-radius: 8px; color: #2e7d32; margin-bottom: 16px;">
                {{ session('success') }}
            </div>
            @endif
            <form action="{{ route('reviews.store') }}" method="POST">
                @csrf
                <input type="hidden" name="type" value="web">

                <div style="margin-bottom: 16px;">
                    <label style="display: block; font-weight: 600; margin-bottom: 6px;">Đánh giá tổng thể <span style="color:red">*</span></label>
                    <select name="rating" class="form-control" required style="max-width: 300px;">
                        <option value="5">⭐⭐⭐⭐⭐ Xuất sắc</option>
                        <option value="4">⭐⭐⭐⭐ Tốt</option>
                        <option value="3" selected>⭐⭐⭐ Trung bình</option>
                        <option value="2">⭐⭐ Kém</option>
                        <option value="1">⭐ Rất kém</option>
                    </select>
                </div>

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 12px; margin-bottom: 16px;">
                    <div>
                        <label style="display: block; font-size: 13px; color: #666; margin-bottom: 4px;">Giao diện & Thiết kế</label>
                        <select name="field_quality_rating" class="form-control">
                            <option value="">-- Chọn --</option>
                            @for($i = 5; $i >= 1; $i--)
                            <option value="{{ $i }}">{{ $i }} sao</option>
                            @endfor
                        </select>
                    </div>
                    <div>
                        <label style="display: block; font-size: 13px; color: #666; margin-bottom: 4px;">Tính năng đặt sân</label>
                        <select name="lighting_rating" class="form-control">
                            <option value="">-- Chọn --</option>
                            @for($i = 5; $i >= 1; $i--)
                            <option value="{{ $i }}">{{ $i }} sao</option>
                            @endfor
                        </select>
                    </div>
                    <div>
                        <label style="display: block; font-size: 13px; color: #666; margin-bottom: 4px;">Tốc độ tải trang</label>
                        <select name="hygiene_rating" class="form-control">
                            <option value="">-- Chọn --</option>
                            @for($i = 5; $i >= 1; $i--)
                            <option value="{{ $i }}">{{ $i }} sao</option>
                            @endfor
                        </select>
                    </div>
                    <div>
                        <label style="display: block; font-size: 13px; color: #666; margin-bottom: 4px;">Hỗ trợ khách hàng</label>
                        <select name="staff_rating" class="form-control">
                            <option value="">-- Chọn --</option>
                            @for($i = 5; $i >= 1; $i--)
                            <option value="{{ $i }}">{{ $i }} sao</option>
                            @endfor
                        </select>
                    </div>
                    <div>
                        <label style="display: block; font-size: 13px; color: #666; margin-bottom: 4px;">Dễ sử dụng</label>
                        <select name="price_rating" class="form-control">
                            <option value="">-- Chọn --</option>
                            @for($i = 5; $i >= 1; $i--)
                            <option value="{{ $i }}">{{ $i }} sao</option>
                            @endfor
                        </select>
                    </div>
                </div>

                <div style="margin-bottom: 16px;">
                    <label style="display: block; font-weight: 600; margin-bottom: 6px;">Nhận xét</label>
                    <textarea name="comment" class="form-control" rows="3" placeholder="Chia sẻ trải nghiệm sử dụng website của bạn..."></textarea>
                </div>

                <button type="submit" class="btn btn-primary">Gửi đánh giá</button>
            </form>
        </div>
        @endif
        @else
        <div style="margin-bottom: 30px; padding: 15px 20px; background: #f8f9fa; border-radius: 10px; color: #666; text-align: center;">
            <a href="{{ route('login') }}" style="color: var(--primary-color); font-weight: 600;">Đăng nhập</a> để chia sẻ trải nghiệm của bạn.
        </div>
        @endauth

        <!-- Bộ lọc -->
        <div class="review-filters">
            <form method="GET" action="{{ route('reviews.index') }}" id="filterForm">
                <div class="filters-row">
                    <div class="filter-group">
                        <label>Lọc theo số sao:</label>
                        <div class="star-filters">
                            <button type="button" class="filter-btn {{ !request('rating') ? 'active' : '' }}" onclick="filterByStar('')">
                                Tất cả
                            </button>
                            @for($i = 5; $i >= 1; $i--)
                            <button type="button" class="filter-btn {{ request('rating') == $i ? 'active' : '' }}" onclick="filterByStar({{ $i }})">
                                {{ $i }} Sao
                            </button>
                            @endfor
                        </div>
                        <input type="hidden" name="rating" id="ratingFilter" value="{{ request('rating') }}">
                    </div>

                    <div class="filter-group">
                        <label>Sắp xếp:</label>
                        <select name="sort" class="filter-select" onchange="this.form.submit()">
                            <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Mới nhất</option>
                            <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Cũ nhất</option>
                            <option value="highest" {{ request('sort') == 'highest' ? 'selected' : '' }}>Điểm cao nhất</option>
                            <option value="lowest" {{ request('sort') == 'lowest' ? 'selected' : '' }}>Điểm thấp nhất</option>
                            <option value="helpful" {{ request('sort') == 'helpful' ? 'selected' : '' }}>Hữu ích nhất</option>
                        </select>
                    </div>
                </div>
            </form>
        </div>

        <!-- Danh sách đánh giá -->
        <div class="reviews-grid" id="reviewsGrid">
            @foreach($reviews->take(4) as $review)
            <div class="review-card">
                <div class="review-header">
                    <div class="reviewer-info">
                        <div class="reviewer-avatar">
                            {{ strtoupper(substr($review->user->name, 0, 1)) }}
                        </div>
                        <div class="reviewer-details">
                            <h4 class="reviewer-name">{{ $review->user->name }}</h4>
                            <span class="review-date">{{ $review->created_at->format('d/m/Y') }}</span>
                        </div>
                    </div>
                    <div class="review-rating">
                        @for($i = 1; $i <= 5; $i++)
                            <span class="star {{ $i <= $review->rating ? 'filled' : '' }}">★</span>
                        @endfor
                    </div>
                </div>

                <div class="review-content">
                    <p>{{ $review->comment }}</p>
                </div>

                @if($review->images && count($review->images) > 0)
                <div class="review-images">
                    @foreach($review->images as $image)
                    <img src="{{ storage_url($image) }}" alt="Review image" class="review-image">
                    @endforeach
                </div>
                @endif

                <div class="review-actions">
                    <button type="button" class="btn-helpful" onclick="markHelpful({{ $review->id }}, this)">
                        Hữu ích (<span class="helpful-count">{{ $review->helpful_count }}</span>)
                    </button>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Hidden reviews for load more -->
        <div class="hidden-reviews" style="display: none;">
            @foreach($reviews->skip(4) as $review)
            <div class="review-card hidden-review">
                <div class="review-header">
                    <div class="reviewer-info">
                        <div class="reviewer-avatar">
                            {{ strtoupper(substr($review->user->name, 0, 1)) }}
                        </div>
                        <div class="reviewer-details">
                            <h4 class="reviewer-name">{{ $review->user->name }}</h4>
                            <span class="review-date">{{ $review->created_at->format('d/m/Y') }}</span>
                        </div>
                    </div>
                    <div class="review-rating">
                        @for($i = 1; $i <= 5; $i++)
                            <span class="star {{ $i <= $review->rating ? 'filled' : '' }}">★</span>
                        @endfor
                    </div>
                </div>

                <div class="review-content">
                    <p>{{ $review->comment }}</p>
                </div>

                @if($review->images && count($review->images) > 0)
                <div class="review-images">
                    @foreach($review->images as $image)
                    <img src="{{ storage_url($image) }}" alt="Review image" class="review-image">
                    @endforeach
                </div>
                @endif

                <div class="review-actions">
                    <button type="button" class="btn-helpful" onclick="markHelpful({{ $review->id }}, this)">
                        Hữu ích (<span class="helpful-count">{{ $review->helpful_count }}</span>)
                    </button>
                </div>
            </div>
            @endforeach
        </div>

        @if($reviews->count() > 4)
        <div class="load-more-container">
            <button type="button" class="btn btn-primary btn-load-more" id="loadMoreBtn">
                Xem thêm đánh giá
            </button>
        </div>
        @endif
    </div>
</section>

@push('scripts')
<script src="{{ asset('js/reviews-animations.js') }}"></script>
<script>
let currentIndex = 0;
const hiddenReviews = document.querySelectorAll('.hidden-review');
const reviewsGrid = document.getElementById('reviewsGrid');
const loadMoreBtn = document.getElementById('loadMoreBtn');

if (loadMoreBtn) {
    loadMoreBtn.addEventListener('click', function() {
        // Load 2 reviews at a time
        for (let i = 0; i < 2 && currentIndex < hiddenReviews.length; i++) {
            const review = hiddenReviews[currentIndex];
            review.style.display = 'block';
            reviewsGrid.appendChild(review);

            // Trigger animation for newly added reviews
            setTimeout(() => {
                review.classList.add('reveal');
            }, 50);

            currentIndex++;
        }

        // Hide button if no more reviews
        if (currentIndex >= hiddenReviews.length) {
            loadMoreBtn.style.display = 'none';
        }
    });
}

function filterByStar(rating) {
    document.getElementById('ratingFilter').value = rating;
    document.getElementById('filterForm').submit();
}

function markHelpful(reviewId, button) {
    @guest
    window.location.href = '{{ route('login') }}';
    return;
    @endguest

    fetch(`/reviews/${reviewId}/helpful`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            button.querySelector('.helpful-count').textContent = data.helpful_count;
            if (data.liked) {
                button.classList.add('liked');
            } else {
                button.classList.remove('liked');
            }
        }
    })
    .catch(error => console.error('Error:', error));
}
</script>
@endpush
@endsection
