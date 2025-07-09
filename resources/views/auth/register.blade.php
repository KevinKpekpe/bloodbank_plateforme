@extends('layouts.app')

@section('title', 'Inscription Donneur - BloodLink')
@section('description', 'Créez votre compte donneur sur BloodLink')

@section('content')
<div class="min-h-screen bg-gray-50 py-12 sm:px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-2xl">
        <div class="flex justify-center">
            <div class="w-12 h-12 bg-red-600 rounded-lg flex items-center justify-center">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                </svg>
            </div>
        </div>
        <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
            Devenez donneur de sang
        </h2>
        <p class="mt-2 text-center text-sm text-gray-600">
            Ou
            <a href="{{ route('login') }}" class="font-medium text-red-600 hover:text-red-500">
                connectez-vous à votre compte existant
            </a>
        </p>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-2xl">
        <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">
            <form class="space-y-6" action="{{ route('register') }}" method="POST">
                @csrf

                <!-- Informations de compte -->
                <div class="border-b border-gray-200 pb-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Informations de compte</h3>

                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">
                                Nom d'utilisateur
                            </label>
                            <div class="mt-1">
                                <input id="name" name="name" type="text" required
                                    class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md placeholder-gray-400 focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm @error('name') border-red-500 @enderror"
                                    value="{{ old('name') }}">
                            </div>
                            @error('name')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700">
                                Adresse email
                            </label>
                            <div class="mt-1">
                                <input id="email" name="email" type="email" autocomplete="email" required
                                    class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md placeholder-gray-400 focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm @error('email') border-red-500 @enderror"
                                    value="{{ old('email') }}">
                            </div>
                            @error('email')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700">
                                Mot de passe
                            </label>
                            <div class="mt-1">
                                <input id="password" name="password" type="password" required
                                    class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md placeholder-gray-400 focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm @error('password') border-red-500 @enderror">
                            </div>
                            @error('password')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700">
                                Confirmer le mot de passe
                            </label>
                            <div class="mt-1">
                                <input id="password_confirmation" name="password_confirmation" type="password" required
                                    class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md placeholder-gray-400 focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Informations personnelles -->
                <div class="border-b border-gray-200 pb-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Informations personnelles</h3>

                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <div>
                            <label for="firstname" class="block text-sm font-medium text-gray-700">
                                Prénom
                            </label>
                            <div class="mt-1">
                                <input id="firstname" name="firstname" type="text" required
                                    class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md placeholder-gray-400 focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm @error('firstname') border-red-500 @enderror"
                                    value="{{ old('firstname') }}">
                            </div>
                            @error('firstname')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="surname" class="block text-sm font-medium text-gray-700">
                                Nom de famille
                            </label>
                            <div class="mt-1">
                                <input id="surname" name="surname" type="text" required
                                    class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md placeholder-gray-400 focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm @error('surname') border-red-500 @enderror"
                                    value="{{ old('surname') }}">
                            </div>
                            @error('surname')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="blood_type_id" class="block text-sm font-medium text-gray-700">
                                Groupe sanguin
                            </label>
                            <div class="mt-1">
                                <select id="blood_type_id" name="blood_type_id" required
                                    class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md placeholder-gray-400 focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm @error('blood_type_id') border-red-500 @enderror">
                                    <option value="">Sélectionnez votre groupe sanguin</option>
                                    @foreach($bloodTypes as $bloodType)
                                        <option value="{{ $bloodType->id }}" {{ old('blood_type_id') == $bloodType->id ? 'selected' : '' }}>
                                            {{ $bloodType->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @error('blood_type_id')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="gender" class="block text-sm font-medium text-gray-700">
                                Genre
                            </label>
                            <div class="mt-1">
                                <select id="gender" name="gender" required
                                    class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md placeholder-gray-400 focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm @error('gender') border-red-500 @enderror">
                                    <option value="">Sélectionnez votre genre</option>
                                    <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Masculin</option>
                                    <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Féminin</option>
                                    <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Autre</option>
                                </select>
                            </div>
                            @error('gender')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="birthdate" class="block text-sm font-medium text-gray-700">
                                Date de naissance
                            </label>
                            <div class="mt-1">
                                <input id="birthdate" name="birthdate" type="date" required
                                    class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md placeholder-gray-400 focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm @error('birthdate') border-red-500 @enderror"
                                    value="{{ old('birthdate') }}">
                            </div>
                            @error('birthdate')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="phone_number" class="block text-sm font-medium text-gray-700">
                                Numéro de téléphone
                            </label>
                            <div class="mt-1">
                                <input id="phone_number" name="phone_number" type="tel" required
                                    class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md placeholder-gray-400 focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm @error('phone_number') border-red-500 @enderror"
                                    value="{{ old('phone_number') }}">
                            </div>
                            @error('phone_number')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-6">
                        <label for="address" class="block text-sm font-medium text-gray-700">
                            Adresse complète
                        </label>
                        <div class="mt-1">
                            <textarea id="address" name="address" rows="3" required
                                class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md placeholder-gray-400 focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm @error('address') border-red-500 @enderror"
                                placeholder="Votre adresse complète">{{ old('address') }}</textarea>
                        </div>
                        @error('address')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <button type="submit"
                        class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                        Créer mon compte donneur
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
