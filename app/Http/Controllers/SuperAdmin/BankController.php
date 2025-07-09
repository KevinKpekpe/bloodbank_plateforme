<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Bank;
use App\Models\User;
use App\Models\Donation;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class BankController extends Controller
{
    /**
     * Afficher la liste des banques de sang
     */
    public function index(Request $request)
    {
        $query = Bank::withCount(['appointments', 'donations']);

        // Filtres
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('address', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $banks = $query->orderBy('name')->paginate(15);

        // Calculer le nombre d'utilisateurs pour chaque banque
        foreach ($banks as $bank) {
            $userIds = collect();

            // Utilisateurs avec des rendez-vous
            $appointmentUserIds = $bank->appointments()
                ->join('donors', 'appointments.donor_id', '=', 'donors.id')
                ->pluck('donors.user_id');

            // Utilisateurs avec des dons
            $donationUserIds = $bank->donations()
                ->join('donors', 'donations.donor_id', '=', 'donors.id')
                ->pluck('donors.user_id');

            // Combiner et dédupliquer
            $bank->users_count = $appointmentUserIds->merge($donationUserIds)->unique()->count();
        }

        return view('superadmin.banks.index', compact('banks'));
    }

    /**
     * Afficher le formulaire de création
     */
    public function create()
    {
        return view('superadmin.banks.create');
    }

    /**
     * Enregistrer une nouvelle banque
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:banks',
            'address' => 'required|string|max:500',
            'contact_phone' => 'required|string|max:20',
            'contact_email' => 'required|email|max:255',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'admin_name' => 'required|string|max:255',
            'admin_email' => 'required|email|max:255|unique:users,email',
            'admin_phone' => 'required|string|max:20',
            'admin_password' => 'required|string|min:8|confirmed'
        ]);

        // Créer l'administrateur de la banque
        $admin = User::create([
            'name' => $request->admin_name,
            'email' => $request->admin_email,
            'phone_number' => $request->admin_phone,
            'password' => Hash::make($request->admin_password),
            'role' => 'admin_banque',
            'email_verified_at' => now()
        ]);

        // Créer la banque en associant l'admin
        $bank = Bank::create([
            'name' => $request->name,
            'address' => $request->address,
            'contact_phone' => $request->contact_phone,
            'contact_email' => $request->contact_email,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'status' => 'active',
            'admin_id' => $admin->id
        ]);

        return redirect()->route('superadmin.banks.index')
            ->with('success', 'Banque de sang créée avec succès.');
    }

    /**
     * Afficher les détails d'une banque
     */
    public function show(Bank $bank)
    {
        $bank->load(['admin', 'appointments', 'donations']);

        // Statistiques de la banque
        $stats = [
            'admin' => $bank->admin,
            'total_appointments' => $bank->appointments()->count(),
            'total_donations' => $bank->donations()->count(),
            'pending_appointments' => $bank->appointments()->where('status', 'pending')->count(),
            'available_donations' => $bank->donations()->where('status', 'available')->count()
        ];

        return view('superadmin.banks.show', compact('bank', 'stats'));
    }

    /**
     * Afficher le formulaire d'édition
     */
    public function edit(Bank $bank)
    {
        return view('superadmin.banks.edit', compact('bank'));
    }

    /**
     * Mettre à jour une banque
     */
    public function update(Request $request, Bank $bank)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:banks,name,' . $bank->id,
            'address' => 'required|string|max:500',
            'contact_phone' => 'required|string|max:20',
            'contact_email' => 'required|email|max:255',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'status' => 'required|in:active,inactive'
        ]);

        $bank->update($request->all());

        return redirect()->route('superadmin.banks.show', $bank)
            ->with('success', 'Banque de sang mise à jour avec succès.');
    }

    /**
     * Supprimer une banque
     */
    public function destroy(Bank $bank)
    {
        // Vérifier qu'il n'y a pas de données associées
        if ($bank->appointments()->count() > 0 || $bank->donations()->count() > 0) {
            return back()->with('error', 'Impossible de supprimer cette banque car elle contient des données associées.');
        }

        // Supprimer l'admin associé
        if ($bank->admin) {
            $bank->admin->delete();
        }

        $bank->delete();

        return redirect()->route('superadmin.banks.index')
            ->with('success', 'Banque de sang supprimée avec succès.');
    }

    /**
     * Statistiques globales des banques ou d'une banque spécifique
     */
    public function statistics(Bank $bank = null)
    {
        if ($bank) {
            // Statistiques d'une banque spécifique
            $bank->load(['admin', 'appointments', 'donations']);

            // Statistiques de base
            $stats = [
                'total_appointments' => $bank->appointments()->count(),
                'total_donations' => $bank->donations()->count(),
                'pending_appointments' => $bank->appointments()->where('status', 'pending')->count(),
                'available_donations' => $bank->donations()->where('status', 'available')->count()
            ];

            // Dons par groupe sanguin
            $bloodTypeStats = $bank->donations()
                ->with('bloodType')
                ->selectRaw('blood_type_id, COUNT(*) as count, SUM(volume) as total_volume')
                ->groupBy('blood_type_id')
                ->get();

            // Dons par mois (6 derniers mois)
            $monthlyStats = $bank->donations()
                ->where('donation_date', '>=', now()->subMonths(6))
                ->selectRaw('strftime("%Y-%m", donation_date) as month, COUNT(*) as count, SUM(volume) as total_volume')
                ->groupBy('month')
                ->orderBy('month')
                ->get();

            // Dons du jour
            $todayDonations = $bank->donations()
                ->with(['donor.user', 'bloodType'])
                ->whereDate('donation_date', today())
                ->orderBy('donation_date', 'desc')
                ->get();

            return view('superadmin.banks.statistics', compact('bank', 'stats', 'bloodTypeStats', 'monthlyStats', 'todayDonations'));
        } else {
            // Statistiques globales
            $stats = [
                'total_banks' => Bank::count(),
                'active_banks' => Bank::where('status', 'active')->count(),
                'total_users' => User::count(),
                'total_donors' => User::where('role', 'donor')->count(),
                'total_admins' => User::where('role', 'admin_banque')->count(),
                'total_appointments' => Appointment::count(),
                'total_donations' => Donation::count(),
                'available_donations' => Donation::where('status', 'available')->count()
            ];

            // Banques les plus actives
            $topBanks = Bank::withCount(['appointments', 'donations'])
                ->orderBy('appointments_count', 'desc')
                ->limit(5)
                ->get();

            return view('superadmin.banks.statistics', compact('stats', 'topBanks'));
        }
    }
}
