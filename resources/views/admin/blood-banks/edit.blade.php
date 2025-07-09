@extends('layouts.app')

@section('title', 'Modifier la Banque de Sang - BloodLink')
@section('description', 'Modifier une banque de sang dans le système BloodLink')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- En-tête -->
        <div class="mb-8">
            <div class="flex items-center">
                <a href="{{ route('admin.blood-banks.index') }}"
                   class="text-gray-400 hover:text-gray-600 transition-colors mr-4">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </a>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Modifier la Banque de Sang</h1>
                    <p class="mt-2 text-gray-600">Modifiez les informations de {{ $bloodBank->name }}</p>
                </div>
            </div>
        </div>

        <!-- Formulaire -->
        <div class="bg-white shadow-lg rounded-lg p-8">
            <form method="POST" action="{{ route('admin.blood-banks.update', $bloodBank->id) }}" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Informations de base -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            Nom de la banque *
                        </label>
                        <input type="text"
                               name="name"
                               id="name"
                               value="{{ old('name', $bloodBank->name) }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-red-500 focus:border-red-500 transition-colors @error('name') border-red-300 @enderror"
                               placeholder="Ex: Centre de transfusion sanguine de Paris"
                               required>
                        @error('name')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="admin_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Administrateur *
                        </label>
                        <select name="admin_id"
                                id="admin_id"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-red-500 focus:border-red-500 transition-colors @error('admin_id') border-red-300 @enderror"
                                required>
                            <option value="">Sélectionner un administrateur</option>
                            @foreach(\App\Models\User::whereHas('role', function($q) { $q->where('name', 'blood_bank'); })->get() as $user)
                                <option value="{{ $user->id }}" @if(old('admin_id', $bloodBank->admin_id) == $user->id) selected @endif>
                                    {{ $user->name }} ({{ $user->email }})
                                </option>
                            @endforeach
                        </select>
                        @error('admin_id')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                        Description
                    </label>
                    <textarea name="description"
                              id="description"
                              rows="3"
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-red-500 focus:border-red-500 transition-colors @error('description') border-red-300 @enderror"
                              placeholder="Description de la banque de sang...">{{ old('description', $bloodBank->description) }}</textarea>
                    @error('description')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Adresse -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="md:col-span-2">
                        <label for="address" class="block text-sm font-medium text-gray-700 mb-2">
                            Adresse *
                        </label>
                        <input type="text"
                               name="address"
                               id="address"
                               value="{{ old('address', $bloodBank->address) }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-red-500 focus:border-red-500 transition-colors @error('address') border-red-300 @enderror"
                               placeholder="123 Rue de la Paix"
                               required>
                        @error('address')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="city" class="block text-sm font-medium text-gray-700 mb-2">
                            Ville *
                        </label>
                        <input type="text"
                               name="city"
                               id="city"
                               value="{{ old('city', $bloodBank->city) }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-red-500 focus:border-red-500 transition-colors @error('city') border-red-300 @enderror"
                               placeholder="Paris"
                               required>
                        @error('city')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="postal_code" class="block text-sm font-medium text-gray-700 mb-2">
                            Code postal *
                        </label>
                        <input type="text"
                               name="postal_code"
                               id="postal_code"
                               value="{{ old('postal_code', $bloodBank->postal_code) }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-red-500 focus:border-red-500 transition-colors @error('postal_code') border-red-300 @enderror"
                               placeholder="75001"
                               required>
                        @error('postal_code')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="partnership_level" class="block text-sm font-medium text-gray-700 mb-2">
                            Niveau de partenariat
                        </label>
                        <select name="partnership_level"
                                id="partnership_level"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-red-500 focus:border-red-500 transition-colors @error('partnership_level') border-red-300 @enderror">
                            <option value="basic" @if(old('partnership_level', $bloodBank->partnership_level) == 'basic') selected @endif>Basique</option>
                            <option value="premium" @if(old('partnership_level', $bloodBank->partnership_level) == 'premium') selected @endif>Premium</option>
                            <option value="premium_plus" @if(old('partnership_level', $bloodBank->partnership_level) == 'premium_plus') selected @endif>Premium Plus</option>
                        </select>
                        @error('partnership_level')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Coordonnées GPS -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="latitude" class="block text-sm font-medium text-gray-700 mb-2">
                            Latitude
                        </label>
                        <input type="number"
                               name="latitude"
                               id="latitude"
                               value="{{ old('latitude', $bloodBank->latitude) }}"
                               step="any"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-red-500 focus:border-red-500 transition-colors @error('latitude') border-red-300 @enderror"
                               placeholder="48.8566">
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
                               value="{{ old('longitude', $bloodBank->longitude) }}"
                               step="any"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-red-500 focus:border-red-500 transition-colors @error('longitude') border-red-300 @enderror"
                               placeholder="2.3522">
                        @error('longitude')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Contact -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                            Téléphone *
                        </label>
                        <input type="tel"
                               name="phone"
                               id="phone"
                               value="{{ old('phone', $bloodBank->phone) }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-red-500 focus:border-red-500 transition-colors @error('phone') border-red-300 @enderror"
                               placeholder="+33 1 23 45 67 89"
                               required>
                        @error('phone')
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
                               value="{{ old('email', $bloodBank->email) }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-red-500 focus:border-red-500 transition-colors @error('email') border-red-300 @enderror"
                               placeholder="contact@banque-sang.fr"
                               required>
                        @error('email')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Statut -->
                <div class="flex items-center">
                    <input type="checkbox"
                           name="is_active"
                           id="is_active"
                           value="1"
                           @if(old('is_active', $bloodBank->is_active)) checked @endif
                           class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded">
                    <label for="is_active" class="ml-2 block text-sm text-gray-900">
                        Banque de sang active
                    </label>
                </div>

                <!-- Statistiques de la banque -->
                <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                    <h3 class="text-sm font-medium text-gray-900 mb-3">Statistiques actuelles</h3>
                    <div class="grid grid-cols-3 gap-4">
                        <div class="text-center">
                            <div class="text-2xl font-bold text-red-600">{{ $bloodBank->bloodStocks->sum('quantity_ml') }}</div>
                            <div class="text-sm text-gray-600">ml en stock</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-red-600">{{ $bloodBank->bloodStocks->count() }}</div>
                            <div class="text-sm text-gray-600">Types de sang</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-red-600">{{ $bloodBank->donations()->count() }}</div>
                            <div class="text-sm text-gray-600">Dons reçus</div>
                        </div>
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
                                    <li>L'administrateur doit avoir le rôle "blood_bank"</li>
                                    <li>Les coordonnées GPS sont utilisées pour la géolocalisation</li>
                                    <li>La modification peut affecter les stocks existants</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Boutons d'action -->
                <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                    <a href="{{ route('admin.blood-banks.index') }}"
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
