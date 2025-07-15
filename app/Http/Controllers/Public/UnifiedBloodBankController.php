<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Bank;
use App\Models\BloodStock;
use App\Models\BloodBag;
use App\Helpers\StockHelper;
use Illuminate\Http\Request;

class UnifiedBloodBankController extends Controller
{
    public function index()
    {
        // Récupérer toutes les banques actives avec leurs stocks
        $banks = Bank::where('status', 'active')
            ->with(['bloodStocks.bloodType'])
            ->get();

        // Calculer le total des stocks en utilisant les statistiques des poches
        $totalStocks = 0;
        $totalBags = 0;

        foreach ($banks as $bank) {
            $statistics = StockHelper::getDashboardStatistics($bank);
            $totalStocks += $statistics['total_volume_l'];
            $totalBags += $statistics['total_bags'];
        }

        return view('blood-banks.unified', compact('banks', 'totalStocks', 'totalBags'));
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

        // Ajouter les statistiques des poches pour chaque banque
        $banks->each(function ($bank) {
            $statistics = StockHelper::getDashboardStatistics($bank);
            $bank->statistics = $statistics;
        });

        return response()->json($banks);
    }

    /**
     * Rechercher des banques proches
     */
    public function nearby(Request $request)
    {
        $latitude = $request->get('latitude');
        $longitude = $request->get('longitude');
        $radius = $request->get('radius', 50); // Rayon en km

        if (!$latitude || !$longitude) {
            return response()->json(['error' => 'Coordonnées requises'], 400);
        }

        // Calculer la distance avec la formule de Haversine
        $banks = Bank::where('status', 'active')
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->with(['bloodStocks.bloodType'])
            ->get()
            ->filter(function ($bank) use ($latitude, $longitude, $radius) {
                $distance = $this->calculateDistance(
                    $latitude, $longitude,
                    $bank->latitude, $bank->longitude
                );
                return $distance <= $radius;
            })
            ->sortBy(function ($bank) use ($latitude, $longitude) {
                return $this->calculateDistance(
                    $latitude, $longitude,
                    $bank->latitude, $bank->longitude
                );
            });

        // Ajouter les statistiques des poches et la distance pour chaque banque
        $banks->each(function ($bank) use ($latitude, $longitude) {
            $statistics = StockHelper::getDashboardStatistics($bank);
            $bank->statistics = $statistics;
            $bank->distance = round($this->calculateDistance(
                $latitude, $longitude,
                $bank->latitude, $bank->longitude
            ), 1);
        });

        return response()->json($banks);
    }

    /**
     * Obtenir les détails d'une banque avec ses stocks
     */
    public function bankDetails(Bank $bank)
    {
        if ($bank->status !== 'active') {
            return response()->json(['error' => 'Banque non active'], 404);
        }

        $bank->load(['bloodStocks.bloodType']);

        // Ajouter les statistiques détaillées des poches
        $statistics = StockHelper::getDashboardStatistics($bank);
        $detailedStats = StockHelper::getDetailedStatistics($bank);

        $bank->statistics = $statistics;
        $bank->detailed_stats = $detailedStats;

        return response()->json($bank);
    }

    /**
     * Calculer la distance entre deux points géographiques (formule de Haversine)
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
}
