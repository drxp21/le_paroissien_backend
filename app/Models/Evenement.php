<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evenement extends Model
{
    use HasFactory;
    protected $fillable = [
        'titre',
        'date',
        'description',
        'frais',
        'institution_id',
        'couverture'
    ];
    protected $appends = ['couverture_path'];

    public function getCouverturePathAttribute()
    {
        return request()->getSchemeAndHttpHost() . '/storage/evenements/' . $this->couverture;
    }
}
