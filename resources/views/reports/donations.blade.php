@extends('layouts.app')

@section('title', 'Rapport des Dons - BloodLink')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('superadmin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('reports.index') }}">Rapports</a></li>
                        <li class="breadcrumb-item active">Dons</li>
                    </ol>
                </div>
                <h4 class="page-title">
                    <i class="mdi mdi-heart-pulse me-1"></i>
                    Rapport des Dons de Sang
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
                                    <i class="mdi mdi-heart-pulse font-20"></i>
                                </span>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="font-14 my-1">Total Dons</h5>
                            <h3 class="text-primary my-1">{{ $donationStats['total'] }}</h3>
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

        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar-sm rounded">
                                <span class="avatar-title bg-success-lighten text-success rounded">
                                    <i class="mdi mdi-check-circle font-20"></i>
                                </span>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="font-14 my-1">Disponibles</h5>
                            <h3 class="text-success my-1">{{ $donationStats['available'] }}</h3>
                            <p class="mb-0 text-muted">
                                <span class="text-info me-2">
                                    <i class="mdi mdi-check"></i> En stock
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
                                    <i class="mdi mdi-hospital font-20"></i>
                                </span>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="font-14 my-1">Utilisés</h5>
                            <h3 class="text-info my-1">{{ $donationStats['used'] }}</h3>
                            <p class="mb-0 text-muted">
                                <span class="text-info me-2">
                                    <i class="mdi mdi-hospital"></i> Transfusés
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
                            <h3 class="text-warning my-1">{{ $donationStats['total_volume'] }}L</h3>
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
                    <h5 class="card-title">Évolution des Dons (6 derniers mois)</h5>
                    <canvas id="monthlyChart" height="300"></canvas>
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
                                    <th>Total Dons</th>
                                    <th>Volume (L)</th>
                                    <th>Disponibles</th>
                                    <th>Taux de Disponibilité</th>
                                    <th>Efficacité</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($monthlyDonations as $stat)
                                <tr>
                                    <td>{{ $stat['month'] }}</td>
                                    <td>
                                        <span class="badge bg-primary">{{ $stat['total'] }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ $stat['volume'] }}L</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-success">{{ $stat['available'] }}</span>
                                    </td>
                                    <td>
                                        @php
                                            $availabilityRate = $stat['total'] > 0 ? round(($stat['available'] / $stat['total']) * 100, 1) : 0;
                                        @endphp
                                        <span class="badge bg-{{ $availabilityRate >= 60 ? 'success' : ($availabilityRate >= 40 ? 'warning' : 'danger') }}">
                                            {{ $availabilityRate }}%
                                        </span>
                                    </td>
                                    <td>
                                        @php
                                            $efficiency = $stat['total'] > 0 ? round(($stat['available'] / $stat['total']) * 100, 1) : 0;
                                        @endphp
                                        <div class="d-flex align-items-center">
                                            <div class="progress flex-grow-1 me-2" style="height: 6px;">
                                                <div class="progress-bar bg-{{ $efficiency >= 60 ? 'success' : ($efficiency >= 40 ? 'warning' : 'danger') }}"
                                                     style="width: {{ min($efficiency, 100) }}%"></div>
                                            </div>
                                            <span class="text-muted small">{{ $efficiency }}%</span>
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
                    <h5 class="card-title">Volume de Sang Collecté par Mois</h5>
                    <canvas id="volumeChart" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Graphique de Performance -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Performance des Dons</h5>
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
                            <a href="{{ route('reports.export') }}?type=donations&format=pdf" class="btn btn-primary me-2">
                                <i class="mdi mdi-download me-1"></i>
                                Exporter en PDF
                            </a>
                            <a href="{{ route('reports.export') }}?type=donations&format=excel" class="btn btn-success me-2">
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
        labels: @json(array_column($monthlyDonations, 'month')),
        datasets: [{
            label: 'Total Dons',
            data: @json(array_column($monthlyDonations, 'total')),
            borderColor: 'rgb(75, 192, 192)',
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            tension: 0.1
        }, {
            label: 'Disponibles',
            data: @json(array_column($monthlyDonations, 'available')),
            borderColor: 'rgb(255, 99, 132)',
            backgroundColor: 'rgba(255, 99, 132, 0.2)',
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

// Graphique du statut
const statusCtx = document.getElementById('statusChart').getContext('2d');
const statusChart = new Chart(statusCtx, {
    type: 'doughnut',
    data: {
        labels: ['Collectés', 'Traités', 'Disponibles', 'Utilisés', 'Expirés'],
        datasets: [{
            data: [
                {{ $donationStats['collected'] }},
                {{ $donationStats['processed'] }},
                {{ $donationStats['available'] }},
                {{ $donationStats['used'] }},
                {{ $donationStats['expired'] }}
            ],
            backgroundColor: [
                'rgba(255, 205, 86, 0.8)',
                'rgba(54, 162, 235, 0.8)',
                'rgba(75, 192, 192, 0.8)',
                'rgba(255, 99, 132, 0.8)',
                'rgba(255, 159, 64, 0.8)'
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
    type: 'bar',
    data: {
        labels: @json(array_column($monthlyDonations, 'month')),
        datasets: [{
            label: 'Volume (L)',
            data: @json(array_column($monthlyDonations, 'volume')),
            backgroundColor: 'rgba(255, 159, 64, 0.8)',
            borderColor: 'rgb(255, 159, 64)',
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

// Graphique de performance
const performanceCtx = document.getElementById('performanceChart').getContext('2d');
const performanceChart = new Chart(performanceCtx, {
    type: 'bar',
    data: {
        labels: @json(array_column($monthlyDonations, 'month')),
        datasets: [{
            label: 'Taux de Disponibilité (%)',
            data: @json(array_map(function($stat) {
                return $stat['total'] > 0 ? round(($stat['available'] / $stat['total']) * 100, 1) : 0;
            }, $monthlyDonations)),
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