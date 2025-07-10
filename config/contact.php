<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Contact Email Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration pour les emails de contact de BloodLink
    |
    */

    'admin_email' => env('CONTACT_ADMIN_EMAIL', 'contact@bloodlink.cd'),

    'support_email' => env('CONTACT_SUPPORT_EMAIL', 'support@bloodlink.cd'),

    'from_email' => env('CONTACT_FROM_EMAIL', 'noreply@bloodlink.cd'),

    'from_name' => env('CONTACT_FROM_NAME', 'BloodLink - Contact'),

    /*
    |--------------------------------------------------------------------------
    | Email Templates
    |--------------------------------------------------------------------------
    |
    | Templates d'emails pour différents types de contact
    |
    */

    'templates' => [
        'contact_form' => 'emails.contact-form',
        'auto_reply' => 'emails.contact-auto-reply',
    ],

    /*
    |--------------------------------------------------------------------------
    | Auto Reply Settings
    |--------------------------------------------------------------------------
    |
    | Configuration pour les réponses automatiques
    |
    */

    'auto_reply' => [
        'enabled' => env('CONTACT_AUTO_REPLY_ENABLED', true),
        'subject' => 'Confirmation de votre message - BloodLink',
    ],

];
