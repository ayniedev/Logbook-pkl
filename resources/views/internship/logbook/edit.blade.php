@extends('layouts.internship')

@section('page-title', 'Perbaiki Logbook')
@section('page-subtitle', $logbook->clock_in->format('d M Y'))

@section('content')
<div class="row">
    <div class="col-lg-8 mb-4">
        <div class="card">
            <div class="card-header">
                <h3>
                    <i class="bi bi-pencil-square"></i>
                    Perbaiki Logbook
                </h3>
            </div>
            <div class="card-body">
                {{-- Rejection Reason --}}
                <div style="padding: 16px; background: rgba(217, 92, 92, 0.06); border: 1px solid rgba(217, 92, 92, 0.20); border-radius: var(--radius-sm); margin-bottom: 20px;">
                    <div style="display: flex; align-items: flex-start; gap: 10px;">
                        <i class="bi bi-exclamation-triangle" style="color: var(--danger); font-size: 1.1rem; margin-top: 1px;"></i>
                        <div>
                            <div style="font-size: 0.82rem; font-weight: 700; color: var(--danger); margin-bottom: 4px;">Alasan Penolakan</div>
                            <div style="font-size: 0.88rem; color: var(--text);">{{ $logbook->rejection_reason }}</div>
                        </div>
                    </div>
                </div>

                {{-- Attendance Info (Read-only) --}}
                <div style="display: flex; gap: 24px; padding: 16px; background: var(--surface-secondary); border-radius: var(--radius-sm); margin-bottom: 24px; border: 1px solid var(--border);">
                    <div style="flex: 1; text-align: center;">
                        <div style="font-size: 0.75rem; color: var(--text-secondary); margin-bottom: 4px;">Tanggal</div>
                        <div style="font-weight: 600; color: var(--text);">{{ $logbook->clock_in->format('d M Y') }}</div>
                    </div>
                    <div style="width: 1px; background: var(--border);"></div>
                    <div style="flex: 1; text-align: center;">
                        <div style="font-size: 0.75rem; color: var(--text-secondary); margin-bottom: 4px;">Jam Masuk</div>
                        <div style="font-weight: 600; color: var(--success);">{{ $logbook->clock_in->format('H:i') }}</div>
                    </div>
                    <div style="width: 1px; background: var(--border);"></div>
                    <div style="flex: 1; text-align: center;">
                        <div style="font-size: 0.75rem; color: var(--text-secondary); margin-bottom: 4px;">Jam Keluar</div>
                        <div style="font-weight: 600; color: var(--warning);">{{ $logbook->clock_out ? $logbook->clock_out->format('H:i') : '-' }}</div>
                    </div>
                </div>

                <form action="{{ route('internship.logbook.update', $logbook->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label for="project_id" class="form-label">Project (Opsional)</label>
                        <select class="form-control @error('project_id') is-invalid @enderror"
                                id="project_id" name="project_id">
                            <option value="">-- Pilih Project --</option>
                            @foreach($projects as $project)
                                <option value="{{ $project->id }}" {{ old('project_id', $logbook->project_id) == $project->id ? 'selected' : '' }}>
                                    {{ $project->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('project_id')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="activities" class="form-label">
                            Kegiatan <span class="required">*</span>
                        </label>
                        <textarea class="form-control @error('activities') is-invalid @enderror"
                                  id="activities" name="activities" rows="5"
                                  placeholder="Jelaskan kegiatan yang dilakukan hari ini..." required>{{ old('activities', $logbook->activities) }}</textarea>
                        @error('activities')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="result" class="form-label">
                            Hasil Kegiatan <span class="required">*</span>
                        </label>
                        <textarea class="form-control @error('result') is-invalid @enderror"
                                  id="result" name="result" rows="5"
                                  placeholder="Jelaskan hasil atau pencapaian hari ini..." required>{{ old('result', $logbook->result) }}</textarea>
                        @error('result')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-send"></i>
                            Submit Ulang
                        </button>
                        <a href="{{ route('internship.logbook.history') }}" class="btn btn-outline">
                            <i class="bi bi-arrow-left"></i>
                            Batalkan
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4 mb-4">
        <div class="card">
            <div class="card-header">
                <h3>
                    <i class="bi bi-info-circle"></i>
                    Panduan
                </h3>
            </div>
            <div class="card-body">
                <div class="steps">
                    <div class="step">
                        <div class="step-number">01</div>
                        <div class="step-content">
                            <h5>Baca Alasan</h5>
                            <p>Perhatikan alasan penolakan dari pembimbing</p>
                        </div>
                    </div>
                    <div class="step">
                        <div class="step-number peach">02</div>
                        <div class="step-content">
                            <h5>Perbaiki Data</h5>
                            <p>Ubah kegiatan atau hasil sesuai masukan</p>
                        </div>
                    </div>
                    <div class="step">
                        <div class="step-number purple">03</div>
                        <div class="step-content">
                            <h5>Submit Ulang</h5>
                            <p>Kirim kembali untuk diperiksa pembimbing</p>
                        </div>
                    </div>
                </div>

                <hr style="margin: 20px 0; border-color: var(--border);">

                <a href="{{ route('internship.logbook.history') }}" class="btn btn-outline btn-block">
                    <i class="bi bi-clock-history"></i>
                    Riwayat Logbook
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
