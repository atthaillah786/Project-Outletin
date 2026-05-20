<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $user = Auth::user();

            if ($user->role === 'superadmin') {
                return redirect()
                ->route('superadmin.dashboard')
                ->with('success', 'Berhasil masuk sebagai superadmin.');
            }

            if ($user->role === 'franchisor') {
                $hasApprovedBrand = Brand::where('franchisor_id', $user->user_id)
                    ->where('status', 'approved')
                    ->exists();

                if ($hasApprovedBrand) {
                    return redirect()
                        ->route('franchisor.dashboard')
                        ->with('success', 'Berhasil masuk sebagai pemilik brand.');
                }

                return redirect()
                    ->route('brand.registration.create')
                    ->with('success', 'Silakan daftarkan brand Anda terlebih dahulu.');
            }

            if ($user->role === 'superadmin') {
                return redirect()
                    ->route('superadmin.brand.verification')
                    ->with('success', 'Berhasil masuk sebagai superadmin.');
            }

            return redirect()
                ->route('home')
                ->with('success', 'Berhasil masuk ke akun.');
        }

        return back()
            ->withErrors([
                'email' => 'Email atau password salah.',
            ])
            ->onlyInput('email');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:100', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            'role' => ['required', Rule::in(['franchisor', 'franchise'])],
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'created_at' => now(),
        ]);

        return redirect()
            ->route('login')
            ->with('success', 'Akun berhasil dibuat. Silakan login.');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('login')
            ->with('success', 'Berhasil keluar dari akun.');
    }
}