@extends('layouts.app')

@section('title', 'Đặt sân - ' . $field->name)

@push('styles')
<style>
.booking-container {
    padding: 30px 0;
}

.info-card {
    background: white;
    padding: 25px;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    margin-bottom: 20px;
}

.shift-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 15px;
    margin-top: 15px;
}

.shift-option {
    position: relative;
}

.shift-option input[type="radio"] {
    display: none;
}

.shift-label {
    display: block;
    padding: 15px;
    border: 2px solid #ddd;
    border-radius: 8px;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s;
}

.shift-label:hover {
    border-color: #28a745;
    background: #f8f9fa;
}

.shift-option input[type="radio"]:checked + .shift-label {
    border-color: #28a745;
    background: #d4edda;
    font-weight: bold;
}

.shift-option input[type="radio"]:disabled + .shift-label {
    background: #f8d7da;
    border-color: #dc3545;
    cursor: not-allowed;
    opacity: 0.6;
}

.shift-time {
    font-size: 14px;
    color: #666;
    margin-top: 5px;
}

.price-display {
    font-size: 28px;
    color: #28a745;
    font-weight: bold;
    text-align: center;
    padding: 20px;
    background: #f8f9fa;
    border-radius: 8px;
    margin: 20px 0;
}
</style>
@endpush

@section('content')
<div class="booking-container">
    <div class="container">
        <div style="max-width: 900px; margin: 0 auto;">
            <h1 style="margin-bottom: 30px; text-align: center;">Đặt Sân: {{ $field->name }}</h1>

            @if($errors->any())
            <div class="alert alert-danger">
                <ul style="margin: 0; padding-left: 20px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <div class="info-card">
                <h3>📍 Thông tin sân</h3>
                <p><strong>Địa chỉ:</strong> {{ $field->address }}</p>
                <p><strong>Giá:</strong> <span style="color: #28a745; font-size: 18px; font-weight: bold;">{{ number_format($field->price_per_hour) }}đ/giờ</span></p>
                <p style="color: #666; font-size: 14px;">💡 Mỗi ca = 2 tiếng = {{ number_format($field->price_per_hour * 2) }}đ</p>
            </div>

            <div class="info-card">
                <form action="{{ route('bookings.store') }}" method="POST" id="bookingForm">
                    @csrf
                    <input type="hidden" name="field_id" value="{{ $field->id }}">

                    <div class="form-group">
                        <label class="form-label"><strong>📅 Chọn ngày đặt sân *</strong></label>
                        <input type="date"
                               name="date"
                               id="dateInput"
                               class="form-control"
                               min="{{ date('Y-m-d') }}"
                               value="{{ old('date', request('date', date('Y-m-d'))) }}"
                               required>
                    </div>

                    <div class="form-group">
                        <label class="form-label"><strong>⏰ Chọn ca *</strong></label>
                        <div class="shift-grid" id="shiftGrid">
                            @php
                                $shifts = \App\Helpers\ShiftHelper::getShifts();
                                $selectedDate = old('date', request('date', date('Y-m-d')));
                                $bookedShifts = \App\Models\Booking::getBookedShifts($field->id, $selectedDate);
                            @endphp

                            @foreach($shifts as $shiftNum => $shiftInfo)
                            <div class="shift-option">
                                <input type="radio"
                                       name="shift"
                                       id="shift{{ $shiftNum }}"
                                       value="{{ $shiftNum }}"
                                       {{ in_array($shiftNum, $bookedShifts) ? 'disabled' : '' }}
                                       {{ old('shift') == $shiftNum ? 'checked' : '' }}>
                                <label for="shift{{ $shiftNum }}" class="shift-label">
                                    <div><strong>Ca {{ $shiftNum }}</strong></div>
                                    <div class="shift-time">{{ $shiftInfo['start'] }} - {{ $shiftInfo['end'] }}</div>
                                    @if(in_array($shiftNum, $bookedShifts))
                                        <div style="color: #dc3545; font-size: 12px; margin-top: 5px;">Đã đặt</div>
                                    @endif
                                </label>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="price-display">
                        Tổng tiền: {{ number_format($field->price_per_hour * 2) }}đ
                    </div>

                    {{-- Phương thức thanh toán --}}
                    <div style="margin-bottom:20px;">
                        <label style="display:block;font-weight:600;margin-bottom:10px;">Phương thức thanh toán</label>
                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                            <label style="border:2px solid #ddd;border-radius:8px;padding:14px;cursor:pointer;display:flex;align-items:center;gap:10px;" id="label-later">
                                <input type="radio" name="payment_method" value="later" checked onchange="updatePaymentLabel()">
                                <div>
                                    <div style="font-weight:600;">Thanh toán sau</div>
                                    <div style="font-size:12px;color:#666;">Thanh toán trực tiếp tại sân</div>
                                </div>
                            </label>
                            <label style="border:2px solid #ddd;border-radius:8px;padding:14px;cursor:pointer;display:flex;align-items:center;gap:10px;" id="label-online">
                                <input type="radio" name="payment_method" value="online" onchange="updatePaymentLabel()">
                                <div>
                                    <div style="font-weight:600;">Thanh toán online</div>
                                    <div style="font-size:12px;color:#666;">Qua SePay - xác nhận ngay</div>
                                </div>
                            </label>
                        </div>
                    </div>

                    <button type="submit" id="submit-btn" class="btn btn-primary" style="width: 100%; padding: 15px; font-size: 18px;">
                        Xác nhận đặt sân
                    </button>

                    <a href="{{ route('fields.show', $field->id) }}" class="btn btn-secondary" style="width: 100%; padding: 15px; font-size: 18px; margin-top: 10px; text-align: center; display: block; text-decoration: none;">
                        ← Quay lại
                    </a>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Tự động load lại ca khi đổi ngày
document.getElementById('dateInput').addEventListener('change', function() {
    const date = this.value;
    const fieldId = {{ $field->id }};

    // Reload trang với ngày mới
    window.location.href = `{{ route('bookings.create', $field->id) }}?date=${date}`;
});
</script>
@endpush

@push('scripts')
<script>
function updatePaymentLabel() {
    var later = document.querySelector('input[value="later"]').checked;
    document.getElementById('label-later').style.borderColor = later ? '#28a745' : '#ddd';
    document.getElementById('label-online').style.borderColor = !later ? '#28a745' : '#ddd';
    document.getElementById('submit-btn').textContent = later ? 'Xác nhận đặt sân' : 'Tiếp tục thanh toán';
}
updatePaymentLabel();

// Validate shift before submit
document.querySelector('form').addEventListener('submit', function(e) {
    var shiftSelected = document.querySelector('input[name="shift"]:checked');
    if (!shiftSelected) {
        e.preventDefault();
        alert('Vui lòng chọn ca đặt sân.');
    }
});
</script>
@endpush
@endsection
