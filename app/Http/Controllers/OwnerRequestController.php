<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\UploadService;

class OwnerRequestController extends Controller
{
    public function plans()
    {
        if (!session('owner_request_data')) {
            return redirect()->route('for-owners')->with('error', 'Vui lòng điền form đăng ký trước.');
        }
        return view('owner-request.plans');
    }

    public function checkout(Request $request)
    {
        $plan = $request->validate(['plan' => 'required|in:1m,3m,6m,12m'])['plan'];

        $prices = ['1m' => 300000, '3m' => 600000, '6m' => 1000000, '12m' => 1500000];
        $labels = ['1m' => '1 tháng', '3m' => '3 tháng', '6m' => '6 tháng', '12m' => '12 tháng'];

        $invoice = 'OWN-' . auth()->id() . '-' . time();
        session(['owner_subscription_plan' => $plan, 'owner_subscription_invoice' => $invoice]);

        // Lưu plan vào user để IPN có thể đọc
        auth()->user()->update(['subscription_plan' => $plan]);

        $sepay = new \SePay\SePayClient(
            config('services.sepay.merchant_id'),
            config('services.sepay.secret_key'),
            config('services.sepay.env', 'production')
        );

        $checkoutData = \SePay\Builders\CheckoutBuilder::make()
            ->currency('VND')
            ->orderInvoiceNumber($invoice)
            ->orderAmount($prices[$plan])
            ->operation('PURCHASE')
            ->orderDescription('Đăng ký chủ sân - ' . $labels[$plan])
            ->successUrl(route('owner-request.payment-success'))
            ->errorUrl(route('owner-request.payment-error'))
            ->cancelUrl(route('for-owners'))
            ->build();

        $formHtml = $sepay->checkout()->generateFormHtml($checkoutData);
        return view('owner-request.checkout', compact('formHtml', 'plan', 'prices', 'labels'));
    }

    public function paymentSuccess()
    {
        $data = session('owner_request_data');
        $plan = session('owner_subscription_plan');
        $invoice = session('owner_subscription_invoice');

        if (!$data || !$plan) {
            return redirect()->route('for-owners')->with('error', 'Phiên đăng ký đã hết hạn.');
        }

        $months = ['1m' => 1, '3m' => 3, '6m' => 6, '12m' => 12];
        $expiresAt = now()->addMonths($months[$plan]);

        $data['owner_request'] = 'pending';
        $data['subscription_plan'] = $plan;
        $data['subscription_expires_at'] = $expiresAt;
        $data['subscription_invoice'] = $invoice;

        auth()->user()->update($data);

        session()->forget(['owner_request_data', 'owner_subscription_plan', 'owner_subscription_invoice']);

        return redirect()->route('home')->with('success', 'Thanh toán thành công! Đơn đăng ký đã được gửi, vui lòng chờ admin duyệt.');
    }

    public function paymentError()
    {
        return redirect()->route('for-owners')->with('error', 'Thanh toán thất bại. Vui lòng thử lại.');
    }

    public function store(Request $request)
    {
        $request->validate([
            'note'                   => 'nullable|string|max:1000',
            'tax_number'             => 'nullable|regex:/^\d{10,13}$/|unique:users,tax_number,' . auth()->id(),
            'id_card_image'          => 'nullable|image|mimes:jpeg,png,jpg|max:4096',
            'id_card_back_image'     => 'nullable|image|mimes:jpeg,png,jpg|max:4096',
            'id_card_selfie_image'   => 'nullable|image|mimes:jpeg,png,jpg|max:4096',
            'business_license_image' => 'nullable|image|mimes:jpeg,png,jpg|max:4096',
        ], [
            'tax_number.unique' => 'Mã số thuế này đã được sử dụng bởi tài khoản khác.',
        ]);

        $user = auth()->user();

        if ($user->role !== 'user') {
            return back()->with('error', 'Chỉ tài khoản user mới có thể đăng ký.');
        }

        if ($user->owner_request === 'pending') {
            return back()->with('error', 'Bạn đã gửi đơn đăng ký, vui lòng chờ duyệt.');
        }

        // Lưu thông tin đăng ký tạm vào session, chờ thanh toán
        $data = [
            'owner_request_note' => $request->note,
            'tax_number'         => $request->tax_number,
        ];

        foreach (['id_card_image', 'id_card_back_image', 'id_card_selfie_image', 'business_license_image'] as $field) {
            if ($request->hasFile($field)) {
                $data[$field] = UploadService::upload($request->file($field), 'id-cards');
            }
        }

        // Lưu tạm vào session
        session(['owner_request_data' => $data]);

        return redirect()->route('owner-request.plans');
    }
}
