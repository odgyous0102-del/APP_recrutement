<?php

namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\VerificationRecruteur;
use App\Models\StatistiquesKpi;
use App\Models\AuditLog;

trait AdminUserTrait
{
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
     * Get the audit logs created by the administrateur.
     */
    public function auditLogsCreated(): HasMany
    {
        return $this->hasMany(AuditLog::class, 'user_id');
    }

    /**
     * Check if administrator can manage users.
     */
    public function canManageUsers(): bool
    {
        if (!$this->relationLoaded('administrateur')) {
            $this->load('administrateur');
        }
        return $this->administrateur->can_manage_users ?? false;
    }

    /**
     * Check if administrator can manage offers.
     */
    public function canManageOffers(): bool
    {
        if (!$this->relationLoaded('administrateur')) {
            $this->load('administrateur');
        }
        return $this->administrateur->can_manage_offres ?? false;
    }

    /**
     * Check if administrator has specific permission.
     */
    public function hasPermission(string $permission): bool
    {
        if (!$this->relationLoaded('administrateur')) {
            $this->load('administrateur');
        }
        $permissions = $this->administrateur->permissions ?? [];
        $permissionsArray = is_array($permissions) ? $permissions : json_decode($permissions, true) ?? [];
        return in_array($permission, $permissionsArray) || 
               in_array('all', $permissionsArray) ||
               $this->administrateur->niveau_acces === 'full';
    }

    /**
     * Get administrator's department.
     */
    public function getDepartment(): ?string
    {
        if (!$this->relationLoaded('administrateur')) {
            $this->load('administrateur');
        }
        return $this->administrateur->departement ?? null;
    }

    /**
     * Get administrator's access level.
     */
    public function getAccessLevel(): ?string
    {
        if (!$this->relationLoaded('administrateur')) {
            $this->load('administrateur');
        }
        return $this->administrateur->niveau_acces ?? null;
    }

    /**
     * Update last login timestamp.
     */
    public function updateLastLogin(): void
    {
        if ($this->administrateur) {
            $this->administrateur->update(['last_login_at' => now()]);
        }
    }

    /**
     * Get administrator's permissions as array.
     */
    public function getPermissions(): array
    {
        if (!$this->relationLoaded('administrateur')) {
            $this->load('administrateur');
        }
        $permissions = $this->administrateur->permissions ?? [];
        return is_array($permissions) ? $permissions : json_decode($permissions, true) ?? [];
    }

    /**
     * Check if two-factor authentication is enabled.
     */
    public function hasTwoFactorEnabled(): bool
    {
        if (!$this->relationLoaded('administrateur')) {
            $this->load('administrateur');
        }
        return $this->administrateur->two_factor_enabled ?? false;
    }

    /**
     * Get full administrator profile data.
     */
    public function getAdminProfile(): array
    {
        if (!$this->relationLoaded('administrateur')) {
            $this->load('administrateur');
        }
        return [
            'user' => [
                'id' => $this->id,
                'name' => $this->name,
                'email' => $this->email,
                'is_active' => $this->is_active,
                'created_at' => $this->created_at,
            ],
            'admin' => [
                'permissions' => $this->getPermissions(),
                'department' => $this->getDepartment(),
                'access_level' => $this->getAccessLevel(),
                'can_manage_users' => $this->canManageUsers(),
                'can_manage_offers' => $this->canManageOffers(),
                'two_factor_enabled' => $this->hasTwoFactorEnabled(),
                'last_login_at' => $this->administrateur->last_login_at,
            ]
        ];
    }
}
