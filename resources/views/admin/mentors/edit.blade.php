@extends('layouts.admin')

@section('page-title', 'Edit Pembimbing')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.mentors.index') }}">Pembimbing</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Kelola Pembimbing: {{ $mentor->name ?? $mentor->username }}</h3>
            </div>
            <form action="{{ route('admin.mentors.update', $mentor) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="card-body">
                    <!-- Info User (Read-only) -->
                    <div class="mb-3">
                        <label class="form-label">Nama</label>
                        <input type="text" class="form-control" value="{{ $mentor->name ?? $mentor->username }}" readonly disabled>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="text" class="form-control" value="{{ $mentor->email }}" readonly disabled>
                    </div>

                    <!-- Division -->
                    <div class="mb-3">
                        <label for="division_id" class="form-label">Divisi <span class="text-danger">*</span></label>
                        <select class="form-select @error('division_id') is-invalid @enderror"
                                id="division_id" name="division_id">
                            <option value="">-- Pilih Divisi --</option>
                            @foreach($divisions as $div)
                                <option value="{{ $div->id }}" {{ old('division_id', $mentor->division_id) == $div->id ? 'selected' : '' }}>
                                    {{ $div->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('division_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-1"></i> Simpan
                    </button>
                    <a href="{{ route('admin.mentors.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left me-1"></i> Kembali
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card card-warning card-outline">
            <div class="card-header">
                <h3 class="card-title"><i class="bi bi-info-circle me-1"></i> Info Pembimbing</h3>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <tr>
                        <td><strong>ID</strong></td>
                        <td>{{ $mentor->id }}</td>
                    </tr>
                    <tr>
                        <td><strong>Username</strong></td>
                        <td>{{ $mentor->username }}</td>
                    </tr>
                    <tr>
                        <td><strong>Role</strong></td>
                        <td><span class="badge bg-warning">{{ $mentor->role->name }}</span></td>
                    </tr>
                    <tr>
                        <td><strong>Divisi</strong></td>
                        <td>
                            @if($mentor->division)
                                <span class="badge bg-info">{{ $mentor->division->name }}</span>
                            @else
                                <span class="text-muted">Belum ditentukan</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Jumlah Peserta</strong></td>
                        <td>{{ $mentor->peserta()->count() }}</td>
                    </tr>
                    <tr>
                        <td><strong>Status</strong></td>
                        <td>
                            @if($mentor->is_active)
                                <span class="badge bg-success">Aktif</span>
                            @else
                                <span class="badge bg-secondary">Nonaktif</span>
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
