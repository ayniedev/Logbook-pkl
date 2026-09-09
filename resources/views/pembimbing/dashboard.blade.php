@extends('layouts.pembimbing')

@section('page-title', 'Dashboard')
@section('page-subtitle', 'Monitoring & persetujuan logbook peserta PKL')

@section('content')
<div class="welcome-section">
    <h2>Selamat datang, {{ Auth::user()->name ?? 'Pembimbing' }} 👋</h2>
    <p>Pantau kegiatan peserta PKL dan periksa logbook mereka dengan lebih mudah.</p>
</div>

{{-- Summary cards --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="card-pmb stat-card">
            <div class="stat-icon purple">
                <i class="bi bi-people"></i>
            </div>
            <div class="stat-info">
                <div class="stat-label">Peserta Aktif</div>
                <div class="stat-value">{{ $stats['peserta_aktif'] }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card-pmb stat-card">
            <div class="stat-icon warning">
                <i class="bi bi-hourglass-split"></i>
            </div>
            <div class="stat-info">
                <div class="stat-label">Menunggu Persetujuan</div>
                <div class="stat-value">{{ $stats['menunggu'] }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card-pmb stat-card">
            <div class="stat-icon success">
                <i class="bi bi-check-circle"></i>
            </div>
            <div class="stat-info">
                <div class="stat-label">Disetujui</div>
                <div class="stat-value">{{ $stats['disetujui'] }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card-pmb stat-card">
            <div class="stat-icon danger">
                <i class="bi bi-x-circle"></i>
            </div>
            <div class="stat-info">
                <div class="stat-label">Ditolak</div>
                <div class="stat-value">{{ $stats['ditolak'] }}</div>
            </div>
        </div>
    </div>
</div>

{{-- Section: Perlu Dicek --}}
<div class="card-pmb section-card">
    <div class="section-head">
        <div>
            <h3>Perlu Dicek</h3>
            <p>Logbook peserta yang membutuhkan persetujuanmu.</p>
        </div>
        <a href="{{ route('pembimbing.logbook.index', ['status' => 'Diajukan']) }}" class="btn-pmb btn-outline-pmb btn-sm-pmb">
            Lihat Semua
            <i class="bi bi-arrow-right"></i>
        </a>
        </div>

    @if ($pendingLogbooks->isEmpty())
        <div class="empty-state">
            <i class="bi bi-emoji-smile"></i>
            <div class="empty-title">Belum ada logbook yang perlu diperiksa.</div>
            <p>Semua logbook sudah kamu periksa. 🎉</p>
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
                    @foreach ($pendingLogbooks as $logbook)
                        <tr>
                            <td>
                                <div class="cell-main">{{ $logbook->user->name ?? $logbook->user->username ?? '-' }}</div>
                                <div class="cell-sub">{{ $logbook->user->email ?? '' }}</div>
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
                                <span class="badge-pmb badge-diajukan">Menunggu Persetujuan</span>
                            </td>
                            <td class="text-end">
                                <a href="{{ route('pembimbing.logbook.show', $logbook->id) }}" class="btn-pmb btn-primary-pmb btn-sm-pmb">
                                    Lihat Detail
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
