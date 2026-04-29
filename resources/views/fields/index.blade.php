@extends('layouts.app')

@section('title', 'Danh sách sân bóng')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/home.css') }}">
<link rel="stylesheet" href="{{ asset('css/fields.css') }}">
@endpush

@section('content')
<section class="hero" style="height: 400px;@if(!$fieldsBanner) background: linear-gradient(135deg, #28a745 0%, #1a5c2e 100%);@endif">
    @if($fieldsBanner)
        <img src="{{ storage_url($fieldsBanner) }}" alt="Đặt sân" class="hero-slide active">
    @endif
    <div class="hero-content">
        <h1>{{ $fieldsTitle }}</h1>
        <p>{{ $fieldsDescription }}</p>
    </div>
</section>

<section class="section">
    <div class="container">
        <!-- Search Form -->
        <div class="card">
            <form action="{{ route('fields.index') }}" method="GET" id="searchForm">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 15px;">
                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label">Tỉnh/Thành phố</label>
                        <select name="province" id="provinceSelect" class="form-control">
                            <option value="">-- Tất cả tỉnh/thành phố --</option>
                        </select>
                    </div>
                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label">Xã/Phường</label>
                        <select name="ward" id="wardSelect" class="form-control" disabled>
                            <option value="">-- Chọn tỉnh trước --</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <input type="text"
                           name="search"
                           class="form-control"
                           placeholder="Tìm kiếm theo tên sân..."
                           value="{{ request('search') }}">
                </div>
                <button type="submit" class="btn btn-primary">Tìm kiếm</button>
                <button type="button" class="btn btn-secondary" onclick="resetFilters()">Xóa bộ lọc</button>
            </form>
        </div>

        <script>
        const API_BASE = 'https://provinces.open-api.vn/api/v2';

        // Load provinces on page load
        document.addEventListener('DOMContentLoaded', async function() {
            await loadProvinces();

            // Restore selected values if any
            const selectedProvince = '{{ request("province") }}';
            const selectedWard = '{{ request("ward") }}';

            if (selectedProvince) {
                document.getElementById('provinceSelect').value = selectedProvince;
                await loadWards(selectedProvince);
                if (selectedWard) {
                    document.getElementById('wardSelect').value = selectedWard;
                }
            }
        });

        async function loadProvinces() {
            try {
                const response = await fetch(`${API_BASE}/p/`);
                const provinces = await response.json();

                const select = document.getElementById('provinceSelect');
                provinces.forEach(province => {
                    const option = document.createElement('option');
                    option.value = province.name;
                    option.dataset.code = province.code;
                    option.textContent = province.name;
                    select.appendChild(option);
                });
            } catch (error) {
                console.error('Error loading provinces:', error);
            }
        }

        async function loadWards(provinceName) {
            const wardSelect = document.getElementById('wardSelect');
            wardSelect.innerHTML = '<option value="">-- Tất cả xã/phường --</option>';
            wardSelect.disabled = true;

            if (!provinceName) return;

            try {
                // Get province code from selected option
                const provinceSelect = document.getElementById('provinceSelect');
                const selectedOption = provinceSelect.options[provinceSelect.selectedIndex];
                const provinceCode = selectedOption.dataset.code;

                if (!provinceCode) return;

                // Load wards with depth=2 (after 2025 reform, wards are directly under provinces)
                const response = await fetch(`${API_BASE}/p/${provinceCode}?depth=2`);
                const data = await response.json();

                if (data.wards && data.wards.length > 0) {
                    data.wards.forEach(ward => {
                        const option = document.createElement('option');
                        option.value = ward.name;
                        option.textContent = ward.name;
                        wardSelect.appendChild(option);
                    });
                    wardSelect.disabled = false;
                }
            } catch (error) {
                console.error('Error loading wards:', error);
            }
        }

        document.getElementById('provinceSelect').addEventListener('change', function() {
            const provinceName = this.value;
            document.getElementById('wardSelect').value = '';
            loadWards(provinceName);
        });

        function resetFilters() {
            document.getElementById('provinceSelect').value = '';
            document.getElementById('wardSelect').value = '';
            document.getElementById('wardSelect').disabled = true;
            document.querySelector('input[name="search"]').value = '';
            document.getElementById('searchForm').submit();
        }
        </script>

        <!-- Fields Grid -->
        @if($fields->count() > 0)
        <div class="fields-grid">
            @foreach($fields as $field)
            <div class="field-card">
                <img src="{{ $field->image_url }}"
                     alt="{{ $field->name }}"
                     class="field-image">
                <div class="field-info">
                    <h3 class="field-name">{{ $field->name }}</h3>
                    <p class="field-address">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:inline-block;vertical-align:middle;margin-right:4px;color:#6c757d;flex-shrink:0;margin-top:-2px"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                        {{ $field->address }}
                    </p>
                    <div class="field-rating">
                        @php $avg = $field->averageRating(); @endphp
                        <span class="field-stars">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= floor($avg))
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="#ffc107" xmlns="http://www.w3.org/2000/svg"><polygon points="12,2 15.09,8.26 22,9.27 17,14.14 18.18,21.02 12,17.77 5.82,21.02 7,14.14 2,9.27 8.91,8.26"/></svg>
                                @else
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="#ddd" xmlns="http://www.w3.org/2000/svg"><polygon points="12,2 15.09,8.26 22,9.27 17,14.14 18.18,21.02 12,17.77 5.82,21.02 7,14.14 2,9.27 8.91,8.26"/></svg>
                                @endif
                            @endfor
                        </span>
                        <span style="color:#6c757d;font-size:13px;">{{ number_format($avg, 1) }} ({{ $field->reviews->count() }} đánh giá)</span>
                    </div>
                    <p class="field-price">{{ number_format($field->price_per_hour) }}đ/giờ</p>
                    <a href="{{ route('fields.show', $field->id) }}" class="btn btn-primary" style="width: 100%;">
                        Xem chi tiết
                    </a>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($fields->hasPages())
        <div class="pagination">
            <div style="display: flex; gap: 10px; align-items: center; justify-content: center;">
                @if($fields->onFirstPage())
                    <span style="padding: 8px 12px; color: #999;">« Trước</span>
                @else
                    <a href="{{ $fields->previousPageUrl() }}" style="padding: 8px 12px; background: white; border: 1px solid #ddd; border-radius: 5px; text-decoration: none; color: #333;">« Trước</a>
                @endif

                <span style="padding: 8px 12px;">
                    Trang {{ $fields->currentPage() }} / {{ $fields->lastPage() }}
                </span>

                @if($fields->hasMorePages())
                    <a href="{{ $fields->nextPageUrl() }}" style="padding: 8px 12px; background: white; border: 1px solid #ddd; border-radius: 5px; text-decoration: none; color: #333;">Sau »</a>
                @else
                    <span style="padding: 8px 12px; color: #999;">Sau »</span>
                @endif
            </div>
        </div>
        @endif
        @else
        <p style="text-align: center; padding: 40px;">Không tìm thấy sân nào.</p>
        @endif

        <!-- Tournaments Section -->
        @if($tournaments->count() > 0)
        <div style="margin-top: 60px;">
            <div style="text-align: center; margin-bottom: 40px;">
                <h2 style="font-size: 32px; margin-bottom: 10px;">Giải đấu đang mở</h2>
                <p style="color: #666;">Tham gia các giải đấu bóng đá chuyên nghiệp</p>
            </div>

            <!-- Tournament Search Form -->
            <div style="max-width: 800px; margin: 0 auto 40px; background: white; padding: 25px; border-radius: 12px; box-shadow: 0 2px 15px rgba(0,0,0,0.08);">
                <form method="GET" action="{{ route('fields.index') }}" class="tournament-search-form">
                    <div>
                        <input type="text" name="tournament_search" class="form-control" placeholder="Tìm theo tên giải đấu..." value="{{ request('tournament_search') }}" style="width: 100%; padding: 10px 15px; border: 2px solid #e0e0e0; border-radius: 8px; font-size: 14px;">
                    </div>
                    <div>
                        <input type="text" name="tournament_field" class="form-control" placeholder="Tìm theo tên sân..." value="{{ request('tournament_field') }}" style="width: 100%; padding: 10px 15px; border: 2px solid #e0e0e0; border-radius: 8px; font-size: 14px;">
                    </div>
                    <div class="tournament-search-btns">
                        <button type="submit" class="btn btn-primary" style="padding: 10px 25px; border-radius: 8px; font-weight: 500; font-size: 14px; white-space: nowrap;">Tìm kiếm</button>
                        <a href="{{ route('fields.index') }}" class="btn btn-secondary" style="padding: 10px 25px; border-radius: 8px; font-weight: 500; font-size: 14px; white-space: nowrap;">Đặt lại</a>
                    </div>
                </form>
            </div>

            <div class="fields-grid">
                @foreach($tournaments as $tournament)
                <div class="field-card">
                    @if($tournament->banner)
                    <img src="{{ storage_url($tournament->banner) }}" alt="{{ $tournament->name }}" class="field-image">
                    @else
                    <div class="field-image" style="background: linear-gradient(135deg, #1e7e34 0%, #28a745 100%);"></div>
                    @endif
                    <div class="field-info">
                        <span style="display: inline-block; padding: 5px 12px; background: #fff3cd; color: #856404; border-radius: 15px; font-size: 12px; margin-bottom: 10px;">
                            @if($tournament->status == 'upcoming') Sắp diễn ra
                            @else Đang diễn ra
                            @endif
                        </span>
                        <h3 class="field-name">{{ $tournament->name }}</h3>
                        <p class="field-address">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:inline-block;vertical-align:middle;margin-right:4px;color:#6c757d;flex-shrink:0;margin-top:-2px"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                            {{ $tournament->field->name }}
                        </p>
                        <p style="font-size: 14px; color: #666; margin: 8px 0; display:flex; align-items:center; gap:4px;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;color:#6c757d"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                            {{ $tournament->start_date->format('d/m/Y') }} - {{ $tournament->end_date->format('d/m/Y') }}
                        </p>
                        <p style="font-size: 14px; color: #666; margin: 8px 0; display:flex; align-items:center; gap:4px;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;color:#6c757d"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                            {{ $tournament->teams->where('status', 'approved')->count() }}/{{ $tournament->max_teams }} đội
                        </p>
                        <p class="field-price">{{ number_format($tournament->entry_fee) }}đ/đội</p>
                        <a href="{{ route('tournaments.show', $tournament->id) }}" class="btn btn-primary" style="width: 100%; background: #28a745;">
                            Xem chi tiết
                        </a>
                    </div>
                </div>
                @endforeach
            </div>

            <div style="text-align: center; margin-top: 30px;">
                <a href="{{ route('tournaments.index') }}" class="btn btn-secondary">Xem tất cả giải đấu</a>
            </div>
        </div>
        @endif
    </div>
</section>
@endsection
