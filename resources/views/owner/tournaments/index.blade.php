@extends('layouts.app')

@section('title', 'Quản lý giải đấu')

@push('styles')
<style>
.tournaments-container {
    padding: 30px 0;
}

.tournaments-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
}

.tournaments-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 25px;
    overflow: hidden;
}

.tournament-card {
    background: white;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    transition: transform 0.3s;
}

.tournament-card:hover {
    transform: translateY(-5px);
}

.tournament-banner {
    width: 100%;
    height: 200px;
    object-fit: cover;
    background: linear-gradient(135deg, #1e7e34 0%, #28a745 100%);
}

.tournament-body {
    padding: 20px;
}

.tournament-title {
    font-size: 20px;
    font-weight: 600;
    margin: 0 0 10px 0;
    color: #333;
}

.tournament-info {
    display: flex;
    flex-direction: column;
    gap: 8px;
    margin-bottom: 15px;
}

.tournament-info-item {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 14px;
    color: #666;
}

.tournament-status {
    display: inline-block;
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 500;
    margin-bottom: 15px;
}

.status-upcoming { background: #fff3cd; color: #856404; }
.status-ongoing { background: #d4edda; color: #155724; }
.status-finished { background: #d1ecf1; color: #0c5460; }

.tournament-actions {
    display: flex;
    gap: 10px;
}

.tournament-actions .btn {
    flex: 1;
    font-size: 13px;
    padding: 8px 12px;
}

.empty-state {
    text-align: center;
    padding: 60px 20px;
    background: white;
    border-radius: 10px;
}

.empty-state-icon {
    font-size: 80px;
    margin-bottom: 20px;
}
</style>
@endpush

@section('content')
<div class="tournaments-container">
    <div class="container">
        <div class="tournaments-header">
            <h1 style="font-size:24px; white-space:nowrap;">Quản lý giải đấu</h1>
            <div style="display:flex; gap:10px; flex-shrink:0;">
                <a href="{{ route('owner.dashboard') }}" class="btn btn-secondary" style="white-space:nowrap; height:38px; display:inline-flex; align-items:center;">← Dashboard</a>
                <a href="{{ route('owner.tournaments.create') }}" class="btn btn-primary" style="white-space:nowrap; height:38px; display:inline-flex; align-items:center;">+ Tạo giải đấu mới</a>
            </div>
        </div>

        @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if($tournaments->count() > 0)
        <div class="tournaments-grid">
            @foreach($tournaments as $tournament)
            <div class="tournament-card">
                @if($tournament->banner)
                <img src="{{ storage_url($tournament->banner) }}" alt="{{ $tournament->name }}" class="tournament-banner">
                @else
                <div class="tournament-banner"></div>
                @endif

                <div class="tournament-body">
                    <h3 class="tournament-title">{{ $tournament->name }}</h3>

                    <span class="tournament-status status-{{ $tournament->status }}">
                        @if($tournament->status == 'upcoming') Sắp diễn ra
                        @elseif($tournament->status == 'ongoing') Đang diễn ra
                        @else Đã kết thúc
                        @endif
                    </span>

                    <div class="tournament-info">
                        <div class="tournament-info-item">
                            <span>Sân:</span>
                            <span>{{ $tournament->field->name }}</span>
                        </div>
                        <div class="tournament-info-item">
                            <span>Thời gian:</span>
                            <span>{{ $tournament->start_date }} → {{ $tournament->end_date }}</span>
                        </div>
                        <div class="tournament-info-item">
                            <span>Đội:</span>
                            <span>{{ $tournament->teams->count() }}/{{ $tournament->max_teams }} đội</span>
                        </div>
                        <div class="tournament-info-item">
                            <span>Số người:</span>
                            <span>{{ $tournament->players_per_team }} người/đội</span>
                        </div>
                        <div class="tournament-info-item">
                            <span>Phí:</span>
                            <span>{{ number_format($tournament->entry_fee) }}đ/đội</span>
                        </div>
                    </div>

                    <div class="tournament-actions">
                        <a href="{{ route('owner.tournaments.show', $tournament->id) }}" class="btn btn-sm btn-primary">Xem chi tiết</a>
                        <a href="{{ route('owner.tournaments.edit', $tournament->id) }}" class="btn btn-sm btn-secondary">Sửa</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div style="margin-top: 30px;">
            {{ $tournaments->links() }}
        </div>
        @else
        <div class="empty-state">
            <h2>Chưa có giải đấu nào</h2>
            <p style="color: #666; margin-bottom: 20px;">Tạo giải đấu đầu tiên để bắt đầu tổ chức các trận đấu!</p>
            <a href="{{ route('owner.tournaments.create') }}" class="btn btn-primary">Tạo giải đấu mới</a>
        </div>
        @endif
    </div>
</div>
@endsection
