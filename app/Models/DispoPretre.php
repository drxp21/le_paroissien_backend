<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DispoPretre extends Model
{
    use HasFactory;
    protected $fillable= [
        'nomPretre',
        'jour',
        'heureDebut',
        'heureFin',
        'institution_id',
    ];
}
