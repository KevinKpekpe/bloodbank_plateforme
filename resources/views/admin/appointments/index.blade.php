@extends('layouts.admin')

@section('title', 'Gestion des Rendez-vous - BloodLink')
@section('description', 'Gestion des rendez-vous de don de sang')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Gestion des Rendez-vous</h1>
            <p class="mt-2 text-gray-600">Validez et gérez les rendez-vous de don de sang</p>
        </div>
        <div class="flex space-x-4">
            <a href="{{ route('admin.appointments.calendar') }}"
               class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                Calendrier
            </a>
            <a href="{{ route('admin.appointments.statistics') }}"
               class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                Statistiques
            </a>
        </div>
    </div>

    <!-- Filtres -->
    <div class="bg-white p-6 rounded-lg shadow-md mb-8">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Filtres</h2>
        <form method="GET" action="{{ route('admin.appointments.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Statut</label>
                <select id="status" name="status" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                    <option value="">Tous les statuts</option>
                    @foreach($statuses as $status)
                        <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                            @switch($status)
                                @case('pending')
                                    En attente
                                    @break
                                @case('confirmed')
                                    Confirmé
                                    @break
                                @case('cancelled')
                                    Annulé
                                    @break
                                @case('completed')
                                    Terminé
                                    @break
                                @default
                                    {{ ucfirst($status) }}
                            @endswitch
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="date_from" class="block text-sm font-medium text-gray-700 mb-1">Date début</label>
                <input type="date" id="date_from" name="date_from"
                       value="{{ request('date_from') }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md">
            </div>

            <div>
                <label for="date_to" class="block text-sm font-medium text-gray-700 mb-1">Date fin</label>
                <input type="date" id="date_to" name="date_to"
                       value="{{ request('date_to') }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md">
            </div>

            <div class="flex items-end space-x-4">
                <a href="{{ route('admin.appointments.index') }}"
                   class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                    Réinitialiser
                </a>
                <button type="submit"
                        class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                    Filtrer
                </button>
            </div>
        </form>
    </div>

    <!-- Liste des rendez-vous -->
    @if($appointments->count() > 0)
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Donneur
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Date & Heure
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Statut
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Don
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($appointments as $appointment)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <div class="h-10 w-10 rounded-full bg-red-100 flex items-center justify-center">
                                                <span class="text-sm font-medium text-red-600">
                                                    {{ strtoupper(substr($appointment->donor->firstname, 0, 1) . substr($appointment->donor->surname, 0, 1)) }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $appointment->donor->firstname }} {{ $appointment->donor->surname }}
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                {{ $appointment->donor->user->email }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('d/m/Y') }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('H:i') }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @switch($appointment->status)
                                        @case('pending')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                En attente
                                            </span>
                                            @break
                                        @case('confirmed')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                Confirmé
                                            </span>
                                            @break
                                        @case('cancelled')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                Annulé
                                            </span>
                                            @break
                                        @case('completed')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                Terminé
                                            </span>
                                            @break
                                        @default
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                {{ ucfirst($appointment->status) }}
                                            </span>
                                    @endswitch
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($appointment->donation)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Don réalisé
                                        </span>
                                    @else
                                        <span class="text-sm text-gray-500">Aucun don</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('admin.appointments.show', $appointment->id) }}"
                                           class="text-blue-600 hover:text-blue-900">
                                            Voir
                                        </a>

                                        @if($appointment->status === 'pending')
                                            <form method="POST" action="{{ route('admin.appointments.confirm', $appointment->id) }}" class="inline">
                                                @csrf
                                                <button type="submit" class="text-green-600 hover:text-green-900">
                                                    Confirmer
                                                </button>
                                            </form>
                                            <button onclick="showRejectModal({{ $appointment->id }})" class="text-red-600 hover:text-red-900">
                                                Rejeter
                                            </button>
                                        @endif

                                        @if($appointment->status === 'confirmed')
                                            <button onclick="showCompleteModal({{ $appointment->id }})" class="text-blue-600 hover:text-blue-900">
                                                Terminer
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                {{ $appointments->links() }}
            </div>
        </div>
    @else
        <div class="bg-white shadow-md rounded-lg p-8 text-center">
            <div class="text-gray-500">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">Aucun rendez-vous</h3>
                <p class="mt-1 text-sm text-gray-500">
                    Aucun rendez-vous ne correspond aux critères de recherche.
                </p>
            </div>
        </div>
    @endif
</div>

<!-- Modal de rejet -->
<div id="rejectModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Rejeter le rendez-vous</h3>
            <form id="rejectForm" method="POST">
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
            <form id="completeForm" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Groupe sanguin du donneur
                    </label>
                    <input type="text" id="blood_type" readonly
                           class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100">
                </div>
                <div class="mb-4">
                    <label for="donation_volume" class="block text-sm font-medium text-gray-700 mb-2">
                        Volume collecté (en litres) *
                    </label>
                    <input type="number" id="donation_volume" name="donation_volume" step="0.01" min="0.1" max="1" required
                           value="0.45"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div class="mb-4">
                    <label for="donation_notes" class="block text-sm font-medium text-gray-700 mb-2">
                        Notes sur le don
                    </label>
                    <textarea id="donation_notes" name="donation_notes" rows="3"
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                              placeholder="Notes sur la qualité du sang, etc..."></textarea>
                </div>
                <div class="flex justify-end space-x-4">
                    <button type="button" onclick="hideCompleteModal()"
                            class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                        Annuler
                    </button>
                    <button type="submit"
                            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        Finaliser
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function showRejectModal(appointmentId) {
    document.getElementById('rejectForm').action = `/admin/appointments/${appointmentId}/reject`;
    document.getElementById('rejectModal').classList.remove('hidden');
}

function hideRejectModal() {
    document.getElementById('rejectModal').classList.add('hidden');
}

function showCompleteModal(appointmentId) {
    document.getElementById('completeForm').action = `/admin/appointments/${appointmentId}/complete`;
    document.getElementById('completeModal').classList.remove('hidden');
}

function hideCompleteModal() {
    document.getElementById('completeModal').classList.add('hidden');
}
</script>
@endsection
