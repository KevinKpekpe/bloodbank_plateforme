@extends('layouts.admin')

@section('title', 'Modifier l\'Administrateur - BloodLink')
@section('description', 'Modifier les informations de l\'administrateur')
@section('page-title', 'Modifier l\'Administrateur')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- En-tête -->
    <div class="mb-8">
        <div class="flex items-center">
            <a href="{{ route('admin.bank-admins.show', $bank_admin) }}"
               class="text-gray-400 hover:text-gray-600 transition-colors mr-4">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Modifier l'Administrateur</h1>
                <p class="mt-2 text-gray-600">{{ $bank->name }}</p>
            </div>
        </div>
    </div>

        <!-- Formulaire -->
        <div class="bg-white shadow-lg rounded-lg p-8">
            <form method="POST" action="{{ route('admin.bank-admins.update', $bank_admin) }}" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Informations de base -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            Nom complet *
                        </label>
                        <input type="text"
                               name="name"
                               id="name"
                               value="{{ old('name', $bank_admin->name) }}"
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
                               value="{{ old('email', $bank_admin->email) }}"
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
                           value="{{ old('phone_number', $bank_admin->phone_number) }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-red-500 focus:border-red-500 transition-colors @error('phone_number') border-red-300 @enderror"
                           placeholder="+243 XXX XXX XXX"
                           required>
                    @error('phone_number')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
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
                                    <li>Seules les informations de base peuvent être modifiées</li>
                                    <li>Le mot de passe ne peut pas être modifié depuis cette interface</li>
                                    <li>L'administrateur devra utiliser la fonction "Mot de passe oublié" si nécessaire</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                    <a href="{{ route('admin.bank-admins.show', $bank_admin) }}"
                       class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                        Annuler
                    </a>
                    <button type="submit"
                            class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                        Mettre à jour
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
