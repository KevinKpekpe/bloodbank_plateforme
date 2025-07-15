<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Donation extends Model
{
    use HasFactory;

    protected $fillable = [
        'appointment_id',
        'donor_id',
        'bank_id',
        'blood_type_id',
        'donation_date',
        'quantity',
        'volume',
        'status',
        'notes',
    ];

    protected $casts = [
        'donation_date' => 'date',
        'volume' => 'decimal:2',
    ];

    /**
     * Get the appointment for this donation.
     */
    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    /**
     * Get the donor for this donation.
     */
    public function donor()
    {
        return $this->belongsTo(Donor::class);
    }

    /**
     * Get the bank for this donation.
     */
    public function bank()
    {
        return $this->belongsTo(Bank::class);
    }

    /**
     * Get the blood type for this donation.
     */
    public function bloodType()
    {
        return $this->belongsTo(BloodType::class);
    }

    /**
     * Get the blood bags created from this donation.
     */
    public function bloodBags()
    {
        return $this->hasMany(BloodBag::class);
    }

    /**
     * Get the number of blood bags created from this donation.
     */
    public function getBloodBagsCount(): int
    {
        return $this->bloodBags()->count();
    }

    /**
     * Get available blood bags from this donation.
     */
    public function getAvailableBloodBags()
    {
        return $this->bloodBags()->where('status', 'available');
    }

    /**
     * Check if donation is collected.
     */
    public function isCollected(): bool
    {
        return $this->status === 'collected';
    }

    /**
     * Check if donation is processed.
     */
    public function isProcessed(): bool
    {
        return $this->status === 'processed';
    }

    /**
     * Check if donation is available.
     */
    public function isAvailable(): bool
    {
        return $this->status === 'available';
    }

    /**
     * Check if donation is expired.
     */
    public function isExpired(): bool
    {
        return $this->status === 'expired';
    }
}
