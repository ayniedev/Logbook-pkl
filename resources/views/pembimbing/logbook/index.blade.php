@extends('layouts.pembimbing')

@section('page-title', 'Logbook Peserta')
@section('page-subtitle', 'Semua logbook peserta di bawah bimbinganmu')

@section('content')
<div class="card-pmb section-card">
    <div class="section-head">
        <div>
            <h3>Daftar Logbook</h3>
            <p>{{ $logbooks->total() }} logbook ditemukan.</p>
        </div>
        <form method="GET" action="{{ route('pembimbing.logbook.index') }}" class="filter-bar">
            <select name="status" class="form-select-pmb" onchange="this.form.submit()">
                <option value="">Semua Status</option>
                <option value="Diajukan" {{ $status === 'Diajukan' ? 'selected' : '' }}>Menunggu</option>
                <option value="Disetujui" {{ $status === 'Disetujui' ? 'selected' : '' }}>Disetujui</option>
                <option value="Ditolak" {{ $status === 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
            </select>
            <select name="peserta_id" class="form-select-pmb" onchange="this.form.submit()">
                <option value="">Semua Peserta</option>
                @foreach ($pesertaList as $p)
                    <option value="{{ $p->id }}" {{ request('peserta_id') == $p->id ? 'selected' : '' }}>
                        {{ $p->name ?? $p->username }}
                    </option>
                @endforeach
            </select>
            <input type="date" name="tanggal" value="{{ request('tanggal') }}" class="form-input-pmb" onchange="this.form.submit()">
            @if (request('status') || request('peserta_id') || request('tanggal'))
                <a href="{{ route('pembimbing.logbook.index') }}" class="btn-pmb btn-outline-pmb btn-sm-pmb" title="Hapus filter">
                    <i class="bi bi-x-lg"></i>
                </a>
            @endif
        </form>
    </div>

    @if ($logbooks->isEmpty())
        <div class="empty-state">
            <i class="bi bi-journal-x"></i>
            <div class="empty-title">Tidak ada logbook.</div>
            <p>Belum ada logbook yang cocok dengan filter ini.</p>
        </div>
    @else
        <div class="table-wrap">
            <table class="table-pmb">
                <thead>
                    <tr>
                        <th>Peserta</th>
                        <th>Tanggal</th>
                        <th>Kegiatan</th>
                        <th>Jam</th>
                        <th>Status</th>
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
                                <span class="text-truncate" style="display: inline-block; max-width: 220px;" title="{{ $logbook->activities }}">
                                    {{ \Illuminate\Support\Str::limit($logbook->activities ?? '-', 60) }}
                                </span>
                            </td>
                            <td>
                                {{ $logbook->clock_in->format('H:i') }}
                                {{ $logbook->clock_out ? ' - ' . $logbook->clock_out->format('H:i') : '' }}
                            </td>
                            <td>
                                @switch($logbook->approval_status)
                                    @case('Diajukan')
                                        <span class="badge-pmb badge-diajukan">Menunggu Persetujuan</span>
                                        @break
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
