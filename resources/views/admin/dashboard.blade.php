@extends('layouts.admin')

@section('title', 'Dashboard Admin - BloodLink')
@section('description', 'Tableau de bord administrateur')
@section('page-title', 'Dashboard Administrateur')

@section('content')
<div class="mb-8">
    <p class="text-gray-600">Bienvenue, {{ Auth::user()->name }} !</p>
</div>

@php
    $bank = Auth::user()->managedBank;
@endphp

@if($bank)
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white p-6 rounded-lg shadow-md">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900">Banque Gérée</h3>
                    <p class="text-2xl font-bold text-blue-600">1</p>
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
                    <h3 class="text-lg font-semibold text-gray-900">Stocks Totaux</h3>
                    @php
                        $totalStocks = \App\Models\BloodStock::where('bank_id', $bank->id)->sum('quantity');
                    @endphp
                    <p class="text-2xl font-bold text-green-600">{{ $totalStocks }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-md">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900">Stocks Critiques</h3>
                    @php
                        $criticalStocks = \App\Models\BloodStock::where('bank_id', $bank->id)
                            ->where('status', 'critical')
                            ->count();
                    @endphp
                    <p class="text-2xl font-bold text-red-600">{{ $criticalStocks }}</p>
                </div>
            </div>
        </div>
    </div>
    <div class="bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-xl font-semibold text-gray-900 mb-4">Votre banque</h2>
        <div class="border border-gray-200 rounded-lg p-4">
            <div class="flex justify-between items-start">
                <div>
                    <h3 class="font-semibold text-gray-900">{{ $bank->name }}</h3>
                    <p class="text-sm text-gray-600">{{ $bank->address }}</p>
                </div>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                    {{ $bank->status }}
                </span>
            </div>
        </div>
    </div>
@else
    <div class="bg-yellow-50 border border-yellow-200 text-yellow-700 px-4 py-3 rounded-lg">
        <p>Aucune banque ne vous est encore assignée. Veuillez contacter le super administrateur.</p>
    </div>
@endif
@endsection
