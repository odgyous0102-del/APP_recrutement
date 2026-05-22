<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use App\Models\Traits\Auditable;

class Experience extends Model
{
    use HasFactory, Auditable;

    protected $fillable = [
        'candidat_id',
        'poste',
        'entreprise',
        'localisation',
        'date_debut',
        'date_fin',
        'en_cours',
        'description',
    ];

    /**
     * Get the candidat that owns the experience.
     */
    public function candidat(): BelongsTo
    {
        return $this->belongsTo(Candidat::class);
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'date_debut' => 'date',
            'date_fin' => 'date',
            'en_cours' => 'boolean',
        ];
    }
}
