@extends('layouts.internship')

@section('page-title', 'Riwayat Logbook')
@section('page-subtitle', 'Semua logbook yang sudah Anda submit')

@section('content')
@if(session('success'))
    <div class="alert alert-success">
        <i class="bi bi-check-circle"></i>
        <span>{{ session('success') }}</span>
    </div>
@endif

<div class="card">
    <div class="card-header">
        <h3>
            <i class="bi bi-clock-history"></i>
            Riwayat Logbook
        </h3>
        <a href="{{ route('internship.dashboard') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i>
            Absen Hari Ini
        </a>
    </div>
    <div class="card-body">
        @if($logbooks->count() > 0)
            <div class="table-wrapper">
                <table class="table">
                    <thead>
                        <tr>
                            <th width="50">No</th>
                            <th>Tanggal</th>
                            <th width="80">Masuk</th>
                            <th width="80">Keluar</th>
                            <th>Kegiatan</th>
                            <th>Hasil</th>
                            <th width="90">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($logbooks as $logbook)
                            <tr>
                                <td>{{ $logbooks->firstItem() + $loop->index }}</td>
                                <td><strong>{{ $logbook->clock_in->format('d M Y') }}</strong></td>
                                <td><span style="color: var(--success);">{{ $logbook->clock_in->format('H:i') }}</span></td>
                                <td>
                                    @if($logbook->clock_out)
                                        <span style="color: var(--warning);">{{ $logbook->clock_out->format('H:i') }}</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="text-truncate" style="display: inline-block; max-width: 180px;" title="{{ $logbook->activities }}">
                                        {{ $logbook->activities ?? '-' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="text-truncate" style="display: inline-block; max-width: 180px;" title="{{ $logbook->result }}">
                                        {{ $logbook->result ?? '-' }}
                                    </span>
                                </td>
                                <td>
                                    @switch($logbook->approval_status)
                                        @case('Pending')
                                            <span class="badge badge-secondary">Pending</span>
                                            @break
                                        @case('Diajukan')
                                            <span class="badge badge-info">Diajukan</span>
                                            @break
                                        @case('Disetujui')
                                            <span class="badge badge-success">Disetujui</span>
                                            @break
                                        @case('Ditolak')
                                            <span class="badge badge-danger">Ditolak</span>
                                            @break
                                        @default
                                            <span class="badge badge-secondary">{{ $logbook->approval_status }}</span>
                                    @endswitch
                                </td>
                            </tr>

                            @if($logbook->approval_status === 'Ditolak' && $logbook->rejection_reason)
                                <tr>
                                    <td colspan="7" style="background: rgba(217, 92, 92, 0.05); padding: 12px 16px;">
                                        <div style="display: flex; align-items: flex-start; gap: 8px;">
                                            <i class="bi bi-info-circle" style="color: var(--danger); margin-top: 2px;"></i>
                                            <div>
                                                <div style="font-size: 0.8rem; font-weight: 600; color: var(--danger); margin-bottom: 4px;">Alasan Penolakan</div>
                                                <div style="font-size: 0.85rem; color: var(--text);">{{ $logbook->rejection_reason }}</div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($logbooks->hasPages())
                <div style="margin-top: 20px;">
                    {{ $logbooks->links() }}
                </div>
            @endif
        @else
            <div class="empty-state">
                <i class="bi bi-inbox"></i>
                <h4>Belum Ada Riwayat</h4>
                <p>Anda belum memiliki riwayat logbook.</p>
                <a href="{{ route('internship.dashboard') }}" class="btn btn-primary">
                    <i class="bi bi-plus-lg"></i>
                    Mulai Absen Hari Ini
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
