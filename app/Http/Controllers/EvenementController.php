<?php

namespace App\Http\Controllers;

use App\Models\Evenement;
use App\Models\Institution;
use Illuminate\Http\Request;
use Inertia\Inertia;

class EvenementController extends Controller
{
    public function index()
    {
        $evenements = Institution::find(HelperFuncs::getInstitutionId())->evenements;
        return Inertia::render('Eglise/EvenementsView', compact('evenements'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'titre' => 'required',
            'date' => 'required',
            'description' => 'required',
            'frais' => 'required',
        ]);
        $dataToInsert = $request->all();
        $dataToInsert['institution_id'] = HelperFuncs::getInstitutionId();
        Evenement::create($dataToInsert);
    }

    public function update($id, Request $request)
    {
        Evenement::findOrFail($id)->update($request->all());
    }

    public function destroy($id)
    {
        $evenement = Evenement::findOrFail($id);
        $evenement->delete();
    }
}
