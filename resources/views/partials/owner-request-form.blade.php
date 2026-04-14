@if(session('success'))
<p style="color:#d4edda; margin-bottom:12px; font-size:14px;">✅ {{ session('success') }}</p>
@endif
@if($errors->any())
<div style="background:#f8d7da; color:#721c24; padding:10px 14px; border-radius:8px; margin-bottom:12px; font-size:13px;">
    @foreach($errors->all() as $error)
    <p style="margin:0 0 4px 0;">❌ {{ $error }}</p>
    @endforeach
</div>
@endif
<form action="{{ route('owner-request.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    @php
        $inputStyle  = "width:100%; padding:10px 12px; border-radius:8px; border:none; font-size:14px; background:#fff; box-sizing:border-box;";
        $labelStyle  = "display:block; color:#1a1a1a; font-size:13px; font-weight:600; margin-bottom:4px; margin-top:14px;";
        $hintStyle   = "display:block; color:#555; font-size:12px; margin-top:4px; margin-bottom:2px;";
    @endphp

    <label style="{{ $labelStyle }}">Mô tả về sân bóng của bạn</label>
    <small style="{{ $hintStyle }}">Mô tả ngắn về sân, vị trí, số lượng sân... (không bắt buộc)</small>
    <textarea name="note" placeholder="VD: Tôi có 3 sân cỏ nhân tạo tại quận 7, TP.HCM..." style="{{ $inputStyle }} resize:vertical; min-height:80px;"></textarea>

    <label style="{{ $labelStyle }}">CCCD mặt trước</label>
    <small style="{{ $hintStyle }}">Chụp rõ mặt trước CCCD, không bị mờ hay che khuất</small>
    <input type="file" name="id_card_image" accept="image/*" style="{{ $inputStyle }}">

    <label style="{{ $labelStyle }}">CCCD mặt sau</label>
    <small style="{{ $hintStyle }}">Chụp rõ mặt sau CCCD, hiển thị đầy đủ thông tin</small>
    <input type="file" name="id_card_back_image" accept="image/*" style="{{ $inputStyle }}">

    <label style="{{ $labelStyle }}">Ảnh mặt chụp kèm CCCD</label>
    <small style="{{ $hintStyle }}">Ảnh selfie cầm CCCD bên cạnh mặt để xác minh danh tính</small>
    <input type="file" name="id_card_selfie_image" accept="image/*" style="{{ $inputStyle }}">

    <label style="{{ $labelStyle }}">Mã số thuế</label>
    <small style="{{ $hintStyle }}">Mã số thuế cá nhân hoặc doanh nghiệp (10-13 chữ số)</small>
    <input type="text" name="tax_number" id="tax_number_input" placeholder="VD: 0123456789" style="{{ $inputStyle }}"
           pattern="\d{10,13}" title="Mã số thuế phải gồm 10-13 chữ số"
           oninput="this.value=this.value.replace(/\D/g,'').slice(0,13); validateTaxNumber(this);">
    <small id="tax_number_error" style="display:none; color:#e74c3c; font-size:12px; margin-top:3px;">Mã số thuế phải gồm 10-13 chữ số</small>

    <label style="{{ $labelStyle }}">Ảnh giấy phép kinh doanh</label>
    <small style="{{ $hintStyle }}">Giấy phép đăng ký kinh doanh còn hiệu lực (nếu có)</small>
    <input type="file" name="business_license_image" accept="image/*" style="{{ $inputStyle }} margin-bottom:4px;">

    <button type="submit" class="btn btn-cta" style="width:100%; margin-top:20px;">Gửi đơn đăng ký</button>
</form>
<script>
function validateTaxNumber(input) {
    var err = document.getElementById('tax_number_error');
    if (input.value.length > 0 && (input.value.length < 10 || input.value.length > 13)) {
        err.style.display = 'block';
        input.style.border = '2px solid #e74c3c';
    } else {
        err.style.display = 'none';
        input.style.border = 'none';
    }
}
</script>
