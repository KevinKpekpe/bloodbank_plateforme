@extends('layouts.admin')

@section('title', 'Détails de l\'Administrateur - BloodLink')
@section('description', 'Informations détaillées de l\'administrateur')
@section('page-title', 'Détails de l\'Administrateur')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- En-tête -->
    <div class="mb-8">
        <div class="flex items-center">
            <a href="{{ route('admin.bank-admins.index') }}"
               class="text-gray-400 hover:text-gray-600 transition-colors mr-4">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Détails de l'Administrateur</h1>
                <p class="mt-2 text-gray-600">{{ $bank->name }}</p>
            </div>
        </div>
    </div>

        <!-- Messages de succès/erreur -->
        @if(session('success'))
            <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                {{ session('error') }}
            </div>
        @endif

        <!-- Informations de l'administrateur -->
        <div class="bg-white shadow-lg rounded-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-medium text-gray-900">Informations personnelles</h2>
            </div>

            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Photo de profil -->
                    <div class="flex items-center space-x-4">
                        <img class="h-20 w-20 rounded-full"
                             src="https://ui-avatars.com/api/?name={{ urlencode($bank_admin->name) }}&color=7F1D1D&background=EF4444&size=80"
                             alt="{{ $bank_admin->name }}">
                        <div>
                            <h3 class="text-xl font-semibold text-gray-900">{{ $bank_admin->name }}</h3>
                            <p class="text-gray-500">{{ $bank_admin->email }}</p>
                        </div>
                    </div>

                    <!-- Statut et rôle -->
                    <div class="space-y-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Statut</label>
                            @if($bank_admin->status === 'active')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <svg class="w-2 h-2 mr-1" fill="currentColor" viewBox="0 0 8 8">
                                        <circle cx="4" cy="4" r="3"></circle>
                                    </svg>
                                    Actif
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    <svg class="w-2 h-2 mr-1" fill="currentColor" viewBox="0 0 8 8">
                                        <circle cx="4" cy="4" r="3"></circle>
                                    </svg>
                                    Inactif
                                </span>
                            @endif
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Rôle</label>
                            @if($bank_admin->id === $bank->admin_id)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    <i class="fas fa-crown mr-1"></i>Administrateur Principal
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    <i class="fas fa-user-shield mr-1"></i>Administrateur
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Informations de contact -->
                <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Email</label>
                        <p class="text-gray-900">{{ $bank_admin->email }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Téléphone</label>
                        <p class="text-gray-900">{{ $bank_admin->phone_number }}</p>
                    </div>
                </div>

                <!-- Date de création -->
                <div class="mt-6">
                    <label class="block text-sm font-medium text-gray-500 mb-1">Membre depuis</label>
                    <p class="text-gray-900">{{ $bank_admin->created_at->format('d/m/Y à H:i') }}</p>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="mt-8 bg-white shadow-lg rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Actions</h3>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <a href="{{ route('admin.bank-admins.edit', $bank_admin) }}"
                   class="flex items-center justify-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                    <i class="fas fa-edit text-blue-600 text-2xl mr-3"></i>
                    <div>
                        <h4 class="font-medium text-gray-900">Modifier</h4>
                        <p class="text-sm text-gray-500">Éditer les informations</p>
                    </div>
                </a>

                @if($bank_admin->id !== $bank->admin_id)
                    <form method="POST" action="{{ route('admin.bank-admins.toggle-status', $bank_admin) }}" class="w-full">
                        @csrf
                        <button type="submit"
                                class="w-full flex items-center justify-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                            <i class="fas fa-{{ $bank_admin->status === 'active' ? 'ban' : 'check' }} text-yellow-600 text-2xl mr-3"></i>
                            <div>
                                <h4 class="font-medium text-gray-900">{{ $bank_admin->status === 'active' ? 'Désactiver' : 'Activer' }}</h4>
                                <p class="text-sm text-gray-500">{{ $bank_admin->status === 'active' ? 'Suspendre l\'accès' : 'Réactiver l\'accès' }}</p>
                            </div>
                        </button>
                    </form>

                    <form method="POST" action="{{ route('admin.bank-admins.destroy', $bank_admin) }}" class="w-full">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet administrateur ?')"
                                class="w-full flex items-center justify-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                            <i class="fas fa-trash text-red-600 text-2xl mr-3"></i>
                            <div>
                                <h4 class="font-medium text-gray-900">Supprimer</h4>
                                <p class="text-sm text-gray-500">Supprimer définitivement</p>
                            </div>
                        </button>
                    </form>
                @else
                    <div class="flex items-center justify-center p-4 border border-gray-200 rounded-lg bg-gray-50">
                        <i class="fas fa-lock text-gray-400 text-2xl mr-3"></i>
                        <div>
                            <h4 class="font-medium text-gray-500">Actions limitées</h4>
                            <p class="text-sm text-gray-400">Admin principal</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
