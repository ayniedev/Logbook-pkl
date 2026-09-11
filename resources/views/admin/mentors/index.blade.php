@extends('layouts.admin')

@section('page-title', 'Manajemen Pembimbing')

@section('breadcrumb')
    <li class="breadcrumb-item active">Pembimbing</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Daftar Pembimbing</h3>
            </div>

            <!-- Filter & Search -->
            <div class="card-body pb-0">
                <form method="GET" action="{{ route('admin.mentors.index') }}" class="row g-2 mb-3">
                    <div class="col-md-3">
                        <input type="text" name="search" class="form-control form-control-sm"
                               placeholder="Cari nama, email..."
                               value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2">
                        <select name="division_id" class="form-select form-select-sm">
                            <option value="">Semua Divisi</option>
                            @foreach($divisions as $div)
                                <option value="{{ $div->id }}" {{ request('division_id') == $div->id ? 'selected' : '' }}>
                                    {{ $div->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="is_active" class="form-select form-select-sm">
                            <option value="">Semua Status</option>
                            <option value="1" {{ request('is_active') === '1' ? 'selected' : '' }}>Aktif</option>
                            <option value="0" {{ request('is_active') === '0' ? 'selected' : '' }}>Nonaktif</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-secondary btn-sm">
                            <i class="bi bi-search me-1"></i> Filter
                        </button>
                        <a href="{{ route('admin.mentors.index') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-x-lg"></i>
                        </a>
                    </div>
                </form>
            </div>

            <!-- Alerts -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show mx-3" role="alert">
                    <i class="bi bi-check-circle me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show mx-3" role="alert">
                    <i class="bi bi-exclamation-triangle me-2"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Table -->
            <div class="card-body table-responsive p-0">
                <table class="table table-hover table-striped">
                    <thead>
                        <tr>
                            <th width="50">No</th>
                            <th>Nama Pembimbing</th>
                            <th>Email</th>
                            <th>Divisi</th>
                            <th width="100" class="text-center">Jumlah Peserta</th>
                            <th width="100">Status</th>
                            <th width="100">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($mentors as $mentor)
                            <tr>
                                <td>{{ $mentors->firstItem() + $loop->index }}</td>
                                <td><strong>{{ $mentor->name ?? $mentor->username }}</strong></td>
                                <td>{{ $mentor->email }}</td>
                                <td>
                                    @if($mentor->division)
                                        <span class="badge bg-info">{{ $mentor->division->name }}</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td class="text-center">{{ $mentor->peserta()->count() }}</td>
                                <td>
                                    @if($mentor->is_active)
                                        <span class="badge bg-success">Aktif</span>
                                    @else
                                        <span class="badge bg-secondary">Nonaktif</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.mentors.edit', $mentor) }}" class="btn btn-warning btn-sm" title="Kelola">
                                        <i class="bi bi-pencil"></i> Kelola
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">
                                    <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                    Tidak ada data pembimbing.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($mentors->hasPages())
                <div class="card-footer">
                    {{ $mentors->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
