<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

use App\Models\Traits\Auditable;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Storage;
class Recruteur extends Model
{
    use HasFactory, Auditable;

    protected $fillable = [
        'user_id',
        'nom_entreprise',
        'secteur_activite',
        'taille_entreprise',
        'site_web',
        'logo_entreprise',
        'description_ent',
        'is_verified',
        'statut_verification',
        'numero_rc',
        'contact_rh_email',
        'quota_offres',
    ];

    /**
     * Get the user that owns the recruteur.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the offers for the recruteur.
     */
    public function offres(): HasMany
    {
        return $this->hasMany(Offre::class);
    }

    /**
     * Get the verification requests for the recruteur.
     */
    public function verificationRecruteurs(): HasMany
    {
        return $this->hasMany(VerificationRecruteur::class);
    }

    /**
     * Get the current verification for the recruteur.
     */
    public function verificationEnCours(): HasOne
    {
        return $this->hasOne(VerificationRecruteur::class)->where('statut', 'en_attente');
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_verified' => 'boolean',
            'quota_offres' => 'integer',
        ];
    }

    /**
     * Obtenir l'URL complète du logo.
     */
    protected function logoUrl(): Attribute
    {
        return Attribute::get(fn() => $this->logo_entreprise
            ? asset('storage/' . $this->logo_entreprise)
            : asset('images/default-company.png'));
    }
}
