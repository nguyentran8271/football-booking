@extends('layouts.app')

@section('title', App\Models\SiteSetting::get('policy_title') ?: 'Chính sách')

@section('content')
<section class="section">
    <div class="container">
        @php
            $policyTitle = App\Models\SiteSetting::get('policy_title') ?: 'Chính Sách';
            $policyContent = App\Models\SiteSetting::get('policy_content');
        @endphp

        <h1 style="margin-bottom: 30px;">{{ $policyTitle }}</h1>

        <div class="card">
            @if($policyContent)
                {!! nl2br(e($policyContent)) !!}
            @else
                <h2 class="card-title">Chính sách đặt sân</h2>
                <p>1. Khách hàng cần đăng ký tài khoản để đặt sân</p>
                <p>2. Đặt sân trước ít nhất 2 giờ so với giờ chơi</p>
                <p>3. Thanh toán trực tiếp tại sân</p>
                <p>4. Có thể hủy đặt sân trước 4 giờ</p>

                <h2 class="card-title" style="margin-top: 30px;">Chính sách hoàn tiền</h2>
                <p>1. Hủy trước 24 giờ: Hoàn 100%</p>
                <p>2. Hủy trước 4 giờ: Hoàn 50%</p>
                <p>3. Hủy trong vòng 4 giờ: Không hoàn tiền</p>
            @endif
        </div>
    </div>
</section>
@endsection
