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
    overflow: hidden;
    padding: 0;
}

.stat-icon img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
}

.stat-icon.blue { background: white; }
.stat-icon.green { background: white; }
.stat-icon.orange { background: white; }
.stat-icon.purple { background: white; }

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
        <h1 style="margin-bottom: 30px;">Dashboard Quản Lý</h1>

        @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <!-- Thống kê -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon blue"><img src="{{ asset('images/lich.png') }}" alt="Lịch"></div>
                <div class="stat-info">
                    <h3>{{ $stats['bookings_today'] }}</h3>
                    <p>Lượt đặt hôm nay</p>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon green"><img src="{{ asset('images/doanhthu.png') }}" alt="Doanh thu"></div>
                <div class="stat-info">
                    <h3>{{ number_format($stats['revenue_today']) }}đ</h3>
                    <p>Doanh thu hôm nay</p>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon orange"><img src="{{ asset('images/tongluotdatthangnay.png') }}" alt="Tổng lượt đặt"></div>
                <div class="stat-info">
                    <h3>{{ $stats['bookings_month'] }}</h3>
                    <p>Tổng lượt đặt tháng này</p>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon purple"><img src="{{ asset('images/iconsanbong.png') }}" alt="Sân bóng"></div>
                <div class="stat-info">
                    <h3>{{ $stats['active_fields'] }}</h3>
                    <p>Sân đang hoạt động</p>
                </div>
            </div>
        </div>

        <!-- Thao tác nhanh -->
        <div class="quick-actions" style="display:flex; align-items:center; gap:10px; flex-wrap:wrap;">
            <a href="{{ route('owner.fields.create') }}" class="btn {{ request()->routeIs('owner.fields.create') ? 'btn-primary' : 'btn-secondary' }}">Thêm sân mới</a>
            <a href="{{ route('owner.bookings.index') }}" class="btn {{ request()->routeIs('owner.bookings.*') ? 'btn-primary' : 'btn-secondary' }}">Quản lý lịch đặt</a>
            <a href="{{ route('owner.fields.index') }}" class="btn {{ request()->routeIs('owner.fields.index') ? 'btn-primary' : 'btn-secondary' }}">Quản lý sân</a>
            <a href="{{ route('owner.tournaments.index') }}" class="btn {{ request()->routeIs('owner.tournaments.*') ? 'btn-primary' : 'btn-secondary' }}">Quản lý giải đấu</a>
        </div>

        <!-- Thông báo booking -->
        @if($notifications->count() > 0)
        <div class="dashboard-section">
            <h2>🔔 Thông báo mới ({{ $notifications->count() }})</h2>
            @foreach($notifications as $booking)
            <div class="notification-item" id="notif-{{ $booking->id }}">
                <strong>Đặt sân mới từ {{ $booking->user->name }}</strong>
                <p>Sân: {{ $booking->field->name }} - {{ $booking->date->format('d/m/Y') }} - Ca {{ $booking->shift }} ({{ $booking->start_time }} - {{ $booking->end_time }})</p>
                <div style="display:flex;gap:8px;flex-wrap:wrap;">
                    <a href="{{ route('owner.bookings.index') }}" class="btn btn-sm btn-primary">Xem chi tiết</a>
                    @if($booking->status === 'pending')
                    <button onclick="confirmBooking({{ $booking->id }}, this)" class="btn btn-sm btn-success" style="font-size:12px;background:#28a745;color:#fff;border:none;padding:6px 12px;border-radius:5px;cursor:pointer;">Duyệt</button>
                    <button onclick="cancelBooking({{ $booking->id }}, this)" class="btn btn-sm btn-danger" style="font-size:12px;background:#dc3545;color:#fff;border:none;padding:6px 12px;border-radius:5px;cursor:pointer;">Từ chối</button>
                    @endif
                    @if($booking->date->isPast())
                    <button onclick="expireBooking({{ $booking->id }})" class="btn btn-sm btn-secondary" style="font-size:12px;">Xác nhận hết hạn</button>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
        @endif

        <!-- Biểu đồ doanh thu -->
        <div class="dashboard-section">
            <h2>📊 Doanh thu theo khoảng thời gian</h2>

            <form method="GET" action="{{ route('owner.dashboard') }}" style="display: flex; gap: 15px; align-items: flex-end; flex-wrap: wrap; margin-bottom: 20px;">
                <div>
                    <label style="display: block; margin-bottom: 5px; font-weight: 500; color: #333;">Từ ngày</label>
                    <input type="date" name="date_from" value="{{ request('date_from', $dateFrom->format('Y-m-d')) }}" style="padding: 8px 12px; border: 1px solid #ddd; border-radius: 6px;">
                </div>
                <div>
                    <label style="display: block; margin-bottom: 5px; font-weight: 500; color: #333;">Đến ngày</label>
                    <input type="date" name="date_to" value="{{ request('date_to', $dateTo->format('Y-m-d')) }}" style="padding: 8px 12px; border: 1px solid #ddd; border-radius: 6px;">
                </div>
                <button type="submit" class="btn btn-primary" style="height: 38px; padding: 0 20px;">Lọc</button>
                <a href="{{ route('owner.dashboard') }}" class="btn btn-secondary" style="height: 38px; padding: 0 20px; display: inline-flex; align-items: center;">Reset</a>
            </form>

            <div style="display: flex; gap: 20px; margin-bottom: 20px; flex-wrap: wrap;">
                <div style="background: #e8f5e9; padding: 15px 25px; border-radius: 8px; border-left: 4px solid #28a745;">
                    <div style="font-size: 13px; color: #555;">Doanh thu khoảng này</div>
                    <div style="font-size: 22px; font-weight: 700; color: #28a745;">{{ number_format($stats['revenue_range']) }}đ</div>
                </div>
                <div style="background: #e3f2fd; padding: 15px 25px; border-radius: 8px; border-left: 4px solid #1976d2;">
                    <div style="font-size: 13px; color: #555;">Lượt đặt khoảng này</div>
                    <div style="font-size: 22px; font-weight: 700; color: #1976d2;">{{ $stats['bookings_range'] }}</div>
                </div>
            </div>

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
const ctx = document.getElementById('revenueChart').getContext('2d');
const revenueData = JSON.parse('{!! addslashes(json_encode($revenueChart)) !!}');

