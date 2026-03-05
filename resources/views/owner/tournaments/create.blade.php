@extends('layouts.app')

@section('title', 'Tạo giải đấu mới')

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

.form-actions {
    display: flex;
    gap: 15px;
    margin-top: 30px;
}

.form-actions .btn {
    flex: 1;
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
}

@media (max-width: 768px) {
    .form-row {
        grid-template-columns: 1fr;
    }
}
</style>
@endpush

@section('content')
<div class="form-container">
    <div class="container">
        <h1 style="text-align: center; margin-bottom: 30px;">Tạo giải đấu mới</h1>

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
            <form action="{{ route('owner.tournaments.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="form-group">
                    <label class="form-label">Tên giải đấu <span class="required">*</span></label>
                    <input type="text" name="name" class="form-control" value="{{ old('name') }}"
                           placeholder="VD: Giải bóng đá mùa hè 2026" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Mô tả</label>
                    <textarea name="description" class="form-control"
                              placeholder="Mô tả về giải đấu, thể lệ, giải thưởng...">{{ old('description') }}</textarea>
                </div>

                <div class="form-group">
                    <label class="form-label">Sân tổ chức <span class="required">*</span></label>
                    <select name="field_id" class="form-control" required>
                        <option value="">-- Chọn sân --</option>
                        @foreach($fields as $field)
                        <option value="{{ $field->id }}" {{ old('field_id') == $field->id ? 'selected' : '' }}>
                            {{ $field->name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Ngày bắt đầu <span class="required">*</span></label>
                        <input type="date" name="start_date" class="form-control" value="{{ old('start_date') }}" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Ngày kết thúc <span class="required">*</span></label>
                        <input type="date" name="end_date" class="form-control" value="{{ old('end_date') }}" required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Hạn đăng ký</label>
                    <input type="date" name="registration_deadline" class="form-control" value="{{ old('registration_deadline') }}">
                    <small style="color: #666;">Để trống nếu không giới hạn</small>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Số đội tối đa <span class="required">*</span></label>
                        <input type="number" name="max_teams" class="form-control" value="{{ old('max_teams', 8) }}"
                               min="2" max="32" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Số người mỗi đội <span class="required">*</span></label>
                        <select name="players_per_team" class="form-control" required>
                            <option value="5" {{ old('players_per_team') == 5 ? 'selected' : '' }}>5 người (Futsal)</option>
                            <option value="7" {{ old('players_per_team') == 7 ? 'selected' : '' }}>7 người</option>
                            <option value="11" {{ old('players_per_team') == 11 ? 'selected' : '' }}>11 người</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Phí tham gia/đội (VNĐ) <span class="required">*</span></label>
                    <input type="number" name="entry_fee" class="form-control" value="{{ old('entry_fee', 0) }}"
                           min="0" step="10000" placeholder="VD: 500000" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Giải thưởng</label>
                    <textarea name="prize" class="form-control"
                              placeholder="VD: Vô địch: 5.000.000đ + Cúp + Huy chương&#10;Á quân: 3.000.000đ + Cúp + Huy chương">{{ old('prize') }}</textarea>
                </div>

                <div class="form-group">
                    <label class="form-label">Ảnh banner giải đấu</label>
                    <input type="file" name="banner" class="form-control" accept="image/*">
                    <small style="color: #666;">Định dạng: JPG, PNG. Tối đa 2MB</small>
                </div>

                <div class="form-actions">
                    <a href="{{ route('owner.tournaments.index') }}" class="btn btn-secondary">Hủy</a>
                    <button type="submit" class="btn btn-primary">Tạo giải đấu</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
