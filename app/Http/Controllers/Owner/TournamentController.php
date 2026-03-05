<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Tournament;
use App\Models\Field;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'field_id' => 'required|exists:fields,id',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',
            'registration_deadline' => 'nullable|date|before_or_equal:start_date',
            'max_teams' => 'required|integer|min:2|max:32',
            'players_per_team' => 'required|in:5,7,11',
            'entry_fee' => 'required|numeric|min:0',
            'prize' => 'nullable|string',
            'banner' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $validated['owner_id'] = auth()->id();

        if ($request->hasFile('banner')) {
            $validated['banner'] = $request->file('banner')->store('tournaments', 'public');
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

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'field_id' => 'required|exists:fields,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'registration_deadline' => 'nullable|date|before_or_equal:start_date',
            'max_teams' => 'required|integer|min:2|max:32',
            'players_per_team' => 'required|in:5,7,11',
            'entry_fee' => 'required|numeric|min:0',
            'prize' => 'nullable|string',
            'status' => 'required|in:upcoming,ongoing,finished',
            'banner' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('banner')) {
            if ($tournament->banner) {
                Storage::disk('public')->delete($tournament->banner);
            }
            $validated['banner'] = $request->file('banner')->store('tournaments', 'public');
        }

        $tournament->update($validated);

        return redirect()->route('owner.tournaments.index')
            ->with('success', 'Cập nhật giải đấu thành công!');
    }

    public function destroy($id)
    {
        $tournament = Tournament::where('owner_id', auth()->id())->findOrFail($id);

        if ($tournament->banner) {
            Storage::disk('public')->delete($tournament->banner);
        }

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
