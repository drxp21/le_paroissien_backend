<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Collecte extends Model
{
    use HasFactory;
    protected $fillable=[
        'titre',
        'description',
        'minimum',
        'date_debut',
        'date_cloture',
        'objectif',
        'institution_id'
    ];

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
