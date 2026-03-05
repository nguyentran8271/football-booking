@extends('layouts.app')

@section('title', $tournament->name)

@push('styles')
<style>
.tournament-detail {
    padding: 40px 0;
}

.tournament-header {
    background: white;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 15px rgba(0,0,0,0.1);
    margin-bottom: 30px;
}

.tournament-banner-large {
    width: 100%;
    height: 350px;
    object-fit: cover;
    background: linear-gradient(135deg, #1e7e34 0%, #28a745 100%);
}

.tournament-header-content {
    padding: 35px;
}

.tournament-title {
    font-size: 36px;
    margin: 0 0 20px 0;
    color: #333;
}

.tournament-meta {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    margin-bottom: 25px;
}

.meta-item {
    display: flex;
    flex-direction: column;
    gap: 5px;
}

.meta-label {
    font-size: 13px;
    color: #999;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.meta-value {
    font-size: 18px;
    font-weight: 600;
    color: #333;
}

.tournament-actions {
    display: flex;
    gap: 15px;
    padding-top: 25px;
    border-top: 1px solid #eee;
}

.btn-register {
    padding: 15px 40px;
    background: #28a745;
    color: white;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 600;
    font-size: 16px;
    transition: background 0.3s;
    border: none;
    cursor: pointer;
}

.btn-register:hover {
    background: #1e7e34;
}

.btn-register:disabled {
    background: #ccc;
    cursor: not-allowed;
}

.btn-back {
    padding: 15px 30px;
    background: #6c757d;
    color: white;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 500;
    transition: background 0.3s;
}

.btn-back:hover {
    background: #5a6268;
}

.section-card {
    background: white;
    border-radius: 12px;
    padding: 30px;
    box-shadow: 0 2px 15px rgba(0,0,0,0.1);
    margin-bottom: 30px;
}

.section-title {
    font-size: 24px;
    margin: 0 0 20px 0;
    color: #333;
    padding-bottom: 15px;
    border-bottom: 2px solid #28a745;
}

.description-text {
    color: #666;
    line-height: 1.8;
    font-size: 15px;
}

.prize-text {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 8px;
    white-space: pre-line;
    line-height: 1.8;
    color: #333;
}

.teams-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 20px;
}

.team-card {
    border: 2px solid #e0e0e0;
    border-radius: 10px;
    padding: 20px;
    text-align: center;
    background: #fafafa;
}

.team-logo {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    object-fit: cover;
    margin: 0 auto 15px;
    background: white;
    border: 3px solid #28a745;
}

.team-name {
    font-size: 18px;
    font-weight: 600;
    margin: 0 0 8px 0;
    color: #333;
}

.team-captain {
    font-size: 14px;
    color: #666;
    margin-bottom: 5px;
}

.team-phone {
    font-size: 13px;
    color: #999;
}

.empty-teams {
    text-align: center;
    padding: 40px 20px;
    color: #999;
}

.registration-closed {
    background: #fff3cd;
    border: 1px solid #ffc107;
    padding: 15px 20px;
    border-radius: 8px;
    margin-bottom: 20px;
    color: #856404;
}
</style>
@endpush

@section('content')
<div class="tournament-detail">
    <div class="container">
        <div class="tournament-header">
            @if($tournament->banner)
            <img src="{{ asset('storage/' . $tournament->banner) }}" alt="{{ $tournament->name }}" class="tournament-banner-large">
            @else
            <div class="tournament-banner-large"></div>
            @endif

            <div class="tournament-header-content">
                <h1 class="tournament-title">{{ $tournament->name }}</h1>

                <div class="tournament-meta">
                    <div class="meta-item">
                        <span class="meta-label">Trạng thái</span>
                        <span class="meta-value">
                            @if($tournament->status == 'upcoming') Sắp diễn ra
                            @elseif($tournament->status == 'ongoing') Đang diễn ra
                            @else Đã kết thúc
                            @endif
                        </span>
                    </div>
                    <div class="meta-item">
                        <span class="meta-label">Sân tổ chức</span>
                        <span class="meta-value">{{ $tournament->field->name }}</span>
                    </div>
                    <div class="meta-item">
                        <span class="meta-label">Thời gian</span>
                        <span class="meta-value">{{ $tournament->start_date->format('d/m/Y') }} - {{ $tournament->end_date->format('d/m/Y') }}</span>
                    </div>
                    <div class="meta-item">
                        <span class="meta-label">Số đội</span>
                        <span class="meta-value">{{ $tournament->teams->where('status', 'approved')->count() }}/{{ $tournament->max_teams }} đội</span>
                    </div>
                    <div class="meta-item">
                        <span class="meta-label">Số người/đội</span>
                        <span class="meta-value">{{ $tournament->players_per_team }} người</span>
                    </div>
                    <div class="meta-item">
                        <span class="meta-label">Phí tham gia</span>
                        <span class="meta-value">{{ number_format($tournament->entry_fee) }}đ</span>
                    </div>
                    @if($tournament->registration_deadline)
                    <div class="meta-item">
                        <span class="meta-label">Hạn đăng ký</span>
                        <span class="meta-value">{{ $tournament->registration_deadline->format('d/m/Y') }}</span>
                    </div>
                    @endif
                </div>

                @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                @if(!$canRegister)
                <div class="registration-closed">
                    @if($tournament->status != 'upcoming')
                        Giải đấu đã bắt đầu hoặc kết thúc.
                    @elseif($tournament->registration_deadline && now()->gt($tournament->registration_deadline))
                        Đã hết hạn đăng ký.
                    @else
                        Đã đủ số đội tham gia.
                    @endif
                </div>
                @endif

                <div class="tournament-actions">
                    @auth
                        @if($canRegister)
                        <a href="{{ route('tournaments.register', $tournament->id) }}" class="btn-register">Đăng ký tham gia</a>
                        @else
                        <button class="btn-register" disabled>Không thể đăng ký</button>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="btn-register">Đăng nhập để đăng ký</a>
                    @endauth
                    <a href="{{ route('tournaments.index') }}" class="btn-back">Quay lại</a>
                </div>
            </div>
        </div>

        @if($tournament->description)
        <div class="section-card">
            <h2 class="section-title">Mô tả giải đấu</h2>
            <div class="description-text">{{ $tournament->description }}</div>
        </div>
        @endif

        @if($tournament->prize)
        <div class="section-card">
            <h2 class="section-title">Giải thưởng</h2>
            <div class="prize-text">{{ $tournament->prize }}</div>
        </div>
        @endif

        <div class="section-card">
            <h2 class="section-title">Danh sách đội tham gia ({{ $tournament->teams->count() }})</h2>

            @if($tournament->teams->count() > 0)
            <div class="teams-grid">
                @foreach($tournament->teams as $team)
                <div class="team-card">
                    @if($team->logo)
                    <img src="{{ asset('storage/' . $team->logo) }}" alt="{{ $team->team_name }}" class="team-logo">
                    @else
                    <div class="team-logo" style="display: flex; align-items: center; justify-content: center; font-size: 32px; color: #28a745;">⚽</div>
                    @endif
                    <h3 class="team-name">{{ $team->team_name }}</h3>
                    <p class="team-captain">Đội trưởng: {{ $team->captain_name }}</p>
                    <p class="team-phone">{{ $team->phone }}</p>
                </div>
                @endforeach
            </div>
            @else
            <div class="empty-teams">
                <p>Chưa có đội nào đăng ký</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
