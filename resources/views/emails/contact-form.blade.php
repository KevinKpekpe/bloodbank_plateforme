<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouveau message de contact - BloodLink</title>
</head>
<body>
    <h1>Nouveau Message de Contact - BloodLink</h1>
    <p>Un nouveau message a été envoyé via le formulaire de contact.</p>

    <h2>Informations du message :</h2>
    <p><strong>Nom :</strong> {{ $name }}</p>
    <p><strong>Email :</strong> {{ $email }}</p>
    <p><strong>Sujet :</strong> {{ $subject }}</p>
    <p><strong>Type :</strong> {{ $userType }}</p>
    <p><strong>Date :</strong> {{ $dateTime }}</p>

    <h2>Message :</h2>
    <p>{{ $messageContent }}</p>

    @if($userType === 'Visiteur')
    <p><strong>⚠️ Attention :</strong> Ce message provient d'un visiteur non connecté. Vérifiez l'email avant de répondre.</p>
    @endif

    <hr>
    <p><strong>BloodLink</strong> - Plateforme de gestion des dons de sang à Kinshasa</p>
    <p>Cet email a été envoyé automatiquement depuis le formulaire de contact.</p>
    <p>© {{ \Carbon\Carbon::now()->format('Y') }} BloodLink. Tous droits réservés.</p>
</body>
</html>
