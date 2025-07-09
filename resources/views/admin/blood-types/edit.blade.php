@extends('layouts.app')

@section('title', 'Modifier le Type de Sang - BloodLink')
@section('description', 'Modifier un type de sang dans le système BloodLink')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- En-tête -->
        <div class="mb-8">
            <div class="flex items-center">
                <a href="{{ route('admin.blood-types.index') }}"
                   class="text-gray-400 hover:text-gray-600 transition-colors mr-4">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </a>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Modifier le Type de Sang</h1>
                    <p class="mt-2 text-gray-600">Modifiez les informations du type de sang {{ $bloodType->name }}</p>
                </div>
            </div>
        </div>

        <!-- Formulaire -->
        <div class="bg-white shadow-lg rounded-lg p-8">
            <form method="POST" action="{{ route('admin.blood-types.update', $bloodType->id) }}" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Nom du type -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        Nom du type de sang *
                    </label>
                    <input type="text"
                           name="name"
                           id="name"
                           value="{{ old('name', $bloodType->name) }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-red-500 focus:border-red-500 transition-colors @error('name') border-red-300 @enderror"
                           placeholder="Ex: A+, B-, O+, AB+"
                           required>
                    @error('name')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                        Description
                    </label>
                    <textarea name="description"
                              id="description"
                              rows="4"
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-red-500 focus:border-red-500 transition-colors @error('description') border-red-300 @enderror"
                              placeholder="Description optionnelle du type de sang...">{{ old('description', $bloodType->description) }}</textarea>
                    @error('description')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Statistiques du type -->
                <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                    <h3 class="text-sm font-medium text-gray-900 mb-3">Statistiques actuelles</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="text-center">
                            <div class="text-2xl font-bold text-red-600">{{ $bloodType->users()->count() }}</div>
                            <div class="text-sm text-gray-600">Donneurs</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-red-600">{{ $bloodType->donations()->count() }}</div>
                            <div class="text-sm text-gray-600">Dons</div>
                        </div>
                    </div>
                </div>

                <!-- Informations sur la compatibilité -->
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
                                    <li>Le nom doit être unique dans le système</li>
                                    <li>Utilisez le format standard (ex: A+, B-, O+, AB+)</li>
                                    <li>La modification peut affecter les donneurs existants</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Boutons d'action -->
                <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                    <a href="{{ route('admin.blood-types.index') }}"
                       class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors">
                        Annuler
                    </a>
                    <button type="submit"
                            class="px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors">
                        <svg class="h-4 w-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Mettre à jour
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
