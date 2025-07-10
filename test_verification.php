<?php

require_once 'vendor/autoload.php';

use App\Models\User;
use Illuminate\Support\Facades\Log;

// Charger l'application Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== Test du système de vérification d'email ===\n\n";

// Créer un utilisateur de test non vérifié
$user = User::where('email', 'test-verification@bloodlink.com')->first();

if (!$user) {
    echo "Création d'un utilisateur de test...\n";
    $user = User::create([
        'name' => 'Test Verification',
        'email' => 'test-verification@bloodlink.com',
        'password' => bcrypt('password'),
        'role' => 'donor',
        'status' => 'active',
        'email_verified_at' => null, // Non vérifié
        'email_verification_code' => null,
        'email_verification_code_expires_at' => null,
    ]);
    echo "Utilisateur créé avec l'ID: " . $user->id . "\n\n";
} else {
    // Réinitialiser l'utilisateur pour le test
    $user->update([
        'email_verified_at' => null,
        'email_verification_code' => null,
        'email_verification_code_expires_at' => null,
    ]);
    echo "Utilisateur existant réinitialisé.\n\n";
}

echo "État initial de l'utilisateur :\n";
echo "- ID: " . $user->id . "\n";
echo "- Email: " . $user->email . "\n";
echo "- Email vérifié: " . ($user->isEmailVerified() ? 'Oui' : 'Non') . "\n";
echo "- Code de vérification: " . $user->email_verification_code . "\n";
echo "- Expire le: " . $user->email_verification_code_expires_at . "\n\n";

// Générer un nouveau code
echo "Génération d'un nouveau code...\n";
$code = $user->generateVerificationCode();
echo "Nouveau code généré: " . $code . "\n\n";

// Vérifier l'état après génération
$user->refresh();
echo "État après génération du code:\n";
echo "- Code de vérification: " . $user->email_verification_code . "\n";
echo "- Expire le: " . $user->email_verification_code_expires_at . "\n";
echo "- Email vérifié: " . ($user->isEmailVerified() ? 'Oui' : 'Non') . "\n\n";

// Test avec un mauvais code
echo "Test avec un mauvais code...\n";
$wrongResult = $user->verifyEmail('000000');
echo "Résultat avec mauvais code: " . ($wrongResult ? 'Succès (ERREUR!)' : 'Échec (CORRECT)') . "\n\n";

// Vérifier le code correct
echo "Test de vérification avec le bon code...\n";
$result = $user->verifyEmail($code);
echo "Résultat avec bon code: " . ($result ? 'Succès' : 'Échec') . "\n\n";

// Vérifier l'état après vérification
$user->refresh();
echo "État après vérification:\n";
echo "- Email vérifié: " . ($user->isEmailVerified() ? 'Oui' : 'Non') . "\n";
echo "- Code de vérification: " . $user->email_verification_code . "\n";
echo "- Expire le: " . $user->email_verification_code_expires_at . "\n\n";

echo "Test terminé.\n";
