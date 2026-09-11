@extends('layouts.admin')

@section('page-title', 'Penempatan PKL')

@section('breadcrumb')
    <li class="breadcrumb-item active">Penempatan PKL</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Daftar Penempatan</h3>
                <div class="card-tools">
                    <a href="{{ route('admin.assignments.create') }}" class="btn btn-primary btn-sm">
                        <i class="bi bi-plus-lg me-1"></i> Tambah Penempatan
                    </a>
                </div>
            </div>

            <!-- Filter & Search -->
            <div class="card-body pb-0">
                <form method="GET" action="{{ route('admin.assignments.index') }}" class="row g-2 mb-3">
                    <div class="col-md-2">
                        <input type="text" name="search" class="form-control form-control-sm"
                               placeholder="Cari peserta..."
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
                        <select name="mentor_id" class="form-select form-select-sm">
                            <option value="">Semua Pembimbing</option>
                            @foreach($mentors as $m)
                                <option value="{{ $m->id }}" {{ request('mentor_id') == $m->id ? 'selected' : '' }}>
                                    {{ $m->name ?? $m->username }}
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
                        <a href="{{ route('admin.assignments.index') }}" class="btn btn-outline-secondary btn-sm">
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
                            <th>Peserta</th>
                            <th>Sekolah</th>
                            <th>Divisi</th>
                            <th>Pembimbing</th>
                            <th width="100">Status</th>
                            <th width="150">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($assignments as $assignment)
                            <tr>
                                <td>{{ $assignments->firstItem() + $loop->index }}</td>
                                <td><strong>{{ $assignment->internship->name ?? $assignment->internship->username }}</strong></td>
                                <td>{{ $assignment->internship->schools ?? '-' }}</td>
                                <td><span class="badge bg-info">{{ $assignment->division->name }}</span></td>
                                <td>{{ $assignment->mentor->name ?? $assignment->mentor->username }}</td>
                                <td>
                                    @if($assignment->is_active)
                                        <span class="badge bg-success">Aktif</span>
                                    @else
                                        <span class="badge bg-secondary">Nonaktif</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.assignments.edit', $assignment) }}" class="btn btn-warning btn-sm" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('admin.assignments.toggle', $assignment) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-{{ $assignment->is_active ? 'secondary' : 'success' }} btn-sm" title="{{ $assignment->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                                <i class="bi bi-{{ $assignment->is_active ? 'pause-circle' : 'play-circle' }}"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">
                                    <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                    Tidak ada data penempatan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($assignments->hasPages())
                <div class="card-footer">
                    {{ $assignments->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
