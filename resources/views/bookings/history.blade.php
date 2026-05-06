@extends('layouts.app')

@section('title', 'Lịch sử đặt sân')

@push('styles')
<style>
.history-container {
    padding: 30px 0;
}

.bookings-table-wrapper {
    background: white;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    overflow: hidden;
}

.bookings-table {
    width: 100%;
    border-collapse: collapse;
}

.bookings-table thead {
    background: #f8f9fa;
}

.bookings-table th,
.bookings-table td {
    padding: 15px;
    text-align: left;
    border-bottom: 1px solid #eee;
}

.bookings-table th {
    font-weight: 600;
    color: #333;
}

.bookings-table tbody tr:hover {
    background: #f8f9fa;
}

.status-badge {
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 500;
    display: inline-block;
}

.status-pending {
    background: #fff3cd;
    color: #856404;
}

.status-approved {
    background: #d4edda;
    color: #155724;
}

.status-cancelled {
    background: #f8d7da;
    color: #721c24;
}

.shift-info {
    background: #e3f2fd;
    padding: 5px 10px;
    border-radius: 5px;
    display: inline-block;
    font-weight: 600;
    color: #1976d2;
}

.empty-state {
    text-align: center;
    padding: 80px 20px;
}
</style>
@endpush

@section('content')
<div class="history-container">
    <div class="container">
        <h1 style="margin-bottom: 30px; margin-top: 40px;">Lịch Sử Đặt Sân</h1>

        @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        @if($bookings->count() > 0)
        <div class="bookings-table-wrapper">
            <div style="overflow-x: auto; -webkit-overflow-scrolling: touch;">
            <table class="bookings-table" style="min-width: 600px;">
                <thead>
                    <tr>
                        <th>Sân</th>
                        <th>Ngày</th>
                        <th>Ca</th>
                        <th>Giờ</th>
                        <th>Tổng tiền</th>
                        <th>Trạng thái</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($bookings as $booking)
                    <tr>
                        <td>
                            <strong>{{ $booking->field->name }}</strong><br>
                            <small style="color: #666;">{{ Str::limit($booking->field->address, 40) }}</small>
                        </td>
                        <td>{{ $booking->date->format('d/m/Y') }}</td>
                        <td>
                            <span class="shift-info">Ca {{ $booking->shift }}</span>
                        </td>
                        <td>{{ $booking->start_time }} - {{ $booking->end_time }}</td>
                        <td><strong style="color: #28a745;">{{ number_format($booking->total_price) }}đ</strong></td>
                        <td>
                            @if($booking->status === 'pending')
                                <span class="status-badge status-pending">⏳ Chờ duyệt</span>
                            @elseif($booking->status === 'approved')
                                <span class="status-badge status-approved">✓ Đã duyệt</span>
                            @else
                                <span class="status-badge status-cancelled">✕ Đã hủy</span>
                            @endif
                        </td>
                        <td>
                            @if($booking->status === 'pending')
                            <form action="{{ route('bookings.cancel', $booking->id) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-sm btn-danger"
                                        onclick="return confirm('Hủy đặt sân này?')">
                                    Hủy
                                </button>
                            </form>
                            @else
                                <span style="color: #999;">-</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            </div>

            @if($bookings->hasPages())
            <div style="padding: 20px; display: flex; justify-content: center;">
                <div style="display: flex; gap: 10px; align-items: center;">
                    @if($bookings->onFirstPage())
                        <span style="padding: 8px 12px; color: #999;">« Trước</span>
                    @else
                        <a href="{{ $bookings->previousPageUrl() }}" style="padding: 8px 12px; background: white; border: 1px solid #ddd; border-radius: 5px; text-decoration: none; color: #333;">« Trước</a>
                    @endif

                    <span style="padding: 8px 12px;">
                        Trang {{ $bookings->currentPage() }} / {{ $bookings->lastPage() }}
                    </span>

                    @if($bookings->hasMorePages())
                        <a href="{{ $bookings->nextPageUrl() }}" style="padding: 8px 12px; background: white; border: 1px solid #ddd; border-radius: 5px; text-decoration: none; color: #333;">Sau »</a>
                    @else
                        <span style="padding: 8px 12px; color: #999;">Sau »</span>
                    @endif
                </div>
            </div>
            @endif
        </div>
        @else
        <div class="bookings-table-wrapper">
            <div class="empty-state">
                <h3>Chưa có lịch đặt sân nào</h3>
                <p style="margin-bottom: 30px;">Hãy bắt đầu đặt sân để trải nghiệm dịch vụ của chúng tôi</p>
                <a href="{{ route('fields.index') }}" class="btn btn-primary" style="display: inline-block;">
                    Xem danh sách sân
                </a>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
