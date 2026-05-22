<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OffreFavori extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'offre_id',
    ];

    /**
     * Get the user for the favorite.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the offre for the favorite.
     */
    public function offre(): BelongsTo
    {
        return $this->belongsTo(Offre::class);
    }
}
