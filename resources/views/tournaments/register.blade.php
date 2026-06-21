@extends('layouts.app')

@section('title', 'Đăng ký giải đấu')

@push('styles')
<style>
.register-page {
    padding: 40px 0;
    min-height: 70vh;
}

.register-container {
    max-width: 700px;
    margin: 0 auto;
}

.register-header {
    text-align: center;
    margin-bottom: 30px;
}

.register-header h1 {
    font-size: 32px;
    margin-bottom: 10px;
    color: #333;
}

.register-header p {
    color: #666;
    font-size: 16px;
}

.tournament-info-box {
    background: linear-gradient(135deg, #1e7e34 0%, #28a745 100%);
    color: white;
    padding: 25px;
    border-radius: 12px;
    margin-bottom: 30px;
}

.tournament-info-box h2 {
    margin: 0 0 15px 0;
    font-size: 24px;
}

.tournament-info-box p {
    margin: 5px 0;
    font-size: 15px;
}

.register-form {
    background: white;
    padding: 35px;
    border-radius: 12px;
    box-shadow: 0 2px 15px rgba(0,0,0,0.1);
}

.form-group {
    margin-bottom: 25px;
}

.form-label {
    display: block;
    margin-bottom: 8px;
    font-weight: 600;
    color: #333;
    font-size: 15px;
}

.form-label .required {
    color: #dc3545;
}

.form-control {
    width: 100%;
    padding: 12px 15px;
    border: 2px solid #ddd;
    border-radius: 8px;
    font-size: 15px;
    transition: border-color 0.3s;
}

.form-control:focus {
    outline: none;
    border-color: #28a745;
}

textarea.form-control {
    resize: vertical;
    min-height: 120px;
}

.form-help {
    font-size: 13px;
    color: #666;
    margin-top: 5px;
}

.form-actions {
    display: flex;
    gap: 15px;
    margin-top: 30px;
}

.btn {
    flex: 1;
    padding: 14px 30px;
    border-radius: 8px;
    font-weight: 600;
    font-size: 16px;
    text-align: center;
    text-decoration: none;
    border: none;
    cursor: pointer;
    transition: all 0.3s;
}

.btn-primary {
    background: #28a745;
    color: white;
}

.btn-primary:hover {
    background: #1e7e34;
}

.btn-secondary {
    background: #6c757d;
    color: white;
}

.btn-secondary:hover {
    background: #5a6268;
}
</style>
@endpush

@section('content')
<div class="register-page">
    <div class="container">
        <div class="register-container">
            <div class="register-header">
                <h1>Đăng ký tham gia giải đấu</h1>
                <p>Điền thông tin đội của bạn để tham gia</p>
            </div>

            <div class="tournament-info-box">
                <h2>{{ $tournament->name }}</h2>
                <p>Sân: {{ $tournament->field->name }}</p>
                <p>Thời gian: {{ $tournament->start_date->format('d/m/Y') }} - {{ $tournament->end_date->format('d/m/Y') }}</p>
                <p>Số người/đội: {{ $tournament->players_per_team }} người</p>
                <p>Phí tham gia: {{ number_format($tournament->entry_fee) }}đ/đội</p>
            </div>

            @if($errors->any())
            <div class="alert alert-danger" style="margin-bottom: 20px;">
                <ul style="margin: 0; padding-left: 20px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <div class="register-form">
                <form action="{{ route('tournaments.register.store', $tournament->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="form-group">
                        <label class="form-label">Tên đội <span class="required">*</span></label>
                        <input type="text" name="team_name" class="form-control" value="{{ old('team_name') }}"
                               placeholder="VD: FC Thành Công" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Tên đội trưởng <span class="required">*</span></label>
                        <input type="text" name="captain_name" class="form-control" value="{{ old('captain_name') }}"
                               placeholder="VD: Nguyễn Văn A" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Số điện thoại <span class="required">*</span></label>
                        <input type="tel" name="phone" class="form-control" value="{{ old('phone') }}"
                               placeholder="VD: 0123456789"
                               pattern="[0-9]{10}"
                               maxlength="10"
                               oninput="this.value=this.value.replace(/[^0-9]/,'')"
                               required>
                        <div class="form-help">Nhập đúng 10 chữ số, không trùng với đội đã đăng ký</div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Danh sách cầu thủ <span class="required">*</span></label>
                        <textarea name="players_list" class="form-control" required
                                  placeholder="Nhập danh sách cầu thủ (mỗi người một dòng)&#10;VD:&#10;1. Nguyễn Văn A&#10;2. Trần Văn B&#10;3. Lê Văn C">{{ old('players_list') }}</textarea>
                        <div class="form-help">Nhập tên các cầu thủ trong đội ({{ $tournament->players_per_team }} người)</div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Logo đội</label>
                        <input type="file" name="logo" class="form-control" accept="image/*">
                        <div class="form-help">Định dạng: JPG, PNG. Tối đa 2MB</div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Phương thức thanh toán <span class="required">*</span></label>
                        <div style="display: flex; gap: 15px; margin-top: 8px;">
                            <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; padding: 14px 20px; border: 2px solid #ddd; border-radius: 8px; flex: 1; transition: all 0.2s;" id="label-direct">
                                <input type="radio" name="payment_method" value="direct"
                                    {{ old('payment_method', 'direct') === 'direct' ? 'checked' : '' }}
                                    onchange="highlightPayment()"
                                    required>
                                <span>
                                    <strong>Thanh toán trực tiếp</strong><br>
                                    <small style="color:#666;">Nộp tiền trực tiếp tại sân</small>
                                </span>
                            </label>
                            <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; padding: 14px 20px; border: 2px solid #ddd; border-radius: 8px; flex: 1; transition: all 0.2s;" id="label-sepay">
                                <input type="radio" name="payment_method" value="sepay"
                                    {{ old('payment_method') === 'sepay' ? 'checked' : '' }}
                                    onchange="highlightPayment()">
                                <span>
                                    <strong>Thanh toán SePay</strong><br>
                                    <small style="color:#666;">Chuyển khoản trực tuyến</small>
                                </span>
                            </label>
                        </div>
                    </div>

                    <div class="form-actions">
                        <a href="{{ route('tournaments.show', $tournament->id) }}" class="btn btn-secondary">Hủy</a>
                        <button type="submit" class="btn btn-primary">Đăng ký</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function highlightPayment() {
    const direct = document.querySelector('input[value="direct"]');
    const sepay  = document.querySelector('input[value="sepay"]');
    document.getElementById('label-direct').style.borderColor = direct.checked ? '#28a745' : '#ddd';
    document.getElementById('label-direct').style.background  = direct.checked ? '#f0fff4' : 'white';
    document.getElementById('label-sepay').style.borderColor  = sepay.checked  ? '#28a745' : '#ddd';
    document.getElementById('label-sepay').style.background   = sepay.checked  ? '#f0fff4' : 'white';
}
document.addEventListener('DOMContentLoaded', highlightPayment);
</script>
@endsection
