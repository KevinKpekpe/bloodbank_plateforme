@extends('layouts.admin')

@section('title', 'Ajouter Plusieurs Stocks - BloodLink')
@section('description', 'Créer plusieurs stocks de sang en une fois')
@section('page-title', 'Ajouter Plusieurs Stocks')

@section('content')
<div class="max-w-4xl mx-auto">
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
                    <h2 class="text-xl font-semibold text-gray-900">Ajouter Plusieurs Stocks</h2>
                    <p class="text-gray-600">Créez plusieurs stocks de sang en une seule fois</p>
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

        @if($availableBloodTypes->count() === 0)
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-yellow-800">Aucun type de sang disponible</h3>
                        <div class="mt-2 text-sm text-yellow-700">
                            <p>Tous les types de sang ont déjà un stock configuré. Vous pouvez retourner à la liste des stocks pour les modifier.</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-6">
                <a href="{{ route('admin.stocks.index') }}"
                   class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-2 rounded-lg text-sm font-medium transition-colors">
                    Retour aux stocks
                </a>
            </div>
        @else
            <!-- Formulaire -->
            <form method="POST" action="{{ route('admin.stocks.store-multiple') }}" id="multiple-stocks-form">
                @csrf

                <!-- Sélection des types de sang -->
                <div class="mb-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Sélectionner les types de sang</h3>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        @foreach($availableBloodTypes as $bloodType)
                            <label class="relative flex items-start">
                                <div class="flex items-center h-5">
                                    <input type="checkbox"
                                           name="selected_types[]"
                                           value="{{ $bloodType->id }}"
                                           class="focus:ring-red-500 h-4 w-4 text-red-600 border-gray-300 rounded"
                                           onchange="toggleStockForm({{ $bloodType->id }}, this.checked)">
                                </div>
                                <div class="ml-3 text-sm">
                                    <span class="font-medium text-gray-900">{{ $bloodType->name }}</span>
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>

                <!-- Formulaires des stocks -->
                <div id="stock-forms" class="space-y-6">
                    @foreach($availableBloodTypes as $bloodType)
                        <div id="stock-form-{{ $bloodType->id }}" class="stock-form hidden bg-gray-50 border border-gray-200 rounded-lg p-4">
                            <div class="flex items-center justify-between mb-4">
                                <h4 class="text-lg font-medium text-gray-900">Stock {{ $bloodType->name }}</h4>
                                <button type="button"
                                        class="text-red-600 hover:text-red-800 text-sm"
                                        onclick="removeStockForm({{ $bloodType->id }})">
                                    Supprimer
                                </button>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <input type="hidden" name="stocks[{{ $bloodType->id }}][blood_type_id]" value="{{ $bloodType->id }}">

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Quantité (ml) *
                                    </label>
                                    <input type="number"
                                           name="stocks[{{ $bloodType->id }}][quantity]"
                                           class="w-full rounded-lg border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500"
                                           min="0" step="1" required
                                           placeholder="0">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Seuil Critique (ml) *
                                    </label>
                                    <input type="number"
                                           name="stocks[{{ $bloodType->id }}][critical_level]"
                                           class="w-full rounded-lg border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500"
                                           min="1" step="1" required
                                           placeholder="1000">
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Actions rapides -->
                <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <h3 class="text-sm font-medium text-blue-800 mb-3">Actions rapides</h3>
                    <div class="flex flex-wrap gap-2">
                        <button type="button"
                                class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-sm"
                                onclick="selectAll()">
                            Tout sélectionner
                        </button>
                        <button type="button"
                                class="bg-gray-600 hover:bg-gray-700 text-white px-3 py-1 rounded text-sm"
                                onclick="deselectAll()">
                            Tout désélectionner
                        </button>
                        <button type="button"
                                class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-sm"
                                onclick="setDefaultValues()">
                            Valeurs par défaut
                        </button>
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
                        Créer les Stocks
                    </button>
                </div>
            </form>
        @endif
    </div>
</div>

<script>
function toggleStockForm(bloodTypeId, checked) {
    const form = document.getElementById(`stock-form-${bloodTypeId}`);
    if (checked) {
        form.classList.remove('hidden');
    } else {
        form.classList.add('hidden');
        // Réinitialiser les valeurs
        const inputs = form.querySelectorAll('input[type="number"]');
        inputs.forEach(input => input.value = '');
    }
}

function removeStockForm(bloodTypeId) {
    const checkbox = document.querySelector(`input[value="${bloodTypeId}"]`);
    checkbox.checked = false;
    toggleStockForm(bloodTypeId, false);
}

function selectAll() {
    const checkboxes = document.querySelectorAll('input[name="selected_types[]"]');
    checkboxes.forEach(checkbox => {
        checkbox.checked = true;
        toggleStockForm(checkbox.value, true);
    });
}

function deselectAll() {
    const checkboxes = document.querySelectorAll('input[name="selected_types[]"]');
    checkboxes.forEach(checkbox => {
        checkbox.checked = false;
        toggleStockForm(checkbox.value, false);
    });
}

function setDefaultValues() {
    const quantityInputs = document.querySelectorAll('input[name$="[quantity]"]');
    const criticalInputs = document.querySelectorAll('input[name$="[critical_level]"]');

    quantityInputs.forEach(input => {
        if (input.closest('.stock-form').classList.contains('hidden')) return;
        input.value = '0';
    });

    criticalInputs.forEach(input => {
        if (input.closest('.stock-form').classList.contains('hidden')) return;
        input.value = '1000';
    });
}

// Validation du formulaire
document.getElementById('multiple-stocks-form').addEventListener('submit', function(e) {
    const selectedCheckboxes = document.querySelectorAll('input[name="selected_types[]"]:checked');
    if (selectedCheckboxes.length === 0) {
        e.preventDefault();
        alert('Veuillez sélectionner au moins un type de sang.');
        return false;
    }
});
</script>
@endsection