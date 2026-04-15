@extends('layouts.app')
@section('title', 'Thanh toán')
@section('content')
<div class="container" style="padding:40px 0;max-width:600px;">
    <h2 style="margin-bottom:20px;">Thanh toán đặt sân</h2>
    <div style="background:#f8f9fa;padding:20px;border-radius:10px;margin-bottom:24px;">
        <p><strong>Sân:</strong> {{ $booking->field->name }}</p>
        <p><strong>Ngày:</strong> {{ $booking->date->format('d/m/Y') }} - Ca {{ $booking->shift }} ({{ $booking->start_time }} - {{ $booking->end_time }})</p>
        <p><strong>Số tiền:</strong> <span style="color:#28a745;font-size:18px;font-weight:700;">{{ number_format($booking->total_price) }}đ</span></p>
    </div>
    {!! $formHtml !!}
</div>
@endsection
