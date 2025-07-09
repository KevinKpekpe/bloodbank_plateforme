<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PartnershipController extends Controller
{
    public function index()
    {
        return view('public.partnership');
    }

    public function store(Request $request)
    {
        $request->validate([
            'organization_name' => 'required|string|max:255',
            'contact_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'partnership_type' => 'required|in:hospital,clinic,ngo,other',
            'description' => 'required|string|max:1000',
        ]);

        // TODO: Sauvegarder la demande de partenariat
        // Pour l'instant, on redirige avec un message de succès

        return redirect()->route('partnership')
            ->with('success', 'Votre demande de partenariat a été envoyée avec succès. Nous vous contacterons bientôt.');
    }
}