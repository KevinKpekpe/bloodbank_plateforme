@component('mail::message')
# Vérification de votre adresse email

Bonjour {{ $user->name }},

Merci de vous être inscrit sur **BloodLink** ! Pour activer votre compte et accéder à tous nos services, veuillez vérifier votre adresse email en utilisant le code de vérification ci-dessous.

## Votre code de vérification

<div style="text-align: center; margin: 30px 0;">
    <div style="display: inline-block; background: #DC2626; color: white; padding: 20px 40px; border-radius: 10px; font-size: 32px; font-weight: bold; letter-spacing: 8px; font-family: monospace;">
        {{ $verificationCode }}
    </div>
</div>

## Instructions

1. Copiez le code ci-dessus
2. Retournez sur BloodLink
3. Entrez le code dans le champ de vérification
4. Cliquez sur "Vérifier mon email"

## Sécurité

- Ce code expire dans **15 minutes**
- Ne partagez jamais ce code avec qui que ce soit
- Si vous n'avez pas demandé ce code, ignorez cet email

## Besoin d'aide ?

Si vous rencontrez des difficultés, n'hésitez pas à nous contacter :

- **Email** : support@bloodlink.cd
- **Téléphone** : +243 123 456 789

---

**BloodLink** - Sauvez des vies, donnez votre sang

*Cet email a été envoyé automatiquement, merci de ne pas y répondre.*
@endcomponent