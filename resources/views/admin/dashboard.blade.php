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
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px;">
                <h2 class="card-title" style="margin:0;">Quản lý</h2>
                {{-- Chuông thông báo admin --}}
                @php $adminTotalUnread = $adminUnreadReviews + $adminUnreadOwnerRequests; @endphp
                <div style="position:relative;">
                    <button id="admin-bell-btn" onclick="toggleAdminBell()" style="background:none;border:none;cursor:pointer;padding:8px;position:relative;font-size:22px;">
                        🔔
                        @if($adminTotalUnread > 0)
                        <span style="position:absolute;top:0;right:0;background:#dc3545;color:#fff;border-radius:50%;width:18px;height:18px;font-size:11px;font-weight:700;display:flex;align-items:center;justify-content:center;line-height:1;">{{ $adminTotalUnread > 9 ? '9+' : $adminTotalUnread }}</span>
                        @endif
                    </button>
                    <div id="admin-bell-dropdown" style="display:none;position:absolute;right:0;top:calc(100% + 4px);width:340px;background:#fff;border-radius:10px;box-shadow:0 4px 20px rgba(0,0,0,0.15);z-index:1000;overflow:hidden;max-height:400px;overflow-y:auto;">
                        <div style="padding:12px 16px;border-bottom:1px solid #eee;display:flex;justify-content:space-between;align-items:center;">
                            <strong style="font-size:14px;">Thông báo</strong>
                            @if($adminTotalUnread > 0)
                            <button onclick="markAdminRead()" style="background:none;border:none;color:#28a745;font-size:12px;cursor:pointer;">Đánh dấu đã đọc</button>
                            @endif
                        </div>
                        @forelse($adminNotifications['owner_requests'] as $u)
                        <div style="padding:10px 16px;border-bottom:1px solid #f0f0f0;background:#fff8f0;">
                            <div style="font-size:13px;font-weight:600;">👤 Đăng ký chủ sân</div>
                            <div style="font-size:12px;color:#666;">{{ $u->name }} ({{ $u->email }})</div>
                            <div style="font-size:11px;color:#999;">{{ $u->updated_at->diffForHumans() }}</div>
                        </div>
                        @empty
                        @endforelse
                        @forelse($adminNotifications['reviews'] as $r)
                        <div style="padding:10px 16px;border-bottom:1px solid #f0f0f0;background:#f0fff4;">
                            <div style="font-size:13px;font-weight:600;">⭐ Đánh giá website mới</div>
                            <div style="font-size:12px;color:#666;">{{ $r->user->name }} - {{ $r->rating }}/5 sao</div>
                            <div style="font-size:11px;color:#999;">{{ $r->created_at->diffForHumans() }}</div>
                        </div>
                        @empty
                        @endforelse
                        @if($adminTotalUnread === 0)
                        <div style="padding:20px;text-align:center;color:#999;font-size:13px;">Không có thông báo mới</div>
                        @endif
                    </div>
                </div>
            </div>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px;">
                <a href="{{ route('admin.users.index') }}" class="btn {{ request()->routeIs('admin.users.*') ? 'btn-primary' : 'btn-secondary' }}">Quản lý Users</a>
                <a href="{{ route('admin.owners.index') }}" class="btn {{ request()->routeIs('admin.owners.*') ? 'btn-primary' : 'btn-secondary' }}">Quản lý Owners</a>
                <a href="{{ route('admin.fields.index') }}" class="btn {{ request()->routeIs('admin.fields.*') ? 'btn-primary' : 'btn-secondary' }}">Quản lý Sân</a>
                <a href="{{ route('admin.bookings.index') }}" class="btn {{ request()->routeIs('admin.bookings.*') ? 'btn-primary' : 'btn-secondary' }}">Quản lý Booking</a>
                <a href="{{ route('admin.settings.index') }}" class="btn {{ request()->routeIs('admin.settings.*') ? 'btn-primary' : 'btn-secondary' }}">Cài đặt Website</a>
            </div>
        </div>
        </div>

        <!-- Hot Fields -->
        <div class="card">
            <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px; margin-bottom: 15px;">
                <h2 class="card-title" style="margin: 0;">Sân bóng hot nhất (theo lượt đặt)</h2>
                <form method="GET" action="{{ route('admin.dashboard') }}" style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
                    <select name="hot_filter" onchange="this.form.submit()" style="padding: 7px 12px; border: 1px solid #ddd; border-radius: 6px;">
                        <option value="month" {{ $hotFilterType === 'month' ? 'selected' : '' }}>Theo tháng</option>
                        <option value="year"  {{ $hotFilterType === 'year'  ? 'selected' : '' }}>Theo năm</option>
                    </select>
                    @if($hotFilterType === 'month')
                    <select name="hot_month" onchange="this.form.submit()" style="padding: 7px 12px; border: 1px solid #ddd; border-radius: 6px;">
                        @for($m = 1; $m <= 12; $m++)
                        <option value="{{ $m }}" {{ $hotMonth == $m ? 'selected' : '' }}>Tháng {{ $m }}</option>
                        @endfor
                    </select>
                    @endif
                    <select name="hot_year" onchange="this.form.submit()" style="padding: 7px 12px; border: 1px solid #ddd; border-radius: 6px;">
                        @for($y = now()->year; $y >= now()->year - 3; $y--)
                        <option value="{{ $y }}" {{ $hotYear == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>
                </form>
            </div>

            @if($hotFields->isEmpty())
                <p style="text-align: center; padding: 30px; color: #999;">Không có dữ liệu</p>
            @else
                <div style="position: relative; height: 300px; margin-bottom: 25px;">
                    <canvas id="hotFieldsChart"></canvas>
                </div>
                <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Tên sân</th>
                            <th>Chủ sân</th>
                            <th>Lượt đặt</th>
                            <th>Doanh thu</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($hotFields as $i => $item)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ $item->field->name ?? 'N/A' }}</td>
                            <td>{{ $item->field->owner->name ?? 'N/A' }}</td>
                            <td><span style="background:#fff3cd;color:#856404;padding:4px 12px;border-radius:20px;font-weight:600;">{{ $item->total_bookings }} lượt</span></td>
                            <td>{{ number_format($item->total_revenue) }}đ</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
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

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
@if($hotFields->isNotEmpty())
const hotData = JSON.parse('{!! addslashes(json_encode($hotFields->map(fn($i) => ["name" => $i->field->name ?? "N/A", "bookings" => $i->total_bookings, "revenue" => (float)$i->total_revenue]))) !!}');

