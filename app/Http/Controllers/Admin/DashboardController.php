<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Field;
use App\Models\Booking;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $totalUsers = User::where('role', 'user')->count();
        $totalOwners = User::where('role', 'owner')->count();
        $totalFields = Field::count();

        $monthlyBookings = Booking::whereIn('status', ['pending', 'approved'])
            ->whereMonth('date', now()->month)
            ->whereYear('date', now()->year)
            ->count();

        $yearlyBookings = Booking::whereIn('status', ['pending', 'approved'])
            ->whereYear('date', now()->year)
            ->count();

        $monthlyRevenue = Booking::where('status', 'approved')
            ->whereMonth('date', now()->month)
            ->whereYear('date', now()->year)
            ->sum('total_price');

        $yearlyRevenue = Booking::where('status', 'approved')
            ->whereYear('date', now()->year)
            ->sum('total_price');

        $recentBookings = Booking::with(['user', 'field'])
            ->latest()
            ->take(10)
            ->get();

        $adminUnreadReviews = 0;
        $adminUnreadOwnerRequests = 0;
        $adminNotifications = ['reviews' => collect(), 'owner_requests' => collect()];

        try {
            $adminUnreadReviews = \App\Models\Review::whereNull('field_id')
                ->where('is_read', false)
                ->count();

            $adminUnreadOwnerRequests = User::where('owner_request', 'pending')->count();

            $adminNotifications = [
                'reviews' => \App\Models\Review::whereNull('field_id')
                    ->where('is_read', false)
                    ->with('user')
                    ->orderBy('created_at', 'desc')
                    ->limit(10)
                    ->get(),
                'owner_requests' => User::where('owner_request', 'pending')
                    ->orderBy('updated_at', 'desc')
                    ->limit(10)
                    ->get(),
            ];
        } catch (\Exception $e) {
            // Column may not exist yet
        }

        $hotFilterType = $request->get('hot_filter', 'month');
        $hotYear  = $request->get('hot_year', now()->year);
        $hotMonth = $request->get('hot_month', now()->month);

        $hotQuery = Booking::with('field.owner')
            ->whereIn('status', ['pending', 'approved'])
            ->selectRaw('field_id, COUNT(*) as total_bookings, SUM(total_price) as total_revenue')
            ->groupBy('field_id')
            ->orderByDesc('total_bookings')
            ->take(5);

        if ($hotFilterType === 'month') {
            $hotQuery->whereMonth('date', $hotMonth)->whereYear('date', $hotYear);
        } else {
            $hotQuery->whereYear('date', $hotYear);
        }

        $hotFields = $hotQuery->get();

        return view('admin.dashboard', compact(
            'totalUsers', 'totalOwners', 'totalFields',
            'monthlyBookings', 'yearlyBookings',
            'monthlyRevenue', 'yearlyRevenue',
            'recentBookings', 'hotFields',
            'hotFilterType', 'hotYear', 'hotMonth',
            'adminUnreadReviews', 'adminUnreadOwnerRequests', 'adminNotifications'
        ));
    }
}
