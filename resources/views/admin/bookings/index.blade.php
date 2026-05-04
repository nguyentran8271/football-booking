@extends('layouts.app')

@section('title', 'Quản lý Booking')

@section('content')
<section class="section">
    <div class="container">
        <div style="margin-bottom:30px;">
            <h1 style="font-size:24px; margin-bottom:12px;">Quản Lý Tất Cả Booking</h1>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary" style="white-space:nowrap;">← Dashboard</a>
        </div>

        @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        {{-- Search Form --}}
        <form method="GET" action="{{ route('admin.bookings.index') }}" style="display: flex; gap: 12px; margin-bottom: 24px; flex-wrap: wrap;">
            <input type="text" name="field_name" value="{{ request('field_name') }}"
                placeholder="Tìm theo tên sân..."
                class="form-control" style="max-width: 260px;">
            <input type="date" name="date" value="{{ request('date') }}"
                class="form-control" style="max-width: 200px;">
            <button type="submit" class="btn btn-primary">Tìm kiếm</button>
            @if(request('field_name') || request('date'))
                <a href="{{ route('admin.bookings.index') }}" class="btn btn-secondary">Xóa bộ lọc</a>
            @endif
        </form>

        @if($bookings->count() > 0)
        <div class="card">
            <div style="overflow-x: auto; -webkit-overflow-scrolling: touch;">
            <table class="table" style="min-width: 600px;">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Khách hàng</th>
                        <th>Sân</th>
                        <th>Ngày</th>
                        <th>Ca</th>
                        <th>Tổng tiền</th>
                        <th>Trạng thái</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($bookings as $booking)
                    <tr>
                        <td>{{ $booking->id }}</td>
                        <td>{{ $booking->user->name }}</td>
                        <td>{{ $booking->field->name }}</td>
                        <td style="white-space:nowrap;">{{ $booking->date->format('d/m/Y') }}</td>
                        <td>{{ $booking->shift_label }}</td>
                        <td style="white-space:nowrap;">{{ number_format($booking->total_price) }}đ</td>
                        <td>
                            @if($booking->status === 'pending')
                                <span class="badge badge-warning">Chờ duyệt</span>
                            @elseif($booking->status === 'approved')
                                <span class="badge badge-success">Đã duyệt</span>
                            @else
                                <span class="badge badge-danger">Đã hủy</span>
                            @endif
                        </td>
                        <td>
                            <form action="{{ route('admin.bookings.destroy', $booking->id) }}" method="POST"
                                  onsubmit="return confirm('Bạn có chắc muốn xóa booking này?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Xóa</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            </div>
        </div>

        <div class="pagination">
            {{ $bookings->links() }}
        </div>
        @else
        <div class="card">
            <p style="text-align: center; padding: 40px;">Chưa có booking nào.</p>
        </div>
        @endif
    </div>
</section>
@endsection
