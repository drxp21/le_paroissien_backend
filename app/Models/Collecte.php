<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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
        if ($this->couverture) {
            return request()->getSchemeAndHttpHost() . '/storage/' . $this->couverture;
        }
        return $this->institution->couverture_path;
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
    /**
     * Get the user that owns the Collecte
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function institution(): BelongsTo
    {
        return $this->belongsTo(Institution::class);
    }
}
