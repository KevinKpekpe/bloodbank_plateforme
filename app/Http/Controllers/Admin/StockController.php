<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bank;
use App\Models\BloodStock;
use App\Models\BloodType;
use App\Models\BloodBag;
use App\Helpers\StockHelper;
use Illuminate\Http\Request;

class StockController extends Controller
{
    /**
     * Obtenir la banque de l'administrateur connecté
     */
    private function getAdminBank()
    {
        $user = \Illuminate\Support\Facades\Auth::user();
        return Bank::where('admin_id', $user->id)->firstOrFail();
    }

    /**
     * Afficher la liste des stocks de sang
     */
    public function index()
    {
        $bank = $this->getAdminBank();

        // Utiliser le helper pour obtenir les statistiques basées sur les poches
        $statistics = StockHelper::getDashboardStatistics($bank);
        $detailedStats = StockHelper::getDetailedStatistics($bank);

        // Récupérer tous les types de sang pour afficher ceux qui n'ont pas de stock
        $allBloodTypes = BloodType::orderBy('name')->get();

        // Créer un tableau avec tous les types de sang et leurs stocks
        $stockSummary = [];
        foreach ($allBloodTypes as $bloodType) {
            $bloodTypeStats = $detailedStats['by_blood_type'][$bloodType->name] ?? [];
            $stock = BloodStock::where('bank_id', $bank->id)
                ->where('blood_type_id', $bloodType->id)
                ->first();

            if ($stock) {
                // Calculer le statut basé sur les poches disponibles
                $availableBags = $bloodTypeStats['available'] ?? 0;
                $criticalLevelInBags = $stock->critical_level / 450; // Convertir ml en nombre de poches

                $isLow = $availableBags <= $criticalLevelInBags;
                $isCritical = $availableBags <= ($criticalLevelInBags * 0.5);
                $isHigh = $availableBags > ($criticalLevelInBags * 3);

                $stockSummary[] = [
                    'blood_type' => $bloodType,
                    'stock' => $stock,
                    'total_bags' => $bloodTypeStats['count'] ?? 0, // Total des poches
                    'available_bags' => $availableBags, // Poches disponibles
                    'reserved_bags' => $bloodTypeStats['reserved'] ?? 0, // Poches réservées
                    'volume_ml' => ($bloodTypeStats['count'] ?? 0) * 450, // Volume en ml
                    'volume_l' => $bloodTypeStats['volume_l'] ?? 0, // Volume en litres
                    'critical_level_ml' => $stock->critical_level, // Seuil critique en ml
                    'critical_level_bags' => $criticalLevelInBags, // Seuil critique en poches
                    'is_low' => $isLow,
                    'is_critical' => $isCritical,
                    'is_high' => $isHigh,
                    'has_stock' => true
                ];
            } else {
                // Pas de stock = critique
                $stockSummary[] = [
                    'blood_type' => $bloodType,
                    'stock' => null,
                    'total_bags' => 0,
                    'available_bags' => 0,
                    'reserved_bags' => 0,
                    'volume_ml' => 0,
                    'volume_l' => 0,
                    'critical_level_ml' => 0,
                    'critical_level_bags' => 0,
                    'is_low' => true,
                    'is_critical' => true,
                    'is_high' => false,
                    'has_stock' => false
                ];
            }
        }

        return view('admin.stocks.index', compact('stockSummary', 'bank', 'statistics'));
    }

    /**
     * Afficher le formulaire de création
     */
    public function create()
    {
        $bank = $this->getAdminBank();
        $bloodTypes = BloodType::orderBy('name')->get();

        // Récupérer les types de sang qui ont déjà un stock
        $existingStockTypes = BloodStock::where('bank_id', $bank->id)
            ->pluck('blood_type_id')
            ->toArray();

        // Séparer les types disponibles et existants
        $availableBloodTypes = $bloodTypes->whereNotIn('id', $existingStockTypes);
        $existingBloodTypes = $bloodTypes->whereIn('id', $existingStockTypes);

        return view('admin.stocks.create', compact('availableBloodTypes', 'existingBloodTypes', 'bank'));
    }

    /**
     * Enregistrer un nouveau stock
     */
    public function store(Request $request)
    {
        $bank = $this->getAdminBank();

        $request->validate([
            'blood_type_id' => 'required|exists:blood_types,id',
            'critical_level' => 'required|integer|min:1',
        ]);

        // Vérifier qu'il n'y a pas déjà un stock pour ce type de sang
        $existingStock = BloodStock::where('bank_id', $bank->id)
            ->where('blood_type_id', $request->blood_type_id)
            ->first();

        if ($existingStock) {
            return back()->with('error', 'Un stock existe déjà pour ce type de sang.')
                ->withInput();
        }

        // Créer le stock
        $stock = BloodStock::create([
            'bank_id' => $bank->id,
            'blood_type_id' => $request->blood_type_id,
            'quantity' => 0, // Sera calculé automatiquement
            'critical_level' => $request->critical_level,
            'status' => 'normal',
            'last_updated' => now(),
        ]);

        // Mettre à jour les statistiques du stock
        StockHelper::updateStockStatistics($stock);

        return redirect()->route('admin.stocks.index')
            ->with('success', 'Stock de sang créé avec succès.');
    }

