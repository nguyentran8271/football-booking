<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $fieldIds = auth()->user()->fields()->pluck('id');

        $query = Booking::with(['user', 'field'])->whereIn('field_id', $fieldIds);

        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        if ($request->has('date') && $request->date != '') {
            $query->whereDate('date', $request->date);
        }

        $bookings = $query->orderBy('date', 'desc')->orderBy('shift', 'desc')->paginate(20);

        return view('owner.bookings.index', compact('bookings'));
    }

    public function confirm($id)
    {
        $booking = Booking::findOrFail($id);

        $fieldIds = auth()->user()->fields()->pluck('id');
        if (!$fieldIds->contains($booking->field_id)) {
            abort(403, 'Bạn không có quyền thao tác booking này');
        }

        if (\Carbon\Carbon::parse($booking->date)->isPast()) {
            return back()->with('error', 'Không thể xác nhận booking đã qua ngày đặt sân.');
        }

        $booking->update(['status' => 'approved', 'user_notified' => false]);

        return back()->with('success', 'Đã xác nhận đặt sân thành công!');
    }

    public function cancel($id)
    {
        $booking = Booking::findOrFail($id);

        $fieldIds = auth()->user()->fields()->pluck('id');
        if (!$fieldIds->contains($booking->field_id)) {
            abort(403, 'Bạn không có quyền thao tác booking này');
        }

        $booking->update(['status' => 'cancelled', 'user_notified' => false]);

        return back()->with('success', 'Đã hủy đặt sân!');
    }
}
