<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Sanctum\HasApiTokens;

class Paroissien extends Authenticatable
{
    use HasApiTokens;
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
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
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


    /**
     * Get all of the pelerinages for the Paroissien
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function pelerinages(): HasMany
    {
        return $this->hasMany(ParoissienPelerinage::class);
    }


    public function dons(): HasMany
    {
        return $this->hasMany(Don::class);
    }

    /**
     * Get all of the intentions for the Paroissien
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function intentions(): HasMany
    {
        return $this->hasMany(Intention::class);
    }
}
