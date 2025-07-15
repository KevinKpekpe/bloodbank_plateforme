<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BloodType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    /**
     * Get the donors with this blood type.
     */
    public function donors()
    {
        return $this->hasMany(Donor::class);
    }

    /**
     * Get the blood stocks for this blood type.
     */
    public function bloodStocks()
    {
        return $this->hasMany(BloodStock::class);
    }

    /**
     * Get the donations with this blood type.
     */
    public function donations()
    {
        return $this->hasMany(Donation::class);
    }

    /**
     * Get compatible blood types for recipients (who can receive this blood type).
     */
    public function getCompatibleRecipients()
    {
        // Règles de compatibilité selon les groupes sanguins
        $compatibility = [
            'A+' => ['A+', 'AB+'],
            'A-' => ['A+', 'A-', 'AB+', 'AB-'],
            'B+' => ['B+', 'AB+'],
            'B-' => ['B+', 'B-', 'AB+', 'AB-'],
            'AB+' => ['AB+'],
            'AB-' => ['AB+', 'AB-'],
            'O+' => ['A+', 'B+', 'AB+', 'O+'],
            'O-' => ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'],
        ];

        $compatibleTypes = $compatibility[$this->name] ?? [];

        return BloodType::whereIn('name', $compatibleTypes)->get();
    }

    /**
     * Get compatible donors (blood types that can donate to this type).
     */
    public function getCompatibleDonors()
    {
        // Règles inversées pour les donneurs
        $donorCompatibility = [
            'A+' => ['A+', 'A-', 'O+', 'O-'],
            'A-' => ['A-', 'O-'],
            'B+' => ['B+', 'B-', 'O+', 'O-'],
            'B-' => ['B-', 'O-'],
            'AB+' => ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'],
            'AB-' => ['A-', 'B-', 'AB-', 'O-'],
            'O+' => ['O+', 'O-'],
            'O-' => ['O-'],
        ];

        $compatibleTypes = $donorCompatibility[$this->name] ?? [];

        return BloodType::whereIn('name', $compatibleTypes)->get();
    }
}
