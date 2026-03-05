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
                            <form action="{{ route('admin.users.convert-to-owner', $user->id) }}" method="POST" style="display: inline-block; margin-right: 5px;">
                                @csrf
                                <button type="submit" class="btn btn-success" onclick="return confirm('Chuyển user này thành owner?')">Chuyển Owner</button>
                            </form>
                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" style="display: inline-block;"
                                  onsubmit="return confirm('Bạn có chắc muốn xóa user này?')">
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
