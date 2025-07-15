@extends('layouts.admin')

@section('title', 'Dashboard Admin - BloodLink')
@section('description', 'Tableau de bord administrateur')
@section('page-title', 'Dashboard Administrateur')

@section('content')
<div class="mb-8">
    <p class="text-gray-600">Bienvenue, {{ Auth::user()->name }} !</p>
</div>

@php
    $bank = Auth::user()->managedBank;
@endphp

@if($bank)
    @php
        // Utiliser le StockHelper pour obtenir les statistiques des poches
        $statistics = \App\Helpers\StockHelper::getDashboardStatistics($bank);
    @endphp

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total des poches -->
        <div class="bg-white p-6 rounded-lg shadow-md">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900">Total Poches</h3>
                    <p class="text-2xl font-bold text-blue-600">{{ $statistics['total_bags'] }}</p>
                    <p class="text-sm text-gray-500">{{ number_format($statistics['total_volume_l'], 1) }}L</p>
                </div>
            </div>
        </div>

        <!-- Poches disponibles -->
        <div class="bg-white p-6 rounded-lg shadow-md">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900">Poches Disponibles</h3>
                    <p class="text-2xl font-bold text-green-600">{{ $statistics['available_bags'] }}</p>
                    <p class="text-sm text-gray-500">{{ number_format($statistics['available_volume_l'], 1) }}L</p>
                </div>
            </div>
        </div>

        <!-- Poches réservées -->
        <div class="bg-white p-6 rounded-lg shadow-md">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a4 4 0 118 0v4m-4 6v6m-4-6h8m-8 6h8" />
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900">Poches Réservées</h3>
                    <p class="text-2xl font-bold text-yellow-600">{{ $statistics['reserved_bags'] }}</p>
                </div>
            </div>
        </div>

        <!-- Stocks critiques -->
        <div class="bg-white p-6 rounded-lg shadow-md">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900">Stocks Critiques</h3>
                    <p class="text-2xl font-bold text-red-600">{{ $statistics['critical_stocks'] }}</p>
                    <p class="text-sm text-gray-500">{{ $statistics['low_stocks'] }} faibles</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Alertes et informations importantes -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Poches expirant bientôt -->
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Poches Expirant Bientôt</h3>
            @if($statistics['expiring_soon_bags'] > 0)
                <div class="flex items-center">
                    <div class="w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-lg font-semibold text-orange-600">{{ $statistics['expiring_soon_bags'] }} poches</p>
                        <p class="text-sm text-gray-500">Expirent dans les 7 prochains jours</p>
                    </div>
                </div>
                <div class="mt-4">
                    <a href="{{ route('admin.blood-bags.expiring-soon') }}" class="text-sm text-blue-600 hover:text-blue-800">
                        Voir les détails →
                    </a>
                </div>
            @else
                <p class="text-gray-500">Aucune poche n'expire dans les 7 prochains jours</p>
            @endif
        </div>

        <!-- Actions rapides -->
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Actions Rapides</h3>
            <div class="space-y-3">
                <a href="{{ route('admin.blood-bags.index') }}" class="flex items-center text-blue-600 hover:text-blue-800">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                    Gérer les poches de sang
                </a>
                <a href="{{ route('admin.blood-bags.reservations') }}" class="flex items-center text-blue-600 hover:text-blue-800">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a4 4 0 118 0v4m-4 6v6m-4-6h8m-8 6h8" />
                    </svg>
                    Voir les réservations
                </a>
                <a href="{{ route('admin.blood-bags.statistics') }}" class="flex items-center text-blue-600 hover:text-blue-800">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    Statistiques détaillées
                </a>
            </div>
        </div>
    </div>

    <!-- Informations de la banque -->
    <div class="bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-xl font-semibold text-gray-900 mb-4">Votre banque</h2>
        <div class="border border-gray-200 rounded-lg p-4">
            <div class="flex justify-between items-start">
                <div>
                    <h3 class="font-semibold text-gray-900">{{ $bank->name }}</h3>
                    <p class="text-sm text-gray-600">{{ $bank->address }}</p>
                </div>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                    {{ $bank->status }}
                </span>
            </div>
        </div>
    </div>
@else
    <div class="bg-yellow-50 border border-yellow-200 text-yellow-700 px-4 py-3 rounded-lg">
        <p>Aucune banque ne vous est encore assignée. Veuillez contacter le super administrateur.</p>
    </div>
@endif
@endsection
