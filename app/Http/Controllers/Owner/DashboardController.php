<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Field;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(\Illuminate\Http\Request $request)
    {
        $owner = auth()->user();
        $fieldIds = $owner->fields()->pluck('id');

        $dateFrom = $request->date_from ? \Carbon\Carbon::parse($request->date_from)->startOfDay() : now()->subDays(29)->startOfDay();
        $dateTo = $request->date_to ? \Carbon\Carbon::parse($request->date_to)->endOfDay() : now()->endOfDay();

        $stats = [
            'bookings_today' => Booking::whereIn('field_id', $fieldIds)
                ->whereDate('date', today())
                ->whereIn('status', ['pending', 'approved'])
                ->count(),

            'revenue_today' => Booking::whereIn('field_id', $fieldIds)
                ->whereDate('date', today())
                ->where('status', 'approved')
                ->sum('total_price'),

            'bookings_month' => Booking::whereIn('field_id', $fieldIds)
                ->whereMonth('date', now()->month)
                ->whereYear('date', now()->year)
                ->whereIn('status', ['pending', 'approved'])
                ->count(),

            'active_fields' => Field::where('owner_id', $owner->id)
                ->where('status', 'active')
                ->count(),

            'revenue_range' => Booking::whereIn('field_id', $fieldIds)
                ->whereBetween('date', [$dateFrom, $dateTo])
                ->where('status', 'approved')
                ->sum('total_price'),

            'bookings_range' => Booking::whereIn('field_id', $fieldIds)
                ->whereBetween('date', [$dateFrom, $dateTo])
                ->whereIn('status', ['pending', 'approved'])
                ->count(),
        ];

        $recentBookings = Booking::whereIn('field_id', $fieldIds)
            ->with(['field', 'user'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        $diffDays = $dateFrom->diffInDays($dateTo);
        $isPostgres = DB::connection()->getDriverName() === 'pgsql';

        if ($diffDays > 90) {
            $dateExpr = $isPostgres ? "TO_CHAR(date, 'YYYY-MM')" : "DATE_FORMAT(date, '%Y-%m')";
        } elseif ($diffDays > 60) {
            $dateExpr = $isPostgres ? "TO_CHAR(date, 'IYYY-IW')" : "DATE_FORMAT(date, '%x-W%v')";
        } else {
            $dateExpr = $isPostgres ? "date::date::text" : "DATE(date)";
        }

        $revenueChart = Booking::whereIn('field_id', $fieldIds)
            ->where('status', 'approved')
            ->whereBetween('date', [$dateFrom, $dateTo])
            ->select(
                DB::raw("{$dateExpr} as date"),
                DB::raw('SUM(total_price) as total'),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy(DB::raw($dateExpr))
            ->orderBy(DB::raw($dateExpr))
            ->get();

        $notifications = Booking::whereIn('field_id', $fieldIds)
            ->where('status', 'pending')
            ->with(['field', 'user'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $unreadBookings = Booking::whereIn('field_id', $fieldIds)
            ->where('status', 'pending')
            ->where('is_read', false)
            ->count();

        $unreadReviews = \App\Models\Review::whereIn('field_id', $fieldIds)
            ->where('is_read', false)
            ->count();

        $allNotifications = [
            'bookings' => Booking::whereIn('field_id', $fieldIds)
                ->where('status', 'pending')
                ->where('is_read', false)
                ->with(['field', 'user'])
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get(),
            'reviews' => \App\Models\Review::whereIn('field_id', $fieldIds)
                ->where('is_read', false)
                ->with(['user', 'field'])
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get(),
        ];

        return view('owner.dashboard', compact('stats', 'recentBookings', 'revenueChart', 'notifications', 'dateFrom', 'dateTo', 'unreadBookings', 'unreadReviews', 'allNotifications'));
}
