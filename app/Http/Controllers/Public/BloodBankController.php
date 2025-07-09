<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Bank;
use App\Models\BloodStock;
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
                  ->where('quantity', '>', 0);
            });
        }

        // Filtre par commune
        if ($request->filled('commune')) {
            $query->where('address', 'like', '%' . $request->commune . '%');
        }

        $banks = $query->get();

        // Formater les données pour l'affichage
        $banks->each(function ($bank) {
            $bank->total_stocks = $bank->bloodStocks->sum('quantity');
            $bank->critical_stocks = $bank->bloodStocks->where('status', 'critical')->count();
        });

        return view('public.blood-banks', compact('banks'));
    }
}