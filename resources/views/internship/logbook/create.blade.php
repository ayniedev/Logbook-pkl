@extends('layouts.internship')

@section('page-title', 'Isi Logbook')
@section('page-subtitle', $logbook->clock_in->format('d M Y'))

@section('content')
<div class="row">
    <div class="col-lg-8 mb-4">
        <div class="card">
            <div class="card-header">
                <h3>
                    <i class="bi bi-pencil-square"></i>
                    Form Logbook
                </h3>
            </div>
            <div class="card-body">
                {{-- Attendance Info --}}
                <div style="display: flex; flex-wrap: wrap; gap: 16px; padding: 16px; background: var(--surface-secondary); border-radius: var(--radius-sm); margin-bottom: 24px; border: 1px solid var(--border);">
                    <div style="flex: 1; min-width: 100px; text-align: center;">
                        <div style="font-size: 0.75rem; color: var(--text-secondary); margin-bottom: 4px;">Tanggal</div>
                        <div style="font-weight: 600; color: var(--text);">{{ $logbook->clock_in->format('d M Y') }}</div>
                    </div>
                    <div style="width: 1px; background: var(--border); flex-shrink: 0;"></div>
                    <div style="flex: 1; min-width: 100px; text-align: center;">
                        <div style="font-size: 0.75rem; color: var(--text-secondary); margin-bottom: 4px;">Jam Masuk</div>
                        <div style="font-weight: 600; color: var(--success);">{{ $logbook->clock_in->format('H:i') }}</div>
                    </div>
                    <div style="width: 1px; background: var(--border); flex-shrink: 0;"></div>
                    <div style="flex: 1; min-width: 100px; text-align: center;">
                        <div style="font-size: 0.75rem; color: var(--text-secondary); margin-bottom: 4px;">Jam Keluar</div>
                        <div style="font-weight: 600; color: var(--warning);">{{ $logbook->clock_out ? $logbook->clock_out->format('H:i') : '-' }}</div>
                    </div>
                </div>

                <form action="{{ route('internship.logbook.store', $logbook->id) }}" method="POST">
                    @csrf

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
                            Submit Logbook
                        </button>
                        <a href="{{ route('internship.dashboard') }}" class="btn btn-outline">
                            <i class="bi bi-arrow-left"></i>
                            Kembali
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
                            <h5>Kegiatan</h5>
                            <p>Jelaskan aktivitas hari ini</p>
                        </div>
                    </div>
                    <div class="step">
                        <div class="step-number peach">02</div>
                        <div class="step-content">
                            <h5>Hasil Kegiatan</h5>
                            <p>Catat pencapaian dari kegiatan</p>
                        </div>
                    </div>
                    <div class="step">
                        <div class="step-number purple">03</div>
                        <div class="step-content">
                            <h5>Submit</h5>
                            <p>Kirim untuk persetujuan pembimbing</p>
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
