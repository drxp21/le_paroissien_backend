<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Collecte extends Model
{
    use HasFactory;
    protected $fillable = [
        'titre',
        'description',
        'minimum',
        'date_debut',
        'couverture',
        'toutlemonde',
        'date_cloture',
        'objectif',
        'institution_id'
    ];
    protected $appends = ['couverture_path'];


    public function getCouverturePathAttribute()
    {
        return request()->getSchemeAndHttpHost() . '/storage/collectes/' . $this->couverture;
    }

    /**
     * Get all of the participations for the Collecte
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function participations(): HasMany
    {
        return $this->hasMany(ParoissienCollecte::class);
    }
}
