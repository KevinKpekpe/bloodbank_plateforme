@extends('layouts.app')

@section('title', 'Dashboard Donneur - BloodLink')
@section('description', 'Tableau de bord du donneur')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Dashboard Donneur</h1>
        <p class="mt-2 text-gray-600">Bienvenue, {{ Auth::user()->name }} !</p>
    </div>

    @if(Auth::user()->donor)
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white p-6 rounded-lg shadow-md">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900">Groupe Sanguin</h3>
                        <p class="text-2xl font-bold text-red-600">{{ Auth::user()->donor->bloodType->name }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-md">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900">Dons Totaux</h3>
                        <p class="text-2xl font-bold text-green-600">{{ Auth::user()->donor->total_donations }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-md">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900">Volume Total</h3>
                        <p class="text-2xl font-bold text-blue-600">{{ Auth::user()->donor->total_volume }}L</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Informations Personnelles</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-600">Nom complet</p>
                    <p class="font-medium">{{ Auth::user()->donor->firstname }} {{ Auth::user()->donor->surname }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Date de naissance</p>
                    <p class="font-medium">{{ \Carbon\Carbon::parse(Auth::user()->donor->birthdate)->format('d/m/Y') }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Genre</p>
                    <p class="font-medium">{{ ucfirst(Auth::user()->donor->gender) }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Dernier don</p>
                    <p class="font-medium">
                        @if(Auth::user()->donor->last_donation_date)
                            {{ \Carbon\Carbon::parse(Auth::user()->donor->last_donation_date)->format('d/m/Y') }}
                        @else
                            Aucun don encore
                        @endif
                    </p>
                </div>
            </div>
        </div>
    @else
        <div class="bg-yellow-50 border border-yellow-200 text-yellow-700 px-4 py-3 rounded-lg">
            <p>Vos informations de donneur ne sont pas encore complètes. Veuillez contacter l'administrateur.</p>
        </div>
    @endif
</div>
@endsection
