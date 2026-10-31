<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Models\User;

class LoginController extends Controller
{

    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->intended('/')->with('success', 'Selamat datang kembali!');
        }

        return back()->withErrors([
            'username' => 'Username atau password yang Anda masukkan salah.',
        ])->onlyInput('username');
    }

    /**
     * Menangani logout
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

    public function sendOtp(Request $request)
    {
        $request->validate([
            'username' => 'required|string|exists:users,username'
        ], [
            'username.exists' => 'Username ini tidak ada.'
        ]);

        $adminEmail = 'adminbonanza1@gmail.com';
        $userYangInginDiReset = $request->username;

        // Generate kode OTP 6 digit
        $otpCode = rand(100000, 999999);

        session([
            'reset_username' => $userYangInginDiReset,
            'server_otp'     => $otpCode
        ]);

        try {
            Mail::raw("Halo Pemilik Toko Minimarket Bonanza,\n\nAda permintaan pemulihan kata sandi untuk akun user: {$userYangInginDiReset}.\nBerikut adalah Kode OTP verifikasinya: {$otpCode}\n\nBerikan kode ini kepada pihak yang bersangkutan.", function ($message) use ($adminEmail, $userYangInginDiReset) {
                $message->to($adminEmail)
                        ->subject('Kode OTP Reset Password - ' . $userYangInginDiReset);
            });
        } catch (\Exception $e) {
            return back()->withErrors(['username' => 'Gagal mengirim email OTP. Periksa koneksi internet atau konfigurasi email Anda.']);
        }

        return back()->with('otp_status', 'Kode OTP untuk user ' . $userYangInginDiReset . ' telah dikirimkan ke email Pemilik (adminbonanza1@gmail.com).');
    }

    public function resetWithOtp(Request $request)
    {
        $request->validate([
            'otp'      => 'required|string',
            'password' => 'required|string|min:6',
        ], [
            'otp.required'      => 'Kode OTP wajib diisi.',
            'password.required' => 'Password baru wajib diisi.',
            'password.min'      => 'Password baru minimal harus 6 karakter.',
        ]);

        $username = session('reset_username');
        $serverOtp = session('server_otp');

        if (!$username || !$serverOtp) {
            return back()->withErrors(['otp' => 'Sesi reset kadaluarsa. Silakan ulangi langkah permintaan OTP dari awal.']);
        }

        if ($request->otp != $serverOtp) {
            return back()->withErrors(['otp' => 'Kode OTP yang Anda masukkan salah!']);
        }

        $user = User::where('username', $username)->first();

        if ($user) {
            $user->password = bcrypt($request->password);
            $user->save();

            $successMessage = 'Password untuk akun "' . $username . '" berhasil diubah! Silakan login menggunakan password baru.';

            session()->forget(['reset_username', 'server_otp']);
            return back()->with('reset_success', $successMessage);
        }

        return back()->withErrors(['otp' => 'Akun pengguna tidak ditemukan di database.']);
    }
}