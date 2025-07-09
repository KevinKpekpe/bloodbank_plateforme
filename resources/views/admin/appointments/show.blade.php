@extends('layouts.admin')

@section('title', 'Détails du Rendez-vous - BloodLink')
@section('description', 'Détails et gestion d\'un rendez-vous de don de sang')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Détails du Rendez-vous</h1>
            <p class="mt-2 text-gray-600">Gestion du rendez-vous de don de sang</p>
        </div>
        <a href="{{ route('admin.appointments.index') }}" class="text-red-600 hover:text-red-700">
            ← Retour à la liste
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
                <!-- Informations du donneur -->
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Informations du donneur</h3>
                    <dl class="space-y-3">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Nom complet</dt>
                            <dd class="text-sm text-gray-900">{{ $appointment->donor->name }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Email</dt>
                            <dd class="text-sm text-gray-900">{{ $appointment->donor->email }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Téléphone</dt>
                            <dd class="text-sm text-gray-900">{{ $appointment->donor->phone }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Date de naissance</dt>
                            <dd class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($appointment->donor->birth_date)->format('d/m/Y') }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Groupe sanguin</dt>
                            <dd class="text-sm text-gray-900">
                                @if($appointment->donor->bloodType)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        {{ $appointment->donor->bloodType->name }}
                                    </span>
                                @else
                                    <span class="text-gray-500">Non renseigné</span>
                                @endif
                            </dd>
                        </div>
                    </dl>
                </div>

                <!-- Informations du rendez-vous -->
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
                                        En attente de confirmation par l'administrateur
                                        @break
                                    @case('confirmed')
                                        Confirmé - Le donneur peut se présenter
                                        @break
                                    @case('cancelled')
                                        Annulé
                                        @if($appointment->rejection_reason)
                                            <div class="mt-1 text-red-600">
                                                Raison : {{ $appointment->rejection_reason }}
                                            </div>
                                        @endif
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
                            <dt class="text-sm font-medium text-gray-500">Notes du donneur</dt>
                            <dd class="text-sm text-gray-900">{{ $appointment->notes }}</dd>
                        </div>
                        @endif
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Créé le</dt>
                            <dd class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($appointment->created_at)->format('d/m/Y à H:i') }}</dd>
                        </div>
                    </dl>
                </div>
            </div>

            <!-- Don associé -->
            @if($appointment->donation)
            <div class="mt-8 pt-6 border-t border-gray-200">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Don associé</h3>
                <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <dt class="text-sm font-medium text-green-800">Groupe sanguin</dt>
                            <dd class="text-sm text-green-700">{{ $appointment->donation->bloodType->name }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-green-800">Volume</dt>
                            <dd class="text-sm text-green-700">{{ $appointment->donation->volume }}L</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-green-800">Statut</dt>
                            <dd class="text-sm text-green-700">{{ ucfirst($appointment->donation->status) }}</dd>
                        </div>
                    </div>
                    @if($appointment->donation->notes)
                    <div class="mt-3">
                        <dt class="text-sm font-medium text-green-800">Notes</dt>
                        <dd class="text-sm text-green-700">{{ $appointment->donation->notes }}</dd>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Actions -->
            <div class="mt-8 pt-6 border-t border-gray-200">
                <div class="flex justify-between items-center">
                    <div class="flex space-x-4">
                        @if($appointment->status === 'pending')
                            <form method="POST" action="{{ route('admin.appointments.confirm', $appointment->id) }}" class="inline">
                                @csrf
                                <button type="submit"
                                        class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                                    Confirmer le rendez-vous
                                </button>
                            </form>
                            <button onclick="showRejectModal()"
                                    class="px-4 py-2 border border-red-300 text-red-700 rounded-md hover:bg-red-50">
                                Rejeter le rendez-vous
                            </button>
                        @endif

                        @if($appointment->status === 'confirmed')
                            <button onclick="showCompleteModal()"
                                    class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                                Finaliser le don
                            </button>
                        @endif
                    </div>
                    <a href="{{ route('admin.appointments.index') }}"
                       class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">
                        Retour à la liste
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de rejet -->
<div id="rejectModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Rejeter le rendez-vous</h3>
            <form method="POST" action="{{ route('admin.appointments.reject', $appointment->id) }}">
                @csrf
                <div class="mb-4">
                    <label for="rejection_reason" class="block text-sm font-medium text-gray-700 mb-2">
                        Raison du rejet *
                    </label>
                    <textarea id="rejection_reason" name="rejection_reason" rows="3" required
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-red-500 focus:border-red-500"
                              placeholder="Expliquez la raison du rejet..."></textarea>
                </div>
                <div class="flex justify-end space-x-4">
                    <button type="button" onclick="hideRejectModal()"
                            class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                        Annuler
                    </button>
                    <button type="submit"
                            class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                        Rejeter
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal de finalisation -->
<div id="completeModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Finaliser le don</h3>
            <form method="POST" action="{{ route('admin.appointments.complete', $appointment->id) }}">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Groupe sanguin du donneur
                    </label>
                    <input type="text" value="{{ $appointment->donor->bloodType->name ?? 'Non renseigné' }}" readonly
                           class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100">
                </div>
                <div class="mb-4">
                    <label for="volume" class="block text-sm font-medium text-gray-700 mb-2">
                        Volume (L) *
                    </label>
                    <input type="number" id="volume" name="volume" step="0.1" min="0.3" max="0.5" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-red-500 focus:border-red-500"
                           placeholder="0.45">
                </div>
                <div class="mb-4">
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                        Notes (optionnel)
                    </label>
                    <textarea id="notes" name="notes" rows="2"
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-red-500 focus:border-red-500"
                              placeholder="Observations, complications, etc."></textarea>
                </div>
                <div class="flex justify-end space-x-4">
                    <button type="button" onclick="hideCompleteModal()"
                            class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                        Annuler
                    </button>
                    <button type="submit"
                            class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                        Finaliser
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function showRejectModal() {
    document.getElementById('rejectModal').classList.remove('hidden');
}

function hideRejectModal() {
    document.getElementById('rejectModal').classList.add('hidden');
}

function showCompleteModal() {
    document.getElementById('completeModal').classList.remove('hidden');
}

function hideCompleteModal() {
    document.getElementById('completeModal').classList.add('hidden');
}
</script>
@endsection
