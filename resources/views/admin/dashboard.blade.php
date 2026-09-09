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
                    <p class="mb-0">Integrasi AdminLTE 4 berhasil. Dashboard ini menggunakan tampilan AdminLTE 4 dengan Laravel.</p>
                </div>

                <div class="row mt-4">
                    <div class="col-md-4">
                        <div class="info-box">
                            <span class="info-box-icon bg-info"><i class="bi bi-person-fill"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Total Users</span>
                                <span class="info-box-number">{{ \App\Models\User::count() ?? 0 }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="info-box">
                            <span class="info-box-icon bg-success"><i class="bi bi-shield-fill"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Role: Admin</span>
                                <span class="info-box-number">{{ Auth::user()->role->name ?? 'N/A' }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="info-box">
                            <span class="info-box-icon bg-warning"><i class="bi bi-envelope-fill"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Email</span>
                                <span class="info-box-number">{{ Auth::user()->email ?? 'N/A' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
