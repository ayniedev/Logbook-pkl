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

{{-- Section: Progres PKL Peserta --}}
@if($internProgress->isNotEmpty())
<div class="card-pmb section-card mt-4">
    <div class="section-head">
        <div>
            <h3><i class="bi bi-graph-up"></i> Progres PKL Peserta</h3>
            <p>Lihat masa PKL setiap peserta yang kamu bimbing.</p>
        </div>
    </div>
    <div class="intern-progress-grid">
        @foreach($internProgress as $intern)
        <div class="intern-progress-card">
            <div class="intern-progress-header">
                <div class="intern-avatar">
                    {{ strtoupper(substr($intern['intern_name'], 0, 1)) }}
                </div>
                <div class="intern-info">
                    <div class="intern-name">{{ $intern['intern_name'] }}</div>
                    <div class="intern-division">{{ $intern['division_name'] }}</div>
                </div>
                @if($intern['status'] === 'belum_dimulai')
                    <span class="badge-pmb badge-secondary">Belum Mulai</span>
                @elseif($intern['status'] === 'berjalan')
                    <span class="badge-pmb badge-info">Berlangsung</span>
                @elseif($intern['status'] === 'hampir_selesai')
                    <span class="badge-pmb badge-warning">Hampir Selesai</span>
                @elseif($intern['status'] === 'selesai')
                    <span class="badge-pmb badge-success">Selesai</span>
                @endif
            </div>
            @if($intern['start_date'] && $intern['total_days'] > 0)
            <div class="intern-progress-body">
                <div class="progress-detail-row">
                    <span class="progress-label">Hari ke-{{ $intern['current_day'] }} dari {{ $intern['total_days'] }} hari</span>
                    <span class="progress-pct">{{ $intern['percentage'] }}%</span>
                </div>
                <div class="progress-bar-wrap">
                    <div class="progress-bar-fill" style="width: {{ $intern['percentage'] }}%"></div>
                </div>
                <div class="progress-date-row">
                    <span><i class="bi bi-calendar-event"></i> {{ $intern['start_date']->format('d M Y') }}</span>
                    <span><i class="bi bi-calendar-check"></i> {{ $intern['end_date']->format('d M Y') }}</span>
                </div>
            </div>
            @else
            <div class="intern-progress-body">
                <div class="progress-empty">Belum ada data tanggal mulai & durasi PKL.</div>
            </div>
            @endif
            @if($intern['is_auto_filled'])
            <div style="margin-top: 10px; padding: 6px 10px; background: rgba(230, 162, 60, 0.08); border: 1px solid rgba(230, 162, 60, 0.20); border-radius: 8px; font-size: 0.72rem; color: var(--text-secondary); display: flex; align-items: center; gap: 5px;">
                <i class="bi bi-info-circle" style="color: var(--warning);"></i>
                Data diambil otomatis dari absensi pertama.
            </div>
            @endif
        </div>
        @endforeach
    </div>
</div>
@endif
@endsection
