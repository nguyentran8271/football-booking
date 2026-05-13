@extends('layouts.app')
@section('title', 'Đặt lại mật khẩu')
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

        <h1 class="card-title">Đặt Lại Mật Khẩu</h1>

        <div class="forgot-steps">
            <div class="step done">✓</div><div class="step-line active"></div>
            <div class="step done">✓</div><div class="step-line active"></div>
            <div class="step done">✓</div><div class="step-line active"></div>
            <div class="step active">4</div>
        </div>
        <p class="step-desc">Tạo mật khẩu mới cho tài khoản</p>

        @if($errors->any())
        <div class="alert alert-danger">@foreach($errors->all() as $e)<p>{{ $e }}</p>@endforeach</div>
        @endif

        <form action="{{ route('password.step4.post') }}" method="POST">
            @csrf
            <div class="form-group">
                <label class="form-label">Mật khẩu mới *</label>
                <input type="password" name="password" class="form-control" required minlength="8" autofocus>
                <small style="color:#666;">Tối thiểu 8 ký tự</small>
            </div>
            <div class="form-group">
                <label class="form-label">Xác nhận mật khẩu *</label>
                <input type="password" name="password_confirmation" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary" style="width:100%;padding:12px;">✅ Đặt lại mật khẩu</button>
        </form>
    </div>
</div>
@endsection
@push('scripts')
<script>document.getElementById('falling-canvas').style.display='block';</script>
<script src="{{ asset('js/falling-effect.js') }}"></script>
@endpush
