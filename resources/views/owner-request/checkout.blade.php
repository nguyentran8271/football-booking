@extends('layouts.app')
@section('title', 'Thanh toán đăng ký chủ sân')
@section('content')
<div class="container" style="padding:40px 0;max-width:600px;">
    <h2 style="margin-bottom:20px;">Thanh toán đăng ký chủ sân</h2>
    <div style="background:#f8f9fa;padding:20px;border-radius:10px;margin-bottom:24px;">
        <p><strong>Gói:</strong> {{ $labels[$plan] }}</p>
        <p><strong>Số tiền:</strong> <span style="color:#28a745;font-size:20px;font-weight:700;">{{ number_format($prices[$plan]) }}đ</span></p>
    </div>
    {!! $formHtml !!}
</div>
@endsection
