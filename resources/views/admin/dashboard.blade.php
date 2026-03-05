@extends('layouts.app')

@section('title', 'Admin Dashboard')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
@endpush

@section('content')
<section class="section">
    <div class="container">
        <h1 style="margin-bottom: 30px;">Admin Dashboard</h1>

        <!-- Stats -->
        <div class="dashboard-stats">
            <div class="stat-card">
                <h3>Tổng người dùng</h3>
                <div class="number">{{ $totalUsers }}</div>
            </div>
            <div class="stat-card">
                <h3>Tổng chủ sân</h3>
                <div class="number">{{ $totalOwners }}</div>
            </div>
            <div class="stat-card">
                <h3>Tổng sân</h3>
                <div class="number">{{ $totalFields }}</div>
            </div>
            <div class="stat-card">
                <h3>Booking tháng này</h3>
                <div class="number">{{ $monthlyBookings }}</div>
            </div>
            <div class="stat-card">
                <h3>Booking năm nay</h3>
                <div class="number">{{ $yearlyBookings }}</div>
            </div>
            <div class="stat-card">
                <h3>Doanh thu tháng</h3>
                <div class="number">{{ number_format($monthlyRevenue) }}đ</div>
            </div>
            <div class="stat-card">
                <h3>Doanh thu năm</h3>
                <div class="number">{{ number_format($yearlyRevenue) }}đ</div>
            </div>
        </div>

        <!-- Quick Links -->
        <div class="card">
            <h2 class="card-title">Quản lý</h2>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px;">
                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Quản lý Users</a>
                <a href="{{ route('admin.owners.index') }}" class="btn btn-secondary">Quản lý Owners</a>
                <a href="{{ route('admin.fields.index') }}" class="btn btn-secondary">Quản lý Sân</a>
                <a href="{{ route('admin.bookings.index') }}" class="btn btn-secondary">Quản lý Booking</a>
                <a href="{{ route('admin.posts.index') }}" class="btn btn-secondary">Quản lý Bài viết</a>
                <a href="{{ route('admin.settings.index') }}" class="btn btn-primary">Cài đặt Website</a>
            </div>
        </div>
        </div>

        <!-- Recent Bookings -->
        <div class="card">
            <h2 class="card-title">Booking gần đây</h2>
            @if($recentBookings->count() > 0)
            <table class="table">
                <thead>
                    <tr>
                        <th>Khách hàng</th>
                        <th>Sân</th>
                        <th>Ngày</th>
                        <th>Giờ</th>
                        <th>Tổng tiền</th>
                        <th>Trạng thái</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentBookings as $booking)
                    <tr>
                        <td>{{ $booking->user->name }}</td>
                        <td>{{ $booking->field->name }}</td>
                        <td>{{ $booking->date->format('d/m/Y') }}</td>
                        <td>{{ $booking->start_time }} - {{ $booking->end_time }}</td>
                        <td>{{ number_format($booking->total_price) }}đ</td>
                        <td>
                            @if($booking->status === 'pending')
                                <span class="badge badge-warning">Chờ duyệt</span>
                            @elseif($booking->status === 'approved')
                                <span class="badge badge-success">Đã duyệt</span>
                            @else
                                <span class="badge badge-danger">Đã hủy</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <p style="text-align: center; padding: 20px;">Chưa có booking nào.</p>
            @endif
        </div>
    </div>
</section>
@endsection
