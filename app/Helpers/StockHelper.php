<?php

namespace App\Helpers;

use App\Models\BloodStock;
use App\Models\BloodBag;
use App\Models\Bank;
use App\Models\BloodType;

class StockHelper
{
    /**
     * Update stock status based on quantity.
     */
    public static function updateStockStatus(BloodStock $stock): void
    {
        $status = 'normal';

        if ($stock->isCritical()) {
            $status = 'critical';
        } elseif ($stock->isLow()) {
            $status = 'low';
        } elseif ($stock->isHigh()) {
            $status = 'high';
        }

        $stock->update([
            'status' => $status,
            'last_updated' => now(),
        ]);
    }

    /**
     * Update stock statistics based on blood bags.
     */
    public static function updateStockStatistics(BloodStock $stock): void
    {
        $stock->updateStatistics();
    }

    /**
     * Update all stock statistics for a bank.
     */
    public static function updateAllStockStatistics(Bank $bank): void
    {
        $stocks = $bank->bloodStocks;

        foreach ($stocks as $stock) {
            $stock->updateStatistics();
        }
    }

    /**
     * Get low stock alerts for a bank.
     */
    public static function getLowStockAlerts(Bank $bank): array
    {
        return $bank->bloodStocks()
            ->whereIn('status', ['low', 'critical'])
            ->with('bloodType')
            ->get()
            ->toArray();
    }

    /**
     * Get stock summary for a bank.
     */
    public static function getStockSummary(Bank $bank): array
    {
        $stocks = $bank->bloodStocks()->with('bloodType')->get();

        $summary = [];
        foreach ($stocks as $stock) {
            $summary[] = [
                'blood_type' => $stock->bloodType->name,
                'total_bags' => $stock->total_bags,
                'available_bags' => $stock->available_bags,
                'reserved_bags' => $stock->reserved_bags,
                'expiring_soon_bags' => $stock->expiring_soon_bags,
                'total_volume_l' => $stock->getTotalVolumeInLiters(),
                'available_volume_l' => $stock->getAvailableVolumeInLiters(),
                'status' => $stock->status,
                'critical_level' => $stock->critical_level,
                'is_low' => $stock->isLow(),
                'is_critical' => $stock->isCritical(),
                'is_high' => $stock->isHigh(),
            ];
        }

        return $summary;
    }

    /**
     * Check if stock needs replenishment.
     */
    public static function needsReplenishment(BloodStock $stock): bool
    {
        return $stock->isLow();
    }

    /**
     * Get available blood types for a bank.
     */
    public static function getAvailableBloodTypes(Bank $bank): array
    {
        return $bank->bloodStocks()
            ->where('available_bags', '>', 0)
            ->with('bloodType')
            ->get()
            ->pluck('bloodType.name')
            ->toArray();
    }

    /**
     * Get expiring soon blood bags for a bank.
     */
    public static function getExpiringSoonBags(Bank $bank, int $days = 7): array
    {
        return BloodBag::where('bank_id', $bank->id)
            ->where('status', 'available')
            ->where('expiry_date', '<=', now()->addDays($days))
            ->with(['bloodType', 'donor'])
            ->get()
            ->toArray();
    }

    /**
     * Get active reservations for a bank.
     */
    public static function getActiveReservations(Bank $bank): array
    {
        return $bank->bloodBags()
            ->whereHas('reservations', function ($query) {
                $query->where('status', 'active');
            })
            ->with(['reservations' => function ($query) {
                $query->where('status', 'active');
            }, 'bloodType'])
            ->get()
            ->toArray();
    }

    /**
     * Get stock statistics for dashboard.
     */
    public static function getDashboardStatistics(Bank $bank): array
    {
        $stocks = $bank->bloodStocks()->with('bloodType')->get();

        $totalBags = $stocks->sum('total_bags');
        $availableBags = $stocks->sum('available_bags');
        $reservedBags = $stocks->sum('reserved_bags');
        $expiringSoonBags = $stocks->sum('expiring_soon_bags');
        $criticalStocks = $stocks->where('status', 'critical')->count();
        $lowStocks = $stocks->where('status', 'low')->count();

        return [
            'total_bags' => $totalBags,
            'available_bags' => $availableBags,
            'reserved_bags' => $reservedBags,
            'expiring_soon_bags' => $expiringSoonBags,
            'total_volume_l' => ($totalBags * 450) / 1000,
            'available_volume_l' => ($availableBags * 450) / 1000,
            'critical_stocks' => $criticalStocks,
            'low_stocks' => $lowStocks,
            'stock_summary' => self::getStockSummary($bank),
        ];
    }

    /**
     * Create blood bags from a donation.
     */
    public static function createBloodBagsFromDonation($donation): array
    {
        $bagsCount = floor($donation->quantity / 450); // 450ml par poche
        $remainingVolume = $donation->quantity % 450;
        $createdBags = [];

        // Créer les poches complètes
        for ($i = 0; $i < $bagsCount; $i++) {
            $bag = BloodBag::create([
                'donation_id' => $donation->id,
                'bank_id' => $donation->bank_id,
                'blood_type_id' => $donation->blood_type_id,
                'donor_id' => $donation->donor_id,
                'volume_ml' => 450,
                'collection_date' => $donation->donation_date,
                'expiry_date' => $donation->donation_date->addDays(42),
                'status' => 'available',
            ]);

            $createdBags[] = $bag;
        }

        // Créer une poche partielle si nécessaire
        if ($remainingVolume > 0) {
            $bag = BloodBag::create([
                'donation_id' => $donation->id,
                'bank_id' => $donation->bank_id,
                'blood_type_id' => $donation->blood_type_id,
                'donor_id' => $donation->donor_id,
                'volume_ml' => $remainingVolume,
                'collection_date' => $donation->donation_date,
                'expiry_date' => $donation->donation_date->addDays(42),
                'status' => 'available',
            ]);

            $createdBags[] = $bag;
        }

        // Mettre à jour les statistiques du stock
        $stock = BloodStock::where('bank_id', $donation->bank_id)
            ->where('blood_type_id', $donation->blood_type_id)
            ->first();

        if ($stock) {
            $stock->updateStatistics();
        }

        return $createdBags;
    }
}
