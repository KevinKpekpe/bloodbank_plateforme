<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Donation;
use App\Models\BloodType;
use App\Models\Appointment;
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

        $query = Donation::with(['donor.user', 'bloodType'])
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

        $donation->load(['donor.user', 'bloodType', 'appointment']);

        return view('admin.donations.show', compact('donation'));
    }

    /**
     * Marquer un don comme traité
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

        $donation->update([
            'status' => 'processed',
            'processed_at' => now()
        ]);

        return back()->with('success', 'Don marqué comme traité.');
    }

    /**
     * Marquer un don comme disponible
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
     * Marquer un don comme expiré
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

        $donation->update([
            'status' => 'expired',
            'expired_at' => now()
        ]);

        return back()->with('success', 'Don marqué comme expiré.');
    }

    /**
     * Marquer un don comme utilisé
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
            ->selectRaw('DATE_FORMAT(donation_date, "%Y-%m") as month, COUNT(*) as count, SUM(volume) as total_volume')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Dons du jour
        $todayDonations = Donation::with(['donor.user', 'bloodType'])
            ->where('bank_id', $bank->id)
            ->whereDate('donation_date', today())
            ->orderBy('donation_date', 'desc')
            ->get();

        return view('admin.donations.statistics', compact('statusStats', 'bloodTypeStats', 'monthlyStats', 'todayDonations'));
    }

    /**
     * Gestion des stocks
     */
    public function inventory()
    {
        $bank = $this->getAdminBank();

        // Stock disponible par groupe sanguin
        $inventory = BloodType::with(['donations' => function ($query) use ($bank) {
            $query->where('bank_id', $bank->id)
                  ->where('status', 'available');
        }])
        ->get()
        ->map(function ($bloodType) {
            return [
                'blood_type' => $bloodType,
                'available_units' => $bloodType->donations->count(),
                'total_volume' => $bloodType->donations->sum('volume'),
                'expiring_soon' => $bloodType->donations->filter(function ($donation) {
                    return Carbon::parse($donation->available_at)->addDays(35)->isPast();
                })->count()
            ];
        });

        // Dons expirant bientôt
        $expiringSoon = Donation::with(['donor', 'bloodType'])
            ->where('bank_id', $bank->id)
            ->where('status', 'available')
            ->where('available_at', '<=', now()->subDays(35))
            ->orderBy('available_at')
            ->get();

        return view('admin.donations.inventory', compact('inventory', 'expiringSoon'));
    }
}
