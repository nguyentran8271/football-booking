<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OwnerRequestController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'note'                   => 'nullable|string|max:1000',
            'tax_number'             => 'nullable|regex:/^\d{10,13}$/|unique:users,tax_number',
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

        $data = [
            'owner_request'      => 'pending',
            'owner_request_note' => $request->note,
            'tax_number'         => $request->tax_number,
        ];

        foreach (['id_card_image', 'id_card_back_image', 'id_card_selfie_image', 'business_license_image'] as $field) {
            if ($request->hasFile($field)) {
                $data[$field] = $request->file($field)->store('id-cards', 'public');
            }
        }

        $user->update($data);

        return back()->with('success', 'Đã gửi đơn đăng ký! Chúng tôi sẽ xem xét sớm nhất.');
    }
}
