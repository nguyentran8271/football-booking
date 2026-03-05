@extends('layouts.app')

@section('title', 'Quản lý Nội dung Trang chủ')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin.css') }}">
@endpush

@section('content')
<section class="section">
    <div class="container">
        <h1 style="margin-bottom: 30px;">Quản lý Nội dung Trang chủ</h1>

        @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if($errors->any())
        <div class="alert alert-danger">
            <ul style="margin: 0; padding-left: 20px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <!-- Tabs -->
        <div class="admin-tabs">
            <button class="admin-tab active" onclick="switchTab('cards')">Cards Về chúng tôi</button>
            <button class="admin-tab" onclick="switchTab('stats')">Số liệu</button>
            <button class="admin-tab" onclick="switchTab('fields')">Sân nổi bật</button>
        </div>

        <!-- Tab: Cards -->
        <div id="cards" class="tab-content active">
            <div class="card">
                <h2 class="card-title">Quản lý Cards "Về chúng tôi"</h2>

                <form action="{{ route('admin.home-content.cards.store') }}" method="POST" style="margin-bottom: 30px;">
                    @csrf
                    <h3>Thêm Card mới</h3>
                    <div class="form-group">
                        <label class="form-label">Tiêu đề</label>
                        <input type="text" name="title" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Mô tả</label>
                        <textarea name="description" class="form-control" rows="3" required></textarea>
                    </div>
                    <input type="hidden" name="order" value="0">
                    <button type="submit" class="btn btn-primary">Thêm Card</button>
                </form>

                <h3>Danh sách Cards</h3>
                @foreach($cards as $card)
                <div class="card" style="margin-bottom: 15px; padding: 15px; background: #f8f9fa;">
                    <form action="{{ route('admin.home-content.cards.update', $card->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label class="form-label">Tiêu đề</label>
                            <input type="text" name="title" class="form-control" value="{{ $card->title }}" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Mô tả</label>
                            <textarea name="description" class="form-control" rows="2" required>{{ $card->description }}</textarea>
                        </div>
                        <input type="hidden" name="order" value="{{ $card->order }}">
                        <button type="submit" class="btn btn-primary">Cập nhật</button>
                        <button type="button" class="btn btn-danger" onclick="deleteItem('{{ route('admin.home-content.cards.delete', $card->id) }}')">Xóa</button>
                    </form>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Tab: Stats -->
        <div id="stats" class="tab-content">
            <div class="card">
                <h2 class="card-title">Quản lý Số liệu</h2>

                <form action="{{ route('admin.home-content.stats.store') }}" method="POST" style="margin-bottom: 30px;">
                    @csrf
                    <h3>Thêm Số liệu mới</h3>
                    <div class="form-group">
                        <label class="form-label">Giá trị (số)</label>
                        <input type="text" name="value" class="form-control" required placeholder="100+">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Nhãn</label>
                        <input type="text" name="title" class="form-control" required placeholder="Người dùng">
                    </div>
                    <input type="hidden" name="order" value="0">
                    <button type="submit" class="btn btn-primary">Thêm Số liệu</button>
                </form>

                <h3>Danh sách Số liệu</h3>
                @foreach($stats as $stat)
                <div class="card" style="margin-bottom: 15px; padding: 15px; background: #f8f9fa;">
                    <form action="{{ route('admin.home-content.stats.update', $stat->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label class="form-label">Giá trị (số)</label>
                            <input type="text" name="value" class="form-control" value="{{ $stat->value }}" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Nhãn</label>
                            <input type="text" name="title" class="form-control" value="{{ $stat->title }}" required>
                        </div>
                        <input type="hidden" name="order" value="{{ $stat->order }}">
                        <button type="submit" class="btn btn-primary">Cập nhật</button>
                        <button type="button" class="btn btn-danger" onclick="deleteItem('{{ route('admin.home-content.stats.delete', $stat->id) }}')">Xóa</button>
                    </form>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Tab: Featured Fields -->
        <div id="fields" class="tab-content">
            <div class="card">
                <h2 class="card-title">Quản lý Sân nổi bật</h2>

                <form action="{{ route('admin.home-content.fields.store') }}" method="POST" enctype="multipart/form-data" style="margin-bottom: 30px;">
                    @csrf
                    <h3>Thêm Sân nổi bật mới</h3>
                    <div class="form-group">
                        <label class="form-label">Tiêu đề</label>
                        <input type="text" name="title" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Mô tả</label>
                        <textarea name="description" class="form-control" rows="2" required></textarea>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Giá (VNĐ/giờ)</label>
                        <input type="number" name="price" class="form-control" required min="1" step="1000" placeholder="200000">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Hotline</label>
                        <input type="text" name="hotline" class="form-control" placeholder="0123456789">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Ảnh</label>
                        <input type="file" name="image" class="form-control" accept="image/*">
                    </div>
                    <input type="hidden" name="order" value="0">
                    <button type="submit" class="btn btn-primary">Thêm Sân</button>
                </form>

                <h3>Danh sách Sân nổi bật</h3>
                @foreach($featuredFields as $field)
                <div class="card" style="margin-bottom: 15px; padding: 15px; background: #f8f9fa;">
                    <form action="{{ route('admin.home-content.fields.update', $field->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        @if($field->image)
                        <div style="margin-bottom: 10px;">
                            <img src="{{ asset('storage/' . $field->image) }}" alt="{{ $field->title }}" style="max-width: 200px; border-radius: 10px;">
                        </div>
                        @endif
                        <div class="form-group">
                            <label class="form-label">Tiêu đề</label>
                            <input type="text" name="title" class="form-control" value="{{ $field->title }}" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Mô tả</label>
                            <textarea name="description" class="form-control" rows="2" required>{{ $field->description }}</textarea>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Giá (VNĐ/giờ)</label>
                            <input type="number" name="price" class="form-control" value="{{ $field->price }}" required min="1" step="1000">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Hotline</label>
                            <input type="text" name="hotline" class="form-control" value="{{ $field->hotline }}" placeholder="0123456789">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Ảnh mới</label>
                            <input type="file" name="image" class="form-control" accept="image/*">
                        </div>
                        <input type="hidden" name="order" value="{{ $field->order }}">
                        <button type="submit" class="btn btn-primary">Cập nhật</button>
                        <button type="button" class="btn btn-danger" onclick="deleteItem('{{ route('admin.home-content.fields.delete', $field->id) }}')">Xóa</button>
                    </form>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

@push('scripts')
<script>
function switchTab(tabName) {
    document.querySelectorAll('.tab-content').forEach(tab => {
        tab.classList.remove('active');
    });
    document.querySelectorAll('.admin-tab').forEach(btn => {
        btn.classList.remove('active');
    });
    document.getElementById(tabName).classList.add('active');
    event.target.classList.add('active');

    // Lưu tab hiện tại
    localStorage.setItem('activeHomeContentTab', tabName);
}

// Khôi phục tab sau khi tải trang
document.addEventListener('DOMContentLoaded', function() {
    const activeTab = localStorage.getItem('activeHomeContentTab');
    if (activeTab && document.getElementById(activeTab)) {
        document.querySelectorAll('.tab-content').forEach(tab => {
            tab.classList.remove('active');
        });
        document.querySelectorAll('.admin-tab').forEach(btn => {
            btn.classList.remove('active');
        });
        document.getElementById(activeTab).classList.add('active');
        document.querySelector(`[onclick="switchTab('${activeTab}')"]`).classList.add('active');
    }
});

function deleteItem(url) {
    if (confirm('Bạn có chắc muốn xóa?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = url;
        form.innerHTML = '@csrf @method("DELETE")';
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endpush
@endsection
