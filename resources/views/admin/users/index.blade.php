@extends('layouts.app')

@section('title', 'Quản lý Users')

@section('content')
<section class="section">
    <div class="container">
        <h1 style="margin-bottom: 30px;">Quản Lý Users</h1>

        @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if($users->count() > 0)
        <div class="card">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tên</th>
                        <th>Email</th>
                        <th>Số điện thoại</th>
                        <th>Ngày đăng ký</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->phone ?? 'N/A' }}</td>
                        <td>{{ $user->created_at->format('d/m/Y') }}</td>
                        <td>
                            <div style="display:flex; gap:6px; align-items:center;">
                                <a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-primary" style="padding:5px 14px; font-size:13px; height:34px; line-height:24px;">Xem</a>
                                <form action="{{ route('admin.users.convert-to-owner', $user->id) }}" method="POST" style="margin:0;">
                                    @csrf
                                    <button type="submit" class="btn btn-secondary" style="padding:5px 14px; font-size:13px; height:34px; line-height:1;" onclick="return confirm('Chuyển user này thành owner?')">Chuyển Owner</button>
                                </form>
                                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" style="margin:0;" onsubmit="return confirm('Bạn có chắc muốn xóa user này?')">
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

        <div class="pagination">
            {{ $users->links() }}
        </div>
        @else
        <div class="card">
            <p style="text-align: center; padding: 40px;">Chưa có user nào.</p>
        </div>
        @endif
    </div>
</section>
@endsection
