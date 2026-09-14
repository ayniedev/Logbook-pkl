@extends('layouts.admin')

@section('page-title', 'Dashboard Admin')

@section('breadcrumb')
    <li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Selamat Datang!</h3>
            </div>
            <div class="card-body">
                <div class="alert alert-success">
                    <h4><i class="bi bi-check-circle me-2"></i> Hello, Admin!</h4>
                    <p class="mb-0">Kelola data user, divisi, pembimbing, dan penempatan PKL dari panel ini.</p>
                </div>

                <div class="row mt-4">
                    <div class="col-md-4 col-sm-6">
                        <div class="info-box">
                            <span class="info-box-icon bg-info"><i class="bi bi-people-fill"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Total Users</span>
                                <span class="info-box-number">{{ $stats['total_users'] }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-6">
                        <div class="info-box">
                            <span class="info-box-icon bg-warning"><i class="bi bi-person-badge-fill"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Pembimbing</span>
                                <span class="info-box-number">{{ $stats['total_mentors'] }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-6">
                        <div class="info-box">
                            <span class="info-box-icon bg-success"><i class="bi bi-person-fill"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Peserta PKL</span>
                                <span class="info-box-number">{{ $stats['total_interns'] }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-6">
                        <div class="info-box">
                            <span class="info-box-icon bg-secondary"><i class="bi bi-diagram-3-fill"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Divisi</span>
                                <span class="info-box-number">{{ $stats['total_divisions'] }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-6">
                        <div class="info-box">
                            <span class="info-box-icon bg-primary"><i class="bi bi-clipboard2-pulse-fill"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Penempatan Aktif</span>
                                <span class="info-box-number">{{ $stats['active_assignments'] }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-6">
                        <div class="info-box">
                            <span class="info-box-icon bg-danger"><i class="bi bi-journal-text"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Logbook Menunggu</span>
                                <span class="info-box-number">{{ $stats['pending_logbooks'] }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <hr>
                <h5 class="mt-3"><i class="bi bi-table me-2"></i>Daftar Penempatan Aktif</h5>

                @if($assignments->isEmpty())
                    <div class="alert alert-info mt-3">
                        <i class="bi bi-info-circle me-2"></i>Belum ada penempatan aktif.
                    </div>
                @else
                    <div class="table-responsive mt-3">
                        <table class="table table-bordered table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th width="50" class="text-center">No</th>
                                    <th>Nama Peserta PKL</th>
                                    <th>Sekolah</th>
                                    <th>Divisi</th>
                                    <th>Pembimbing</th>
                                    <th width="100" class="text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($assignments as $index => $assignment)
                                    <tr>
                                        <td class="text-center">{{ $index + 1 }}</td>
                                        <td>{{ $assignment->internship->name ?? '-' }}</td>
                                        <td>{{ $assignment->internship->schools ?? '-' }}</td>
                                        <td>{{ $assignment->division->name ?? '-' }}</td>
                                        <td>{{ $assignment->mentor->name ?? '-' }}</td>
                                        <td class="text-center">
                                            @if($assignment->is_active)
                                                <span class="badge bg-success">Aktif</span>
                                            @else
                                                <span class="badge bg-secondary">Non-aktif</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
