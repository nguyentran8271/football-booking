<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    /**
     * Danh sách tất cả booking
     */
    public function index(Request $request)
    {
        $query = Booking::with(['user', 'field'])
            ->orderBy('date', 'desc')
            ->orderBy('shift', 'asc');

        if ($request->filled('field_name')) {
            $query->whereHas('field', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->field_name . '%');
            });
        }

        if ($request->filled('date')) {
            $query->whereDate('date', $request->date);
        }

        $bookings = $query->paginate(20)->withQueryString();

        return view('admin.bookings.index', compact('bookings'));
    }

    /**
     * Xóa booking
     */
    public function destroy($id)
    {
        $booking = Booking::findOrFail($id);
        $booking->delete();

        return back()->with('success', 'Đã xóa booking.');
    }
}
