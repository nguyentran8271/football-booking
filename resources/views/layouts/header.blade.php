<header class="header">
    <div class="container">
        <div class="header-content">
            <div class="logo">
                @php
                    $logo = App\Models\SiteSetting::get('logo');
                    $siteName = App\Models\SiteSetting::get('site_name', 'Đặt Sân Bóng');
                @endphp
                @if($logo)
                    <img src="{{ asset('storage/' . $logo) }}" alt="{{ $siteName }}">
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
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-primary">Admin</a>
                    @elseif(auth()->user()->isOwner())
                        <a href="{{ route('owner.dashboard') }}" class="btn btn-primary">Quản lý</a>
                    @else
                        <a href="{{ route('bookings.history') }}" class="btn btn-primary">Lịch sử</a>
                    @endif
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-secondary">Đăng xuất</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="btn btn-outline">Đăng nhập</a>
                    <a href="{{ route('register') }}" class="btn btn-primary">Đăng ký</a>
                @endauth
            </div>
        </div>
    </div>
</header>
