@extends('layouts.superadmin')

@section('title', $user->name . ' - BloodLink')
@section('description', 'Détails de l\'administrateur ' . $user->name)
@section('page-title', $user->name)

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- En-tête -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">{{ $user->name }}</h1>
            <p class="mt-2 text-gray-600">Détails de l'administrateur</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('superadmin.users.edit', $user) }}"
               class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 flex items-center">
                <i class="fas fa-edit mr-2"></i>
                Modifier
            </a>
            <a href="{{ route('superadmin.users.index') }}"
               class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700 flex items-center">
                <i class="fas fa-arrow-left mr-2"></i>
                Retour
            </a>
        </div>
    </div>

    <!-- Informations principales -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Carte principale -->
        <div class="lg:col-span-2">
            <div class="bg-white shadow-md rounded-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-900">Informations de Base</h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Nom complet</label>
                            <p class="text-gray-900 font-medium">{{ $user->name }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Email</label>
                            <p class="text-gray-900">{{ $user->email }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Téléphone</label>
                            <p class="text-gray-900">{{ $user->phone_number }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Rôle</label>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                Administrateur de Banque
                            </span>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Statut</label>
                            @if($user->status === 'active')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-check-circle mr-1"></i>
                                    Actif
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    <i class="fas fa-times-circle mr-1"></i>
                                    Inactif
                                </span>
                            @endif
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Date de création</label>
                            <p class="text-gray-900">{{ $user->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Banque gérée -->
            @if($user->managedBank)
            <div class="bg-white shadow-md rounded-lg overflow-hidden mt-6">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-900">Banque Gérée</h2>
                </div>
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">{{ $user->managedBank->name }}</h3>
                            <p class="text-gray-600">{{ $user->managedBank->address }}</p>
                            <p class="text-gray-600">{{ $user->managedBank->phone_number }}</p>
                        </div>
                        <a href="{{ route('superadmin.banks.show', $user->managedBank) }}"
                           class="bg-gray-100 text-gray-700 px-3 py-2 rounded-md hover:bg-gray-200 transition-colors">
                            Voir la banque
                        </a>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Actions rapides -->
            <div class="bg-white shadow-md rounded-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Actions</h3>
                </div>
                <div class="p-4 space-y-3">
                    <a href="{{ route('superadmin.users.edit', $user) }}"
                       class="w-full bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition-colors flex items-center justify-center">
                        <i class="fas fa-edit mr-2"></i>
                        Modifier
                    </a>

                    <form method="POST" action="{{ route('superadmin.users.toggle-status', $user) }}" class="w-full">
                        @csrf
                        <button type="submit"
                                class="w-full {{ $user->status === 'active' ? 'bg-yellow-600 hover:bg-yellow-700' : 'bg-green-600 hover:bg-green-700' }} text-white px-4 py-2 rounded-md transition-colors flex items-center justify-center">
                            <i class="fas {{ $user->status === 'active' ? 'fa-ban' : 'fa-check' }} mr-2"></i>
                            {{ $user->status === 'active' ? 'Désactiver' : 'Activer' }}
                        </button>
                    </form>

                    @if($user->appointments()->count() === 0 && $user->donations()->count() === 0)
                    <form method="POST" action="{{ route('superadmin.users.destroy', $user) }}" class="w-full" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet administrateur ?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="w-full bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700 transition-colors flex items-center justify-center">
                            <i class="fas fa-trash mr-2"></i>
                            Supprimer
                        </button>
                    </form>
                    @else
                    <button disabled
                            class="w-full bg-gray-400 text-white px-4 py-2 rounded-md cursor-not-allowed flex items-center justify-center"
                            title="Impossible de supprimer cet administrateur car il a des données associées">
                        <i class="fas fa-trash mr-2"></i>
                        Supprimer
                    </button>
                    @endif
                </div>
            </div>

            <!-- Statistiques -->
            <div class="bg-white shadow-md rounded-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Statistiques</h3>
                </div>
                <div class="p-4 space-y-4">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Rendez-vous</span>
                        <span class="font-semibold text-gray-900">{{ $user->appointments()->count() }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Dons</span>
                        <span class="font-semibold text-gray-900">{{ $user->donations()->count() }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
