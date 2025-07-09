<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;
use App\Models\Appointment;
use App\Models\Donation;
use Carbon\Carbon;

class NotificationService
{
    /**
     * Notification de confirmation de rendez-vous
     */
    public static function appointmentConfirmed(Appointment $appointment)
    {
        Notification::createForUser(
            $appointment->donor_id,
            'Rendez-vous confirmé',
            "Votre rendez-vous du " . $appointment->appointment_date->format('d/m/Y à H:i') . " a été confirmé.",
            'success',
            [
                'appointment_id' => $appointment->id,
                'type' => 'appointment_confirmed'
            ]
        );
    }

    /**
     * Notification de rejet de rendez-vous
     */
    public static function appointmentRejected(Appointment $appointment, $reason = null)
    {
        $message = "Votre rendez-vous du " . $appointment->appointment_date->format('d/m/Y à H:i') . " a été rejeté.";
        if ($reason) {
            $message .= " Raison : " . $reason;
        }

        Notification::createForUser(
            $appointment->donor_id,
            'Rendez-vous rejeté',
            $message,
            'error',
            [
                'appointment_id' => $appointment->id,
                'type' => 'appointment_rejected',
                'reason' => $reason
            ]
        );
    }

    /**
     * Notification de rappel de rendez-vous
     */
    public static function appointmentReminder(Appointment $appointment)
    {
        Notification::createForUser(
            $appointment->donor_id,
            'Rappel de rendez-vous',
            "N'oubliez pas votre rendez-vous demain à " . $appointment->appointment_date->format('H:i') . ".",
            'warning',
            [
                'appointment_id' => $appointment->id,
                'type' => 'appointment_reminder'
            ]
        );
    }

    /**
     * Notification de don traité
     */
    public static function donationProcessed(Donation $donation)
    {
        Notification::createForUser(
            $donation->donor_id,
            'Don traité',
            "Votre don du " . $donation->donation_date->format('d/m/Y') . " a été traité avec succès.",
            'success',
            [
                'donation_id' => $donation->id,
                'type' => 'donation_processed'
            ]
        );
    }

    /**
     * Notification de don disponible
     */
    public static function donationAvailable(Donation $donation)
    {
        Notification::createForUser(
            $donation->donor_id,
            'Don disponible',
            "Votre don du " . $donation->donation_date->format('d/m/Y') . " est maintenant disponible pour les patients.",
            'success',
            [
                'donation_id' => $donation->id,
                'type' => 'donation_available'
            ]
        );
    }

    /**
     * Notification de don utilisé
     */
    public static function donationUsed(Donation $donation)
    {
        Notification::createForUser(
            $donation->donor_id,
            'Don utilisé',
            "Votre don du " . $donation->donation_date->format('d/m/Y') . " a été utilisé pour sauver une vie.",
            'success',
            [
                'donation_id' => $donation->id,
                'type' => 'donation_used'
            ]
        );
    }

    /**
     * Notification de don expiré
     */
    public static function donationExpired(Donation $donation)
    {
        Notification::createForUser(
            $donation->donor_id,
            'Don expiré',
            "Votre don du " . $donation->donation_date->format('d/m/Y') . " a expiré et ne peut plus être utilisé.",
            'warning',
            [
                'donation_id' => $donation->id,
                'type' => 'donation_expired'
            ]
        );
    }

    /**
     * Notification de stock faible pour les admins
     */
    public static function lowStockAlert($bankId, $bloodType, $currentStock)
    {
        Notification::createForBank(
            $bankId,
            'Stock faible',
            "Le stock de sang de type {$bloodType} est faible ({$currentStock} unités).",
            'warning',
            [
                'blood_type' => $bloodType,
                'current_stock' => $currentStock,
                'type' => 'low_stock_alert'
            ]
        );
    }

    /**
     * Notification de stock critique pour les admins
     */
    public static function criticalStockAlert($bankId, $bloodType)
    {
        Notification::createForBank(
            $bankId,
            'Stock critique',
            "Le stock de sang de type {$bloodType} est critique. Action immédiate requise.",
            'error',
            [
                'blood_type' => $bloodType,
                'type' => 'critical_stock_alert'
            ]
        );
    }

    /**
     * Notification de demande urgente de sang
     */
    public static function urgentBloodRequest($bloodType, $hospital, $contact)
    {
        Notification::createForAllDonors(
            'Demande urgente de sang',
            "Demande urgente de sang de type {$bloodType} pour {$hospital}. Contact : {$contact}",
            'error',
            [
                'blood_type' => $bloodType,
                'hospital' => $hospital,
                'contact' => $contact,
                'type' => 'urgent_blood_request'
            ]
        );
    }

    /**
     * Notification de campagne de don
     */
    public static function donationCampaign($title, $message, $bankId = null)
    {
        if ($bankId) {
            Notification::createForBank($bankId, $title, $message, 'info', ['type' => 'donation_campaign']);
        } else {
            Notification::createForAllDonors($title, $message, 'info', ['type' => 'donation_campaign']);
        }
    }

    /**
     * Notification de maintenance
     */
    public static function maintenanceNotification($message, $scheduledTime = null)
    {
        $title = 'Maintenance prévue';
        if ($scheduledTime) {
            $title .= ' - ' . $scheduledTime;
        }

        Notification::createForAllAdmins($title, $message, 'warning', [
            'type' => 'maintenance',
            'scheduled_time' => $scheduledTime
        ]);
    }

    /**
     * Notification de bienvenue pour nouveaux utilisateurs
     */
    public static function welcomeNotification(User $user)
    {
        $message = "Bienvenue sur BloodLink ! Votre compte a été créé avec succès.";

        if ($user->role === 'donor') {
            $message .= " Vous pouvez maintenant prendre rendez-vous pour faire un don de sang.";
        } elseif ($user->role === 'admin') {
            $message .= " Vous pouvez maintenant gérer votre banque de sang.";
        }

        Notification::createForUser(
            $user->id,
            'Bienvenue sur BloodLink',
            $message,
            'success',
            ['type' => 'welcome']
        );
    }

    /**
     * Envoyer les rappels de rendez-vous
     */
    public static function sendAppointmentReminders()
    {
        $tomorrow = Carbon::tomorrow();

        $appointments = Appointment::where('status', 'confirmed')
            ->whereDate('appointment_date', $tomorrow)
            ->get();

        foreach ($appointments as $appointment) {
            self::appointmentReminder($appointment);
        }
    }

    /**
     * Vérifier les stocks et envoyer des alertes
     */
    public static function checkStockLevels()
    {
        // Logique pour vérifier les niveaux de stock
        // et envoyer des alertes si nécessaire
        // Cette méthode peut être appelée par une tâche cron
    }
}