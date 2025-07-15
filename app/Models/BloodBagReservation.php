<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BloodBagReservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'blood_bag_id',
        'bank_id',
        'patient_name',
        'patient_id',
        'hospital_name',
        'doctor_name',
        'reservation_date',
        'expiry_date',
        'status',
        'urgency_level',
        'surgery_date',
        'notes',
    ];

    protected $casts = [
        'reservation_date' => 'datetime',
        'expiry_date' => 'datetime',
        'surgery_date' => 'date',
    ];

    /**
     * Relations
     */
    public function bloodBag()
    {
        return $this->belongsTo(BloodBag::class);
    }

    public function bank()
    {
        return $this->belongsTo(Bank::class);
    }

    /**
     * Scopes pour filtrer les réservations
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }

    public function scopeExpired($query)
    {
        return $query->where('status', 'expired');
    }

    public function scopeByBank($query, $bankId)
    {
        return $query->where('bank_id', $bankId);
    }

    public function scopeByUrgency($query, $urgencyLevel)
    {
        return $query->where('urgency_level', $urgencyLevel);
    }

    public function scopeExpiringSoon($query, $hours = 24)
    {
        return $query->where('status', 'active')
                    ->where('expiry_date', '<=', now()->addHours($hours));
    }

    /**
     * Méthodes de statut
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    public function isCancelled(): bool
    {
        return $this->status === 'cancelled';
    }

    public function isExpired(): bool
    {
        return $this->status === 'expired' || $this->expiry_date->isPast();
    }

    public function isExpiringSoon(int $hours = 24): bool
    {
        return $this->expiry_date->diffInHours(now()) <= $hours;
    }

    public function isUrgent(): bool
    {
        return $this->urgency_level === 'urgent';
    }

    public function isCritical(): bool
    {
        return $this->urgency_level === 'critical';
    }

    /**
     * Méthodes utilitaires
     */
    public function getUrgencyLevelLabel(): string
    {
        return match($this->urgency_level) {
            'normal' => 'Normal',
            'urgent' => 'Urgent',
            'critical' => 'Critique',
            default => 'Inconnu'
        };
    }

    public function getStatusLabel(): string
    {
        return match($this->status) {
            'active' => 'Active',
            'completed' => 'Terminée',
            'cancelled' => 'Annulée',
            'expired' => 'Expirée',
            default => 'Inconnu'
        };
    }

    public function getUrgencyColor(): string
    {
        return match($this->urgency_level) {
            'normal' => 'green',
            'urgent' => 'yellow',
            'critical' => 'red',
            default => 'gray'
        };
    }

    public function getStatusColor(): string
    {
        return match($this->status) {
            'active' => 'blue',
            'completed' => 'green',
            'cancelled' => 'gray',
            'expired' => 'red',
            default => 'gray'
        };
    }

    public function getHoursUntilExpiry(): int
    {
        return $this->expiry_date->diffInHours(now());
    }

    /**
     * Méthodes de gestion
     */
    public function complete()
    {
        $this->update(['status' => 'completed']);
    }

    public function cancel(string $reason = null)
    {
        $this->update([
            'status' => 'cancelled',
            'notes' => $reason ? ($this->notes ? $this->notes . "\n" . $reason : $reason) : $this->notes
        ]);
    }

    public function markAsExpired()
    {
        $this->update(['status' => 'expired']);
    }

    /**
     * Boot method pour gérer les expirations automatiques
     */
    protected static function boot()
    {
        parent::boot();

        static::updating(function ($reservation) {
            // Marquer comme expirée si la date d'expiration est passée
            if ($reservation->isDirty('expiry_date') && $reservation->expiry_date->isPast()) {
                $reservation->status = 'expired';
            }
        });
    }
}
