<div class="relative" x-data="{ open: false, notifications: [], unreadCount: 0 }" x-init="
    // Charger le nombre de notifications non lues
    fetch('/notifications/unread-count')
        .then(response => response.json())
        .then(data => unreadCount = data.count);

    // Charger les dernières notifications
    fetch('/notifications/latest')
        .then(response => response.json())
        .then(data => notifications = data);
">
    <!-- Bouton de notification -->
    <button @click="open = !open" class="relative p-2 text-gray-500 hover:text-gray-700 focus:outline-none">
        <i class="fas fa-bell text-lg"></i>

        <!-- Badge de notifications non lues -->
        <span x-show="unreadCount > 0"
              x-text="unreadCount > 99 ? '99+' : unreadCount"
              class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
        </span>
    </button>

    <!-- Dropdown des notifications -->
    <div x-show="open"
         @click.away="open = false"
         x-transition:enter="transition ease-out duration-100"
         x-transition:enter-start="transform opacity-0 scale-95"
         x-transition:enter-end="transform opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-75"
         x-transition:leave-start="transform opacity-100 scale-100"
         x-transition:leave-end="transform opacity-0 scale-95"
         class="absolute right-0 mt-2 w-80 bg-white rounded-md shadow-lg py-1 z-50 max-h-96 overflow-y-auto">

        <!-- En-tête -->
        <div class="px-4 py-2 border-b border-gray-200">
            <div class="flex justify-between items-center">
                <h3 class="text-sm font-medium text-gray-900">Notifications</h3>
                <a href="{{ route('notifications.index') }}" class="text-xs text-blue-600 hover:text-blue-800">
                    Voir tout
                </a>
            </div>
        </div>

        <!-- Liste des notifications -->
        <template x-if="notifications.length > 0">
            <div>
                <template x-for="notification in notifications" :key="notification.id">
                    <div class="px-4 py-3 hover:bg-gray-50 border-b border-gray-100 last:border-b-0">
                        <div class="flex items-start">
                            <!-- Icône selon le type -->
                            <div class="flex-shrink-0 mr-3">
                                <template x-if="notification.type === 'success'">
                                    <i class="fas fa-check-circle text-green-500"></i>
                                </template>
                                <template x-if="notification.type === 'warning'">
                                    <i class="fas fa-exclamation-triangle text-yellow-500"></i>
                                </template>
                                <template x-if="notification.type === 'error'">
                                    <i class="fas fa-times-circle text-red-500"></i>
                                </template>
                                <template x-if="notification.type === 'info'">
                                    <i class="fas fa-info-circle text-blue-500"></i>
                                </template>
                            </div>

                            <!-- Contenu -->
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900" x-text="notification.title"></p>
                                <p class="text-sm text-gray-500 mt-1" x-text="notification.message"></p>
                                <p class="text-xs text-gray-400 mt-1" x-text="new Date(notification.created_at).toLocaleDateString('fr-FR')"></p>
                            </div>

                            <!-- Bouton marquer comme lu -->
                            <button @click="
                                fetch(`/notifications/${notification.id}/mark-as-read`, {
                                    method: 'POST',
                                    headers: {
                                        'X-CSRF-TOKEN': document.querySelector('meta[name=\"csrf-token\"]').getAttribute('content'),
                                        'Content-Type': 'application/json',
                                    }
                                }).then(() => {
                                    unreadCount--;
                                    notifications = notifications.filter(n => n.id !== notification.id);
                                });
                            "
                            class="ml-2 text-blue-600 hover:text-blue-800 text-xs">
                                <i class="fas fa-check"></i>
                            </button>
                        </div>
                    </div>
                </template>
            </div>
        </template>

        <!-- Aucune notification -->
        <template x-if="notifications.length === 0">
            <div class="px-4 py-6 text-center">
                <i class="fas fa-bell text-gray-400 text-2xl mb-2"></i>
                <p class="text-sm text-gray-500">Aucune notification</p>
            </div>
        </template>

        <!-- Footer -->
        <div class="px-4 py-2 border-t border-gray-200">
            <button @click="
                fetch('/notifications/mark-all-as-read', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name=\"csrf-token\"]').getAttribute('content'),
                        'Content-Type': 'application/json',
                    }
                }).then(() => {
                    unreadCount = 0;
                    notifications = [];
                });
            "
            class="w-full text-xs text-blue-600 hover:text-blue-800">
                Marquer tout comme lu
            </button>
        </div>
    </div>
</div>
