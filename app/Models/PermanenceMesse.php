<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PermanenceMesse extends Model
{
    use HasFactory;
    protected $fillable=[
        'heureDebut',
        'jour_id',
        'institution_id'
    ];

}
