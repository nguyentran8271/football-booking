@extends('layouts.app')

@section('title', 'Quản lý lịch đặt sân')

@push('styles')
<style>
.bookings-container {
    padding: 30px 0;
}

.filter-section {
    background: white;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    margin-bottom: 20px;
    display: flex;
    gap: 15px;
    flex-wrap: wrap;
    align-items: end;
}

.filter-group {
    flex: 1;
    min-width: 200px;
}

.filter-group label {
    display: block;
    margin-bottom: 5px;
    font-weight: 500;
    color: #333;
}

.filter-group select,
.filter-group input {
    width: 100%;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 5px;
}

.bookings-table-container {
    background: white;
    padding: 25px;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.bookings-table {
    width: 100%;
    border-collapse: collapse;
}

.bookings-table th,
.bookings-table td {
    padding: 15px;
    text-align: left;
    border-bottom: 1px solid #eee;
}

.bookings-table th {
    background: #f8f9fa;
    font-weight: 600;
    color: #333;
}

.bookings-table tr:hover {
    background: #f8f9fa;
}

.status-badge {
    padding: 6px 14px;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 500;
    display: inline-block;
}

.status-pending { background: #fff3cd; color: #856404; }
.status-confirmed { background: #d4edda; color: #155724; }
.status-cancelled { background: #f8d7da; color: #721c24; }

.bookings-table th,
.bookings-table td {
    white-space: nowrap;
}

.action-buttons {
    display: flex;
    gap: 8px;
}

.btn-sm {
    padding: 6px 12px;
    font-size: 13px;
}

.stats-summary {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 15px;
    margin-bottom: 20px;
}

.stat-box {
    background: white;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    text-align: center;
}

.stat-box h3 {
    font-size: 32px;
    margin: 0 0 5px 0;
    color: #28a745;
}

.stat-box p {
    margin: 0;
    color: #666;
    font-size: 14px;
}
</style>
@endpush

@section('content')
<div class="bookings-container">
    <div class="container">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
            <h1>Quản Lý Lịch Đặt Sân</h1>
            <a href="{{ route('owner.dashboard') }}" class="btn btn-secondary">← Quay lại Dashboard</a>
        </div>

        @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <!-- Bộ lọc -->
        <form method="GET" action="{{ route('owner.bookings.index') }}" class="filter-section">
            <div class="filter-group">
                <label>Trạng thái</label>
                <select name="status">
                    <option value="">Tất cả</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Chờ xác nhận</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Đã xác nhận</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                </select>
            </div>

            <div class="filter-group">
                <label>Ngày đặt</label>
                <input type="date" name="date" value="{{ request('date') }}">
            </div>

            <div class="filter-group">
                <button type="submit" class="btn btn-primary">Lọc</button>
                <a href="{{ route('owner.bookings.index') }}" class="btn btn-secondary">Reset</a>
            </div>
        </form>

        <!-- Bảng danh sách -->
        <div class="bookings-table-container">
            <h2 style="margin: 0 0 20px 0;">Danh sách đặt sân ({{ $bookings->total() }})</h2>
            <div style="overflow-x: auto;">
                <table class="bookings-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Khách hàng</th>
                            <th>Số điện thoại</th>
                            <th>Sân</th>
                            <th>Ngày đặt</th>
                            <th>Ca</th>
                            <th>Khung giờ</th>
                            <th>Tổng tiền</th>
                            <th>Trạng thái</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bookings as $booking)
                        <tr>
                            <td>#{{ $booking->id }}</td>
                            <td>{{ $booking->user->name }}</td>
                            <td>{{ $booking->user->phone ?? 'N/A' }}</td>
                            <td>{{ $booking->field->name }}</td>
                            <td>{{ \Carbon\Carbon::parse($booking->date)->format('d/m/Y') }}</td>
                            <td>
                                <span style="background: #e3f2fd; padding: 5px 10px; border-radius: 5px; font-weight: 600; color: #1976d2; white-space: nowrap;">
                                    Ca {{ $booking->shift }}
                                </span>
                            </td>
                            <td>{{ $booking->start_time }} - {{ $booking->end_time }}</td>
                            <td>{{ number_format($booking->total_price) }}đ</td>
                            <td>
                                @if($booking->status == 'pending')
                                    <span class="status-badge status-pending">Chờ xác nhận</span>
                                @elseif($booking->status == 'approved')
                                    <span class="status-badge status-confirmed">Đã xác nhận</span>
                                @else
                                    <span class="status-badge status-cancelled">Đã hủy</span>
                                @endif
                            </td>
                            <td>
                                <div class="action-buttons">
                                    @if($booking->status == 'pending')
                                        @if(\Carbon\Carbon::parse($booking->date)->isPast())
                                            <span style="color: #999; font-size: 13px;">Đã hết hạn</span>
                                        @else
                                            <form action="{{ route('owner.bookings.confirm', $booking->id) }}" method="POST" style="display: inline;">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-sm btn-primary" onclick="return confirm('Xác nhận đặt sân này?')">Xác nhận</button>
                                            </form>
                                            <form action="{{ route('owner.bookings.cancel', $booking->id) }}" method="POST" style="display: inline;">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Hủy đặt sân này?')">Hủy</button>
                                            </form>
                                        @endif
                                    @elseif($booking->status == 'approved')
                                        <button onclick="showCancelModal({{ $booking->id }})" class="btn btn-sm btn-danger">Hủy booking</button>
                                    @else
                                        <span style="color: #999;">-</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" style="text-align: center; padding: 40px;">
                                Chưa có lịch đặt nào
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div style="margin-top: 20px;">
                {{ $bookings->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<div id="cancel-modal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:9999;align-items:center;justify-content:center;">
    <div style="background:#fff;border-radius:12px;padding:24px;width:400px;max-width:90%;">
        <h3 style="margin:0 0 16px;">Lý do hủy booking</h3>
        <textarea id="cancel-reason-input" placeholder="Nhập lý do hủy (VD: khách yêu cầu hủy, trời mưa...)" style="width:100%;padding:10px;border:1px solid #ddd;border-radius:8px;min-height:80px;font-size:14px;box-sizing:border-box;"></textarea>
        <div style="display:flex;gap:10px;margin-top:16px;justify-content:flex-end;">
            <button onclick="closeCancelModal()" style="padding:8px 16px;border:1px solid #ddd;border-radius:6px;background:#fff;cursor:pointer;">Hủy bỏ</button>
            <button onclick="submitCancel()" style="padding:8px 16px;background:#dc3545;color:#fff;border:none;border-radius:6px;cursor:pointer;">Xác nhận hủy</button>
        </div>
    </div>
</div>
<script>
var cancelBookingId = null;
function showCancelModal(id) {
    cancelBookingId = id;
    document.getElementById('cancel-reason-input').value = '';
    var m = document.getElementById('cancel-modal');
    m.style.display = 'flex';
}
function closeCancelModal() {
    document.getElementById('cancel-modal').style.display = 'none';
    cancelBookingId = null;
}
function submitCancel() {
    var reason = document.getElementById('cancel-reason-input').value.trim();
    var form = document.createElement('form');
    form.method = 'POST';
    form.action = '/owner/bookings/' + cancelBookingId + '/cancel';
    form.innerHTML = '<input type="hidden" name="_token" value="{{ csrf_token() }}">'
        + '<input type="hidden" name="_method" value="PATCH">'
        + '<input type="hidden" name="cancel_reason" value="' + reason.replace(/"/g, '&quot;') + '">';
    document.body.appendChild(form);
    form.submit();
}
</script>
@endpush
