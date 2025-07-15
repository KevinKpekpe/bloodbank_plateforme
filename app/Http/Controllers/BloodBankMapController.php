<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\BloodStock;
use App\Models\BloodType;
use App\Models\BloodBag;
use App\Helpers\StockHelper;
use Illuminate\Http\Request;

class BloodBankMapController extends Controller
{
    /**
     * Afficher la page unifiée avec carte et stocks
     */
    public function index()
    {
        // Récupérer toutes les banques actives avec leurs stocks
        $banks = Bank::where('status', 'active')
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->with(['bloodStocks.bloodType'])
            ->get();

        // Récupérer tous les types de sang pour l'affichage
        $bloodTypes = BloodType::orderBy('name')->get();

        // Calculer les statistiques globales en utilisant les poches
        $totalStocks = 0;
        $totalBags = 0;
        $criticalStocks = 0;

        foreach ($banks as $bank) {
            $statistics = StockHelper::getDashboardStatistics($bank);
            $totalStocks += $statistics['total_volume_l'];
            $totalBags += $statistics['total_bags'];
            $criticalStocks += $statistics['critical_bags'] ?? 0;
        }

        return view('blood-bank-map.index', compact('banks', 'bloodTypes', 'totalStocks', 'totalBags', 'criticalStocks'));
    }

    /**
     * API pour obtenir les détails d'une banque avec ses stocks
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
     * API pour obtenir toutes les banques avec leurs stocks
     */
    public function getAllBanks()
    {
        $banks = Bank::where('status', 'active')
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
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
     * API pour rechercher des banques par groupe sanguin
     */
    public function searchByBloodType(Request $request)
    {
        $bloodTypeId = $request->get('blood_type_id');

        if (!$bloodTypeId) {
            return response()->json(['error' => 'ID du groupe sanguin requis'], 400);
        }

        $banks = Bank::where('status', 'active')
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->whereHas('bloodStocks', function ($query) use ($bloodTypeId) {
                $query->where('blood_type_id', $bloodTypeId)
                      ->where('available_bags', '>', 0); // Poches disponibles
            })
            ->with(['bloodStocks.bloodType'])
            ->get();

        // Ajouter les statistiques des poches pour chaque banque
        $banks->each(function ($bank) {
            $statistics = StockHelper::getDashboardStatistics($bank);
            $bank->statistics = $statistics;
        });

        return response()->json($banks);
    }
}
