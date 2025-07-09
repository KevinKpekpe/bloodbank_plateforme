@extends('layouts.app')

@section('title', 'Rapport des Banques - BloodLink')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('superadmin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('reports.index') }}">Rapports</a></li>
                        <li class="breadcrumb-item active">Banques de Sang</li>
                    </ol>
                </div>
                <h4 class="page-title">
                    <i class="mdi mdi-hospital-building me-1"></i>
                    Rapport des Banques de Sang
                </h4>
            </div>
        </div>
    </div>

    <!-- Statistiques Globales -->
    <div class="row">
        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar-sm rounded">
                                <span class="avatar-title bg-primary-lighten text-primary rounded">
                                    <i class="mdi mdi-hospital-building font-20"></i>
                                </span>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="font-14 my-1">Total Banques</h5>
                            <h3 class="text-primary my-1">{{ $banks->count() }}</h3>
                            <p class="mb-0 text-muted">
                                <span class="text-success me-2">
                                    <i class="mdi mdi-arrow-up-bold"></i> Actives
                                </span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar-sm rounded">
                                <span class="avatar-title bg-success-lighten text-success rounded">
                                    <i class="mdi mdi-account-group font-20"></i>
                                </span>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="font-14 my-1">Total Utilisateurs</h5>
                            <h3 class="text-success my-1">{{ $banks->sum('users_count') }}</h3>
                            <p class="mb-0 text-muted">
                                <span class="text-info me-2">
                                    <i class="mdi mdi-arrow-up-bold"></i> Donneurs
                                </span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card">
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
                            <h5 class="font-14 my-1">Total Rendez-vous</h5>
                            <h3 class="text-info my-1">{{ $banks->sum('appointments_count') }}</h3>
                            <p class="mb-0 text-muted">
                                <span class="text-warning me-2">
                                    <i class="mdi mdi-clock"></i> Planifiés
                                </span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar-sm rounded">
                                <span class="avatar-title bg-warning-lighten text-warning rounded">
                                    <i class="mdi mdi-heart-pulse font-20"></i>
                                </span>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="font-14 my-1">Total Dons</h5>
                            <h3 class="text-warning my-1">{{ $banks->sum('donations_count') }}</h3>
                            <p class="mb-0 text-muted">
                                <span class="text-success me-2">
                                    <i class="mdi mdi-arrow-up-bold"></i> Collectés
                                </span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Graphique de Performance -->
    <div class="row">
        <div class="col-xl-8">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Performance des Banques (Rendez-vous)</h5>
                    <canvas id="performanceChart" height="300"></canvas>
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Répartition des Dons</h5>
                    <canvas id="donationsChart" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Tableau des Banques -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Détails par Banque de Sang</h5>

                    <div class="table-responsive">
                        <table class="table table-centered table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Banque</th>
                                    <th>Statut</th>
                                    <th>Utilisateurs</th>
                                    <th>Rendez-vous</th>
                                    <th>Dons</th>
                                    <th>Dons Disponibles</th>
                                    <th>Performance</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($bankStats as $stat)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm me-3">
                                                <span class="avatar-title bg-primary-lighten text-primary rounded">
                                                    <i class="mdi mdi-hospital-building"></i>
                                                </span>
                                            </div>
                                            <div>
                                                <h6 class="mb-0">{{ $stat['name'] }}</h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $stat['status'] === 'active' ? 'success' : 'danger' }}">
                                            {{ $stat['status'] === 'active' ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ $stat['users_count'] }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-warning">{{ $stat['appointments_count'] }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-success">{{ $stat['donations_count'] }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-primary">{{ $stat['available_donations'] }}</span>
                                    </td>
                                    <td>
                                        @php
                                            $performance = $stat['appointments_count'] > 0 ?
                                                round(($stat['donations_count'] / $stat['appointments_count']) * 100, 1) : 0;
                                        @endphp
                                        <div class="d-flex align-items-center">
                                            <div class="progress flex-grow-1 me-2" style="height: 6px;">
                                                <div class="progress-bar bg-{{ $performance >= 50 ? 'success' : ($performance >= 25 ? 'warning' : 'danger') }}"
                                                     style="width: {{ min($performance, 100) }}%"></div>
                                            </div>
                                            <span class="text-muted small">{{ $performance }}%</span>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Actions</h5>

                    <div class="row">
                        <div class="col-md-6">
                            <a href="{{ route('reports.export') }}?type=banks&format=pdf" class="btn btn-primary me-2">
                                <i class="mdi mdi-download me-1"></i>
                                Exporter en PDF
                            </a>
                            <a href="{{ route('reports.export') }}?type=banks&format=excel" class="btn btn-success me-2">
                                <i class="mdi mdi-file-excel me-1"></i>
                                Exporter en Excel
                            </a>
                        </div>
                        <div class="col-md-6 text-end">
                            <a href="{{ route('reports.index') }}" class="btn btn-secondary">
                                <i class="mdi mdi-arrow-left me-1"></i>
                                Retour aux Rapports
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Graphique de performance
const performanceCtx = document.getElementById('performanceChart').getContext('2d');
const performanceChart = new Chart(performanceCtx, {
    type: 'bar',
    data: {
        labels: @json(array_column($bankStats, 'name')),
        datasets: [{
            label: 'Rendez-vous',
            data: @json(array_column($bankStats, 'appointments_count')),
            backgroundColor: 'rgba(75, 192, 192, 0.8)',
            borderColor: 'rgb(75, 192, 192)',
            borderWidth: 1
        }, {
            label: 'Dons',
            data: @json(array_column($bankStats, 'donations_count')),
            backgroundColor: 'rgba(255, 99, 132, 0.8)',
            borderColor: 'rgb(255, 99, 132)',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});

// Graphique des dons
const donationsCtx = document.getElementById('donationsChart').getContext('2d');
const donationsChart = new Chart(donationsCtx, {
    type: 'doughnut',
    data: {
        labels: ['Disponibles', 'Utilisés', 'Expirés'],
        datasets: [{
            data: [
                {{ $banks->sum(function($bank) { return $bank->donations()->where('status', 'available')->count(); }) }},
                {{ $banks->sum(function($bank) { return $bank->donations()->where('status', 'used')->count(); }) }},
                {{ $banks->sum(function($bank) { return $bank->donations()->where('status', 'expired')->count(); }) }}
            ],
            backgroundColor: [
                'rgba(75, 192, 192, 0.8)',
                'rgba(255, 99, 132, 0.8)',
                'rgba(255, 205, 86, 0.8)'
            ],
            borderWidth: 2
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
    }
});
</script>
@endpush

<style>
.bg-primary-lighten {
    background-color: rgba(114, 124, 245, 0.1);
}

.bg-success-lighten {
    background-color: rgba(10, 207, 151, 0.1);
}

.bg-info-lighten {
    background-color: rgba(57, 175, 209, 0.1);
}

.bg-warning-lighten {
    background-color: rgba(255, 188, 0, 0.1);
}

.avatar-sm {
    width: 48px;
    height: 48px;
    display: flex;
    align-items: center;
    justify-content: center;
}
</style>
@endsection