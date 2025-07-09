@extends('layouts.superadmin')

@section('title', $bank->name . ' - BloodLink')
@section('description', 'Détails de la banque de sang ' . $bank->name)
@section('page-title', $bank->name)

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- En-tête avec actions -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">{{ $bank->name }}</h1>
            <p class="mt-2 text-gray-600">{{ $bank->address }}</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('superadmin.banks.edit', $bank) }}"
               class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 flex items-center">
                <i class="fas fa-edit mr-2"></i>
                Modifier
            </a>
            <a href="{{ route('superadmin.banks.statistics', $bank) }}"
               class="bg-purple-600 text-white px-4 py-2 rounded-md hover:bg-purple-700 flex items-center">
                <i class="fas fa-chart-bar mr-2"></i>
                Statistiques
            </a>
            <a href="{{ route('superadmin.banks.index') }}"
               class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700 flex items-center">
                <i class="fas fa-arrow-left mr-2"></i>
                Retour
            </a>
        </div>
    </div>

    <!-- Informations générales -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Informations de base -->
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Informations Générales</h2>
            <div class="space-y-3">
                <div>
                    <label class="text-sm font-medium text-gray-500">Nom</label>
                    <p class="text-sm text-gray-900">{{ $bank->name }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-500">Adresse</label>
                    <p class="text-sm text-gray-900">{{ $bank->address }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-500">Statut</label>
                    <p class="text-sm">
                        @if($bank->status === 'active')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                Actif
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                Inactif
                            </span>
                        @endif
                    </p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-500">Créée le</label>
                    <p class="text-sm text-gray-900">{{ $bank->created_at->format('d/m/Y H:i') }}</p>
                </div>
            </div>
        </div>

        <!-- Contact -->
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Contact</h2>
            <div class="space-y-3">
                <div>
                    <label class="text-sm font-medium text-gray-500">Téléphone</label>
                    <p class="text-sm text-gray-900">{{ $bank->contact_phone }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-500">Email</label>
                    <p class="text-sm text-gray-900">{{ $bank->contact_email }}</p>
                </div>
                @if($bank->latitude && $bank->longitude)
                    <div>
                        <label class="text-sm font-medium text-gray-500">Coordonnées GPS</label>
                        <p class="text-sm text-gray-900">{{ $bank->latitude }}, {{ $bank->longitude }}</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Statistiques -->
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Statistiques</h2>
            <div class="space-y-3">
                <div>
                    <label class="text-sm font-medium text-gray-500">Total Rendez-vous</label>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['total_appointments'] }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-500">Total Dons</label>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['total_donations'] }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-500">RDV en attente</label>
                    <p class="text-lg font-semibold text-orange-600">{{ $stats['pending_appointments'] }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-500">Dons disponibles</label>
                    <p class="text-lg font-semibold text-green-600">{{ $stats['available_donations'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Administrateur -->
    @if($bank->admin)
        <div class="bg-white p-6 rounded-lg shadow-md mb-8">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Administrateur</h2>
            <div class="flex items-center space-x-4">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-user text-red-600 text-xl"></i>
                    </div>
                </div>
                <div>
                    <h3 class="text-lg font-medium text-gray-900">{{ $bank->admin->name }}</h3>
                    <p class="text-sm text-gray-500">{{ $bank->admin->email }}</p>
                    <p class="text-sm text-gray-500">{{ $bank->admin->phone_number }}</p>
                </div>
            </div>
        </div>
    @endif

    <!-- Actions rapides -->
    <div class="bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-xl font-semibold text-gray-900 mb-4">Actions Rapides</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <a href="{{ route('superadmin.banks.statistics', $bank) }}"
               class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50">
                <i class="fas fa-chart-bar text-purple-600 text-2xl mr-3"></i>
                <div>
                    <h3 class="font-medium text-gray-900">Voir les statistiques</h3>
                    <p class="text-sm text-gray-500">Analyser les performances</p>
                </div>
            </a>
            <a href="{{ route('superadmin.banks.edit', $bank) }}"
               class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50">
                <i class="fas fa-edit text-blue-600 text-2xl mr-3"></i>
                <div>
                    <h3 class="font-medium text-gray-900">Modifier la banque</h3>
                    <p class="text-sm text-gray-500">Éditer les informations</p>
                </div>
            </a>
            @if($bank->users_count === 0 && $bank->appointments_count === 0 && $bank->donations_count === 0)
                <form method="POST" action="{{ route('superadmin.banks.destroy', $bank) }}" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="w-full flex items-center p-4 border border-red-200 rounded-lg hover:bg-red-50 text-left"
                            onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette banque ?')">
                        <i class="fas fa-trash text-red-600 text-2xl mr-3"></i>
                        <div>
                            <h3 class="font-medium text-red-900">Supprimer la banque</h3>
                            <p class="text-sm text-red-500">Action irréversible</p>
                        </div>
                    </button>
                </form>
            @else
                <div class="flex items-center p-4 border border-gray-200 rounded-lg bg-gray-50">
                    <i class="fas fa-lock text-gray-400 text-2xl mr-3"></i>
                    <div>
                        <h3 class="font-medium text-gray-500">Suppression bloquée</h3>
                        <p class="text-sm text-gray-400">Données associées</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
