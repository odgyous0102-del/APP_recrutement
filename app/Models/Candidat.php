<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Candidat extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'titre_professionnel',
        'niveau_experience',
        'disponibilite',
        'competences',
        'langues',
        'cv_path',
        'portfolio_url',
        'pretention_salariale',
        'score_profil',
        'linkedin_url',
        'is_open_to_relocation',
    ];

    /**
     * Get the user that owns the candidat.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the experiences for the candidat.
     */
    public function experiences(): HasMany
    {
        return $this->hasMany(Experience::class);
    }

    /**
     * Get the formations for the candidat.
     */
    public function formations(): HasMany
    {
        return $this->hasMany(Formation::class);
    }

    /**
     * Get the candidatures for the candidat.
     */
    public function candidatures(): HasMany
    {
        return $this->hasMany(Candidature::class);
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'competences' => 'array',
            'langues' => 'array',
            'pretention_salariale' => 'decimal:2',
            'score_profil' => 'integer',
            'is_open_to_relocation' => 'boolean',
        ];
    }
}
