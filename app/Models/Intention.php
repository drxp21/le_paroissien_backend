<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Intention extends Model
{
    use HasFactory;
    protected $fillable = [
        'intention',
        'nom_demandeur',
        'count',
        'paroissien_id',
    ];

    
}
