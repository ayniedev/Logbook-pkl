@extends('layouts.pembimbing')

@section('page-title', 'Detail Logbook')
@section('page-subtitle', $logbook->user->name ?? $logbook->user->username ?? 'Peserta')

@section('content')
<div class="mb-3">
    <a href="{{ route('pembimbing.logbook.index') }}" class="btn-pmb btn-outline-pmb btn-sm-pmb">
        <i class="bi bi-arrow-left"></i>
        Kembali
    </a>
</div>

<div class="card-pmb section-card">
    <div class="section-head">
        <div>
            <h3>Logbook — {{ $logbook->clock_in->format('d M Y') }}</h3>
            <p>Detail kegiatan peserta PKL.</p>
        </div>
        <div>
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
        </div>
    </div>

    {{-- Info grid --}}
    <div class="detail-grid">
        <div class="detail-item">
            <div class="detail-label">Nama Peserta</div>
            <div class="detail-value">{{ $logbook->user->name ?? $logbook->user->username ?? '-' }}</div>
        </div>
        <div class="detail-item">
            <div class="detail-label">Tanggal</div>
            <div class="detail-value">{{ $logbook->clock_in->format('d M Y') }}</div>
        </div>
        <div class="detail-item">
            <div class="detail-label">Jam Masuk</div>
            <div class="detail-value">{{ $logbook->clock_in->format('H:i') }}</div>
        </div>
        <div class="detail-item">
            <div class="detail-label">Jam Keluar</div>
            <div class="detail-value">{{ $logbook->clock_out ? $logbook->clock_out->format('H:i') : '-' }}</div>
        </div>
        <div class="detail-item">
            <div class="detail-label">Project</div>
            <div class="detail-value">{{ $logbook->project->name ?? '-' }}</div>
        </div>
    </div>

    {{-- Activities --}}
    <div class="detail-block">
        <div class="detail-label">Kegiatan</div>
        <div class="detail-text">{{ $logbook->activities ?? '-' }}</div>
    </div>

    {{-- Result --}}
    <div class="detail-block">
        <div class="detail-label">Hasil Kegiatan</div>
        <div class="detail-text">{{ $logbook->result ?? '-' }}</div>
    </div>

    {{-- Rejection reason (if rejected) --}}
    @if ($logbook->approval_status === 'Ditolak' && $logbook->rejection_reason)
        <div class="detail-block">
            <div class="detail-label">Alasan Penolakan</div>
            <div class="detail-text reject">{{ $logbook->rejection_reason }}</div>
        </div>
    @endif

    {{-- Review info --}}
    @if (in_array($logbook->approval_status, ['Disetujui', 'Ditolak']))
        <div class="detail-block">
            <div class="detail-label">Diperiksa oleh</div>
            <div class="detail-value">
                {{ $logbook->approver->name ?? $logbook->approver->username ?? '-' }}
                @if ($logbook->approved_at)
                    <span style="font-weight: 400; color: var(--text-secondary); font-size: 0.85rem;">
                        pada {{ $logbook->approved_at->format('d M Y H:i') }} WIB
                    </span>
                @endif
            </div>
        </div>
    @endif

    {{-- Actions: approve / reject (only when Diajukan) --}}
    @if ($logbook->approval_status === 'Diajukan')
        <div class="d-flex gap-2 mt-4 flex-wrap">
            <form method="POST" action="{{ route('pembimbing.logbook.approve', $logbook->id) }}">
                @csrf
                <button type="submit" class="btn-pmb btn-success-pmb">
                    <i class="bi bi-check-lg"></i>
                    Setujui
                </button>
            </form>
            <button type="button" class="btn-pmb btn-danger-pmb" onclick="document.getElementById('rejectModal').classList.add('active')">
                <i class="bi bi-x-lg"></i>
                Tolak
            </button>
        </div>
    @endif
</div>

{{-- Reject modal --}}
<div class="modal-overlay" id="rejectModal">
    <div class="modal-box">
        <h5><i class="bi bi-exclamation-triangle"></i> Tolak Logbook</h5>
        <p>Berikan alasan penolakan. Alasan ini akan dilihat oleh peserta.</p>
        <form method="POST" action="{{ route('pembimbing.logbook.reject', $logbook->id) }}">
            @csrf
            <textarea name="rejection_reason" required placeholder="Contoh: Kegiatan belum dijelaskan secara lengkap."></textarea>
            @error('rejection_reason')
                <div style="font-size: 0.8rem; color: var(--danger); margin-top: 4px;">{{ $message }}</div>
            @enderror
            <div class="modal-actions">
                <button type="button" class="btn-pmb btn-outline-pmb" onclick="document.getElementById('rejectModal').classList.remove('active')">
                    Batal
                </button>
                <button type="submit" class="btn-pmb btn-danger-pmb">
                    <i class="bi bi-x-lg"></i>
                    Tolak Logbook
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
