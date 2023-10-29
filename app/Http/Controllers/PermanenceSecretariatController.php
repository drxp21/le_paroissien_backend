<?php

namespace App\Http\Controllers;

use App\Models\Institution;
use App\Models\PermanenceSecretariat;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PermanenceSecretariatController extends Controller
{
    public function index()
    {
        $permanences_secretariat = Institution::find(HelperFuncs::getInstitutionId())->permanences_secretariat;
        return Inertia::render('Eglise/DispoPretresView', compact('permanences_secretariat'));
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
        PermanenceSecretariat::create($dataToInsert);
    }

    public function update($id, Request $request)
    {
        PermanenceSecretariat::findOrFail($id)->update($request->all());
    }

    public function destroy($id)
    {
        $permanence_secretariat = PermanenceSecretariat::findOrFail($id);
        $permanence_secretariat->delete();
    }
}
