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
                    <h2 class="text-xl font-semibold text-gray-900">Ajouter un Stock de Sang</h2>
                    <p class="text-gray-600">Créez un nouveau stock pour un groupe sanguin</p>
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
                    @foreach($availableBloodTypes as $bloodType)
                        <option value="{{ $bloodType->id }}"
                                {{ (old('blood_type_id', request('blood_type_id')) == $bloodType->id) ? 'selected' : '' }}>
                            {{ $bloodType->name }}
                        </option>
                    @endforeach
                </select>
                <p class="mt-1 text-sm text-gray-500">Sélectionnez le groupe sanguin pour ce stock</p>
            </div>

            <!-- Quantité -->
            <div>
                <label for="quantity" class="block text-sm font-medium text-gray-700 mb-2">
                    Quantité (ml) *
                </label>
                <input type="number" name="quantity" id="quantity"
                       value="{{ old('quantity', 0) }}"
                       class="w-full rounded-lg border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500"
                       min="0" step="1" required>
                <p class="mt-1 text-sm text-gray-500">Quantité actuelle en millilitres</p>
            </div>

            <!-- Seuil critique -->
            <div>
                <label for="critical_level" class="block text-sm font-medium text-gray-700 mb-2">
                    Seuil Critique (ml) *
                </label>
                <input type="number" name="critical_level" id="critical_level"
                       value="{{ old('critical_level', 1000) }}"
                       class="w-full rounded-lg border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500"
                       min="1" step="1" required>
                <p class="mt-1 text-sm text-gray-500">Seuil d'alerte pour stock faible (en ml)</p>
            </div>

            <!-- Informations supplémentaires -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-blue-800">Informations</h3>
                        <div class="mt-2 text-sm text-blue-700">
                            <ul class="list-disc list-inside space-y-1">
                                <li>Le statut du stock sera automatiquement calculé selon la quantité</li>
                                <li>Les alertes seront générées quand le stock atteint le seuil critique</li>
                                <li>Vous pourrez modifier ces valeurs ultérieurement</li>
                            </ul>
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
                <button type="submit"
                        class="bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-lg text-sm font-medium transition-colors">
                    Créer le Stock
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
