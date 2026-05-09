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
                <ul class="nav-menu" id="nav-menu">
                    <li><a href="{{ route('home') }}">Trang chủ</a></li>
                    <li><a href="{{ route('fields.index') }}">Đặt sân</a></li>
                    <li><a href="{{ route('about') }}">Giới thiệu</a></li>
                    <li><a href="{{ route('reviews.index') }}">Đánh giá</a></li>
                    <li><a href="{{ route('for-owners') }}">Dành cho chủ sân</a></li>
                    @auth
                    <li class="mobile-only">
                        @if(auth()->user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}">Admin Dashboard</a>
                        @elseif(auth()->user()->isOwner())
                            <a href="{{ route('owner.dashboard') }}">Quản lý</a>
                        @else
                            <a href="{{ route('bookings.history') }}">Lịch sử đặt sân</a>
                        @endif
                    </li>
                    <li class="mobile-only"><a href="{{ route('profile.edit') }}">Thông tin cá nhân</a></li>
                    <li class="mobile-only">
                        <form action="{{ route('logout') }}" method="POST" style="margin:0;">
                            @csrf
                            <button type="submit" style="background:none;border:none;color:#ffcdd2;font-size:15px;cursor:pointer;padding:12px 20px;width:100%;text-align:left;font-weight:500;border-top:1px solid rgba(255,255,255,0.1);">Đăng xuất</button>
                        </form>
                    </li>
                    @else
                    <li class="mobile-only"><a href="{{ route('login') }}">Đăng nhập</a></li>
                    <li class="mobile-only"><a href="{{ route('register') }}">Đăng ký</a></li>
                    @endauth
                </ul>
            </nav>

            <button class="mobile-menu-toggle" id="mobile-toggle" onclick="toggleMobileMenu()" aria-label="Menu">
                <span></span><span></span><span></span>
            </button>

            <div class="auth-buttons" id="auth-buttons-mobile">
                @auth
                    @php
                        $bellUnread = 0;
                        $bellRole = auth()->user()->role;
                        $bellNotifs = [];
                        try {
                            if($bellRole === 'user') {
                                $bellUnread = \App\Models\Booking::where('user_id', auth()->id())
                                    ->where('user_notified', false)
                                    ->whereIn('status', ['approved', 'cancelled'])->count();
                                $bellNotifs = \App\Models\Booking::where('user_id', auth()->id())
                                    ->whereIn('status', ['approved', 'cancelled'])
                                    ->with('field')->orderBy('updated_at','desc')->limit(10)->get();
                            } elseif($bellRole === 'owner') {
                                $ownerFids = auth()->user()->fields()->pluck('id');
                                $bellUnread = \App\Models\Booking::whereIn('field_id', $ownerFids)->where('status','pending')->where('is_read',false)->count()
                                           + \App\Models\Review::whereIn('field_id', $ownerFids)->where('is_read',false)->count();
                                $bellNotifs = [
                                    'bookings' => \App\Models\Booking::whereIn('field_id', $ownerFids)->where('status','pending')->with(['field','user'])->orderBy('created_at','desc')->limit(10)->get(),
                                    'reviews'  => \App\Models\Review::whereIn('field_id', $ownerFids)->with(['user','field'])->orderBy('created_at','desc')->limit(10)->get(),
                                ];
                            } elseif($bellRole === 'admin') {
                                $bellUnread = \App\Models\Review::whereNull('field_id')->where('is_read',false)->count();
                                $expiredCount = \App\Models\User::where('role','owner')->whereNotNull('subscription_expires_at')->where('subscription_expires_at','<',now())->count();
                                $bellUnread += $expiredCount;
                                $bellNotifs = [
                                    'reviews'        => \App\Models\Review::whereNull('field_id')->with('user')->orderBy('created_at','desc')->limit(10)->get(),
                                    'owner_requests' => \App\Models\User::where('owner_request','pending')->orderBy('updated_at','desc')->limit(10)->get(),
                                    'expired_owners' => \App\Models\User::where('role','owner')->whereNotNull('subscription_expires_at')->where('subscription_expires_at','<',now())->orderBy('subscription_expires_at','asc')->limit(5)->get(),
                                ];
                            }
                        } catch(\Exception $e) {}
                    @endphp
                    <div style="position:relative;display:inline-block;margin-right:8px;">
                        <button id="bell-btn" onclick="toggleBell()" style="background:none;border:none;cursor:pointer;padding:6px;position:relative;font-size:20px;">
                            🔔
                            @if($bellUnread > 0)
                            <span id="bell-badge" style="position:absolute;top:0;right:0;background:#dc3545;color:#fff;border-radius:50%;width:16px;height:16px;font-size:10px;font-weight:700;display:flex;align-items:center;justify-content:center;line-height:1;">{{ $bellUnread > 9 ? '9+' : $bellUnread }}</span>
                            @endif
                        </button>
                        <div id="bell-dropdown" style="display:none;position:absolute;right:0;top:calc(100% + 4px);width:310px;background:#fff;border-radius:10px;box-shadow:0 4px 20px rgba(0,0,0,0.15);z-index:1001;overflow:hidden;max-height:380px;overflow-y:auto;">
                            <div style="padding:10px 14px;border-bottom:1px solid #eee;">
                                <strong style="font-size:13px;">Thông báo</strong>
                            </div>
                            @if($bellRole === 'user')
                                @forelse($bellNotifs as $b)
                                <a href="{{ route('bookings.history') }}" style="display:block;padding:10px 14px;border-bottom:1px solid #f0f0f0;background:{{ $b->user_notified ? '#fafafa' : ($b->status === 'approved' ? '#f0fff4' : '#fff5f5') }};text-decoration:none;color:inherit;">
                                    <div style="font-size:12px;font-weight:600;">{{ $b->status === 'approved' ? '✅ Booking được duyệt' : '❌ Booking bị từ chối' }}{{ $b->user_notified ? ' · Đã đọc' : '' }}</div>
                                    <div style="font-size:11px;color:#666;">{{ $b->field->name ?? '' }} - {{ $b->date->format('d/m/Y') }}</div>
                                    <div style="font-size:10px;color:#999;">{{ $b->updated_at->diffForHumans() }}</div>
                                </a>
                                @empty
                                <div style="padding:16px;text-align:center;color:#999;font-size:12px;">Không có thông báo</div>
                                @endforelse
                            @elseif($bellRole === 'owner')
                                @forelse($bellNotifs['bookings'] as $b)
                                <a href="{{ route('owner.bookings.index') }}" style="display:block;padding:10px 14px;border-bottom:1px solid #f0f0f0;background:{{ $b->is_read ? '#fafafa' : '#fff8f0' }};text-decoration:none;color:inherit;">
                                    <div style="font-size:12px;font-weight:600;">📅 Đặt sân mới{{ $b->is_read ? ' · Đã đọc' : '' }}</div>
                                    <div style="font-size:11px;color:#666;">{{ $b->user->name ?? '' }} - {{ $b->field->name ?? '' }}</div>
                                    <div style="font-size:10px;color:#999;">{{ $b->date->format('d/m/Y') }} Ca {{ $b->shift }} · {{ $b->created_at->diffForHumans() }}</div>
                                </a>
                                @empty
                                @endforelse
                                @forelse($bellNotifs['reviews'] as $r)
                                <a href="{{ route('owner.dashboard') }}" style="display:block;padding:10px 14px;border-bottom:1px solid #f0f0f0;background:{{ $r->is_read ? '#fafafa' : '#f0fff4' }};text-decoration:none;color:inherit;">
                                    <div style="font-size:12px;font-weight:600;">⭐ Đánh giá mới{{ $r->is_read ? ' · Đã đọc' : '' }}</div>
                                    <div style="font-size:11px;color:#666;">{{ $r->user->name ?? '' }} - {{ $r->field->name ?? '' }} {{ $r->rating }}/5</div>
                                    <div style="font-size:10px;color:#999;">{{ $r->created_at->diffForHumans() }}</div>
                                </a>
                                @empty
                                @endforelse
                                @if($bellNotifs['bookings']->isEmpty() && $bellNotifs['reviews']->isEmpty())
                                <div style="padding:16px;text-align:center;color:#999;font-size:12px;">Không có thông báo</div>
                                @endif
                            @elseif($bellRole === 'admin')
                                @forelse($bellNotifs['owner_requests'] as $u)
                                <a href="{{ route('admin.owners.index') }}" style="display:block;padding:10px 14px;border-bottom:1px solid #f0f0f0;background:#fff8f0;text-decoration:none;color:inherit;">
                                    <div style="font-size:12px;font-weight:600;">👤 Đăng ký chủ sân</div>
                                    <div style="font-size:11px;color:#666;">{{ $u->name }} ({{ $u->email }})</div>
                                    <div style="font-size:10px;color:#999;">{{ $u->updated_at->diffForHumans() }}</div>
                                </a>
                                @empty
                                @endforelse
                                @forelse($bellNotifs['reviews'] as $r)
                                <a href="{{ route('reviews.index') }}" style="display:block;padding:10px 14px;border-bottom:1px solid #f0f0f0;background:{{ $r->is_read ? '#fafafa' : '#f0fff4' }};text-decoration:none;color:inherit;">
                                    <div style="font-size:12px;font-weight:600;">⭐ Đánh giá website mới{{ $r->is_read ? ' · Đã đọc' : '' }}</div>
                                    <div style="font-size:11px;color:#666;">{{ $r->user->name ?? '' }} - {{ $r->rating }}/5 sao</div>
                                    <div style="font-size:10px;color:#999;">{{ $r->created_at->diffForHumans() }}</div>
                                </a>
                                @empty
                                @endforelse
                                @forelse($bellNotifs['expired_owners'] as $u)
                                <a href="{{ route('admin.owners.index') }}" style="display:block;padding:10px 14px;border-bottom:1px solid #f0f0f0;background:#fff3cd;text-decoration:none;color:inherit;">
                                    <div style="font-size:12px;font-weight:600;">⚠️ Chủ sân hết hạn</div>
                                    <div style="font-size:11px;color:#666;">{{ $u->name }} - hết hạn {{ $u->subscription_expires_at->format('d/m/Y') }}</div>
                                </a>
                                @empty
                                @endforelse
                                @if($bellNotifs['owner_requests']->isEmpty() && $bellNotifs['reviews']->isEmpty() && $bellNotifs['expired_owners']->isEmpty())
                                <div style="padding:16px;text-align:center;color:#999;font-size:12px;">Không có thông báo</div>
                                @endif
                            @endif
                        </div>
                    </div>
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
                                <a href="{{ route('owner.dashboard') }}" style="display:block; padding:10px 16px; color:#333; text-decoration:none; font-size:14px;" onmouseenter="this.style.background='#f8f9fa'" onmouseleave="this.style.background='white'">Quản lý</a>
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
function toggleBell() {
    var d = document.getElementById('bell-dropdown');
    var opening = d.style.display === 'none';
    d.style.display = opening ? 'block' : 'none';
    if (opening) {
        // Poll ngay khi mở để lấy data mới nhất
        if (window._pollNotifications) window._pollNotifications();
        // Mark-read sau 1s (sau khi đã hiển thị)
        setTimeout(function() {
            var token = (document.querySelector('meta[name="csrf-token"]') || {}).content || '';
            var opts = {method:'POST', credentials:'same-origin', headers:{'X-CSRF-TOKEN':token,'Content-Type':'application/json'}};
            var role = '{{ auth()->check() ? auth()->user()->role : "" }}';
            if (role === 'user') fetch('/notifications/mark-read', opts);
            else if (role === 'owner') { fetch('/owner/bookings-mark-read', opts); fetch('/owner/reviews-mark-read', opts); }
            else if (role === 'admin') fetch('/admin/reviews/mark-read', opts);
        }, 1000);
    }
}
document.addEventListener('click', function(e) {
    var dropdown = document.querySelector('.user-dropdown');
    if (dropdown && !dropdown.contains(e.target)) {
        var menu = document.getElementById('user-menu');
        if (menu) menu.style.display = 'none';
    }
    var bellBtn = document.getElementById('bell-btn');
    var bellDrop = document.getElementById('bell-dropdown');
    if (bellBtn && bellDrop && !bellBtn.contains(e.target) && !bellDrop.contains(e.target)) {
        bellDrop.style.display = 'none';
    }
});
function toggleMobileMenu() {
    var nav = document.getElementById('nav-menu');
    var btn = document.getElementById('mobile-toggle');
    nav.classList.toggle('open');
    btn.classList.toggle('active');
}
// Ensure bell dropdown is closed on page load
document.addEventListener('DOMContentLoaded', function() {
    var d = document.getElementById('bell-dropdown');
    if (d) d.style.display = 'none';

    // Force show auth-buttons on mobile
    if (window.innerWidth <= 768) {
        var ab = document.getElementById('auth-buttons-mobile');
        if (ab) {
            ab.style.display = 'flex';
            ab.style.gap = '4px';
            ab.style.alignItems = 'center';
            // Hide login/register links
            ab.querySelectorAll('a.btn').forEach(function(el) {
                el.style.display = 'none';
            });
        }
    }
});
// Also reset immediately
(function() {
    var d = document.getElementById('bell-dropdown');
    if (d) d.style.display = 'none';
})();

