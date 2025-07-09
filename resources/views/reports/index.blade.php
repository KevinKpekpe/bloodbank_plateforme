@extends('layouts.app')

@section('title', 'Rapports - BloodLink')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('superadmin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Rapports</li>
                    </ol>
                </div>
                <h4 class="page-title">
                    <i class="mdi mdi-chart-line me-1"></i>
                    Rapports et Statistiques
                </h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-4">Sélectionnez un rapport</h5>

                    <div class="row">
                        <!-- Rapport Général -->
                        <div class="col-xl-3 col-md-6">
                            <div class="card report-card">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0">
                                            <div class="avatar-sm rounded">
                                                <span class="avatar-title bg-primary-lighten text-primary rounded">
                                                    <i class="mdi mdi-chart-donut font-20"></i>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h5 class="font-14 my-1">
                                                <a href="{{ route('reports.general') }}" class="text-body">Rapport Général</a>
                                            </h5>
                                            <p class="mb-0 text-muted">
                                                <span class="text-success me-2">
                                                    <i class="mdi mdi-arrow-up-bold"></i> Vue d'ensemble
                                                </span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Rapport des Banques -->
                        <div class="col-xl-3 col-md-6">
                            <div class="card report-card">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0">
                                            <div class="avatar-sm rounded">
                                                <span class="avatar-title bg-success-lighten text-success rounded">
                                                    <i class="mdi mdi-hospital-building font-20"></i>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h5 class="font-14 my-1">
                                                <a href="{{ route('reports.banks') }}" class="text-body">Banques de Sang</a>
                                            </h5>
                                            <p class="mb-0 text-muted">
                                                <span class="text-success me-2">
                                                    <i class="mdi mdi-arrow-up-bold"></i> Performance
                                                </span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Rapport des Groupes Sanguins -->
                        <div class="col-xl-3 col-md-6">
                            <div class="card report-card">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0">
                                            <div class="avatar-sm rounded">
                                                <span class="avatar-title bg-warning-lighten text-warning rounded">
                                                    <i class="mdi mdi-blood-bag font-20"></i>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h5 class="font-14 my-1">
                                                <a href="{{ route('reports.blood-types') }}" class="text-body">Groupes Sanguins</a>
                                            </h5>
                                            <p class="mb-0 text-muted">
                                                <span class="text-success me-2">
                                                    <i class="mdi mdi-arrow-up-bold"></i> Disponibilité
                                                </span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Rapport des Rendez-vous -->
                        <div class="col-xl-3 col-md-6">
                            <div class="card report-card">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0">
                                            <div class="avatar-sm rounded">
                                                <span class="avatar-title bg-info-lighten text-info rounded">
                                                    <i class="mdi mdi-calendar-check font-20"></i>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h5 class="font-14 my-1">
                                                <a href="{{ route('reports.appointments') }}" class="text-body">Rendez-vous</a>
                                            </h5>
                                            <p class="mb-0 text-muted">
                                                <span class="text-success me-2">
                                                    <i class="mdi mdi-arrow-up-bold"></i> Planification
                                                </span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Rapport des Dons -->
                        <div class="col-xl-3 col-md-6">
                            <div class="card report-card">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0">
                                            <div class="avatar-sm rounded">
                                                <span class="avatar-title bg-danger-lighten text-danger rounded">
                                                    <i class="mdi mdi-heart-pulse font-20"></i>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h5 class="font-14 my-1">
                                                <a href="{{ route('reports.donations') }}" class="text-body">Dons de Sang</a>
                                            </h5>
                                            <p class="mb-0 text-muted">
                                                <span class="text-success me-2">
                                                    <i class="mdi mdi-arrow-up-bold"></i> Collecte
                                                </span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Rapport des Utilisateurs -->
                        <div class="col-xl-3 col-md-6">
                            <div class="card report-card">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0">
                                            <div class="avatar-sm rounded">
                                                <span class="avatar-title bg-secondary-lighten text-secondary rounded">
                                                    <i class="mdi mdi-account-group font-20"></i>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h5 class="font-14 my-1">
                                                <a href="{{ route('reports.users') }}" class="text-body">Utilisateurs</a>
                                            </h5>
                                            <p class="mb-0 text-muted">
                                                <span class="text-success me-2">
                                                    <i class="mdi mdi-arrow-up-bold"></i> Activité
                                                </span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques Rapides -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-4">Statistiques Rapides</h5>

                    <div class="row">
                        <div class="col-md-3">
                            <div class="text-center">
                                <h3 class="text-primary">{{ \App\Models\Bank::count() }}</h3>
                                <p class="text-muted">Banques de Sang</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <h3 class="text-success">{{ \App\Models\User::where('role', 'donor')->count() }}</h3>
                                <p class="text-muted">Donneurs</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <h3 class="text-info">{{ \App\Models\Appointment::count() }}</h3>
                                <p class="text-muted">Rendez-vous</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <h3 class="text-warning">{{ \App\Models\Donation::count() }}</h3>
                                <p class="text-muted">Dons</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.report-card {
    transition: transform 0.2s ease-in-out;
    cursor: pointer;
}

.report-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.avatar-sm {
    width: 48px;
    height: 48px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.bg-primary-lighten {
    background-color: rgba(114, 124, 245, 0.1);
}

.bg-success-lighten {
    background-color: rgba(10, 207, 151, 0.1);
}

.bg-warning-lighten {
    background-color: rgba(255, 188, 0, 0.1);
}

.bg-info-lighten {
    background-color: rgba(57, 175, 209, 0.1);
}

.bg-danger-lighten {
    background-color: rgba(250, 92, 124, 0.1);
}

.bg-secondary-lighten {
    background-color: rgba(108, 117, 125, 0.1);
}
</style>
@endsection