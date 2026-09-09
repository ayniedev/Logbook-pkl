@extends('layouts.pembimbing')

@section('page-title', 'Peserta PKL')
@section('page-subtitle', 'Daftar peserta di bawah bimbinganmu')

@section('content')
<div class="card-pmb section-card">
    <div class="section-head">
        <div>
            <h3>Peserta Bimbingan</h3>
            <p>{{ $peserta->total() }} peserta terdaftar di bawah bimbinganmu.</p>
        </div>
    </div>

    @if ($peserta->isEmpty())
        <div class="empty-state">
            <i class="bi bi-people"></i>
            <div class="empty-title">Belum ada peserta PKL.</div>
            <p>Belum ada peserta yang di-assign kepadamu. Hubungi Admin untuk penugasan peserta.</p>
        </div>
    @else
        <div class="table-wrap">
            <table class="table-pmb">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Project</th>
                        <th>Status</th>
                        <th>Jumlah Logbook</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($peserta as $p)
                        <tr>
                            <td>
                                <div class="cell-main">{{ $p->name ?? $p->username ?? '-' }}</div>
                                <div class="cell-sub">{{ $p->username }}</div>
                            </td>
                            <td>{{ $p->email }}</td>
                            <td>-</td>
                            <td>
                                @if ($p->is_active)
                                    <span class="badge-pmb badge-aktif">Aktif</span>
                                @else
                                    <span class="badge-pmb badge-nonaktif">Nonaktif</span>
                                @endif
                            </td>
                            <td>{{ $p->logbooks_count }}</td>
                            <td class="text-end">
                                <a href="{{ route('pembimbing.logbook.index', ['peserta_id' => $p->id]) }}" class="btn-pmb btn-outline-pmb btn-sm-pmb">
                                    <i class="bi bi-journal-text"></i>
                                    Lihat Logbook
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if ($peserta->hasPages())
            <div class="pagination-pmb">
                @if ($peserta->onFirstPage())
                    <span class="page-link-pmb disabled"><i class="bi bi-chevron-left"></i></span>
                @else
                    <a href="{{ $peserta->previousPageUrl() }}" class="page-link-pmb"><i class="bi bi-chevron-left"></i></a>
                @endif

                @foreach ($peserta->getUrlRange(1, $peserta->lastPage()) as $page => $url)
                    <a href="{{ $url }}" class="page-link-pmb {{ $peserta->currentPage() === $page ? 'active' : '' }}">{{ $page }}</a>
                @endforeach

                @if ($peserta->hasMorePages())
                    <a href="{{ $peserta->nextPageUrl() }}" class="page-link-pmb"><i class="bi bi-chevron-right"></i></a>
                @else
                    <span class="page-link-pmb disabled"><i class="bi bi-chevron-right"></i></span>
                @endif
            </div>
        @endif
    @endif
</div>
@endsection
