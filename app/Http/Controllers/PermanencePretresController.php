<?php

namespace App\Http\Controllers;

use App\Models\Institution;
use App\Models\PermanenceMesse;
use App\Models\PermanencePretres;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PermanencePretresController extends Controller
{
    public function index()
    {
        $permanences_pretre = Institution::find(HelperFuncs::getInstitutionId())->permanences_pretre;
        return Inertia::render('Eglise/DispoPretresView', compact('permanences_pretre'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'jour_id' => 'required|unique',
            'heureDebut' => 'required',
            'heureFin' => 'required',
            'heureFin' => 'required|gt:heureDebut',
        ]);
        $dataToInsert = $request->all();
        $dataToInsert['institution_id'] = HelperFuncs::getInstitutionId();
        PermanencePretres::create($dataToInsert);
    }

    public function update($id, Request $request)
    {
        PermanencePretres::findOrFail($id)->update($request->all());
    }

    public function destroy($id)
    {
        $permanence_pretre = PermanencePretres::findOrFail($id);
        $permanence_pretre->delete();
    }
}
