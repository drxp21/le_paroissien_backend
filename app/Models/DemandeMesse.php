<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DemandeMesse extends Model
{
    use HasFactory;
    protected $fillable = [
        'institution_id',
        'paroissien_id',
        'intention',
        'date',
        'operateur',
        'heure',
        'anonyme',
        'type',
        'montant',
    ];

    protected $appends = ['auteur', 'institution_name'];

    /**
     * Get the paroissien that owns the DemandeMesse
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function paroissien(): BelongsTo
    {
        return $this->belongsTo(Paroissien::class);
    }
    /**
     * Get the institution that owns the DemandeMesse
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function institution(): BelongsTo
    {
        return $this->belongsTo(Institution::class);
    }


    public function getAuteurAttribute()
    {
        if ($this->paroissien != null && !$this->anonyme) {
            return $this->paroissien->nom;
        }

        return 'Anonyme';
    }
    public function getInstitutionNameAttribute()
    {
        return $this->institution->denomination;
    }
}
