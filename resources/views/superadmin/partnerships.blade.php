@extends('layouts.admin')

@section('title', 'Partenariats - BloodLink')
@section('description', 'Gestion des partenariats')
@section('page-title', 'Gestion des Partenariats')

@section('content')
<div class="bg-white p-6 rounded-lg shadow-md">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-semibold text-gray-900">Partenariats</h2>
        <button class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md text-sm font-medium">
            Nouveau Partenariat
        </button>
    </div>

    <div class="bg-yellow-50 border border-yellow-200 text-yellow-700 px-4 py-3 rounded-lg">
        <p>Cette page sera développée dans la phase suivante. Elle permettra de gérer les demandes de partenariat et les partenaires existants.</p>
    </div>
</div>
@endsection