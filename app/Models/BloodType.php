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
}
