<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Institution extends Model
{
    use HasFactory;

    protected $fillable = [
        'verified',
        'statut',
        'denomination',
        'diocese',
        'doyenne',
        'adresse',
        'telephonefixe',
        'telephonemobile',
        'emailinstitution',
        'slogan',
        'nomresp',
        'prenomresp',
        'titreresp',
        'emailresp',
        'emaildemandeur',
        'operateur',
        'numcomptemarchand',
        'titulairecompte',
        'codebanque',
        'codeguichet',
        'numerocompte',
        'rib',
        'user_id'
    ];
    protected $appends = ['couverture_path'];

    public function getCouverturePathAttribute()
    {
        return request()->getSchemeAndHttpHost() . '/storage/'.$this->user?->profile_photo_path;
    }

    /**
     * Get the user that owns the Institution
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all of the annonces for the Institution
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function annonces(): HasMany
    {
        return $this->hasMany(Annonce::class);
    }

    /**
     * Get all of the dipsopretres for the Institution
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function dispopretres(): HasMany
    {
        return $this->hasMany(DispoPretre::class);
    }
    /**
     * Get all of the demandemesses for the Institution
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function demandemesses(): HasMany
    {
        return $this->hasMany(DemandeMesse::class);
    }
    /**
     * Get all of the collectes for the Institution
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function collectes(): HasMany
    {
        return $this->hasMany(Collecte::class);
    }
    /**
     * Get the pelerinage associated with the Institution
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function pelerinage(): HasOne
    {
        return $this->hasOne(Pelerinage::class);
    }
    /**
     * Get all of the paroissien_collectes for the Institution
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function paroissien_collectes(): HasMany
    {
        return $this->hasMany(ParoissienCollecte::class);
    }

    /**
     * Get all of the paroissien_pelerinages for the Institution
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function paroissien_pelerinages(): HasMany
    {
        return $this->hasMany(ParoissienPelerinage::class);
    }
    /**
     * Get all of the evenements for the Institution
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function evenements(): HasMany
    {
        return $this->hasMany(Evenement::class);
    }

    /**
     * Get all of the dons for the Institution
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function dons(): HasMany
    {
        return $this->hasMany(Don::class);
    }

    /**
     * Get all of the permanences_messe for the Institution
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function permanences_messe(): HasMany
    {
        return $this->hasMany(PermanenceMesse::class);
    }

    /**
     * Get all of the permanences_pretre for the Institution
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function permanences_pretre(): HasMany
    {
        return $this->hasMany(PermanencePretres::class);
    }


    /**
     * Get all of the permanences_confession for the Institution
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function permanences_confession(): HasMany
    {
        return $this->hasMany(PermanenceConfession::class);
    }

    /**
     * Get all of the permanences_secretariat for the Institution
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function permanences_secretariat(): HasMany
    {
        return $this->hasMany(PermanenceSecretariat::class);
    }
        /**
     * Get all of the permanence_messes for the PermanenceMesse
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
   
}
