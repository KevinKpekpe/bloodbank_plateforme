<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\BloodStock;
use App\Models\BloodType;
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

        // Calculer les statistiques globales (en ml)
        $totalStocks = $banks->sum(function ($bank) {
            return $bank->bloodStocks->sum('quantity');
        });

        $criticalStocks = $banks->sum(function ($bank) {
            return $bank->bloodStocks->filter(function ($stock) {
                return $stock->isCritical();
            })->count();
        });

        return view('blood-bank-map.index', compact('banks', 'bloodTypes', 'totalStocks', 'criticalStocks'));
    }

    /**
     * API pour obtenir les détails d'une banque avec ses stocks
     */
    public function bankDetails(Bank $bank)
    {
        if (!$bank->latitude || !$bank->longitude) {
            return response()->json(['error' => 'Coordonnées non disponibles'], 404);
        }

        // Charger les stocks avec les types de sang
        $bank->load(['bloodStocks.bloodType']);

        // Formater les données des stocks
        $stocks = $bank->bloodStocks->map(function ($stock) {
            return [
                'blood_type' => $stock->bloodType->name,
                'quantity' => $stock->quantity, // en ml
                'quantity_l' => $stock->quantity / 1000, // en litres
                'critical_level' => $stock->critical_level,
                'status' => $stock->status,
                'is_low' => $stock->isLow(),
                'is_critical' => $stock->isCritical(),
                'is_high' => $stock->isHigh(),
                'last_updated' => $stock->last_updated
            ];
        });

        $bankData = [
            'id' => $bank->id,
            'name' => $bank->name,
            'address' => $bank->address,
            'contact_phone' => $bank->contact_phone,
            'contact_email' => $bank->contact_email,
            'latitude' => $bank->latitude,
            'longitude' => $bank->longitude,
            'status' => $bank->status,
            'stocks' => $stocks,
            'total_stocks' => $bank->bloodStocks->sum('quantity'), // en ml
            'total_stocks_l' => $bank->bloodStocks->sum('quantity') / 1000, // en litres
            'critical_stocks_count' => $bank->bloodStocks->filter(function ($stock) {
                return $stock->isCritical();
            })->count()
        ];

        return response()->json($bankData);
    }

    /**
     * API pour rechercher des banques par nom ou adresse
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
                ->with(['bloodStocks.bloodType'])
                ->get();

            // Formater les données pour l'affichage
            $banks->each(function ($bank) {
                $bank->total_stocks = $bank->bloodStocks->sum('quantity');
                $bank->critical_stocks = $bank->bloodStocks->filter(function ($stock) {
                    return $stock->isCritical();
                })->count();
            });

            return response()->json($banks);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * API pour obtenir les banques proches avec leurs stocks
     */
    public function nearby(Request $request)
    {
        try {
            $request->validate([
                'latitude' => 'required|numeric|between:-90,90',
                'longitude' => 'required|numeric|between:-180,180',
                'radius' => 'nullable|numeric|min:1|max:50'
            ]);

            $latitude = $request->latitude;
            $longitude = $request->longitude;
            $radius = $request->radius ?? 10;

            // Formule de Haversine pour calculer la distance
            $banks = Bank::where('status', 'active')
                ->whereNotNull('latitude')
                ->whereNotNull('longitude')
                ->with(['bloodStocks.bloodType'])
                ->selectRaw('*,
                    (6371 * acos(cos(radians(?)) * cos(radians(latitude)) * cos(radians(longitude) - radians(?)) + sin(radians(?)) * sin(radians(latitude)))) AS distance',
                    [$latitude, $longitude, $latitude])
                ->get()
                ->filter(function($bank) use ($radius) {
                    return $bank->distance <= $radius;
                })
                ->sortBy('distance')
                ->values();

            // Formater les données pour l'affichage
            $banks->each(function ($bank) {
                $bank->total_stocks = $bank->bloodStocks->sum('quantity');
                $bank->critical_stocks = $bank->bloodStocks->filter(function ($stock) {
                    return $stock->isCritical();
                })->count();
            });

            return response()->json($banks);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * API pour filtrer par type de sang
     */
    public function filterByBloodType(Request $request)
    {
        try {
            $request->validate([
                'blood_type_id' => 'required|exists:blood_types,id'
            ]);

            $bloodTypeId = $request->blood_type_id;

            $banks = Bank::where('status', 'active')
                ->whereNotNull('latitude')
                ->whereNotNull('longitude')
                ->whereHas('bloodStocks', function ($query) use ($bloodTypeId) {
                    $query->where('blood_type_id', $bloodTypeId)
                          ->where('quantity', '>', 0);
                })
                ->with(['bloodStocks.bloodType'])
                ->get();

            // Formater les données pour l'affichage
            $banks->each(function ($bank) {
                $bank->total_stocks = $bank->bloodStocks->sum('quantity');
                $bank->critical_stocks = $bank->bloodStocks->filter(function ($stock) {
                    return $stock->isCritical();
                })->count();
            });

            return response()->json($banks);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
