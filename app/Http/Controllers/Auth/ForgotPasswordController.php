<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class ForgotPasswordController extends Controller
{
    // Bước 1: Nhập email và gửi OTP
    public function showStep1()
    {
        return view('auth.forgot-password.step1');
    }

    public function postStep1(Request $request)
    {
        $request->validate(['email' => 'required|email'], [
            'email.required' => 'Vui lòng nhập email.',
            'email.email'    => 'Email không hợp lệ.',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'Không tìm thấy tài khoản với email này.'])->withInput();
        }

        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        DB::table('password_reset_otps')->where('email', $user->email)->delete();
        DB::table('password_reset_otps')->insert([
            'email'      => $user->email,
            'otp'        => $otp,
            'expires_at' => now()->addMinutes(10),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $sent = $this->sendOtpEmail($user->email, $user->name, $otp);

        if (!$sent) {
            $errorDetail = session('mail_error', 'Không rõ nguyên nhân');
            session()->forget('mail_error');
            return back()->withErrors(['email' => 'Lỗi gửi mail: ' . $errorDetail])->withInput();
        }

        session(['reset_email' => $user->email, 'reset_user_id' => $user->id]);

        return redirect()->route('password.step2')->with('success', 'Mã OTP đã được gửi đến email của bạn.');
    }

    // Bước 2: Nhập mã OTP
    public function showStep2()
    {
        if (!session('reset_email')) return redirect()->route('password.step1');
        return view('auth.forgot-password.step2');
    }

    public function postStep2(Request $request)
    {
        if (!session('reset_email')) return redirect()->route('password.step1');

        $request->validate(['otp' => 'required|digits:6'], [
            'otp.required' => 'Vui lòng nhập mã OTP.',
            'otp.digits'   => 'Mã OTP phải gồm 6 chữ số.',
        ]);

        $record = DB::table('password_reset_otps')
            ->where('email', session('reset_email'))
            ->where('otp', $request->otp)
            ->first();

        if (!$record) {
            return back()->withErrors(['otp' => 'Mã OTP không đúng.']);
        }

        if (now()->isAfter($record->expires_at)) {
            DB::table('password_reset_otps')->where('email', session('reset_email'))->delete();
            return back()->withErrors(['otp' => 'Mã OTP đã hết hạn. Vui lòng yêu cầu mã mới.']);
        }

        session(['reset_verified' => true]);

        return redirect()->route('password.step3');
    }

    // Bước 3: Đặt lại mật khẩu
    public function showStep3()
    {
        if (!session('reset_verified') || !session('reset_email')) return redirect()->route('password.step1');
        return view('auth.forgot-password.step3');
    }

    public function postStep3(Request $request)
    {
        if (!session('reset_verified') || !session('reset_email')) return redirect()->route('password.step1');

        $request->validate([
            'password'              => 'required|min:8|confirmed',
            'password_confirmation' => 'required',
        ], [
            'password.required'  => 'Vui lòng nhập mật khẩu mới.',
            'password.min'       => 'Mật khẩu phải có ít nhất 8 ký tự.',
            'password.confirmed' => 'Xác nhận mật khẩu không khớp.',
        ]);

        $user = User::where('email', session('reset_email'))->first();

        if (!$user) return redirect()->route('password.step1');

        $user->password = Hash::make($request->password);
        $user->save();

        DB::table('password_reset_otps')->where('email', $user->email)->delete();
        session()->forget(['reset_user_id', 'reset_email', 'reset_verified']);

        return redirect()->route('login')->with('success', 'Đặt lại mật khẩu thành công! Vui lòng đăng nhập.');
    }

    // Gửi lại OTP
    public function resendOtp()
    {
        if (!session('reset_email')) return redirect()->route('password.step1');

        $user = User::where('email', session('reset_email'))->first();
        if (!$user) return redirect()->route('password.step1');

        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        DB::table('password_reset_otps')->where('email', $user->email)->delete();
        DB::table('password_reset_otps')->insert([
            'email'      => $user->email,
            'otp'        => $otp,
            'expires_at' => now()->addMinutes(10),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $sent = $this->sendOtpEmail($user->email, $user->name, $otp);

        if (!$sent) {
            return back()->withErrors(['otp' => 'Không thể gửi email. Vui lòng thử lại.']);
        }

        return back()->with('success', 'Đã gửi lại mã OTP mới.');
    }

    private function sendOtpEmail(string $toEmail, string $toName, string $otp): bool
    {
        try {
            $response = \Illuminate\Support\Facades\Http::withHeaders([
                'Authorization' => 'Bearer ' . config('services.resend.key'),
                'Content-Type'  => 'application/json',
            ])->post('https://api.resend.com/emails', [
                'from'    => config('services.resend.from', 'Đặt Sân Bóng <onboarding@resend.dev>'),
                'to'      => [$toEmail],
                'subject' => 'Mã xác nhận đặt lại mật khẩu - Đặt Sân Bóng',
                'html'    => $this->buildOtpHtml($toName, $otp),
            ]);

            if ($response->successful()) {
                return true;
            }

            \Log::error('Resend API lỗi: ' . $response->body());
            session(['mail_error' => $response->json('message', $response->body())]);
            return false;
        } catch (\Exception $e) {
            \Log::error('Mail gửi OTP thất bại: ' . $e->getMessage());
            session(['mail_error' => $e->getMessage()]);
            return false;
        }
    }

    private function buildOtpHtml(string $name, string $otp): string
    {
        return '<!DOCTYPE html>
        <html lang="vi"><head><meta charset="UTF-8"><style>
            body{font-family:Arial,sans-serif;background:#f4f4f4;margin:0;padding:20px}
            .container{max-width:500px;margin:0 auto;background:#fff;border-radius:10px;padding:30px;box-shadow:0 2px 10px rgba(0,0,0,.1)}
            .logo{text-align:center;margin-bottom:20px;color:#28a745;font-size:24px;font-weight:700}
            .otp-box{background:#f0fff4;border:2px dashed #28a745;border-radius:8px;text-align:center;padding:20px;margin:20px 0}
            .otp-code{font-size:40px;font-weight:700;color:#28a745;letter-spacing:8px}
            .note{color:#666;font-size:14px;text-align:center}
            .footer{margin-top:20px;text-align:center;color:#999;font-size:12px}
        </style></head>
        <body><div class="container">
            <div class="logo">⚽ Đặt Sân Bóng</div>
            <p>Xin chào <strong>' . htmlspecialchars($name) . '</strong>,</p>
            <p>Bạn vừa yêu cầu đặt lại mật khẩu. Đây là mã xác nhận của bạn:</p>
            <div class="otp-box"><div class="otp-code">' . $otp . '</div></div>
            <p class="note">⏰ Mã có hiệu lực trong <strong>10 phút</strong>. Không chia sẻ mã này với ai.</p>
            <p class="note">Nếu bạn không yêu cầu đặt lại mật khẩu, hãy bỏ qua email này.</p>
            <div class="footer">© ' . date('Y') . ' Đặt Sân Bóng. All rights reserved.</div>
        </div></body></html>';
    }
}
