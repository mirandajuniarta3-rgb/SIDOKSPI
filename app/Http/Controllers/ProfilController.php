<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProfilController extends Controller
{
    /**
     * TAMPILKAN PROFIL
     */
    public function index(): View
    {
        $user = auth()->user();

        return view('profil.index', compact('user'));
    }

    /**
     * UPDATE PROFIL
     */
    public function update(Request $request): RedirectResponse
    {
        $user = auth()->user();

        $validated = $request->validate([
            'nama_lengkap' => [
                'required',
                'string',
                'max:255',
            ],

            'nip' => [
                'required',
                'string',
                'max:50',
                Rule::unique('users', 'nip')->ignore($user->id),
            ],

            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
            ],

            'password' => [
                'nullable',
                'string',
                'min:8',
                'confirmed',
            ],
        ]);

        // Update data profil
        $user->nama_lengkap = $validated['nama_lengkap'];
        $user->nip = $validated['nip'];
        $user->email = $validated['email'];

        // Password hanya diubah jika diisi
        if (!empty($validated['password'])) {
            $user->password_hash = Hash::make(
                $validated['password']
            );
        }

        $user->save();

        return redirect()
            ->route('profil.index')
            ->with('success', 'Profil berhasil diperbarui.');
    }
}