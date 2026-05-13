@extends('layouts.app')
@section('title', 'Quên mật khẩu - Xác nhận email')
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
            <div class="step active">2</div><div class="step-line"></div>
            <div class="step">3</div><div class="step-line"></div>
            <div class="step">4</div>
        </div>
        <p class="step-desc">Xin chào <strong>{{ session('reset_name') }}</strong>! Nhập email để nhận mã xác nhận</p>

        @if($errors->any())
        <div class="alert alert-danger">@foreach($errors->all() as $e)<p>{{ $e }}</p>@endforeach</div>
        @endif

        <form action="{{ route('password.step2.post') }}" method="POST">
            @csrf
            <div class="form-group">
                <label class="form-label">Email *</label>
                <input type="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="Nhập email đã đăng ký" required autofocus>
                <small style="color:#666;">Mã OTP 6 số sẽ được gửi đến email này</small>
            </div>
            <button type="submit" class="btn btn-primary" style="width:100%;padding:12px;">Gửi mã OTP →</button>
        </form>

        <div style="text-align:center;margin-top:20px;">
            <a href="{{ route('password.step1') }}" style="color:#28a745;">← Quay lại</a>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>document.getElementById('falling-canvas').style.display='block';</script>
<script src="{{ asset('js/falling-effect.js') }}"></script>
@endpush
