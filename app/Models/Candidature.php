<?php

namespace App\Models;

use App\Models\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Candidature extends Model
{
    use HasFactory, Auditable;

    protected $fillable = [
        'candidat_id',
        'offre_id',
        'cv_path',
        'lettre_motivation',
        'disponibilite',
        'pretention_salariale',
        'statut',
        'etape_recrutement',
        'score_matching',
        'notes_rh',
        'date_soumission',
    ];

    public function candidat(): BelongsTo
    {
        return $this->belongsTo(Candidat::class);
    }

    public function offre(): BelongsTo
    {
        return $this->belongsTo(Offre::class);
    }

    public function entretiens(): HasMany
    {
        return $this->hasMany(Entretien::class);
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }

    protected function casts(): array
    {
        return [
            'pretention_salariale' => 'decimal:2',
            'score_matching' => 'integer',
            'date_soumission' => 'datetime',
        ];
    }
}
