@extends('layouts.app')

@section('title', 'Quản lý Owners')

@section('content')
<section class="section">
    <div class="container">
        <div style="margin-bottom:30px;">
            <h1 style="font-size:24px; margin-bottom:12px;">Quản Lý Chủ Sân</h1>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary" style="white-space:nowrap;">← Dashboard</a>
        </div>

        @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form method="GET" action="{{ route('admin.owners.index') }}" style="display:flex; gap:12px; margin-bottom:24px;">
            <input type="text" name="search" value="{{ request('search') }}"
                placeholder="Tìm theo tên, email hoặc SĐT..."
                class="form-control" style="max-width:320px;">
            <button type="submit" class="btn btn-primary">Tìm kiếm</button>
            @if(request('search'))
                <a href="{{ route('admin.owners.index') }}" class="btn btn-secondary">Xóa bộ lọc</a>
            @endif
        </form>

        @if($pendingRequests->count() > 0)
        <div class="card" style="margin-bottom: 30px; border-left: 4px solid #f0ad4e;">
            <div style="display:flex; align-items:center; gap:10px; margin-bottom:16px;">
                <h2 style="margin:0; font-size:18px;">Đơn đăng ký chờ duyệt</h2>
                <span style="background:#f0ad4e; color:#fff; border-radius:20px; padding:2px 10px; font-size:13px; font-weight:600;">{{ $pendingRequests->count() }}</span>
            </div>
            <div style="overflow-x: auto; -webkit-overflow-scrolling: touch;">
            <table class="table" style="min-width: 700px;">
                <thead>
                    <tr>
                        <th>Tên</th>
                        <th>Email</th>
                        <th>SĐT</th>
                        <th>Ghi chú</th>
                        <th>CCCD</th>
                        <th>MST</th>
                        <th>GPKD</th>
                        <th>Ngày gửi</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pendingRequests as $user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->phone ?? 'N/A' }}</td>
                        <td style="max-width:160px; font-size:13px; color:#666;">{{ $user->owner_request_note ?: '—' }}</td>
                        <td>
                            @php $hasAny = $user->id_card_image || $user->id_card_back_image || $user->id_card_selfie_image; @endphp
                            @if($hasAny)
                                <div style="display:flex; flex-direction:column; gap:4px;">
                                    @foreach(['id_card_image' => 'Trước', 'id_card_back_image' => 'Sau', 'id_card_selfie_image' => 'Mặt+CCCD'] as $field => $label)
                                        @if($user->$field)
                                        <a href="{{ storage_url($user->$field) }}" target="_blank" style="font-size:11px; color:#007bff;">
                                            <img src="{{ storage_url($user->$field) }}" style="width:56px; height:36px; object-fit:cover; border-radius:3px; display:block;">
                                            {{ $label }}
                                        </a>
                                        @endif
                                    @endforeach
                                </div>
                            @else
                                <span style="color:#999; font-size:13px;">Không có</span>
                            @endif
                        </td>
                        <td style="font-size:13px;">{{ $user->tax_number ?: '—' }}</td>
                        <td>
                            @if($user->business_license_image)
                            <a href="{{ storage_url($user->business_license_image) }}" target="_blank">
                                <img src="{{ storage_url($user->business_license_image) }}" style="width:56px; height:36px; object-fit:cover; border-radius:3px;">
                            </a>
                            @else
                            <span style="color:#999; font-size:13px;">Không có</span>
                            @endif
                        </td>
                        <td style="font-size:13px;">{{ $user->updated_at->format('d/m/Y H:i') }}</td>
                        <td>
                            <div style="display:flex; flex-direction:column; gap:6px; min-width:80px;">
                                <form action="{{ route('admin.owners.approve', $user->id) }}" method="POST" style="margin:0;">
                                    @csrf
                                    <button type="submit" class="btn btn-primary" style="width:100%; padding:6px 0; font-size:13px;">Duyệt</button>
                                </form>
                                <form action="{{ route('admin.owners.reject', $user->id) }}" method="POST" style="margin:0;">
                                    @csrf
                                    <button type="submit" class="btn btn-danger" style="width:100%; padding:6px 0; font-size:13px;">Từ chối</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            </div>
        </div>
        @endif

        @if($owners->count() > 0)
        <div class="card">
            <h2 style="font-size:18px; margin-bottom:16px;">Danh sách chủ sân</h2>
            <div style="overflow-x: auto; -webkit-overflow-scrolling: touch;">
            <table class="table" style="min-width: 700px;">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tên</th>
                        <th>Email</th>
                        <th>SĐT</th>
                        <th>Số sân</th>
                        <th>CCCD</th>
                        <th>MST</th>
                        <th>GPKD</th>
                        <th>Ngày đăng ký</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($owners as $owner)
                    <tr>
                        <td>{{ $owner->id }}</td>
                        <td>{{ $owner->name }}</td>
                        <td>{{ $owner->email }}</td>
                        <td>{{ $owner->phone ?? 'N/A' }}</td>
                        <td>{{ $owner->fields_count }}</td>
                        <td>
                            @php $hasAny = $owner->id_card_image || $owner->id_card_back_image || $owner->id_card_selfie_image; @endphp
                            @if($hasAny)
                                <div style="display:flex; flex-direction:column; gap:4px;">
                                    @foreach(['id_card_image' => 'Trước', 'id_card_back_image' => 'Sau', 'id_card_selfie_image' => 'Mặt+CCCD'] as $field => $label)
                                        @if($owner->$field)
                                        <a href="{{ storage_url($owner->$field) }}" target="_blank" style="font-size:11px; color:#007bff;">
                                            <img src="{{ storage_url($owner->$field) }}" style="width:56px; height:36px; object-fit:cover; border-radius:3px; display:block;">
                                            {{ $label }}
                                        </a>
                                        @endif
                                    @endforeach
                                </div>
                            @else
                                <span style="color:#999; font-size:13px;">—</span>
                            @endif
                        </td>
                        <td style="font-size:13px;">{{ $owner->tax_number ?: '—' }}</td>
                        <td>
                            @if($owner->business_license_image)
                            <a href="{{ storage_url($owner->business_license_image) }}" target="_blank">
                                <img src="{{ storage_url($owner->business_license_image) }}" style="width:56px; height:36px; object-fit:cover; border-radius:3px;">
                            </a>
                            @else
                            <span style="color:#999; font-size:13px;">—</span>
                            @endif
                        </td>
                        <td style="font-size:13px;">{{ $owner->created_at->format('d/m/Y') }}</td>
                        <td>
                            <div style="display:flex; gap:6px; align-items:center;">
                                <form action="{{ route('admin.users.destroy', $owner->id) }}" method="POST"
                                      onsubmit="return confirm('Bạn có chắc muốn xóa owner này?')" style="margin:0;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" style="padding:5px 14px; font-size:13px; height:34px; line-height:1;">Xóa</button>
                                </form>
                                <a href="{{ route('admin.owners.show', $owner->id) }}" class="btn btn-primary" style="padding:5px 14px; font-size:13px; height:34px; line-height:24px; display:inline-block;">Xem</a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            </div>
        </div>

        <div style="margin-top: 20px;">
            {{ $owners->links() }}
        </div>
        @else
        <div class="card">
            <p style="text-align: center; padding: 40px;">Chưa có owner nào.</p>
        </div>
        @endif
    </div>
</section>
@endsection
