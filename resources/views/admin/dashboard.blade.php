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
            </div>
        </div>
    </div>
</div>
@endsection
