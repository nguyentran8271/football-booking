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

        // Chỉ từ chối nếu ca đó đã kết thúc (không chỉ check ngày)
        $shiftEnd = \App\Helpers\ShiftHelper::getShiftEndTime($booking->shift);
        if ($shiftEnd) {
            $shiftEndTime = \Carbon\Carbon::createFromFormat('Y-m-d H:i', $booking->date . ' ' . $shiftEnd, 'Asia/Ho_Chi_Minh');
            if (now('Asia/Ho_Chi_Minh')->gt($shiftEndTime)) {
                if (request()->wantsJson()) {
                    return response()->json(['success' => false, 'message' => 'Ca này đã kết thúc, không thể xác nhận.']);
                }
                return back()->with('error', 'Ca này đã kết thúc, không thể xác nhận.');
            }
        }

        $booking->update(['status' => 'approved', 'user_notified' => false]);

        if (request()->wantsJson()) {
            return response()->json(['success' => true]);
        }
        return back()->with('success', 'Đã xác nhận đặt sân thành công!');
    }

    public function cancel($id)
    {
        $booking = Booking::findOrFail($id);

        $fieldIds = auth()->user()->fields()->pluck('id');
        if (!$fieldIds->contains($booking->field_id)) {
            abort(403, 'Bạn không có quyền thao tác booking này');
        }

        $reason = request('cancel_reason');
        $booking->update(['status' => 'cancelled', 'user_notified' => false, 'cancel_reason' => $reason]);

        if (request()->wantsJson()) {
            return response()->json(['success' => true]);
        }
        return back()->with('success', 'Đã hủy đặt sân!');
    }
}
