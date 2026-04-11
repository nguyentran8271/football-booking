<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function show($id)
    {
        $user = User::findOrFail($id);
        $bookings = $user->bookings()->with('field')->orderBy('created_at', 'desc')->paginate(10);
        return view('admin.users.show', compact('user', 'bookings'));
    }

    public function ownerShow($id, \Illuminate\Http\Request $request)
    {
        $owner = User::where('role', 'owner')->findOrFail($id);
        $fields = $owner->fields()->withCount('bookings')->get();

        $filterType = $request->get('filter_type', 'month');
        $filterYear = $request->get('filter_year', now()->year);
        $filterMonth = $request->get('filter_month', now()->month);

        $revenueQuery = \App\Models\Booking::whereIn('field_id', $owner->fields->pluck('id'))
            ->where('status', 'approved');

        if ($filterType === 'month') {
            $revenueQuery->whereYear('date', $filterYear)->whereMonth('date', $filterMonth);
        } else {
            $revenueQuery->whereYear('date', $filterYear);
        }

        $filteredRevenue = $revenueQuery->sum('total_price');

        $tournaments = \App\Models\Tournament::whereIn('field_id', $owner->fields->pluck('id'))->with(['field', 'teams'])->get();
        $recentBookings = \App\Models\Booking::whereIn('field_id', $owner->fields->pluck('id'))
            ->with(['user', 'field'])->orderBy('created_at', 'desc')->take(10)->get();

        return view('admin.users.owner-show', compact('owner', 'fields', 'filteredRevenue', 'tournaments', 'recentBookings', 'filterType', 'filterYear', 'filterMonth'));
    }

    public function index(Request $request)
    {
        $query = User::where('role', 'user');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%')
                  ->orWhere('phone', 'like', '%' . $request->search . '%');
            });
        }

        $users = $query->paginate(20)->withQueryString();
        return view('admin.users.index', compact('users'));
    }

    public function owners(Request $request)
    {
        $query = User::where('role', 'owner')->withCount('fields');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%')
                  ->orWhere('phone', 'like', '%' . $request->search . '%');
            });
        }

        $owners = $query->paginate(20)->withQueryString();
        $pendingRequests = User::where('owner_request', 'pending')->get();
        return view('admin.users.owners', compact('owners', 'pendingRequests'));
    }

    public function approveOwner($id)
    {
        $user = User::findOrFail($id);
        $user->update(['role' => 'owner', 'owner_request' => null]);
        return back()->with('success', 'Đã duyệt chủ sân.');
    }

    public function rejectOwner($id)
    {
        $user = User::findOrFail($id);
        $user->update(['owner_request' => 'rejected']);
        return back()->with('success', 'Đã từ chối đơn đăng ký.');
    }

    public function convertToOwner($id)
    {
        $user = User::findOrFail($id);
        if ($user->role !== 'user') {
            return back()->withErrors(['error' => 'Chỉ có thể chuyển user thành owner.']);
        }
        $user->update(['role' => 'owner']);
        return back()->with('success', 'Đã chuyển user thành owner.');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        if ($user->role === 'admin') {
            return back()->withErrors(['error' => 'Không thể xóa admin.']);
        }
        $user->delete();
        return back()->with('success', 'Đã xóa người dùng.');
    }
}
