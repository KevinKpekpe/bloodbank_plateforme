<?php

namespace App\Helpers;

use App\Models\BloodStock;
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

        if ($stock->quantity <= ($stock->critical_level * 0.5)) {
            $status = 'critical';
        } elseif ($stock->quantity <= $stock->critical_level) {
            $status = 'low';
        } elseif ($stock->quantity > ($stock->critical_level * 3)) {
            $status = 'high';
        }

        $stock->update([
            'status' => $status,
            'last_updated' => now(),
        ]);
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
                'quantity' => $stock->quantity,
                'status' => $stock->status,
                'critical_level' => $stock->critical_level,
            ];
        }

        return $summary;
    }

    /**
     * Check if stock needs replenishment.
     */
    public static function needsReplenishment(BloodStock $stock): bool
    {
        return $stock->quantity <= $stock->critical_level;
    }

    /**
     * Get available blood types for a bank.
     */
    public static function getAvailableBloodTypes(Bank $bank): array
    {
        return $bank->bloodStocks()
            ->where('quantity', '>', 0)
            ->with('bloodType')
            ->get()
            ->pluck('bloodType.name')
            ->toArray();
    }
}