@extends('layouts.app')

@section('title', 'Rapport Général - BloodLink')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('superadmin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('reports.index') }}">Rapports</a></li>
                        <li class="breadcrumb-item active">Général</li>
                    </ol>
                </div>
                <h4 class="page-title">
                    <i class="mdi mdi-chart-donut me-1"></i>
                    Rapport Général de la Plateforme
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
                                    <i class="mdi mdi-hospital-building font-20"></i>
                                </span>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="font-14 my-1">Banques de Sang</h5>
                            <h3 class="text-primary my-1">{{ $stats['total_banks'] }}</h3>
                            <p class="mb-0 text-muted">
                                <span class="text-success me-2">
                                    <i class="mdi mdi-arrow-up-bold"></i> {{ $stats['active_banks'] }} actives
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
                            <h5 class="font-14 my-1">Utilisateurs</h5>
                            <h3 class="text-success my-1">{{ $stats['total_users'] }}</h3>
                            <p class="mb-0 text-muted">
                                <span class="text-info me-2">
                                    <i class="mdi mdi-arrow-up-bold"></i> {{ $stats['total_donors'] }} donneurs
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
                            <h5 class="font-14 my-1">Rendez-vous</h5>
                            <h3 class="text-info my-1">{{ $stats['total_appointments'] }}</h3>
                            <p class="mb-0 text-muted">
                                <span class="text-warning me-2">
                                    <i class="mdi mdi-clock"></i> Total
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
                            <h5 class="font-14 my-1">Dons de Sang</h5>
                            <h3 class="text-warning my-1">{{ $stats['total_donations'] }}</h3>
                            <p class="mb-0 text-muted">
                                <span class="text-success me-2">
                                    <i class="mdi mdi-arrow-up-bold"></i> {{ $stats['available_donations'] }} disponibles
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
                    <h5 class="card-title">Évolution des Rendez-vous et Dons (6 derniers mois)</h5>
                    <canvas id="monthlyChart" height="300"></canvas>
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Statut des Dons</h5>
                    <canvas id="donationsChart" height="300"></canvas>
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
                                    <th>Rendez-vous</th>
                                    <th>Dons</th>
                                    <th>Taux de Conversion</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($monthlyStats as $stat)
                                <tr>
                                    <td>{{ $stat['month'] }}</td>
                                    <td>
                                        <span class="badge bg-info">{{ $stat['appointments'] }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-success">{{ $stat['donations'] }}</span>
                                    </td>
                                    <td>
                                        @php
                                            $rate = $stat['appointments'] > 0 ? round(($stat['donations'] / $stat['appointments']) * 100, 1) : 0;
                                        @endphp
                                        <span class="badge bg-{{ $rate >= 50 ? 'success' : ($rate >= 25 ? 'warning' : 'danger') }}">
                                            {{ $rate }}%
                                        </span>
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
                            <a href="{{ route('reports.export') }}" class="btn btn-primary me-2">
                                <i class="mdi mdi-download me-1"></i>
                                Exporter en PDF
                            </a>
                            <a href="{{ route('reports.export') }}" class="btn btn-success me-2">
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
        labels: @json(array_column($monthlyStats, 'month')),
        datasets: [{
            label: 'Rendez-vous',
            data: @json(array_column($monthlyStats, 'appointments')),
            borderColor: 'rgb(75, 192, 192)',
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            tension: 0.1
        }, {
            label: 'Dons',
            data: @json(array_column($monthlyStats, 'donations')),
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

// Graphique des dons
const donationsCtx = document.getElementById('donationsChart').getContext('2d');
const donationsChart = new Chart(donationsCtx, {
    type: 'doughnut',
    data: {
        labels: ['Disponibles', 'Utilisés', 'Expirés'],
        datasets: [{
            data: [
                {{ $stats['available_donations'] }},
                {{ $stats['used_donations'] }},
                {{ $stats['expired_donations'] }}
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