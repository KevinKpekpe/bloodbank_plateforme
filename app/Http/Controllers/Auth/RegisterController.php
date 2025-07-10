<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\EmailVerificationMail;
use App\Models\BloodType;
use App\Models\Donor;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
    /**
     * Show the registration form.
     */
    public function showRegistrationForm()
    {
        $bloodTypes = BloodType::all();
        return view('auth.register', compact('bloodTypes'));
    }

    /**
     * Handle a registration request for the application.
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Password::defaults()],
            'firstname' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'blood_type_id' => 'required|exists:blood_types,id',
            'gender' => 'required|in:male,female,other',
            'birthdate' => 'required|date|before:today',
            'address' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
        ]);

        // Créer l'utilisateur
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'donor',
            'phone_number' => $request->phone_number,
            'email_verified_at' => null, // Pas d'auto-vérification
        ]);

        // Créer le donneur
        Donor::create([
            'user_id' => $user->id,
            'firstname' => $request->firstname,
            'surname' => $request->surname,
            'blood_type_id' => $request->blood_type_id,
            'gender' => $request->gender,
            'birthdate' => $request->birthdate,
            'address' => $request->address,
            'phone_number' => $request->phone_number,
        ]);

        // Générer et envoyer le code de vérification
        $verificationCode = $user->generateVerificationCode();
        Mail::to($user->email)->send(new EmailVerificationMail($user, $verificationCode));

        // Connecter l'utilisateur
        Auth::login($user);

        return redirect()->route('verification.notice')
            ->with('success', 'Compte créé avec succès ! Veuillez vérifier votre adresse email pour activer votre compte.');
    }
}
