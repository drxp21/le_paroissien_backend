<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pelerinage extends Model
{
    use HasFactory;
    protected $fillable = [
        'edition',
        'theme',
        'dateLimCar',
        'dateLimMarche',
        'fraisCar',
        'fraisMarche',
        'couverture',
        'description',
        'institution_id'
    ];
}
