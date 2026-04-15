<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Field;
use App\Helpers\ShiftHelper;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    /**
     * Hiển thị form đặt sân
     */
    public function create($fieldId)
    {
        $field = Field::findOrFail($fieldId);
        return view('bookings.create', compact('field'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'field_id'       => 'required|exists:fields,id',
            'date'           => 'required|date|after_or_equal:today',
            'shift'          => 'required|integer|between:1,8',
            'payment_method' => 'required|in:online,later',
        ], [
            'date.after_or_equal' => 'Ngày đặt phải từ hôm nay trở đi.',
            'shift.required'      => 'Vui lòng chọn ca.',
            'shift.between'       => 'Ca không hợp lệ.',
        ]);

        if (Booking::isShiftBooked($validated['field_id'], $validated['date'], $validated['shift'])) {
            return back()->withErrors(['error' => 'Ca này đã được đặt. Vui lòng chọn ca khác.'])->withInput();
        }

        $field = Field::findOrFail($validated['field_id']);
        $totalPrice = ShiftHelper::calculatePrice($field->price_per_hour);

        $booking = Booking::create([
            'user_id'        => auth()->id(),
            'field_id'       => $validated['field_id'],
            'date'           => $validated['date'],
            'shift'          => $validated['shift'],
            'total_price'    => $totalPrice,
            'status'         => 'pending',
            'payment_method' => $validated['payment_method'],
            'payment_status' => 'unpaid',
        ]);

        if ($validated['payment_method'] === 'online') {
            return redirect()->route('payment.checkout', $booking->id);
        }

        return redirect()->route('bookings.history')->with('success', 'Đặt sân thành công! Vui lòng chờ xác nhận.');
    }

    public function history()
    {
        $bookings = Booking::with('field')
            ->where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('bookings.history', compact('bookings'));
    }

    public function cancel($id)
    {
        $booking = Booking::findOrFail($id);

        if ($booking->user_id !== auth()->id()) {
            abort(403);
        }

        if ($booking->status !== 'pending') {
            return back()->withErrors(['error' => 'Không thể hủy booking này.']);
        }

        $booking->update(['status' => 'cancelled']);

        return back()->with('success', 'Đã hủy đặt sân.');
    }
}
