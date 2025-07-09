@extends('layouts.superadmin')

@section('title', 'Modifier ' . $bank->name . ' - BloodLink')
@section('description', 'Modifier les informations de la banque de sang ' . $bank->name)
@section('page-title', 'Modifier ' . $bank->name)

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- En-tête -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Modifier {{ $bank->name }}</h1>
            <p class="mt-2 text-gray-600">Modifier les informations de la banque de sang</p>
        </div>
        <a href="{{ route('superadmin.banks.show', $bank) }}"
           class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700 flex items-center">
            <i class="fas fa-arrow-left mr-2"></i>
            Retour
        </a>
    </div>

    <!-- Formulaire -->
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <form method="POST" action="{{ route('superadmin.banks.update', $bank) }}" class="p-6">
            @csrf
            @method('PUT')

            <!-- Informations générales -->
            <div class="mb-8">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Informations Générales</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            Nom de la banque *
                        </label>
                        <input type="text"
                               name="name"
                               id="name"
                               value="{{ old('name', $bank->name) }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-red-500 focus:border-red-500 transition-colors @error('name') border-red-300 @enderror"
                               required>
                        @error('name')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                            Statut *
                        </label>
                        <select name="status"
                                id="status"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-red-500 focus:border-red-500 transition-colors @error('status') border-red-300 @enderror"
                                required>
                            <option value="active" {{ old('status', $bank->status) === 'active' ? 'selected' : '' }}>Actif</option>
                            <option value="inactive" {{ old('status', $bank->status) === 'inactive' ? 'selected' : '' }}>Inactif</option>
                        </select>
                        @error('status')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-6">
                    <label for="address" class="block text-sm font-medium text-gray-700 mb-2">
                        Adresse complète *
                    </label>
                    <textarea name="address"
                              id="address"
                              rows="3"
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-red-500 focus:border-red-500 transition-colors @error('address') border-red-300 @enderror"
                              required>{{ old('address', $bank->address) }}</textarea>
                    @error('address')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Coordonnées GPS -->
            <div class="mb-8">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Coordonnées GPS</h2>
                <p class="text-sm text-gray-600 mb-4">Ces coordonnées sont utilisées pour la géolocalisation et les cartes</p>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="latitude" class="block text-sm font-medium text-gray-700 mb-2">
                            Latitude
                        </label>
                        <input type="number"
                               name="latitude"
                               id="latitude"
                               value="{{ old('latitude', $bank->latitude) }}"
                               step="0.000001"
                               min="-90"
                               max="90"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-red-500 focus:border-red-500 transition-colors @error('latitude') border-red-300 @enderror"
                               placeholder="Ex: -4.4419">
                        @error('latitude')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="longitude" class="block text-sm font-medium text-gray-700 mb-2">
                            Longitude
                        </label>
                        <input type="number"
                               name="longitude"
                               id="longitude"
                               value="{{ old('longitude', $bank->longitude) }}"
                               step="0.000001"
                               min="-180"
                               max="180"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-red-500 focus:border-red-500 transition-colors @error('longitude') border-red-300 @enderror"
                               placeholder="Ex: 15.2663">
                        @error('longitude')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Contact -->
            <div class="mb-8">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Informations de Contact</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="contact_phone" class="block text-sm font-medium text-gray-700 mb-2">
                            Téléphone *
                        </label>
                        <input type="tel"
                               name="contact_phone"
                               id="contact_phone"
                               value="{{ old('contact_phone', $bank->contact_phone) }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-red-500 focus:border-red-500 transition-colors @error('contact_phone') border-red-300 @enderror"
                               required>
                        @error('contact_phone')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="contact_email" class="block text-sm font-medium text-gray-700 mb-2">
                            Email *
                        </label>
                        <input type="email"
                               name="contact_email"
                               id="contact_email"
                               value="{{ old('contact_email', $bank->contact_email) }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-red-500 focus:border-red-500 transition-colors @error('contact_email') border-red-300 @enderror"
                               required>
                        @error('contact_email')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                <a href="{{ route('superadmin.banks.show', $bank) }}"
                   class="bg-gray-300 text-gray-700 px-6 py-3 rounded-lg hover:bg-gray-400 transition-colors">
                    Annuler
                </a>
                <button type="submit"
                        class="bg-red-600 text-white px-6 py-3 rounded-lg hover:bg-red-700 transition-colors">
                    <i class="fas fa-save mr-2"></i>
                    Enregistrer les modifications
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
