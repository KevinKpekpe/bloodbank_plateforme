@extends('layouts.app')

@section('title', 'Carte des Banques de Sang - BloodLink')
@section('description', 'Trouvez les banques de sang près de chez vous')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    #map {
        height: 600px;
        width: 100%;
        border-radius: 8px;
    }
    .search-container {
        position: absolute;
        top: 10px;
        left: 10px;
        z-index: 1000;
        background: white;
        padding: 10px;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        min-width: 300px;
    }
    .location-button {
        position: absolute;
        top: 10px;
        right: 10px;
        z-index: 1000;
        background: white;
        border: none;
        padding: 10px;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        cursor: pointer;
    }
</style>
@endpush

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Carte des Banques de Sang</h1>
        <p class="mt-2 text-gray-600">Trouvez les banques de sang les plus proches de votre position</p>
    </div>

    <!-- Carte -->
    <div class="bg-white p-6 rounded-lg shadow-md">
        <div class="relative">
            <!-- Barre de recherche -->
            <div class="search-container">
                <div class="flex space-x-2">
                    <input type="text" id="searchInput"
                           class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500"
                           placeholder="Rechercher une banque...">
                    <button onclick="searchBanks()"
                            class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
                <div class="mt-2">
                    <label class="text-sm text-gray-600">Rayon de recherche:</label>
                    <select id="radiusSelect" class="ml-2 px-2 py-1 border border-gray-300 rounded text-sm">
                        <option value="5">5 km</option>
                        <option value="10" selected>10 km</option>
                        <option value="20">20 km</option>
                        <option value="50">50 km</option>
                    </select>
                </div>
            </div>

            <!-- Bouton de localisation -->
            <button onclick="getCurrentLocation()" class="location-button">
                <i class="fas fa-location-arrow text-gray-600"></i>
            </button>

            <!-- Carte Leaflet -->
            <div id="map"></div>
        </div>
    </div>

    <!-- Liste des banques -->
    <div class="mt-8 bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-xl font-semibold text-gray-900 mb-4">Banques de Sang</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="banksList">
            @foreach($banks as $bank)
                <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                    <h3 class="font-semibold text-gray-900 mb-2">{{ $bank->name }}</h3>
                    <p class="text-sm text-gray-600 mb-2">{{ $bank->address }}</p>
                    <div class="text-sm text-gray-500 space-y-1">
                        <p><i class="fas fa-phone mr-2"></i>{{ $bank->contact_phone }}</p>
                        <p><i class="fas fa-envelope mr-2"></i>{{ $bank->contact_email }}</p>
                    </div>
                    <div class="mt-3">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            {{ $bank->status === 'active' ? 'Actif' : 'Inactif' }}
                        </span>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
let map;
let markers = [];
let currentLocationMarker;

// Initialiser la carte
document.addEventListener('DOMContentLoaded', function() {
    // Coordonnées de Kinshasa par défaut
    const defaultLat = -4.4419;
    const defaultLng = 15.2663;

    map = L.map('map').setView([defaultLat, defaultLng], 10);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    // Ajouter les marqueurs des banques
    addBankMarkers();
});

// Ajouter les marqueurs des banques
function addBankMarkers() {
    const banks = @json($banks);

    banks.forEach(bank => {
        if (bank.latitude && bank.longitude) {
            const marker = L.marker([bank.latitude, bank.longitude])
                .addTo(map)
                .bindPopup(`
                    <div class="p-2">
                        <h3 class="font-semibold text-gray-900">${bank.name}</h3>
                        <p class="text-sm text-gray-600">${bank.address}</p>
                        <p class="text-sm text-gray-500">${bank.contact_phone}</p>
                        <p class="text-sm text-gray-500">${bank.contact_email}</p>
                    </div>
                `);

            markers.push(marker);
        }
    });
}

// Obtenir la position actuelle
function getCurrentLocation() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            function(position) {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;

                // Supprimer l'ancien marqueur de position
                if (currentLocationMarker) {
                    map.removeLayer(currentLocationMarker);
                }

                // Ajouter le nouveau marqueur
                currentLocationMarker = L.marker([lat, lng], {
                    icon: L.divIcon({
                        className: 'custom-div-icon',
                        html: '<div style="background-color: #3B82F6; width: 20px; height: 20px; border-radius: 50%; border: 3px solid white; box-shadow: 0 2px 4px rgba(0,0,0,0.3);"></div>',
                        iconSize: [20, 20],
                        iconAnchor: [10, 10]
                    })
                }).addTo(map);

                // Centrer la carte sur la position
                map.setView([lat, lng], 13);

                // Rechercher les banques proches
                searchNearbyBanks(lat, lng);
            },
            function(error) {
                alert('Impossible d\'obtenir votre position: ' + error.message);
            }
        );
    } else {
        alert('La géolocalisation n\'est pas supportée par votre navigateur.');
    }
}

// Rechercher les banques proches
function searchNearbyBanks(lat, lng) {
    const radius = document.getElementById('radiusSelect').value;

    fetch(`/geolocation/nearby?latitude=${lat}&longitude=${lng}&radius=${radius}`)
        .then(response => response.json())
        .then(banks => {
            updateBanksList(banks);
        });
}

// Rechercher des banques par nom
function searchBanks() {
    const query = document.getElementById('searchInput').value;

    if (query.length < 2) {
        alert('Veuillez saisir au moins 2 caractères');
        return;
    }

    fetch(`/geolocation/search?query=${encodeURIComponent(query)}`)
        .then(response => response.json())
        .then(banks => {
            updateBanksList(banks);
        });
}

// Mettre à jour la liste des banques
function updateBanksList(banks) {
    const container = document.getElementById('banksList');

    if (banks.length === 0) {
        container.innerHTML = '<div class="col-span-full text-center text-gray-500">Aucune banque trouvée</div>';
        return;
    }

    container.innerHTML = banks.map(bank => `
        <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
            <h3 class="font-semibold text-gray-900 mb-2">${bank.name}</h3>
            <p class="text-sm text-gray-600 mb-2">${bank.address}</p>
            <div class="text-sm text-gray-500 space-y-1">
                <p><i class="fas fa-phone mr-2"></i>${bank.contact_phone}</p>
                <p><i class="fas fa-envelope mr-2"></i>${bank.contact_email}</p>
                ${bank.distance ? `<p><i class="fas fa-map-marker-alt mr-2"></i>${bank.distance.toFixed(1)} km</p>` : ''}
            </div>
            <div class="mt-3">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                    ${bank.status === 'active' ? 'Actif' : 'Inactif'}
                </span>
            </div>
        </div>
    `).join('');
}
</script>
@endpush
@endsection