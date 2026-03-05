@extends('layouts.app')

@section('title', 'Dashboard - Quản lý sân')

@push('styles')
<style>
.dashboard-container {
    padding: 30px 0;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.stat-card {
    background: white;
    padding: 25px;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    display: flex;
    align-items: center;
    gap: 20px;
}

.stat-icon {
    font-size: 40px;
    width: 70px;
    height: 70px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 10px;
}

.stat-icon.blue { background: #e3f2fd; }
.stat-icon.green { background: #e8f5e9; }
.stat-icon.orange { background: #fff3e0; }
.stat-icon.purple { background: #f3e5f5; }

.stat-info h3 {
    font-size: 28px;
    margin: 0 0 5px 0;
    color: #333;
}

.stat-info p {
    margin: 0;
    color: #666;
    font-size: 14px;
}

.dashboard-section {
    background: white;
    padding: 25px;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    margin-bottom: 30px;
}

.dashboard-section h2 {
    margin: 0 0 20px 0;
    font-size: 20px;
    color: #333;
}

.booking-table {
    width: 100%;
    border-collapse: collapse;
}

.booking-table th,
.booking-table td {
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid #eee;
}

.booking-table th {
    background: #f5f5f5;
    font-weight: 600;
    color: #333;
}

.status-badge {
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 500;
}

.status-pending { background: #fff3cd; color: #856404; }
.status-confirmed { background: #d4edda; color: #155724; }
.status-cancelled { background: #f8d7da; color: #721c24; }

.notification-item {
    padding: 20px 25px;
    border-left: 4px solid #ffc107;
    background: #fff8e1;
    margin-bottom: 20px;
    border-radius: 8px;
}

.notification-item strong {
    display: block;
    margin-bottom: 12px;
    font-size: 16px;
}

.notification-item p {
    margin-bottom: 15px;
    line-height: 1.6;
}

.chart-container {
    position: relative;
    height: 300px;
}

.quick-actions {
    display: flex;
    gap: 15px;
    flex-wrap: wrap;
    margin-bottom: 30px;
}
</style>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endpush

@section('content')
<div class="dashboard-container">
    <div class="container">
        <h1 style="margin-bottom: 30px;">Dashboard Quản Lý Sân</h1>

        @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <!-- Thống kê -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon blue">📅</div>
                <div class="stat-info">
                    <h3>{{ $stats['bookings_today'] }}</h3>
                    <p>Lượt đặt hôm nay</p>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon green">💰</div>
                <div class="stat-info">
                    <h3>{{ number_format($stats['revenue_today']) }}đ</h3>
                    <p>Doanh thu hôm nay</p>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon orange">📆</div>
                <div class="stat-info">
                    <h3>{{ $stats['bookings_month'] }}</h3>
                    <p>Tổng lượt đặt tháng này</p>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon purple">🏟</div>
                <div class="stat-info">
                    <h3>{{ $stats['active_fields'] }}</h3>
                    <p>Sân đang hoạt động</p>
                </div>
            </div>
        </div>

        <!-- Thao tác nhanh -->
        <div class="quick-actions">
            <a href="{{ route('owner.fields.create') }}" class="btn btn-primary">Thêm sân mới</a>
            <a href="{{ route('owner.bookings.index') }}" class="btn btn-secondary">Quản lý lịch đặt</a>
            <a href="{{ route('owner.fields.index') }}" class="btn btn-secondary">Quản lý sân</a>
            <a href="{{ route('owner.tournaments.index') }}" class="btn btn-secondary" style="background: #28a745; color: white;">Quản lý giải đấu</a>
        </div>

        <!-- Thông báo -->
        @if($notifications->count() > 0)
        <div class="dashboard-section">
            <h2>🔔 Thông báo mới ({{ $notifications->count() }})</h2>
            @foreach($notifications as $booking)
            <div class="notification-item">
                <strong>Đặt sân mới từ {{ $booking->user->name }}</strong>
                <p>Sân: {{ $booking->field->name }} - {{ $booking->date }} - Ca {{ $booking->shift }} ({{ $booking->start_time }} - {{ $booking->end_time }})</p>
                <a href="{{ route('owner.bookings.index') }}" class="btn btn-sm btn-primary">Xem chi tiết</a>
            </div>
            @endforeach
        </div>
        @endif

        <!-- Biểu đồ doanh thu -->
        <div class="dashboard-section">
            <h2>📊 Doanh thu 7 ngày gần đây</h2>
            <div class="chart-container">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>

        <!-- Booking gần đây -->
        <div class="dashboard-section">
            <h2>📅 Lịch đặt gần đây</h2>
            <div style="overflow-x: auto;">
                <table class="booking-table">
                    <thead>
                        <tr>
                            <th>Khách hàng</th>
                            <th>SĐT</th>
                            <th>Sân</th>
                            <th>Ngày</th>
                            <th>Ca</th>
                            <th>Giờ</th>
                            <th>Trạng thái</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentBookings as $booking)
                        <tr>
                            <td>{{ $booking->user->name }}</td>
                            <td>{{ $booking->user->phone ?? 'N/A' }}</td>
                            <td>{{ $booking->field->name }}</td>
                            <td>{{ $booking->date }}</td>
                            <td>
                                <span style="background: #e3f2fd; padding: 5px 10px; border-radius: 5px; font-weight: 600; color: #1976d2;">
                                    Ca {{ $booking->shift }}
                                </span>
                            </td>
                            <td>{{ $booking->start_time }} - {{ $booking->end_time }}</td>
                            <td>
                                @if($booking->status == 'pending')
                                    <span class="status-badge status-pending">Chờ xác nhận</span>
                                @elseif($booking->status == 'approved')
                                    <span class="status-badge status-confirmed">Đã xác nhận</span>
                                @else
                                    <span class="status-badge status-cancelled">Đã hủy</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 30px;">Chưa có lịch đặt nào</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Biểu đồ doanh thu
const ctx = document.getElementById('revenueChart').getContext('2d');
const revenueData = JSON.parse('{!! addslashes(json_encode($revenueChart)) !!}');

const labels = revenueData.map(item => item.date);
const data = revenueData.map(item => item.total);

new Chart(ctx, {
    type: 'line',
    data: {
        labels: labels,
        datasets: [{
            label: 'Doanh thu (VNĐ)',
            data: data,
            borderColor: '#28a745',
            backgroundColor: 'rgba(40, 167, 69, 0.1)',
            tension: 0.4,
            fill: true
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: true,
                position: 'top'
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return value.toLocaleString() + 'đ';
                    }
                }
            }
        }
    }
});
</script>
@endpush
