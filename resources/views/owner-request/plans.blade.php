@extends('layouts.app')
@section('title', 'Chọn gói đăng ký chủ sân')
@section('content')
<div class="container" style="padding:40px 0;max-width:700px;">
    <h2 style="text-align:center;margin-bottom:8px;">Chọn gói đăng ký</h2>
    <p style="text-align:center;color:#666;margin-bottom:32px;">Thanh toán để gửi đơn đăng ký làm chủ sân</p>

    <form action="{{ route('owner-request.checkout') }}" method="POST">
        @csrf
        <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:16px;margin-bottom:24px;">
            @foreach([
                ['1m',  '1 tháng',  300000,  'Dùng thử'],
                ['3m',  '3 tháng',  600000,  'Phổ biến'],
                ['6m',  '6 tháng',  1000000, 'Tiết kiệm'],
                ['12m', '12 tháng', 1500000, 'Tốt nhất'],
            ] as [$val, $label, $price, $badge])
            <label style="border:2px solid #ddd;border-radius:12px;padding:20px;cursor:pointer;position:relative;transition:border-color 0.2s;" onclick="selectPlan(this)">
                <input type="radio" name="plan" value="{{ $val }}" style="display:none;" {{ $val === '3m' ? 'checked' : '' }}>
                <div style="position:absolute;top:-10px;right:12px;background:#28a745;color:#fff;font-size:11px;padding:2px 8px;border-radius:10px;">{{ $badge }}</div>
                <div style="font-size:18px;font-weight:700;margin-bottom:4px;">{{ $label }}</div>
                <div style="font-size:22px;color:#28a745;font-weight:700;">{{ number_format($price) }}đ</div>
                <div style="font-size:12px;color:#999;margin-top:4px;">{{ number_format($price / (int)$val) }}đ/tháng</div>
            </label>
            @endforeach
        </div>
        <button type="submit" class="btn btn-primary" style="width:100%;padding:14px;font-size:16px;">Tiếp tục thanh toán</button>
        <a href="{{ route('for-owners') }}" style="display:block;text-align:center;margin-top:12px;color:#666;">← Quay lại</a>
    </form>
</div>
<script>
function selectPlan(label) {
    document.querySelectorAll('label[onclick]').forEach(l => l.style.borderColor = '#ddd');
    label.style.borderColor = '#28a745';
    label.querySelector('input').checked = true;
}
document.addEventListener('DOMContentLoaded', function() {
    var checked = document.querySelector('input[name="plan"]:checked');
    if (checked) checked.closest('label').style.borderColor = '#28a745';
});
</script>
@endsection
