@extends('layouts.app')

@section('title', 'Rapport des Groupes Sanguins - BloodLink')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('superadmin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('reports.index') }}">Rapports</a></li>
                        <li class="breadcrumb-item active">Groupes Sanguins</li>
                    </ol>
                </div>
                <h4 class="page-title">
                    <i class="mdi mdi-blood-bag me-1"></i>
                    Rapport des Groupes Sanguins
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
                                    <i class="mdi mdi-blood-bag font-20"></i>
                                </span>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="font-14 my-1">Total Groupes</h5>
                            <h3 class="text-primary my-1">{{ $bloodTypes->count() }}</h3>
                            <p class="mb-0 text-muted">
                                <span class="text-success me-2">
                                    <i class="mdi mdi-arrow-up-bold"></i> Types
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
                            <h5 class="font-14 my-1">Total Dons</h5>
                            <h3 class="text-success my-1">{{ $bloodTypes->sum('donations_count') }}</h3>
                            <p class="mb-0 text-muted">
                                <span class="text-info me-2">
                                    <i class="mdi mdi-arrow-up-bold"></i> Collectés
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
                                    <i class="mdi mdi-check-circle font-20"></i>
                                </span>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="font-14 my-1">Dons Disponibles</h5>
                            <h3 class="text-info my-1">{{ $bloodTypeStats->sum('available_donations') }}</h3>
                            <p class="mb-0 text-muted">
                                <span class="text-warning me-2">
                                    <i class="mdi mdi-clock"></i> En stock
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
                                    <i class="mdi mdi-dropbox font-20"></i>
                                </span>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="font-14 my-1">Volume Total</h5>
                            <h3 class="text-warning my-1">{{ $bloodTypeStats->sum('total_volume') }}L</h3>
                            <p class="mb-0 text-muted">
                                <span class="text-success me-2">
                                    <i class="mdi mdi-arrow-up-bold"></i> Collecté
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
                    <h5 class="card-title">Répartition des Dons par Groupe Sanguin</h5>
                    <canvas id="bloodTypesChart" height="300"></canvas>
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Statut des Dons</h5>
                    <canvas id="statusChart" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Tableau des Groupes Sanguins -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Détails par Groupe Sanguin</h5>

                    <div class="table-responsive">
                        <table class="table table-centered table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Groupe Sanguin</th>
                                    <th>Total Dons</th>
                                    <th>Disponibles</th>
                                    <th>Utilisés</th>
                                    <th>Expirés</th>
                                    <th>Volume (L)</th>
                                    <th>Disponibilité</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($bloodTypeStats as $stat)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm me-3">
                                                <span class="avatar-title bg-danger-lighten text-danger rounded">
                                                    <i class="mdi mdi-blood-bag"></i>
                                                </span>
                                            </div>
                                            <div>
                                                <h6 class="mb-0">{{ $stat['name'] }}</h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-primary">{{ $stat['total_donations'] }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-success">{{ $stat['available_donations'] }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ $stat['used_donations'] }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-warning">{{ $stat['expired_donations'] }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary">{{ $stat['total_volume'] }}L</span>
                                    </td>
                                    <td>
                                        @php
                                            $availability = $stat['total_donations'] > 0 ?
                                                round(($stat['available_donations'] / $stat['total_donations']) * 100, 1) : 0;
                                        @endphp
                                        <div class="d-flex align-items-center">
                                            <div class="progress flex-grow-1 me-2" style="height: 6px;">
                                                <div class="progress-bar bg-{{ $availability >= 50 ? 'success' : ($availability >= 25 ? 'warning' : 'danger') }}"
                                                     style="width: {{ min($availability, 100) }}%"></div>
                                            </div>
                                            <span class="text-muted small">{{ $availability }}%</span>
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

    <!-- Graphique de Volume -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Volume de Sang par Groupe Sanguin</h5>
                    <canvas id="volumeChart" height="200"></canvas>
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
                            <a href="{{ route('reports.export') }}?type=blood-types&format=pdf" class="btn btn-primary me-2">
                                <i class="mdi mdi-download me-1"></i>
                                Exporter en PDF
                            </a>
                            <a href="{{ route('reports.export') }}?type=blood-types&format=excel" class="btn btn-success me-2">
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
// Graphique des groupes sanguins
const bloodTypesCtx = document.getElementById('bloodTypesChart').getContext('2d');
const bloodTypesChart = new Chart(bloodTypesCtx, {
    type: 'bar',
    data: {
        labels: @json(array_column($bloodTypeStats, 'name')),
        datasets: [{
            label: 'Total Dons',
            data: @json(array_column($bloodTypeStats, 'total_donations')),
            backgroundColor: 'rgba(75, 192, 192, 0.8)',
            borderColor: 'rgb(75, 192, 192)',
            borderWidth: 1
        }, {
            label: 'Disponibles',
            data: @json(array_column($bloodTypeStats, 'available_donations')),
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

// Graphique du statut
const statusCtx = document.getElementById('statusChart').getContext('2d');
const statusChart = new Chart(statusCtx, {
    type: 'doughnut',
    data: {
        labels: ['Disponibles', 'Utilisés', 'Expirés'],
        datasets: [{
            data: [
                {{ $bloodTypeStats->sum('available_donations') }},
                {{ $bloodTypeStats->sum('used_donations') }},
                {{ $bloodTypeStats->sum('expired_donations') }}
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

// Graphique du volume
const volumeCtx = document.getElementById('volumeChart').getContext('2d');
const volumeChart = new Chart(volumeCtx, {
    type: 'line',
    data: {
        labels: @json(array_column($bloodTypeStats, 'name')),
        datasets: [{
            label: 'Volume (L)',
            data: @json(array_column($bloodTypeStats, 'total_volume')),
            borderColor: 'rgb(255, 159, 64)',
            backgroundColor: 'rgba(255, 159, 64, 0.2)',
            tension: 0.1,
            fill: true
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

.bg-danger-lighten {
    background-color: rgba(250, 92, 124, 0.1);
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