const labels = revenueData.map(item => {
    const d = new Date(item.date);
    return (d.getDate()).toString().padStart(2,'0') + '/' + (d.getMonth()+1).toString().padStart(2,'0');
});
const revenueValues = revenueData.map(item => item.total);
const countValues = revenueData.map(item => item.count);

new Chart(ctx, {
    data: {
        labels: labels,
        datasets: [
            {
                type: 'bar',
                label: 'Lượt đặt',
                data: countValues,
                backgroundColor: 'rgba(25, 118, 210, 0.25)',
                borderColor: '#1976d2',
                borderWidth: 1.5,
                borderRadius: 4,
                barThickness: 40,
                yAxisID: 'yCount',
                order: 2,
            },
            {
                type: 'line',
                label: 'Doanh thu (VNĐ)',
                data: revenueValues,
                borderColor: '#28a745',
                backgroundColor: 'rgba(40, 167, 69, 0.08)',
                tension: 0.4,
                fill: true,
                pointRadius: 4,
                pointBackgroundColor: '#28a745',
                yAxisID: 'yRevenue',
                order: 1,
            }
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        interaction: { mode: 'index', intersect: false },
        plugins: {
            legend: { display: true, position: 'top' },
            tooltip: {
                callbacks: {
                    label: function(ctx) {
                        if (ctx.dataset.yAxisID === 'yRevenue') {
                            return ' Doanh thu: ' + ctx.parsed.y.toLocaleString() + 'đ';
                        }
                        return ' Lượt đặt: ' + ctx.parsed.y;
                    }
                }
            }
        },
        scales: {
            yRevenue: {
                type: 'linear',
                position: 'left',
                beginAtZero: true,
                ticks: {
                    callback: v => v.toLocaleString() + 'đ',
                    color: '#28a745'
                },
                grid: { color: 'rgba(0,0,0,0.05)' }
            },
            yCount: {
                type: 'linear',
                position: 'right',
                beginAtZero: true,
                ticks: {
                    stepSize: 1,
                    color: '#1976d2'
                },
                grid: { drawOnChartArea: false }
            }
        }
    }
});
</script>
<script>
function confirmBooking(id, btn) {
    if(!confirm('Xác nhận duyệt booking này?')) return;
    fetch('/owner/bookings/' + id + '/confirm', {method:'POST', headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}', 'Accept':'application/json'}})
        .then(r => r.json()).then(d => {
            if(d.success) {
                var el = document.getElementById('notif-' + id);
                if(el) el.style.background = '#d4edda';
                btn.parentElement.querySelectorAll('button').forEach(b => b.disabled = true);
                btn.textContent = 'Đã duyệt';
            } else {
                alert(d.message || 'Không thể duyệt booking này.');
            }
        });
}
function cancelBooking(id, btn) {
    if(!confirm('Từ chối booking này?')) return;
    fetch('/owner/bookings/' + id + '/cancel', {method:'POST', headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}', 'Accept':'application/json'}})
        .then(r => r.json()).then(d => {
            if(d.success) {
                var el = document.getElementById('notif-' + id);
                if(el) el.remove();
            }
        });
}
function expireBooking(id) {
    if(!confirm('Xác nhận booking này đã hết hạn?')) return;
    fetch('/owner/bookings/' + id + '/expire', {method:'POST',headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}'}})
        .then(r => r.json())
        .then(d => {
            if(d.success) {
                var el = document.getElementById('notif-' + id);
                if(el) el.remove();
            }
        });
}
</script>
@endpush
