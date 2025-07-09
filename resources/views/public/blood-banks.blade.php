@extends('layouts.app')

@section('title', 'Banques de Sang - BloodLink')
@section('description', 'Trouvez les banques de sang à Kinshasa')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Banques de Sang à Kinshasa</h1>
        <p class="mt-2 text-gray-600">Trouvez la banque de sang la plus proche de chez vous</p>
    </div>

    @if(isset($banks) && $banks->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($banks as $bank)
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <div class="flex justify-between items-start mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">{{ $bank->name }}</h3>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            {{ $bank->status }}
                        </span>
                    </div>

                    <div class="space-y-2 mb-4">
                        <p class="text-sm text-gray-600">
                            <i class="fas fa-map-marker-alt mr-2"></i>
                            {{ $bank->address }}
                        </p>
                        <p class="text-sm text-gray-600">
                            <i class="fas fa-phone mr-2"></i>
                            {{ $bank->contact_phone }}
                        </p>
                        <p class="text-sm text-gray-600">
                            <i class="fas fa-envelope mr-2"></i>
                            {{ $bank->contact_email }}
                        </p>
                    </div>

                    <div class="border-t pt-4">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Stocks totaux:</span>
                            <span class="font-semibold text-blue-600">{{ $bank->total_stocks ?? 0 }}</span>
                        </div>
                        @if(isset($bank->critical_stocks) && $bank->critical_stocks > 0)
                            <div class="flex justify-between items-center mt-1">
                                <span class="text-sm text-red-600">Stocks critiques:</span>
                                <span class="font-semibold text-red-600">{{ $bank->critical_stocks }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="bg-yellow-50 border border-yellow-200 text-yellow-700 px-4 py-3 rounded-lg">
            <p>Aucune banque de sang n'est actuellement disponible.</p>
        </div>
    @endif
</div>
@endsection