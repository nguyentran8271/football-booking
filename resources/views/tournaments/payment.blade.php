@extends('layouts.app')

@section('title', 'Thanh toán đăng ký giải đấu')

@push('styles')
<style>
.payment-page { padding: 40px 0; min-height: 70vh; }
.payment-container { max-width: 700px; margin: 0 auto; }
.payment-info-box {
    background: linear-gradient(135deg, #1e7e34 0%, #28a745 100%);
    color: white;
    padding: 25px;
    border-radius: 12px;
    margin-bottom: 30px;
}
.payment-info-box h2 { margin: 0 0 15px 0; font-size: 22px; }
.payment-info-box p { margin: 5px 0; font-size: 15px; }
.payment-form-box {
    background: white;
    padding: 35px;
    border-radius: 12px;
    box-shadow: 0 2px 15px rgba(0,0,0,0.1);
}
</style>
@endpush

@section('content')
<div class="payment-page">
    <div class="container">
        <div class="payment-container">
            <div style="text-align:center; margin-bottom: 30px;">
                <h1 style="font-size: 28px; margin-bottom: 8px;">Thanh toán phí đăng ký</h1>
                <p style="color:#666;">Hoàn tất thanh toán để xác nhận đăng ký của bạn</p>
            </div>

            <div class="payment-info-box">
                <h2>{{ $team->tournament->name }}</h2>
                <p>Đội: <strong>{{ $team->team_name }}</strong></p>
                <p>Đội trưởng: {{ $team->captain_name }}</p>
                <p>Phí tham gia: <strong>{{ number_format($team->tournament->entry_fee) }}đ</strong></p>
            </div>

            <div class="payment-form-box">
                {!! $formHtml !!}
            </div>
        </div>
    </div>
</div>
@endsection
