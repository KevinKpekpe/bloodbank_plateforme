@extends('layouts.superadmin')

@section('title', 'Créer une Banque de Sang - BloodLink')
@section('description', 'Créer une nouvelle banque de sang')
@section('page-title', 'Créer une Banque')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Créer une Banque de Sang</h1>
        <p class="mt-2 text-gray-600">Ajouter une nouvelle banque de sang à la plateforme</p>
    </div>

    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <form method="POST" action="{{ route('superadmin.banks.store') }}" class="p-6">
            @csrf

            <!-- Informations de la banque -->
            <div class="mb-8">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Informations de la Banque</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                            Nom de la banque <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 @error('name') border-red-500 @enderror"
                               placeholder="Ex: Centre National de Transfusion Sanguine">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="contact_phone" class="block text-sm font-medium text-gray-700 mb-1">
                            Téléphone <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="contact_phone" id="contact_phone" value="{{ old('contact_phone') }}" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 @error('contact_phone') border-red-500 @enderror"
                               placeholder="+243 XXX XXX XXX">
                        @error('contact_phone')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-6">
                    <label for="contact_email" class="block text-sm font-medium text-gray-700 mb-1">
                        Email de contact <span class="text-red-500">*</span>
                    </label>
                    <input type="email" name="contact_email" id="contact_email" value="{{ old('contact_email') }}" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 @error('contact_email') border-red-500 @enderror"
                           placeholder="contact@banque.com">
                    @error('contact_email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mt-6">
                    <label for="address" class="block text-sm font-medium text-gray-700 mb-1">
                        Adresse <span class="text-red-500">*</span>
                    </label>
                    <textarea name="address" id="address" rows="3" required
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 @error('address') border-red-500 @enderror"
                              placeholder="Adresse complète de la banque de sang">{{ old('address') }}</textarea>
                    @error('address')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                    <div>
                        <label for="latitude" class="block text-sm font-medium text-gray-700 mb-1">
                            Latitude
                        </label>
                        <input type="number" name="latitude" id="latitude" value="{{ old('latitude') }}" step="any"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 @error('latitude') border-red-500 @enderror"
                               placeholder="-4.4419">
                        @error('latitude')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="longitude" class="block text-sm font-medium text-gray-700 mb-1">
                            Longitude
                        </label>
                        <input type="number" name="longitude" id="longitude" value="{{ old('longitude') }}" step="any"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 @error('longitude') border-red-500 @enderror"
                               placeholder="15.2663">
                        @error('longitude')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Informations de l'administrateur -->
            <div class="mb-8">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Administrateur de la Banque</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="admin_name" class="block text-sm font-medium text-gray-700 mb-1">
                            Nom complet <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="admin_name" id="admin_name" value="{{ old('admin_name') }}" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 @error('admin_name') border-red-500 @enderror"
                               placeholder="Nom et prénom de l'administrateur">
                        @error('admin_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="admin_phone" class="block text-sm font-medium text-gray-700 mb-1">
                            Téléphone <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="admin_phone" id="admin_phone" value="{{ old('admin_phone') }}" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 @error('admin_phone') border-red-500 @enderror"
                               placeholder="+243 XXX XXX XXX">
                        @error('admin_phone')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-6">
                    <label for="admin_email" class="block text-sm font-medium text-gray-700 mb-1">
                        Email <span class="text-red-500">*</span>
                    </label>
                    <input type="email" name="admin_email" id="admin_email" value="{{ old('admin_email') }}" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 @error('admin_email') border-red-500 @enderror"
                           placeholder="admin@banque.com">
                    @error('admin_email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                    <div>
                        <label for="admin_password" class="block text-sm font-medium text-gray-700 mb-1">
                            Mot de passe <span class="text-red-500">*</span>
                        </label>
                        <input type="password" name="admin_password" id="admin_password" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 @error('admin_password') border-red-500 @enderror"
                               placeholder="Mot de passe sécurisé">
                        @error('admin_password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="admin_password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">
                            Confirmer le mot de passe <span class="text-red-500">*</span>
                        </label>
                        <input type="password" name="admin_password_confirmation" id="admin_password_confirmation" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500"
                               placeholder="Confirmer le mot de passe">
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                <a href="{{ route('superadmin.banks.index') }}"
                   class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                    Annuler
                </a>
                <button type="submit"
                        class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                    Créer la Banque
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
