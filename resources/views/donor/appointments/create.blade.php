@extends('layouts.app')

@section('title', 'Nouveau Rendez-vous - BloodLink')
@section('description', 'Prendre un rendez-vous pour un don de sang')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Nouveau Rendez-vous</h1>
        <p class="mt-2 text-gray-600">Planifiez votre prochain don de sang</p>
    </div>

    <div class="bg-white shadow-md rounded-lg p-6">
        <form action="{{ route('donor.appointments.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Banque de sang -->
                <div class="md:col-span-2">
                    <label for="bank_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Banque de sang *
                    </label>
                    <select id="bank_id" name="bank_id" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-red-500 focus:border-red-500 @error('bank_id') border-red-500 @enderror">
                        <option value="">Sélectionnez une banque de sang</option>
                        @foreach($banks as $bank)
                            <option value="{{ $bank->id }}" {{ old('bank_id') == $bank->id ? 'selected' : '' }}>
                                {{ $bank->name }} - {{ $bank->address }}
                            </option>
                        @endforeach
                    </select>
                    @error('bank_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Date du rendez-vous -->
                <div>
                    <label for="appointment_date" class="block text-sm font-medium text-gray-700 mb-2">
                        Date du rendez-vous *
                    </label>
                    <input type="date" id="appointment_date" name="appointment_date" required
                           min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-red-500 focus:border-red-500 @error('appointment_date') border-red-500 @enderror"
                           value="{{ old('appointment_date') }}">
                    @error('appointment_date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Heure du rendez-vous -->
                <div>
                    <label for="appointment_time" class="block text-sm font-medium text-gray-700 mb-2">
                        Heure du rendez-vous *
                    </label>
                    <input type="time" id="appointment_time" name="appointment_time" required
                           min="08:00" max="17:30"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-red-500 focus:border-red-500 @error('appointment_time') border-red-500 @enderror"
                           value="{{ old('appointment_time') }}">
                    <p class="mt-1 text-sm text-gray-500">Heures d'ouverture : 8h00 - 18h00</p>
                    @error('appointment_time')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Notes -->
                <div class="md:col-span-2">
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                        Notes (optionnel)
                    </label>
                    <textarea id="notes" name="notes" rows="3"
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-red-500 focus:border-red-500 @error('notes') border-red-500 @enderror"
                              placeholder="Informations supplémentaires, préférences, etc.">{{ old('notes') }}</textarea>
                    @error('notes')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Informations importantes -->
            <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                <h3 class="text-sm font-medium text-blue-900 mb-2">Informations importantes</h3>
                <ul class="text-sm text-blue-800 space-y-1">
                    <li>• Assurez-vous d'être en bonne santé le jour du rendez-vous</li>
                    <li>• Évitez de manger un repas copieux 2 heures avant le don</li>
                    <li>• Buvez suffisamment d'eau avant et après le don</li>
                    <li>• Apportez une pièce d'identité valide</li>
                    <li>• Le don dure environ 30 minutes</li>
                </ul>
            </div>

            <!-- Boutons -->
            <div class="mt-8 flex justify-end space-x-4">
                <a href="{{ route('donor.appointments.index') }}"
                   class="px-6 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                    Annuler
                </a>
                <button type="submit"
                        class="px-6 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500">
                    Créer le rendez-vous
                </button>
            </div>
        </form>
    </div>
</div>
@endsection