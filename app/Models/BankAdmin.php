<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankAdmin extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'bank_id',
        'role',
    ];

    /**
     * Get the user for this bank admin.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the bank for this bank admin.
     */
    public function bank()
    {
        return $this->belongsTo(Bank::class);
    }
}
