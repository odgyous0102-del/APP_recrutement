<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use App\Models\Traits\AdminUserTrait;
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, AdminUserTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'telephone',
        'password',
        'role',
        'is_active',
        'ville',
        'pays',
        'profile_picture',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
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
            'is_active' => 'boolean',
        ];
    }

    /**
     * Get the candidat associated with the user.
     */
    public function candidat(): HasOne
    {
        return $this->hasOne(Candidat::class);
    }

    /**
     * Get the recruteur associated with the user.
     */
    public function recruteur(): HasOne
    {
        return $this->hasOne(Recruteur::class);
    }

    /**
     * Get the notifications for the user.
     */
    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }

    /**
     * Get the unread notifications for the user.
     */
    public function unreadNotifications()
    {
        return $this->notifications()->whereNull('lu_at');
    }

    /**
     * Get the administrateur associated with the user.
     */
    public function administrateur(): HasOne
    {
        return $this->hasOne(Administrateur::class);
    }

    /**
     * Get the messages sent by the user.
     */
    public function messagesEnvoyes(): HasMany
    {
        return $this->hasMany(Message::class, 'expediteur_id');
    }

    /**
     * Get the messages received by the user.
     */
    public function messagesRecus(): HasMany
    {
        return $this->hasMany(Message::class, 'destinataire_id');
    }

    /**
     * Get the favorite offers for the user.
     */
    public function offreFavoris(): HasMany
    {
        return $this->hasMany(OffreFavori::class);
    }

    /**
     * Get the favorite offers for the user.
     */
    public function offresFavorites(): BelongsToMany
    {
        return $this->belongsToMany(Offre::class, 'offre_favoris');
    }

    /**
     * Get the audit logs for the user.
     */
    public function auditLogs(): HasMany
    {
        return $this->hasMany(AuditLog::class);
    }

    /**
     * Check if user is a candidate.
     */
    public function isCandidat(): bool
    {
        return $this->role === 'candidat';
    }

    /**
     * Check if user is a recruiter.
     */
    public function isRecruteur(): bool
    {
        return $this->role === 'recruteur';
    }

    /**
     * Check if user is an administrator.
     */
    public function isAdministrateur(): bool
    {
        return $this->role === 'administrateur';
    }

    /**
     * Get administrator methods only if user is admin.
     * This provides delegation to admin-specific functionality.
     */
    public function asAdministrator()
    {
        if (!$this->isAdministrateur()) {
            throw new \Exception('User is not an administrator');
        }
        return $this;
    }

    /**
     * Magic method to delegate admin-specific calls to trait methods.
     */
    public function __call($method, $parameters)
    {
        // Check if this is an admin-specific method and user is admin
        if ($this->isAdministrateur() && method_exists($this, $method)) {
            return $this->$method(...$parameters);
        }
        
        return parent::__call($method, $parameters);
    }
}
