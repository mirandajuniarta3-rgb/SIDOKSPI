<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLogin(): View|RedirectResponse
    {
        if (Auth::check()) {
            return $this->redirectByRole(Auth::user());
        }

        return view('auth.login');
    }

    /**
     * LOGIN EMAIL ATAU NIP
     */
    public function login(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'identifier' => ['required', 'string', 'max:100'],
            'password' => ['required', 'string'],
            'remember' => ['sometimes', 'boolean'],
        ]);

        $identifier = trim($validated['identifier']);

        $identityColumn = filter_var(
            $identifier,
            FILTER_VALIDATE_EMAIL
        ) ? 'email' : 'nip';

        $credentials = [
            $identityColumn => $identifier,
            'password' => $validated['password'],
            'status' => 'aktif',
        ];

        if (!Auth::attempt(
            $credentials,
            $request->boolean('remember')
        )) {
            return back()
                ->withErrors([
                    'identifier' => 'Email/NIP atau kata sandi tidak sesuai, atau akun tidak aktif.',
                ])
                ->onlyInput('identifier');
        }

        $request->session()->regenerate();

        return $this->redirectByRole(Auth::user())
            ->with('success', 'Selamat datang di SIDOKSPI.');
    }

    /**
     * REDIRECT BERDASARKAN ROLE
     */
    private function redirectByRole(User $user): RedirectResponse
    {
        $role = strtolower(trim($user->role ?? ''));

        if ($role === 'admin') {
            return redirect()->route('dashboard.admin');
        }

        if ($role === 'staf_spi') {
            return redirect()->route('dashboard.staf');
        }

        if ($role === 'auditee') {
            return redirect()->route('dashboard');
        }

        Auth::logout();

        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect()
            ->route('login')
            ->withErrors([
                'identifier' => 'Role akun tidak dikenali. Hubungi Admin SPI.',
            ]);
    }

    /**
     * LOGOUT
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('login')
            ->with('success', 'Anda berhasil logout.');
    }
}
