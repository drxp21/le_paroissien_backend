<?php

namespace App\Http\Controllers;

use App\Models\Institution;
use App\Models\PermanenceMesse;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PermanenceMesseController extends Controller
{

    public function index()
    {
        $permanences_messe = Institution::find(HelperFuncs::getInstitutionId())->permanences_messe;
        return Inertia::render('Eglise/DispoPretresView', compact('permanences_messe'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'jour_id' => 'required|unique',
            'heureDebut' => 'required',
        ]);
        $dataToInsert = $request->all();
        $dataToInsert['institution_id'] = HelperFuncs::getInstitutionId();
        PermanenceMesse::create($dataToInsert);
    }

    public function update($id, Request $request)
    {

        $dataToInsert["jour_id"] = $request->jour_id;
        $dataToInsert["heureDebut"] = $request->heureDebut;
        $dataToInsert["institution_id"] = $request->institution_id;
        PermanenceMesse::updateOrCreate(["id" => $request->id],$dataToInsert);
    }

    public function destroy($id)
    {
        $permanence_messe = PermanenceMesse::findOrFail($id);
        $permanence_messe->delete();
    }
}
