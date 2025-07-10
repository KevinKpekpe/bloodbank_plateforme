<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\EmailVerificationMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class EmailVerificationController extends Controller
{
    /**
     * Show the email verification notice.
     */
    public function show()
    {
        $user = Auth::user();
        Log::info('EmailVerificationController@show - User ID: ' . $user->id . ', Email: ' . $user->email . ', Email verified: ' . ($user->isEmailVerified() ? 'true' : 'false'));

        if ($user->isEmailVerified()) {
            Log::info('EmailVerificationController@show - Redirecting to dashboard, email already verified');
            return redirect()->route('donor.dashboard');
        }

        Log::info('EmailVerificationController@show - Showing verify-email page');
        return view('auth.verify-email');
    }

    /**
     * Show the verification code form.
     */
    public function showVerificationForm()
    {
        $user = Auth::user();
        Log::info('EmailVerificationController@showVerificationForm - User ID: ' . $user->id . ', Email: ' . $user->email . ', Email verified: ' . ($user->isEmailVerified() ? 'true' : 'false'));

        if ($user->isEmailVerified()) {
            Log::info('EmailVerificationController@showVerificationForm - Redirecting to dashboard, email already verified');
            return redirect()->route('donor.dashboard');
        }

        Log::info('EmailVerificationController@showVerificationForm - Showing verification-code page');
        return view('auth.verification-code');
    }

    /**
     * Verify the email with the provided code.
     */
    public function verify(Request $request)
    {
        Log::info('EmailVerificationController@verify - Starting verification process');
        Log::info('EmailVerificationController@verify - Request data: ' . json_encode($request->all()));

        $request->validate([
            'verification_code' => 'required|string|size:6',
        ]);

        $user = Auth::user();
        Log::info('EmailVerificationController@verify - User ID: ' . $user->id . ', Email: ' . $user->email);
        Log::info('EmailVerificationController@verify - Stored code: ' . $user->email_verification_code);
        Log::info('EmailVerificationController@verify - Provided code: ' . $request->verification_code);
        Log::info('EmailVerificationController@verify - Code expires at: ' . $user->email_verification_code_expires_at);
        Log::info('EmailVerificationController@verify - Is code expired: ' . ($user->isVerificationCodeExpired() ? 'true' : 'false'));

        if ($user->verifyEmail($request->verification_code)) {
            Log::info('EmailVerificationController@verify - Email verification successful, redirecting to dashboard');
            return redirect()->route('donor.dashboard')
                ->with('success', 'Votre adresse email a été vérifiée avec succès ! Bienvenue sur BloodLink.');
        }

        Log::error('EmailVerificationController@verify - Email verification failed');
        return back()->withErrors([
            'verification_code' => 'Le code de vérification est invalide ou a expiré.',
        ]);
    }

    /**
     * Resend the verification email.
     */
    public function resend()
    {
        $user = Auth::user();
        Log::info('EmailVerificationController@resend - User ID: ' . $user->id . ', Email: ' . $user->email);

        if ($user->isEmailVerified()) {
            Log::info('EmailVerificationController@resend - Redirecting to dashboard, email already verified');
            return redirect()->route('donor.dashboard');
        }

        // Générer un nouveau code
        $verificationCode = $user->generateVerificationCode();
        Log::info('EmailVerificationController@resend - Generated new code: ' . $verificationCode);

        // Envoyer l'email
        Mail::to($user->email)->send(new EmailVerificationMail($user, $verificationCode));
        Log::info('EmailVerificationController@resend - Email sent successfully');

        return back()->with('success', 'Un nouveau code de vérification a été envoyé à votre adresse email.');
    }
}
