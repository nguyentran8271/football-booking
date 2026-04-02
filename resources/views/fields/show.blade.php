@extends('layouts.app')

@section('title', $field->name)

@section('content')
<section class="section">
    <div class="container">
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 40px; margin-bottom: 40px;">
            <!-- Field Image -->
            <div>
                <img src="{{ $field->image ? asset('storage/' . $field->image) : asset('images/default-field.jpg') }}"
                     alt="{{ $field->name }}"
                     style="width: 100%; border-radius: 10px;">
            </div>

            <!-- Field Info -->
            <div class="card">
                <h1 style="margin-bottom: 20px;">{{ $field->name }}</h1>

                <div style="margin-bottom: 15px;">
                    <strong>📍 Địa chỉ:</strong> {{ $field->address }}
                </div>

                <div style="margin-bottom: 15px;">
                    <strong>💰 Giá:</strong>
                    <span style="color: #28a745; font-size: 24px; font-weight: bold;">
                        {{ number_format($field->price_per_hour) }}đ/giờ
                    </span>
                </div>

                <div style="margin-bottom: 15px;">
                    <strong>⭐ Đánh giá:</strong>
                    {{ number_format($field->averageRating(), 1) }}
                    ({{ $field->reviews->count() }} đánh giá)
                </div>

                <div style="margin-bottom: 20px;">
                    <strong>👤 Chủ sân:</strong> {{ $field->owner->name }}
                </div>

                @auth
                <a href="{{ route('bookings.create', $field->id) }}" class="btn btn-primary" style="width: 100%; padding: 15px; font-size: 18px;">
                    Đặt sân ngay
                </a>
                @else
                <a href="{{ route('login') }}" class="btn btn-primary" style="width: 100%; padding: 15px; font-size: 18px;">
                    Đăng nhập để đặt sân
                </a>
                @endauth
            </div>
        </div>

        <!-- Description -->
        @if($field->description)
        <div class="card">
            <h2 class="card-title">Mô tả</h2>
            <p>{{ $field->description }}</p>
        </div>
        @endif

        <!-- Reviews -->
        <div class="card">
            <h2 class="card-title">Đánh giá ({{ $field->reviews->count() }})</h2>

            @auth
            <!-- Review Form -->
            @if($hasReviewed)
            <div style="margin-bottom: 30px; padding: 15px 20px; background: #e8f5e9; border-radius: 10px; color: #2e7d32;">
                ✅ Bạn đã đánh giá sân này rồi.
            </div>
            @elseif($hasBooked)
            <form action="{{ route('reviews.store') }}" method="POST" style="margin-bottom: 30px; padding: 20px; background: #f8f9fa; border-radius: 10px;">
                @csrf
                <input type="hidden" name="field_id" value="{{ $field->id }}">
                <input type="hidden" name="type" value="field">

                <div class="form-group">
                    <label class="form-label">Đánh giá của bạn</label>
                    <select name="rating" class="form-control" required>
                        <option value="5">⭐⭐⭐⭐⭐ Xuất sắc</option>
                        <option value="4">⭐⭐⭐⭐ Tốt</option>
                        <option value="3">⭐⭐⭐ Trung bình</option>
                        <option value="2">⭐⭐ Kém</option>
                        <option value="1">⭐ Rất kém</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Nhận xét</label>
                    <textarea name="comment" class="form-control" rows="3" placeholder="Chia sẻ trải nghiệm của bạn về sân này..."></textarea>
                </div>

                <button type="submit" class="btn btn-primary">Gửi đánh giá</button>
            </form>
            @else
            <div style="margin-bottom: 30px; padding: 15px 20px; background: #fff3e0; border-radius: 10px; color: #e65100;">
                ⚠️ Bạn cần đặt và hoàn thành lịch tại sân này mới có thể đánh giá.
                <a href="{{ route('bookings.create', $field->id) }}" style="color: #e65100; font-weight: 600; margin-left: 8px;">Đặt sân ngay →</a>
            </div>
            @endif
            @else
            <div style="margin-bottom: 30px; padding: 15px 20px; background: #f8f9fa; border-radius: 10px; color: #666;">
                <a href="{{ route('login') }}" style="color: var(--primary-color); font-weight: 600;">Đăng nhập</a> để đánh giá sân này.
            </div>
            @endauth

            <!-- Reviews List -->
            @foreach($field->reviews as $review)
            <div style="padding: 20px; border-bottom: 1px solid #eee;">
                <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                    <strong>{{ $review->user->name }}</strong>
                    <span style="color: #ffc107;">
                        @for($i = 0; $i < $review->rating; $i++)⭐@endfor
                    </span>
                </div>
                @if($review->comment)
                <p style="color: #666;">{{ $review->comment }}</p>
                @endif
                <small style="color: #999;">{{ $review->created_at->format('d/m/Y H:i') }}</small>
            </div>
            @endforeach

            @if($field->reviews->count() === 0)
            <p style="text-align: center; color: #999; padding: 20px;">Chưa có đánh giá nào.</p>
            @endif
        </div>
    </div>
</section>
@endsection
