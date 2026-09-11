@extends('layouts.admin')

@section('page-title', 'Tambah Divisi')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.divisions.index') }}">Divisi</a></li>
    <li class="breadcrumb-item active">Tambah</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Form Tambah Divisi</h3>
            </div>
            <form action="{{ route('admin.divisions.store') }}" method="POST">
                @csrf
                <div class="card-body">
                    <!-- Name -->
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Divisi <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                               id="name" name="name" value="{{ old('name') }}"
                               placeholder="Masukkan nama divisi" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1"
                                   {{ old('is_active', '1') === '1' ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">Aktif</label>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-1"></i> Simpan
                    </button>
                    <a href="{{ route('admin.divisions.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left me-1"></i> Kembali
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title"><i class="bi bi-info-circle me-1"></i> Info</h3>
            </div>
            <div class="card-body">
                <ul class="mb-0" style="font-size: 0.9rem;">
                    <li><strong>Nama Divisi</strong> harus unik dan wajib diisi.</li>
                    <li><strong>Status Aktif</strong> divisi dapat dipilih untuk penempatan.</li>
                    <li>Divisi nonaktif tidak dapat dipilih untuk penempatan baru.</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
