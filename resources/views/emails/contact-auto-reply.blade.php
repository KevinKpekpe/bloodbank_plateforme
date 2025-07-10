<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmation de votre message - BloodLink</title>
</head>
<body>
    <h1>Message Reçu - BloodLink</h1>
    <p>Nous avons bien reçu votre message et nous vous en remercions.</p>

    <h2>Bonjour {{ $name }},</h2>
    <p>Nous avons bien reçu votre message concernant : <strong>{{ $subject }}</strong></p>
    <p>Notre équipe va examiner votre demande et vous répondre dans les plus brefs délais.</p>

    <h3>Récapitulatif de votre message :</h3>
    <p><strong>Sujet :</strong> {{ $subject }}</p>
    <p><strong>Date d'envoi :</strong> {{ $dateTime }}</p>
    <p><strong>Numéro de référence :</strong> #{{ $referenceNumber }}</p>

    <h3>Délai de réponse :</h3>
    <ul>
        <li>Questions générales : 24-48 heures</li>
        <li>Support technique : 12-24 heures</li>
        <li>Urgences médicales : Immédiat (appelez +243 123 456 789)</li>
    </ul>

    <h3>Besoin d'aide immédiate ?</h3>
    <p>Téléphone d'urgence : +243 123 456 789</p>
    <p>Site web : https://bloodlink.cd/contact</p>

    <hr>
    <p><strong>BloodLink</strong> - Plateforme de gestion des dons de sang à Kinshasa</p>
    <p>Cet email a été envoyé automatiquement. Merci de ne pas y répondre.</p>
    <p>© {{ \Carbon\Carbon::now()->format('Y') }} BloodLink. Tous droits réservés.</p>
</body>
</html>
