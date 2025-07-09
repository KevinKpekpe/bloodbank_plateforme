@extends('layouts.app')

@section('title', 'Rapport des Utilisateurs - BloodLink')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('superadmin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('reports.index') }}">Rapports</a></li>
                        <li class="breadcrumb-item active">Utilisateurs</li>
                    </ol>
                </div>
                <h4 class="page-title">
                    <i class="mdi mdi-account-group me-1"></i>
                    Rapport des Utilisateurs
                </h4>
            </div>
        </div>
    </div>

    <!-- Statistiques Principales -->
    <div class="row">
        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar-sm rounded">
                                <span class="avatar-title bg-primary-lighten text-primary rounded">
                                    <i class="mdi mdi-account-group font-20"></i>
                                </span>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="font-14 my-1">Total Utilisateurs</h5>
                            <h3 class="text-primary my-1">{{ $userStats['total'] }}</h3>
                            <p class="mb-0 text-muted">
                                <span class="text-success me-2">
                                    <i class="mdi mdi-arrow-up-bold"></i> Inscrits
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
                                    <i class="mdi mdi-heart-pulse font-20"></i>
                                </span>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="font-14 my-1">Donneurs</h5>
                            <h3 class="text-success my-1">{{ $userStats['donors'] }}</h3>
                            <p class="mb-0 text-muted">
                                <span class="text-info me-2">
                                    <i class="mdi mdi-heart"></i> Actifs
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
                                    <i class="mdi mdi-hospital-building font-20"></i>
                                </span>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="font-14 my-1">Admins Banque</h5>
                            <h3 class="text-info my-1">{{ $userStats['admins'] }}</h3>
                            <p class="mb-0 text-muted">
                                <span class="text-info me-2">
                                    <i class="mdi mdi-hospital"></i> Gestionnaires
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
                                    <i class="mdi mdi-shield-crown font-20"></i>
                                </span>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="font-14 my-1">Super Admins</h5>
                            <h3 class="text-warning my-1">{{ $userStats['superadmins'] }}</h3>
                            <p class="mb-0 text-muted">
                                <span class="text-success me-2">
                                    <i class="mdi mdi-shield"></i> Administrateurs
                                </span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Graphiques -->
    <div class="row">
        <div class="col-xl-8">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Évolution des Inscriptions (6 derniers mois)</h5>
                    <canvas id="monthlyChart" height="300"></canvas>
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Répartition par Rôle</h5>
                    <canvas id="roleChart" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Tableau des Statistiques Mensuelles -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Statistiques Mensuelles</h5>

                    <div class="table-responsive">
                        <table class="table table-centered table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Mois</th>
                                    <th>Total Inscriptions</th>
                                    <th>Donneurs</th>
                                    <th>Admins</th>
                                    <th>Taux de Croissance</th>
                                    <th>Activité</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($monthlyUsers as $stat)
                                <tr>
                                    <td>{{ $stat['month'] }}</td>
                                    <td>
                                        <span class="badge bg-primary">{{ $stat['total'] }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-success">{{ $stat['donors'] }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ $stat['admins'] }}</span>
                                    </td>
                                    <td>
                                        @php
                                            $growthRate = $stat['total'] > 0 ? round(($stat['total'] / max(array_column($monthlyUsers, 'total'))) * 100, 1) : 0;
                                        @endphp
                                        <span class="badge bg-{{ $growthRate >= 80 ? 'success' : ($growthRate >= 50 ? 'warning' : 'danger') }}">
                                            {{ $growthRate }}%
                                        </span>
                                    </td>
                                    <td>
                                        @php
                                            $activity = $stat['donors'] > 0 ? round(($stat['donors'] / $stat['total']) * 100, 1) : 0;
                                        @endphp
                                        <div class="d-flex align-items-center">
                                            <div class="progress flex-grow-1 me-2" style="height: 6px;">
                                                <div class="progress-bar bg-{{ $activity >= 70 ? 'success' : ($activity >= 50 ? 'warning' : 'danger') }}"
                                                     style="width: {{ min($activity, 100) }}%"></div>
                                            </div>
                                            <span class="text-muted small">{{ $activity }}%</span>
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

    <!-- Statistiques d'Activité -->
    <div class="row">
        <div class="col-xl-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Statut des Comptes</h5>
                    <canvas id="statusChart" height="200"></canvas>
                </div>
            </div>
        </div>

        <div class="col-xl-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Performance des Inscriptions</h5>
                    <canvas id="performanceChart" height="200"></canvas>
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
                            <a href="{{ route('reports.export') }}?type=users&format=pdf" class="btn btn-primary me-2">
                                <i class="mdi mdi-download me-1"></i>
                                Exporter en PDF
                            </a>
                            <a href="{{ route('reports.export') }}?type=users&format=excel" class="btn btn-success me-2">
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
// Graphique mensuel
const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
const monthlyChart = new Chart(monthlyCtx, {
    type: 'line',
    data: {
        labels: @json(array_column($monthlyUsers, 'month')),
        datasets: [{
            label: 'Total Inscriptions',
            data: @json(array_column($monthlyUsers, 'total')),
            borderColor: 'rgb(75, 192, 192)',
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            tension: 0.1
        }, {
            label: 'Donneurs',
            data: @json(array_column($monthlyUsers, 'donors')),
            borderColor: 'rgb(255, 99, 132)',
            backgroundColor: 'rgba(255, 99, 132, 0.2)',
            tension: 0.1
        }, {
            label: 'Admins',
            data: @json(array_column($monthlyUsers, 'admins')),
            borderColor: 'rgb(255, 159, 64)',
            backgroundColor: 'rgba(255, 159, 64, 0.2)',
            tension: 0.1
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

// Graphique des rôles
const roleCtx = document.getElementById('roleChart').getContext('2d');
const roleChart = new Chart(roleCtx, {
    type: 'doughnut',
    data: {
        labels: ['Donneurs', 'Admins Banque', 'Super Admins'],
        datasets: [{
            data: [
                {{ $userStats['donors'] }},
                {{ $userStats['admins'] }},
                {{ $userStats['superadmins'] }}
            ],
            backgroundColor: [
                'rgba(75, 192, 192, 0.8)',
                'rgba(54, 162, 235, 0.8)',
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

// Graphique du statut
const statusCtx = document.getElementById('statusChart').getContext('2d');
const statusChart = new Chart(statusCtx, {
    type: 'pie',
    data: {
        labels: ['Actifs', 'Inactifs'],
        datasets: [{
            data: [
                {{ $userStats['active'] }},
                {{ $userStats['inactive'] }}
            ],
            backgroundColor: [
                'rgba(75, 192, 192, 0.8)',
                'rgba(255, 99, 132, 0.8)'
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

// Graphique de performance
const performanceCtx = document.getElementById('performanceChart').getContext('2d');
const performanceChart = new Chart(performanceCtx, {
    type: 'bar',
    data: {
        labels: @json(array_column($monthlyUsers, 'month')),
        datasets: [{
            label: 'Taux de Donneurs (%)',
            data: @json(array_map(function($stat) {
                return $stat['total'] > 0 ? round(($stat['donors'] / $stat['total']) * 100, 1) : 0;
            }, $monthlyUsers)),
            backgroundColor: 'rgba(75, 192, 192, 0.8)',
            borderColor: 'rgb(75, 192, 192)',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true,
                max: 100
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
