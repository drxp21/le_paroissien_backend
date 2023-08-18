<?php

namespace App\Http\Controllers;

use App\Models\Pelerinage;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PelerinageController extends Controller
{
    public function show($id){
        $pelerinage=Pelerinage::find($id);
        return Inertia::render('Eglise/PelerinageView.vue',compact('pelerinage'));
    }
}
