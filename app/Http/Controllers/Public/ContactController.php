<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Mail\ContactFormMail;
use App\Mail\ContactAutoReplyMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class ContactController extends Controller
{
    public function index()
    {
        return view('public.contact');
    }

    public function store(Request $request)
    {
        // Validation des champs
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:1000',
        ]);

        // Déterminer le type d'utilisateur
        $userType = 'Visiteur';
        if (Auth::check()) {
            $userType = 'Utilisateur connecté (' . Auth::user()->role . ')';
        }

        // Préparer les données pour l'email
        $contactData = [
            'name' => $request->name,
            'email' => $request->email,
            'subject' => $request->subject,
            'message' => $request->message,
            'userType' => $userType,
        ];

        try {
            // Envoyer l'email à l'équipe BloodLink
            $adminEmail = config('contact.admin_email', 'contact@bloodlink.cd');
            Mail::to($adminEmail)->send(new ContactFormMail($contactData));

            // Envoyer une réponse automatique à l'utilisateur
            $autoReplyEnabled = config('contact.auto_reply.enabled', true);
            if ($autoReplyEnabled) {
                Mail::to($request->email)->send(new ContactAutoReplyMail($contactData));
            }

            // Log du message pour traçabilité
            Log::info('Message de contact envoyé avec succès', [
                'from' => $request->email,
                'name' => $request->name,
                'subject' => $request->subject,
                'user_type' => $userType,
                'ip' => $request->ip(),
                'timestamp' => Carbon::now()->toDateTimeString(),
            ]);

            return redirect()->route('contact')
                ->with('success', 'Votre message a été envoyé avec succès. Nous vous répondrons dans les plus brefs délais.');

        } catch (\Exception $e) {
            // En cas d'erreur d'envoi d'email, on peut logger l'erreur
            Log::error('Erreur envoi email contact', [
                'from' => $request->email,
                'name' => $request->name,
                'subject' => $request->subject,
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'timestamp' => Carbon::now()->toDateTimeString(),
            ]);

            return redirect()->route('contact')
                ->with('error', 'Une erreur est survenue lors de l\'envoi du message. Veuillez réessayer ou nous contacter directement.')
                ->withInput();
        }
    }
}
