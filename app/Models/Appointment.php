<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'donor_id',
        'bank_id',
        'appointment_date',
        'status',
        'notes',
    ];

    protected $casts = [
        'appointment_date' => 'datetime',
    ];

    /**
     * Get the donor for this appointment.
     */
    public function donor()
    {
        return $this->belongsTo(Donor::class);
    }

    /**
     * Get the bank for this appointment.
     */
    public function bank()
    {
        return $this->belongsTo(Bank::class);
    }

    /**
     * Get the donation for this appointment.
     */
    public function donation()
    {
        return $this->hasOne(Donation::class);
    }

    /**
     * Check if appointment is pending.
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if appointment is confirmed.
     */
    public function isConfirmed(): bool
    {
        return $this->status === 'confirmed';
    }

    /**
     * Check if appointment is completed.
     */
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    /**
     * Check if appointment is cancelled.
     */
    public function isCancelled(): bool
    {
        return $this->status === 'cancelled';
    }
}
