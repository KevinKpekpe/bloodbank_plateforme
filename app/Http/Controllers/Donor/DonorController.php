<?php

namespace App\Http\Controllers\Donor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DonorController extends Controller
{
    /**
     * Afficher le dashboard du donneur
     */
    public function dashboard()
    {
        $user = Auth::user();
        $donor = $user->donor;

        if (!$donor) {
            return redirect()->route('home')
                ->with('error', 'Profil donneur non trouvé.');
        }

        // Statistiques du donneur
        $stats = [
            'total_donations' => $donor->donations()->count(),
            'total_volume' => $donor->donations()->sum('volume'),
            'last_donation' => $donor->donations()->latest()->first(),
            'upcoming_appointments' => $donor->appointments()
                ->where('status', 'confirmed')
                ->where('appointment_date', '>', now())
                ->count(),
            'pending_appointments' => $donor->appointments()
                ->where('status', 'pending')
                ->count(),
        ];

        return view('donor.dashboard', compact('donor', 'stats'));
    }

    /**
     * Afficher le profil du donneur
     */
    public function profile()
    {
        $user = Auth::user();
        $donor = $user->donor;

        if (!$donor) {
            return redirect()->route('home')
                ->with('error', 'Profil donneur non trouvé.');
        }

        return view('donor.profile', compact('donor'));
    }

    /**
     * Mettre à jour le profil du donneur
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        $donor = $user->donor;

        if (!$donor) {
            return redirect()->route('home')
                ->with('error', 'Profil donneur non trouvé.');
        }

        $request->validate([
            'firstname' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'birthdate' => 'required|date|before:today',
            'gender' => 'required|in:male,female,other',
        ]);

        // Mettre à jour les informations utilisateur
        $user->update([
            'phone_number' => $request->phone_number,
        ]);

        // Mettre à jour les informations donneur
        $donor->update([
            'firstname' => $request->firstname,
            'surname' => $request->surname,
            'phone_number' => $request->phone_number,
            'address' => $request->address,
            'birthdate' => $request->birthdate,
            'gender' => $request->gender,
        ]);

        return redirect()->route('donor.profile')
            ->with('success', 'Profil mis à jour avec succès.');
    }

    /**
     * Afficher les statistiques du donneur
     */
    public function statistics()
    {
        $user = Auth::user();
        $donor = $user->donor;

        if (!$donor) {
            return redirect()->route('home')
                ->with('error', 'Profil donneur non trouvé.');
        }

        // Statistiques détaillées
        $donationsByYear = $donor->donations()
            ->selectRaw('YEAR(donation_date) as year, COUNT(*) as count, SUM(volume) as total_volume')
            ->groupBy('year')
            ->orderBy('year', 'desc')
            ->get();

        $donationsByBank = $donor->donations()
            ->with('bank')
            ->selectRaw('bank_id, COUNT(*) as count, SUM(volume) as total_volume')
            ->groupBy('bank_id')
            ->orderBy('count', 'desc')
            ->get();

        $recentDonations = $donor->donations()
            ->with(['bank', 'bloodType'])
            ->latest('donation_date')
            ->take(10)
            ->get();

        return view('donor.statistics', compact('donor', 'donationsByYear', 'donationsByBank', 'recentDonations'));
    }
}