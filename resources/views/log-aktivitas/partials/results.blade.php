@php
    $columns = [
        'created_at' => 'Waktu (Tanggal)',
        'pengguna' => 'Pengguna',
        'aksi' => 'Aksi',
        'referensi' => 'Objek & Referensi',
        'ip_address' => 'Alamat IP',
    ];
@endphp

<div class="log-table-head">

    <div>

        <h3>Daftar Jejak Audit Sistem</h3>

        <p>
            Riwayat aktivitas yang tercatat dalam sistem SIDOKSPI.
        </p>

    </div>

    <div class="log-table-count">

        Total {{ number_format($aktivitas->total(), 0, ',', '.') }} aktivitas

    </div>

</div>

<div class="log-table-wrap">

    <table class="log-table">

        <thead>

            <tr>

                <th class="log-col-number">No.</th>

                @foreach ($columns as $column => $label)

                    @php
                        $isActive = $sort === $column;

                        $nextDirection = $isActive && $direction === 'asc'
                            ? 'desc'
                            : 'asc';

                        $sortUrl = route(
                            'log-aktivitas.index',
                            array_merge(
                                request()->except(['page', 'sort', 'direction']),
                                [
                                    'sort' => $column,
                                    'direction' => $nextDirection,
                                ]
                            )
                        );
                    @endphp

                    <th aria-sort="{{ $isActive
                        ? ($direction === 'asc' ? 'ascending' : 'descending')
                        : 'none' }}">

                        <a href="{{ $sortUrl }}"
                           class="log-sort-link {{ $isActive ? 'is-active' : '' }}"
                           data-sort="{{ $column }}"
                           data-direction="{{ $nextDirection }}">

                            <span>{{ $label }}</span>

                            @if ($isActive)

                                @if ($direction === 'asc')

                                    <svg viewBox="0 0 24 24"
                                         fill="none"
                                         stroke="currentColor"
                                         stroke-width="2"
                                         stroke-linecap="round"
                                         stroke-linejoin="round"
                                         aria-hidden="true">

                                        <path d="M12 19V5M6 11l6-6 6 6"/>

                                    </svg>

                                @else

                                    <svg viewBox="0 0 24 24"
                                         fill="none"
                                         stroke="currentColor"
                                         stroke-width="2"
                                         stroke-linecap="round"
                                         stroke-linejoin="round"
                                         aria-hidden="true">

                                        <path d="M12 5v14M6 13l6 6 6-6"/>

                                    </svg>

                                @endif

                            @else

                                <svg viewBox="0 0 24 24"
                                     fill="none"
                                     stroke="currentColor"
                                     stroke-width="1.7"
                                     stroke-linecap="round"
                                     stroke-linejoin="round"
                                     aria-hidden="true">

                                    <path d="M8 17V4M4 8l4-4 4 4"/>
                                    <path d="M16 7v13M12 16l4 4 4-4"/>

                                </svg>

                            @endif

                        </a>

                    </th>

                @endforeach

            </tr>

        </thead>

        <tbody>

            @forelse ($aktivitas as $log)

                @php
                    $action = strtolower(trim($log->aksi));

                    $actionClass = match (true) {
                        in_array($action, [
                            'login',
                            'create',
                            'store',
                            'upload',
                            'tambah',
                            'unggah',
                            'restore',
                        ]) => 'action-success',

                        in_array($action, [
                            'update',
                            'edit',
                            'ubah',
                            'arsip',
                            'archive',
                        ]) => 'action-warning',

                        in_array($action, [
                            'delete',
                            'destroy',
                            'hapus',
                            'failed_login',
                            'login_gagal',
                        ]) => 'action-danger',

                        in_array($action, [
                            'logout',
                            'keluar',
                        ]) => 'action-neutral',

                        default => 'action-primary',
                    };
                @endphp

                <tr>

                    <td class="log-number">

                        {{ $aktivitas->firstItem() + $loop->index }}

                    </td>

                    <td>

                        <div class="log-date-main">

                            {{ $log->created_at
                                ? $log->created_at->format('d M Y')
                                : '-' }}

                        </div>

                        <div class="log-date-time">

                            {{ $log->created_at
                                ? $log->created_at->format('H:i:s')
                                : '-' }}

                        </div>

                    </td>

                    <td>

                        <div class="log-user">

                            <div class="log-avatar">

                                {{ mb_substr($log->user->nama_lengkap ?? '?', 0, 1) }}

                            </div>

                            <div class="log-user-info">

                                <div class="log-user-name">

                                    {{ $log->user->nama_lengkap
                                        ?? 'Pengguna tidak ditemukan' }}

                                </div>

                                <div class="log-user-email">

                                    {{ $log->user->email ?? '-' }}

                                </div>

                            </div>

                        </div>

                    </td>

                    <td>

                        <span class="log-action {{ $actionClass }}">

                            <span class="log-action-dot"></span>

                            {{ ucfirst(str_replace('_', ' ', $log->aksi)) }}

                        </span>

                    </td>

                    <td>

                        @if ($log->dokumen_id)

                            <span class="log-reference">

                                Dokumen #{{ $log->dokumen_id }}

                            </span>

                        @elseif ($log->sk_id)

                            <span class="log-reference">

                                Surat Keputusan #{{ $log->sk_id }}

                            </span>

                        @else

                            <span class="log-muted">-</span>

                        @endif

                    </td>

                    <td>

                        <span class="log-ip">

                            {{ $log->ip_address ?? '-' }}

                        </span>

                    </td>

                </tr>

            @empty

                <tr>

                    <td colspan="6" class="log-empty">

                        <div class="log-empty-content">

                            <div class="log-empty-icon">

                                <svg viewBox="0 0 24 24"
                                     fill="none"
                                     stroke="currentColor"
                                     stroke-width="1.5"
                                     stroke-linecap="round"
                                     stroke-linejoin="round"
                                     aria-hidden="true">

                                    <circle cx="11" cy="11" r="7"/>
                                    <path d="m16 16 4 4M8 11h6"/>

                                </svg>

                            </div>

                            <strong>Tidak ada aktivitas ditemukan</strong>

                            <span>
                                Belum ada aktivitas yang sesuai dengan pencarian.
                            </span>

                        </div>

                    </td>

                </tr>

            @endforelse

        </tbody>

    </table>

</div>

<div class="log-pagination">

    <div class="log-pagination-info">

        Menampilkan {{ $aktivitas->firstItem() ?? 0 }}
        sampai {{ $aktivitas->lastItem() ?? 0 }}
        dari {{ $aktivitas->total() }} aktivitas

    </div>

    <div class="log-pagination-links">

        {{ $aktivitas->links() }}

    </div>

</div>
