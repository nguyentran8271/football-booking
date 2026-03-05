@extends('layouts.app')

@section('title', 'Quản lý Sân')

@section('content')
<section class="section">
    <div class="container">
        <h1 style="margin-bottom: 30px;">Quản Lý Tất Cả Sân</h1>

        @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if($fields->count() > 0)
        <div class="card">
            <table class="table">
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
                            <div style="display: flex; gap: 5px;">
                                <a href="{{ route('fields.show', $field->id) }}" class="btn btn-secondary" target="_blank">Xem</a>
                                <form action="{{ route('admin.fields.destroy', $field->id) }}" method="POST"
                                      onsubmit="return confirm('Bạn có chắc muốn xóa sân này?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">Xóa</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
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
