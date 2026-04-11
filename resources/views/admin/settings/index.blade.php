@extends('layouts.app')

@section('title', 'Cài đặt Website')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin.css') }}">
@endpush

@section('content')
<section class="section">
    <div class="container">
        <h1 style="margin-bottom: 30px;">Cài Đặt Website</h1>

        @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if($errors->any())
        <div class="alert alert-danger">
            @foreach($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
        @endif

        <!-- Tabs -->
        <div class="admin-tabs">
            <button class="admin-tab active" onclick="switchTab('general')">Thông tin chung</button>
            <button class="admin-tab" onclick="switchTab('home')">Trang chủ</button>
            <button class="admin-tab" onclick="switchTab('fields')">Trang Đặt sân</button>
            <button class="admin-tab" onclick="switchTab('about')">Trang Giới thiệu</button>
            <button class="admin-tab" onclick="switchTab('reviews')">Trang Đánh giá</button>
            <button class="admin-tab" onclick="switchTab('owners')">Trang Chủ sân</button>
        </div>

        <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Tab: Thông tin chung -->
            <div id="general" class="tab-content active">
                <div class="card">
                    <h2 class="card-title">Thông tin chung</h2>

                    <div class="form-group">
                        <label class="form-label">Tên website</label>
                        <input type="text" name="site_name" class="form-control"
                               value="{{ App\Models\SiteSetting::get('site_name') }}">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Slogan</label>
                        <input type="text" name="site_slogan" class="form-control"
                               value="{{ App\Models\SiteSetting::get('site_slogan') }}">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Mô tả</label>
                        <textarea name="site_description" class="form-control" rows="3">{{ App\Models\SiteSetting::get('site_description') }}</textarea>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Logo</label>
                        @php $logo = App\Models\SiteSetting::get('logo'); @endphp
                        @if($logo)
                        <div style="margin-bottom: 10px;">
                            <img src="{{ storage_url($logo) }}" alt="Logo" style="max-height: 80px;">
                        </div>
                        @endif
                        <input type="file" name="logo" class="form-control" accept="image/*">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Logo trang đăng nhập</label>
                        @php $loginLogo = App\Models\SiteSetting::get('login_logo'); @endphp
                        @if($loginLogo)
                        <div style="margin-bottom: 10px;">
                            <img src="{{ storage_url($loginLogo) }}" alt="Login Logo" style="max-height: 80px;">
                        </div>
                        @endif
                        <input type="file" name="login_logo" class="form-control" accept="image/*">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Ảnh nền trang đăng nhập</label>
                        @php $loginBg = App\Models\SiteSetting::get('login_background'); @endphp
                        @if($loginBg)
                        <div style="margin-bottom: 10px;">
                            <img src="{{ storage_url($loginBg) }}" alt="Login Background" style="max-width: 300px; border-radius: 10px;">
                        </div>
                        @endif
                        <input type="file" name="login_background" class="form-control" accept="image/*">
                        <small>Khuyến nghị: 1920x1080px</small>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Logo trang đăng ký</label>
                        @php $registerLogo = App\Models\SiteSetting::get('register_logo'); @endphp
                        @if($registerLogo)
                        <div style="margin-bottom: 10px;">
                            <img src="{{ storage_url($registerLogo) }}" alt="Register Logo" style="max-height: 80px;">
                        </div>
                        @endif
                        <input type="file" name="register_logo" class="form-control" accept="image/*">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Ảnh nền trang đăng ký</label>
                        @php $registerBg = App\Models\SiteSetting::get('register_background'); @endphp
                        @if($registerBg)
                        <div style="margin-bottom: 10px;">
                            <img src="{{ storage_url($registerBg) }}" alt="Register Background" style="max-width: 300px; border-radius: 10px;">
                        </div>
                        @endif
                        <input type="file" name="register_background" class="form-control" accept="image/*">
                        <small>Khuyến nghị: 1920x1080px</small>
                    </div>

                    <h3 style="margin-top: 30px; margin-bottom: 20px;">Thông tin liên hệ</h3>

                    <div class="form-group">
                        <label class="form-label">Số điện thoại</label>
                        <input type="text" name="site_phone" class="form-control"
                               value="{{ App\Models\SiteSetting::get('site_phone') }}">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Email</label>
                        <input type="email" name="site_email" class="form-control"
                               value="{{ App\Models\SiteSetting::get('site_email') }}">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Hotline</label>
                        <input type="text" name="site_hotline" class="form-control"
                               value="{{ App\Models\SiteSetting::get('site_hotline') }}">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Địa chỉ</label>
                        <textarea name="site_address" class="form-control" rows="2">{{ App\Models\SiteSetting::get('site_address') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Tab: Trang chủ -->
            <div id="home" class="tab-content">
                <div class="card">
                    <h2 class="card-title">Nội dung Trang chủ</h2>

                    <div class="form-group">
                        <label class="form-label">Tiêu đề Hero (Banner chính)</label>
                        <input type="text" name="hero_title" class="form-control"
                               value="{{ App\Models\SiteSetting::get('hero_title') }}"
                               placeholder="Đặt Sân Bóng Dễ Dàng">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Mô tả Hero</label>
                        <input type="text" name="hero_description" class="form-control"
                               value="{{ App\Models\SiteSetting::get('hero_description') }}"
                               placeholder="Tìm và đặt sân bóng chất lượng gần bạn">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Banner Trang chủ (Chọn tối đa 5 ảnh)</label>
                        @php
                            $heroBanners = App\Models\SiteSetting::get('hero_banners');
                            $bannerArray = $heroBanners ? json_decode($heroBanners, true) : [];
                        @endphp
                        @if(!empty($bannerArray))
                        <div style="margin-bottom: 10px; display: flex; gap: 10px; flex-wrap: wrap;">
                            @foreach($bannerArray as $index => $banner)
                            <div style="position: relative;">
                                <img src="{{ storage_url($banner) }}" alt="Banner {{ $index + 1 }}" style="max-width: 200px; border-radius: 10px;">
                                <button type="button" onclick="removeBanner({{ $index }})" style="position: absolute; top: 5px; right: 5px; background: red; color: white; border: none; border-radius: 50%; width: 25px; height: 25px; cursor: pointer;">×</button>
                            </div>
                            @endforeach
                        </div>
                        @endif
                        <input type="file" name="hero_banners[]" class="form-control" accept="image/*" multiple>
                        <small>Khuyến nghị: 1920x500px. Chọn tối đa 5 ảnh. Banner sẽ tự động chuyển sau 4 giây.</small>
                        <input type="hidden" name="remove_banners" id="remove_banners" value="">
                    </div>

                    <h3 style="margin-top: 30px; margin-bottom: 20px;">Phần Giới thiệu</h3>

                    <div class="form-group">
                        <label class="form-label">Tiêu đề</label>
                        <input type="text" name="about_title" class="form-control"
                               value="{{ App\Models\SiteSetting::get('about_title') }}"
                               placeholder="Về Chúng Tôi">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Mô tả</label>
                        <textarea name="about_description" class="form-control" rows="3">{{ App\Models\SiteSetting::get('about_description') }}</textarea>
                    </div>

                    <h3 style="margin-top: 40px; margin-bottom: 20px; border-top: 2px solid #ddd; padding-top: 30px;">Cards "Về chúng tôi"</h3>

                    @php
                        $homeCards = App\Models\HomeCard::orderBy('order')->get();
                    @endphp

                    <div id="cards-list">
                        @foreach($homeCards as $card)
                        <div class="card" style="margin-bottom: 15px; padding: 15px; background: #f8f9fa;">
                            <div class="form-group">
                                <label class="form-label">Tiêu đề Card</label>
                                <input type="text" class="form-control" name="cards[{{ $card->id }}][title]" value="{{ $card->title }}" data-card-id="{{ $card->id }}" data-field="title">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Mô tả</label>
                                <textarea class="form-control" rows="2" name="cards[{{ $card->id }}][description]" data-card-id="{{ $card->id }}" data-field="description">{{ $card->description }}</textarea>
                            </div>
                            <button type="button" class="btn btn-primary" onclick="updateCard({{ $card->id }})">Cập nhật</button>
                            <button type="button" class="btn btn-danger" onclick="deleteCard({{ $card->id }})">Xóa</button>
                        </div>
                        @endforeach
                    </div>

                    <button type="button" class="btn btn-success" onclick="showAddCardForm()">+ Thêm Card mới</button>

                    <div id="add-card-form" style="display: none; margin-top: 20px; padding: 20px; background: #e9ecef; border-radius: 10px;">
                        <h4>Thêm Card mới</h4>
                        <div class="form-group">
                            <label class="form-label">Tiêu đề</label>
                            <input type="text" id="new-card-title" class="form-control">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Mô tả</label>
                            <textarea id="new-card-description" class="form-control" rows="2"></textarea>
                        </div>
                        <button type="button" class="btn btn-primary" onclick="addCard()">Lưu</button>
                        <button type="button" class="btn btn-secondary" onclick="hideAddCardForm()">Hủy</button>
                    </div>

                    <h3 style="margin-top: 40px; margin-bottom: 20px; border-top: 2px solid #ddd; padding-top: 30px;">Số liệu (Stats)</h3>

                    @php
                        $homeStats = App\Models\HomeStat::orderBy('order')->get();
                    @endphp

                    <div id="stats-list">
                        @foreach($homeStats as $stat)
                        <div class="card" style="margin-bottom: 15px; padding: 15px; background: #f8f9fa;">
                            <div class="form-group">
                                <label class="form-label">Giá trị (số)</label>
                                <input type="text" class="form-control" name="stats[{{ $stat->id }}][value]" value="{{ $stat->value }}" data-stat-id="{{ $stat->id }}" data-field="value" style="width: 150px;">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Nhãn</label>
                                <input type="text" class="form-control" name="stats[{{ $stat->id }}][title]" value="{{ $stat->title ?? $stat->label }}" data-stat-id="{{ $stat->id }}" data-field="title">
                            </div>
                            <button type="button" class="btn btn-primary" onclick="updateStat({{ $stat->id }})">Cập nhật</button>
                            <button type="button" class="btn btn-danger" onclick="deleteStat({{ $stat->id }})">Xóa</button>
                        </div>
                        @endforeach
                    </div>

                    <button type="button" class="btn btn-success" onclick="showAddStatForm()">+ Thêm Số liệu mới</button>

                    <div id="add-stat-form" style="display: none; margin-top: 20px; padding: 20px; background: #e9ecef; border-radius: 10px;">
                        <h4>Thêm Số liệu mới</h4>
                        <div class="form-group">
                            <label class="form-label">Giá trị (số)</label>
                            <input type="text" id="new-stat-value" class="form-control" placeholder="100+" style="width: 150px;">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Nhãn</label>
                            <input type="text" id="new-stat-title" class="form-control" placeholder="Người dùng">
                        </div>
                        <button type="button" class="btn btn-primary" onclick="addStat()">Lưu</button>
                        <button type="button" class="btn btn-secondary" onclick="hideAddStatForm()">Hủy</button>
                    </div>

                    <h3 style="margin-top: 40px; margin-bottom: 20px; border-top: 2px solid #ddd; padding-top: 30px;">Sân nổi bật</h3>

                    @php
                        $featuredFields = App\Models\FeaturedField::orderBy('order')->get();
                    @endphp

                    <div id="fields-list">
                        @foreach($featuredFields as $field)
                        <div class="card" style="margin-bottom: 15px; padding: 15px; background: #f8f9fa;">
                            @if($field->image)
                            <div style="margin-bottom: 10px;">
                                <img src="{{ $field->image_url }}" alt="{{ $field->title }}" style="max-width: 200px; border-radius: 10px;">
                            </div>
                            @endif
                            <div class="form-group">
                                <label class="form-label">Tiêu đề</label>
                                <input type="text" class="form-control" name="fields[{{ $field->id }}][title]" value="{{ $field->title }}" data-field-id="{{ $field->id }}" data-field="title">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Mô tả</label>
                                <textarea class="form-control" rows="2" name="fields[{{ $field->id }}][description]" data-field-id="{{ $field->id }}" data-field="description">{{ $field->description }}</textarea>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Giá</label>
                                <input type="number" class="form-control" name="fields[{{ $field->id }}][price]" value="{{ $field->price }}" data-field-id="{{ $field->id }}" data-field="price" step="1000">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Hotline</label>
                                <input type="text" class="form-control" name="fields[{{ $field->id }}][hotline]" value="{{ $field->hotline }}" data-field-id="{{ $field->id }}" data-field="hotline" placeholder="0123456789">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Ảnh mới</label>
                                <input type="file" class="form-control" id="field-image-{{ $field->id }}" accept="image/*">
                            </div>
                            <button type="button" class="btn btn-primary" onclick="updateField({{ $field->id }})">Cập nhật</button>
                            <button type="button" class="btn btn-danger" onclick="deleteField({{ $field->id }})">Xóa</button>
                        </div>
                        @endforeach
                    </div>

                    <button type="button" class="btn btn-success" onclick="showAddFieldForm()">+ Thêm Sân nổi bật mới</button>

                    <div id="add-field-form" style="display: none; margin-top: 20px; padding: 20px; background: #e9ecef; border-radius: 10px;">
                        <h4>Thêm Sân nổi bật mới</h4>
                        <div class="form-group">
                            <label class="form-label">Tiêu đề</label>
                            <input type="text" id="new-field-title" class="form-control">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Mô tả</label>
                            <textarea id="new-field-description" class="form-control" rows="2"></textarea>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Giá (VNĐ/giờ)</label>
                            <input type="number" id="new-field-price" class="form-control" placeholder="200000" min="1" step="1000">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Hotline</label>
                            <input type="text" id="new-field-hotline" class="form-control" placeholder="0123456789">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Ảnh</label>
                            <input type="file" id="new-field-image" class="form-control" accept="image/*">
                        </div>
                        <button type="button" class="btn btn-primary" onclick="addField()">Lưu</button>
                        <button type="button" class="btn btn-secondary" onclick="hideAddFieldForm()">Hủy</button>
                    </div>

                    <h3 style="margin-top: 40px; margin-bottom: 20px; border-top: 2px solid #ddd; padding-top: 30px;">Tin tức & Bài viết</h3>
                    <p style="color: #666; margin-bottom: 15px;">Quản lý các bài viết tin tức hiển thị trên trang chủ.</p>
                    <a href="{{ route('admin.posts.index') }}" class="btn btn-secondary">Quản lý Bài viết</a>
                </div>
            </div>

            <!-- Tab: Trang Đặt sân -->
            <div id="fields" class="tab-content">
                <div class="card">
                    <h2 class="card-title">Trang Đặt sân</h2>

                    <div class="form-group">
                        <label class="form-label">Tiêu đề trang</label>
                        <input type="text" name="fields_title" class="form-control"
                               value="{{ App\Models\SiteSetting::get('fields_title') }}"
                               placeholder="Tìm Sân Bóng">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Mô tả</label>
                        <input type="text" name="fields_description" class="form-control"
                               value="{{ App\Models\SiteSetting::get('fields_description') }}"
                               placeholder="Chọn sân phù hợp với bạn">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Banner</label>
                        @php $fieldsBanner = App\Models\SiteSetting::get('fields_banner'); @endphp
                        @if($fieldsBanner)
                        <div style="margin-bottom: 10px;">
                            <img src="{{ storage_url($fieldsBanner) }}" alt="Fields Banner" style="max-width: 300px; border-radius: 10px;">
                        </div>
                        @endif
                        <input type="file" name="fields_banner" class="form-control" accept="image/*">
                        <small>Khuyến nghị: 1920x300px</small>
                    </div>
                </div>
            </div>

            <!-- Tab: Trang Giới thiệu -->
            <div id="about" class="tab-content">
                <div class="card">
                    <h2 class="card-title">Trang Giới thiệu</h2>

                    <div class="form-group">
                        <label class="form-label">Tiêu đề trang</label>
                        <input type="text" name="about_page_title" class="form-control"
                               value="{{ App\Models\SiteSetting::get('about_page_title') }}"
                               placeholder="Giới Thiệu">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Banner</label>
                        @php $aboutBanner = App\Models\SiteSetting::get('about_banner'); @endphp
                        @if($aboutBanner)
                        <div style="margin-bottom: 10px;">
                            <img src="{{ storage_url($aboutBanner) }}" alt="About Banner" style="max-width: 300px; border-radius: 10px;">
                        </div>
                        @endif
                        <input type="file" name="about_banner" class="form-control" accept="image/*">
                        <small>Khuyến nghị: 1920x300px</small>
                    </div>

                    <h3 style="margin-top: 40px; margin-bottom: 20px; border-top: 2px solid #ddd; padding-top: 30px;">Sections Giới thiệu</h3>

                    @php
                        $aboutSections = $aboutSections ?? collect();
                    @endphp

                    <div id="about-sections-list">
                        @foreach($aboutSections as $section)
                        <div class="card" style="margin-bottom: 15px; padding: 15px; background: #f8f9fa;">
                            @if($section->image)
                            <div style="margin-bottom: 10px;">
                                <img src="{{ storage_url($section->image) }}" alt="{{ $section->title }}" style="max-width: 200px; border-radius: 10px;">
                            </div>
                            @endif
                            <div class="form-group">
                                <label class="form-label">Tiêu đề</label>
                                <input type="text" class="form-control" name="sections[{{ $section->id }}][title]" value="{{ $section->title }}" data-section-id="{{ $section->id }}" data-field="title">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Nội dung</label>
                                <textarea class="form-control" rows="4" name="sections[{{ $section->id }}][content]" data-section-id="{{ $section->id }}" data-field="content">{{ $section->content }}</textarea>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Vị trí ảnh</label>
                                <select class="form-control" name="sections[{{ $section->id }}][layout]" data-section-id="{{ $section->id }}" data-field="layout">
                                    <option value="image-left" {{ $section->layout == 'image-left' ? 'selected' : '' }}>Ảnh bên trái</option>
                                    <option value="image-right" {{ $section->layout == 'image-right' ? 'selected' : '' }}>Ảnh bên phải</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Ảnh mới</label>
                                <input type="file" class="form-control" id="section-image-{{ $section->id }}" accept="image/*">
                            </div>
                            <button type="button" class="btn btn-primary" onclick="updateAboutSection({{ $section->id }})">Cập nhật</button>
                            <button type="button" class="btn btn-danger" onclick="deleteAboutSection({{ $section->id }})">Xóa</button>
                        </div>
                        @endforeach
                    </div>

                    <button type="button" class="btn btn-success" onclick="showAddAboutSectionForm()">+ Thêm Section mới</button>

                    <div id="add-about-section-form" style="display: none; margin-top: 20px; padding: 20px; background: #e9ecef; border-radius: 10px;">
                        <h4>Thêm Section mới</h4>
                        <div class="form-group">
                            <label class="form-label">Tiêu đề</label>
                            <input type="text" id="new-section-title" class="form-control">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Nội dung</label>
                            <textarea id="new-section-content" class="form-control" rows="4"></textarea>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Vị trí ảnh</label>
                            <select id="new-section-layout" class="form-control">
                                <option value="image-left">Ảnh bên trái</option>
                                <option value="image-right">Ảnh bên phải</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Ảnh</label>
                            <input type="file" id="new-section-image" class="form-control" accept="image/*">
                        </div>
                        <button type="button" class="btn btn-primary" onclick="addAboutSection()">Lưu</button>
                        <button type="button" class="btn btn-secondary" onclick="hideAddAboutSectionForm()">Hủy</button>
                    </div>
                </div>
            </div>

            <!-- Tab: Trang Đánh giá -->
            <div id="reviews" class="tab-content">
                <div class="card">
                    <h2 class="card-title">Trang Đánh giá</h2>

                    <div class="form-group">
                        <label class="form-label">Tiêu đề trang</label>
                        <input type="text" name="reviews_title" class="form-control"
                               value="{{ App\Models\SiteSetting::get('reviews_title') }}"
                               placeholder="Đánh Giá Từ Khách Hàng">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Mô tả</label>
                        <input type="text" name="reviews_description" class="form-control"
                               value="{{ App\Models\SiteSetting::get('reviews_description') }}"
                               placeholder="Chia sẻ trải nghiệm thực tế từ cộng đồng người chơi">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Banner</label>
                        @php $reviewsBanner = App\Models\SiteSetting::get('reviews_banner'); @endphp
                        @if($reviewsBanner)
                        <div style="margin-bottom: 10px;">
                            <img src="{{ storage_url($reviewsBanner) }}" alt="Reviews Banner" style="max-width: 300px; border-radius: 10px;">
                        </div>
                        @endif
                        <input type="file" name="reviews_banner" class="form-control" accept="image/*">
                        <small>Khuyến nghị: 1920x300px</small>
                    </div>
                </div>
            </div>

            <!-- Tab: Trang Chủ sân -->
            <div id="owners" class="tab-content">
                <!-- Banner & Title -->
                <div class="card mb-4">
                    <h2 class="card-title">Banner & Tiêu đề</h2>

                    <div class="form-group">
                        <label class="form-label">Tiêu đề trang</label>
                        <input type="text" name="owners_title" class="form-control"
                               value="{{ App\Models\SiteSetting::get('owners_title') }}"
                               placeholder="Dành Cho Chủ Sân">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Mô tả</label>
                        <input type="text" name="owners_description" class="form-control"
                               value="{{ App\Models\SiteSetting::get('owners_description') }}"
                               placeholder="Đăng ký trở thành đối tác của chúng tôi">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Banner</label>
                        @php $ownerBanner = App\Models\SiteSetting::get('owner_banner'); @endphp
                        @if($ownerBanner)
                        <div style="margin-bottom: 10px;">
                            <img src="{{ storage_url($ownerBanner) }}" alt="Owner Banner" style="max-width: 300px; border-radius: 10px;">
                        </div>
                        @endif
                        <input type="file" name="owner_banner" class="form-control" accept="image/*">
                        <small>Khuyến nghị: 1920x400px</small>
                    </div>
                </div>

                <!-- Stats Section -->
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Thống kê (Stats)</h5>
                        <button type="button" class="btn btn-primary btn-sm" onclick="openOwnerStatModal()">
                            <i class="fas fa-plus"></i> Thêm Stat
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Số</th>
                                        <th>Nhãn</th>
                                        <th>Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $ownerStats = \App\Models\OwnerStat::orderBy('order')->get(); @endphp
                                    @forelse($ownerStats as $stat)
                                    <tr>
                                        <td>{{ $stat->number }}</td>
                                        <td>{{ $stat->label }}</td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-warning" onclick="editOwnerStat({{ $stat->id }}, '{{ $stat->number }}', '{{ $stat->label }}')">
                                                Sửa
                                            </button>
                                            <button type="button" class="btn btn-sm btn-danger" onclick="deleteOwnerStat({{ $stat->id }})">
                                                Xóa
                                            </button>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="3" class="text-center">Chưa có stat nào</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Benefits Section -->
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Lợi ích (Benefits)</h5>
                        <button type="button" class="btn btn-primary btn-sm" onclick="openOwnerBenefitModal()">
                            <i class="fas fa-plus"></i> Thêm Benefit
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Ảnh</th>
                                        <th>Tiêu đề</th>
                                        <th>Mô tả</th>
                                        <th>Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $ownerBenefits = \App\Models\OwnerBenefit::orderBy('order')->get(); @endphp
                                    @forelse($ownerBenefits as $benefit)
                                    <tr>
                                        <td>
                                            @if($benefit->image)
                                            <img src="{{ storage_url($benefit->image) }}" alt="" style="width: 50px; height: 50px; object-fit: cover;">
                                            @else
                                            <span class="text-muted">Không có ảnh</span>
                                            @endif
                                        </td>
                                        <td>{{ $benefit->title }}</td>
                                        <td>{{ Str::limit($benefit->description, 50) }}</td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-warning" onclick="editOwnerBenefit({{ $benefit->id }}, '{{ $benefit->title }}', '{{ addslashes($benefit->description) }}')">
                                                Sửa
                                            </button>
                                            <button type="button" class="btn btn-sm btn-danger" onclick="deleteOwnerBenefit({{ $benefit->id }})">
                                                Xóa
                                            </button>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="text-center">Chưa có benefit nào</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Steps Section -->
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Các bước (How it Works)</h5>
                        <button type="button" class="btn btn-primary btn-sm" onclick="openOwnerStepModal()">
                            <i class="fas fa-plus"></i> Thêm Bước
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Bước</th>
                                        <th>Tiêu đề</th>
                                        <th>Mô tả</th>
                                        <th>Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $ownerSteps = \App\Models\OwnerStep::orderBy('step_number')->get(); @endphp
                                    @forelse($ownerSteps as $step)
                                    <tr>
                                        <td>{{ $step->step_number }}</td>
                                        <td>{{ $step->title }}</td>
                                        <td>{{ Str::limit($step->description, 50) }}</td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-warning" onclick="editOwnerStep({{ $step->id }}, '{{ $step->title }}', '{{ addslashes($step->description) }}', {{ $step->step_number }})">
                                                Sửa
                                            </button>
                                            <button type="button" class="btn btn-sm btn-danger" onclick="deleteOwnerStep({{ $step->id }})">
                                                Xóa
                                            </button>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="text-center">Chưa có bước nào</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Content Sections -->
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Sections (Text + Ảnh)</h5>
                        <button type="button" class="btn btn-primary btn-sm" onclick="openOwnerSectionModal()">
                            <i class="fas fa-plus"></i> Thêm Section
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Ảnh</th>
                                        <th>Tiêu đề</th>
                                        <th>Nội dung</th>
                                        <th>Vị trí ảnh</th>
                                        <th>Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $ownerSections = \App\Models\OwnerSection::orderBy('order')->get(); @endphp
                                    @forelse($ownerSections as $section)
                                    <tr>
                                        <td>
                                            @if($section->image)
                                            <img src="{{ storage_url($section->image) }}" alt="" style="width: 50px; height: 50px; object-fit: cover;">
                                            @else
                                            <span class="text-muted">Không có ảnh</span>
                                            @endif
                                        </td>
                                        <td>{{ $section->title }}</td>
                                        <td>{{ Str::limit($section->content, 50) }}</td>
                                        <td>{{ $section->image_position == 'left' ? 'Trái' : 'Phải' }}</td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-warning" onclick="editOwnerSection({{ $section->id }}, '{{ $section->title }}', '{{ addslashes($section->content) }}', '{{ $section->image_position }}')">
                                                Sửa
                                            </button>
                                            <button type="button" class="btn btn-sm btn-danger" onclick="deleteOwnerSection({{ $section->id }})">
                                                Xóa
                                            </button>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="text-center">Chưa có section nào</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary" style="margin-top: 20px;">Lưu cài đặt</button>
        </form>
    </div>
</section>

@push('scripts')
<script>
function switchTab(tabName) {
    // Ẩn tất cả tab content
    document.querySelectorAll('.tab-content').forEach(tab => {
        tab.classList.remove('active');
    });

    // Bỏ active tất cả tab buttons
    document.querySelectorAll('.admin-tab').forEach(btn => {
        btn.classList.remove('active');
    });

    // Hiện tab được chọn
    document.getElementById(tabName).classList.add('active');
    event.target.classList.add('active');

    // Lưu tab hiện tại vào localStorage
    localStorage.setItem('activeTab', tabName);
}

// Khôi phục tab sau khi tải trang
document.addEventListener('DOMContentLoaded', function() {
    const activeTab = localStorage.getItem('activeTab');
    if (activeTab && document.getElementById(activeTab)) {
        // Ẩn tất cả tab
        document.querySelectorAll('.tab-content').forEach(tab => {
            tab.classList.remove('active');
        });
        document.querySelectorAll('.admin-tab').forEach(btn => {
            btn.classList.remove('active');
        });

        // Hiện tab đã lưu
        document.getElementById(activeTab).classList.add('active');
        document.querySelector(`[onclick="switchTab('${activeTab}')"]`).classList.add('active');
    }
});

let bannersToRemove = [];
function removeBanner(index) {
    bannersToRemove.push(index);
    document.getElementById('remove_banners').value = JSON.stringify(bannersToRemove);
    event.target.parentElement.remove();
}

// Cards functions
function showAddCardForm() {
    document.getElementById('add-card-form').style.display = 'block';
}

function hideAddCardForm() {
    document.getElementById('add-card-form').style.display = 'none';
}

function addCard() {
    const title = document.getElementById('new-card-title').value;
    const description = document.getElementById('new-card-description').value;

    fetch('{{ route("admin.home-content.cards.store") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ title, description })
    }).then(() => location.reload());
}

function updateCard(id) {
    const title = document.querySelector(`[data-card-id="${id}"][data-field="title"]`).value;
    const description = document.querySelector(`[data-card-id="${id}"][data-field="description"]`).value;

    fetch(`/admin/home-content/cards/${id}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ title, description })
    }).then(() => alert('Cập nhật thành công!'));
}

function deleteCard(id) {
    if (confirm('Bạn có chắc muốn xóa?')) {
        fetch(`/admin/home-content/cards/${id}`, {
            method: 'DELETE',
            headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'}
        }).then(() => location.reload());
    }
}

// Stats functions
function showAddStatForm() {
    document.getElementById('add-stat-form').style.display = 'block';
}

function hideAddStatForm() {
    document.getElementById('add-stat-form').style.display = 'none';
}

function addStat() {
    const value = document.getElementById('new-stat-value').value;
    const title = document.getElementById('new-stat-title').value;

    if (!value || !title) {
        alert('Vui lòng điền đầy đủ thông tin!');
        return;
    }

    fetch('{{ route("admin.home-content.stats.store") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ value, title })
    }).then(r => {
        if (!r.ok) {
            return r.text().then(t => { throw new Error('HTTP ' + r.status + ': ' + t.substring(0, 200)); });
        }
        return r.json();
    }).then(data => {
        if (data.success) location.reload();
        else alert('Lỗi: ' + (data.message || 'Không thể lưu'));
    }).catch(e => alert('Lỗi: ' + e.message));
}

function updateStat(id) {
    const value = document.querySelector(`[data-stat-id="${id}"][data-field="value"]`).value;
    const title = document.querySelector(`[data-stat-id="${id}"][data-field="title"]`).value;

    fetch(`/admin/home-content/stats/${id}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ value, title })
    }).then(() => alert('Cập nhật thành công!'));
}

