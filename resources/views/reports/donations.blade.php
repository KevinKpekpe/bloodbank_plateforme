@extends('layouts.superadmin')

@section('title', 'Rapport des Dons - BloodLink')
@section('description', 'Rapport détaillé des dons de sang')
@section('page-title', 'Rapport des Dons')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- En-tête -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Rapport des Dons de Sang</h1>
            <p class="mt-2 text-gray-600">Statistiques détaillées de la collecte de sang</p>
        </div>
        <a href="{{ route('reports.index') }}"
           class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700 flex items-center">
            <i class="fas fa-arrow-left mr-2"></i>
            Retour aux rapports
        </a>
    </div>

    <!-- Statistiques Principales -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Dons -->
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-heart text-red-600 text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-2xl font-bold text-gray-900">{{ $donationStats['total'] }}</h3>
                        <p class="text-gray-600">Total Dons</p>
                        <p class="text-sm text-green-600">Collectés</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Disponibles -->
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-check-circle text-green-600 text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-2xl font-bold text-gray-900">{{ $donationStats['available'] }}</h3>
                        <p class="text-gray-600">Disponibles</p>
                        <p class="text-sm text-green-600">En stock</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Utilisés -->
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-hospital text-blue-600 text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-2xl font-bold text-gray-900">{{ $donationStats['used'] }}</h3>
                        <p class="text-gray-600">Utilisés</p>
                        <p class="text-sm text-blue-600">Transfusés</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Volume Total -->
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-tint text-purple-600 text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-2xl font-bold text-gray-900">{{ $donationStats['total_volume'] }}L</h3>
                        <p class="text-gray-600">Volume Total</p>
                        <p class="text-sm text-purple-600">Collecté</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Graphiques -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
        <!-- Évolution des Dons -->
        <div class="lg:col-span-2 bg-white shadow-md rounded-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-900">Évolution des Dons (6 derniers mois)</h2>
            </div>
            <div class="p-6">
                <canvas id="monthlyChart" height="300"></canvas>
            </div>
        </div>

        <!-- Statut des Dons -->
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-900">Statut des Dons</h2>
            </div>
            <div class="p-6">
                <canvas id="statusChart" height="300"></canvas>
            </div>
        </div>
    </div>

    <!-- Tableau des Statistiques Mensuelles -->
    <div class="bg-white shadow-md rounded-lg overflow-hidden mb-8">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-900">Statistiques Mensuelles</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mois</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Dons</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Volume (L)</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Disponibles</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Taux de Disponibilité</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Efficacité</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($monthlyDonations as $stat)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $stat['month'] }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                {{ $stat['total'] }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                {{ $stat['volume'] }}L
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                {{ $stat['available'] }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $availabilityRate = $stat['total'] > 0 ? round(($stat['available'] / $stat['total']) * 100, 1) : 0;
                                $availabilityColor = $availabilityRate >= 60 ? 'green' : ($availabilityRate >= 40 ? 'yellow' : 'red');
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $availabilityColor }}-100 text-{{ $availabilityColor }}-800">
                                {{ $availabilityRate }}%
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $efficiency = $stat['total'] > 0 ? round(($stat['available'] / $stat['total']) * 100, 1) : 0;
                                $efficiencyColor = $efficiency >= 60 ? 'green' : ($efficiency >= 40 ? 'yellow' : 'red');
                            @endphp
                            <div class="flex items-center">
                                <div class="flex-1 bg-gray-200 rounded-full h-2 mr-2">
                                    <div class="bg-{{ $efficiencyColor }}-500 h-2 rounded-full" style="width: {{ min($efficiency, 100) }}%"></div>
                                </div>
                                <span class="text-sm text-gray-600">{{ $efficiency }}%</span>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Graphiques supplémentaires -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Volume de Sang Collecté -->
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-900">Volume de Sang Collecté par Mois</h2>
            </div>
            <div class="p-6">
                <canvas id="volumeChart" height="200"></canvas>
            </div>
        </div>

        <!-- Performance des Dons -->
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-900">Performance des Dons</h2>
            </div>
            <div class="p-6">
                <canvas id="performanceChart" height="200"></canvas>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Graphique mensuel
const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
new Chart(monthlyCtx, {
    type: 'line',
    data: {
        labels: @json(array_column($monthlyDonations, 'month')),
        datasets: [{
            label: 'Total Dons',
            data: @json(array_column($monthlyDonations, 'total')),
            borderColor: 'rgb(239, 68, 68)',
            backgroundColor: 'rgba(239, 68, 68, 0.1)',
            tension: 0.1
        }, {
            label: 'Disponibles',
            data: @json(array_column($monthlyDonations, 'available')),
            borderColor: 'rgb(34, 197, 94)',
            backgroundColor: 'rgba(34, 197, 94, 0.1)',
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
new Chart(statusCtx, {
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
                'rgb(245, 158, 11)',
                'rgb(59, 130, 246)',
                'rgb(34, 197, 94)',
                'rgb(239, 68, 68)',
                'rgb(156, 163, 175)'
            ]
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false
    }
});

// Graphique du volume
const volumeCtx = document.getElementById('volumeChart').getContext('2d');
new Chart(volumeCtx, {
    type: 'bar',
    data: {
        labels: @json(array_column($monthlyDonations, 'month')),
        datasets: [{
            label: 'Volume (L)',
            data: @json(array_column($monthlyDonations, 'volume')),
            backgroundColor: 'rgb(147, 51, 234)',
            borderColor: 'rgb(147, 51, 234)',
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
new Chart(performanceCtx, {
    type: 'bar',
    data: {
        labels: @json(array_column($monthlyDonations, 'month')),
        datasets: [{
            label: 'Taux de Disponibilité (%)',
            data: @json(array_map(function($stat) {
                return $stat['total'] > 0 ? round(($stat['available'] / $stat['total']) * 100, 1) : 0;
            }, $monthlyDonations)),
            backgroundColor: 'rgb(34, 197, 94)',
            borderColor: 'rgb(34, 197, 94)',
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
@endsection
