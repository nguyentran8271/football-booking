@extends('layouts.app')

@section('title', $tournament->name)

@push('styles')
<style>
.tournament-detail-container {
    padding: 30px 0;
}

.tournament-header {
    background: white;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    margin-bottom: 30px;
}

.tournament-banner-large {
    width: 100%;
    height: 300px;
    object-fit: cover;
    background: linear-gradient(135deg, #1e7e34 0%, #28a745 100%);
}

.tournament-header-content {
    padding: 30px;
}

.tournament-title-large {
    font-size: 32px;
    margin: 0 0 15px 0;
    color: #333;
}

.tournament-meta {
    display: flex;
    gap: 30px;
    flex-wrap: wrap;
    margin-bottom: 20px;
}

.tournament-meta-item {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 16px;
}

.tournament-description {
    color: #666;
    line-height: 1.6;
    margin-bottom: 20px;
}

.tournament-actions-header {
    display: flex;
    gap: 10px;
}

.section-card {
    background: white;
    border-radius: 10px;
    padding: 25px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    margin-bottom: 30px;
}

.section-title {
    font-size: 20px;
    margin: 0 0 20px 0;
    color: #333;
}

.teams-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 20px;
}

.team-card {
    border: 2px solid #e0e0e0;
    border-radius: 8px;
    padding: 20px;
    transition: border-color 0.3s;
}

.team-card.pending {
    border-color: #ffc107;
    background: #fff8e1;
}

.team-card.approved {
    border-color: #28a745;
    background: #e8f5e9;
}

.team-card.rejected {
    border-color: #dc3545;
    background: #f8d7da;
}

.team-header {
    display: flex;
    align-items: center;
    gap: 15px;
    margin-bottom: 15px;
}

.team-logo {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    object-fit: cover;
    background: #f0f0f0;
}

.team-info h3 {
    margin: 0 0 5px 0;
    font-size: 18px;
}

.team-info p {
    margin: 0;
    font-size: 14px;
    color: #666;
}

.team-details {
    margin-bottom: 15px;
}

.team-detail-item {
    display: flex;
    gap: 8px;
    margin-bottom: 8px;
    font-size: 14px;
}

.team-actions {
    display: flex;
    gap: 10px;
}

.team-actions .btn {
    flex: 1;
    font-size: 13px;
    padding: 8px 12px;
}

.empty-teams {
    text-align: center;
    padding: 40px 20px;
    color: #999;
}

.prize-section {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 8px;
    white-space: pre-line;
    line-height: 1.8;
}
</style>
@endpush

@section('content')
<div class="tournament-detail-container">
    <div class="container">
        <div class="tournament-header">
            @if($tournament->banner)
            <img src="{{ asset('storage/' . $tournament->banner) }}" alt="{{ $tournament->name }}" class="tournament-banner-large">
            @else
            <div class="tournament-banner-large"></div>
            @endif

            <div class="tournament-header-content">
                <h1 class="tournament-title-large">{{ $tournament->name }}</h1>

                <div class="tournament-meta">
                    <div class="tournament-meta-item">
                        <span>Trạng thái:</span>
                        <span class="tournament-status status-{{ $tournament->status }}">
                            @if($tournament->status == 'upcoming') Sắp diễn ra
                            @elseif($tournament->status == 'ongoing') Đang diễn ra
                            @else Đã kết thúc
                            @endif
                        </span>
                    </div>
                    <div class="tournament-meta-item">
                        <span>Sân:</span>
                        <span>{{ $tournament->field->name }}</span>
                    </div>
                    <div class="tournament-meta-item">
                        <span>Thời gian:</span>
                        <span>{{ $tournament->start_date }} → {{ $tournament->end_date }}</span>
                    </div>
                    <div class="tournament-meta-item">
                        <span>Đội:</span>
                        <span>{{ $tournament->teams->count() }}/{{ $tournament->max_teams }} đội</span>
                    </div>
                    <div class="tournament-meta-item">
                        <span>Số người:</span>
                        <span>{{ $tournament->players_per_team }} người/đội</span>
                    </div>
                    <div class="tournament-meta-item">
                        <span>Phí:</span>
                        <span>{{ number_format($tournament->entry_fee) }}đ/đội</span>
                    </div>
                </div>

                @if($tournament->description)
                <div class="tournament-description">
                    {{ $tournament->description }}
                </div>
                @endif

                <div class="tournament-actions-header">
                    <a href="{{ route('owner.tournaments.edit', $tournament->id) }}" class="btn btn-primary">Chỉnh sửa</a>
                    <a href="{{ route('owner.tournaments.index') }}" class="btn btn-secondary">Quay lại</a>
                </div>
            </div>
        </div>

        @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if($tournament->prize)
        <div class="section-card">
            <h2 class="section-title">Giải thưởng</h2>
            <div class="prize-section">{{ $tournament->prize }}</div>
        </div>
        @endif

        <div class="section-card">
            <h2 class="section-title">Danh sách đội tham gia ({{ $tournament->teams->count() }})</h2>

            @if($tournament->teams->count() > 0)
            <div class="teams-grid">
                @foreach($tournament->teams as $team)
                <div class="team-card {{ $team->status }}">
                    <div class="team-header">
                        @if($team->logo)
                        <img src="{{ asset('storage/' . $team->logo) }}" alt="{{ $team->team_name }}" class="team-logo">
                        @else
                        <div class="team-logo" style="display: flex; align-items: center; justify-content: center; font-size: 24px;">⚽</div>
                        @endif
                        <div class="team-info">
                            <h3>{{ $team->team_name }}</h3>
                            <p>
                                @if($team->status == 'pending') Chờ duyệt
                                @elseif($team->status == 'approved') Đã duyệt
                                @else Đã từ chối
                                @endif
                            </p>
                        </div>
                    </div>

                    <div class="team-details">
                        <div class="team-detail-item">
                            <span>Đội trưởng:</span>
                            <span>{{ $team->captain_name }}</span>
                        </div>
                        <div class="team-detail-item">
                            <span>SĐT:</span>
                            <span>{{ $team->phone }}</span>
                        </div>
                        <div class="team-detail-item">
                            <span>Đăng ký:</span>
                            <span>{{ $team->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                    </div>

                    @if($team->status == 'pending')
                    <div class="team-actions">
                        <form action="{{ route('owner.tournaments.teams.approve', [$tournament->id, $team->id]) }}" method="POST" style="flex: 1;">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-success" style="width: 100%;">Duyệt</button>
                        </form>
                        <form action="{{ route('owner.tournaments.teams.reject', [$tournament->id, $team->id]) }}" method="POST" style="flex: 1;">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-danger" style="width: 100%;">Từ chối</button>
                        </form>
                    </div>
                    @endif
                </div>
                @endforeach
            </div>
            @else
            <div class="empty-teams">
                <p>Chưa có đội nào đăng ký tham gia</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
