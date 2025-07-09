@extends('layouts.superadmin')

@section('title', 'Rapport des Utilisateurs - BloodLink')
@section('description', 'Rapport détaillé des utilisateurs')
@section('page-title', 'Rapport des Utilisateurs')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- En-tête -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Rapport des Utilisateurs</h1>
            <p class="mt-2 text-gray-600">Statistiques détaillées des utilisateurs de la plateforme</p>
        </div>
        <a href="{{ route('reports.index') }}"
           class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700 flex items-center">
            <i class="fas fa-arrow-left mr-2"></i>
            Retour aux rapports
        </a>
    </div>

    <!-- Statistiques Principales -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Utilisateurs -->
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-users text-blue-600 text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-2xl font-bold text-gray-900">{{ $userStats['total'] }}</h3>
                        <p class="text-gray-600">Total Utilisateurs</p>
                        <p class="text-sm text-blue-600">Inscrits</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Donneurs -->
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-heart text-red-600 text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-2xl font-bold text-gray-900">{{ $userStats['donors'] }}</h3>
                        <p class="text-gray-600">Donneurs</p>
                        <p class="text-sm text-red-600">Actifs</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Admins Banque -->
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-hospital text-green-600 text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-2xl font-bold text-gray-900">{{ $userStats['admins'] }}</h3>
                        <p class="text-gray-600">Admins Banque</p>
                        <p class="text-sm text-green-600">Gestionnaires</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Super Admins -->
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-crown text-purple-600 text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-2xl font-bold text-gray-900">{{ $userStats['superadmins'] }}</h3>
                        <p class="text-gray-600">Super Admins</p>
                        <p class="text-sm text-purple-600">Administrateurs</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Graphiques -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
        <!-- Évolution des Inscriptions -->
        <div class="lg:col-span-2 bg-white shadow-md rounded-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-900">Évolution des Inscriptions (6 derniers mois)</h2>
            </div>
            <div class="p-6">
                <canvas id="monthlyChart" height="300"></canvas>
            </div>
        </div>

        <!-- Répartition par Rôle -->
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-900">Répartition par Rôle</h2>
            </div>
            <div class="p-6">
                <canvas id="roleChart" height="300"></canvas>
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
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Inscriptions</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Donneurs</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Admins</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Taux de Croissance</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Activité</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($monthlyUsers as $stat)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $stat['month'] }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                {{ $stat['total'] }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                {{ $stat['donors'] }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                {{ $stat['admins'] }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $growthRate = $stat['total'] > 0 ? round(($stat['total'] / max(array_column($monthlyUsers, 'total'))) * 100, 1) : 0;
                                $growthColor = $growthRate >= 80 ? 'green' : ($growthRate >= 50 ? 'yellow' : 'red');
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $growthColor }}-100 text-{{ $growthColor }}-800">
                                {{ $growthRate }}%
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $activity = $stat['donors'] > 0 ? round(($stat['donors'] / $stat['total']) * 100, 1) : 0;
                                $activityColor = $activity >= 70 ? 'green' : ($activity >= 50 ? 'yellow' : 'red');
                            @endphp
                            <div class="flex items-center">
                                <div class="flex-1 bg-gray-200 rounded-full h-2 mr-2">
                                    <div class="bg-{{ $activityColor }}-500 h-2 rounded-full" style="width: {{ min($activity, 100) }}%"></div>
                                </div>
                                <span class="text-sm text-gray-600">{{ $activity }}%</span>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Statistiques d'Activité -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Statut des Comptes -->
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-900">Statut des Comptes</h2>
            </div>
            <div class="p-6">
                <canvas id="statusChart" height="200"></canvas>
            </div>
        </div>

        <!-- Performance des Inscriptions -->
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-900">Performance des Inscriptions</h2>
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
        labels: @json(array_column($monthlyUsers, 'month')),
        datasets: [{
            label: 'Total Inscriptions',
            data: @json(array_column($monthlyUsers, 'total')),
            borderColor: 'rgb(59, 130, 246)',
            backgroundColor: 'rgba(59, 130, 246, 0.1)',
            tension: 0.1
        }, {
            label: 'Donneurs',
            data: @json(array_column($monthlyUsers, 'donors')),
            borderColor: 'rgb(239, 68, 68)',
            backgroundColor: 'rgba(239, 68, 68, 0.1)',
            tension: 0.1
        }, {
            label: 'Admins',
            data: @json(array_column($monthlyUsers, 'admins')),
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

// Graphique des rôles
const roleCtx = document.getElementById('roleChart').getContext('2d');
new Chart(roleCtx, {
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
                'rgb(239, 68, 68)',
                'rgb(34, 197, 94)',
                'rgb(147, 51, 234)'
            ]
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false
    }
});

// Graphique du statut
const statusCtx = document.getElementById('statusChart').getContext('2d');
new Chart(statusCtx, {
    type: 'pie',
    data: {
        labels: ['Actifs', 'Inactifs'],
        datasets: [{
            data: [
                {{ $userStats['active'] }},
                {{ $userStats['inactive'] }}
            ],
            backgroundColor: [
                'rgb(34, 197, 94)',
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
        labels: @json(array_column($monthlyUsers, 'month')),
        datasets: [{
            label: 'Taux de Donneurs (%)',
            data: @json(array_map(function($stat) {
                return $stat['total'] > 0 ? round(($stat['donors'] / $stat['total']) * 100, 1) : 0;
            }, $monthlyUsers)),
            backgroundColor: 'rgb(239, 68, 68)',
            borderColor: 'rgb(239, 68, 68)',
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
