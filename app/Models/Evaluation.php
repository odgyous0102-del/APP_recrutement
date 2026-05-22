<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use App\Models\Traits\Auditable;

class Evaluation extends Model
{
    use HasFactory, Auditable;

    protected $fillable = [
        'entretien_id',
        'evaluateur_id',
        'competences_techniques',
        'communication',
        'motivation',
        'culture_fit',
        'note_globale',
        'recommandation',
        'commentaires',
    ];

    /**
     * Get the entretien that owns the evaluation.
     */
    public function entretien(): BelongsTo
    {
        return $this->belongsTo(Entretien::class);
    }

    /**
     * Get the user who performed the evaluation.
     */
    public function evaluateur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'evaluateur_id');
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'competences_techniques' => 'integer',
            'communication' => 'integer',
            'motivation' => 'integer',
            'culture_fit' => 'integer',
            'note_globale' => 'decimal:2',
        ];
    }
}
