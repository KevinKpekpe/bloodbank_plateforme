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
     * Check if stock is low.
     */
    public function isLow(): bool
    {
        return $this->quantity <= $this->critical_level;
    }

    /**
     * Check if stock is critical.
     */
    public function isCritical(): bool
    {
        return $this->quantity <= ($this->critical_level * 0.5);
    }

    /**
     * Check if stock is high.
     */
    public function isHigh(): bool
    {
        return $this->quantity > ($this->critical_level * 3);
    }
}
