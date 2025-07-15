<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BloodBagMovement extends Model
{
    use HasFactory;

    protected $fillable = [
        'blood_bag_id',
        'bank_id',
        'movement_type',
        'quantity',
        'recipient_type',
        'recipient_name',
        'recipient_id',
        'reason',
        'requested_by',
        'authorized_by',
        'movement_date',
        'notes',
    ];

    protected $casts = [
        'movement_date' => 'datetime',
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
     * Scopes pour filtrer les mouvements
     */
    public function scopeByType($query, $type)
    {
        return $query->where('movement_type', $type);
    }

    public function scopeByBank($query, $bankId)
    {
        return $query->where('bank_id', $bankId);
    }

    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('movement_date', [$startDate, $endDate]);
    }

    public function scopeRecent($query, $days = 30)
    {
        return $query->where('movement_date', '>=', now()->subDays($days));
    }

    /**
     * Méthodes utilitaires
     */
    public function isInbound(): bool
    {
        return $this->movement_type === 'in';
    }

    public function isOutbound(): bool
    {
        return in_array($this->movement_type, ['out', 'transfer']);
    }

    public function isReservation(): bool
    {
        return $this->movement_type === 'reservation';
    }

    public function isCancellation(): bool
    {
        return $this->movement_type === 'cancellation';
    }

    public function getMovementTypeLabel(): string
    {
        return match($this->movement_type) {
            'in' => 'Entrée',
            'out' => 'Sortie',
            'reservation' => 'Réservation',
            'cancellation' => 'Annulation',
            'transfer' => 'Transfert',
            default => 'Inconnu'
        };
    }

    public function getRecipientTypeLabel(): string
    {
        return match($this->recipient_type) {
            'patient' => 'Patient',
            'hospital' => 'Hôpital',
            'other_bank' => 'Autre banque',
            'discard' => 'Destruction',
            default => 'Inconnu'
        };
    }
}
