@extends('layouts.app')

@section('title', 'Chỉnh sửa giải đấu')

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

.current-banner {
    max-width: 300px;
    border-radius: 8px;
    margin-top: 10px;
}

.delete-section {
    margin-top: 40px;
    padding-top: 30px;
    border-top: 2px solid #f0f0f0;
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
        <h1 style="text-align: center; margin-bottom: 30px;">Chỉnh sửa giải đấu</h1>

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
            <form action="{{ route('owner.tournaments.update', $tournament->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label class="form-label">Tên giải đấu <span class="required">*</span></label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $tournament->name) }}"
                           placeholder="VD: Giải bóng đá mùa hè 2026" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Mô tả</label>
                    <textarea name="description" class="form-control"
                              placeholder="Mô tả về giải đấu, thể lệ, giải thưởng...">{{ old('description', $tournament->description) }}</textarea>
                </div>

                <div class="form-group">
                    <label class="form-label">Sân tổ chức <span class="required">*</span></label>
                    <select name="field_id" class="form-control" required>
                        <option value="">-- Chọn sân --</option>
                        @foreach($fields as $field)
                        <option value="{{ $field->id }}" {{ old('field_id', $tournament->field_id) == $field->id ? 'selected' : '' }}>
                            {{ $field->name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Ngày bắt đầu <span class="required">*</span></label>
                        <input type="date" name="start_date" class="form-control" value="{{ old('start_date', $tournament->start_date) }}" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Ngày kết thúc <span class="required">*</span></label>
                        <input type="date" name="end_date" class="form-control" value="{{ old('end_date', $tournament->end_date) }}" required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Hạn đăng ký</label>
                    <input type="date" name="registration_deadline" class="form-control" value="{{ old('registration_deadline', $tournament->registration_deadline) }}">
                    <small style="color: #666;">Để trống nếu không giới hạn</small>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Số đội tối đa <span class="required">*</span></label>
                        <input type="number" name="max_teams" class="form-control" value="{{ old('max_teams', $tournament->max_teams) }}"
                               min="2" max="32" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Số người mỗi đội <span class="required">*</span></label>
                        <select name="players_per_team" class="form-control" required>
                            <option value="5" {{ old('players_per_team', $tournament->players_per_team) == 5 ? 'selected' : '' }}>5 người (Futsal)</option>
                            <option value="7" {{ old('players_per_team', $tournament->players_per_team) == 7 ? 'selected' : '' }}>7 người</option>
                            <option value="11" {{ old('players_per_team', $tournament->players_per_team) == 11 ? 'selected' : '' }}>11 người</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Phí tham gia/đội (VNĐ) <span class="required">*</span></label>
                    <input type="number" name="entry_fee" class="form-control" value="{{ old('entry_fee', $tournament->entry_fee) }}"
                           min="0" step="10000" placeholder="VD: 500000" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Giải thưởng</label>
                    <textarea name="prize" class="form-control"
                              placeholder="VD: Vô địch: 5.000.000đ + Cúp + Huy chương&#10;Á quân: 3.000.000đ + Cúp + Huy chương">{{ old('prize', $tournament->prize) }}</textarea>
                </div>

                <div class="form-group">
                    <label class="form-label">Trạng thái <span class="required">*</span></label>
                    <select name="status" class="form-control" required>
                        <option value="upcoming" {{ old('status', $tournament->status) == 'upcoming' ? 'selected' : '' }}>Sắp diễn ra</option>
                        <option value="ongoing" {{ old('status', $tournament->status) == 'ongoing' ? 'selected' : '' }}>Đang diễn ra</option>
                        <option value="finished" {{ old('status', $tournament->status) == 'finished' ? 'selected' : '' }}>Đã kết thúc</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Ảnh banner giải đấu</label>
                    @if($tournament->banner)
                    <div style="margin-bottom: 10px;">
                        <img src="{{ storage_url($tournament->banner) }}" alt="Current banner" class="current-banner">
                        <p style="font-size: 13px; color: #666; margin-top: 5px;">Banner hiện tại</p>
                    </div>
                    @endif
                    <input type="file" name="banner" class="form-control" accept="image/*">
                    <small style="color: #666;">Định dạng: JPG, PNG. Tối đa 2MB. Để trống nếu không muốn thay đổi.</small>
                </div>

                <div class="form-actions">
                    <a href="{{ route('owner.tournaments.show', $tournament->id) }}" class="btn btn-secondary">Hủy</a>
                    <button type="submit" class="btn btn-primary">Cập nhật</button>
                </div>
            </form>

            <div class="delete-section">
                <h3 style="color: #dc3545; margin-bottom: 15px;">Xóa giải đấu</h3>
                <p style="color: #666; margin-bottom: 15px;">Hành động này không thể hoàn tác. Tất cả dữ liệu liên quan sẽ bị xóa.</p>
                <form action="{{ route('owner.tournaments.destroy', $tournament->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa giải đấu này?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Xóa giải đấu</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
