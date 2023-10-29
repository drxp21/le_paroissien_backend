<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pelerinage extends Model
{
    use HasFactory;
    protected $fillable = [
        'edition',
        'theme',
        'dateLimCar',
        'dateLimMarche',
        'fraisCar',
        'dateDebut',
        'dateFin',
        'fraisMarche',
        'couverture',
        'description',
        'institution_id'
    ];

    protected $appends = ['couverture_path'];


    public function getCouverturePathAttribute()
    {
        return request()->getSchemeAndHttpHost() . '/storage/pelerinages/' . $this->couverture;
    }
     /**
     * Get all of the participations for the Collecte
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function participations(): HasMany
    {
        return $this->hasMany(ParoissienPelerinage::class);
    }
}