function deleteStat(id) {
    if (confirm('Bạn có chắc muốn xóa?')) {
        fetch(`/admin/home-content/stats/${id}`, {
            method: 'DELETE',
            headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'}
        }).then(() => location.reload());
    }
}

// Fields functions
function showAddFieldForm() {
    document.getElementById('add-field-form').style.display = 'block';
}

function hideAddFieldForm() {
    document.getElementById('add-field-form').style.display = 'none';
}

function addField() {
    const price = document.getElementById('new-field-price').value;

    if (!price || parseFloat(price) < 1) {
        alert('Giá tiền phải lớn hơn 0!');
        return;
    }

    const formData = new FormData();
    formData.append('title', document.getElementById('new-field-title').value);
    formData.append('description', document.getElementById('new-field-description').value);
    formData.append('price', price);
    formData.append('hotline', document.getElementById('new-field-hotline').value);

    const imageFile = document.getElementById('new-field-image').files[0];
    if (imageFile) formData.append('image', imageFile);

    fetch('{{ route("admin.home-content.fields.store") }}', {
        method: 'POST',
        headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
        body: formData
    }).then(() => location.reload());
}

function updateField(id) {
    const price = document.querySelector(`[data-field-id="${id}"][data-field="price"]`).value;

    if (!price || parseFloat(price) < 1) {
        alert('Giá tiền phải lớn hơn 0!');
        return;
    }

    const formData = new FormData();
    formData.append('_method', 'PUT');
    formData.append('title', document.querySelector(`[data-field-id="${id}"][data-field="title"]`).value);
    formData.append('description', document.querySelector(`[data-field-id="${id}"][data-field="description"]`).value);
    formData.append('price', price);
    formData.append('hotline', document.querySelector(`[data-field-id="${id}"][data-field="hotline"]`).value);

    const imageFile = document.getElementById(`field-image-${id}`).files[0];
    if (imageFile) formData.append('image', imageFile);

    fetch(`/admin/home-content/fields/${id}`, {
        method: 'POST',
        headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
        body: formData
    }).then(() => { alert('Cập nhật thành công!'); location.reload(); });
}

