@extends('layouts.admin')

@section('title', 'Détails Poche ' . $bloodBag->bag_number . ' - BloodLink')
@section('description', 'Détails complets de la poche de sang')
@section('page-title', 'Détails de la Poche')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- En-tête avec navigation -->
    <div class="mb-8">
        <div class="flex justify-between items-center">
            <div>
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
                                <span class="ml-4 text-sm font-medium text-gray-500">{{ $bloodBag->bag_number }}</span>
                            </div>
                        </li>
                    </ol>
                </nav>
                <h1 class="mt-2 text-3xl font-bold text-gray-900">{{ $bloodBag->bag_number }}</h1>
                <p class="mt-2 text-gray-600">Détails complets de la poche de sang</p>
            </div>
            <div class="flex space-x-3">
                @if($bloodBag->isAvailable())
                    <a href="{{ route('admin.blood-bags.reserve', $bloodBag) }}"
                       class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a4 4 0 118 0v4m-4 6v6m-4-6h8m-8 6h8" />
                        </svg>
                        Réserver
                    </a>
                @endif
                @if($bloodBag->isReserved() && $bloodBag->activeReservation)
                    <form method="POST" action="{{ route('admin.blood-bags.transfuse', $bloodBag) }}" class="inline">
                        @csrf
                        <button type="submit"
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-purple-600 hover:bg-purple-700"
                                onclick="return confirm('Confirmer la transfusion ?')">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                            </svg>
                            Transfuser
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Informations principales -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Informations de base -->
            <div class="bg-white shadow-sm border rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Informations de Base</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
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
                        <label class="block text-sm font-medium text-gray-700">Statut</label>
                        <p class="mt-1">
                            @php
                                $statusColors = [
                                    'available' => 'bg-green-100 text-green-800',
                                    'reserved' => 'bg-yellow-100 text-yellow-800',
                                    'transfused' => 'bg-blue-100 text-blue-800',
                                    'expired' => 'bg-red-100 text-red-800',
                                    'discarded' => 'bg-gray-100 text-gray-800'
                                ];
                                $statusLabels = [
                                    'available' => 'Disponible',
                                    'reserved' => 'Réservée',
                                    'transfused' => 'Transfusée',
                                    'expired' => 'Expirée',
                                    'discarded' => 'Jetée'
                                ];
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$bloodBag->status] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ $statusLabels[$bloodBag->status] ?? $bloodBag->status }}
                            </span>
                        </p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Date de Collecte</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $bloodBag->collection_date->format('d/m/Y') }}</p>
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
                </div>
            </div>

            <!-- Informations du donneur -->
            <div class="bg-white shadow-sm border rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Informations du Donneur</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nom Complet</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $bloodBag->donor->firstname }} {{ $bloodBag->donor->surname }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Groupe Sanguin</label>
                        <p class="mt-1">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                {{ $bloodBag->donor->bloodType->name }}
                            </span>
                        </p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Téléphone</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $bloodBag->donor->phone_number }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Adresse</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $bloodBag->donor->address }}</p>
                    </div>
                </div>
            </div>

            <!-- Réservation active -->
            @if($bloodBag->isReserved() && $bloodBag->activeReservation)
                <div class="bg-white shadow-sm border rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Réservation Active</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Patient</label>
                            <p class="mt-1 text-sm text-gray-900 font-medium">{{ $bloodBag->activeReservation->patient_name }}</p>
                            @if($bloodBag->activeReservation->patient_id)
                                <p class="mt-1 text-xs text-gray-500">ID: {{ $bloodBag->activeReservation->patient_id }}</p>
                            @endif
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Hôpital</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $bloodBag->activeReservation->hospital_name ?? 'Non spécifié' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Médecin</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $bloodBag->activeReservation->doctor_name ?? 'Non spécifié' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Niveau d'Urgence</label>
                            <p class="mt-1">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $bloodBag->activeReservation->getUrgencyColor() === 'red' ? 'bg-red-100 text-red-800' : ($bloodBag->activeReservation->getUrgencyColor() === 'yellow' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800') }}">
                                    {{ $bloodBag->activeReservation->getUrgencyLevelLabel() }}
                                </span>
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Date de Réservation</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $bloodBag->activeReservation->reservation_date->format('d/m/Y H:i') }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Expire le</label>
                            <p class="mt-1 text-sm text-gray-900">
                                {{ $bloodBag->activeReservation->expiry_date->format('d/m/Y H:i') }}
                                @if($bloodBag->activeReservation->isExpiringSoon())
                                    <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                        Expire bientôt
                                    </span>
                                @endif
                            </p>
                        </div>
                    </div>
                    @if($bloodBag->activeReservation->notes)
                        <div class="mt-4">
                            <label class="block text-sm font-medium text-gray-700">Notes</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $bloodBag->activeReservation->notes }}</p>
                        </div>
                    @endif
                </div>
            @endif

            <!-- Historique des mouvements -->
            <div class="bg-white shadow-sm border rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Historique des Mouvements</h3>
                @if($bloodBag->movements->count() > 0)
                    <div class="flow-root">
                        <ul class="-mb-8">
                            @foreach($bloodBag->movements->sortByDesc('movement_date') as $movement)
                                <li>
                                    <div class="relative pb-8">
                                        @if(!$loop->last)
                                            <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                                        @endif
                                        <div class="relative flex space-x-3">
                                            <div>
                                                <span class="h-8 w-8 rounded-full flex items-center justify-center ring-8 ring-white
                                                    {{ $movement->isInbound() ? 'bg-green-500' : ($movement->isOutbound() ? 'bg-red-500' : 'bg-blue-500') }}">
                                                    <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        @if($movement->isInbound())
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12" />
                                                        @elseif($movement->isOutbound())
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6" />
                                                        @else
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a4 4 0 118 0v4m-4 6v6m-4-6h8m-8 6h8" />
                                                        @endif
                                                    </svg>
                                                </span>
                                            </div>
                                            <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                                <div>
                                                    <p class="text-sm text-gray-500">
                                                        {{ $movement->getMovementTypeLabel() }}
                                                        @if($movement->recipient_name)
                                                            pour <span class="font-medium text-gray-900">{{ $movement->recipient_name }}</span>
                                                        @endif
                                                    </p>
                                                    @if($movement->reason)
                                                        <p class="text-sm text-gray-500">{{ $movement->reason }}</p>
                                                    @endif
                                                </div>
                                                <div class="text-right text-sm whitespace-nowrap text-gray-500">
                                                    <time datetime="{{ $movement->movement_date->format('Y-m-d H:i:s') }}">
                                                        {{ $movement->movement_date->format('d/m/Y H:i') }}
                                                    </time>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @else
                    <p class="text-gray-500 text-center py-4">Aucun mouvement enregistré</p>
                @endif
            </div>
        </div>

        <!-- Actions et informations complémentaires -->
        <div class="space-y-6">
            <!-- Actions -->
            <div class="bg-white shadow-sm border rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Actions</h3>
                <div class="space-y-3">
                    @if($bloodBag->isAvailable())
                        <a href="{{ route('admin.blood-bags.reserve', $bloodBag) }}"
                           class="w-full flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a4 4 0 118 0v4m-4 6v6m-4-6h8m-8 6h8" />
                            </svg>
                            Réserver cette poche
                        </a>
                    @endif

                    @if($bloodBag->isReserved() && $bloodBag->activeReservation)
                        <form method="POST" action="{{ route('admin.blood-bags.transfuse', $bloodBag) }}">
                            @csrf
                            <button type="submit"
                                    class="w-full flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-purple-600 hover:bg-purple-700"
                                    onclick="return confirm('Confirmer la transfusion ?')">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                                </svg>
                                Effectuer la transfusion
                            </button>
                        </form>

                        <button type="button"
                                class="w-full flex items-center justify-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
                                onclick="showCancelModal()">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            Annuler la réservation
                        </button>
                    @endif

                    @if($bloodBag->isAvailable() || $bloodBag->isReserved())
                        <button type="button"
                                class="w-full flex items-center justify-center px-4 py-2 border border-red-300 text-sm font-medium rounded-md text-red-700 bg-white hover:bg-red-50"
                                onclick="showDiscardModal()">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            Jeter cette poche
                        </button>
                    @endif
                </div>
            </div>

            <!-- Informations complémentaires -->
            <div class="bg-white shadow-sm border rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Informations Complémentaires</h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Don d'origine</label>
                        <p class="mt-1 text-sm text-gray-900">
                            @if($bloodBag->donation)
                                Don #{{ $bloodBag->donation->id }} du {{ $bloodBag->donation->donation_date->format('d/m/Y') }}
                            @else
                                Non spécifié
                            @endif
                        </p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Banque</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $bloodBag->bank->name }}</p>
                    </div>
                    @if($bloodBag->notes)
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Notes</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $bloodBag->notes }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal pour annuler la réservation -->
@if($bloodBag->isReserved() && $bloodBag->activeReservation)
    <div id="cancelModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Annuler la réservation</h3>
                <form method="POST" action="{{ route('admin.blood-bags.cancel-reservation', $bloodBag) }}">
                    @csrf
                    <div class="mb-4">
                        <label for="reason" class="block text-sm font-medium text-gray-700 mb-2">Raison de l'annulation</label>
                        <textarea name="reason" id="reason" rows="3" class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500" required></textarea>
                    </div>
                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="hideCancelModal()" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200">
                            Annuler
                        </button>
                        <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-md hover:bg-red-700">
                            Confirmer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endif

<!-- Modal pour jeter la poche -->
<div id="discardModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Jeter la poche</h3>
            <form method="POST" action="{{ route('admin.blood-bags.discard', $bloodBag) }}">
                @csrf
                <div class="mb-4">
                    <label for="discard_reason" class="block text-sm font-medium text-gray-700 mb-2">Raison de la destruction</label>
                    <textarea name="reason" id="discard_reason" rows="3" class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500" required></textarea>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="hideDiscardModal()" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200">
                        Annuler
                    </button>
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-md hover:bg-red-700">
                        Confirmer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function showCancelModal() {
    document.getElementById('cancelModal').classList.remove('hidden');
}

function hideCancelModal() {
    document.getElementById('cancelModal').classList.add('hidden');
}

function showDiscardModal() {
    document.getElementById('discardModal').classList.remove('hidden');
}

function hideDiscardModal() {
    document.getElementById('discardModal').classList.add('hidden');
}
</script>
@endsection
