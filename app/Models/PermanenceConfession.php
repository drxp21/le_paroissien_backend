<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermanenceConfession extends Model
{
    use HasFactory;
    protected $fillable=[
        'heureDebut',
        'jour_id',
        'institution_id'
    ];
}
