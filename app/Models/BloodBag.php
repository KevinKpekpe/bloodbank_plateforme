<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BloodBag extends Model
{
    use HasFactory;

    protected $fillable = [
        'donation_id',
        'bank_id',
        'blood_type_id',
        'donor_id',
        'bag_number',
        'volume_ml',
        'collection_date',
        'expiry_date',
        'status',
        'reserved_for_patient',
        'reserved_for_hospital',
        'reserved_until',
        'notes',
    ];

    protected $casts = [
        'collection_date' => 'date',
        'expiry_date' => 'date',
        'reserved_until' => 'datetime',
    ];

    /**
     * Relations
     */
    public function donation()
    {
        return $this->belongsTo(Donation::class);
    }

    public function bank()
    {
        return $this->belongsTo(Bank::class);
    }

    public function bloodType()
    {
        return $this->belongsTo(BloodType::class);
    }

    public function donor()
    {
        return $this->belongsTo(Donor::class);
    }

    public function movements()
    {
        return $this->hasMany(BloodBagMovement::class);
    }

    public function reservations()
    {
        return $this->hasMany(BloodBagReservation::class);
    }

    public function activeReservation()
    {
        return $this->hasOne(BloodBagReservation::class)->where('status', 'active');
    }

    /**
     * Scopes pour filtrer les poches
     */
    public function scopeAvailable($query)
    {
        return $query->where('status', 'available');
    }

    public function scopeReserved($query)
    {
        return $query->where('status', 'reserved');
    }

    public function scopeExpiringSoon($query, $days = 7)
    {
        return $query->where('status', 'available')
                    ->where('expiry_date', '<=', now()->addDays($days));
    }

    public function scopeByBloodType($query, $bloodTypeId)
    {
        return $query->where('blood_type_id', $bloodTypeId);
    }

    public function scopeByBank($query, $bankId)
    {
        return $query->where('bank_id', $bankId);
    }

    /**
     * Méthodes de statut
     */
    public function isAvailable(): bool
    {
        return $this->status === 'available';
    }

    public function isReserved(): bool
    {
        return $this->status === 'reserved';
    }

    public function isTransfused(): bool
    {
        return $this->status === 'transfused';
    }

    public function isExpired(): bool
    {
        return $this->status === 'expired' || $this->expiry_date->isPast();
    }

    public function isExpiringSoon(int $days = 7): bool
    {
        return $this->expiry_date->diffInDays(now()) <= $days;
    }

    public function isDiscarded(): bool
    {
        return $this->status === 'discarded';
    }

    /**
     * Méthodes de gestion des réservations
     */
    public function reserveForPatient(array $patientData, int $duration = 24)
    {
        return DB::transaction(function () use ($patientData, $duration) {
            // Créer la réservation
            $reservation = BloodBagReservation::create([
                'blood_bag_id' => $this->id,
                'bank_id' => $this->bank_id,
                'patient_name' => $patientData['patient_name'],
                'patient_id' => $patientData['patient_id'] ?? null,
                'hospital_name' => $patientData['hospital_name'] ?? null,
                'doctor_name' => $patientData['doctor_name'] ?? null,
                'reservation_date' => now(),
                'expiry_date' => now()->addHours($duration),
                'status' => 'active',
                'urgency_level' => $patientData['urgency_level'] ?? 'normal',
                'surgery_date' => $patientData['surgery_date'] ?? null,
                'notes' => $patientData['notes'] ?? null,
            ]);

            // Changer le statut de la poche
            $this->update([
                'status' => 'reserved',
                'reserved_for_patient' => $patientData['patient_name'],
                'reserved_for_hospital' => $patientData['hospital_name'] ?? null,
                'reserved_until' => now()->addHours($duration),
            ]);

            // Enregistrer le mouvement
            BloodBagMovement::create([
                'blood_bag_id' => $this->id,
                'bank_id' => $this->bank_id,
                'movement_type' => 'reservation',
                'recipient_type' => 'patient',
                'recipient_name' => $patientData['patient_name'],
                'recipient_id' => $patientData['patient_id'] ?? null,
                'reason' => 'Réservation pour transfusion',
                'requested_by' => $patientData['requested_by'] ?? null,
                'movement_date' => now(),
                'notes' => $patientData['notes'] ?? null,
            ]);

            return $reservation;
        });
    }

    public function transfuse(string $authorizedBy, array $transfusionData = [])
    {
        return DB::transaction(function () use ($authorizedBy, $transfusionData) {
            $reservation = $this->activeReservation()->first();

            if (!$reservation) {
                throw new \Exception('Aucune réservation active pour cette poche');
            }

            // Changer le statut de la poche
            $this->update([
                'status' => 'transfused',
                'reserved_for_patient' => null,
                'reserved_for_hospital' => null,
                'reserved_until' => null,
            ]);

            // Marquer la réservation comme terminée
            $reservation->update(['status' => 'completed']);

            // Enregistrer le mouvement
            BloodBagMovement::create([
                'blood_bag_id' => $this->id,
                'bank_id' => $this->bank_id,
                'movement_type' => 'out',
                'recipient_type' => 'patient',
                'recipient_name' => $reservation->patient_name,
                'recipient_id' => $reservation->patient_id,
                'reason' => 'Transfusion effectuée',
                'authorized_by' => $authorizedBy,
                'movement_date' => now(),
                'notes' => $transfusionData['notes'] ?? null,
            ]);

            return $reservation;
        });
    }

    public function cancelReservation(string $reason = 'Annulation')
    {
        return DB::transaction(function () use ($reason) {
            $reservation = $this->activeReservation()->first();

            if ($reservation) {
                $reservation->update(['status' => 'cancelled']);
            }

            // Remettre la poche en disponible
            $this->update([
                'status' => 'available',
                'reserved_for_patient' => null,
                'reserved_for_hospital' => null,
                'reserved_until' => null,
            ]);

            // Enregistrer le mouvement
            BloodBagMovement::create([
                'blood_bag_id' => $this->id,
                'bank_id' => $this->bank_id,
                'movement_type' => 'cancellation',
                'recipient_type' => 'patient',
                'recipient_name' => $reservation ? $reservation->patient_name : null,
                'recipient_id' => $reservation ? $reservation->patient_id : null,
                'reason' => $reason,
                'movement_date' => now(),
            ]);
        });
    }

    public function discard(string $reason, string $authorizedBy)
    {
        return DB::transaction(function () use ($reason, $authorizedBy) {
            // Annuler toute réservation active
            $reservation = $this->activeReservation()->first();
            if ($reservation) {
                $reservation->update(['status' => 'cancelled']);
            }

            // Changer le statut de la poche
            $this->update([
                'status' => 'discarded',
                'reserved_for_patient' => null,
                'reserved_for_hospital' => null,
                'reserved_until' => null,
            ]);

            // Enregistrer le mouvement
            BloodBagMovement::create([
                'blood_bag_id' => $this->id,
                'bank_id' => $this->bank_id,
                'movement_type' => 'out',
                'recipient_type' => 'discard',
                'reason' => $reason,
                'authorized_by' => $authorizedBy,
                'movement_date' => now(),
            ]);
        });
    }

    /**
     * Marquer une poche comme expirée
     */
    public function expire(string $reason = 'Expiration automatique')
    {
        return DB::transaction(function () use ($reason) {
            // Annuler toute réservation active
            $reservation = $this->activeReservation()->first();
            if ($reservation) {
                $reservation->update(['status' => 'cancelled']);
            }

            // Changer le statut de la poche
            $this->update([
                'status' => 'expired',
                'reserved_for_patient' => null,
                'reserved_for_hospital' => null,
                'reserved_until' => null,
            ]);

            // Enregistrer le mouvement
            BloodBagMovement::create([
                'blood_bag_id' => $this->id,
                'bank_id' => $this->bank_id,
                'movement_type' => 'expiration',
                'recipient_type' => 'expired',
                'reason' => $reason,
                'movement_date' => now(),
            ]);
        });
    }

    /**
     * Méthodes utilitaires
     */
    public function getVolumeInLiters(): float
    {
        return $this->volume_ml / 1000;
    }

    public function getDaysUntilExpiry(): int
    {
        return $this->expiry_date->diffInDays(now());
    }

    public function getStatusColor(): string
    {
        return match($this->status) {
            'available' => 'green',
            'reserved' => 'yellow',
            'transfused' => 'blue',
            'expired' => 'red',
            'discarded' => 'gray',
            default => 'gray'
        };
    }

    /**
     * Générer un numéro de poche unique
     */
    public static function generateBagNumber(): string
    {
        $year = date('Y');
        $count = self::whereYear('created_at', $year)->count() + 1;
        return "BAG-{$year}-" . str_pad($count, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Boot method pour définir les dates automatiquement
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($bloodBag) {
            if (empty($bloodBag->bag_number)) {
                $bloodBag->bag_number = self::generateBagNumber();
            }

            if (empty($bloodBag->expiry_date) && $bloodBag->collection_date) {
                $bloodBag->expiry_date = Carbon::parse($bloodBag->collection_date)->addDays(42);
            }
        });
    }
}
