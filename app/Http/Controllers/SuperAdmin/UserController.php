<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Bank;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Afficher la liste des utilisateurs
     */
    public function index(Request $request)
    {
        $query = User::with('managedBank');

        // Filtres
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('bank_id')) {
            $query->whereHas('managedBank', function($q) use ($request) {
                $q->where('id', $request->bank_id);
            });
        }

        $users = $query->orderBy('name')->paginate(15);
        $banks = Bank::orderBy('name')->get();

        return view('superadmin.users.index', compact('users', 'banks'));
    }

    /**
     * Afficher le formulaire de création
     */
    public function create()
    {
        $banks = Bank::orderBy('name')->get();
        return view('superadmin.users.create', compact('banks'));
    }

    /**
     * Enregistrer un nouvel utilisateur
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users',
            'phone' => 'required|string|max:20',
            'role' => 'required|in:admin_banque',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Créer l'utilisateur (seulement admin_banque)
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone_number' => $request->phone,
            'role' => $request->role,
            'password' => Hash::make($request->password),
            'status' => 'active',
            'email_verified_at' => now()
        ]);

        return redirect()->route('superadmin.users.index')
            ->with('success', 'Administrateur créé avec succès.');
    }

    /**
     * Afficher les détails d'un utilisateur
     */
    public function show(User $user)
    {
        $user->load(['managedBank']);

        return view('superadmin.users.show', compact('user'));
    }

    /**
     * Afficher le formulaire d'édition
     */
    public function edit(User $user)
    {
        $banks = Bank::orderBy('name')->get();
        return view('superadmin.users.edit', compact('user', 'banks'));
    }

    /**
     * Mettre à jour un utilisateur
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'required|string|max:20',
            'role' => 'required|in:admin_banque',
        ]);

        // Mettre à jour l'utilisateur (seulement admin_banque)
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone_number' => $request->phone,
            'role' => $request->role
        ]);

        return redirect()->route('superadmin.users.show', $user)
            ->with('success', 'Administrateur mis à jour avec succès.');
    }

    /**
     * Supprimer un utilisateur
     */
    public function destroy(User $user)
    {
        // Vérifier qu'il n'y a pas de données associées
        if ($user->appointments()->count() > 0 || $user->donations()->count() > 0) {
            return back()->with('error', 'Impossible de supprimer cet utilisateur car il a des données associées.');
        }

        $user->delete();

        return redirect()->route('superadmin.users.index')
            ->with('success', 'Utilisateur supprimé avec succès.');
    }

    /**
     * Activer/Désactiver un utilisateur
     */
    public function toggleStatus(User $user)
    {
        $user->update([
            'status' => $user->status === 'active' ? 'inactive' : 'active'
        ]);

        $status = $user->status === 'active' ? 'activé' : 'désactivé';
        return back()->with('success', "Utilisateur {$status} avec succès.");
    }
}
