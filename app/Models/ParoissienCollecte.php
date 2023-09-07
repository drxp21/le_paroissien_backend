<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParoissienCollecte extends Model
{
    use HasFactory;
    protected $fillable = [
        'montant',
        'collecte_id',
        'paroissien_id',
    ];
}
