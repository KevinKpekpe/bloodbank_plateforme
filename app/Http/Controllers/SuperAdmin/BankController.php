<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Bank;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class BankController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:superadmin');
    }

    /**
     * Afficher la liste des banques de sang
     */
    public function index(Request $request)
    {
        $query = Bank::withCount(['users', 'appointments', 'donations']);

        // Filtres
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('address', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $banks = $query->orderBy('name')->paginate(15);

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

        // Créer la banque
        $bank = Bank::create([
            'name' => $request->name,
            'address' => $request->address,
            'contact_phone' => $request->contact_phone,
            'contact_email' => $request->contact_email,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'status' => 'active'
        ]);

        // Créer l'administrateur de la banque
        $admin = User::create([
            'name' => $request->admin_name,
            'email' => $request->admin_email,
            'phone' => $request->admin_phone,
            'password' => Hash::make($request->admin_password),
            'role' => 'admin',
            'bank_id' => $bank->id,
            'email_verified_at' => now()
        ]);

        return redirect()->route('superadmin.banks.index')
            ->with('success', 'Banque de sang créée avec succès.');
    }

    /**
     * Afficher les détails d'une banque
     */
    public function show(Bank $bank)
    {
        $bank->load(['users', 'appointments', 'donations']);

        // Statistiques de la banque
        $stats = [
            'total_users' => $bank->users()->count(),
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
        // Vérifier qu'il n'y a pas d'utilisateurs ou de données associées
        if ($bank->users()->count() > 0 || $bank->appointments()->count() > 0 || $bank->donations()->count() > 0) {
            return back()->with('error', 'Impossible de supprimer cette banque car elle contient des données associées.');
        }

        $bank->delete();

        return redirect()->route('superadmin.banks.index')
            ->with('success', 'Banque de sang supprimée avec succès.');
    }

    /**
     * Statistiques globales des banques
     */
    public function statistics()
    {
        $stats = [
            'total_banks' => Bank::count(),
            'active_banks' => Bank::where('status', 'active')->count(),
            'total_users' => User::count(),
            'total_donors' => User::where('role', 'donor')->count(),
            'total_admins' => User::where('role', 'admin')->count(),
            'total_appointments' => \App\Models\Appointment::count(),
            'total_donations' => \App\Models\Donation::count(),
            'available_donations' => \App\Models\Donation::where('status', 'available')->count()
        ];

        // Banques les plus actives
        $topBanks = Bank::withCount(['appointments', 'donations'])
            ->orderBy('appointments_count', 'desc')
            ->limit(5)
            ->get();

        return view('superadmin.banks.statistics', compact('stats', 'topBanks'));
    }
}