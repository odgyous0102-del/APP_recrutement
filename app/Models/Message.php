<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'expediteur_id',
        'destinataire_id',
        'candidature_id',
        'contenu',
        'type',
        'fichier_path',
        'lu_at',
    ];

    /**
     * Get the expediteur user.
     */
    public function expediteur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'expediteur_id');
    }

    /**
     * Get the destinataire user.
     */
    public function destinataire(): BelongsTo
    {
        return $this->belongsTo(User::class, 'destinataire_id');
    }

    /**
     * Get the candidature.
     */
    public function candidature(): BelongsTo
    {
        return $this->belongsTo(Candidature::class);
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'lu_at' => 'datetime',
        ];
    }
}
