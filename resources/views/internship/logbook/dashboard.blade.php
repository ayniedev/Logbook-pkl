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

{{-- Stat Cards --}}
<div class="row mb-4">
    <div class="col-lg-4 col-md-6 mb-3">
        <div class="stat-card">
            <div class="icon {{ $todayLogbook ? ($todayLogbook->clock_out ? 'success' : 'mauve') : 'purple' }}">
                <i class="bi bi-{{ $todayLogbook ? ($todayLogbook->clock_out ? 'check-circle' : 'clock') : 'circle' }}"></i>
            </div>
            <div class="stat-info">
                <div class="stat-label">Status Absensi</div>
                <div class="stat-value">
                    @if(!$todayLogbook)
                        Belum Absen
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

                @elseif(!$todayLogbook->clock_out)
                    {{-- Sudah masuk, belum keluar --}}
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
                    <div style="text-align: center;">
                        <form action="{{ route('internship.logbook.clock-out') }}" method="POST"
                              onsubmit="return confirm('Absen keluar dan isi logbook?')">
                            @csrf
                            <button type="submit" class="btn btn-warning btn-lg">
                                <i class="bi bi-box-arrow-right"></i>
                                Absen Keluar
                            </button>
                        </form>
                    </div>

                @else
                    {{-- Selesai --}}
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
                                <div class="label">Absen Keluar</div>
                                <div class="time">{{ $todayLogbook->clock_out->format('H:i') }}</div>
                            </div>
                        </div>
                    </div>
                    <div style="text-align: center; margin-top: 18px;">
                        <span class="badge badge-success">
                            <i class="bi bi-check-lg"></i>
                            Absensi Selesai
                        </span>
                    </div>
                @endif
            </div>
        </div>

        {{-- Logbook Status --}}
        @if($todayLogbook && $todayLogbook->clock_out)
            <div class="card mt-4">
                <div class="card-header">
                    <h3>
                        <i class="bi bi-journal-text"></i>
                        Logbook Hari Ini
                    </h3>
                    @if(!$todayLogbook->activities)
                        <span class="badge badge-warning">Belum Diisi</span>
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
