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
        $query = User::with('bank');

        // Filtres
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('bank_id')) {
            $query->where('bank_id', $request->bank_id);
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
            'role' => 'required|in:donor,admin',
            'bank_id' => 'required_if:role,admin|nullable|exists:banks,id',
            'password' => 'required|string|min:8|confirmed',
            'birth_date' => 'required_if:role,donor|nullable|date|before:today',
            'blood_type_id' => 'required_if:role,donor|nullable|exists:blood_types,id'
        ]);

        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'role' => $request->role,
            'password' => Hash::make($request->password),
            'email_verified_at' => now()
        ];

        if ($request->role === 'admin') {
            $userData['bank_id'] = $request->bank_id;
        } else {
            $userData['birth_date'] = $request->birth_date;
            $userData['blood_type_id'] = $request->blood_type_id;
        }

        User::create($userData);

        return redirect()->route('superadmin.users.index')
            ->with('success', 'Utilisateur créé avec succès.');
    }

    /**
     * Afficher les détails d'un utilisateur
     */
    public function show(User $user)
    {
        $user->load(['bank', 'bloodType', 'appointments', 'donations']);

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
            'role' => 'required|in:donor,admin',
            'bank_id' => 'required_if:role,admin|nullable|exists:banks,id',
            'birth_date' => 'required_if:role,donor|nullable|date|before:today',
            'blood_type_id' => 'required_if:role,donor|nullable|exists:blood_types,id'
        ]);

        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'role' => $request->role
        ];

        if ($request->role === 'admin') {
            $userData['bank_id'] = $request->bank_id;
            $userData['birth_date'] = null;
            $userData['blood_type_id'] = null;
        } else {
            $userData['bank_id'] = null;
            $userData['birth_date'] = $request->birth_date;
            $userData['blood_type_id'] = $request->blood_type_id;
        }

        $user->update($userData);

        return redirect()->route('superadmin.users.show', $user)
            ->with('success', 'Utilisateur mis à jour avec succès.');
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
            'email_verified_at' => $user->email_verified_at ? null : now()
        ]);

        $status = $user->email_verified_at ? 'activé' : 'désactivé';
        return back()->with('success', "Utilisateur {$status} avec succès.");
    }
}
