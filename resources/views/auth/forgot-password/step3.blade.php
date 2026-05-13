@extends('layouts.app')
@section('title', 'Quên mật khẩu - Nhập mã OTP')
@push('styles')
<link rel="stylesheet" href="{{ asset('css/auth.css') }}">
<link rel="stylesheet" href="{{ asset('css/falling-effect.css') }}">
@endpush

@section('content')
<div class="auth-page" @php $bg = App\Models\SiteSetting::get('login_background'); @endphp
    @if($bg) style="background-image:linear-gradient(rgba(0,0,0,0.6),rgba(0,0,0,0.6)),url('{{ storage_url($bg) }}');background-size:cover;background-position:center;" @endif>
    <div class="auth-card">
        <div class="auth-logo">
            @php $logo = App\Models\SiteSetting::get('login_logo') ?? App\Models\SiteSetting::get('logo'); @endphp
            <img src="{{ $logo ? storage_url($logo) : asset('images/football-logo.png') }}" alt="Logo">
        </div>

        <h1 class="card-title">Quên Mật Khẩu</h1>

        <div class="forgot-steps">
            <div class="step done">✓</div><div class="step-line active"></div>
            <div class="step done">✓</div><div class="step-line active"></div>
            <div class="step active">3</div><div class="step-line"></div>
            <div class="step">4</div>
        </div>
        <p class="step-desc">Nhập mã OTP đã gửi đến <strong>{{ session('reset_email') }}</strong></p>

        @if(session('success'))
        <div class="alert alert-success"><p>{{ session('success') }}</p></div>
        @endif
        @if($errors->any())
        <div class="alert alert-danger">@foreach($errors->all() as $e)<p>{{ $e }}</p>@endforeach</div>
        @endif

        <form action="{{ route('password.step3.post') }}" method="POST">
            @csrf
            <div class="form-group">
                <label class="form-label">Mã OTP *</label>
                <input type="text" name="otp" id="otp-input" class="form-control"
                    maxlength="6" inputmode="numeric" placeholder="_ _ _ _ _ _"
                    required autofocus autocomplete="off"
                    style="font-size:32px;letter-spacing:14px;text-align:center;font-weight:700;">
                <small style="color:#666;">Mã gồm 6 chữ số, có hiệu lực trong 10 phút</small>
            </div>
            <button type="submit" class="btn btn-primary" style="width:100%;padding:12px;">Xác nhận mã →</button>
        </form>

        <div style="text-align:center;margin-top:15px;">
            <form action="{{ route('password.resend') }}" method="POST" style="display:inline;">
                @csrf
                <button type="submit" class="btn-link-green">Gửi lại mã OTP</button>
            </form>
        </div>
        <div style="text-align:center;margin-top:8px;">
            <a href="{{ route('password.step2') }}" style="color:#28a745;font-size:14px;">← Đổi email</a>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>document.getElementById('falling-canvas').style.display='block';</script>
<script src="{{ asset('js/falling-effect.js') }}"></script>
<script>
document.getElementById('otp-input').addEventListener('input', function() {
    this.value = this.value.replace(/[^0-9]/g, '');
});
</script>
@endpush
