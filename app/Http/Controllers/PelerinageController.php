<?php

namespace App\Http\Controllers;

use App\Models\Institution;
use App\Models\ParoissienPelerinage;
use App\Models\Pelerinage;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PelerinageController extends Controller
{
    public function index()
    {
        $institution_id = HelperFuncs::getInstitutionId();
        $pelerinage = Institution::find($institution_id)->pelerinage;
        $inscriptions = ParoissienPelerinage::where('institution_id', $institution_id)->get();
    return Inertia::render('Eglise/PelerinageView', compact('pelerinage', 'inscriptions'));
    }
    public function show($id)
    {
        $pelerinage = Pelerinage::find($id);
        return Inertia::render('Eglise/PelerinageView.vue', compact('pelerinage'));
    }
    public function store(Request $request)
    {

        $request->validate([
            'edition' => 'required',
            'theme' => 'required',
            'dateLimCar' => 'required',
            'dateLimMarche' => 'required',
            'fraisCar' => 'required',
            'fraisMarche' => 'required',
            'couverture' => 'required',
            'description' => 'required',
        ]);
        $dataToInsert = $request->all();
        $dataToInsert['institution_id'] = HelperFuncs::getInstitutionId();
        Pelerinage::create($dataToInsert);
    }
    public function update($id, Request  $request)
    {
        $request->validate([
            'edition' => 'required',
            'theme' => 'required',
            'dateLimCar' => 'required',
            'dateLimMarche' => 'required',
            'fraisCar' => 'required',
            'fraisMarche' => 'required',
            'couverture' => 'required',
            'description' => 'required',
        ]);
        Pelerinage::find($id)->update($request->all());
    }
}
