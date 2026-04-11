<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Services\UploadService;

class ProfileController extends Controller
{
    public function edit()
    {
        return view('profile.edit', ['user' => auth()->user()]);
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name'                  => 'required|string|max:255',
            'phone'                 => 'required|digits:10',
            'password'              => 'nullable|min:8|confirmed',
            'password_confirmation' => 'nullable',
            'id_card_image'         => 'nullable|image|max:2048',
            'id_card_back_image'    => 'nullable|image|max:2048',
            'id_card_selfie_image'  => 'nullable|image|max:2048',
            'business_license_image'=> 'nullable|image|max:2048',
            'tax_number'            => 'nullable|digits_between:10,13|unique:users,tax_number,' . auth()->id(),
        ], [
            'tax_number.unique' => 'Mã số thuế này đã được sử dụng bởi tài khoản khác.',
        ]);

        $user->name  = $validated['name'];
        $user->phone = $validated['phone'];

        if (!empty($validated['password'])) {
            $user->password = \Illuminate\Support\Facades\Hash::make($validated['password']);
        }

        foreach (['id_card_image', 'id_card_back_image', 'id_card_selfie_image', 'business_license_image'] as $field) {
            if ($request->hasFile($field)) {
                UploadService::delete($user->$field);
                $user->$field = UploadService::upload($request->file($field), 'owner-docs');
            }
        }

        if (isset($validated['tax_number'])) {
            $user->tax_number = $validated['tax_number'];
        }

        $user->save();

        return back()->with('success', 'Cập nhật thông tin thành công.');
    }
}
