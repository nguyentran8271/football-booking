@extends('layouts.app')

@section('title', 'Đăng nhập')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/auth.css') }}">
@endpush

@section('content')
<div class="auth-page">
    <div class="auth-card">
        <div class="auth-logo">
            <img src="{{ asset('images/football-logo.png') }}" alt="Logo">
        </div>

        <h1 class="card-title">Đăng Nhập</h1>

        @if($errors->any())
        <div class="alert alert-danger">
            @foreach($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
        @endif

        <form action="{{ route('login') }}" method="POST">
            @csrf

            <div class="form-group">
                <label class="form-label">Email *</label>
                <input type="email" name="email" class="form-control" value="{{ old('email') }}" required autofocus>
            </div>

            <div class="form-group">
                <label class="form-label">Mật khẩu *</label>
                <input type="password" name="password" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; padding: 12px;">
                Đăng nhập
            </button>
        </form>

        <div style="text-align: center; margin-top: 20px;">
            <p>Chưa có tài khoản? <a href="{{ route('register') }}" style="color: #28a745; font-weight: 500;">Đăng ký ngay</a></p>
        </div>
    </div>
</div>
@endsection
