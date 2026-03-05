<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Field;
use App\Models\Booking;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Dashboard cho Admin
     */
    public function index()
    {
        // Thống kê tổng quan
        $totalUsers = User::where('role', 'user')->count();
        $totalOwners = User::where('role', 'owner')->count();
        $totalFields = Field::count();

        $monthlyBookings = Booking::whereMonth('date', now()->month)
            ->whereYear('date', now()->year)
            ->count();

        $yearlyBookings = Booking::whereYear('date', now()->year)->count();

        $monthlyRevenue = Booking::where('status', 'approved')
            ->whereMonth('date', now()->month)
            ->whereYear('date', now()->year)
            ->sum('total_price');

        $yearlyRevenue = Booking::where('status', 'approved')
            ->whereYear('date', now()->year)
            ->sum('total_price');

        // Booking gần đây
        $recentBookings = Booking::with(['user', 'field'])
            ->latest()
            ->take(10)
            ->get();

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalOwners',
            'totalFields',
            'monthlyBookings',
            'yearlyBookings',
            'monthlyRevenue',
            'yearlyRevenue',
            'recentBookings'
        ));
    }
}
