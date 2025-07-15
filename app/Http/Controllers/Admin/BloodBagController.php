<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bank;
use App\Models\BloodBag;
use App\Models\BloodType;
use App\Models\BloodBagReservation;
use App\Models\BloodBagMovement;
use App\Helpers\StockHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\BloodStock; // Added this import

class BloodBagController extends Controller
{
    /**
     * Obtenir la banque de l'administrateur connecté
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
     * Afficher la liste des poches de sang
     */
    public function index(Request $request)
    {
        $bank = $this->getAdminBank();

        $query = BloodBag::where('bank_id', $bank->id)
            ->with(['bloodType', 'donor', 'activeReservation']);

        // Filtres
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('blood_type_id')) {
            $query->where('blood_type_id', $request->blood_type_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('bag_number', 'like', "%{$search}%")
                  ->orWhere('reserved_for_patient', 'like', "%{$search}%")
                  ->orWhere('reserved_for_hospital', 'like', "%{$search}%");
            });
        }

        $bloodBags = $query->orderBy('created_at', 'desc')->paginate(20);
        $bloodTypes = BloodType::orderBy('name')->get();

        return view('admin.blood-bags.index', compact('bloodBags', 'bloodTypes', 'bank'));
    }

    /**
     * Afficher les détails d'une poche
     */
    public function show(BloodBag $bloodBag)
    {
        $bank = $this->getAdminBank();

        // Vérifier que la poche appartient à la banque de l'admin
        if ($bloodBag->bank_id !== $bank->id) {
            abort(403);
        }

        $bloodBag->load(['bloodType', 'donor', 'donation', 'movements', 'reservations']);

        return view('admin.blood-bags.show', compact('bloodBag', 'bank'));
    }

    /**
     * Afficher le formulaire de réservation
     */
    public function reserve(BloodBag $bloodBag)
    {
        $bank = $this->getAdminBank();

        // Vérifier que la poche appartient à la banque de l'admin
        if ($bloodBag->bank_id !== $bank->id) {
            abort(403);
        }

        // Vérifier que la poche est disponible
        if (!$bloodBag->isAvailable()) {
            return back()->with('error', 'Cette poche n\'est pas disponible pour réservation.');
        }

        return view('admin.blood-bags.reserve', compact('bloodBag', 'bank'));
    }

    /**
     * Effectuer une réservation
     */
    public function storeReservation(Request $request, BloodBag $bloodBag)
    {
        $bank = $this->getAdminBank();

        // Vérifier que la poche appartient à la banque de l'admin
        if ($bloodBag->bank_id !== $bank->id) {
            abort(403);
        }

        $request->validate([
            'patient_name' => 'required|string|max:255',
            'patient_id' => 'nullable|string|max:100',
            'hospital_name' => 'nullable|string|max:255',
            'doctor_name' => 'nullable|string|max:255',
            'urgency_level' => 'required|in:normal,urgent,critical',
            'surgery_date' => 'nullable|date',
            'duration' => 'required|integer|min:1|max:72', // Durée en heures
            'notes' => 'nullable|string|max:500',
        ]);

        try {
            $patientData = [
                'patient_name' => $request->patient_name,
                'patient_id' => $request->patient_id,
                'hospital_name' => $request->hospital_name,
                'doctor_name' => $request->doctor_name,
                'urgency_level' => $request->urgency_level,
                'surgery_date' => $request->surgery_date,
                'notes' => $request->notes,
                'requested_by' => Auth::user()->name,
            ];

            // Vérifier que la poche est disponible
            if (!$bloodBag->isAvailable()) {
                throw new \Exception('Cette poche n\'est pas disponible pour réservation.');
            }

            $reservation = $bloodBag->reserveForPatient($patientData, $request->duration);

            // Mettre à jour les statistiques du stock
            $bloodStock = BloodStock::where('bank_id', $bloodBag->bank_id)
                ->where('blood_type_id', $bloodBag->blood_type_id)
                ->first();

            if ($bloodStock) {
                StockHelper::updateStockStatistics($bloodStock);
            }

            return redirect()->route('admin.blood-bags.show', $bloodBag)
                ->with('success', 'Poche réservée avec succès pour ' . $request->patient_name);

        } catch (\Exception $e) {
            Log::error('Erreur lors de la réservation: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return back()->with('error', 'Erreur lors de la réservation : ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Effectuer la sortie (transfusion)
     */
    public function transfuse(Request $request, BloodBag $bloodBag)
    {
        $bank = $this->getAdminBank();

        // Vérifier que la poche appartient à la banque de l'admin
        if ($bloodBag->bank_id !== $bank->id) {
            abort(403);
        }

        $request->validate([
            'notes' => 'nullable|string|max:500',
        ]);

        try {
            $transfusionData = [
                'notes' => $request->notes,
            ];

            $reservation = $bloodBag->transfuse(Auth::user()->name, $transfusionData);

            // Mettre à jour les statistiques du stock
            $bloodStock = BloodStock::where('bank_id', $bloodBag->bank_id)
                ->where('blood_type_id', $bloodBag->blood_type_id)
                ->first();

            if ($bloodStock) {
                StockHelper::updateStockStatistics($bloodStock);
            }

            return redirect()->route('admin.blood-bags.show', $bloodBag)
                ->with('success', 'Transfusion effectuée avec succès');

        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la transfusion : ' . $e->getMessage());
        }
    }

    /**
     * Annuler une réservation
     */
    public function cancelReservation(Request $request, BloodBag $bloodBag)
    {
        $bank = $this->getAdminBank();

        // Vérifier que la poche appartient à la banque de l'admin
        if ($bloodBag->bank_id !== $bank->id) {
            abort(403);
        }

        $request->validate([
            'reason' => 'required|string|max:500',
        ]);

        try {
            $bloodBag->cancelReservation($request->reason);

            // Mettre à jour les statistiques du stock
            $bloodStock = BloodStock::where('bank_id', $bloodBag->bank_id)
                ->where('blood_type_id', $bloodBag->blood_type_id)
                ->first();

            if ($bloodStock) {
                StockHelper::updateStockStatistics($bloodStock);
            }

            return redirect()->route('admin.blood-bags.show', $bloodBag)
                ->with('success', 'Réservation annulée avec succès');

        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de l\'annulation : ' . $e->getMessage());
        }
    }

    /**
     * Jeter une poche (destruction)
     */
    public function discard(Request $request, BloodBag $bloodBag)
    {
        $bank = $this->getAdminBank();

        // Vérifier que la poche appartient à la banque de l'admin
        if ($bloodBag->bank_id !== $bank->id) {
            abort(403);
        }

        $request->validate([
            'reason' => 'required|string|max:500',
        ]);

        try {
            $bloodBag->discard($request->reason, Auth::user()->name);

            // Mettre à jour les statistiques du stock
            $bloodStock = BloodStock::where('bank_id', $bloodBag->bank_id)
                ->where('blood_type_id', $bloodBag->blood_type_id)
                ->first();

            if ($bloodStock) {
                StockHelper::updateStockStatistics($bloodStock);
            }

            return redirect()->route('admin.blood-bags.index')
                ->with('success', 'Poche détruite avec succès');

        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la destruction : ' . $e->getMessage());
        }
    }

    /**
     * Afficher l'historique des mouvements
     */
    public function movements(Request $request)
    {
        $bank = $this->getAdminBank();

        $query = BloodBagMovement::where('bank_id', $bank->id)
            ->with(['bloodBag.bloodType', 'bloodBag.donor']);

        // Filtres
        if ($request->filled('movement_type')) {
            $query->where('movement_type', $request->movement_type);
        }

        if ($request->filled('blood_type_id')) {
            $query->whereHas('bloodBag', function ($q) use ($request) {
                $q->where('blood_type_id', $request->blood_type_id);
            });
        }

        if ($request->filled('date_from')) {
            $query->where('movement_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->where('movement_date', '<=', $request->date_to);
        }

        $movements = $query->orderBy('movement_date', 'desc')->paginate(20);
        $bloodTypes = BloodType::orderBy('name')->get();

        return view('admin.blood-bags.movements', compact('movements', 'bank', 'bloodTypes'));
    }

    /**
     * Afficher les réservations actives
     */
    public function reservations(Request $request)
    {
        $bank = $this->getAdminBank();

        $query = BloodBagReservation::where('bank_id', $bank->id)
            ->where('status', 'active')
            ->with(['bloodBag.bloodType']);

        // Filtres
        if ($request->filled('urgency_level')) {
            $query->where('urgency_level', $request->urgency_level);
        }

        if ($request->filled('blood_type_id')) {
            $query->whereHas('bloodBag', function ($q) use ($request) {
                $q->where('blood_type_id', $request->blood_type_id);
            });
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('patient_name', 'like', "%{$search}%")
                  ->orWhere('hospital_name', 'like', "%{$search}%")
                  ->orWhere('doctor_name', 'like', "%{$search}%")
                  ->orWhere('patient_id', 'like', "%{$search}%");
            });
        }

        $activeReservations = $query->orderBy('expiry_date', 'asc')->paginate(20);
        $bloodTypes = BloodType::orderBy('name')->get();

        return view('admin.blood-bags.reservations', compact('activeReservations', 'bank', 'bloodTypes'));
    }

    /**
     * Afficher les poches expirant bientôt
     */
    public function expiringSoon()
    {
        $bank = $this->getAdminBank();

        $expiringBags = BloodBag::where('bank_id', $bank->id)
            ->where('status', 'available')
            ->where('expiry_date', '<=', now()->addDays(7))
            ->with(['bloodType', 'donor'])
            ->orderBy('expiry_date', 'asc')
            ->paginate(20);

        $bloodTypes = BloodType::orderBy('name')->get();

        return view('admin.blood-bags.expiring-soon', compact('expiringBags', 'bank', 'bloodTypes'));
    }

    /**
     * Statistiques des poches
     */
    public function statistics()
    {
        $bank = $this->getAdminBank();

        $statistics = StockHelper::getDashboardStatistics($bank);

        // Statistiques supplémentaires
        $statistics['total_movements_today'] = BloodBagMovement::where('bank_id', $bank->id)
            ->whereDate('movement_date', today())
            ->count();

        $statistics['active_reservations'] = BloodBagReservation::where('bank_id', $bank->id)
            ->where('status', 'active')
            ->count();

        return view('admin.blood-bags.statistics', compact('statistics', 'bank'));
    }

    /**
     * Annuler une réservation spécifique
     */
    public function cancelSpecificReservation(Request $request, BloodBagReservation $reservation)
    {
        $bank = $this->getAdminBank();

        // Vérifier que la réservation appartient à la banque de l'admin
        if ($reservation->bank_id !== $bank->id) {
            abort(403);
        }

        $request->validate([
            'reason' => 'required|string|max:500',
        ]);

        try {
            // Annuler la réservation
            $reservation->update([
                'status' => 'cancelled',
                'cancelled_at' => now(),
                'cancelled_by' => Auth::user()->name,
                'cancellation_reason' => $request->reason,
            ]);

            // Libérer la poche
            $bloodBag = $reservation->bloodBag;
            $bloodBag->update([
                'status' => 'available',
                'reserved_for_patient' => null,
                'reserved_for_hospital' => null,
                'reserved_until' => null,
            ]);

            // Mettre à jour les statistiques du stock
            $bloodStock = BloodStock::where('bank_id', $bloodBag->bank_id)
                ->where('blood_type_id', $bloodBag->blood_type_id)
                ->first();

            if ($bloodStock) {
                StockHelper::updateStockStatistics($bloodStock);
            }

            return redirect()->route('admin.blood-bags.reservations')
                ->with('success', 'Réservation annulée avec succès');

        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de l\'annulation : ' . $e->getMessage());
        }
    }

    /**
     * Jeter en masse les poches expirées
     */
    public function bulkDiscard(Request $request)
    {
        $bank = $this->getAdminBank();

        $request->validate([
            'reason' => 'required|string|max:500',
        ]);

        try {
            // Récupérer toutes les poches expirées de la banque
            $expiredBags = BloodBag::where('bank_id', $bank->id)
                ->where('status', 'available')
                ->where('expiry_date', '<=', now())
                ->get();

            $discardedCount = 0;

            foreach ($expiredBags as $bloodBag) {
                // Jeter chaque poche expirée
                $bloodBag->discard($request->reason, Auth::user()->name);
                $discardedCount++;
            }

            // Mettre à jour les statistiques de tous les stocks
            StockHelper::updateAllStockStatistics($bank);

            return redirect()->route('admin.blood-bags.expiring-soon')
                ->with('success', "{$discardedCount} poche(s) expirée(s) jetée(s) avec succès");

        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la destruction en masse : ' . $e->getMessage());
        }
    }
}
