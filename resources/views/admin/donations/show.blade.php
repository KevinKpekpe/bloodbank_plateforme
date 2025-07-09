@extends('layouts.admin')

@section('title', 'Détail du Don - BloodLink')
@section('description', 'Détail et gestion d\'un don de sang')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Détail du Don</h1>
            <p class="mt-2 text-gray-600">Informations complètes sur le don</p>
        </div>
        <a href="{{ route('admin.donations.index') }}" class="text-red-600 hover:text-red-700">
            ← Retour à la liste
        </a>
    </div>

    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h2 class="text-lg font-semibold text-gray-900">
                Don du {{ \Carbon\Carbon::parse($donation->donation_date)->format('d/m/Y à H:i') }}
            </h2>
            @switch($donation->status)
                @case('collected')
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                        Collecté
                    </span>
                    @break
                @case('processed')
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                        Traité
                    </span>
                    @break
                @case('available')
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                        Disponible
                    </span>
                    @break
                @case('expired')
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                        Expiré
                    </span>
                    @break
                @case('used')
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                        Utilisé
                    </span>
                    @break
                @default
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                        {{ ucfirst($donation->status) }}
                    </span>
            @endswitch
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Infos du don -->
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Informations du don</h3>
                    <dl class="space-y-3">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Date du don</dt>
                            <dd class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($donation->donation_date)->format('d/m/Y à H:i') }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Groupe sanguin</dt>
                            <dd class="text-sm text-gray-900">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    {{ $donation->bloodType->name }}
                                </span>
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Volume</dt>
                            <dd class="text-sm text-gray-900">{{ $donation->volume }}L</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Statut</dt>
                            <dd class="text-sm text-gray-900">{{ ucfirst($donation->status) }}</dd>
                        </div>
                        @if($donation->notes)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Notes</dt>
                            <dd class="text-sm text-gray-900">{{ $donation->notes }}</dd>
                        </div>
                        @endif
                    </dl>
                </div>
                <!-- Infos du donneur -->
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Donneur</h3>
                    <dl class="space-y-3">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Nom</dt>
                            <dd class="text-sm text-gray-900">{{ $donation->donor->name }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Email</dt>
                            <dd class="text-sm text-gray-900">{{ $donation->donor->email }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Téléphone</dt>
                            <dd class="text-sm text-gray-900">{{ $donation->donor->phone }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Date de naissance</dt>
                            <dd class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($donation->donor->birth_date)->format('d/m/Y') }}</dd>
                        </div>
                    </dl>
                </div>
            </div>

            <!-- Historique du don -->
            <div class="mt-8 pt-6 border-t border-gray-200">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Historique du don</h3>
                <ul class="text-sm text-gray-700 space-y-1">
                    <li>Collecté le : <strong>{{ \Carbon\Carbon::parse($donation->donation_date)->format('d/m/Y à H:i') }}</strong></li>
                    @if($donation->processed_at)
                        <li>Traité le : <strong>{{ \Carbon\Carbon::parse($donation->processed_at)->format('d/m/Y à H:i') }}</strong></li>
                    @endif
                    @if($donation->available_at)
                        <li>Disponible le : <strong>{{ \Carbon\Carbon::parse($donation->available_at)->format('d/m/Y à H:i') }}</strong></li>
                    @endif
                    @if($donation->expired_at)
                        <li>Expiré le : <strong>{{ \Carbon\Carbon::parse($donation->expired_at)->format('d/m/Y à H:i') }}</strong></li>
                    @endif
                    @if($donation->used_at)
                        <li>Utilisé le : <strong>{{ \Carbon\Carbon::parse($donation->used_at)->format('d/m/Y à H:i') }}</strong></li>
                    @endif
                </ul>
            </div>

            <!-- Actions rapides -->
            <div class="mt-8 pt-6 border-t border-gray-200">
                <div class="flex space-x-4">
                    @if($donation->status === 'collected')
                        <form method="POST" action="{{ route('admin.donations.process', $donation->id) }}" class="inline">
                            @csrf
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                                Marquer comme traité
                            </button>
                        </form>
                    @endif
                    @if($donation->status === 'processed')
                        <form method="POST" action="{{ route('admin.donations.available', $donation->id) }}" class="inline">
                            @csrf
                            <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                                Rendre disponible
                            </button>
                        </form>
                    @endif
                    @if($donation->status === 'available')
                        <form method="POST" action="{{ route('admin.donations.expire', $donation->id) }}" class="inline">
                            @csrf
                            <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                                Marquer comme expiré
                            </button>
                        </form>
                        <form method="POST" action="{{ route('admin.donations.use', $donation->id) }}" class="inline">
                            @csrf
                            <button type="submit" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">
                                Marquer comme utilisé
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
