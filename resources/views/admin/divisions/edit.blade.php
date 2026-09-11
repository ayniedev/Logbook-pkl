@extends('layouts.admin')

@section('page-title', 'Edit Divisi')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.divisions.index') }}">Divisi</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Form Edit Divisi: {{ $division->name }}</h3>
            </div>
            <form action="{{ route('admin.divisions.update', $division) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="card-body">
                    <!-- Name -->
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Divisi <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                               id="name" name="name" value="{{ old('name', $division->name) }}"
                               placeholder="Masukkan nama divisi" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1"
                                   {{ old('is_active', $division->is_active ? '1' : '0') === '1' ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">Aktif</label>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-1"></i> Update
                    </button>
                    <a href="{{ route('admin.divisions.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left me-1"></i> Kembali
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card card-warning card-outline">
            <div class="card-header">
                <h3 class="card-title"><i class="bi bi-info-circle me-1"></i> Info Divisi</h3>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <tr>
                        <td><strong>ID</strong></td>
                        <td>{{ $division->id }}</td>
                    </tr>
                    <tr>
                        <td><strong>Pembimbing</strong></td>
                        <td>{{ $division->mentors()->count() }}</td>
                    </tr>
                    <tr>
                        <td><strong>Peserta PKL</strong></td>
                        <td>{{ $division->internshipAssignments()->count() }}</td>
                    </tr>
                    <tr>
                        <td><strong>Status</strong></td>
                        <td>
                            @if($division->is_active)
                                <span class="badge bg-success">Aktif</span>
                            @else
                                <span class="badge bg-secondary">Nonaktif</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Created</strong></td>
                        <td>{{ $division->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
