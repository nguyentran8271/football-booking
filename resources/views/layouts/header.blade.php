<header class="header">
    <div class="container">
        <div class="header-content">
            <div class="logo">
                @php
                    $logo = App\Models\SiteSetting::get('logo');
                    $siteName = App\Models\SiteSetting::get('site_name', 'Đặt Sân Bóng');
                @endphp
                @if($logo)
                    <img src="{{ storage_url($logo) }}" alt="{{ $siteName }}">
                @else
                    {{ $siteName }}
                @endif
            </div>

            <div class="slogan">
                {{ App\Models\SiteSetting::get('site_slogan', 'Đặt sân nhanh - Chơi bóng vui') }}
            </div>

            <nav>
                <ul class="nav-menu">
                    <li><a href="{{ route('home') }}">Trang chủ</a></li>
                    <li><a href="{{ route('fields.index') }}">Đặt sân</a></li>
                    <li><a href="{{ route('about') }}">Giới thiệu</a></li>
                    <li><a href="{{ route('reviews.index') }}">Đánh giá</a></li>
                    <li><a href="{{ route('for-owners') }}">Dành cho chủ sân</a></li>
                </ul>
            </nav>

            <div class="auth-buttons">
                @auth
                    <div class="user-dropdown" style="position:relative; display:inline-block;">
                        <button class="user-avatar-btn" onclick="toggleUserMenu()" style="background:none; border:none; cursor:pointer; display:flex; align-items:center; gap:8px; padding:6px 12px; border-radius:8px; transition:background 0.2s;" onmouseenter="this.style.background='rgba(0,0,0,0.05)'" onmouseleave="this.style.background='none'">
                            <div style="width:36px; height:36px; border-radius:50%; background:#28a745; display:flex; align-items:center; justify-content:center; color:white; font-weight:600; font-size:15px;">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                            <span style="font-size:14px; font-weight:500; color:#333;">{{ Str::limit(auth()->user()->name, 15) }}</span>
                            <svg width="12" height="12" viewBox="0 0 12 12" fill="#666"><path d="M2 4l4 4 4-4"/></svg>
                        </button>
                        <div id="user-menu" style="display:none; position:absolute; right:0; top:calc(100% + 8px); background:white; border-radius:10px; box-shadow:0 4px 20px rgba(0,0,0,0.15); min-width:200px; z-index:1000; overflow:hidden;">
                            <div style="padding:12px 16px; border-bottom:1px solid #eee; background:#f8f9fa;">
                                <div style="font-weight:600; font-size:14px;">{{ auth()->user()->name }}</div>
                                <div style="font-size:12px; color:#999;">{{ auth()->user()->email }}</div>
                            </div>
                            @if(auth()->user()->isAdmin())
                                <a href="{{ route('admin.dashboard') }}" style="display:block; padding:10px 16px; color:#333; text-decoration:none; font-size:14px;" onmouseenter="this.style.background='#f8f9fa'" onmouseleave="this.style.background='white'">Admin Dashboard</a>
                            @elseif(auth()->user()->isOwner())
                                <a href="{{ route('owner.dashboard') }}" style="display:block; padding:10px 16px; color:#333; text-decoration:none; font-size:14px;" onmouseenter="this.style.background='#f8f9fa'" onmouseleave="this.style.background='white'">Quản lý sân</a>
                            @else
                                <a href="{{ route('bookings.history') }}" style="display:block; padding:10px 16px; color:#333; text-decoration:none; font-size:14px;" onmouseenter="this.style.background='#f8f9fa'" onmouseleave="this.style.background='white'">Lịch sử đặt sân</a>
                            @endif
                            <a href="{{ route('profile.edit') }}" style="display:block; padding:10px 16px; color:#333; text-decoration:none; font-size:14px;" onmouseenter="this.style.background='#f8f9fa'" onmouseleave="this.style.background='white'">Thông tin cá nhân</a>
                            <div style="border-top:1px solid #eee;">
                                <form action="{{ route('logout') }}" method="POST" style="margin:0;">
                                    @csrf
                                    <button type="submit" style="width:100%; padding:10px 16px; background:none; border:none; text-align:left; cursor:pointer; font-size:14px; color:#dc3545;" onmouseenter="this.style.background='#fff5f5'" onmouseleave="this.style.background='none'">Đăng xuất</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="btn btn-outline">Đăng nhập</a>
                    <a href="{{ route('register') }}" class="btn btn-primary">Đăng ký</a>
                @endauth
            </div>
        </div>
    </div>
</header>

<script>
function toggleUserMenu() {
    var menu = document.getElementById('user-menu');
    menu.style.display = menu.style.display === 'none' ? 'block' : 'none';
}
document.addEventListener('click', function(e) {
    var dropdown = document.querySelector('.user-dropdown');
    if (dropdown && !dropdown.contains(e.target)) {
        var menu = document.getElementById('user-menu');
        if (menu) menu.style.display = 'none';
    }
});
</script>
