@extends('layouts.admin')

@section('title', 'Ajouter un Stock - BloodLink')
@section('description', 'Créer un nouveau stock de sang')
@section('page-title', 'Ajouter un Stock')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-lg shadow-md p-6">
        <!-- En-tête -->
        <div class="mb-6">
            <div class="flex items-center">
                <a href="{{ route('admin.stocks.index') }}"
                   class="text-gray-400 hover:text-gray-600 transition-colors mr-4">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </a>
                <div>
                    <h2 class="text-xl font-semibold text-gray-900">Configurer un Stock de Sang</h2>
                    <p class="text-gray-600">Configurez les paramètres de stock pour un groupe sanguin</p>
                </div>
            </div>
        </div>

        @if($errors->any())
            <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Information sur les stocks existants -->
        @if($existingBloodTypes->count() > 0)
            <div class="mb-6 bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-yellow-800">Stocks déjà configurés</h3>
                        <div class="mt-2 text-sm text-yellow-700">
                            <p>Les groupes sanguins suivants ont déjà un stock configuré :</p>
                            <ul class="list-disc list-inside mt-1">
                                @foreach($existingBloodTypes as $bloodType)
                                    <li>{{ $bloodType->name }}</li>
                                @endforeach
                            </ul>
                            <p class="mt-2">
                                <a href="{{ route('admin.stocks.index') }}" class="text-yellow-600 hover:text-yellow-500 font-medium">
                                    Voir et modifier les stocks existants →
                                </a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Formulaire -->
        <form method="POST" action="{{ route('admin.stocks.store') }}" class="space-y-6">
            @csrf

            <!-- Type de sang -->
            <div>
                <label for="blood_type_id" class="block text-sm font-medium text-gray-700 mb-2">
                    Groupe Sanguin *
                </label>
                <select name="blood_type_id" id="blood_type_id"
                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500" required>
                    <option value="">Sélectionnez un groupe sanguin</option>

                    <!-- Groupes sanguins disponibles -->
                    @if($availableBloodTypes->count() > 0)
                        <optgroup label="Groupes sanguins disponibles">
                            @foreach($availableBloodTypes as $bloodType)
                                <option value="{{ $bloodType->id }}"
                                        {{ (old('blood_type_id', request('blood_type_id')) == $bloodType->id) ? 'selected' : '' }}>
                                    {{ $bloodType->name }}
                                </option>
                            @endforeach
                        </optgroup>
                    @endif

                    <!-- Groupes sanguins déjà configurés -->
                    @if($existingBloodTypes->count() > 0)
                        <optgroup label="Groupes sanguins déjà configurés">
                            @foreach($existingBloodTypes as $bloodType)
                                <option value="{{ $bloodType->id }}" disabled>
                                    {{ $bloodType->name }} (déjà configuré)
                                </option>
                            @endforeach
                        </optgroup>
                    @endif
                </select>
                <p class="mt-1 text-sm text-gray-500">
                    @if($availableBloodTypes->count() > 0)
                        Sélectionnez le groupe sanguin pour configurer son stock
                    @else
                        Tous les groupes sanguins sont déjà configurés.
                        <a href="{{ route('admin.stocks.index') }}" class="text-red-600 hover:text-red-500">
                            Voir les stocks existants
                        </a>
                    @endif
                </p>
            </div>

            <!-- Seuil critique -->
            <div>
                <label for="critical_level" class="block text-sm font-medium text-gray-700 mb-2">
                    Seuil Critique (nombre de poches) *
                </label>
                <input type="number" name="critical_level" id="critical_level"
                       value="{{ old('critical_level', 10) }}"
                       class="w-full rounded-lg border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500"
                       min="1" step="1" required>
                <p class="mt-1 text-sm text-gray-500">Nombre minimum de poches disponibles avant alerte ({{ old('critical_level', 10) * 450 }}ml)</p>
            </div>

            <!-- Informations sur la logique de poche -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-blue-800">Logique de Gestion par Poches</h3>
                        <div class="mt-2 text-sm text-blue-700">
                            <ul class="list-disc list-inside space-y-1">
                                <li>Le stock sera initialisé avec 0 poches disponibles</li>
                                <li>Les poches seront ajoutées automatiquement lors des dons de sang</li>
                                <li>Le statut sera calculé automatiquement selon le nombre de poches</li>
                                <li>Les alertes seront générées quand le stock atteint le seuil critique</li>
                                <li>Chaque poche = 450ml de sang</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Lien vers la gestion des dons -->
            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-green-800">Ajouter des Poches</h3>
                        <div class="mt-2 text-sm text-green-700">
                            <p>Pour ajouter des poches à ce stock, utilisez le système de gestion des dons :</p>
                            <a href="{{ route('admin.donations.index') }}" class="inline-flex items-center mt-2 text-green-600 hover:text-green-500">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                </svg>
                                Gérer les dons de sang
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Boutons -->
            <div class="flex justify-end space-x-4 pt-6">
                <a href="{{ route('admin.stocks.index') }}"
                   class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-2 rounded-lg text-sm font-medium transition-colors">
                    Annuler
                </a>
                @if($availableBloodTypes->count() > 0)
                    <button type="submit"
                            class="bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-lg text-sm font-medium transition-colors">
                        Configurer le Stock
                    </button>
                @else
                    <button type="button" disabled
                            class="bg-gray-400 text-gray-600 px-6 py-2 rounded-lg text-sm font-medium cursor-not-allowed">
                        Aucun groupe disponible
                    </button>
                @endif
            </div>
        </form>
    </div>
</div>

<script>
// Calcul automatique du volume en ml basé sur le nombre de poches
document.getElementById('critical_level').addEventListener('input', function() {
    const bags = parseInt(this.value) || 0;
    const volumeMl = bags * 450;
    const volumeL = (volumeMl / 1000).toFixed(1);

    // Mettre à jour le texte d'aide
    const helpText = this.parentNode.querySelector('p');
    helpText.textContent = `Nombre minimum de poches disponibles avant alerte (${volumeMl}ml / ${volumeL}L)`;
});
</script>
@endsection
