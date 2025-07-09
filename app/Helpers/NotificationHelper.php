<?php

namespace App\Helpers;

use App\Models\Notification;
use App\Models\User;
use App\Models\Donor;
use App\Models\Bank;

class NotificationHelper
{
    /**
     * Create a notification for a user.
     */
    public static function createUserNotification(
        User $user,
        string $type,
        string $title,
        string $message
    ): Notification {
        return Notification::create([
            'user_id' => $user->id,
            'type' => $type,
            'title' => $title,
            'message' => $message,
        ]);
    }

    /**
     * Create a notification for a donor.
     */
    public static function createDonorNotification(
        Donor $donor,
        string $type,
        string $title,
        string $message
    ): Notification {
        return Notification::create([
            'donor_id' => $donor->id,
            'type' => $type,
            'title' => $title,
            'message' => $message,
        ]);
    }

    /**
     * Create a notification for a bank.
     */
    public static function createBankNotification(
        Bank $bank,
        string $type,
        string $title,
        string $message
    ): Notification {
        return Notification::create([
            'bank_id' => $bank->id,
            'type' => $type,
            'title' => $title,
            'message' => $message,
        ]);
    }

    /**
     * Get unread notifications count for a user.
     */
    public static function getUnreadCount(User $user): int
    {
        return $user->notifications()
            ->where('is_read', false)
            ->count();
    }

    /**
     * Get unread notifications count for a donor.
     */
    public static function getDonorUnreadCount(Donor $donor): int
    {
        return $donor->notifications()
            ->where('is_read', false)
            ->count();
    }

    /**
     * Mark all notifications as read for a user.
     */
    public static function markAllAsRead(User $user): void
    {
        $user->notifications()
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
    }

    /**
     * Mark all notifications as read for a donor.
     */
    public static function markDonorAllAsRead(Donor $donor): void
    {
        $donor->notifications()
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
    }
}