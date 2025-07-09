@extends('layouts.app')

@section('title', 'Notifications - BloodLink')
@section('description', 'Vos notifications')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Notifications</h1>
        <p class="mt-2 text-gray-600">Vos notifications et alertes</p>
    </div>

    <!-- Actions -->
    <div class="mb-6 flex justify-between items-center">
        <div class="flex space-x-4">
            <button id="markAllRead" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                <i class="fas fa-check-double mr-2"></i>Marquer tout comme lu
            </button>
        </div>
        <div class="text-sm text-gray-500">
            {{ $notifications->total() }} notification(s)
        </div>
    </div>

    <!-- Liste des notifications -->
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        @forelse($notifications as $notification)
            <div class="border-b border-gray-200 last:border-b-0">
                <div class="p-6 {{ $notification->isUnread() ? 'bg-blue-50' : '' }}">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <div class="flex items-center mb-2">
                                <div class="flex-shrink-0">
                                    @switch($notification->type)
                                        @case('success')
                                            <i class="fas fa-check-circle text-green-500 text-lg"></i>
                                            @break
                                        @case('warning')
                                            <i class="fas fa-exclamation-triangle text-yellow-500 text-lg"></i>
                                            @break
                                        @case('error')
                                            <i class="fas fa-times-circle text-red-500 text-lg"></i>
                                            @break
                                        @case('info')
                                        @default
                                            <i class="fas fa-info-circle text-blue-500 text-lg"></i>
                                    @endswitch
                                </div>
                                <div class="ml-3 flex-1">
                                    <h3 class="text-sm font-medium text-gray-900">
                                        {{ $notification->title }}
                                        @if($notification->isUnread())
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 ml-2">
                                                Nouveau
                                            </span>
                                        @endif
                                    </h3>
                                    <p class="text-sm text-gray-600 mt-1">{{ $notification->message }}</p>
                                    <p class="text-xs text-gray-400 mt-2">
                                        {{ $notification->created_at->diffForHumans() }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="ml-4 flex-shrink-0 flex space-x-2">
                            @if($notification->isUnread())
                                <button onclick="markAsRead({{ $notification->id }})"
                                        class="text-blue-600 hover:text-blue-900 text-sm">
                                    <i class="fas fa-check"></i>
                                </button>
                            @endif
                            <button onclick="deleteNotification({{ $notification->id }})"
                                    class="text-red-600 hover:text-red-900 text-sm">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="p-8 text-center">
                <i class="fas fa-bell text-gray-400 text-4xl mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Aucune notification</h3>
                <p class="text-gray-500">Vous n'avez pas encore de notifications.</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($notifications->hasPages())
        <div class="mt-6">
            {{ $notifications->links() }}
        </div>
    @endif
</div>

<script>
function markAsRead(notificationId) {
    fetch(`/notifications/${notificationId}/mark-as-read`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    });
}

function deleteNotification(notificationId) {
    if (confirm('Êtes-vous sûr de vouloir supprimer cette notification ?')) {
        fetch(`/notifications/${notificationId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            }
        });
    }
}

document.getElementById('markAllRead').addEventListener('click', function() {
    fetch('/notifications/mark-all-as-read', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    });
});
</script>
@endsection
