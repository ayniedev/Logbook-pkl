@extends('layouts.internship')

@section('page-title', 'Dashboard')
@section('page-subtitle', now()->format('l, d F Y'))

@section('content')
@if(session('success'))
    <div class="alert alert-success">
        <i class="bi bi-check-circle"></i>
        <span>{{ session('success') }}</span>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-error">
        <i class="bi bi-exclamation-circle"></i>
        <span>{{ session('error') }}</span>
    </div>
@endif

{{-- Greeting --}}
<div class="mb-4">
    <h3 style="font-size: 1.15rem; font-weight: 700; color: var(--text); margin-bottom: 3px;">
        Selamat datang, {{ Auth::user()->name }}
    </h3>
    <p style="font-size: 0.88rem; color: var(--text-secondary);">Semoga kegiatan PKL hari ini berjalan lancar.</p>
</div>

{{-- PKL Progress Card --}}
@if($assignment && $startDate)
<div class="card mb-4">
    <div class="card-header">
        <h3>
            <i class="bi bi-graph-up"></i>
            Progres PKL
        </h3>
        @if($status === 'belum_dimulai')
            <span class="badge badge-secondary">Belum Dimulai</span>
        @elseif($status === 'berjalan')
            <span class="badge badge-info">Berlangsung</span>
        @elseif($status === 'hampir_selesai')
            <span class="badge badge-warning">Hampir Selesai</span>
        @elseif($status === 'selesai')
            <span class="badge badge-success">Selesai</span>
        @endif
    </div>
    <div class="card-body">
        <div class="progress-info">
            <div class="progress-detail">
                <span class="progress-label">Hari ke-{{ $currentDay }} dari {{ $totalDays }} hari</span>
                <span class="progress-percentage">{{ $percentage }}%</span>
            </div>
            <div class="progress-bar-wrapper">
                <div class="progress-bar" style="width: {{ $percentage }}%"></div>
            </div>
            <div class="progress-dates">
                <span><i class="bi bi-calendar-event"></i> {{ $startDate->format('d M Y') }}</span>
                <span><i class="bi bi-calendar-check"></i> {{ $endDate->format('d M Y') }}</span>
            </div>
        </div>
        <div class="progress-meta">
            <div class="meta-item">
                <i class="bi bi-building"></i>
                <span>{{ $assignment->division->name ?? '-' }}</span>
            </div>
            <div class="meta-item">
                <i class="bi bi-person-badge"></i>
                <span>{{ $assignment->mentor->name ?? '-' }}</span>
            </div>
        </div>
        @if($isAutoFilled)
        <div style="margin-top: 12px; padding: 8px 12px; background: rgba(230, 162, 60, 0.08); border: 1px solid rgba(230, 162, 60, 0.20); border-radius: 8px; font-size: 0.78rem; color: var(--text-secondary); display: flex; align-items: center; gap: 6px;">
            <i class="bi bi-info-circle" style="color: var(--warning);"></i>
            Tanggal mulai & durasi diambil otomatis dari absensi pertama. Hubungi admin untuk pengaturan lebih lanjut.
        </div>
        @endif
    </div>
</div>
@elseif(!$assignment)
<div class="card mb-4">
    <div class="card-body" style="text-align: center; padding: 24px;">
        <i class="bi bi-info-circle" style="font-size: 1.5rem; color: var(--text-soft);"></i>
        <p style="margin-top: 8px; color: var(--text-secondary); font-size: 0.9rem;">Anda belum memiliki penempatan PKL aktif.</p>
    </div>
</div>
@endif

