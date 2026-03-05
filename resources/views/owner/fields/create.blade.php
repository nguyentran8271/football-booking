@extends('layouts.app')

@section('title', 'Thêm sân mới')

@push('styles')
<style>
.form-container {
    padding: 30px 0;
}

.form-card {
    max-width: 800px;
    margin: 0 auto;
    background: white;
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.form-group {
    margin-bottom: 20px;
}

.form-label {
    display: block;
    margin-bottom: 8px;
    font-weight: 600;
    color: #333;
}

.form-label .required {
    color: #dc3545;
}

.form-control {
    width: 100%;
    padding: 12px;
    border: 1px solid #ddd;
    border-radius: 5px;
    font-size: 15px;
}

.form-control:focus {
    outline: none;
    border-color: #28a745;
}

textarea.form-control {
    resize: vertical;
    min-height: 100px;
}

.form-actions {
    display: flex;
    gap: 15px;
    margin-top: 30px;
}

.form-actions .btn {
    flex: 1;
}

.address-suggestions {
    display: none;
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    background: white;
    border: 1px solid #ddd;
    border-top: none;
    border-radius: 0 0 5px 5px;
    max-height: 250px;
    overflow-y: auto;
    z-index: 1000;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
}

.address-suggestion-item {
    padding: 12px 15px;
    cursor: pointer;
    border-bottom: 1px solid #f0f0f0;
    transition: background 0.2s;
}

.address-suggestion-item:hover {
    background: #f8f9fa;
}

.address-suggestion-item:last-child {
    border-bottom: none;
}
</style>
@endpush

@section('content')
<div class="form-container">
    <div class="container">
        <h1 style="text-align: center; margin-bottom: 30px;">Thêm Sân Mới</h1>

        @if($errors->any())
        <div class="alert alert-danger" style="max-width: 800px; margin: 0 auto 20px;">
            <ul style="margin: 0; padding-left: 20px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <div class="form-card">
            <form action="{{ route('owner.fields.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="form-group">
                    <label class="form-label">Tên sân <span class="required">*</span></label>
                    <input type="text" name="name" class="form-control" value="{{ old('name') }}"
                           placeholder="VD: Sân bóng Thành Công" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Tỉnh/Thành phố <span class="required">*</span></label>
                    <div style="position: relative;">
                        <input type="text" id="province-search" class="form-control"
                               placeholder="Chọn hoặc tìm kiếm tỉnh/thành phố..." autocomplete="off">
                        <div id="province-suggestions" class="address-suggestions"></div>
                    </div>
                    <input type="hidden" name="province" id="province-input" value="{{ old('province') }}" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Phường/Xã/Thị trấn <span class="required">*</span></label>
                    <div style="position: relative;">
                        <input type="text" id="ward-search" class="form-control"
                               placeholder="Chọn tỉnh/thành phố trước..." autocomplete="off" disabled>
                        <div id="ward-suggestions" class="address-suggestions"></div>
                    </div>
                    <input type="hidden" name="ward" id="ward-input" value="{{ old('ward') }}" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Địa chỉ chi tiết <span class="required">*</span></label>
                    <input type="text" name="address_detail" class="form-control" value="{{ old('address_detail') }}"
                           placeholder="VD: Số 123, Đường Nguyễn Văn A" required>
                    <small style="color: #666;">💡 Nhập số nhà, tên đường cụ thể</small>
                </div>

                <div class="form-group">
                    <label class="form-label">Giá mỗi giờ (VNĐ) <span class="required">*</span></label>
                    <input type="number" name="price_per_hour" class="form-control" value="{{ old('price_per_hour') }}"
                           min="0" step="1000" placeholder="VD: 200000" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Số điện thoại liên hệ</label>
                    <input type="text" name="hotline" class="form-control" value="{{ old('hotline') }}"
                           placeholder="VD: 0123456789" maxlength="10">
                </div>

                <div class="form-group">
                    <label class="form-label">Mô tả</label>
                    <textarea name="description" class="form-control"
                              placeholder="Mô tả về sân bóng, tiện nghi, đặc điểm...">{{ old('description') }}</textarea>
                </div>

                <div class="form-group">
                    <label class="form-label">Trạng thái <span class="required">*</span></label>
                    <select name="status" class="form-control" required>
                        <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>✓ Hoạt động</option>
                        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>✕ Tạm ngưng</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Hình ảnh sân</label>
                    <input type="file" name="image" class="form-control" accept="image/*">
                    <small style="color: #666;">Định dạng: JPG, PNG, GIF. Tối đa 2MB</small>
                </div>

                <div class="form-actions">
                    <a href="{{ route('owner.fields.index') }}" class="btn btn-secondary">← Hủy</a>
                    <button type="submit" class="btn btn-primary">✓ Thêm sân</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection


@push('scripts')
<script>
// API tỉnh/thành phố và phường/xã Việt Nam (2025 - v2)
const API_BASE = 'https://provinces.open-api.vn/api/v2';

let provinces = [];
let wards = [];

// Load danh sách tỉnh/thành phố
async function loadProvinces() {
    try {
        const response = await fetch(`${API_BASE}/p/`);
        provinces = await response.json();
    } catch (error) {
        console.error('Lỗi tải danh sách tỉnh:', error);
    }
}

// Load danh sách phường/xã theo tỉnh
async function loadWards(provinceCode) {
    try {
        const response = await fetch(`${API_BASE}/w/?province=${provinceCode}`);
        wards = await response.json();
        return wards;
    } catch (error) {
        console.error('Lỗi tải danh sách phường/xã:', error);
        return [];
    }
}

// Hiển thị danh sách (có tìm kiếm)
function showList(inputId, suggestionsId, items, onSelect) {
    const input = document.getElementById(inputId);
    const suggestionsDiv = document.getElementById(suggestionsId);
    const searchText = input.value.toLowerCase().trim();

    // Lọc theo từ khóa tìm kiếm
    const filtered = searchText
        ? items.filter(item =>
            item.name.toLowerCase().includes(searchText) ||
            (item.codename && item.codename.toLowerCase().includes(searchText))
          )
        : items;

    if (filtered.length === 0) {
        suggestionsDiv.innerHTML = '<div class="address-suggestion-item" style="color: #999; cursor: default;">Không tìm thấy kết quả</div>';
        suggestionsDiv.style.display = 'block';
        return;
    }

    suggestionsDiv.innerHTML = filtered.map(item =>
        `<div class="address-suggestion-item" data-code="${item.code}" data-name="${item.name}">
            <div style="font-weight: 600;">${item.name}</div>
            ${item.division_type ? `<div style="font-size: 12px; color: #999;">${item.division_type}</div>` : ''}
        </div>`
    ).join('');

    suggestionsDiv.style.display = 'block';

    // Xử lý click chọn
    suggestionsDiv.querySelectorAll('.address-suggestion-item[data-code]').forEach(el => {
        el.onclick = () => onSelect(el.dataset.code, el.dataset.name);
    });
}

// Khởi tạo
document.addEventListener('DOMContentLoaded', async () => {
    await loadProvinces();

    const provinceSearch = document.getElementById('province-search');
    const provinceSuggestions = document.getElementById('province-suggestions');

    // Click vào ô tỉnh - hiện tất cả
    provinceSearch.addEventListener('focus', () => {
        showList('province-search', 'province-suggestions', provinces, async (code, name) => {
            provinceSearch.value = name;
            document.getElementById('province-input').value = name;
            provinceSuggestions.style.display = 'none';

            // Reset phường/xã
            document.getElementById('ward-search').value = '';
            document.getElementById('ward-input').value = '';

            // Load phường/xã
            wards = [];
            const wardSearch = document.getElementById('ward-search');
            wardSearch.disabled = true;
            wardSearch.placeholder = 'Đang tải...';

            await loadWards(code);

            if (wards.length > 0) {
                wardSearch.disabled = false;
                wardSearch.placeholder = 'Chọn hoặc tìm kiếm phường/xã/thị trấn...';
            } else {
                wardSearch.placeholder = 'Không có dữ liệu';
            }
        });
    });

    // Gõ để tìm kiếm tỉnh
    provinceSearch.addEventListener('input', () => {
        showList('province-search', 'province-suggestions', provinces, async (code, name) => {
            provinceSearch.value = name;
            document.getElementById('province-input').value = name;
            provinceSuggestions.style.display = 'none';

            // Reset phường/xã
            document.getElementById('ward-search').value = '';
            document.getElementById('ward-input').value = '';

            // Load phường/xã
            wards = [];
            const wardSearch = document.getElementById('ward-search');
            wardSearch.disabled = true;
            wardSearch.placeholder = 'Đang tải...';

            await loadWards(code);

            if (wards.length > 0) {
                wardSearch.disabled = false;
                wardSearch.placeholder = 'Chọn hoặc tìm kiếm phường/xã/thị trấn...';
            } else {
                wardSearch.placeholder = 'Không có dữ liệu';
            }
        });
    });

    const wardSearch = document.getElementById('ward-search');
    const wardSuggestions = document.getElementById('ward-suggestions');

    // Click vào ô phường/xã - hiện tất cả
    wardSearch.addEventListener('focus', () => {
        if (!wardSearch.disabled) {
            showList('ward-search', 'ward-suggestions', wards, (code, name) => {
                wardSearch.value = name;
                document.getElementById('ward-input').value = name;
                wardSuggestions.style.display = 'none';
            });
        }
    });

    // Gõ để tìm kiếm phường/xã
    wardSearch.addEventListener('input', () => {
        if (!wardSearch.disabled) {
            showList('ward-search', 'ward-suggestions', wards, (code, name) => {
                wardSearch.value = name;
                document.getElementById('ward-input').value = name;
                wardSuggestions.style.display = 'none';
            });
        }
    });

    // Ẩn dropdown khi click bên ngoài
    document.addEventListener('click', (e) => {
        if (!e.target.closest('.form-group')) {
            document.querySelectorAll('.address-suggestions').forEach(el => {
                el.style.display = 'none';
            });
        }
    });
});
</script>
@endpush
