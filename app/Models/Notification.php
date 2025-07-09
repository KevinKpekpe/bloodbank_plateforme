<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'message',
        'type',
        'read_at',
        'data'
    ];

    protected $casts = [
        'read_at' => 'datetime',
        'data' => 'array'
    ];

    /**
     * Relation avec l'utilisateur
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope pour les notifications non lues
     */
    public function scopeUnread($query)
    {
        return $query->whereNull('read_at');
    }

    /**
     * Scope pour les notifications lues
     */
    public function scopeRead($query)
    {
        return $query->whereNotNull('read_at');
    }

    /**
     * Marquer comme lue
     */
    public function markAsRead()
    {
        $this->update(['read_at' => now()]);
    }

    /**
     * Marquer comme non lue
     */
    public function markAsUnread()
    {
        $this->update(['read_at' => null]);
    }

    /**
     * Vérifier si la notification est lue
     */
    public function isRead()
    {
        return !is_null($this->read_at);
    }

    /**
     * Vérifier si la notification est non lue
     */
    public function isUnread()
    {
        return is_null($this->read_at);
    }

    /**
     * Créer une notification pour un utilisateur
     */
    public static function createForUser($userId, $title, $message, $type = 'info', $data = [])
    {
        return self::create([
            'user_id' => $userId,
            'title' => $title,
            'message' => $message,
            'type' => $type,
            'data' => $data
        ]);
    }

    /**
     * Créer une notification pour tous les utilisateurs d'une banque
     */
    public static function createForBank($bankId, $title, $message, $type = 'info', $data = [])
    {
        $users = User::where('bank_id', $bankId)->pluck('id');

        foreach ($users as $userId) {
            self::createForUser($userId, $title, $message, $type, $data);
        }
    }

    /**
     * Créer une notification pour tous les donneurs
     */
    public static function createForAllDonors($title, $message, $type = 'info', $data = [])
    {
        $users = User::where('role', 'donor')->pluck('id');

        foreach ($users as $userId) {
            self::createForUser($userId, $title, $message, $type, $data);
        }
    }

    /**
     * Créer une notification pour tous les administrateurs
     */
    public static function createForAllAdmins($title, $message, $type = 'info', $data = [])
    {
        $users = User::where('role', 'admin')->pluck('id');

        foreach ($users as $userId) {
            self::createForUser($userId, $title, $message, $type, $data);
        }
    }
}