new Chart(document.getElementById('hotFieldsChart').getContext('2d'), {
    data: {
        labels: hotData.map(i => i.name),
        datasets: [
            {
                type: 'bar',
                label: 'Lượt đặt',
                data: hotData.map(i => i.bookings),
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
                data: hotData.map(i => i.revenue),
                borderColor: '#28a745',
                backgroundColor: 'rgba(40,167,69,0.08)',
                tension: 0.4,
                fill: true,
                pointRadius: 5,
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
                    label: ctx => ctx.dataset.yAxisID === 'yRevenue'
                        ? ' Doanh thu: ' + ctx.parsed.y.toLocaleString() + 'đ'
                        : ' Lượt đặt: ' + ctx.parsed.y
                }
            }
        },
        scales: {
            yRevenue: {
                type: 'linear', position: 'left', beginAtZero: true,
                ticks: { callback: v => v.toLocaleString() + 'đ', color: '#28a745' },
                grid: { color: 'rgba(0,0,0,0.05)' }
            },
            yCount: {
                type: 'linear', position: 'right', beginAtZero: true,
                ticks: { stepSize: 1, color: '#1976d2' },
                grid: { drawOnChartArea: false }
            }
        }
    }
});
@endif
</script>
<script>
function toggleAdminBell() {
    const d = document.getElementById('admin-bell-dropdown');
    d.style.display = d.style.display === 'none' ? 'block' : 'none';
}
function markAdminRead() {
    fetch('{{ route("admin.reviews.mark-read") }}', {method:'POST',headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}'}});
    setTimeout(() => location.reload(), 300);
}
document.addEventListener('click', function(e) {
    const bell = document.getElementById('admin-bell-btn');
    const dropdown = document.getElementById('admin-bell-dropdown');
    if (bell && dropdown && !bell.contains(e.target) && !dropdown.contains(e.target)) {
        dropdown.style.display = 'none';
    }
});
</script>
@endpush