{{-- Stat Cards --}}
<div class="row mb-4">
    <div class="col-lg-4 col-md-6 mb-3">
        <div class="stat-card">
            <div class="icon {{ $todayLogbook ? ($todayLogbook->clock_out ? 'success' : ($todayLogbook->overtime_started_at ? 'warning' : 'mauve')) : 'purple' }}">
                <i class="bi bi-{{ $todayLogbook ? ($todayLogbook->clock_out ? 'check-circle' : ($todayLogbook->overtime_started_at ? 'lightning' : 'clock')) : 'circle' }}"></i>
            </div>
            <div class="stat-info">
                <div class="stat-label">Status Absensi</div>
                <div class="stat-value">
                    @if(!$todayLogbook)
                        Belum Absen
                    @elseif($todayLogbook->clock_out)
                        Selesai
                    @elseif($todayLogbook->overtime_started_at && !$todayLogbook->overtime_ended_at)
                        Sedang Lembur
                    @elseif(!$todayLogbook->clock_out)
                        Sudah Masuk
                    @else
                        Selesai
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-6 mb-3">
        <div class="stat-card">
            <div class="icon mauve">
                <i class="bi bi-box-arrow-in-right"></i>
            </div>
            <div class="stat-info">
                <div class="stat-label">Jam Masuk</div>
                <div class="stat-value">{{ $todayLogbook ? $todayLogbook->clock_in->format('H:i') : '-' }}</div>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-6 mb-3">
        <div class="stat-card">
            <div class="icon {{ $todayLogbook && $todayLogbook->clock_out ? 'success' : 'purple' }}">
                <i class="bi bi-box-arrow-right"></i>
            </div>
            <div class="stat-info">
                <div class="stat-label">Jam Keluar</div>
                <div class="stat-value">{{ $todayLogbook && $todayLogbook->clock_out ? $todayLogbook->clock_out->format('H:i') : '-' }}</div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    {{-- Attendance Card --}}
    <div class="col-lg-8 mb-4">
        <div class="card">
            <div class="card-header">
                <h3>
                    <i class="bi bi-calendar-check"></i>
                    Absensi Hari Ini
                </h3>
                @if($todayLogbook && $todayLogbook->clock_out)
                    <span class="badge badge-success">Selesai</span>
                @endif
            </div>
            <div class="card-body attendance-content">
                @if(!$todayLogbook)
                    {{-- Belum absen --}}
                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="timeline-dot"></div>
                            <div class="timeline-info">
                                <div class="label">Absen Masuk</div>
                                <div class="time empty">--:--</div>
                            </div>
                        </div>
                        <div class="timeline-line"></div>
                        <div class="timeline-item">
                            <div class="timeline-dot"></div>
                            <div class="timeline-info">
                                <div class="label">Absen Keluar</div>
                                <div class="time empty">--:--</div>
                            </div>
                        </div>
                    </div>
                    <p style="text-align: center;">Tekan tombol untuk memulai kegiatan hari ini</p>
                    <div style="text-align: center;">
                        <form action="{{ route('internship.logbook.clock-in') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-box-arrow-in-right"></i>
                                Absen Masuk
                            </button>
                        </form>
                    </div>

                @elseif($todayLogbook->clock_out)
                    {{-- Selesai --}}
                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="timeline-dot active"></div>
                            <div class="timeline-info">
                                <div class="label">Absen Masuk</div>
                                <div class="time">{{ $todayLogbook->clock_in->format('H:i') }}</div>
                            </div>
                        </div>
                        @if($todayLogbook->overtime_started_at)
                            <div class="timeline-line active"></div>
                            <div class="timeline-item">
                                <div class="timeline-dot active"></div>
                                <div class="timeline-info">
                                    <div class="label">Mulai Lembur</div>
                                    <div class="time">{{ $todayLogbook->overtime_started_at->format('H:i') }}</div>
                                </div>
                            </div>
                        @else
                            <div class="timeline-line active"></div>
                        @endif
                        <div class="timeline-item">
                            <div class="timeline-dot active"></div>
                            <div class="timeline-info">
                                <div class="label">{{ $todayLogbook->overtime_ended_at ? 'Selesai Lembur' : 'Absen Keluar' }}</div>
                                <div class="time">{{ $todayLogbook->overtime_ended_at ? $todayLogbook->overtime_ended_at->format('H:i') : $todayLogbook->clock_out->format('H:i') }}</div>
                            </div>
                        </div>
                    </div>
                    <div style="text-align: center; margin-top: 18px;">
                        <span class="badge badge-success">
                            <i class="bi bi-check-lg"></i>
                            Absensi Selesai
                        </span>
                    </div>

                @elseif($todayLogbook->overtime_started_at && !$todayLogbook->overtime_ended_at)
                    {{-- Sedang Lembur --}}
                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="timeline-dot active"></div>
                            <div class="timeline-info">
                                <div class="label">Absen Masuk</div>
                                <div class="time">{{ $todayLogbook->clock_in->format('H:i') }}</div>
                            </div>
                        </div>
                        <div class="timeline-line active"></div>
                        <div class="timeline-item">
                            <div class="timeline-dot active"></div>
                            <div class="timeline-info">
                                <div class="label">Mulai Lembur</div>
                                <div class="time">{{ $todayLogbook->overtime_started_at->format('H:i') }}</div>
                            </div>
                        </div>
                        <div class="timeline-line"></div>
                        <div class="timeline-item">
                            <div class="timeline-dot"></div>
                            <div class="timeline-info">
                                <div class="label">Selesai Lembur</div>
                                <div class="time empty">--:--</div>
                            </div>
                        </div>
                    </div>
                    <div style="text-align: center; margin-top: 8px; margin-bottom: 12px;">
                        <span class="badge badge-warning">
                            <i class="bi bi-lightning"></i>
                            Sedang Lembur
                        </span>
                    </div>
                    <p style="text-align: center;">Klik tombol saat lembur selesai</p>
                    <div style="text-align: center; display: flex; gap: 12px; justify-content: center; flex-wrap: wrap;">
                        <form action="{{ route('internship.logbook.overtime.end') }}" method="POST"
                              onsubmit="return confirm('Selesaikan lembur?')">
                            @csrf
                            <button type="submit" class="btn btn-success btn-lg">
                                <i class="bi bi-stop-circle"></i>
                                Selesai Lembur
                            </button>
                        </form>
                    </div>

                @else
                    {{-- Sudah masuk, belum keluar, belum lembur --}}
                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="timeline-dot active"></div>
                            <div class="timeline-info">
                                <div class="label">Absen Masuk</div>
                                <div class="time">{{ $todayLogbook->clock_in->format('H:i') }}</div>
                            </div>
                        </div>
                        <div class="timeline-line"></div>
                        <div class="timeline-item">
                            <div class="timeline-dot"></div>
                            <div class="timeline-info">
                                <div class="label">Absen Keluar</div>
                                <div class="time empty">--:--</div>
                            </div>
                        </div>
                    </div>
                    <p style="text-align: center;">Klik tombol saat kegiatan PKL hari ini selesai</p>
                    <div style="text-align: center; display: flex; gap: 12px; justify-content: center; flex-wrap: wrap;">
                        <form action="{{ route('internship.logbook.clock-out') }}" method="POST"
                              onsubmit="return confirm('Absen keluar dan isi logbook?')">
                            @csrf
                            <button type="submit" class="btn btn-warning btn-lg">
                                <i class="bi bi-box-arrow-right"></i>
                                Absen Keluar
                            </button>
                        </form>
                        <form action="{{ route('internship.logbook.overtime.start') }}" method="POST"
                              onsubmit="return confirm('Mulai lembur? Absen keluar tidak akan diisi.')">
                            @csrf
                            <button type="submit" class="btn btn-overtime btn-lg">
                                <i class="bi bi-lightning"></i>
                                Lanjut Lembur
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        </div>

        {{-- Logbook Status --}}
        @if($todayLogbook && ($todayLogbook->clock_out || $todayLogbook->overtime_started_at))
            <div class="card mt-4">
                <div class="card-header">
                    <h3>
                        <i class="bi bi-journal-text"></i>
                        Logbook Hari Ini
                    </h3>
                    @if(!$todayLogbook->activities)
                        <span class="badge badge-warning">Belum Diisi</span>
                    @elseif($todayLogbook->approval_status === 'Ditolak')
                        <span class="badge badge-danger">Ditolak</span>
                    @else
                        <span class="badge badge-info">{{ $todayLogbook->approval_status }}</span>
                    @endif
                </div>
                <div class="card-body logbook-status">
                    @if(!$todayLogbook->activities)
                        <p class="mb-0" style="margin-bottom: 14px;">
                            Silakan isi logbook untuk kegiatan hari ini.
                        </p>
                        <a href="{{ route('internship.logbook.create', $todayLogbook->id) }}" class="btn btn-primary">
                            <i class="bi bi-pencil-square"></i>
                            Isi Logbook
                        </a>
                    @elseif($todayLogbook->approval_status === 'Ditolak')
                        {{-- Rejection reason --}}
                        @if($todayLogbook->rejection_reason)
                            <div style="padding: 12px 14px; background: rgba(217, 92, 92, 0.06); border: 1px solid rgba(217, 92, 92, 0.20); border-radius: var(--radius-sm); margin-bottom: 14px;">
                                <div style="display: flex; align-items: flex-start; gap: 8px;">
                                    <i class="bi bi-info-circle" style="color: var(--danger); margin-top: 2px;"></i>
                                    <div>
                                        <div style="font-size: 0.78rem; font-weight: 600; color: var(--danger); margin-bottom: 3px;">Alasan Penolakan</div>
                                        <div style="font-size: 0.85rem; color: var(--text);">{{ $todayLogbook->rejection_reason }}</div>
                                    </div>
                                </div>
                            </div>
                        @endif
                        <p class="mb-0" style="margin-bottom: 14px;">
                            Logbook ditolak oleh pembimbing. Silakan perbaiki dan submit ulang.
                        </p>
                        <a href="{{ route('internship.logbook.edit', $todayLogbook->id) }}" class="btn btn-primary">
                            <i class="bi bi-pencil-square"></i>
                            Perbaiki Logbook
                        </a>
                    @else
                        <p class="mb-0" style="margin-bottom: 14px;">
                            Logbook sudah disubmit, menunggu persetujuan pembimbing.
                        </p>
                        <a href="{{ route('internship.logbook.history') }}" class="btn btn-outline">
                            <i class="bi bi-clock-history"></i>
                            Lihat Riwayat
                        </a>
                    @endif
                </div>
            </div>
        @endif
    </div>

    {{-- Guide --}}
    <div class="col-lg-4 mb-4">
        <div class="card">
            <div class="card-header">
                <h3>
                    <i class="bi bi-info-circle"></i>
                    Cara Menggunakan
                </h3>
            </div>
            <div class="card-body">
                <div class="steps">
                    <div class="step">
                        <div class="step-number">01</div>
                        <div class="step-content">
                            <h5>Absen Masuk</h5>
                            <p>Catat waktu mulai PKL</p>
                        </div>
                    </div>
                    <div class="step">
                        <div class="step-number peach">02</div>
                        <div class="step-content">
                            <h5>Absen Keluar</h5>
                            <p>Catat waktu selesai PKL</p>
                        </div>
                    </div>
                    <div class="step">
                        <div class="step-number purple">03</div>
                        <div class="step-content">
                            <h5>Isi Logbook</h5>
                            <p>Masukkan kegiatan dan hasil</p>
                        </div>
                    </div>
                    <div class="step">
                        <div class="step-number peach">04</div>
                        <div class="step-content">
                            <h5>Submit</h5>
                            <p>Kirim untuk diperiksa pembimbing</p>
                        </div>
                    </div>
                </div>

                <div style="margin-top: 20px;">
                    <a href="{{ route('internship.logbook.history') }}" class="btn btn-outline btn-block">
                        <i class="bi bi-clock-history"></i>
                        Riwayat Logbook
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
