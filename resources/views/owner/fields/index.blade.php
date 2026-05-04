@extends('layouts.app')

@section('title', 'Quản lý sân')

@push('styles')
<style>
.fields-container {
    padding: 30px 0;
}

.header-section {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
}

.fields-table-wrapper {
    background: white;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    overflow: hidden;
}

.fields-table {
    width: 100%;
    border-collapse: collapse;
}

.fields-table thead {
    background: #f8f9fa;
}

.fields-table th,
.fields-table td {
    padding: 15px;
    text-align: left;
    border-bottom: 1px solid #eee;
}

.fields-table th {
    font-weight: 600;
    color: #333;
}

.fields-table tbody tr:hover {
    background: #f8f9fa;
}

.field-image-thumb {
    width: 80px;
    height: 60px;
    object-fit: cover;
    border-radius: 5px;
}

.status-badge {
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 500;
    display: inline-block;
}

.status-active {
    background: #d4edda;
    color: #155724;
}

.status-inactive {
    background: #f8d7da;
    color: #721c24;
}

.action-buttons {
    display: flex;
    gap: 8px;
}

.btn-sm {
    padding: 6px 12px;
    font-size: 13px;
}

.empty-state {
    text-align: center;
    padding: 60px 20px;
}

.empty-state h3 {
    margin-bottom: 10px;
    color: #666;
}

.pagination {
    padding: 20px;
    display: flex;
    justify-content: center;
}

.pagination nav {
    display: flex;
    gap: 5px;
}

.pagination .page-link {
    padding: 8px 12px;
    border: 1px solid #ddd;
    border-radius: 5px;
    color: #333;
    text-decoration: none;
    background: white;
}

.pagination .page-link:hover {
    background: #f8f9fa;
}

.pagination .active .page-link {
    background: #28a745;
    color: white;
    border-color: #28a745;
}

.pagination .disabled .page-link {
    color: #999;
    cursor: not-allowed;
}
</style>
@endpush

@section('content')
<div class="fields-container">
    <div class="container">
        <div style="margin-bottom: 30px;">
            <h1 style="font-size:24px; margin-bottom:12px;">Quản Lý Sân</h1>
            <div style="display:flex; gap:10px;">
                <a href="{{ route('owner.dashboard') }}" class="btn btn-secondary" style="white-space:nowrap;">← Dashboard</a>
                <a href="{{ route('owner.fields.create') }}" class="btn btn-primary" style="white-space:nowrap;">+ Thêm sân mới</a>
            </div>
        </div>

        @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if($fields->count() > 0)
        <div class="fields-table-wrapper">
            <div style="overflow-x: auto; -webkit-overflow-scrolling: touch;">
            <table class="fields-table" style="min-width: 600px;">
                <thead>
                    <tr>
                        <th>Hình ảnh</th>
                        <th>Tên sân</th>
                        <th>Địa chỉ</th>
                        <th>Giá/giờ</th>
                        <th>Trạng thái</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($fields as $field)
                    <tr>
                        <td>
                            <img src="{{ $field->image_url }}"
                                 alt="{{ $field->name }}"
                                 class="field-image-thumb">
                        </td>
                        <td><strong>{{ $field->name }}</strong></td>
                        <td>{{ Str::limit($field->address, 50) }}</td>
                        <td><strong>{{ number_format($field->price_per_hour) }}đ</strong></td>
                        <td>
                            @if($field->status == 'active')
                                <span class="status-badge status-active">Hoạt động</span>
                            @else
                                <span class="status-badge status-inactive">Tạm ngưng</span>
                            @endif
                        </td>
                        <td>
                            <div class="action-buttons">
                                <a href="{{ route('owner.fields.edit', $field->id) }}" class="btn btn-sm btn-secondary" style="height: 34px; display: inline-flex; align-items: center;">
                                    Sửa
                                </a>
                                <form action="{{ route('owner.fields.destroy', $field->id) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" style="height: 34px;"
                                            onclick="return confirm('Xóa sân {{ $field->name }}?')">
                                        Xóa
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            </div>

            @if($fields->hasPages())
            <div class="pagination">
                <div style="display: flex; gap: 10px; align-items: center;">
                    @if($fields->onFirstPage())
                        <span style="padding: 8px 12px; color: #999;">« Trước</span>
                    @else
                        <a href="{{ $fields->previousPageUrl() }}" style="padding: 8px 12px; background: white; border: 1px solid #ddd; border-radius: 5px; text-decoration: none; color: #333;">« Trước</a>
                    @endif

                    <span style="padding: 8px 12px;">
                        Trang {{ $fields->currentPage() }} / {{ $fields->lastPage() }}
                    </span>

                    @if($fields->hasMorePages())
                        <a href="{{ $fields->nextPageUrl() }}" style="padding: 8px 12px; background: white; border: 1px solid #ddd; border-radius: 5px; text-decoration: none; color: #333;">Sau »</a>
                    @else
                        <span style="padding: 8px 12px; color: #999;">Sau »</span>
                    @endif
                </div>
            </div>
            @endif
        </div>
        @else
        <div class="fields-table-wrapper">
            <div class="empty-state">
                <h3>Chưa có sân nào</h3>
                <p>Bắt đầu bằng cách thêm sân đầu tiên của bạn</p>
                <a href="{{ route('owner.fields.create') }}" class="btn btn-primary" style="margin-top: 20px;">
                    Thêm sân đầu tiên
                </a>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
