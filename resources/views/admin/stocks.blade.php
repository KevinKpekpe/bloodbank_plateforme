@extends('layouts.admin')

@section('title', 'Stocks - BloodLink')
@section('description', 'Gestion des stocks')
@section('page-title', 'Gestion des Stocks')

@section('content')
<div class="bg-white p-6 rounded-lg shadow-md">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-semibold text-gray-900">Stocks de Sang</h2>
        <a href="{{ route('admin.stocks.index') }}" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md text-sm font-medium">
            Gérer les Stocks
        </a>
    </div>

    <div class="bg-blue-50 border border-blue-200 text-blue-700 px-4 py-3 rounded-lg">
        <p>La fonctionnalité de gestion des stocks de sang est maintenant disponible ! Cliquez sur "Gérer les Stocks" pour accéder à l'interface complète de gestion des stocks par groupe sanguin.</p>
    </div>
</div>
@endsection
