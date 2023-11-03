<?php

namespace App\Http\Controllers;

use App\Models\Don;
use App\Models\Institution;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DonController extends Controller
{
    public function index()
    {

        $dons = Institution::find(HelperFuncs::getInstitutionId())->dons;
        return Inertia::render('Eglise/DonsView', compact('dons'));
    }

    public function store(Request $request)
    {

        $request->validate([
            'type' => 'required',
            'montant' => 'required|numeric',
            'intention' => 'required',
        ]);
        $dataToInsert = $request->all();
        $dataToInsert['auteur'] = $request->auteur ?? 'Anonyme';
        $dataToInsert['institution_id'] = HelperFuncs::getInstitutionId();
        Don::create($dataToInsert);
    }

    public function update($id, Request $request)
    {
        Don::findOrFail($id)->update($request->all());
    }

    public function destroy($id)
    {
        $don = Don::findOrFail($id);
        $don->delete();
    }
}
