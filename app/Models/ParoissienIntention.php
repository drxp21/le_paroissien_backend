<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParoissienIntention extends Model
{
    use HasFactory;
    protected $fillable = [
        'paroissien_id',
        'intention_id'
    ];
}
