<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VerificationRecruteur extends Model
{
    use HasFactory;
    
    protected $table = 'verification_recruteur';

    protected $fillable = [
        'recruteur_id',
        'admin_id',
        'statut',
        'commentaire',
        'date_demande',
        'date_decision',
    ];

    /**
     * Get the recruteur being verified.
     */
    public function recruteur(): BelongsTo
    {
        return $this->belongsTo(Recruteur::class);
    }

    /**
     * Get the administrator who performed verification.
     */
    public function admin(): BelongsTo
    {
        return $this->belongsTo(Administrateur::class, 'admin_id');
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'date_demande' => 'datetime',
            'date_decision' => 'datetime',
        ];
    }
}
