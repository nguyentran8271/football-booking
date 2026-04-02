@extends('layouts.app')

@section('title', 'Thêm bài viết')

@push('styles')
<link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">
<style>
#quill-editor { height: 400px; background: #fff; }
.ql-toolbar { border-radius: 8px 8px 0 0; }
.ql-container { border-radius: 0 0 8px 8px; font-size: 15px; }
</style>
@endpush

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
                <form id="post-form" action="{{ route('admin.posts.store') }}" method="POST" enctype="multipart/form-data">
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
                        <div id="quill-editor">{!! old('content') !!}</div>
                        <textarea name="content" id="content-hidden" style="display:none;">{{ old('content') }}</textarea>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Hình ảnh thumbnail</label>
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

@push('scripts')
<script src="https://cdn.quilljs.com/1.3.7/quill.min.js"></script>
<script>
var quill = new Quill('#quill-editor', {
    theme: 'snow',
    modules: {
        toolbar: [
            [{ 'header': [1, 2, 3, false] }],
            ['bold', 'italic', 'underline'],
            [{ 'list': 'ordered' }, { 'list': 'bullet' }],
            [{ 'align': [] }],
            ['link', 'image'],
            ['clean']
        ]
    }
});

document.getElementById('post-form').addEventListener('submit', function(e) {
    var content = quill.root.innerHTML.trim();
    if (content === '<p><br></p>' || content === '') {
        e.preventDefault();
        alert('Vui lòng nhập nội dung bài viết!');
        return;
    }
    document.getElementById('content-hidden').value = content;
});

quill.getModule('toolbar').addHandler('image', function() {
    var input = document.createElement('input');
    input.setAttribute('type', 'file');
    input.setAttribute('accept', 'image/*');
    input.click();
    input.onchange = function() {
        var file = input.files[0];
        var formData = new FormData();
        formData.append('file', file);
        formData.append('_token', '{{ csrf_token() }}');
        fetch('{{ route('admin.posts.upload-image') }}', { method: 'POST', body: formData })
            .then(function(r) { return r.json(); })
            .then(function(data) {
                var range = quill.getSelection();
                quill.insertEmbed(range.index, 'image', data.location);
            });
    };
});
</script>
@endpush
