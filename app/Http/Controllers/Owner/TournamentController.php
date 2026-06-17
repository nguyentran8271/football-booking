<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Tournament;
use App\Models\Field;
use App\Services\UploadService;
use Illuminate\Http\Request;

class TournamentController extends Controller
{
    public function index()
    {
        $tournaments = Tournament::where('owner_id', auth()->id())
            ->with(['field', 'teams'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('owner.tournaments.index', compact('tournaments'));
    }

    public function create()
    {
        $fields = Field::where('owner_id', auth()->id())
            ->where('status', 'active')
            ->get();
        return view('owner.tournaments.create', compact('fields'));
    }

    public function store(Request $request)
    {
        $messages = [
            'name.required'                         => 'Vui lòng nhập tên giải đấu.',
            'field_id.required'                     => 'Vui lòng chọn sân thi đấu.',
            'field_id.exists'                       => 'Sân thi đấu không hợp lệ.',
            'start_date.required'                   => 'Vui lòng nhập ngày bắt đầu.',
            'start_date.after_or_equal'             => 'Ngày bắt đầu phải từ hôm nay trở đi.',
            'end_date.required'                     => 'Vui lòng nhập ngày kết thúc.',
            'end_date.after'                        => 'Ngày kết thúc phải sau ngày bắt đầu.',
            'registration_deadline.before_or_equal' => 'Hạn đăng ký phải trước hoặc bằng ngày bắt đầu.',
            'max_teams.required'                    => 'Vui lòng nhập số đội tối đa.',
            'max_teams.min'                         => 'Số đội tối thiểu là 2.',
            'max_teams.max'                         => 'Số đội tối đa là 32.',
            'players_per_team.required'             => 'Vui lòng chọn số người mỗi đội.',
            'players_per_team.in'                   => 'Số người mỗi đội phải là 5, 7 hoặc 11.',
            'entry_fee.required'                    => 'Vui lòng nhập phí tham gia.',
            'entry_fee.min'                         => 'Phí tham gia không được âm.',
            'banner.image'                          => 'Banner phải là file ảnh.',
            'banner.max'                            => 'Banner không được vượt quá 2MB.',
        ];

        $validated = $request->validate([
            'name'                  => 'required|string|max:255',
            'description'           => 'nullable|string',
            'field_id'              => 'required|exists:fields,id',
            'start_date'            => 'required|date|after_or_equal:today',
            'end_date'              => 'required|date|after:start_date',
            'registration_deadline' => 'nullable|date|before_or_equal:start_date',
            'max_teams'             => 'required|integer|min:2|max:32',
            'players_per_team'      => 'required|in:5,7,11',
            'entry_fee'             => 'required|numeric|min:0',
            'prize'                 => 'nullable|string',
            'banner'                => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ], $messages);

        $validated['owner_id'] = auth()->id();

        if ($request->hasFile('banner')) {
            $validated['banner'] = UploadService::upload($request->file('banner'), 'tournaments');
        }

        Tournament::create($validated);

        return redirect()->route('owner.tournaments.index')
            ->with('success', 'Tạo giải đấu thành công!');
    }

    public function show($id)
    {
        $tournament = Tournament::where('owner_id', auth()->id())
            ->with(['field', 'teams'])
            ->findOrFail($id);

        return view('owner.tournaments.show', compact('tournament'));
    }

    public function edit($id)
    {
        $tournament = Tournament::where('owner_id', auth()->id())->findOrFail($id);
        $fields = Field::where('owner_id', auth()->id())
            ->where('status', 'active')
            ->get();

        return view('owner.tournaments.edit', compact('tournament', 'fields'));
    }

    public function update(Request $request, $id)
    {
        $tournament = Tournament::where('owner_id', auth()->id())->findOrFail($id);

        $messages = [
            'name.required'                         => 'Vui lòng nhập tên giải đấu.',
            'field_id.required'                     => 'Vui lòng chọn sân thi đấu.',
            'end_date.after'                        => 'Ngày kết thúc phải sau ngày bắt đầu.',
            'registration_deadline.before_or_equal' => 'Hạn đăng ký phải trước hoặc bằng ngày bắt đầu.',
            'max_teams.min'                         => 'Số đội tối thiểu là 2.',
            'max_teams.max'                         => 'Số đội tối đa là 32.',
            'players_per_team.in'                   => 'Số người mỗi đội phải là 5, 7 hoặc 11.',
            'entry_fee.min'                         => 'Phí tham gia không được âm.',
            'banner.image'                          => 'Banner phải là file ảnh.',
            'banner.max'                            => 'Banner không được vượt quá 2MB.',
            'status.in'                             => 'Trạng thái không hợp lệ.',
        ];

        $validated = $request->validate([
            'name'                  => 'required|string|max:255',
            'description'           => 'nullable|string',
            'field_id'              => 'required|exists:fields,id',
            'start_date'            => 'required|date',
            'end_date'              => 'required|date|after:start_date',
            'registration_deadline' => 'nullable|date|before_or_equal:start_date',
            'max_teams'             => 'required|integer|min:2|max:32',
            'players_per_team'      => 'required|in:5,7,11',
            'entry_fee'             => 'required|numeric|min:0',
            'prize'                 => 'nullable|string',
            'status'                => 'required|in:upcoming,ongoing,finished',
            'banner'                => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ], $messages);

        if ($request->hasFile('banner')) {
            UploadService::delete($tournament->banner);
            $validated['banner'] = UploadService::upload($request->file('banner'), 'tournaments');
        }

        $tournament->update($validated);

        return redirect()->route('owner.tournaments.index')
            ->with('success', 'Cập nhật giải đấu thành công!');
    }

    public function destroy($id)
    {
        $tournament = Tournament::where('owner_id', auth()->id())->findOrFail($id);

        UploadService::delete($tournament->banner);
        $tournament->delete();

        return redirect()->route('owner.tournaments.index')
            ->with('success', 'Xóa giải đấu thành công!');
    }

    public function approveTeam($tournamentId, $teamId)
    {
        $tournament = Tournament::where('owner_id', auth()->id())->findOrFail($tournamentId);
        $team = $tournament->teams()->findOrFail($teamId);

        $team->update(['status' => 'approved']);

        return back()->with('success', 'Đã duyệt đội tham gia!');
    }

    public function rejectTeam($tournamentId, $teamId)
    {
        $tournament = Tournament::where('owner_id', auth()->id())->findOrFail($tournamentId);
        $team = $tournament->teams()->findOrFail($teamId);

        $team->update(['status' => 'rejected']);

        return back()->with('success', 'Đã từ chối đội tham gia!');
    }
}
