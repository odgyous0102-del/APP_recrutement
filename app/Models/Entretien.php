<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

use App\Models\Traits\Auditable;

class Entretien extends Model
{
    use HasFactory, Auditable;

    protected $fillable = [
        'candidature_id',
        'planifie_par',
        'date_entretien',
        'duree_minutes',
        'type',
        'lien_visio',
        'statut',
        'resultat',
        'compte_rendu',
    ];

    /**
     * Get the candidature that owns the entretien.
     */
    public function candidature(): BelongsTo
    {
        return $this->belongsTo(Candidature::class);
    }

    /**
     * Get the user who planned the entretien.
     */
    public function planifiePar(): BelongsTo
    {
        return $this->belongsTo(User::class, 'planifie_par');
    }

    /**
     * Get the evaluations for the entretien.
     */
    public function evaluations(): HasMany
    {
        return $this->hasMany(Evaluation::class);
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'date_entretien' => 'datetime',
            'duree_minutes' => 'integer',
        ];
    }
}
