@extends('layouts.admin')

@section('title', 'Banques de Sang - BloodLink')
@section('description', 'Gestion des banques de sang')
@section('page-title', 'Gestion des Banques de Sang')

@section('content')
<div class="bg-white p-6 rounded-lg shadow-md">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-semibold text-gray-900">Banques de Sang</h2>
        <button class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md text-sm font-medium">
            Nouvelle Banque
        </button>
    </div>

    <div class="bg-yellow-50 border border-yellow-200 text-yellow-700 px-4 py-3 rounded-lg">
        <p>Cette page sera développée dans la phase suivante. Elle permettra de créer et gérer les banques de sang.</p>
    </div>
</div>
@endsection