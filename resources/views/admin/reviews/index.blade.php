@extends('layouts.app')

@section('title', 'Quản lý Đánh giá')

@section('content')
<section class="section">
    <div class="container">
        <h1 style="margin-bottom: 30px;">Quản Lý Đánh Giá</h1>

        @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if($reviews->count() > 0)
        <div class="card">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Người dùng</th>
                        <th>Sân</th>
                        <th>Rating</th>
                        <th>Nhận xét</th>
                        <th>Ngày</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reviews as $review)
                    <tr>
                        <td>{{ $review->id }}</td>
                        <td>{{ $review->user->name }}</td>
                        <td>{{ $review->field->name }}</td>
                        <td>
                            <span style="color: #ffc107;">
                                @for($i = 0; $i < $review->rating; $i++)⭐@endfor
                            </span>
                        </td>
                        <td>{{ Str::limit($review->comment, 50) }}</td>
                        <td>{{ $review->created_at->format('d/m/Y') }}</td>
                        <td>
                            <form action="{{ route('admin.reviews.destroy', $review->id) }}" method="POST"
                                  onsubmit="return confirm('Bạn có chắc muốn xóa đánh giá này?')">
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
            {{ $reviews->links() }}
        </div>
        @else
        <div class="card">
            <p style="text-align: center; padding: 40px;">Chưa có đánh giá nào.</p>
        </div>
        @endif
    </div>
</section>
@endsection
