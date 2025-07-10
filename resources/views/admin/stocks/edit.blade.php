@extends('layouts.admin')

@section('title', 'Modifier le Stock - BloodLink')
@section('description', 'Modifier un stock de sang existant')
@section('page-title', 'Modifier le Stock')

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
                    <h2 class="text-xl font-semibold text-gray-900">Modifier le Stock</h2>
                    <p class="text-gray-600">Modifiez les informations du stock {{ $stock->bloodType->name }}</p>
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

        <!-- Informations actuelles -->
        <div class="mb-6 bg-gray-50 border border-gray-200 rounded-lg p-4">
            <h3 class="text-sm font-medium text-gray-900 mb-3">Informations actuelles</h3>
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div>
                    <span class="text-gray-600">Groupe sanguin :</span>
                    <span class="font-medium text-gray-900">{{ $stock->bloodType->name }}</span>
                </div>
                <div>
                    <span class="text-gray-600">Statut actuel :</span>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                        @if($stock->quantity == 0) bg-red-100 text-red-800
                        @elseif($stock->isCritical()) bg-red-100 text-red-800
                        @elseif($stock->isLow()) bg-yellow-100 text-yellow-800
                        @else bg-green-100 text-green-800
                        @endif">
                        @if($stock->quantity == 0) Rupture
                        @elseif($stock->isCritical()) Critique
                        @elseif($stock->isLow()) Faible
                        @else Normal
                        @endif
                    </span>
                </div>
                <div>
                    <span class="text-gray-600">Dernière mise à jour :</span>
                    <span class="font-medium text-gray-900">
                        {{ $stock->last_updated ? $stock->last_updated->format('d/m/Y H:i') : 'Jamais' }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Formulaire -->
        <form method="POST" action="{{ route('admin.stocks.update', $stock->id) }}" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Quantité -->
            <div>
                <label for="quantity" class="block text-sm font-medium text-gray-700 mb-2">
                    Quantité (ml) *
                </label>
                <input type="number" name="quantity" id="quantity"
                       value="{{ old('quantity', $stock->quantity) }}"
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
                       value="{{ old('critical_level', $stock->critical_level) }}"
                       class="w-full rounded-lg border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500"
                       min="1" step="1" required>
                <p class="mt-1 text-sm text-gray-500">Seuil d'alerte pour stock faible (en ml)</p>
            </div>

            <!-- Aperçu du nouveau statut -->
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-yellow-800">Note importante</h3>
                        <div class="mt-2 text-sm text-yellow-700">
                            <p>Le statut du stock sera automatiquement recalculé après la mise à jour selon les nouvelles valeurs.</p>
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
                    Mettre à Jour
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
