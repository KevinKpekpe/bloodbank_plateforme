<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Donor extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'firstname',
        'surname',
        'blood_type_id',
        'gender',
        'birthdate',
        'address',
        'phone_number',
        'last_donation_date',
        'total_donations',
        'total_volume',
        'status',
    ];

    protected $casts = [
        'birthdate' => 'date',
        'last_donation_date' => 'date',
        'total_volume' => 'decimal:2',
    ];

    /**
     * Get the user for this donor.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the blood type for this donor.
     */
    public function bloodType()
    {
        return $this->belongsTo(BloodType::class);
    }

    /**
     * Get the appointments for this donor.
     */
    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    /**
     * Get the donations for this donor.
     */
    public function donations()
    {
        return $this->hasMany(Donation::class);
    }

    /**
     * Get the notifications for this donor.
     */
    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    /**
     * Get the full name of the donor.
     */
    public function getFullNameAttribute(): string
    {
        return $this->firstname . ' ' . $this->surname;
    }
}
