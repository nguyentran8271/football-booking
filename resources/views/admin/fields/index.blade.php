@extends('layouts.app')

@section('title', 'Quản lý Sân')

@section('content')
<section class="section">
    <div class="container">
        <div style="margin-bottom:30px;">
            <h1 style="font-size:24px; margin-bottom:12px;">Quản Lý Tất Cả Sân</h1>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary" style="white-space:nowrap;">← Dashboard</a>
        </div>

        @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form method="GET" action="{{ route('admin.fields.index') }}" style="display:flex; gap:12px; margin-bottom:24px;">
            <input type="text" name="search" value="{{ request('search') }}"
                placeholder="Tìm theo tên sân hoặc chủ sân..."
                class="form-control" style="max-width:320px;">
            <button type="submit" class="btn btn-primary">Tìm kiếm</button>
            @if(request('search'))
                <a href="{{ route('admin.fields.index') }}" class="btn btn-secondary">Xóa bộ lọc</a>
            @endif
        </form>

        @if($fields->count() > 0)
        <div class="card">
            <div style="overflow-x: auto; -webkit-overflow-scrolling: touch;">
            <table class="table" style="min-width: 550px;">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tên sân</th>
                        <th>Chủ sân</th>
                        <th>Địa chỉ</th>
                        <th>Giá/giờ</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($fields as $field)
                    <tr>
                        <td>{{ $field->id }}</td>
                        <td><strong>{{ $field->name }}</strong></td>
                        <td>{{ $field->owner->name }}</td>
                        <td>{{ Str::limit($field->address, 50) }}</td>
                        <td>{{ number_format($field->price_per_hour) }}đ</td>
                        <td>
                            <div style="display:flex; gap:6px; align-items:center;">
                                <a href="{{ route('fields.show', $field->id) }}" class="btn btn-secondary" target="_blank" style="padding:5px 14px; font-size:13px; height:34px; line-height:24px; display:inline-block;">Xem</a>
                                <form action="{{ route('admin.fields.destroy', $field->id) }}" method="POST"
                                      onsubmit="return confirm('Bạn có chắc muốn xóa sân này?')" style="margin:0;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" style="padding:5px 14px; font-size:13px; height:34px; line-height:1;">Xóa</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            </div>
        </div>

        <div class="pagination">
            {{ $fields->links() }}
        </div>
        @else
        <div class="card">
            <p style="text-align: center; padding: 40px;">Chưa có sân nào.</p>
        </div>
        @endif
    </div>
</section>
@endsection
