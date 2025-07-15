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
                    <h2 class="text-xl font-semibold text-gray-900">Configurer le Stock</h2>
                    <p class="text-gray-600">Modifiez les paramètres du stock {{ $stock->bloodType->name }}</p>
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

        <!-- Statistiques actuelles des poches -->
        <div class="mb-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
            <h3 class="text-sm font-medium text-blue-900 mb-3">Statistiques actuelles des poches</h3>
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div>
                    <span class="text-blue-600">Total des poches :</span>
                    <span class="font-medium text-blue-900">{{ $stock->total_bags ?? 0 }} poches</span>
                </div>
                <div>
                    <span class="text-blue-600">Poches disponibles :</span>
                    <span class="font-medium text-blue-900">{{ $stock->available_bags ?? 0 }} poches</span>
                </div>
                <div>
                    <span class="text-blue-600">Poches réservées :</span>
                    <span class="font-medium text-blue-900">{{ $stock->reserved_bags ?? 0 }} poches</span>
                </div>
                <div>
                    <span class="text-blue-600">Volume total :</span>
                    <span class="font-medium text-blue-900">{{ number_format(($stock->total_bags ?? 0) * 450) }}ml ({{ number_format((($stock->total_bags ?? 0) * 450) / 1000, 1) }}L)</span>
                </div>
            </div>
        </div>

        <!-- Statut actuel -->
        <div class="mb-6 bg-gray-50 border border-gray-200 rounded-lg p-4">
            <h3 class="text-sm font-medium text-gray-900 mb-3">Statut actuel</h3>
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div>
                    <span class="text-gray-600">Statut :</span>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                        @if($stock->available_bags == 0) bg-red-100 text-red-800
                        @elseif($stock->isCritical()) bg-red-100 text-red-800
                        @elseif($stock->isLow()) bg-yellow-100 text-yellow-800
                        @else bg-green-100 text-green-800
                        @endif">
                        @if($stock->available_bags == 0) Rupture
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

            <!-- Seuil critique -->
            <div>
                <label for="critical_level" class="block text-sm font-medium text-gray-700 mb-2">
                    Seuil Critique (nombre de poches) *
                </label>
                <input type="number" name="critical_level" id="critical_level"
                       value="{{ old('critical_level', $stock->critical_level) }}"
                       class="w-full rounded-lg border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500"
                       min="1" step="1" required>
                <p class="mt-1 text-sm text-gray-500">Nombre minimum de poches disponibles avant alerte ({{ old('critical_level', $stock->critical_level) * 450 }}ml)</p>
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
                                <li>Les statistiques sont calculées automatiquement à partir des poches réelles</li>
                                <li>Le statut sera mis à jour automatiquement selon le nombre de poches disponibles</li>
                                <li>Les alertes seront générées quand le stock atteint le seuil critique</li>
                                <li>Chaque poche = 450ml de sang</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Lien vers la gestion des poches -->
            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-green-800">Gérer les Poches</h3>
                        <div class="mt-2 text-sm text-green-700">
                            <p>Pour voir et gérer les poches de ce groupe sanguin :</p>
                            <a href="{{ route('admin.blood-bags.index', ['blood_type_id' => $stock->blood_type_id]) }}" class="inline-flex items-center mt-2 text-green-600 hover:text-green-500">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                </svg>
                                Voir les poches {{ $stock->bloodType->name }}
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
                <button type="submit"
                        class="bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-lg text-sm font-medium transition-colors">
                    Mettre à Jour
                </button>
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
