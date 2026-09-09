@extends('layouts.pembimbing')

@section('page-title', 'Riwayat Logbook')
@section('page-subtitle', 'Logbook yang sudah kamu periksa')

@section('content')
<div class="card-pmb section-card">
    <div class="section-head">
        <div>
            <h3>Riwayat Pemeriksaan</h3>
            <p>{{ $logbooks->total() }} logbook sudah diperiksa.</p>
        </div>
        <div class="tab-links">
            <a href="{{ route('pembimbing.riwayat') }}" class="{{ !$tab ? 'active' : '' }}">Semua</a>
            <a href="{{ route('pembimbing.riwayat', ['tab' => 'Disetujui']) }}" class="{{ $tab === 'Disetujui' ? 'active' : '' }}">Disetujui</a>
            <a href="{{ route('pembimbing.riwayat', ['tab' => 'Ditolak']) }}" class="{{ $tab === 'Ditolak' ? 'active' : '' }}">Ditolak</a>
        </div>
    </div>

    @if ($logbooks->isEmpty())
        <div class="empty-state">
            <i class="bi bi-clock-history"></i>
            <div class="empty-title">Belum ada riwayat.</div>
            <p>Logbook yang sudah kamu setujui atau tolak akan muncul di sini.</p>
        </div>
    @else
        <div class="table-wrap">
            <table class="table-pmb">
                <thead>
                    <tr>
                        <th>Peserta</th>
                        <th>Tanggal</th>
                        <th>Kegiatan Singkat</th>
                        <th>Status</th>
                        <th>Tanggal Pemeriksaan</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($logbooks as $logbook)
                        <tr>
                            <td>
                                <div class="cell-main">{{ $logbook->user->name ?? $logbook->user->username ?? '-' }}</div>
                            </td>
                            <td>{{ $logbook->clock_in->format('d M Y') }}</td>
                            <td>
                                <span class="text-truncate" style="display: inline-block; max-width: 200px;" title="{{ $logbook->activities }}">
                                    {{ \Illuminate\Support\Str::limit($logbook->activities ?? '-', 50) }}
                                </span>
                            </td>
                            <td>
                                @switch($logbook->approval_status)
                                    @case('Disetujui')
                                        <span class="badge-pmb badge-disetujui">Disetujui</span>
                                        @break
                                    @case('Ditolak')
                                        <span class="badge-pmb badge-ditolak">Ditolak</span>
                                        @break
                                    @default
                                        <span class="badge-pmb badge-pending">{{ $logbook->approval_status }}</span>
                                @endswitch
                            </td>
                            <td>{{ $logbook->approved_at?->format('d M Y H:i') ?? '-' }}</td>
                            <td class="text-end">
                                <a href="{{ route('pembimbing.logbook.show', $logbook->id) }}" class="btn-pmb btn-outline-pmb btn-sm-pmb">
                                    Lihat Detail
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if ($logbooks->hasPages())
            <div class="pagination-pmb">
                @if ($logbooks->onFirstPage())
                    <span class="page-link-pmb disabled"><i class="bi bi-chevron-left"></i></span>
                @else
                    <a href="{{ $logbooks->previousPageUrl() }}" class="page-link-pmb"><i class="bi bi-chevron-left"></i></a>
                @endif

                @foreach ($logbooks->getUrlRange(1, $logbooks->lastPage()) as $page => $url)
                    <a href="{{ $url }}" class="page-link-pmb {{ $logbooks->currentPage() === $page ? 'active' : '' }}">{{ $page }}</a>
                @endforeach

                @if ($logbooks->hasMorePages())
                    <a href="{{ $logbooks->nextPageUrl() }}" class="page-link-pmb"><i class="bi bi-chevron-right"></i></a>
                @else
                    <span class="page-link-pmb disabled"><i class="bi bi-chevron-right"></i></span>
                @endif
            </div>
        @endif
    @endif
</div>
@endsection
