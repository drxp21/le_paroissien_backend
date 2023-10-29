<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermanenceMesse extends Model
{
    use HasFactory;
    protected $fillable=[
        'heureDebut',
        'heureFin',
        'jour_id',
        'institution_id'
    ];

}
