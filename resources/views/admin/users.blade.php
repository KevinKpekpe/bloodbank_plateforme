@extends('layouts.admin')

@section('title', 'Utilisateurs - BloodLink')
@section('description', 'Gestion des utilisateurs')
@section('page-title', 'Gestion des Utilisateurs')

@section('content')
<div class="bg-white p-6 rounded-lg shadow-md">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-semibold text-gray-900">Utilisateurs</h2>
        <button class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md text-sm font-medium">
            Nouvel Utilisateur
        </button>
    </div>

    <div class="bg-yellow-50 border border-yellow-200 text-yellow-700 px-4 py-3 rounded-lg">
        <p>Cette page sera développée dans la phase suivante. Elle permettra de gérer les utilisateurs de la banque.</p>
    </div>
</div>
@endsection