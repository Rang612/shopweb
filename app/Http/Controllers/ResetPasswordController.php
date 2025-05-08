<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ResetPasswordController extends Controller
{
    /**
     * Hiển thị form reset password.
     */
    public function showResetForm(Request $request, $token = null)
    {
        return view('front.account.reset-password')->with(
            ['token' => $token, 'email' => $request->email]
        );
    }

    /**
     * Xử lý reset password.
     */
    public function reset(Request $request)
    {
        // Validate dữ liệu
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:8',
        ]);

        // Thực hiện reset
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();
            }
        );

        return $status == Password::PASSWORD_RESET
            ? redirect()->route('account.login')->with('status', __($status))
            : back()->withErrors(['email' => [__($status)]]);
    }
}
