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
    /**
     * DAFTAR ROLE YANG DIIZINKAN
     */
    private const ROLES = [
        'admin',
        'staf_spi',
        'auditee',
    ];

    // ==========================================
    // MENAMPILKAN DAFTAR PENGGUNA
    // ==========================================
    public function index(Request $request): View
    {
        $query = User::query();

        // PENCARIAN
        if ($request->filled('search')) {
            $search = trim($request->search);

            $query->where(function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                    ->orWhere('nip', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // FILTER ROLE
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // FILTER STATUS
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $pengguna = $query
            ->latest('id')
            ->paginate(10)
            ->withQueryString();

        return view('pengguna.index', compact('pengguna'));
    }

    // ==========================================
    // FORM TAMBAH PENGGUNA
    // ==========================================
    public function create(): View
    {
        return view('pengguna.create');
    }

    // ==========================================
    // MENYIMPAN PENGGUNA BARU
    // ==========================================
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

    // ==========================================
    // FORM EDIT PENGGUNA
    // ==========================================
    public function edit(User $pengguna): View
    {
        return view('pengguna.edit', compact('pengguna'));
    }

    // ==========================================
    // MEMPERBARUI DATA PENGGUNA
    // ==========================================
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

        // PASSWORD HANYA DIUBAH JIKA DIISI
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

    // ==========================================
    // MENGHAPUS PENGGUNA
    // ==========================================
    public function destroy(User $pengguna): RedirectResponse
    {
        // MENCEGAH PENGHAPUSAN AKUN SENDIRI
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
