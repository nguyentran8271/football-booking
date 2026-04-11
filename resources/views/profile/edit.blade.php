@extends('layouts.app')
@section('title', 'Thông tin cá nhân')
@section('content')
<section class="section">
    <div class="container" style="max-width:700px;">
        <h1 style="margin-bottom:30px;">Thông tin cá nhân</h1>

        @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if($errors->any())
        <div class="alert alert-danger">
            @foreach($errors->all() as $error)<p>{{ $error }}</p>@endforeach
        </div>
        @endif

        {{-- Thông tin cơ bản --}}
        <div class="card" style="margin-bottom:24px;">
            <h2 class="card-title">Thông tin cơ bản</h2>
            <form action="{{ route('profile.update') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label class="form-label">Họ tên</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Email</label>
                    <input type="email" class="form-control" value="{{ $user->email }}" disabled style="background:#f8f9fa;">
                    <small style="color:#999;">Email không thể thay đổi</small>
                </div>

                <div class="form-group">
                    <label class="form-label">Số điện thoại</label>
                    <input type="text" name="phone" class="form-control" value="{{ old('phone', $user->phone) }}" pattern="[0-9]{10}" maxlength="10" required>
                </div>

                <hr style="margin:24px 0;">
                <p style="color:#666; font-size:14px; margin-bottom:16px;">Để trống nếu không muốn đổi mật khẩu</p>

                <div class="form-group">
                    <label class="form-label">Mật khẩu mới</label>
                    <input type="password" name="password" class="form-control">
                </div>

                <div class="form-group">
                    <label class="form-label">Xác nhận mật khẩu mới</label>
                    <input type="password" name="password_confirmation" class="form-control">
                </div>

                <button type="submit" class="btn btn-primary" style="width:100%; padding:12px;">Lưu thay đổi</button>
            </form>
        </div>

        {{-- Thông tin chủ sân --}}
        @if($user->isOwner())
        <div class="card">
            <h2 class="card-title">Thông tin xác minh chủ sân</h2>
            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <input type="hidden" name="name" value="{{ $user->name }}">
                <input type="hidden" name="phone" value="{{ $user->phone }}">

                <div class="form-group">
                    <label class="form-label">Mô tả sân bóng</label>
                    <p style="padding:10px; background:#f8f9fa; border-radius:8px; color:#555;">{{ $user->owner_request_note ?: 'Chưa có mô tả' }}</p>
                </div>

                <div class="form-group">
                    <label class="form-label">Mã số thuế</label>
                    <input type="text" name="tax_number" class="form-control" value="{{ old('tax_number', $user->tax_number) }}" pattern="\d{10,13}" maxlength="13" placeholder="10-13 chữ số">
                </div>

                <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap:20px; margin-top:16px;">
                    @foreach([
                        ['id_card_image', 'CCCD mặt trước'],
                        ['id_card_back_image', 'CCCD mặt sau'],
                        ['id_card_selfie_image', 'Ảnh mặt kèm CCCD'],
                        ['business_license_image', 'Giấy phép kinh doanh'],
                    ] as [$field, $label])
                    <div>
                        <label class="form-label" style="font-size:13px;">{{ $label }}</label>
                        @if($user->$field)
                        <a href="{{ storage_url($user->$field) }}" target="_blank">
                            <img src="{{ storage_url($user->$field) }}" alt="{{ $label }}" style="width:100%; border-radius:8px; border:1px solid #eee; margin-bottom:8px; display:block;">
                        </a>
                        @else
                        <div style="width:100%; height:100px; background:#f8f9fa; border-radius:8px; border:1px dashed #ddd; display:flex; align-items:center; justify-content:center; color:#999; font-size:13px; margin-bottom:8px;">Chưa có ảnh</div>
                        @endif
                        <input type="file" name="{{ $field }}" class="form-control" accept="image/*" style="font-size:13px;">
                    </div>
                    @endforeach
                </div>

                <button type="submit" class="btn btn-primary" style="width:100%; padding:12px; margin-top:20px;">Lưu thay đổi</button>
            </form>
        </div>
        @endif
    </div>
</section>
@endsection
