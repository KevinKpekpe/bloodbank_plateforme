@extends('layouts.app')

@section('title', 'Détails du Rendez-vous - BloodLink')
@section('description', 'Détails de votre rendez-vous de don de sang')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Détails du Rendez-vous</h1>
            <p class="mt-2 text-gray-600">Informations complètes sur votre rendez-vous</p>
        </div>
        <a href="{{ route('donor.appointments.index') }}" class="text-red-600 hover:text-red-700">
            ← Retour aux rendez-vous
        </a>
    </div>

    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <!-- En-tête avec statut -->
        <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
            <div class="flex justify-between items-center">
                <h2 class="text-lg font-semibold text-gray-900">
                    Rendez-vous du {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('d/m/Y à H:i') }}
                </h2>
                @switch($appointment->status)
                    @case('pending')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                            En attente de confirmation
                        </span>
                        @break
                    @case('confirmed')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                            Confirmé
                        </span>
                        @break
                    @case('cancelled')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                            Annulé
                        </span>
                        @break
                    @case('completed')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                            Terminé
                        </span>
                        @break
                    @default
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                            {{ ucfirst($appointment->status) }}
                        </span>
                @endswitch
            </div>
        </div>

        <!-- Informations du rendez-vous -->
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Informations de base -->
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Informations du rendez-vous</h3>
                    <dl class="space-y-3">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Date et heure</dt>
                            <dd class="text-sm text-gray-900">
                                {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('d/m/Y à H:i') }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Statut</dt>
                            <dd class="text-sm text-gray-900">
                                @switch($appointment->status)
                                    @case('pending')
                                        En attente de confirmation par la banque
                                        @break
                                    @case('confirmed')
                                        Confirmé - Préparez-vous pour votre don
                                        @break
                                    @case('cancelled')
                                        Annulé
                                        @break
                                    @case('completed')
                                        Terminé avec succès
                                        @break
                                    @default
                                        {{ ucfirst($appointment->status) }}
                                @endswitch
                            </dd>
                        </div>
                        @if($appointment->notes)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Notes</dt>
                            <dd class="text-sm text-gray-900">{{ $appointment->notes }}</dd>
                        </div>
                        @endif
                    </dl>
                </div>

                <!-- Informations de la banque -->
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Banque de sang</h3>
                    <dl class="space-y-3">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Nom</dt>
                            <dd class="text-sm text-gray-900">{{ $appointment->bank->name }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Adresse</dt>
                            <dd class="text-sm text-gray-900">{{ $appointment->bank->address }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Téléphone</dt>
                            <dd class="text-sm text-gray-900">{{ $appointment->bank->contact_phone }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Email</dt>
                            <dd class="text-sm text-gray-900">{{ $appointment->bank->contact_email }}</dd>
                        </div>
                    </dl>
                </div>
            </div>

            <!-- Don associé -->
            @if($appointment->donation)
            <div class="mt-8 pt-6 border-t border-gray-200">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Don associé</h3>
                <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="text-green-800 font-medium">Don réalisé avec succès</span>
                    </div>
                    <p class="text-green-700 text-sm mt-1">
                        Volume : {{ $appointment->donation->volume }}L |
                        Statut : {{ ucfirst($appointment->donation->status) }}
                    </p>
                </div>
            </div>
            @endif

            <!-- Actions -->
            <div class="mt-8 pt-6 border-t border-gray-200">
                <div class="flex justify-between items-center">
                    <div class="flex space-x-4">
                        @if(in_array($appointment->status, ['pending', 'confirmed']))
                            <form method="POST" action="{{ route('donor.appointments.cancel', $appointment) }}" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="px-4 py-2 border border-red-300 text-red-700 rounded-md hover:bg-red-50"
                                        onclick="return confirm('Êtes-vous sûr de vouloir annuler ce rendez-vous ?')">
                                    Annuler le rendez-vous
                                </button>
                            </form>
                        @endif
                    </div>
                    <a href="{{ route('donor.appointments.index') }}"
                       class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">
                        Retour à la liste
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
