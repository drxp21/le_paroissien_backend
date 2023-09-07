<?php

namespace App\Http\Controllers;

use App\Models\DemandeMesse;
use App\Models\Institution;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DemandeMesseController extends Controller
{
    public function index()
    {

        $demandemesses = Institution::find(HelperFuncs::getInstitutionId())->demandemesses;
        return Inertia::render('Eglise/DemandeMessesView', compact('demandemesses'));
    }

    public function store(Request $request)
    {

        $request->validate([
            'intention' => 'required',
            'date' => 'required|after_or_equal:today',
            'heure' => 'required',
            'type' => 'required',
        ], [
            'date' => 'Choisir un date posterieure à aujourd\'hui'
        ]);
        $dataToInsert = $request->all();
        $request->type == 'Simple' ? $dataToInsert['montant'] = 4000 : null;
        $request->type == 'Neuvaine' ? $dataToInsert['montant'] = 9000 : null;
        $request->type == 'Trentaine' ? $dataToInsert['montant'] = 150000 : null;
        $dataToInsert['institution_id'] = HelperFuncs::getInstitutionId();
        DemandeMesse::create($dataToInsert);
    }

    public function update($id, Request $request)
    {
        DemandeMesse::findOrFail($id)->update($request->all());
    }

    public function destroy($id)
    {
        $demandemesse = DemandeMesse::findOrFail($id);
        $demandemesse->delete();
    }
}
