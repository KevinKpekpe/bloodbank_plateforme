<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Bank;
use App\Models\BloodStock;
use Illuminate\Http\Request;

class UnifiedBloodBankController extends Controller
{
    public function index()
    {
        // Récupérer toutes les banques actives avec leurs stocks
        $banks = Bank::where('status', 'active')
            ->with(['bloodStocks.bloodType'])
            ->get();

        // Calculer le total des stocks
        $totalStocks = $banks->sum(function ($bank) {
            return $bank->bloodStocks->sum('quantity');
        });

        return view('blood-banks.unified', compact('banks', 'totalStocks'));
    }

    /**
     * Rechercher des banques par nom
     */
    public function search(Request $request)
    {
        $query = $request->get('query');

        $banks = Bank::where('status', 'active')
            ->where('name', 'like', '%' . $query . '%')
            ->orWhere('address', 'like', '%' . $query . '%')
            ->with(['bloodStocks.bloodType'])
            ->get();

        return response()->json($banks);
    }

    /**
     * Rechercher des banques proches
     */
    public function nearby(Request $request)
    {
        $latitude = $request->get('latitude');
        $longitude = $request->get('longitude');
        $radius = $request->get('radius', 10); // Rayon en km

        // Formule de calcul de distance (formule de Haversine)
        $banks = Bank::where('status', 'active')
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->with(['bloodStocks.bloodType'])
            ->get()
            ->filter(function ($bank) use ($latitude, $longitude, $radius) {
                $distance = $this->calculateDistance(
                    $latitude,
                    $longitude,
                    $bank->latitude,
                    $bank->longitude
                );

                $bank->distance = $distance;
                return $distance <= $radius;
            })
            ->sortBy('distance')
            ->values();

        return response()->json($banks);
    }

    /**
     * Calculer la distance entre deux points (formule de Haversine)
     */
    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371; // Rayon de la Terre en km

        $latDelta = deg2rad($lat2 - $lat1);
        $lonDelta = deg2rad($lon2 - $lon1);

        $a = sin($latDelta / 2) * sin($latDelta / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($lonDelta / 2) * sin($lonDelta / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }

    /**
     * Obtenir les détails d'une banque spécifique
     */
    public function show($id)
    {
        $bank = Bank::where('status', 'active')
            ->with(['bloodStocks.bloodType'])
            ->findOrFail($id);

        return response()->json($bank);
    }
}
