<?php

namespace App\Http\Controllers\Donor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DonationController extends Controller
{
    /**
     * Afficher l'historique des dons du donneur
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $donor = $user->donor;

        if (!$donor) {
            return redirect()->route('home')
                ->with('error', 'Profil donneur non trouvé.');
        }

        $query = $donor->donations()->with(['bank', 'bloodType', 'appointment']);

        // Filtres
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('bank_id')) {
            $query->where('bank_id', $request->bank_id);
        }

        if ($request->filled('date_from')) {
            $query->where('donation_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->where('donation_date', '<=', $request->date_to);
        }

        $donations = $query->orderBy('donation_date', 'desc')->paginate(15);

        // Statistiques pour les filtres
        $totalDonations = $donor->donations()->count();
        $totalVolume = $donor->donations()->sum('volume');
        $lastDonation = $donor->donations()->latest()->first();

        // Données pour les filtres
        $banks = $donor->donations()->with('bank')->get()->pluck('bank')->unique();
        $statuses = ['collected', 'processed', 'available', 'expired'];

        return view('donor.donations.index', compact(
            'donations',
            'totalDonations',
            'totalVolume',
            'lastDonation',
            'banks',
            'statuses'
        ));
    }

    /**
     * Afficher les détails d'un don
     */
    public function show($id)
    {
        $user = Auth::user();
        $donor = $user->donor;

        if (!$donor) {
            return redirect()->route('home')
                ->with('error', 'Profil donneur non trouvé.');
        }

        $donation = $donor->donations()
            ->with(['bank', 'bloodType', 'appointment'])
            ->findOrFail($id);

        return view('donor.donations.show', compact('donation'));
    }

    /**
     * Afficher les statistiques détaillées des dons
     */
    public function statistics()
    {
        $user = Auth::user();
        $donor = $user->donor;

        if (!$donor) {
            return redirect()->route('home')
                ->with('error', 'Profil donneur non trouvé.');
        }

        // Statistiques par année
        $donationsByYear = $donor->donations()
            ->selectRaw('YEAR(donation_date) as year, COUNT(*) as count, SUM(volume) as total_volume')
            ->groupBy('year')
            ->orderBy('year', 'desc')
            ->get();

        // Statistiques par mois (année en cours)
        $donationsByMonth = $donor->donations()
            ->whereYear('donation_date', date('Y'))
            ->selectRaw('MONTH(donation_date) as month, COUNT(*) as count, SUM(volume) as total_volume')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Statistiques par banque
        $donationsByBank = $donor->donations()
            ->with('bank')
            ->selectRaw('bank_id, COUNT(*) as count, SUM(volume) as total_volume')
            ->groupBy('bank_id')
            ->orderBy('count', 'desc')
            ->get();

        // Statistiques par statut
        $donationsByStatus = $donor->donations()
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->get();

        // Derniers dons
        $recentDonations = $donor->donations()
            ->with(['bank', 'bloodType'])
            ->latest('donation_date')
            ->take(5)
            ->get();

        return view('donor.donations.statistics', compact(
            'donor',
            'donationsByYear',
            'donationsByMonth',
            'donationsByBank',
            'donationsByStatus',
            'recentDonations'
        ));
    }

    /**
     * Télécharger le certificat de don
     */
    public function certificate($id)
    {
        $user = Auth::user();
        $donor = $user->donor;

        if (!$donor) {
            return redirect()->route('home')
                ->with('error', 'Profil donneur non trouvé.');
        }

        $donation = $donor->donations()
            ->with(['bank', 'bloodType'])
            ->findOrFail($id);

        // Vérifier que le don est disponible (pas expiré)
        if ($donation->status === 'expired') {
            return back()->with('error', 'Ce don a expiré et ne peut plus faire l\'objet d\'un certificat.');
        }

        // TODO: Générer le PDF du certificat
        // Pour l'instant, on redirige vers la vue du don
        return view('donor.donations.certificate', compact('donation'));
    }
}