@auth
// Realtime notification polling every 30s
(function() {
    function renderItems(items) {
        var drop = document.getElementById('bell-dropdown');
        if (!drop) return;
        var header = drop.querySelector('div:first-child');
        // Remove old items
        while (drop.children.length > 1) drop.removeChild(drop.lastChild);
        if (!items || items.length === 0) {
            var empty = document.createElement('div');
            empty.style.cssText = 'padding:16px;text-align:center;color:#999;font-size:12px;';
            empty.textContent = 'Không có thông báo';
            drop.appendChild(empty);
            return;
        }
        items.forEach(function(item) {
            var a = document.createElement('a');
            a.href = item.url;
            a.style.cssText = 'display:block;padding:10px 14px;border-bottom:1px solid #f0f0f0;background:' + item.bg + ';text-decoration:none;color:inherit;';
            a.innerHTML = '<div style="font-size:12px;font-weight:600;">' + item.icon + ' ' + item.title + (item.is_read ? ' · Đã đọc' : '') + '</div>'
                + '<div style="font-size:11px;color:#666;">' + item.body + '</div>'
                + '<div style="font-size:10px;color:#999;">' + item.time + '</div>';
            drop.appendChild(a);
        });
    }

    function pollNotifications() {
        var controller = typeof AbortController !== 'undefined' ? new AbortController() : null;
        var timeout = controller ? setTimeout(function() { controller.abort(); }, 8000) : null;
        fetch('/api/notifications/poll', {
            credentials: 'same-origin',
            signal: controller ? controller.signal : undefined
        })
            .then(function(r) {
                if (timeout) clearTimeout(timeout);
                if (!r.ok) throw new Error('HTTP ' + r.status);
                return r.json();
            })
            .then(function(data) {
                var badge = document.getElementById('bell-badge');
                if (data.unread > 0) {
                    if (!badge) {
                        badge = document.createElement('span');
                        badge.id = 'bell-badge';
                        badge.style.cssText = 'position:absolute;top:0;right:0;background:#dc3545;color:#fff;border-radius:50%;width:16px;height:16px;font-size:10px;font-weight:700;display:flex;align-items:center;justify-content:center;line-height:1;';
                        var btn = document.getElementById('bell-btn');
                        if (btn) btn.appendChild(badge);
                    }
                    badge.textContent = data.unread > 9 ? '9+' : data.unread;
                    badge.style.display = 'flex';
                } else if (badge) {
                    badge.style.display = 'none';
                }
                var drop = document.getElementById('bell-dropdown');
                if (drop && drop.style.display !== 'none') {
                    renderItems(data.items);
                }
                window._bellItems = data.items;
            })
            .catch(function(e) {
                if (timeout) clearTimeout(timeout);
            });
    }

    window._pollNotifications = pollNotifications;
    pollNotifications();
    setInterval(pollNotifications, 15000);
})();
@endauth
</script>
