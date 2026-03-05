@extends('layouts.app')

@section('title', 'Quản lý Owners')

@section('content')
<section class="section">
    <div class="container">
        <h1 style="margin-bottom: 30px;">Quản Lý Chủ Sân</h1>

        @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if($owners->count() > 0)
        <div class="card">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tên</th>
                        <th>Email</th>
                        <th>Số điện thoại</th>
                        <th>Số sân</th>
                        <th>Ngày đăng ký</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($owners as $owner)
                    <tr>
                        <td>{{ $owner->id }}</td>
                        <td>{{ $owner->name }}</td>
                        <td>{{ $owner->email }}</td>
                        <td>{{ $owner->phone ?? 'N/A' }}</td>
                        <td>{{ $owner->fields_count }}</td>
                        <td>{{ $owner->created_at->format('d/m/Y') }}</td>
                        <td>
                            <form action="{{ route('admin.users.destroy', $owner->id) }}" method="POST"
                                  onsubmit="return confirm('Bạn có chắc muốn xóa owner này?')">
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

        <div class="pagination">
            {{ $owners->links() }}
        </div>
        @else
        <div class="card">
            <p style="text-align: center; padding: 40px;">Chưa có owner nào.</p>
        </div>
        @endif
    </div>
</section>
@endsection
