<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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
     * Admin de cette banque.
     */
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
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

    /**
     * Get the users associated with this bank (donors who have appointments or donations).
     */
    public function users()
    {
        // Récupérer les IDs des utilisateurs qui ont des interactions avec cette banque
        $appointmentUserIds = $this->appointments()
            ->join('donors', 'appointments.donor_id', '=', 'donors.id')
            ->pluck('donors.user_id');

        $donationUserIds = $this->donations()
            ->join('donors', 'donations.donor_id', '=', 'donors.id')
            ->pluck('donors.user_id');

        $userIds = $appointmentUserIds->merge($donationUserIds)->unique();

        return User::whereIn('id', $userIds);
    }
}
