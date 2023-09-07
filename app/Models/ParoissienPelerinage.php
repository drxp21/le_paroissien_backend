<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ParoissienPelerinage extends Model
{
    use HasFactory;
    protected $fillable=[
        'montant',
        'operateur',
        'nomBeneficiare',
        'numeroBeneficiare',
        'moyen',
        'pour',
        'institution_id',
        'pelerinage_id',
        'paroissien_id'
    ];

    /**
     * Get the paroissien that owns the InscriptionPelerinage
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function paroissien(): BelongsTo
    {
        return $this->belongsTo(Paroissien::class);
    }
    /**
     * Get the eglise that owns the InscriptionPelerinage
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function institution(): BelongsTo
    {
        return $this->belongsTo(Eglise::class);
    }
}
