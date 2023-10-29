<?php

namespace App\Http\Controllers;

use App\Models\Institution;
use App\Models\PermanenceConfession;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PermanenceConfessionController extends Controller
{
    public function index()
    {
        $permanences_confession = Institution::find(HelperFuncs::getInstitutionId())->permanences_confession;
        return Inertia::render('Eglise/DispoPretresView', compact('permanences_confession'));
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
        PermanenceConfession::create($dataToInsert);
    }

    public function update($id, Request $request)
    {
        PermanenceConfession::findOrFail($id)->update($request->all());
    }

    public function destroy($id)
    {
        $permanence_confession = PermanenceConfession::findOrFail($id);
        $permanence_confession->delete();
    }
}
