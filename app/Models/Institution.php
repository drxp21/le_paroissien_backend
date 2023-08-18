<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Institution extends Model
{
    use HasFactory;

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
}
