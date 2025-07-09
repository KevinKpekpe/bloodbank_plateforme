@extends('layouts.admin')

@section('title', 'Calendrier des Rendez-vous - BloodLink')
@section('description', 'Calendrier des rendez-vous de don de sang')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet">
@endpush

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Calendrier des Rendez-vous</h1>
            <p class="mt-2 text-gray-600">Vue calendrier des rendez-vous de don de sang</p>
        </div>
        <a href="{{ route('admin.appointments.index') }}" class="text-red-600 hover:text-red-700">
            ← Retour à la liste
        </a>
    </div>

    <!-- Légende -->
    <div class="bg-white p-4 rounded-lg shadow-md mb-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-3">Légende</h2>
        <div class="flex flex-wrap gap-4">
            <div class="flex items-center">
                <div class="w-4 h-4 bg-yellow-400 rounded mr-2"></div>
                <span class="text-sm text-gray-700">En attente</span>
            </div>
            <div class="flex items-center">
                <div class="w-4 h-4 bg-green-400 rounded mr-2"></div>
                <span class="text-sm text-gray-700">Confirmé</span>
            </div>
            <div class="flex items-center">
                <div class="w-4 h-4 bg-blue-400 rounded mr-2"></div>
                <span class="text-sm text-gray-700">Terminé</span>
            </div>
            <div class="flex items-center">
                <div class="w-4 h-4 bg-red-400 rounded mr-2"></div>
                <span class="text-sm text-gray-700">Annulé</span>
            </div>
        </div>
    </div>

    <!-- Calendrier -->
    <div class="bg-white p-6 rounded-lg shadow-md">
        <div id="calendar"></div>
    </div>
</div>

<!-- Modal de détails -->
<div id="appointmentModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4" id="modalTitle">Détails du Rendez-vous</h3>
            <div id="modalContent">
                <!-- Le contenu sera rempli par JavaScript -->
            </div>
            <div class="flex justify-end mt-4">
                <button onclick="closeModal()" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">
                    Fermer
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        locale: 'fr',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        events: @json($appointments),
        eventClick: function(info) {
            showAppointmentDetails(info.event);
        },
        eventDidMount: function(info) {
            // Personnaliser les couleurs selon le statut
            var status = info.event.extendedProps.status;
            switch(status) {
                case 'pending':
                    info.el.style.backgroundColor = '#fbbf24';
                    break;
                case 'confirmed':
                    info.el.style.backgroundColor = '#10b981';
                    break;
                case 'completed':
                    info.el.style.backgroundColor = '#3b82f6';
                    break;
                case 'cancelled':
                    info.el.style.backgroundColor = '#ef4444';
                    break;
            }
        }
    });
    calendar.render();
});

function showAppointmentDetails(event) {
    var title = event.title;
    var start = event.start;
    var status = event.extendedProps.status;
    var url = event.url;

    document.getElementById('modalTitle').textContent = 'Rendez-vous de ' + title;

    var content = `
        <div class="space-y-3">
            <div>
                <strong>Donneur:</strong> ${title}
            </div>
            <div>
                <strong>Date:</strong> ${start.toLocaleDateString('fr-FR')}
            </div>
            <div>
                <strong>Heure:</strong> ${start.toLocaleTimeString('fr-FR', {hour: '2-digit', minute: '2-digit'})}
            </div>
            <div>
                <strong>Statut:</strong>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                    ${getStatusColor(status)}">
                    ${getStatusText(status)}
                </span>
            </div>
        </div>
    `;

    document.getElementById('modalContent').innerHTML = content;
    document.getElementById('appointmentModal').classList.remove('hidden');
}

function closeModal() {
    document.getElementById('appointmentModal').classList.add('hidden');
}

function getStatusColor(status) {
    switch(status) {
        case 'pending': return 'bg-yellow-100 text-yellow-800';
        case 'confirmed': return 'bg-green-100 text-green-800';
        case 'completed': return 'bg-blue-100 text-blue-800';
        case 'cancelled': return 'bg-red-100 text-red-800';
        default: return 'bg-gray-100 text-gray-800';
    }
}

function getStatusText(status) {
    switch(status) {
        case 'pending': return 'En attente';
        case 'confirmed': return 'Confirmé';
        case 'completed': return 'Terminé';
        case 'cancelled': return 'Annulé';
        default: return status;
    }
}
</script>
@endpush
