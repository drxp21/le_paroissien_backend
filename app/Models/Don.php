<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Don extends Model
{
    use HasFactory;
    protected $fillable = [
        'type',
        'montant',
        'intention',
        'moyen',
        'auteur',
        'operateur',
        'institution_id',
        'paroissien_id',
    ];
}