function deleteField(id) {
    if (confirm('Bạn có chắc muốn xóa?')) {
        fetch(`/admin/home-content/fields/${id}`, {
            method: 'DELETE',
            headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'}
        }).then(() => location.reload());
    }
}

// About Sections functions
function showAddAboutSectionForm() {
    document.getElementById('add-about-section-form').style.display = 'block';
}

function hideAddAboutSectionForm() {
    document.getElementById('add-about-section-form').style.display = 'none';
}

function addAboutSection() {
    const formData = new FormData();
    formData.append('title', document.getElementById('new-section-title').value);
    formData.append('content', document.getElementById('new-section-content').value);
    formData.append('layout', document.getElementById('new-section-layout').value);

    const imageFile = document.getElementById('new-section-image').files[0];
    if (imageFile) formData.append('image', imageFile);

    fetch('{{ route("admin.home-content.about-sections.store") }}', {
        method: 'POST',
        headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
        body: formData
    }).then(() => location.reload());
}

function updateAboutSection(id) {
    const formData = new FormData();
    formData.append('_method', 'PUT');
    formData.append('_token', '{{ csrf_token() }}');
    formData.append('title', document.querySelector(`[data-section-id="${id}"][data-field="title"]`).value);
    formData.append('content', document.querySelector(`[data-section-id="${id}"][data-field="content"]`).value);
    formData.append('layout', document.querySelector(`[data-section-id="${id}"][data-field="layout"]`).value);

    const imageFile = document.getElementById(`section-image-${id}`).files[0];
    if (imageFile) {
        formData.append('image', imageFile);
        console.log('Image file selected:', imageFile.name);
    }

    fetch(`/admin/home-content/about-sections/${id}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Cập nhật thành công!');
            location.reload();
        } else {
            alert('Có lỗi xảy ra: ' + (data.message || 'Unknown error'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Có lỗi xảy ra khi cập nhật!');
    });
}

function deleteAboutSection(id) {
    if (confirm('Bạn có chắc muốn xóa?')) {
        fetch(`/admin/home-content/about-sections/${id}`, {
            method: 'DELETE',
            headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'}
        }).then(() => location.reload());
    }
}

// Owner Page Functions
function openOwnerStatModal() {
    document.getElementById('ownerStatModal').style.display = 'block';
}

function editOwnerStat(id, number, label) {
    document.getElementById('edit_owner_stat_id').value = id;
    document.getElementById('edit_owner_stat_number').value = number;
    document.getElementById('edit_owner_stat_label').value = label;
    document.getElementById('ownerStatEditModal').style.display = 'block';
}

function deleteOwnerStat(id) {
    if (confirm('Xác nhận xóa?')) {
        fetch(`/admin/settings/owner-stats/${id}`, {
            method: 'DELETE',
            headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'}
        }).then(() => location.reload());
    }
}

function openOwnerBenefitModal() {
    document.getElementById('ownerBenefitModal').style.display = 'block';
}

function editOwnerBenefit(id, title, description) {
    document.getElementById('edit_owner_benefit_id').value = id;
    document.getElementById('edit_owner_benefit_title').value = title;
    document.getElementById('edit_owner_benefit_description').value = description;
    document.getElementById('ownerBenefitEditModal').style.display = 'block';
}

function deleteOwnerBenefit(id) {
    if (confirm('Xác nhận xóa?')) {
        fetch(`/admin/settings/owner-benefits/${id}`, {
            method: 'DELETE',
            headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'}
        }).then(() => location.reload());
    }
}

function openOwnerStepModal() {
    document.getElementById('ownerStepModal').style.display = 'block';
}

function editOwnerStep(id, title, description, stepNumber) {
    document.getElementById('edit_owner_step_id').value = id;
    document.getElementById('edit_owner_step_title').value = title;
    document.getElementById('edit_owner_step_description').value = description;
    document.getElementById('edit_owner_step_number').value = stepNumber;
    document.getElementById('ownerStepEditModal').style.display = 'block';
}

function deleteOwnerStep(id) {
    if (confirm('Xác nhận xóa?')) {
        fetch(`/admin/settings/owner-steps/${id}`, {
            method: 'DELETE',
            headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'}
        }).then(() => location.reload());
    }
}

function openOwnerSectionModal() {
    document.getElementById('ownerSectionModal').style.display = 'block';
}

function editOwnerSection(id, title, content, position) {
    document.getElementById('edit_owner_section_id').value = id;
    document.getElementById('edit_owner_section_title').value = title;
    document.getElementById('edit_owner_section_content').value = content;
    document.getElementById('edit_owner_section_position').value = position;
    document.getElementById('ownerSectionEditModal').style.display = 'block';
}

function deleteOwnerSection(id) {
    if (confirm('Xác nhận xóa?')) {
        fetch(`/admin/settings/owner-sections/${id}`, {
            method: 'DELETE',
            headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'}
        }).then(() => location.reload());
    }
}

function closeModal(modalId) {
    document.getElementById(modalId).style.display = 'none';
}
</script>

<!-- Owner Page Modals -->
@include('admin.settings.owner-modals')

@endpush
@endsection
