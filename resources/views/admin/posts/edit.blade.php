@extends('layouts.app')

@section('title', 'Sửa bài viết')

@section('content')
<section class="section">
    <div class="container">
        <div style="max-width: 900px; margin: 0 auto;">
            <h1 style="margin-bottom: 30px;">Sửa Bài Viết</h1>

            @if($errors->any())
            <div class="alert alert-danger">
                @foreach($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
            @endif

            <div class="card">
                <form action="{{ route('admin.posts.update', $post->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label class="form-label">Tiêu đề *</label>
                        <input type="text" name="title" class="form-control" value="{{ old('title', $post->title) }}" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Danh mục *</label>
                        <select name="category" class="form-control" required>
                            <option value="trong_nuoc" {{ old('category', $post->category) === 'trong_nuoc' ? 'selected' : '' }}>Trong nước</option>
                            <option value="ngoai_nuoc" {{ old('category', $post->category) === 'ngoai_nuoc' ? 'selected' : '' }}>Ngoài nước</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Nội dung *</label>
                        <textarea name="content" class="form-control" rows="10" required>{{ old('content', $post->content) }}</textarea>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Hình ảnh hiện tại</label>
                        @if($post->image)
                        <div style="margin-bottom: 10px;">
                            <img src="{{ asset('storage/' . $post->image) }}" alt="{{ $post->title }}" style="max-width: 300px; border-radius: 10px;">
                        </div>
                        @endif
                        <input type="file" name="image" class="form-control" accept="image/*">
                        <small>Để trống nếu không muốn thay đổi ảnh</small>
                    </div>

                    <div style="display: flex; gap: 15px;">
                        <button type="submit" class="btn btn-primary">Cập nhật</button>
                        <a href="{{ route('admin.posts.index') }}" class="btn btn-secondary">Hủy</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
