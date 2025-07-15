@extends('layouts.admin')

@section('title', 'Réserver Poche ' . $bloodBag->bag_number . ' - BloodLink')
@section('description', 'Réserver une poche de sang pour un patient')
@section('page-title', 'Réserver une Poche')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- En-tête avec navigation -->
    <div class="mb-8">
        <nav class="flex" aria-label="Breadcrumb">
            <ol class="flex items-center space-x-4">
                <li>
                    <a href="{{ route('admin.blood-bags.index') }}" class="text-gray-400 hover:text-gray-500">
                        Poches de Sang
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="flex-shrink-0 h-5 w-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                        </svg>
                        <a href="{{ route('admin.blood-bags.show', $bloodBag) }}" class="ml-4 text-gray-400 hover:text-gray-500">
                            {{ $bloodBag->bag_number }}
                        </a>
                    </div>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="flex-shrink-0 h-5 w-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                        </svg>
                        <span class="ml-4 text-sm font-medium text-gray-500">Réserver</span>
                    </div>
                </li>
            </ol>
        </nav>
        <h1 class="mt-2 text-3xl font-bold text-gray-900">Réserver la Poche {{ $bloodBag->bag_number }}</h1>
        <p class="mt-2 text-gray-600">Remplissez les informations pour réserver cette poche pour un patient</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Formulaire de réservation -->
        <div class="lg:col-span-2">
            <div class="bg-white shadow-sm border rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-6">Informations de Réservation</h3>

                <form method="POST" action="{{ route('admin.blood-bags.store-reservation', $bloodBag) }}">
                    @csrf

                    <!-- Informations du patient -->
                    <div class="space-y-6">
                        <div>
                            <h4 class="text-md font-medium text-gray-900 mb-4">Informations du Patient</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="patient_name" class="block text-sm font-medium text-gray-700 mb-1">
                                        Nom du Patient <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="patient_name" id="patient_name"
                                           value="{{ old('patient_name') }}" required
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500"
                                           placeholder="Nom complet du patient">
                                    @error('patient_name')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="patient_id" class="block text-sm font-medium text-gray-700 mb-1">
                                        ID Patient
                                    </label>
                                    <input type="text" name="patient_id" id="patient_id"
                                           value="{{ old('patient_id') }}"
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500"
                                           placeholder="Numéro d'identification">
                                    @error('patient_id')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Informations médicales -->
                        <div>
                            <h4 class="text-md font-medium text-gray-900 mb-4">Informations Médicales</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="hospital_name" class="block text-sm font-medium text-gray-700 mb-1">
                                        Hôpital/Établissement
                                    </label>
                                    <input type="text" name="hospital_name" id="hospital_name"
                                           value="{{ old('hospital_name') }}"
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500"
                                           placeholder="Nom de l'hôpital">
                                    @error('hospital_name')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="doctor_name" class="block text-sm font-medium text-gray-700 mb-1">
                                        Médecin Prescripteur
                                    </label>
                                    <input type="text" name="doctor_name" id="doctor_name"
                                           value="{{ old('doctor_name') }}"
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500"
                                           placeholder="Nom du médecin">
                                    @error('doctor_name')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Détails de la réservation -->
                        <div>
                            <h4 class="text-md font-medium text-gray-900 mb-4">Détails de la Réservation</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="urgency_level" class="block text-sm font-medium text-gray-700 mb-1">
                                        Niveau d'Urgence <span class="text-red-500">*</span>
                                    </label>
                                    <select name="urgency_level" id="urgency_level" required
                                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">
                                        <option value="">Sélectionner le niveau d'urgence</option>
                                        <option value="normal" {{ old('urgency_level') === 'normal' ? 'selected' : '' }}>Normal</option>
                                        <option value="urgent" {{ old('urgency_level') === 'urgent' ? 'selected' : '' }}>Urgent</option>
                                        <option value="critical" {{ old('urgency_level') === 'critical' ? 'selected' : '' }}>Critique</option>
                                    </select>
                                    @error('urgency_level')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="duration" class="block text-sm font-medium text-gray-700 mb-1">
                                        Durée de Réservation <span class="text-red-500">*</span>
                                    </label>
                                    <select name="duration" id="duration" required
                                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">
                                        <option value="">Sélectionner la durée</option>
                                        <option value="2" {{ old('duration') === '2' ? 'selected' : '' }}>2 heures</option>
                                        <option value="4" {{ old('duration') === '4' ? 'selected' : '' }}>4 heures</option>
                                        <option value="6" {{ old('duration') === '6' ? 'selected' : '' }}>6 heures</option>
                                        <option value="12" {{ old('duration') === '12' ? 'selected' : '' }}>12 heures</option>
                                        <option value="24" {{ old('duration') === '24' ? 'selected' : '' }}>24 heures</option>
                                    </select>
                                    @error('duration')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Notes -->
                        <div>
                            <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">
                                Notes Additionnelles
                            </label>
                            <textarea name="notes" id="notes" rows="4"
                                      class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500"
                                      placeholder="Informations supplémentaires, instructions spéciales, etc.">{{ old('notes') }}</textarea>
                            @error('notes')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Boutons d'action -->
                        <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                            <a href="{{ route('admin.blood-bags.show', $bloodBag) }}"
                               class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                                Annuler
                            </a>
                            <button type="submit"
                                    class="px-4 py-2 text-sm font-medium text-white bg-green-600 border border-transparent rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                Confirmer la Réservation
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Informations de la poche -->
        <div class="space-y-6">
            <!-- Résumé de la poche -->
            <div class="bg-white shadow-sm border rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Informations de la Poche</h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Numéro de Poche</label>
                        <p class="mt-1 text-sm text-gray-900 font-mono">{{ $bloodBag->bag_number }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Groupe Sanguin</label>
                        <p class="mt-1">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                {{ $bloodBag->bloodType->name }}
                            </span>
                        </p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Volume</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $bloodBag->volume_ml }}ml ({{ $bloodBag->getVolumeInLiters() }}L)</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Date d'Expiration</label>
                        <p class="mt-1 text-sm text-gray-900">
                            {{ $bloodBag->expiry_date->format('d/m/Y') }}
                            @if($bloodBag->isExpiringSoon())
                                <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                    Expire bientôt ({{ $bloodBag->getDaysUntilExpiry() }} jours)
                                </span>
                            @endif
                        </p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Donneur</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $bloodBag->donor->firstname }} {{ $bloodBag->donor->surname }}</p>
                    </div>
                </div>
            </div>

            <!-- Avertissements -->
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-yellow-800">Important</h3>
                        <div class="mt-2 text-sm text-yellow-700">
                            <ul class="list-disc list-inside space-y-1">
                                <li>La réservation expire automatiquement selon la durée choisie</li>
                                <li>Une fois expirée, la poche redevient disponible</li>
                                <li>Vérifiez la compatibilité du groupe sanguin</li>
                                <li>Confirmez l'urgence avant de réserver</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Compatibilité des groupes sanguins -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-blue-800">Compatibilité</h3>
                        <div class="mt-2 text-sm text-blue-700">
                            <p>Groupe {{ $bloodBag->bloodType->name }} :</p>
                            <ul class="list-disc list-inside mt-1">
                                @foreach($bloodBag->bloodType->getCompatibleRecipients() as $compatible)
                                    <li>{{ $compatible->name }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
