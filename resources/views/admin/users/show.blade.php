@extends('layouts.app')
@section('title', 'Chi tiết người dùng - ' . $user->name)
@section('content')
<section class="section">
    <div class="container">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:24px;">
            <h1>Chi tiết người dùng</h1>
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">← Quay lại</a>
        </div>

        <div class="card" style="margin-bottom:24px;">
            <h2 class="card-title">Thông tin cá nhân</h2>
            <table class="table">
                <tr><td style="width:180px; color:#666;">Họ tên</td><td><strong>{{ $user->name }}</strong></td></tr>
                <tr><td style="color:#666;">Email</td><td>{{ $user->email }}</td></tr>
                <tr><td style="color:#666;">Số điện thoại</td><td>{{ $user->phone ?? 'N/A' }}</td></tr>
                <tr><td style="color:#666;">Ngày đăng ký</td><td>{{ $user->created_at->format('d/m/Y H:i') }}</td></tr>
                <tr><td style="color:#666;">Tổng lượt đặt</td><td>{{ $bookings->total() }}</td></tr>
            </table>
        </div>

        <div class="card">
            <h2 class="card-title">Lịch sử đặt sân</h2>
            @if($bookings->count() > 0)
            <div style="overflow-x:auto;">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Sân</th>
                            <th>Ngày</th>
                            <th>Ca</th>
                            <th>Giờ</th>
                            <th>Tổng tiền</th>
                            <th>Trạng thái</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($bookings as $booking)
                        <tr>
                            <td>{{ $booking->field->name }}</td>
                            <td>{{ \Carbon\Carbon::parse($booking->date)->format('d/m/Y') }}</td>
                            <td>Ca {{ $booking->shift }}</td>
                            <td>{{ $booking->start_time }} - {{ $booking->end_time }}</td>
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
            <div style="margin-top:16px;">{{ $bookings->links() }}</div>
            @else
            <p style="text-align:center;padding:30px;color:#999;">Chưa có lịch đặt sân nào.</p>
            @endif
        </div>
    </div>
</section>
@endsection
