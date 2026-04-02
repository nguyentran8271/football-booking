<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|regex:/^[0-9]{10}$/|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'email.unique' => 'Email này đã được sử dụng, vui lòng dùng email khác.',
            'phone.unique' => 'Số điện thoại này đã được đăng ký, vui lòng dùng số khác.',
            'phone.regex'  => 'Số điện thoại phải đúng 10 chữ số.',
            'password.min' => 'Mật khẩu phải có ít nhất 8 ký tự.',
            'password.confirmed' => 'Xác nhận mật khẩu không khớp.',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'password' => Hash::make($validated['password']),
            'role' => 'user', // Mặc định là user
        ]);

        Auth::login($user);

        return redirect('/')->with('success', 'Đăng ký thành công!');
    }
}
