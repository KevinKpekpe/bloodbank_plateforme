<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'latitude',
        'longitude',
        'contact_phone',
        'contact_email',
        'status',
        'created_by',
    ];

    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    /**
     * Get the user who created this bank.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the bank admins for this bank.
     */
    public function bankAdmins()
    {
        return $this->hasMany(BankAdmin::class);
    }

    /**
     * Get the admins for this bank.
     */
    public function admins()
    {
        return $this->belongsToMany(User::class, 'bank_admins');
    }

    /**
     * Get the blood stocks for this bank.
     */
    public function bloodStocks()
    {
        return $this->hasMany(BloodStock::class);
    }

    /**
     * Get the appointments for this bank.
     */
    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    /**
     * Get the donations for this bank.
     */
    public function donations()
    {
        return $this->hasMany(Donation::class);
    }

    /**
     * Get the notifications for this bank.
     */
    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }
}
