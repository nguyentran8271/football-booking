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

        $alreadyRegistered = false;
        if (auth()->check()) {
            $alreadyRegistered = TournamentTeam::where('tournament_id', $tournament->id)
                ->where('user_id', auth()->id())
                ->whereIn('status', ['pending', 'approved'])
                ->exists();
        }

        return view('tournaments.show', compact('tournament', 'canRegister', 'alreadyRegistered'));
    }

    public function register($id)
    {
        $tournament = Tournament::findOrFail($id);

        if (!$this->canRegister($tournament)) {
            return redirect()->route('tournaments.show', $id)
                ->with('error', 'Không thể đăng ký giải đấu này.');
        }

        $alreadyRegistered = TournamentTeam::where('tournament_id', $id)
            ->where('user_id', auth()->id())
            ->whereIn('status', ['pending', 'approved'])
            ->exists();

        if ($alreadyRegistered) {
            return redirect()->route('tournaments.show', $id)
                ->with('info', 'Bạn đã đăng ký giải đấu này. Vui lòng chờ chủ sân duyệt.');
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

        $alreadyRegistered = TournamentTeam::where('tournament_id', $id)
            ->where('user_id', auth()->id())
            ->whereIn('status', ['pending', 'approved'])
            ->exists();

        if ($alreadyRegistered) {
            return redirect()->route('tournaments.show', $id)
                ->with('info', 'Bạn đã đăng ký giải đấu này. Vui lòng chờ chủ sân duyệt.');
        }

        $validated = $request->validate([
            'team_name'      => 'required|string|max:255',
            'captain_name'   => 'required|string|max:255',
            'phone'          => [
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
            'players_list'   => 'required|string',
            'logo'           => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'payment_method' => 'required|in:direct,sepay',
        ], [
            'phone.digits'          => 'Số điện thoại phải đúng 10 chữ số.',
            'phone.required'        => 'Vui lòng nhập số điện thoại.',
            'players_list.required' => 'Vui lòng nhập danh sách cầu thủ.',
            'payment_method.required' => 'Vui lòng chọn phương thức thanh toán.',
        ]);

        $validated['tournament_id']  = $tournament->id;
        $validated['user_id']        = auth()->id();
        $validated['status']         = 'pending';
        $validated['payment_status'] = 'unpaid';

        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store('tournament-teams', 'public');
        }

        $team = TournamentTeam::create($validated);

        // Thanh toán trực tiếp: chuyển về trang show, chờ chủ sân duyệt
        if ($validated['payment_method'] === 'direct') {
            return redirect()->route('tournaments.show', $id)
                ->with('success', 'Đăng ký thành công! Vui lòng thanh toán trực tiếp tại sân và chờ chủ sân duyệt.');
        }

        // Thanh toán SePay: redirect sang trang checkout
        return redirect()->route('tournaments.payment.checkout', $team->id);
    }

    public function paymentCheckout($teamId)
    {
        $team = TournamentTeam::with('tournament.field')->findOrFail($teamId);

        // Chỉ cho phép người đăng ký xem (kiểm tra phone hoặc user_id nếu có)
        if ($team->payment_status === 'paid') {
            return redirect()->route('tournaments.show', $team->tournament_id)
                ->with('info', 'Đội này đã thanh toán.');
        }

        $invoice = 'TRN-' . $team->id . '-' . time();
        $team->update(['payment_invoice' => $invoice]);

        try {
            $client = new \SePay\SePayClient(
                config('services.sepay.merchant_id'),
                config('services.sepay.secret_key'),
                config('services.sepay.env', 'sandbox')
            );

            $checkoutData = \SePay\Builders\CheckoutBuilder::make()
                ->currency('VND')
                ->orderInvoiceNumber($invoice)
                ->orderAmount((int) $team->tournament->entry_fee)
                ->operation('PURCHASE')
                ->orderDescription('Đăng ký giải đấu ' . $team->tournament->name . ' - Đội ' . $team->team_name)
                ->successUrl(route('tournaments.payment.success', $team->id))
                ->errorUrl(route('tournaments.payment.error', $team->id))
                ->cancelUrl(route('tournaments.payment.cancel', $team->id))
                ->build();

            $formHtml = $client->checkout()->generateFormHtml($checkoutData);
        } catch (\Exception $e) {
            return redirect()->route('tournaments.show', $team->tournament_id)
                ->with('error', 'Không thể kết nối cổng thanh toán. Vui lòng thử lại sau.');
        }

        return view('tournaments.payment', compact('team', 'formHtml'));
    }

    public function paymentSuccess($teamId)
    {
        $team = TournamentTeam::findOrFail($teamId);
        $team->update(['payment_status' => 'paid']);

        return redirect()->route('tournaments.show', $team->tournament_id)
            ->with('success', 'Thanh toán thành công! Vui lòng chờ chủ sân duyệt.');
    }

    public function paymentError($teamId)
    {
        $team = TournamentTeam::findOrFail($teamId);
        $team->update(['payment_status' => 'failed']);

        return redirect()->route('tournaments.show', $team->tournament_id)
            ->with('error', 'Thanh toán thất bại. Vui lòng thử lại.');
    }

    public function paymentCancel($teamId)
    {
        $team = TournamentTeam::findOrFail($teamId);

        return redirect()->route('tournaments.show', $team->tournament_id)
            ->with('info', 'Bạn đã hủy thanh toán.');
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
