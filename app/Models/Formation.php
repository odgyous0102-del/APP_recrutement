<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Formation extends Model
{
    use HasFactory;

    protected $fillable = [
        'candidat_id',
        'diplome',
        'etablissement',
        'domaine',
        'niveau',
        'date_debut',
        'date_fin',
        'mention',
    ];

    /**
     * Get the candidat that owns the formation.
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
            'date_debut' => 'integer',
            'date_fin' => 'integer',
        ];
    }
}
