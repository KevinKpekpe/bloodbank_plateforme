<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Bank;
use App\Models\BloodStock;
use App\Models\BloodBag;
use App\Helpers\StockHelper;
use Illuminate\Http\Request;

class BloodBankController extends Controller
{
    public function index(Request $request)
    {
        $query = Bank::where('status', 'active')
            ->with(['bloodStocks.bloodType']);

        // Filtre par groupe sanguin
        if ($request->filled('blood_type')) {
            $query->whereHas('bloodStocks', function ($q) use ($request) {
                $q->where('blood_type_id', $request->blood_type)
                  ->where('available_bags', '>', 0); // Utiliser les poches disponibles
            });
        }

        // Filtre par commune
        if ($request->filled('commune')) {
            $query->where('address', 'like', '%' . $request->commune . '%');
        }

        $banks = $query->get();

        // Formater les données pour l'affichage en utilisant les statistiques des poches
        $banks->each(function ($bank) {
            $statistics = StockHelper::getDashboardStatistics($bank);
            $bank->total_stocks = number_format($statistics['total_volume_l'], 1) . 'L'; // Volume total en litres
            $bank->available_stocks = $statistics['available_bags'] . ' poches'; // Nombre de poches disponibles
            $bank->critical_stocks = $statistics['critical_bags'] ?? 0; // Poches en niveau critique
        });

        return view('public.blood-banks', compact('banks'));
    }
}
