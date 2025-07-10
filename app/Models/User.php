<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Log;

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
        'status',
        'created_by',
        'email_verification_code',
        'email_verification_code_expires_at',
        'email_verified_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'email_verification_code',
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
            'email_verification_code_expires_at' => 'datetime',
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
     * Get the appointments for this user (through donor).
     */
    public function appointments()
    {
        return $this->hasManyThrough(Appointment::class, Donor::class);
    }

    /**
     * Get the donations for this user (through donor).
     */
    public function donations()
    {
        return $this->hasManyThrough(Donation::class, Donor::class);
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

    /**
     * Check if user's email is verified.
     */
    public function isEmailVerified(): bool
    {
        $verified = !is_null($this->email_verified_at);
        Log::info('User@isEmailVerified - User ID: ' . $this->id . ', Email verified: ' . ($verified ? 'true' : 'false') . ', email_verified_at: ' . $this->email_verified_at);
        return $verified;
    }

    /**
     * Check if verification code is expired.
     */
    public function isVerificationCodeExpired(): bool
    {
        $expired = $this->email_verification_code_expires_at && $this->email_verification_code_expires_at->isPast();
        Log::info('User@isVerificationCodeExpired - User ID: ' . $this->id . ', Code expired: ' . ($expired ? 'true' : 'false') . ', expires_at: ' . $this->email_verification_code_expires_at);
        return $expired;
    }

    /**
     * Generate a new verification code.
     */
    public function generateVerificationCode(): string
    {
        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        Log::info('User@generateVerificationCode - User ID: ' . $this->id . ', Generated code: ' . $code);

        $this->update([
            'email_verification_code' => $code,
            'email_verification_code_expires_at' => now()->addMinutes(15),
        ]);

        Log::info('User@generateVerificationCode - User ID: ' . $this->id . ', Code saved to database');
        return $code;
    }

    /**
     * Verify the email with the provided code.
     */
    public function verifyEmail(string $code): bool
    {
        Log::info('User@verifyEmail - User ID: ' . $this->id . ', Stored code: ' . $this->email_verification_code . ', Provided code: ' . $code);
        Log::info('User@verifyEmail - User ID: ' . $this->id . ', Code expired: ' . ($this->isVerificationCodeExpired() ? 'true' : 'false'));

        if ($this->email_verification_code === $code && !$this->isVerificationCodeExpired()) {
            Log::info('User@verifyEmail - User ID: ' . $this->id . ', Code is valid, updating email_verified_at');

            $this->update([
                'email_verified_at' => now(),
                'email_verification_code' => null,
                'email_verification_code_expires_at' => null,
            ]);

            // Recharger l'instance pour avoir les données mises à jour
            $this->refresh();

            Log::info('User@verifyEmail - User ID: ' . $this->id . ', Email verification completed successfully');
            return true;
        }

        Log::error('User@verifyEmail - User ID: ' . $this->id . ', Code verification failed');
        return false;
    }
}
