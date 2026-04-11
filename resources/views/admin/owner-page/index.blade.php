@extends('layouts.admin')

@section('title', 'Quản lý Trang Dành cho Chủ sân')

@section('content')
<div class="container-fluid">
    <h1 class="mb-4">Quản lý Trang Dành cho Chủ sân</h1>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <!-- Stats Section -->
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Thống kê (Stats)</h5>
            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addStatModal">
                <i class="fas fa-plus"></i> Thêm Stat
            </button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Ảnh</th>
                            <th>Số</th>
                            <th>Nhãn</th>
                            <th>Thứ tự</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($stats as $stat)
                        <tr>
                            <td>
                                @if($stat->image)
                                <img src="{{ storage_url($stat->image) }}" alt="" style="width: 50px; height: 50px; object-fit: cover;">
                                @else
                                <span class="text-muted">Không có ảnh</span>
                                @endif
                            </td>
                            <td>{{ $stat->number }}</td>
                            <td>{{ $stat->label }}</td>
                            <td>{{ $stat->order }}</td>
                            <td>
                                <button class="btn btn-sm btn-warning" onclick="editStat({{ $stat->id }}, '{{ $stat->number }}', '{{ $stat->label }}', {{ $stat->order }})">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form action="{{ route('admin.owner-page.stats.delete', $stat->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Xác nhận xóa?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center">Chưa có stat nào</td>
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
            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addBenefitModal">
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
                            <th>Thứ tự</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($benefits as $benefit)
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
                            <td>{{ $benefit->order }}</td>
                            <td>
                                <button class="btn btn-sm btn-warning" onclick="editBenefit({{ $benefit->id }}, '{{ $benefit->title }}', '{{ addslashes($benefit->description) }}', {{ $benefit->order }})">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form action="{{ route('admin.owner-page.benefits.delete', $benefit->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Xác nhận xóa?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center">Chưa có benefit nào</td>
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
            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addStepModal">
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
                        @forelse($steps as $step)
                        <tr>
                            <td>{{ $step->step_number }}</td>
                            <td>{{ $step->title }}</td>
                            <td>{{ Str::limit($step->description, 50) }}</td>
                            <td>
                                <button class="btn btn-sm btn-warning" onclick="editStep({{ $step->id }}, '{{ $step->title }}', '{{ addslashes($step->description) }}', {{ $step->step_number }})">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form action="{{ route('admin.owner-page.steps.delete', $step->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Xác nhận xóa?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
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
            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addSectionModal">
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
                            <th>Thứ tự</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sections as $section)
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
                            <td>{{ $section->order }}</td>
                            <td>
                                <button class="btn btn-sm btn-warning" onclick="editSection({{ $section->id }}, '{{ $section->title }}', '{{ addslashes($section->content) }}', '{{ $section->image_position }}', {{ $section->order }})">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form action="{{ route('admin.owner-page.sections.delete', $section->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Xác nhận xóa?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">Chưa có section nào</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


@include('admin.owner-page.modals')
@endsection
