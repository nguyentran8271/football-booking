@extends('layouts.app')

@section('title', 'Sửa sân')

@push('styles')
<style>
.form-container {
    padding: 30px 0;
}

.form-card {
    max-width: 800px;
    margin: 0 auto;
    background: white;
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.form-group {
    margin-bottom: 20px;
}

.form-label {
    display: block;
    margin-bottom: 8px;
    font-weight: 600;
    color: #333;
}

.form-label .required {
    color: #dc3545;
}

.form-control {
    width: 100%;
    padding: 12px;
    border: 1px solid #ddd;
    border-radius: 5px;
    font-size: 15px;
}

.form-control:focus {
    outline: none;
    border-color: #28a745;
}

textarea.form-control {
    resize: vertical;
    min-height: 100px;
}

.current-image {
    margin-top: 10px;
    border-radius: 10px;
    max-width: 300px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.form-actions {
    display: flex;
    gap: 15px;
    margin-top: 30px;
}

.form-actions .btn {
    flex: 1;
}
</style>
@endpush

@section('content')
<div class="form-container">
    <div class="container">
        <h1 style="text-align: center; margin-bottom: 30px;">Sửa Sân: {{ $field->name }}</h1>

        @if($errors->any())
        <div class="alert alert-danger" style="max-width: 800px; margin: 0 auto 20px;">
            <ul style="margin: 0; padding-left: 20px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <div class="form-card">
            <form action="{{ route('owner.fields.update', $field->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label class="form-label">Tên sân <span class="required">*</span></label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $field->name) }}"
                           placeholder="VD: Sân bóng Thành Công" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Địa chỉ <span class="required">*</span></label>
                    <textarea name="address" class="form-control" required
                              placeholder="VD: 123 Đường ABC, Quận 1, TP.HCM">{{ old('address', $field->address) }}</textarea>
                </div>

                <div class="form-group">
                    <label class="form-label">Giá mỗi giờ (VNĐ) <span class="required">*</span></label>
                    <input type="number" name="price_per_hour" class="form-control"
                           value="{{ old('price_per_hour', $field->price_per_hour) }}"
                           min="0" step="1000" placeholder="VD: 200000" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Số điện thoại liên hệ</label>
                    <input type="text" name="hotline" class="form-control"
                           value="{{ old('hotline', $field->hotline) }}"
                           placeholder="VD: 0123456789" maxlength="10">
                </div>

                <div class="form-group">
                    <label class="form-label">Mô tả</label>
                    <textarea name="description" class="form-control"
                              placeholder="Mô tả về sân bóng, tiện nghi, đặc điểm...">{{ old('description', $field->description) }}</textarea>
                </div>

                <div class="form-group">
                    <label class="form-label">Trạng thái <span class="required">*</span></label>
                    <select name="status" class="form-control" required>
                        <option value="active" {{ old('status', $field->status) == 'active' ? 'selected' : '' }}>✓ Hoạt động</option>
                        <option value="inactive" {{ old('status', $field->status) == 'inactive' ? 'selected' : '' }}>✕ Tạm ngưng</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Hình ảnh sân</label>
                    @if($field->image)
                    <div style="margin-bottom: 10px;">
                        <p style="color: #666; margin-bottom: 10px;">Hình ảnh hiện tại:</p>
                        <img src="{{ asset('storage/' . $field->image) }}" alt="{{ $field->name }}" class="current-image">
                    </div>
                    @endif
                    <input type="file" name="image" class="form-control" accept="image/*">
                    <small style="color: #666;">Để trống nếu không muốn thay đổi. Định dạng: JPG, PNG, GIF. Tối đa 2MB</small>
                </div>

                <div class="form-actions">
                    <a href="{{ route('owner.fields.index') }}" class="btn btn-secondary">← Hủy</a>
                    <button type="submit" class="btn btn-primary">✓ Cập nhật</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
