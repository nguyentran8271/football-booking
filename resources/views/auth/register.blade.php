@extends('layouts.app')

@section('title', 'Đăng ký')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/auth.css') }}">
@endpush

@section('content')
<div class="auth-page">
    <div class="auth-card">
        <div class="auth-logo">
            <img src="{{ asset('images/football-logo.png') }}" alt="Logo">
        </div>

        <h1 class="card-title">Đăng Ký</h1>

        @if($errors->any())
        <div class="alert alert-danger">
            @foreach($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
        @endif

        <form action="{{ route('register') }}" method="POST">
            @csrf

            <div class="form-group">
                <label class="form-label">Họ tên *</label>
                <input type="text" name="name" class="form-control" value="{{ old('name') }}" required autofocus>
            </div>

            <div class="form-group">
                <label class="form-label">Email *</label>
                <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
            </div>

            <div class="form-group">
                <label class="form-label">Số điện thoại *</label>
                <input type="text" name="phone" class="form-control" value="{{ old('phone') }}" required pattern="[0-9]{10}" maxlength="10">
                <small>Nhập đúng 10 số</small>
            </div>

            <div class="form-group">
                <label class="form-label">Mật khẩu *</label>
                <input type="password" name="password" class="form-control" required>
                <small>Tối thiểu 8 ký tự</small>
            </div>

            <div class="form-group">
                <label class="form-label">Xác nhận mật khẩu *</label>
                <input type="password" name="password_confirmation" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; padding: 12px;">
                Đăng ký
            </button>
        </form>

        <div style="text-align: center; margin-top: 20px;">
            <p>Đã có tài khoản? <a href="{{ route('login') }}" style="color: #28a745; font-weight: 500;">Đăng nhập</a></p>
        </div>
    </div>
</div>
@endsection
