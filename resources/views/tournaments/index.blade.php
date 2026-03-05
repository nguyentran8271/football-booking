@extends('layouts.app')

@section('title', 'Giải đấu')

@push('styles')
<style>
.tournaments-page {
    padding: 40px 0;
    min-height: 60vh;
}

.page-header {
    text-align: center;
    margin-bottom: 40px;
}

.page-header h1 {
    font-size: 36px;
    margin-bottom: 10px;
    color: #333;
}

.page-header p {
    color: #666;
    font-size: 16px;
}

.tournaments-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
    gap: 30px;
    margin-bottom: 40px;
}

.tournament-card {
    background: white;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 15px rgba(0,0,0,0.1);
    transition: transform 0.3s, box-shadow 0.3s;
}

.tournament-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 5px 25px rgba(0,0,0,0.15);
}

.tournament-banner {
    width: 100%;
    height: 200px;
    object-fit: cover;
    background: linear-gradient(135deg, #1e7e34 0%, #28a745 100%);
}

.tournament-content {
    padding: 25px;
}

.tournament-name {
    font-size: 22px;
    font-weight: 600;
    margin: 0 0 15px 0;
    color: #333;
}

.tournament-info {
    display: flex;
    flex-direction: column;
    gap: 10px;
    margin-bottom: 20px;
}

.tournament-info-row {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 14px;
    color: #666;
}

.tournament-info-row strong {
    color: #333;
    min-width: 100px;
}

.tournament-status {
    display: inline-block;
    padding: 6px 14px;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 500;
    margin-bottom: 15px;
}

.status-upcoming { background: #fff3cd; color: #856404; }
.status-ongoing { background: #d4edda; color: #155724; }

.tournament-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-top: 15px;
    border-top: 1px solid #eee;
}

.teams-count {
    font-size: 14px;
    color: #666;
}

.teams-count strong {
    color: #28a745;
    font-size: 16px;
}

.btn-view {
    padding: 10px 20px;
    background: #28a745;
    color: white;
    border-radius: 6px;
    text-decoration: none;
    font-weight: 500;
    transition: background 0.3s;
}

.btn-view:hover {
    background: #1e7e34;
}

.empty-state {
    text-align: center;
    padding: 80px 20px;
}

.empty-state h2 {
    font-size: 24px;
    color: #666;
    margin-bottom: 10px;
}

.empty-state p {
    color: #999;
}

/* Search Section */
.search-section {
    background: white;
    padding: 25px;
    border-radius: 12px;
    box-shadow: 0 2px 15px rgba(0,0,0,0.08);
    margin-bottom: 30px;
}

.search-grid {
    display: grid;
    grid-template-columns: 1fr 1fr auto;
    gap: 15px;
    align-items: end;
}

.search-field {
    display: flex;
    flex-direction: column;
}

.search-field .form-control {
    width: 100%;
    padding: 10px 15px;
    border: 2px solid #e0e0e0;
    border-radius: 8px;
    font-size: 14px;
    transition: border-color 0.3s;
}

.search-field .form-control:focus {
    outline: none;
    border-color: #28a745;
}

.search-field .form-control:disabled {
    background: #f5f5f5;
    cursor: not-allowed;
}

.search-actions {
    display: flex;
    gap: 10px;
}

.btn-search, .btn-reset {
    padding: 10px 25px;
    border-radius: 8px;
    font-weight: 500;
    font-size: 14px;
    border: none;
    cursor: pointer;
    transition: all 0.3s;
    text-decoration: none;
    display: inline-block;
}

.btn-search {
    background: #28a745;
    color: white;
}

.btn-search:hover {
    background: #1e7e34;
}

.btn-reset {
    background: #6c757d;
    color: white;
}

.btn-reset:hover {
    background: #5a6268;
}

@media (max-width: 1024px) {
    .search-grid {
        grid-template-columns: 1fr 1fr;
    }

    .search-actions {
        grid-column: 1 / -1;
        justify-content: center;
    }
}

@media (max-width: 768px) {
    .search-grid {
        grid-template-columns: 1fr;
    }

    .search-actions {
        flex-direction: column;
    }

    .btn-search, .btn-reset {
        width: 100%;
    }
}
</style>
@endpush

@section('content')
<div class="tournaments-page">
    <div class="container">
        <div class="page-header">
            <h1>Giải đấu</h1>
            <p>Tham gia các giải đấu bóng đá chuyên nghiệp</p>
        </div>

        <!-- Search Form -->
        <div class="search-section">
            <form method="GET" action="{{ route('tournaments.index') }}" id="searchForm">
                <div class="search-grid">
                    <div class="search-field">
                        <input type="text" name="search" class="form-control" placeholder="Tìm theo tên giải đấu..." value="{{ request('search') }}">
                    </div>
                    <div class="search-field">
                        <input type="text" name="field_name" class="form-control" placeholder="Tìm theo tên sân..." value="{{ request('field_name') }}">
                    </div>
                    <div class="search-actions">
                        <button type="submit" class="btn btn-search">Tìm kiếm</button>
                        <a href="{{ route('tournaments.index') }}" class="btn btn-reset">Đặt lại</a>
                    </div>
                </div>
            </form>
        </div>

        @if($tournaments->count() > 0)
        <div class="tournaments-grid">
            @foreach($tournaments as $tournament)
            <div class="tournament-card">
                @if($tournament->banner)
                <img src="{{ asset('storage/' . $tournament->banner) }}" alt="{{ $tournament->name }}" class="tournament-banner">
                @else
                <div class="tournament-banner"></div>
                @endif

                <div class="tournament-content">
                    <span class="tournament-status status-{{ $tournament->status }}">
                        @if($tournament->status == 'upcoming') Sắp diễn ra
                        @else Đang diễn ra
                        @endif
                    </span>

                    <h3 class="tournament-name">{{ $tournament->name }}</h3>

                    <div class="tournament-info">
                        <div class="tournament-info-row">
                            <strong>Sân:</strong>
                            <span>{{ $tournament->field->name }}</span>
                        </div>
                        <div class="tournament-info-row">
                            <strong>Thời gian:</strong>
                            <span>{{ $tournament->start_date->format('d/m/Y') }} - {{ $tournament->end_date->format('d/m/Y') }}</span>
                        </div>
                        <div class="tournament-info-row">
                            <strong>Số người:</strong>
                            <span>{{ $tournament->players_per_team }} người/đội</span>
                        </div>
                        <div class="tournament-info-row">
                            <strong>Phí:</strong>
                            <span>{{ number_format($tournament->entry_fee) }}đ/đội</span>
                        </div>
                    </div>

                    <div class="tournament-footer">
                        <div class="teams-count">
                            <strong>{{ $tournament->teams->where('status', 'approved')->count() }}</strong>/{{ $tournament->max_teams }} đội
                        </div>
                        <a href="{{ route('tournaments.show', $tournament->id) }}" class="btn-view">Xem chi tiết</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        {{ $tournaments->links() }}
        @else
        <div class="empty-state">
            <h2>Chưa có giải đấu nào</h2>
            <p>Hiện tại chưa có giải đấu nào đang mở đăng ký</p>
        </div>
        @endif
    </div>
</div>
@endsection
