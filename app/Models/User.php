<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone_number',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the donor associated with the user.
     */
    public function donor()
    {
        return $this->hasOne(Donor::class);
    }

    /**
     * Banque administrée par cet utilisateur.
     */
    public function managedBank()
    {
        return $this->hasOne(Bank::class, 'admin_id');
    }

    /**
     * Get the custom notifications for the user.
     */
    public function customNotifications()
    {
        return $this->hasMany(Notification::class);
    }

    /**
     * Check if user is a donor.
     */
    public function isDonor(): bool
    {
        return $this->role === 'donor';
    }

    /**
     * Check if user is a bank admin.
     */
    public function isBankAdmin(): bool
    {
        return $this->role === 'admin_banque';
    }

    /**
     * Check if user is a super admin.
     */
    public function isSuperAdmin(): bool
    {
        return $this->role === 'superadmin';
    }
}
