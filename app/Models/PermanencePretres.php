<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermanencePretres extends Model
{
    use HasFactory;
    protected $fillable=[
        'heureDebut',
        'jour_id',
        'institution_id'
    ];
}
