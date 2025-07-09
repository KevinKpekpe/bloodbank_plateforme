@extends('layouts.superadmin')

@section('title', 'Dashboard Super Admin - BloodLink')
@section('description', 'Tableau de bord super administrateur')
@section('page-title', 'Dashboard')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Statistiques globales -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white p-6 rounded-lg shadow-md">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-hospital text-red-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900">Banques de Sang</h3>
                    <p class="text-2xl font-bold text-red-600">{{ \App\Models\Bank::count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-md">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-users text-blue-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900">Utilisateurs</h3>
                    <p class="text-2xl font-bold text-blue-600">{{ \App\Models\User::count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-md">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-calendar-check text-green-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900">Rendez-vous</h3>
                    <p class="text-2xl font-bold text-green-600">{{ \App\Models\Appointment::count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-md">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-tint text-purple-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900">Dons</h3>
                    <p class="text-2xl font-bold text-purple-600">{{ \App\Models\Donation::count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Banques les plus actives -->
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Banques les Plus Actives</h2>
            <div class="space-y-4">
                @foreach(\App\Models\Bank::withCount(['appointments', 'donations'])->orderBy('appointments_count', 'desc')->limit(5)->get() as $bank)
                    <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                        <div>
                            <h3 class="font-medium text-gray-900">{{ $bank->name }}</h3>
                            <p class="text-sm text-gray-500">{{ $bank->address }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-medium text-gray-900">{{ $bank->appointments_count }} RDV</p>
                            <p class="text-sm text-gray-500">{{ $bank->donations_count }} dons</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Statistiques par rôle -->
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Utilisateurs par Rôle</h2>
            <div class="space-y-4">
                <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                    <div class="flex items-center">
                        <i class="fas fa-user text-blue-600 mr-3"></i>
                        <span class="font-medium">Donneurs</span>
                    </div>
                    <span class="text-lg font-bold text-blue-600">{{ \App\Models\User::where('role', 'donor')->count() }}</span>
                </div>

                <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                    <div class="flex items-center">
                        <i class="fas fa-user-shield text-green-600 mr-3"></i>
                        <span class="font-medium">Administrateurs</span>
                    </div>
                    <span class="text-lg font-bold text-green-600">{{ \App\Models\User::where('role', 'admin')->count() }}</span>
                </div>

                <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                    <div class="flex items-center">
                        <i class="fas fa-crown text-red-600 mr-3"></i>
                        <span class="font-medium">Super Admins</span>
                    </div>
                    <span class="text-lg font-bold text-red-600">{{ \App\Models\User::where('role', 'superadmin')->count() }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions rapides -->
    <div class="mt-8 bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Actions Rapides</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <a href="{{ route('superadmin.banks.create') }}"
               class="flex items-center p-4 bg-red-50 border border-red-200 rounded-lg hover:bg-red-100 transition-colors">
                <i class="fas fa-plus text-red-600 mr-3"></i>
                <div>
                    <h3 class="font-medium text-red-900">Ajouter une Banque</h3>
                    <p class="text-sm text-red-600">Créer une nouvelle banque de sang</p>
                </div>
            </a>

            <a href="{{ route('superadmin.users.create') }}"
               class="flex items-center p-4 bg-blue-50 border border-blue-200 rounded-lg hover:bg-blue-100 transition-colors">
                <i class="fas fa-user-plus text-blue-600 mr-3"></i>
                <div>
                    <h3 class="font-medium text-blue-900">Ajouter un Utilisateur</h3>
                    <p class="text-sm text-blue-600">Créer un nouveau compte</p>
                </div>
            </a>

            <a href="{{ route('superadmin.banks.index') }}"
               class="flex items-center p-4 bg-green-50 border border-green-200 rounded-lg hover:bg-green-100 transition-colors">
                <i class="fas fa-chart-bar text-green-600 mr-3"></i>
                <div>
                    <h3 class="font-medium text-green-900">Voir les Statistiques</h3>
                    <p class="text-sm text-green-600">Analyser les performances</p>
                </div>
            </a>
        </div>
    </div>
</div>
@endsection
