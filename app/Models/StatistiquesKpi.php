<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StatistiquesKpi extends Model
{
    use HasFactory;

    protected $fillable = [
        'administrateur_id',
        'nb_offres_actives',
        'nb_candidatures_total',
        'nb_entretiens_planifies',
        'nb_recruteurs_en_attente',
        'taux_conversion',
        'periode_date',
    ];

    /**
     * Get the administrator that owns the statistics.
     */
    public function administrateur(): BelongsTo
    {
        return $this->belongsTo(Administrateur::class);
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'nb_offres_actives' => 'integer',
            'nb_candidatures_total' => 'integer',
            'nb_entretiens_planifies' => 'integer',
            'nb_recruteurs_en_attente' => 'integer',
            'taux_conversion' => 'decimal:2',
        ];
    }

    /**
     * Calculate KPI statistics.
     */
    public function calculerKPI(): array
    {
        return [
            'nb_offres_actives' => $this->nb_offres_actives,
            'nb_candidatures_total' => $this->nb_candidatures_total,
            'nb_entretiens_planifies' => $this->nb_entretiens_planifies,
            'nb_recruteurs_en_attente' => $this->nb_recruteurs_en_attente,
            'taux_conversion' => $this->taux_conversion,
        ];
    }

    /**
     * Generate KPI report.
     */
    public function genererRapport(): string
    {
        $kpi = $this->calculerKPI();
        
        return "Rapport KPI - Offres actives: {$kpi['nb_offres_actives']}, " .
               "Candidatures totales: {$kpi['nb_candidatures_total']}, " .
               "Entretiens planifiés: {$kpi['nb_entretiens_planifies']}, " .
               "Recruteurs en attente: {$kpi['nb_recruteurs_en_attente']}, " .
               "Taux de conversion: {$kpi['taux_conversion']}%";
    }
}
