<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use Illuminate\Http\Request;

class GeolocationController extends Controller
{
    /**
     * Afficher la carte des banques de sang
     */
    public function index()
    {
        $banks = Bank::where('status', 'active')
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get();

        return view('geolocation.index', compact('banks'));
    }

    /**
     * API pour obtenir les banques proches
     */
    public function nearby(Request $request)
    {
        try {
            $request->validate([
                'latitude' => 'required|numeric|between:-90,90',
                'longitude' => 'required|numeric|between:-180,180',
                'radius' => 'nullable|numeric|min:1|max:50' // rayon en km
            ]);

            $latitude = $request->latitude;
            $longitude = $request->longitude;
            $radius = $request->radius ?? 10; // 10km par défaut

            // Formule de Haversine pour calculer la distance - compatible SQLite
            $banks = Bank::where('status', 'active')
                ->whereNotNull('latitude')
                ->whereNotNull('longitude')
                ->selectRaw('*,
                    (6371 * acos(cos(radians(?)) * cos(radians(latitude)) * cos(radians(longitude) - radians(?)) + sin(radians(?)) * sin(radians(latitude)))) AS distance',
                    [$latitude, $longitude, $latitude])
                ->get()
                ->filter(function($bank) use ($radius) {
                    return $bank->distance <= $radius;
                })
                ->sortBy('distance')
                ->values();

            return response()->json($banks);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * API pour obtenir toutes les banques avec leurs coordonnées
     */
    public function allBanks()
    {
        $banks = Bank::where('status', 'active')
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->select('id', 'name', 'address', 'contact_phone', 'contact_email', 'latitude', 'longitude')
            ->get();

        return response()->json($banks);
    }

    /**
     * Obtenir les détails d'une banque
     */
    public function bankDetails(Bank $bank)
    {
        if (!$bank->latitude || !$bank->longitude) {
            return response()->json(['error' => 'Coordonnées non disponibles'], 404);
        }

        $bankData = [
            'id' => $bank->id,
            'name' => $bank->name,
            'address' => $bank->address,
            'contact_phone' => $bank->contact_phone,
            'contact_email' => $bank->contact_email,
            'latitude' => $bank->latitude,
            'longitude' => $bank->longitude,
            'status' => $bank->status
        ];

        return response()->json($bankData);
    }

    /**
     * Rechercher des banques par nom ou adresse
     */
    public function search(Request $request)
    {
        try {
            $request->validate([
                'query' => 'required|string|min:2'
            ]);

            $query = $request->query('query');

            $banks = Bank::where('status', 'active')
                ->whereNotNull('latitude')
                ->whereNotNull('longitude')
                ->where(function($q) use ($query) {
                    $q->where('name', 'like', "%{$query}%")
                      ->orWhere('address', 'like', "%{$query}%");
                })
                ->get();

            return response()->json($banks);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Obtenir les statistiques de géolocalisation
     */
    public function statistics()
    {
        $stats = [
            'total_banks' => Bank::count(),
            'banks_with_coordinates' => Bank::whereNotNull('latitude')->whereNotNull('longitude')->count(),
            'active_banks' => Bank::where('status', 'active')->count(),
            'active_banks_with_coordinates' => Bank::where('status', 'active')
                ->whereNotNull('latitude')
                ->whereNotNull('longitude')
                ->count()
        ];

        return response()->json($stats);
    }
}
