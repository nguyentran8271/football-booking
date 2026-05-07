<?php

namespace App\Http\Controllers;

use App\Models\Tournament;
use App\Models\TournamentTeam;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TournamentController extends Controller
{
    public function index(Request $request)
    {
        $query = Tournament::with(['field', 'teams'])
            ->where('status', '!=', 'finished');

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('field_name')) {
            $query->whereHas('field', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->field_name . '%');
            });
        }

        $tournaments = $query->orderBy('start_date', 'asc')->paginate(12);

        return view('tournaments.index', compact('tournaments'));
    }

    public function show($id)
    {
        $tournament = Tournament::with(['field', 'teams' => function($query) {
            $query->where('status', 'approved');
        }])->findOrFail($id);

        $canRegister = $this->canRegister($tournament);

        return view('tournaments.show', compact('tournament', 'canRegister'));
    }

    public function register($id)
    {
        $tournament = Tournament::findOrFail($id);

        if (!$this->canRegister($tournament)) {
            return redirect()->route('tournaments.show', $id)
                ->with('error', 'Không thể đăng ký giải đấu này.');
        }

        return view('tournaments.register', compact('tournament'));
    }

    public function storeRegistration(Request $request, $id)
    {
        $tournament = Tournament::findOrFail($id);

        if (!$this->canRegister($tournament)) {
            return redirect()->route('tournaments.show', $id)
                ->with('error', 'Không thể đăng ký giải đấu này.');
        }

        $validated = $request->validate([
            'team_name' => 'required|string|max:255',
            'captain_name' => 'required|string|max:255',
            'phone' => [
                'required',
                'digits:10',
                function ($attribute, $value, $fail) use ($tournament) {
                    $exists = TournamentTeam::where('tournament_id', $tournament->id)
                        ->where('phone', $value)
                        ->exists();
                    if ($exists) {
                        $fail('Số điện thoại này đã được dùng để đăng ký giải đấu.');
                    }
                },
            ],
            'players_list' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'phone.digits' => 'Số điện thoại phải đúng 10 chữ số.',
            'phone.required' => 'Vui lòng nhập số điện thoại.',
        ]);

        $validated['tournament_id'] = $tournament->id;
        $validated['status'] = 'pending';

        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store('tournament-teams', 'public');
        }

        TournamentTeam::create($validated);

        return redirect()->route('tournaments.show', $id)
            ->with('success', 'Đăng ký thành công! Vui lòng chờ chủ sân duyệt.');
    }

    private function canRegister(Tournament $tournament)
    {
        if ($tournament->status != 'upcoming') {
            return false;
        }

        if ($tournament->registration_deadline && now()->gt($tournament->registration_deadline)) {
            return false;
        }

        $approvedTeams = $tournament->teams()->where('status', 'approved')->count();
        if ($approvedTeams >= $tournament->max_teams) {
            return false;
        }

        return true;
    }
}
