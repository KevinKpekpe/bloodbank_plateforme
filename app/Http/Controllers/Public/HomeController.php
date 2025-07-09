<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Bank;
use App\Models\BloodStock;

class HomeController extends Controller
{
    public function index()
    {
        // Statistiques pour la page d'accueil
        $totalBanks = Bank::where('status', 'active')->count();
        $totalStocks = BloodStock::sum('quantity');

        // Banques récentes
        $recentBanks = Bank::where('status', 'active')
            ->latest()
            ->take(3)
            ->get();

        return view('public.home', compact('totalBanks', 'totalStocks', 'recentBanks'));
    }
}