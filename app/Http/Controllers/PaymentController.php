<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use SePay\SePayClient;
use SePay\Builders\CheckoutBuilder;

class PaymentController extends Controller
{
    private function client()
    {
        return new SePayClient(
            config('services.sepay.merchant_id'),
            config('services.sepay.secret_key'),
            config('services.sepay.env', 'sandbox')
        );
    }

    public function checkout($bookingId)
    {
        $booking = Booking::with('field')->where('user_id', auth()->id())->findOrFail($bookingId);

        if ($booking->payment_status === 'paid') {
            return redirect()->route('bookings.history')->with('info', 'Booking này đã được thanh toán.');
        }

        $invoice = 'INV-' . $bookingId . '-' . time();
        $booking->update(['payment_invoice' => $invoice]);

        $checkoutData = CheckoutBuilder::make()
            ->currency('VND')
            ->orderInvoiceNumber($invoice)
            ->orderAmount((int) $booking->total_price)
            ->operation('PURCHASE')
            ->orderDescription('Đặt sân ' . $booking->field->name . ' - ' . $booking->date->format('d/m/Y') . ' Ca ' . $booking->shift)
            ->successUrl(route('payment.success', $bookingId))
            ->errorUrl(route('payment.error', $bookingId))
            ->cancelUrl(route('payment.cancel', $bookingId))
            ->build();

        $formHtml = $this->client()->checkout()->generateFormHtml($checkoutData);

        return view('payment.checkout', compact('booking', 'formHtml'));
    }

    public function success($bookingId, Request $request)
    {
        $booking = Booking::where('user_id', auth()->id())->findOrFail($bookingId);
        $booking->update([
            'status' => 'approved',
            'payment_status' => 'paid',
            'user_notified' => false,
        ]);

        return redirect()->route('bookings.history')->with('success', 'Thanh toán thành công! Booking đã được xác nhận.');
    }

    public function error($bookingId)
    {
        $booking = Booking::where('user_id', auth()->id())->findOrFail($bookingId);
        $booking->update(['payment_status' => 'failed']);

        return redirect()->route('bookings.history')->with('error', 'Thanh toán thất bại. Vui lòng thử lại.');
    }

    public function cancel($bookingId)
    {
        return redirect()->route('bookings.history')->with('info', 'Bạn đã hủy thanh toán.');
    }

    public function ipn(Request $request)
    {
        \Log::info('SePay IPN received', $request->all());

        // Verify signature
        $secretKey = config('services.sepay.secret_key');
        $data = $request->except('signature');
        ksort($data);
        $signString = implode('|', array_values($data)) . '|' . $secretKey;
        $expectedSig = hash('sha256', $signString);

        if ($request->signature !== $expectedSig) {
            \Log::warning('SePay IPN invalid signature');
            return response()->json(['status' => 'error', 'message' => 'Invalid signature'], 400);
        }

        $invoice = $request->order_invoice_number ?? $request->orderInvoiceNumber ?? null;
        if (!$invoice) {
            return response()->json(['status' => 'error', 'message' => 'No invoice'], 400);
        }

        // Owner subscription invoice: OWN-{userId}-{timestamp}
        if (str_starts_with($invoice, 'OWN-')) {
            $parts = explode('-', $invoice);
            $userId = $parts[1] ?? null;
            $user = $userId ? \App\Models\User::find($userId) : null;

            if (!$user) {
                return response()->json(['status' => 'error', 'message' => 'User not found'], 404);
            }

            $plan = $user->subscription_plan;
            $months = ['1m' => 1, '3m' => 3, '6m' => 6, '12m' => 12];
            $expiresAt = now()->addMonths($months[$plan] ?? 1);

            $user->update([
                'owner_request' => 'pending',
                'subscription_expires_at' => $expiresAt,
                'subscription_invoice' => $invoice,
            ]);

            return response()->json(['status' => 'success']);
        }

        // Booking invoice: INV-{bookingId}-{timestamp}
        $booking = \App\Models\Booking::where('payment_invoice', $invoice)->first();
        if (!$booking) {
            return response()->json(['status' => 'error', 'message' => 'Booking not found'], 404);
        }

        $booking->update([
            'status' => 'approved',
            'payment_status' => 'paid',
            'user_notified' => false,
        ]);

        return response()->json(['status' => 'success']);
    }
}
