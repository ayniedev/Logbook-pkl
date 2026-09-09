@extends('layouts.admin')

@section('page-title', 'Tambah User')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">Users</a></li>
    <li class="breadcrumb-item active">Tambah</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Form Tambah User</h3>
            </div>
            <form action="{{ route('admin.users.store') }}" method="POST">
                @csrf
                <div class="card-body">
                    <!-- Username -->
                    <div class="mb-3">
                        <label for="username" class="form-label">Username <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('username') is-invalid @enderror"
                               id="username" name="username" value="{{ old('username') }}"
                               placeholder="Masukkan username" required>
                        @error('username')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Name -->
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                               id="name" name="name" value="{{ old('name') }}"
                               placeholder="Masukkan nama lengkap" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div class="mb-3">
                        <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                               id="email" name="email" value="{{ old('email') }}"
                               placeholder="Masukkan email" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="mb-3">
                        <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror"
                               id="password" name="password"
                               placeholder="Minimal 8 karakter" required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Password Confirmation -->
                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Konfirmasi Password <span class="text-danger">*</span></label>
                        <input type="password" class="form-control"
                               id="password_confirmation" name="password_confirmation"
                               placeholder="Ulangi password" required>
                    </div>

                    <!-- Role -->
                    <div class="mb-3">
                        <label for="role_id" class="form-label">Role <span class="text-danger">*</span></label>
                        <select class="form-select @error('role_id') is-invalid @enderror"
                                id="role_id" name="role_id" required>
                            <option value="">-- Pilih Role --</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                    {{ $role->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('role_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Pembimbing (only for Internship role) -->
                    <div class="mb-3" id="pembimbing-group" style="display: {{ old('role_id') ? 'block' : 'none' }};">
                        <label for="pembimbing_id" class="form-label">Pembimbing</label>
                        <select class="form-select @error('pembimbing_id') is-invalid @enderror"
                                id="pembimbing_id" name="pembimbing_id">
                            <option value="">-- Pilih Pembimbing --</option>
                            @foreach($pembimbings as $p)
                                <option value="{{ $p->id }}" {{ old('pembimbing_id') == $p->id ? 'selected' : '' }}>
                                    {{ $p->name ?? $p->username }}
                                </option>
                            @endforeach
                        </select>
                        @error('pembimbing_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Schools -->
                    <div class="mb-3">
                        <label for="schools" class="form-label">Sekolah</label>
                        <input type="text" class="form-control @error('schools') is-invalid @enderror"
                               id="schools" name="schools" value="{{ old('schools') }}"
                               placeholder="Masukkan nama sekolah (opsional)">
                        @error('schools')
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
                    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
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
                    <li><strong>Username</strong> harus unik dan digunakan untuk login.</li>
                    <li><strong>Password</strong> minimal 8 karakter.</li>
                    <li><strong>Role</strong> menentukan hak akses user.</li>
                    <li><strong>Status Aktif</strong> user bisa login jika aktif.</li>
                </ul>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const roleSelect = document.getElementById('role_id');
        const pembimbingGroup = document.getElementById('pembimbing-group');

        function togglePembimbing() {
            const selectedRole = roleSelect.options[roleSelect.selectedIndex].text;
            pembimbingGroup.style.display = selectedRole === 'Internship' ? 'block' : 'none';
        }

        roleSelect.addEventListener('change', togglePembimbing);
        togglePembimbing();
    });
</script>
@endpush
@endsection
