<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Administrateur extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'permissions',
        'departement',
        'niveau_acces',
        'last_login_at',
        'can_manage_users',
        'can_manage_offres',
        'two_factor_enabled',
    ];

    /**
     * Get the user that owns the administrateur.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the verification requests managed by the administrateur.
     */
    public function verificationRecruteurs(): HasMany
    {
        return $this->hasMany(VerificationRecruteur::class, 'admin_id');
    }

    /**
     * Get the statistics managed by the administrateur.
     */
    public function statistiquesKpis(): HasMany
    {
        return $this->hasMany(StatistiquesKpi::class, 'administrateur_id');
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'permissions' => 'array',
            'last_login_at' => 'datetime',
            'can_manage_users' => 'boolean',
            'can_manage_offres' => 'boolean',
            'two_factor_enabled' => 'boolean',
        ];
    }

    /**
     * Get user attribute (delegated to User model).
     */
    public function getNameAttribute()
    {
        return $this->user->name ?? null;
    }

    /**
     * Get email attribute (delegated to User model).
     */
    public function getEmailAttribute()
    {
        return $this->user->email ?? null;
    }

    /**
     * Get is_active attribute (delegated to User model).
     */
    public function getIsActiveAttribute()
    {
        return $this->user->is_active ?? false;
    }

    /**
     * Get created_at attribute (delegated to User model).
     */
    public function getUserCreatedAtAttribute()
    {
        return $this->user->created_at ?? null;
    }

    /**
     * Delegate method calls to the User model for inheritance.
     */
    public function __call($method, $parameters)
    {
        // Check if method exists on User model
        if ($this->user && method_exists($this->user, $method)) {
            return $this->user->$method(...$parameters);
        }

        // Check if method exists on this model
        if (method_exists($this, $method)) {
            return $this->$method(...$parameters);
        }

        // Call parent method
        return parent::__call($method, $parameters);
    }

    /**
     * Get the underlying User model.
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * Check if administrator has specific permission.
     */
    public function hasPermission(string $permission): bool
    {
        $permissions = $this->permissions ?? [];
        return in_array($permission, $permissions) || 
               in_array('all', $permissions) ||
               $this->niveau_acces === 'full';
    }

    /**
     * Update last login timestamp.
     */
    public function updateLastLogin(): void
    {
        $this->update(['last_login_at' => now()]);
    }

    /**
     * Get full administrator profile including user data.
     */
    public function getFullProfile(): array
    {
        return [
            'user' => [
                'id' => $this->user->id,
                'name' => $this->user->name,
                'email' => $this->user->email,
                'is_active' => $this->user->is_active,
                'created_at' => $this->user->created_at,
            ],
            'admin' => [
                'permissions' => $this->permissions,
                'department' => $this->departement,
                'access_level' => $this->niveau_acces,
                'can_manage_users' => $this->can_manage_users,
                'can_manage_offres' => $this->can_manage_offres,
                'two_factor_enabled' => $this->two_factor_enabled,
                'last_login_at' => $this->last_login_at,
            ]
        ];
    }

    /**
     * Static method to create admin with user.
     */
    public static function createWithUser(array $userData, array $adminData): self
    {
        $user = User::create(array_merge($userData, [
            'role' => 'administrateur',
            'is_active' => true,
        ]));

        return self::create(array_merge($adminData, [
            'user_id' => $user->id,
        ]));
    }
}
