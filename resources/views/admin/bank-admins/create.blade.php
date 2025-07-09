@extends('layouts.admin')

@section('title', 'Ajouter un Administrateur - BloodLink')
@section('description', 'Créer un nouvel administrateur pour la banque')
@section('page-title', 'Ajouter un Administrateur')

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
                <h1 class="text-3xl font-bold text-gray-900">Ajouter un Administrateur</h1>
                <p class="mt-2 text-gray-600">{{ $bank->name }}</p>
            </div>
        </div>
    </div>

        <!-- Formulaire -->
        <div class="bg-white shadow-lg rounded-lg p-8">
            <form method="POST" action="{{ route('admin.bank-admins.store') }}" class="space-y-6">
                @csrf

                <!-- Informations de base -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            Nom complet *
                        </label>
                        <input type="text"
                               name="name"
                               id="name"
                               value="{{ old('name') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-red-500 focus:border-red-500 transition-colors @error('name') border-red-300 @enderror"
                               placeholder="Nom et prénom de l'administrateur"
                               required>
                        @error('name')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                            Email *
                        </label>
                        <input type="email"
                               name="email"
                               id="email"
                               value="{{ old('email') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-red-500 focus:border-red-500 transition-colors @error('email') border-red-300 @enderror"
                               placeholder="admin@banque.com"
                               required>
                        @error('email')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="phone_number" class="block text-sm font-medium text-gray-700 mb-2">
                        Numéro de téléphone *
                    </label>
                    <input type="text"
                           name="phone_number"
                           id="phone_number"
                           value="{{ old('phone_number') }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-red-500 focus:border-red-500 transition-colors @error('phone_number') border-red-300 @enderror"
                           placeholder="+243 XXX XXX XXX"
                           required>
                    @error('phone_number')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Mot de passe -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                            Mot de passe *
                        </label>
                        <input type="password"
                               name="password"
                               id="password"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-red-500 focus:border-red-500 transition-colors @error('password') border-red-300 @enderror"
                               placeholder="Mot de passe sécurisé"
                               required>
                        @error('password')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                            Confirmer le mot de passe *
                        </label>
                        <input type="password"
                               name="password_confirmation"
                               id="password_confirmation"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-red-500 focus:border-red-500 transition-colors"
                               placeholder="Confirmer le mot de passe"
                               required>
                    </div>
                </div>

                <!-- Informations importantes -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-blue-800">Informations importantes</h3>
                            <div class="mt-2 text-sm text-blue-700">
                                <ul class="list-disc list-inside space-y-1">
                                    <li>L'administrateur aura accès à toutes les fonctionnalités de la banque</li>
                                    <li>Un email de confirmation sera envoyé à l'adresse fournie</li>
                                    <li>Le mot de passe doit contenir au moins 8 caractères</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                    <a href="{{ route('admin.bank-admins.index') }}"
                       class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                        Annuler
                    </a>
                    <button type="submit"
                            class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                        Créer l'administrateur
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
