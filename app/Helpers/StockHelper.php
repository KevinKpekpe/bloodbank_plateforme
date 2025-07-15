<?php

namespace App\Helpers;

use App\Models\BloodStock;
use App\Models\BloodBag;
use App\Models\Bank;
use App\Models\BloodType;
use App\Models\BloodBagReservation;
use App\Models\BloodBagMovement;

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
     * Get expiring soon statistics for a bank.
     */
    public static function getExpiringSoonStatistics(Bank $bank): array
    {
        $expiringBags = BloodBag::where('bank_id', $bank->id)
            ->where('status', 'available')
            ->where('expiry_date', '<=', now()->addDays(7))
            ->where('expiry_date', '>', now())
            ->count();

        $expiredToday = BloodBag::where('bank_id', $bank->id)
            ->where('status', 'available')
            ->whereDate('expiry_date', today())
            ->count();

        $expiredTomorrow = BloodBag::where('bank_id', $bank->id)
            ->where('status', 'available')
            ->whereDate('expiry_date', now()->addDay())
            ->count();

        $expiredThisWeek = BloodBag::where('bank_id', $bank->id)
            ->where('status', 'available')
            ->where('expiry_date', '<=', now()->addDays(7))
            ->where('expiry_date', '>', now())
            ->count();

        return [
            'total_expiring_soon' => $expiringBags,
            'total_expiring' => $expiringBags, // Alias pour compatibilité avec la vue
            'expired_today' => $expiredToday,
            'expired_tomorrow' => $expiredTomorrow,
            'expired_this_week' => $expiredThisWeek,
        ];
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
     * Get movement statistics for a bank.
     */
    public static function getMovementStatistics(Bank $bank): array
    {
        $today = now()->startOfDay();
        $thisWeek = now()->startOfWeek();
        $thisMonth = now()->startOfMonth();

        // Mouvements d'aujourd'hui
        $movementsToday = BloodBagMovement::where('bank_id', $bank->id)
            ->whereDate('movement_date', $today)
            ->count();

        // Mouvements de cette semaine
        $movementsThisWeek = BloodBagMovement::where('bank_id', $bank->id)
            ->where('movement_date', '>=', $thisWeek)
            ->count();

        // Mouvements de ce mois
        $movementsThisMonth = BloodBagMovement::where('bank_id', $bank->id)
            ->where('movement_date', '>=', $thisMonth)
            ->count();

        // Mouvements par type
        $movementsByType = BloodBagMovement::where('bank_id', $bank->id)
            ->selectRaw('movement_type, COUNT(*) as count')
            ->groupBy('movement_type')
            ->pluck('count', 'movement_type')
            ->toArray();

        // Mouvements récents (7 derniers jours)
        $recentMovements = BloodBagMovement::where('bank_id', $bank->id)
            ->where('movement_date', '>=', now()->subDays(7))
            ->with(['bloodBag.bloodType'])
            ->orderBy('movement_date', 'desc')
            ->limit(10)
            ->get();

        return [
            'movements_today' => $movementsToday,
            'movements_this_week' => $movementsThisWeek,
            'movements_this_month' => $movementsThisMonth,
            'movements_by_type' => $movementsByType,
            'recent_movements' => $recentMovements,
            'total_movements' => BloodBagMovement::where('bank_id', $bank->id)->count(),
        ];
    }

    /**
     * Get reservation statistics for a bank.
     */
    public static function getReservationStatistics(Bank $bank): array
    {
        $activeReservations = BloodBagReservation::where('bank_id', $bank->id)
            ->where('status', 'active')
            ->count();

        $expiredReservations = BloodBagReservation::where('bank_id', $bank->id)
            ->where('status', 'expired')
            ->count();

        $cancelledReservations = BloodBagReservation::where('bank_id', $bank->id)
            ->where('status', 'cancelled')
            ->count();

        $completedReservations = BloodBagReservation::where('bank_id', $bank->id)
            ->where('status', 'completed')
            ->count();

        $urgentReservations = BloodBagReservation::where('bank_id', $bank->id)
            ->where('status', 'active')
            ->where('urgency_level', 'urgent')
            ->count();

        $criticalReservations = BloodBagReservation::where('bank_id', $bank->id)
            ->where('status', 'active')
            ->where('urgency_level', 'critical')
            ->count();

        $expiringSoonReservations = BloodBagReservation::where('bank_id', $bank->id)
            ->where('status', 'active')
            ->where('expiry_date', '<=', now()->addHours(24))
            ->count();

        return [
            'active_count' => $activeReservations,
            'expired_count' => $expiredReservations,
            'cancelled_count' => $cancelledReservations,
            'completed_count' => $completedReservations,
            'urgent_count' => $urgentReservations,
            'critical_count' => $criticalReservations,
            'expiring_soon_count' => $expiringSoonReservations,
            'total' => $activeReservations + $expiredReservations + $cancelledReservations + $completedReservations,
        ];
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
            'reserved_volume_l' => ($reservedBags * 450) / 1000,
            'critical_stocks' => $criticalStocks,
            'low_stocks' => $lowStocks,
            'stock_summary' => self::getStockSummary($bank),
        ];
    }

    /**
     * Get detailed statistics for a bank, organized by blood type.
     */
    public static function getDetailedStatistics(Bank $bank): array
    {
        $stocks = $bank->bloodStocks()->with('bloodType')->get();
        $bloodTypes = BloodType::orderBy('name')->get();

        $byBloodType = [];
        $totals = [
            'count' => 0,
            'available' => 0,
            'reserved' => 0,
            'transfused' => 0,
            'expired' => 0,
            'discarded' => 0,
            'volume_l' => 0,
            'expiring_soon' => 0
        ];

        // Initialiser tous les types de sang
        foreach ($bloodTypes as $bloodType) {
            $byBloodType[$bloodType->name] = [
                'count' => 0,
                'available' => 0,
                'reserved' => 0,
                'transfused' => 0,
                'expired' => 0,
                'discarded' => 0,
                'volume_l' => 0,
                'expiring_soon' => 0
            ];
        }

        // Remplir avec les données des stocks existants
        foreach ($stocks as $stock) {
            $bloodTypeName = $stock->bloodType->name;

            $byBloodType[$bloodTypeName] = [
                'count' => $stock->total_bags,
                'available' => $stock->available_bags,
                'reserved' => $stock->reserved_bags,
                'transfused' => $stock->transfused_bags ?? 0,
                'expired' => $stock->expired_bags ?? 0,
                'discarded' => $stock->discarded_bags ?? 0,
                'volume_l' => $stock->getTotalVolumeInLiters(),
                'expiring_soon' => $stock->expiring_soon_bags,
                'percentage' => $totals['count'] > 0 ? round(($stock->total_bags / $totals['count']) * 100, 1) : 0
            ];

            // Ajouter aux totaux
            $totals['count'] += $stock->total_bags;
            $totals['available'] += $stock->available_bags;
            $totals['reserved'] += $stock->reserved_bags;
            $totals['transfused'] += $stock->transfused_bags ?? 0;
            $totals['expired'] += $stock->expired_bags ?? 0;
            $totals['discarded'] += $stock->discarded_bags ?? 0;
            $totals['volume_l'] += $stock->getTotalVolumeInLiters();
            $totals['expiring_soon'] += $stock->expiring_soon_bags;
        }

        return [
            'by_blood_type' => $byBloodType,
            'totals' => $totals,
            'stock_summary' => self::getStockSummary($bank)
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
