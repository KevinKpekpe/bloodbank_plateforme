<?php

namespace App\Helpers;

use App\Models\Donor;
use App\Models\Donation;
use Carbon\Carbon;

class DonationHelper
{
    /**
     * Check if donor can donate based on last donation date.
     */
    public static function canDonate(Donor $donor): bool
    {
        if (!$donor->last_donation_date) {
            return true;
        }

        $lastDonation = Carbon::parse($donor->last_donation_date);
        $minimumInterval = Carbon::now()->subMonths(3); // 3 mois minimum entre dons

        return $lastDonation->lt($minimumInterval);
    }

    /**
     * Get next donation date for donor.
     */
    public static function getNextDonationDate(Donor $donor): ?Carbon
    {
        if (!$donor->last_donation_date) {
            return null;
        }

        return Carbon::parse($donor->last_donation_date)->addMonths(3);
    }

    /**
     * Calculate total volume donated by donor.
     */
    public static function calculateTotalVolume(Donor $donor): float
    {
        return $donor->donations()->sum('volume');
    }

    /**
     * Calculate total donations count for donor.
     */
    public static function calculateTotalDonations(Donor $donor): int
    {
        return $donor->donations()->count();
    }

    /**
     * Update donor statistics after donation.
     */
    public static function updateDonorStats(Donor $donor): void
    {
        $donor->update([
            'total_donations' => self::calculateTotalDonations($donor),
            'total_volume' => self::calculateTotalVolume($donor),
            'last_donation_date' => now(),
        ]);
    }
}