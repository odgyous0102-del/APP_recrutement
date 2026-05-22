<?php

namespace App\Models;

use App\Models\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Offre extends Model
{
    use HasFactory, Auditable;

    protected $fillable = [
        'recruteur_id',
        'titre',
        'description',
        'competences_req',
        'type_contrat',
        'lieu',
        'teletravail',
        'salaire_min',
        'salaire_max',
        'experience_req',
        'statut',
        'date_limite',
        'nb_postes',
        'vues',
    ];

    public function recruteur(): BelongsTo
    {
        return $this->belongsTo(Recruteur::class);
    }

    public function candidatures(): HasMany
    {
        return $this->hasMany(Candidature::class);
    }

    public function favoris(): HasMany
    {
        return $this->hasMany(OffreFavori::class);
    }

    protected function casts(): array
    {
        return [
            'competences_req' => 'array',
            'salaire_min' => 'decimal:2',
            'salaire_max' => 'decimal:2',
            'date_limite' => 'date',
            'nb_postes' => 'integer',
            'vues' => 'integer',
        ];
    }
}
