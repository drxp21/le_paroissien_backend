<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Paroissien extends Model
{
    use HasFactory;
    protected $fillable = [
        'nom',
        'numero',
        'password',
        'genre',
        'adresse',
        'institution_id',
    ];

    /**
     * Get all of the demandes for the Paroissien
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function demandes(): HasMany
    {
        return $this->hasMany(DemandeMesse::class);
    }
}
