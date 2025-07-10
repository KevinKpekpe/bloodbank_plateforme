@extends('layouts.app')

@section('title', 'Code de vérification - BloodLink')
@section('description', 'Entrez le code de vérification reçu par email.')

@section('content')
<div class="min-h-screen bg-gray-50 flex flex-col justify-center py-12 sm:px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <div class="flex justify-center">
            <a href="{{ route('home') }}" class="flex items-center">
                <div class="w-12 h-12 bg-red-600 rounded-lg flex items-center justify-center mr-3">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                    </svg>
                </div>
                <span class="text-2xl font-bold text-gray-900">BloodLink</span>
            </a>
        </div>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-lg">
        <div class="bg-white py-8 px-4 shadow-xl rounded-lg sm:px-10">
            <div class="text-center">
                <!-- Lock Icon -->
                <div class="mx-auto flex items-center justify-center h-24 w-24 rounded-full bg-green-100 mb-6">
                    <svg class="h-12 w-12 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </div>

                <!-- Title -->
                <h1 class="text-2xl font-bold text-gray-900 mb-4">Code de vérification</h1>

                <!-- Message -->
                <p class="text-gray-600 mb-6 text-lg">
                    Entrez le code à 6 chiffres que nous avons envoyé à votre adresse email.
                </p>

                <!-- Email Display -->
                <div class="mb-8 p-4 bg-gray-50 border border-gray-200 rounded-lg">
                    <p class="text-sm text-gray-600 mb-2">Code envoyé à :</p>
                    <p class="text-lg font-semibold text-gray-900">{{ Auth::user()->email }}</p>
                </div>

                <!-- Verification Form -->
                <form method="POST" action="{{ route('verification.verify') }}" class="space-y-6">
                    @csrf

                    <!-- Code Input -->
                    <div>
                        <label for="verification_code" class="block text-sm font-medium text-gray-700 mb-2">
                            Code de vérification
                        </label>
                        <div class="relative">
                            <input type="text"
                                   name="verification_code"
                                   id="verification_code"
                                   maxlength="6"
                                   pattern="[0-9]{6}"
                                   class="w-full px-4 py-3 text-center text-2xl font-mono tracking-widest border border-gray-300 rounded-lg focus:ring-red-500 focus:border-red-500 transition-colors @error('verification_code') border-red-300 @enderror"
                                   placeholder="000000"
                                   required
                                   autocomplete="off">
                        </div>
                        @error('verification_code')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <button type="submit"
                            class="w-full inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Vérifier mon email
                    </button>
                </form>

                <!-- Resend Code -->
                <div class="mt-6">
                    <form method="POST" action="{{ route('verification.resend') }}">
                        @csrf
                        <button type="submit"
                                class="w-full inline-flex items-center justify-center px-6 py-3 border border-gray-300 text-base font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            Renvoyer le code
                        </button>
                    </form>
                </div>

                <!-- Security Notice -->
                <div class="mt-8 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                    <h3 class="text-sm font-medium text-yellow-800 mb-2">Sécurité</h3>
                    <ul class="text-sm text-yellow-700 space-y-1">
                        <li>• Le code expire dans 15 minutes</li>
                        <li>• Ne partagez jamais ce code</li>
                        <li>• Vérifiez votre boîte de réception et les spams</li>
                    </ul>
                </div>

                <!-- Help Section -->
                <div class="mt-8 pt-6 border-t border-gray-200">
                    <p class="text-sm text-gray-500 mb-4">Besoin d'aide ?</p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm">
                        <a href="{{ route('verification.notice') }}" class="text-red-600 hover:text-red-500 flex items-center">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Retour
                        </a>
                        <a href="{{ route('contact') }}" class="text-red-600 hover:text-red-500 flex items-center">
                            <i class="fas fa-envelope mr-2"></i>
                            Support technique
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Auto-focus on input
document.getElementById('verification_code').focus();

// Auto-format code input
document.getElementById('verification_code').addEventListener('input', function(e) {
    // Remove non-numeric characters
    this.value = this.value.replace(/[^0-9]/g, '');

    // Limit to 6 digits
    if (this.value.length > 6) {
        this.value = this.value.slice(0, 6);
    }
});

// Handle paste event
document.getElementById('verification_code').addEventListener('paste', function(e) {
    e.preventDefault();
    const pastedText = (e.clipboardData || window.clipboardData).getData('text');
    const numericText = pastedText.replace(/[^0-9]/g, '').slice(0, 6);
    this.value = numericText;
});
</script>
@endsection