    /**
     * Afficher le formulaire d'édition
     */
    public function edit(BloodStock $stock)
    {
        $bank = $this->getAdminBank();

        // Vérifier que le stock appartient à la banque de l'admin
        if ($stock->bank_id !== $bank->id) {
            abort(403);
        }

        $stock->load('bloodType');

        return view('admin.stocks.edit', compact('stock', 'bank'));
    }

    /**
     * Mettre à jour un stock
     */
    public function update(Request $request, BloodStock $stock)
    {
        $bank = $this->getAdminBank();

        // Vérifier que le stock appartient à la banque de l'admin
        if ($stock->bank_id !== $bank->id) {
            abort(403);
        }

        $request->validate([
            'critical_level' => 'required|integer|min:1',
        ]);

        $stock->update([
            'critical_level' => $request->critical_level,
            'last_updated' => now(),
        ]);

        // Mettre à jour les statistiques du stock
        StockHelper::updateStockStatistics($stock);

        return redirect()->route('admin.stocks.index')
            ->with('success', 'Stock de sang mis à jour avec succès.');
    }

    /**
     * Supprimer un stock
     */
    public function destroy(BloodStock $stock)
    {
        $bank = $this->getAdminBank();

        // Vérifier que le stock appartient à la banque de l'admin
        if ($stock->bank_id !== $bank->id) {
            abort(403);
        }

        // Vérifier qu'il n'y a pas de poches associées
        $bloodBagsCount = BloodBag::where('bank_id', $bank->id)
            ->where('blood_type_id', $stock->blood_type_id)
            ->whereIn('status', ['available', 'reserved'])
            ->count();

        if ($bloodBagsCount > 0) {
            return back()->with('error', 'Impossible de supprimer un stock qui contient des poches de sang.');
        }

        $stock->delete();

        return redirect()->route('admin.stocks.index')
            ->with('success', 'Stock de sang supprimé avec succès.');
    }

    /**
     * Créer des stocks pour plusieurs types de sang en une fois
     */
    public function createMultiple()
    {
        $bank = $this->getAdminBank();
        $bloodTypes = BloodType::orderBy('name')->get();

        // Récupérer les types de sang qui n'ont pas encore de stock
        $existingStockTypes = BloodStock::where('bank_id', $bank->id)
            ->pluck('blood_type_id')
            ->toArray();

        $availableBloodTypes = $bloodTypes->whereNotIn('id', $existingStockTypes);

        return view('admin.stocks.create-multiple', compact('availableBloodTypes', 'bank'));
    }

    /**
     * Enregistrer plusieurs stocks
     */
    public function storeMultiple(Request $request)
    {
        $bank = $this->getAdminBank();

        $request->validate([
            'stocks' => 'required|array|min:1',
            'stocks.*.blood_type_id' => 'required|exists:blood_types,id',
            'stocks.*.critical_level' => 'required|integer|min:1',
        ]);

        $createdCount = 0;
        $errors = [];

        foreach ($request->stocks as $stockData) {
            // Vérifier qu'il n'y a pas déjà un stock pour ce type de sang
            $existingStock = BloodStock::where('bank_id', $bank->id)
                ->where('blood_type_id', $stockData['blood_type_id'])
                ->first();

            if ($existingStock) {
                $bloodType = BloodType::find($stockData['blood_type_id']);
                $errors[] = "Un stock existe déjà pour le type {$bloodType->name}.";
                continue;
            }

            // Créer le stock
            $stock = BloodStock::create([
                'bank_id' => $bank->id,
                'blood_type_id' => $stockData['blood_type_id'],
                'quantity' => 0, // Sera calculé automatiquement
                'critical_level' => $stockData['critical_level'],
                'status' => 'normal',
                'last_updated' => now(),
            ]);

            // Mettre à jour les statistiques du stock
            StockHelper::updateStockStatistics($stock);

            $createdCount++;
        }

        if ($createdCount > 0) {
            return redirect()->route('admin.stocks.index')
                ->with('success', "{$createdCount} stock(s) créé(s) avec succès.");
        } else {
            return redirect()->route('admin.stocks.index')
                ->with('error', 'Aucun stock n\'a pu être créé. ' . implode(', ', $errors));
        }
    }
}
