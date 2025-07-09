@extends('layouts.superadmin')

@section('title', 'Rapports - BloodLink')
@section('description', 'Rapports et statistiques du système BloodLink')
@section('page-title', 'Rapports')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- En-tête -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Rapports et Statistiques</h1>
        <p class="mt-2 text-gray-600">Consultez les rapports détaillés de votre système de gestion des banques de sang</p>
    </div>

    <!-- Cartes des rapports -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        <!-- Rapport Général -->
        <div class="bg-white shadow-md rounded-lg overflow-hidden hover:shadow-lg transition-shadow">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-chart-pie text-red-600 text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900">
                            <a href="{{ route('reports.general') }}" class="hover:text-red-600 transition-colors">
                                Rapport Général
                            </a>
                        </h3>
                        <p class="text-gray-600 text-sm">Vue d'ensemble du système</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Rapport des Banques -->
        <div class="bg-white shadow-md rounded-lg overflow-hidden hover:shadow-lg transition-shadow">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-hospital text-blue-600 text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900">
                            <a href="{{ route('reports.banks') }}" class="hover:text-blue-600 transition-colors">
                                Banques de Sang
                            </a>
                        </h3>
                        <p class="text-gray-600 text-sm">Performance des banques</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Rapport des Groupes Sanguins -->
        <div class="bg-white shadow-md rounded-lg overflow-hidden hover:shadow-lg transition-shadow">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-tint text-green-600 text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900">
                            <a href="{{ route('reports.blood-types') }}" class="hover:text-green-600 transition-colors">
                                Groupes Sanguins
                            </a>
                        </h3>
                        <p class="text-gray-600 text-sm">Disponibilité des stocks</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Rapport des Rendez-vous -->
        <div class="bg-white shadow-md rounded-lg overflow-hidden hover:shadow-lg transition-shadow">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-calendar-check text-yellow-600 text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900">
                            <a href="{{ route('reports.appointments') }}" class="hover:text-yellow-600 transition-colors">
                                Rendez-vous
                            </a>
                        </h3>
                        <p class="text-gray-600 text-sm">Planification et suivi</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Rapport des Dons -->
        <div class="bg-white shadow-md rounded-lg overflow-hidden hover:shadow-lg transition-shadow">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-heart text-purple-600 text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900">
                            <a href="{{ route('reports.donations') }}" class="hover:text-purple-600 transition-colors">
                                Dons de Sang
                            </a>
                        </h3>
                        <p class="text-gray-600 text-sm">Collecte et statistiques</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Rapport des Utilisateurs -->
        <div class="bg-white shadow-md rounded-lg overflow-hidden hover:shadow-lg transition-shadow">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-users text-indigo-600 text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900">
                            <a href="{{ route('reports.users') }}" class="hover:text-indigo-600 transition-colors">
                                Utilisateurs
                            </a>
                        </h3>
                        <p class="text-gray-600 text-sm">Activité des utilisateurs</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques Rapides -->
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-900">Statistiques Rapides</h2>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Total des banques -->
                <div class="text-center">
                    <div class="text-3xl font-bold text-red-600">{{ \App\Models\Bank::count() }}</div>
                    <div class="text-gray-600">Banques de sang</div>
                </div>

                <!-- Total des utilisateurs -->
                <div class="text-center">
                    <div class="text-3xl font-bold text-blue-600">{{ \App\Models\User::count() }}</div>
                    <div class="text-gray-600">Utilisateurs</div>
                </div>

                <!-- Total des dons -->
                <div class="text-center">
                    <div class="text-3xl font-bold text-green-600">{{ \App\Models\Donation::count() }}</div>
                    <div class="text-gray-600">Dons effectués</div>
                </div>

                <!-- Total des rendez-vous -->
                <div class="text-center">
                    <div class="text-3xl font-bold text-yellow-600">{{ \App\Models\Appointment::count() }}</div>
                    <div class="text-gray-600">Rendez-vous</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
