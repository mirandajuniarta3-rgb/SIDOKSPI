<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class UserController extends Controller
{
    private const ROLES = [
        'admin',
        'staf_spi',
        'auditee',
    ];

    public function index(Request $request): View
    {
        $query = User::query();

        $search = $request->input('search');
        $sort = $request->input('sort', 'id');
        $direction = $request->input('direction', 'desc');

        $allowedSorts = [
            'id',
            'nama_lengkap',
            'nip',
            'email',
            'role',
            'bidang',
            'status',
        ];

        if (!in_array($sort, $allowedSorts, true)) {
            $sort = 'id';
        }

        if (!in_array($direction, ['asc', 'desc'], true)) {
            $direction = 'desc';
        }

        if (is_string($search) && trim($search) !== '') {
            $search = trim($search);

            $query->where(function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                    ->orWhere('nip', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        foreach (['role', 'status'] as $filter) {
            if (
                $request->filled($filter) &&
                is_string($request->input($filter))
            ) {
                $query->where($filter, $request->input($filter));
            }
        }

        $query->orderBy($sort, $direction);

        if ($sort !== 'id') {
            $query->orderBy('id', 'asc');
        }

        $pengguna = $query
            ->paginate(10)
            ->withQueryString();

        return view('pengguna.index', compact(
            'pengguna',
            'sort',
            'direction'
        ));
    }

    public function create(): View
    {
        return view('pengguna.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama_lengkap' => [
                'required',
                'string',
                'max:100',
            ],

            'nip' => [
                'nullable',
                'string',
                'max:30',
                'unique:users,nip',
            ],

            'email' => [
                'required',
                'email',
                'max:100',
                'unique:users,email',
            ],

            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
            ],

            'role' => [
                'required',
                Rule::in(self::ROLES),
            ],

            'bidang' => [
                'nullable',
                'string',
                'max:100',
            ],

            'status' => [
                'required',
                Rule::in(['aktif', 'nonaktif']),
            ],
        ]);

        User::create([
            'nama_lengkap' => $validated['nama_lengkap'],
            'nip' => $validated['nip'] ?? null,
            'email' => $validated['email'],
            'password_hash' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'bidang' => $validated['bidang'] ?? null,
            'status' => $validated['status'],
        ]);

        return redirect()
            ->route('pengguna.index')
            ->with('success', 'Pengguna berhasil ditambahkan.');
    }

    public function edit(User $pengguna): View
    {
        return view('pengguna.edit', compact('pengguna'));
    }

    public function update(
        Request $request,
        User $pengguna
    ): RedirectResponse {

        $validated = $request->validate([
            'nama_lengkap' => [
                'required',
                'string',
                'max:100',
            ],

            'nip' => [
                'nullable',
                'string',
                'max:30',
                Rule::unique('users', 'nip')->ignore($pengguna->id),
            ],

            'email' => [
                'required',
                'email',
                'max:100',
                Rule::unique('users', 'email')->ignore($pengguna->id),
            ],

            'password' => [
                'nullable',
                'string',
                'min:8',
                'confirmed',
            ],

            'role' => [
                'required',
                Rule::in(self::ROLES),
            ],

            'bidang' => [
                'nullable',
                'string',
                'max:100',
            ],

            'status' => [
                'required',
                Rule::in(['aktif', 'nonaktif']),
            ],
        ]);

        $pengguna->nama_lengkap = $validated['nama_lengkap'];
        $pengguna->nip = $validated['nip'] ?? null;
        $pengguna->email = $validated['email'];
        $pengguna->role = $validated['role'];
        $pengguna->bidang = $validated['bidang'] ?? null;
        $pengguna->status = $validated['status'];

        if (!empty($validated['password'])) {
            $pengguna->password_hash = Hash::make(
                $validated['password']
            );
        }

        $pengguna->save();

        return redirect()
            ->route('pengguna.index')
            ->with('success', 'Data pengguna berhasil diperbarui.');
    }

    public function destroy(User $pengguna): RedirectResponse
    {
        if (auth()->id() === $pengguna->id) {
            return redirect()
                ->route('pengguna.index')
                ->with('error', 'Akun yang sedang digunakan tidak dapat dihapus.');
        }

        $pengguna->delete();

        return redirect()
            ->route('pengguna.index')
            ->with('success', 'Pengguna berhasil dihapus.');
    }
}
