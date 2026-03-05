@extends('layouts.app')

@section('title', 'Thêm bài viết')

@section('content')
<section class="section">
    <div class="container">
        <div style="max-width: 900px; margin: 0 auto;">
            <h1 style="margin-bottom: 30px;">Thêm Bài Viết</h1>

            @if($errors->any())
            <div class="alert alert-danger">
                @foreach($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
            @endif

            <div class="card">
                <form action="{{ route('admin.posts.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="form-group">
                        <label class="form-label">Tiêu đề *</label>
                        <input type="text" name="title" class="form-control" value="{{ old('title') }}" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Danh mục *</label>
                        <select name="category" class="form-control" required>
                            <option value="trong_nuoc" {{ old('category') === 'trong_nuoc' ? 'selected' : '' }}>Trong nước</option>
                            <option value="ngoai_nuoc" {{ old('category') === 'ngoai_nuoc' ? 'selected' : '' }}>Ngoài nước</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Nội dung *</label>
                        <textarea name="content" class="form-control" rows="10" required>{{ old('content') }}</textarea>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Hình ảnh</label>
                        <input type="file" name="image" class="form-control" accept="image/*">
                    </div>

                    <div style="display: flex; gap: 15px;">
                        <button type="submit" class="btn btn-primary">Thêm bài viết</button>
                        <a href="{{ route('admin.posts.index') }}" class="btn btn-secondary">Hủy</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
