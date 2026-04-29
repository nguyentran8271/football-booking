@extends('layouts.app')

@section('title', $field->name)

@section('content')
<section class="section">
    <div class="container">
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 40px; margin-bottom: 40px;">
            <!-- Field Image -->
            <div>
                <img src="{{ $field->image_url }}"
                     alt="{{ $field->name }}"
                     style="width: 100%; border-radius: 10px;">
            </div>

            <!-- Field Info -->
            <div class="card">
                <h1 style="margin-bottom: 20px;">{{ $field->name }}</h1>

                <div style="margin-bottom: 15px; display:flex; align-items:flex-start; gap:8px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#6c757d" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;margin-top:3px"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                    <span><strong>Địa chỉ:</strong> {{ $field->address }}</span>
                </div>

                <div style="margin-bottom: 15px; display:flex; align-items:center; gap:8px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#28a745" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                    <span><strong>Giá:</strong>
                        <span style="color: #28a745; font-size: 24px; font-weight: bold;">
                            {{ number_format($field->price_per_hour) }}đ/giờ
                        </span>
                    </span>
                </div>

                <div style="margin-bottom: 15px; display:flex; align-items:center; gap:8px;">
                    <span class="field-stars" style="display:flex;gap:2px;">
                        @php $avg = $field->averageRating(); @endphp
                        @for($i = 1; $i <= 5; $i++)
                            @if($i <= floor($avg))
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="#ffc107" xmlns="http://www.w3.org/2000/svg"><polygon points="12,2 15.09,8.26 22,9.27 17,14.14 18.18,21.02 12,17.77 5.82,21.02 7,14.14 2,9.27 8.91,8.26"/></svg>
                            @else
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="#ddd" xmlns="http://www.w3.org/2000/svg"><polygon points="12,2 15.09,8.26 22,9.27 17,14.14 18.18,21.02 12,17.77 5.82,21.02 7,14.14 2,9.27 8.91,8.26"/></svg>
                            @endif
                        @endfor
                    </span>
                    <span>{{ number_format($avg, 1) }} ({{ $field->reviews->count() }} đánh giá)</span>
                </div>

                <div style="margin-bottom: 20px; display:flex; align-items:center; gap:8px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#6c757d" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    <span><strong>Chủ sân:</strong> {{ $field->owner->name }}</span>
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
            @php $userReview = $field->reviews->where('user_id', auth()->id())->first(); @endphp
            <div style="margin-bottom: 30px; padding: 20px; background: #e8f5e9; border-radius: 10px;">
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:10px;">
                    <strong style="color:#2e7d32;">Đánh giá của bạn</strong>
                    <div style="display:flex; gap:8px; align-items:center;">
                        <button onclick="document.getElementById('edit-review-form').style.display='block'; this.parentElement.parentElement.parentElement.querySelector('.review-display').style.display='none';" class="btn btn-secondary" style="padding:5px 14px; font-size:13px; height:34px; line-height:1;">Sửa</button>
                        <form action="{{ route('reviews.destroy', $userReview->id) }}" method="POST" style="margin:0;" onsubmit="return confirm('Xóa đánh giá này?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" style="padding:5px 14px; font-size:13px; height:34px; line-height:1;">Xóa</button>
                        </form>
                    </div>
                </div>
                <div class="review-display">
                    <span style="color:#ffc107;">@for($i=0;$i<$userReview->rating;$i++)⭐@endfor</span>
                    @if($userReview->comment)<p style="color:#555; margin-top:8px;">{{ $userReview->comment }}</p>@endif
                </div>
                <form id="edit-review-form" action="{{ route('reviews.update', $userReview->id) }}" method="POST" style="display:none; margin-top:10px;">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label class="form-label">Đánh giá</label>
                        <select name="rating" class="form-control" required>
                            @for($i=5;$i>=1;$i--)
                            <option value="{{ $i }}" {{ $userReview->rating == $i ? 'selected' : '' }}>
                                {{ str_repeat('⭐', $i) }} {{ ['','Rất kém','Kém','Trung bình','Tốt','Xuất sắc'][$i] }}
                            </option>
                            @endfor
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Nhận xét</label>
                        <textarea name="comment" class="form-control" rows="3">{{ $userReview->comment }}</textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Lưu</button>
                    <button type="button" onclick="document.getElementById('edit-review-form').style.display='none'; this.closest('.review-display') || document.querySelector('.review-display').style.display='block';" class="btn btn-secondary">Hủy</button>
                </form>
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
