<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Donation;
use App\Models\BloodType;
use App\Models\Appointment;
use App\Models\BloodBag;
use App\Models\BloodStock;
use App\Helpers\StockHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DonationController extends Controller
{
    /**
     * Récupérer la banque de l'admin connecté
     */
    private function getAdminBank()
    {
        $user = Auth::user();
        $bank = $user->managedBank;

        if (!$bank) {
            abort(403, 'Vous n\'êtes pas associé à une banque de sang.');
        }

        return $bank;
    }

    /**
     * Afficher la liste des dons de la banque
     */
    public function index(Request $request)
    {
        $bank = $this->getAdminBank();

        $query = Donation::with(['donor.user', 'bloodType', 'bloodBags'])
            ->where('bank_id', $bank->id);

        // Filtres
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('blood_type_id')) {
            $query->where('blood_type_id', $request->blood_type_id);
        }

        if ($request->filled('date_from')) {
            $query->where('donation_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->where('donation_date', '<=', $request->date_to);
        }

        $donations = $query->orderBy('donation_date', 'desc')
            ->paginate(15);

        $bloodTypes = BloodType::all();
        $statuses = ['collected', 'processed', 'available', 'expired', 'used'];

        return view('admin.donations.index', compact('donations', 'bloodTypes', 'statuses'));
    }

    /**
     * Afficher les détails d'un don
     */
    public function show(Donation $donation)
    {
        $bank = $this->getAdminBank();

        // Vérifier que le don appartient à la banque de l'admin
        if ($donation->bank_id !== $bank->id) {
            abort(403);
        }

        $donation->load(['donor.user', 'bloodType', 'appointment', 'bloodBags.bloodType', 'bloodBags.movements']);

        return view('admin.donations.show', compact('donation'));
    }

    /**
     * Marquer un don comme traité et créer les poches de sang
     */
    public function process(Donation $donation)
    {
        $bank = $this->getAdminBank();

        // Vérifier que le don appartient à la banque de l'admin
        if ($donation->bank_id !== $bank->id) {
            abort(403);
        }

        if ($donation->status !== 'collected') {
            return back()->with('error', 'Ce don ne peut pas être traité.');
        }

        try {
            // Calculer le nombre de poches à créer (450ml par poche)
            $totalVolumeMl = $donation->quantity;
            $bagsToCreate = floor($totalVolumeMl / 450);
            $remainingVolume = $totalVolumeMl % 450;

            // Créer les poches de 450ml
            for ($i = 0; $i < $bagsToCreate; $i++) {
                $bagNumber = $this->generateBagNumber($bank);

                BloodBag::create([
                    'bag_number' => $bagNumber,
                    'donation_id' => $donation->id,
                    'donor_id' => $donation->donor_id,
                    'bank_id' => $donation->bank_id,
                    'blood_type_id' => $donation->blood_type_id,
                    'volume_ml' => 450,
                    'collection_date' => $donation->donation_date,
                    'expiry_date' => $donation->donation_date->addDays(42), // 42 jours pour les poches
                    'status' => 'available',
                    'notes' => "Poche créée automatiquement lors du traitement du don #{$donation->id}"
                ]);
            }

            // Créer une poche supplémentaire avec le volume restant si > 200ml
            if ($remainingVolume >= 200) {
                $bagNumber = $this->generateBagNumber($bank);

                BloodBag::create([
                    'bag_number' => $bagNumber,
                    'donation_id' => $donation->id,
                    'donor_id' => $donation->donor_id,
                    'bank_id' => $donation->bank_id,
                    'blood_type_id' => $donation->blood_type_id,
                    'volume_ml' => $remainingVolume,
                    'collection_date' => $donation->donation_date,
                    'expiry_date' => $donation->donation_date->addDays(42),
                    'status' => 'available',
                    'notes' => "Poche créée automatiquement lors du traitement du don #{$donation->id} (volume restant)"
                ]);
            }

            // Marquer le don comme traité
            $donation->update([
                'status' => 'processed',
                'processed_at' => now()
            ]);

            // Mettre à jour les statistiques du stock
            $bloodStock = BloodStock::where('bank_id', $bank->id)
                ->where('blood_type_id', $donation->blood_type_id)
                ->first();

            if ($bloodStock) {
                StockHelper::updateStockStatistics($bloodStock);
            }

            return back()->with('success', 'Don traité avec succès. ' . ($bagsToCreate + ($remainingVolume >= 200 ? 1 : 0)) . ' poche(s) créée(s).');

        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors du traitement : ' . $e->getMessage());
        }
    }

    /**
     * Marquer un don comme disponible (les poches sont déjà disponibles après traitement)
     */
    public function makeAvailable(Donation $donation)
    {
        $bank = $this->getAdminBank();

        // Vérifier que le don appartient à la banque de l'admin
        if ($donation->bank_id !== $bank->id) {
            abort(403);
        }

        if ($donation->status !== 'processed') {
            return back()->with('error', 'Ce don doit être traité avant d\'être disponible.');
        }

        $donation->update([
            'status' => 'available',
            'available_at' => now()
        ]);

        return back()->with('success', 'Don marqué comme disponible.');
    }

    /**
     * Marquer un don comme expiré (marquer toutes les poches comme expirées)
     */
    public function expire(Donation $donation)
    {
        $bank = $this->getAdminBank();

        // Vérifier que le don appartient à la banque de l'admin
        if ($donation->bank_id !== $bank->id) {
            abort(403);
        }

        if ($donation->status !== 'available') {
            return back()->with('error', 'Seuls les dons disponibles peuvent être expirés.');
        }

        try {
            // Marquer toutes les poches du don comme expirées
            $bloodBags = $donation->bloodBags()->where('status', 'available')->get();

            foreach ($bloodBags as $bloodBag) {
                $bloodBag->expire('Expiration du don parent');
            }

            $donation->update([
                'status' => 'expired',
                'expired_at' => now()
            ]);

            // Mettre à jour les statistiques du stock
            $bloodStock = BloodStock::where('bank_id', $bank->id)
                ->where('blood_type_id', $donation->blood_type_id)
                ->first();

            if ($bloodStock) {
                StockHelper::updateStockStatistics($bloodStock);
            }

            return back()->with('success', 'Don et ' . $bloodBags->count() . ' poche(s) marqués comme expirés.');

        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de l\'expiration : ' . $e->getMessage());
        }
    }

    /**
     * Marquer un don comme utilisé (toutes les poches ont été transfusées)
     */
    public function use(Donation $donation)
    {
        $bank = $this->getAdminBank();

        // Vérifier que le don appartient à la banque de l'admin
        if ($donation->bank_id !== $bank->id) {
            abort(403);
        }

        if ($donation->status !== 'available') {
            return back()->with('error', 'Seuls les dons disponibles peuvent être utilisés.');
        }

        // Vérifier que toutes les poches ont été transfusées
        $availableBags = $donation->bloodBags()->where('status', 'available')->count();
        if ($availableBags > 0) {
            return back()->with('error', 'Toutes les poches du don doivent être transfusées avant de marquer le don comme utilisé.');
        }

        $donation->update([
            'status' => 'used',
            'used_at' => now()
        ]);

        return back()->with('success', 'Don marqué comme utilisé.');
    }

    /**
     * Statistiques des dons
     */
    public function statistics()
    {
        $bank = $this->getAdminBank();

        // Statistiques par statut
        $statusStats = Donation::where('bank_id', $bank->id)
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        // Dons par groupe sanguin
        $bloodTypeStats = Donation::where('bank_id', $bank->id)
            ->with('bloodType')
            ->selectRaw('blood_type_id, COUNT(*) as count, SUM(volume) as total_volume')
            ->groupBy('blood_type_id')
            ->get();

        // Dons par mois (6 derniers mois)
        $monthlyStats = Donation::where('bank_id', $bank->id)
            ->where('donation_date', '>=', now()->subMonths(6))
            ->selectRaw('strftime("%Y-%m", donation_date) as month, COUNT(*) as count, SUM(volume) as total_volume')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Dons du jour
        $todayDonations = Donation::with(['donor.user', 'bloodType', 'bloodBags'])
            ->where('bank_id', $bank->id)
            ->whereDate('donation_date', today())
            ->orderBy('donation_date', 'desc')
            ->get();

        return view('admin.donations.statistics', compact('statusStats', 'bloodTypeStats', 'monthlyStats', 'todayDonations'));
    }

    /**
     * Gestion des stocks (maintenant basée sur les poches)
     */
    public function inventory()
    {
        $bank = $this->getAdminBank();

        // Utiliser le helper pour obtenir les statistiques basées sur les poches
        $statistics = StockHelper::getDashboardStatistics($bank);
        $detailedStats = StockHelper::getDetailedStatistics($bank);

        // Stock disponible par groupe sanguin depuis la table blood_stocks
        $inventory = BloodType::with(['bloodStocks' => function ($query) use ($bank) {
            $query->where('bank_id', $bank->id);
        }])
        ->get()
        ->map(function ($bloodType) use ($detailedStats) {
            $stock = $bloodType->bloodStocks->first();
            $bloodTypeStats = $detailedStats['by_blood_type'][$bloodType->name] ?? [];

            return [
                'blood_type' => $bloodType,
                'available_units' => $bloodTypeStats['available'] ?? 0,
                'total_volume' => $bloodTypeStats['volume_l'] ?? 0,
                'critical_level' => $stock ? $stock->critical_level : 0,
                'is_low' => $stock ? $stock->isLow() : true,
                'is_critical' => $stock ? $stock->isCritical() : true,
                'expiring_soon' => $bloodTypeStats['expiring_soon'] ?? 0
            ];
        });

        // Poches expirant bientôt (pour information)
        $expiringSoon = BloodBag::with(['donor', 'bloodType'])
            ->where('bank_id', $bank->id)
            ->where('status', 'available')
            ->where('expiry_date', '<=', now()->addDays(7))
            ->where('expiry_date', '>', now())
            ->orderBy('expiry_date')
            ->get();

        return view('admin.donations.inventory', compact('inventory', 'expiringSoon', 'statistics'));
    }

    /**
     * Générer un numéro de poche unique
     */
    private function generateBagNumber($bank)
    {
        $prefix = strtoupper(substr($bank->name, 0, 3));
        $year = date('Y');
        $month = date('m');

        // Trouver le dernier numéro pour ce mois
        $lastBag = BloodBag::where('bag_number', 'like', "{$prefix}{$year}{$month}%")
            ->orderBy('bag_number', 'desc')
            ->first();

        if ($lastBag) {
            $lastNumber = intval(substr($lastBag->bag_number, -4));
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return $prefix . $year . $month . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }
}
