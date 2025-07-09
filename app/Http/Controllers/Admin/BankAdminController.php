<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Bank;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class BankAdminController extends Controller
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
     * Afficher la liste des administrateurs de la banque
     */
    public function index()
    {
        $bank = $this->getAdminBank();

        // Récupérer uniquement les administrateurs de cette banque spécifique
        $admins = User::where('role', 'admin_banque')
            ->where(function($query) use ($bank) {
                $query->where('id', $bank->admin_id) // Admin principal de la banque
                      ->orWhere('created_by', $bank->admin_id); // Admins créés par l'admin principal
            })
            ->orderBy('name')
            ->get();

        return view('admin.bank-admins.index', compact('admins', 'bank'));
    }

    /**
     * Afficher le formulaire de création
     */
    public function create()
    {
        $bank = $this->getAdminBank();
        return view('admin.bank-admins.create', compact('bank'));
    }

    /**
     * Enregistrer un nouvel administrateur
     */
    public function store(Request $request)
    {
        $bank = $this->getAdminBank();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'phone_number' => 'required|string|max:20',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Créer l'administrateur
        $admin = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'password' => Hash::make($request->password),
            'role' => 'admin_banque',
            'status' => 'active',
            'email_verified_at' => now(),
            'created_by' => $bank->admin_id // Associer à l'admin principal de la banque
        ]);

        return redirect()->route('admin.bank-admins.index')
            ->with('success', 'Administrateur créé avec succès.');
    }

    /**
     * Afficher les détails d'un administrateur
     */
    public function show(User $bank_admin)
    {
        $bank = $this->getAdminBank();
        return view('admin.bank-admins.show', compact('bank_admin', 'bank'));
    }

    /**
     * Afficher le formulaire d'édition
     */
    public function edit(User $bank_admin)
    {
        $bank = $this->getAdminBank();
        return view('admin.bank-admins.edit', compact('bank_admin', 'bank'));
    }

    /**
     * Mettre à jour un administrateur
     */
    public function update(Request $request, User $bank_admin)
    {
        $bank = $this->getAdminBank();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $bank_admin->id,
            'phone_number' => 'required|string|max:20',
        ]);

        $bank_admin->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
        ]);

        return redirect()->route('admin.bank-admins.show', $bank_admin)
            ->with('success', 'Administrateur mis à jour avec succès.');
    }

    /**
     * Supprimer un administrateur
     */
    public function destroy(User $bank_admin)
    {
        $bank = $this->getAdminBank();

        // Empêcher la suppression de l'admin principal
        if ($bank_admin->id === $bank->admin_id) {
            return back()->with('error', 'Impossible de supprimer l\'administrateur principal de la banque.');
        }

        // Vérifier qu'il n'y a pas de données associées
        if ($bank_admin->appointments()->count() > 0 || $bank_admin->donations()->count() > 0) {
            return back()->with('error', 'Impossible de supprimer cet administrateur car il a des données associées.');
        }

        $bank_admin->delete();

        return redirect()->route('admin.bank-admins.index')
            ->with('success', 'Administrateur supprimé avec succès.');
    }

    /**
     * Activer/Désactiver un administrateur
     */
    public function toggleStatus(User $bank_admin)
    {
        $bank = $this->getAdminBank();

        // Empêcher la désactivation de l'admin principal
        if ($bank_admin->id === $bank->admin_id) {
            return back()->with('error', 'Impossible de désactiver l\'administrateur principal de la banque.');
        }

        $bank_admin->update([
            'status' => $bank_admin->status === 'active' ? 'inactive' : 'active'
        ]);

        $status = $bank_admin->status === 'active' ? 'activé' : 'désactivé';
        return back()->with('success', "Administrateur {$status} avec succès.");
    }
}
