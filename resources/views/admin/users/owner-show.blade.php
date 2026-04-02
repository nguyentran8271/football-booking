@extends('layouts.app')
@section('title', 'Chi tiết chủ sân - ' . $owner->name)
@section('content')
<section class="section">
    <div class="container">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:24px;">
            <h1>Chi tiết chủ sân</h1>
            <a href="{{ route('admin.owners.index') }}" class="btn btn-secondary">← Quay lại</a>
        </div>

        {{-- Thông tin cá nhân --}}
        <div class="card" style="margin-bottom:24px;">
            <h2 class="card-title">Thông tin cá nhân</h2>
            <table class="table">
                <tr><td style="width:180px;color:#666;">Họ tên</td><td><strong>{{ $owner->name }}</strong></td></tr>
                <tr><td style="color:#666;">Email</td><td>{{ $owner->email }}</td></tr>
                <tr><td style="color:#666;">Số điện thoại</td><td>{{ $owner->phone ?? 'N/A' }}</td></tr>
                <tr><td style="color:#666;">Ngày đăng ký</td><td>{{ $owner->created_at->format('d/m/Y H:i') }}</td></tr>
                <tr>
                    <td style="color:#666; vertical-align:top; padding-top:12px;">Doanh thu</td>
                    <td>
                        <form method="GET" action="{{ route('admin.owners.show', $owner->id) }}" style="display:flex; gap:10px; align-items:center; flex-wrap:wrap; margin-bottom:10px;">
                            <select name="filter_type" onchange="this.form.submit()" style="padding:6px 10px; border:1px solid #ddd; border-radius:6px;">
                                <option value="month" {{ $filterType == 'month' ? 'selected' : '' }}>Theo tháng</option>
                                <option value="year" {{ $filterType == 'year' ? 'selected' : '' }}>Theo năm</option>
                            </select>
                            @if($filterType == 'month')
                            <select name="filter_month" onchange="this.form.submit()" style="padding:6px 10px; border:1px solid #ddd; border-radius:6px;">
                                @for($m = 1; $m <= 12; $m++)
                                <option value="{{ $m }}" {{ $filterMonth == $m ? 'selected' : '' }}>Tháng {{ $m }}</option>
                                @endfor
                            </select>
                            @endif
                            <select name="filter_year" onchange="this.form.submit()" style="padding:6px 10px; border:1px solid #ddd; border-radius:6px;">
                                @for($y = now()->year; $y >= now()->year - 3; $y--)
                                <option value="{{ $y }}" {{ $filterYear == $y ? 'selected' : '' }}>{{ $y }}</option>
                                @endfor
                            </select>
                        </form>
                        <strong style="color:#28a745; font-size:20px;">{{ number_format($filteredRevenue) }}đ</strong>
                    </td>
                </tr>
            </table>
        </div>

        {{-- Danh sách sân --}}
        <div class="card" style="margin-bottom:24px;">
            <h2 class="card-title">Sân đang quản lý ({{ $fields->count() }})</h2>
            @if($fields->count() > 0)
            <table class="table">
                <thead>
                    <tr><th>Tên sân</th><th>Địa chỉ</th><th>Giá/giờ</th><th>Lượt đặt</th><th>Trạng thái</th></tr>
                </thead>
                <tbody>
                    @foreach($fields as $field)
                    <tr>
                        <td><strong>{{ $field->name }}</strong></td>
                        <td>{{ Str::limit($field->address, 50) }}</td>
                        <td>{{ number_format($field->price_per_hour) }}đ</td>
                        <td>{{ $field->bookings_count }}</td>
                        <td>
                            @if($field->status == 'active')
                                <span style="background:#d4edda;color:#155724;padding:4px 10px;border-radius:20px;font-size:13px;">Hoạt động</span>
                            @else
                                <span style="background:#f8d7da;color:#721c24;padding:4px 10px;border-radius:20px;font-size:13px;">Tạm ngưng</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <p style="text-align:center;padding:20px;color:#999;">Chưa có sân nào.</p>
            @endif
        </div>

        {{-- Giải đấu --}}
        <div class="card" style="margin-bottom:24px;">
            <h2 class="card-title">Giải đấu ({{ $tournaments->count() }})</h2>
            @if($tournaments->count() > 0)
            <table class="table">
                <thead>
                    <tr><th>Tên giải</th><th>Sân</th><th>Thời gian</th><th>Số đội</th><th>Phí</th><th>Trạng thái</th></tr>
                </thead>
                <tbody>
                    @foreach($tournaments as $t)
                    <tr>
                        <td><strong>{{ $t->name }}</strong></td>
                        <td>{{ $t->field->name }}</td>
                        <td>{{ $t->start_date->format('d/m/Y') }} - {{ $t->end_date->format('d/m/Y') }}</td>
                        <td>{{ $t->teams->count() }}/{{ $t->max_teams }}</td>
                        <td>{{ number_format($t->entry_fee) }}đ</td>
                        <td>
                            @if($t->status == 'upcoming')
                                <span style="background:#fff3cd;color:#856404;padding:4px 10px;border-radius:20px;font-size:13px;">Sắp diễn ra</span>
                            @elseif($t->status == 'ongoing')
                                <span style="background:#d4edda;color:#155724;padding:4px 10px;border-radius:20px;font-size:13px;">Đang diễn ra</span>
                            @else
                                <span style="background:#d1ecf1;color:#0c5460;padding:4px 10px;border-radius:20px;font-size:13px;">Đã kết thúc</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <p style="text-align:center;padding:20px;color:#999;">Chưa có giải đấu nào.</p>
            @endif
        </div>

        {{-- Booking gần đây --}}
        <div class="card">
            <h2 class="card-title">Booking gần đây</h2>
            @if($recentBookings->count() > 0)
            <div style="overflow-x:auto;">
                <table class="table">
                    <thead>
                        <tr><th>Khách hàng</th><th>Sân</th><th>Ngày</th><th>Ca</th><th>Tổng tiền</th><th>Trạng thái</th></tr>
                    </thead>
                    <tbody>
                        @foreach($recentBookings as $booking)
                        <tr>
                            <td>{{ $booking->user->name }}</td>
                            <td>{{ $booking->field->name }}</td>
                            <td>{{ \Carbon\Carbon::parse($booking->date)->format('d/m/Y') }}</td>
                            <td>Ca {{ $booking->shift }}</td>
                            <td>{{ number_format($booking->total_price) }}đ</td>
                            <td>
                                @if($booking->status == 'pending')
                                    <span style="background:#fff3cd;color:#856404;padding:4px 10px;border-radius:20px;font-size:13px;">Chờ duyệt</span>
                                @elseif($booking->status == 'approved')
                                    <span style="background:#d4edda;color:#155724;padding:4px 10px;border-radius:20px;font-size:13px;">Đã duyệt</span>
                                @else
                                    <span style="background:#f8d7da;color:#721c24;padding:4px 10px;border-radius:20px;font-size:13px;">Đã hủy</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <p style="text-align:center;padding:20px;color:#999;">Chưa có booking nào.</p>
            @endif
        </div>
    </div>
</section>
@endsection
