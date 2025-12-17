<?php
// app/Http/Controllers/AuthController.php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Mail\WelcomeEmail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function registerLihat()
    {
        return view('register');
    }

    public function registerSubmit(Request $request)
{
    $validator = Validator::make($request->all(), [
        'username' => 'required|min:3|max:50|unique:users',
        'email' => 'required|email|unique:users',
        'role' => 'required|in:mahasiswa,dosen',
        'password' => 'required|min:6|confirmed',
    ]);

    if ($validator->fails()) {
        return redirect()->back()
            ->withErrors($validator)
            ->withInput();
    }


    $verificationToken = Str::random(60);

    $user = User::create([
        'username' => $request->username,
        'email' => $request->email,
        'password' => bcrypt($request->password),
        'role' => $request->role,
        'token' => $verificationToken,
        'status' => 'pending',
    ]);

        try {
        Mail::to($user->email)->send(new WelcomeEmail($verificationToken, $user->email));

        return redirect()->route('login.lihat')
            ->with('success', 'Registrasi berhasil! Silakan cek email Anda untuk verifikasi akun.');

    } catch (\Exception $e) {
        return redirect()->route('login.lihat')
            ->with('warning', 'Registrasi berhasil, tetapi email verifikasi gagal dikirim. Silakan hubungi admin.');
    }
}

    public function loginLihat()
    {
        return view('login');
    }

    public function loginSubmit(Request $request)
{
    $user = User::where('email', $request->email)->first();

    if ($user && Hash::check($request->password, $user->password)) {
        // Cek apakah akun sudah diverifikasi
        if ($user->status !== 'aktif') {
            return redirect()->route('login.lihat')
                ->with('failed', 'Akun belum diverifikasi. Silakan cek email Anda untuk link verifikasi.')
                ->withInput();
        }

        // Login user
        Auth::login($user);
        $request->session()->regenerate();

        // Redirect ke DASHBOARD
        return redirect()->route('dashboard')->with('success', 'Login berhasil! Selamat datang ' . $user->username);
    }

    return redirect()->route('login.lihat')
        ->with('failed', 'Email atau password salah.')
        ->withInput();
}

    public function verify(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'token' => 'required|string',
        ]);

        $user = User::where('email', $request->email)
                    ->where('token', $request->token)
                    ->first();

        if (!$user) {
            return redirect()->route('login.lihat')
                ->with('failed', 'Link verifikasi tidak valid atau sudah kedaluwarsa.');
        }


        $user->update([
            'status' => 'aktif',
            'token' => null
        ]);

        return redirect()->route('login.lihat')
            ->with('success', 'Akun Anda telah berhasil diverifikasi! Silakan login.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login.lihat')
            ->with('success', 'Logout berhasil.');
    }
}
