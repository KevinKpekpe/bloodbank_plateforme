@extends('layouts.superadmin')

@section('title', 'Rapport des Rendez-vous - BloodLink')
@section('description', 'Rapport détaillé des rendez-vous')
@section('page-title', 'Rapport des Rendez-vous')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- En-tête -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Rapport des Rendez-vous</h1>
            <p class="mt-2 text-gray-600">Statistiques détaillées des rendez-vous de don</p>
        </div>
        <a href="{{ route('reports.index') }}"
           class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700 flex items-center">
            <i class="fas fa-arrow-left mr-2"></i>
            Retour aux rapports
        </a>
    </div>

    <!-- Statistiques Principales -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Rendez-vous -->
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-calendar-check text-blue-600 text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-2xl font-bold text-gray-900">{{ $appointmentStats['total'] }}</h3>
                        <p class="text-gray-600">Total Rendez-vous</p>
                        <p class="text-sm text-blue-600">Tous</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- En Attente -->
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-clock text-yellow-600 text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-2xl font-bold text-gray-900">{{ $appointmentStats['pending'] }}</h3>
                        <p class="text-gray-600">En Attente</p>
                        <p class="text-sm text-yellow-600">À confirmer</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Confirmés -->
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-check-circle text-green-600 text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-2xl font-bold text-gray-900">{{ $appointmentStats['confirmed'] }}</h3>
                        <p class="text-gray-600">Confirmés</p>
                        <p class="text-sm text-green-600">Validés</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Terminés -->
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-calendar-check text-purple-600 text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-2xl font-bold text-gray-900">{{ $appointmentStats['completed'] }}</h3>
                        <p class="text-gray-600">Terminés</p>
                        <p class="text-sm text-purple-600">Réalisés</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Graphiques -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
        <!-- Évolution des Rendez-vous -->
        <div class="lg:col-span-2 bg-white shadow-md rounded-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-900">Évolution des Rendez-vous (6 derniers mois)</h2>
            </div>
            <div class="p-6">
                <canvas id="monthlyChart" height="300"></canvas>
            </div>
        </div>

        <!-- Statut des Rendez-vous -->
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-900">Statut des Rendez-vous</h2>
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
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Confirmés</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Terminés</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Taux de Confirmation</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Taux de Réalisation</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($monthlyAppointments as $stat)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $stat['month'] }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                {{ $stat['total'] }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                {{ $stat['confirmed'] }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                {{ $stat['completed'] }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $confirmationRate = $stat['total'] > 0 ? round(($stat['confirmed'] / $stat['total']) * 100, 1) : 0;
                                $confirmationColor = $confirmationRate >= 70 ? 'green' : ($confirmationRate >= 50 ? 'yellow' : 'red');
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $confirmationColor }}-100 text-{{ $confirmationColor }}-800">
                                {{ $confirmationRate }}%
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $completionRate = $stat['total'] > 0 ? round(($stat['completed'] / $stat['total']) * 100, 1) : 0;
                                $completionColor = $completionRate >= 60 ? 'green' : ($completionRate >= 40 ? 'yellow' : 'red');
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $completionColor }}-100 text-{{ $completionColor }}-800">
                                {{ $completionRate }}%
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Graphique de Performance -->
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-900">Performance des Rendez-vous</h2>
        </div>
        <div class="p-6">
            <canvas id="performanceChart" height="200"></canvas>
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
        labels: @json(array_column($monthlyAppointments, 'month')),
        datasets: [{
            label: 'Total',
            data: @json(array_column($monthlyAppointments, 'total')),
            borderColor: 'rgb(59, 130, 246)',
            backgroundColor: 'rgba(59, 130, 246, 0.1)',
            tension: 0.1
        }, {
            label: 'Confirmés',
            data: @json(array_column($monthlyAppointments, 'confirmed')),
            borderColor: 'rgb(34, 197, 94)',
            backgroundColor: 'rgba(34, 197, 94, 0.1)',
            tension: 0.1
        }, {
            label: 'Terminés',
            data: @json(array_column($monthlyAppointments, 'completed')),
            borderColor: 'rgb(147, 51, 234)',
            backgroundColor: 'rgba(147, 51, 234, 0.1)',
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
        labels: ['En Attente', 'Confirmés', 'Terminés', 'Annulés'],
        datasets: [{
            data: [
                {{ $appointmentStats['pending'] }},
                {{ $appointmentStats['confirmed'] }},
                {{ $appointmentStats['completed'] }},
                {{ $appointmentStats['cancelled'] }}
            ],
            backgroundColor: [
                'rgb(245, 158, 11)',
                'rgb(34, 197, 94)',
                'rgb(147, 51, 234)',
                'rgb(239, 68, 68)'
            ]
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false
    }
});

// Graphique de performance
const performanceCtx = document.getElementById('performanceChart').getContext('2d');
new Chart(performanceCtx, {
    type: 'bar',
    data: {
        labels: @json(array_column($monthlyAppointments, 'month')),
        datasets: [{
            label: 'Taux de Confirmation (%)',
            data: @json(array_map(function($stat) {
                return $stat['total'] > 0 ? round(($stat['confirmed'] / $stat['total']) * 100, 1) : 0;
            }, $monthlyAppointments)),
            backgroundColor: 'rgb(34, 197, 94)',
            borderColor: 'rgb(34, 197, 94)',
            borderWidth: 1
        }, {
            label: 'Taux de Réalisation (%)',
            data: @json(array_map(function($stat) {
                return $stat['total'] > 0 ? round(($stat['completed'] / $stat['total']) * 100, 1) : 0;
            }, $monthlyAppointments)),
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
                beginAtZero: true,
                max: 100
            }
        }
    }
});
</script>
@endsection
