@extends('layouts.admin')

@section('page-title', 'Manajemen Divisi')

@section('breadcrumb')
    <li class="breadcrumb-item active">Divisi</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Daftar Divisi</h3>
                <div class="card-tools">
                    <a href="{{ route('admin.divisions.create') }}" class="btn btn-primary btn-sm">
                        <i class="bi bi-plus-lg me-1"></i> Tambah Divisi
                    </a>
                </div>
            </div>

            <!-- Filter & Search -->
            <div class="card-body pb-0">
                <form method="GET" action="{{ route('admin.divisions.index') }}" class="row g-2 mb-3">
                    <div class="col-md-4">
                        <input type="text" name="search" class="form-control form-control-sm"
                               placeholder="Cari nama divisi..."
                               value="{{ request('search') }}">
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
                        <a href="{{ route('admin.divisions.index') }}" class="btn btn-outline-secondary btn-sm">
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
                            <th>Nama Divisi</th>
                            <th width="100" class="text-center">Pembimbing</th>
                            <th width="100" class="text-center">Peserta PKL</th>
                            <th width="100">Status</th>
                            <th width="150">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($divisions as $division)
                            <tr>
                                <td>{{ $divisions->firstItem() + $loop->index }}</td>
                                <td><strong>{{ $division->name }}</strong></td>
                                <td class="text-center">{{ $division->mentors_count }}</td>
                                <td class="text-center">{{ $division->internship_assignments_count }}</td>
                                <td>
                                    @if($division->is_active)
                                        <span class="badge bg-success">Aktif</span>
                                    @else
                                        <span class="badge bg-secondary">Nonaktif</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.divisions.edit', $division) }}" class="btn btn-warning btn-sm" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('admin.divisions.toggle', $division) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-{{ $division->is_active ? 'secondary' : 'success' }} btn-sm" title="{{ $division->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                                <i class="bi bi-{{ $division->is_active ? 'pause-circle' : 'play-circle' }}"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">
                                    <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                    Tidak ada data divisi.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($divisions->hasPages())
                <div class="card-footer">
                    {{ $divisions->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
