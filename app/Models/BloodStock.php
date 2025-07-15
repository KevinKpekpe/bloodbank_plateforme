<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BloodStock extends Model
{
    use HasFactory;

    protected $fillable = [
        'bank_id',
        'blood_type_id',
        'quantity',
        'total_bags',
        'available_bags',
        'reserved_bags',
        'expiring_soon_bags',
        'critical_level',
        'status',
        'last_updated',
    ];

    protected $casts = [
        'last_updated' => 'datetime',
    ];

    /**
     * Get the bank for this blood stock.
     */
    public function bank()
    {
        return $this->belongsTo(Bank::class);
    }

    /**
     * Get the blood type for this blood stock.
     */
    public function bloodType()
    {
        return $this->belongsTo(BloodType::class);
    }

    /**
     * Get all blood bags for this stock.
     */
    public function bloodBags()
    {
        return $this->hasMany(BloodBag::class, 'blood_type_id', 'blood_type_id')
                    ->where('bank_id', $this->bank_id);
    }

    /**
     * Get available blood bags.
     */
    public function availableBloodBags()
    {
        return $this->bloodBags()->where('status', 'available');
    }

    /**
     * Get reserved blood bags.
     */
    public function reservedBloodBags()
    {
        return $this->bloodBags()->where('status', 'reserved');
    }

    /**
     * Get expiring soon blood bags.
     */
    public function expiringSoonBloodBags()
    {
        return $this->bloodBags()
                    ->where('status', 'available')
                    ->where('expiry_date', '<=', now()->addDays(7));
    }

    /**
     * Check if stock is low.
     */
    public function isLow(): bool
    {
        return $this->available_bags <= ($this->critical_level / 450); // Convertir ml en nombre de poches
    }

    /**
     * Check if stock is critical.
     */
    public function isCritical(): bool
    {
        return $this->available_bags <= (($this->critical_level * 0.5) / 450);
    }

    /**
     * Check if stock is high.
     */
    public function isHigh(): bool
    {
        return $this->available_bags > (($this->critical_level * 3) / 450);
    }

    /**
     * Update statistics based on blood bags.
     */
    public function updateStatistics()
    {
        $totalBags = $this->bloodBags()
            ->whereIn('status', ['available', 'reserved'])
            ->count();

        $availableBags = $this->bloodBags()
            ->where('status', 'available')
            ->count();

        $reservedBags = $this->bloodBags()
            ->where('status', 'reserved')
            ->count();

        $expiringSoonBags = $this->bloodBags()
            ->where('status', 'available')
            ->where('expiry_date', '<=', now()->addDays(7))
            ->count();

        $this->update([
            'total_bags' => $totalBags,
            'available_bags' => $availableBags,
            'reserved_bags' => $reservedBags,
            'expiring_soon_bags' => $expiringSoonBags,
            'quantity' => $totalBags * 450, // Pour compatibilité
            'last_updated' => now(),
        ]);

        // Mettre à jour le statut
        $this->updateStatus();
    }

    /**
     * Update status based on available bags.
     */
    public function updateStatus()
    {
        $status = 'normal';

        if ($this->isCritical()) {
            $status = 'critical';
        } elseif ($this->isLow()) {
            $status = 'low';
        } elseif ($this->isHigh()) {
            $status = 'high';
        }

        $this->update(['status' => $status]);
    }

    /**
     * Get total volume in liters.
     */
    public function getTotalVolumeInLiters(): float
    {
        return ($this->total_bags * 450) / 1000;
    }

    /**
     * Get available volume in liters.
     */
    public function getAvailableVolumeInLiters(): float
    {
        return ($this->available_bags * 450) / 1000;
    }
}
