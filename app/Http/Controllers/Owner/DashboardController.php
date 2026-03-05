<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Field;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $owner = auth()->user();
        $fieldIds = $owner->fields()->pluck('id');

        // Thống kê
        $stats = [
            'bookings_today' => Booking::whereIn('field_id', $fieldIds)
                ->whereDate('date', today())
                ->count(),

            'revenue_today' => Booking::whereIn('field_id', $fieldIds)
                ->whereDate('date', today())
                ->where('status', 'approved')
                ->sum('total_price'),

            'bookings_month' => Booking::whereIn('field_id', $fieldIds)
                ->whereMonth('date', now()->month)
                ->whereYear('date', now()->year)
                ->count(),

            'active_fields' => Field::where('owner_id', $owner->id)
                ->where('status', 'active')
                ->count(),
        ];

        // Booking gần đây
        $recentBookings = Booking::whereIn('field_id', $fieldIds)
            ->with(['field', 'user'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Doanh thu 7 ngày gần đây
        $revenueChart = Booking::whereIn('field_id', $fieldIds)
            ->where('status', 'approved')
            ->where('date', '>=', now()->subDays(7))
            ->select(
                DB::raw('DATE(date) as date'),
                DB::raw('SUM(total_price) as total')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Thông báo
        $notifications = Booking::whereIn('field_id', $fieldIds)
            ->where('status', 'pending')
            ->with(['field', 'user'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('owner.dashboard', compact('stats', 'recentBookings', 'revenueChart', 'notifications'));
    }
}
