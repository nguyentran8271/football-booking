<footer class="footer">
    <div class="container">
        <div class="footer-content">
            <div class="footer-section">
                @php
                    $logo = App\Models\SiteSetting::get('logo');
                    $siteName = App\Models\SiteSetting::get('site_name', 'Đặt Sân Bóng');
                @endphp
                <h3>{{ $siteName }}</h3>
                <p>{{ App\Models\SiteSetting::get('site_description', 'Hệ thống đặt sân bóng trực tuyến hàng đầu Việt Nam') }}</p>
            </div>

            <div class="footer-section">
                <h3>Liên hệ</h3>
                <p>Điện thoại: {{ App\Models\SiteSetting::get('site_phone', '0123456789') }}</p>
                <p>Email: {{ App\Models\SiteSetting::get('site_email', 'info@datsanbong.vn') }}</p>
                <p>Hotline: {{ App\Models\SiteSetting::get('site_hotline', '1900xxxx') }}</p>
            </div>

            <div class="footer-section">
                <h3>Địa chỉ</h3>
                <p>{{ App\Models\SiteSetting::get('site_address', 'Hà Nội, Việt Nam') }}</p>
            </div>

            <div class="footer-section">
                <h3>Liên kết</h3>
                <a href="{{ route('about') }}">Giới thiệu</a>
                <a href="{{ route('policy') }}">Chính sách</a>
                <a href="{{ route('for-owners') }}">Dành cho chủ sân</a>
            </div>
        </div>

        <div class="footer-bottom">
            <p>&copy; {{ date('Y') }} {{ $siteName }}. All rights reserved.</p>
        </div>
    </div>
</footer>
