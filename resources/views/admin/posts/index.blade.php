@extends('layouts.app')

@section('title', 'Quản lý bài viết')

@section('content')
<section class="section">
    <div class="container">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
            <h1>Quản Lý Bài Viết</h1>
            <div style="display:flex; gap:10px;">
                <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">← Dashboard</a>
                <a href="{{ route('admin.posts.create') }}" class="btn btn-primary">Thêm bài viết</a>
            </div>
        </div>

        @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if($posts->count() > 0)
        <div class="card">
            <table class="table">
                <thead>
                    <tr>
                        <th>Tiêu đề</th>
                        <th>Danh mục</th>
                        <th>Ngày tạo</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($posts as $post)
                    <tr>
                        <td><strong>{{ $post->title }}</strong></td>
                        <td>
                            <span class="badge badge-success">
                                {{ $post->category === 'trong_nuoc' ? 'Trong nước' : 'Ngoài nước' }}
                            </span>
                        </td>
                        <td>{{ $post->created_at->format('d/m/Y') }}</td>
                        <td>
                            <div style="display: flex; gap: 5px; align-items: center;">
                                <a href="{{ route('admin.posts.edit', $post->id) }}" class="btn btn-secondary" style="padding: 6px 16px; line-height: 1.5;">Sửa</a>
                                <form action="{{ route('admin.posts.destroy', $post->id) }}" method="POST"
                                      onsubmit="return confirm('Bạn có chắc muốn xóa bài viết này?')" style="margin:0;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" style="padding: 6px 16px; line-height: 1.5;">Xóa</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div style="margin-top: 20px;">
            {{ $posts->links('vendor.pagination.custom') }}
        </div>
        @else
        <div class="card">
            <p style="text-align: center; padding: 40px;">Chưa có bài viết nào.</p>
        </div>
        @endif
    </div>
</section>
@endsection
