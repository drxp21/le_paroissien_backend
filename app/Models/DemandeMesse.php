<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DemandeMesse extends Model
{
    use HasFactory;
    protected $fillable=[
        'institution_id',
        'paroissien_id',
        'intention',
        'date',
        'heure',
        'montant',
    ];

}
