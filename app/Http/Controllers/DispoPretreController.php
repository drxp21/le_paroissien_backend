<?php

namespace App\Http\Controllers;

use App\Models\DispoPretre;
use App\Models\Institution;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DispoPretreController extends Controller
{
    public function index()
    {
        $dispopretres = Institution::find(HelperFuncs::getInstitutionId())->dispopretres;
        return Inertia::render('Eglise/DispoPretresView', compact('dispopretres'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nomPretre'=> 'required',
            'jour'=> 'required',
            'heureDebut'=> 'required',
            'heureFin'=> 'required',
            'heureFin' => 'required|gt:heureDebut',
        ]);
        $dataToInsert=$request->all();
        $dataToInsert['institution_id']=HelperFuncs::getInstitutionId();
        DispoPretre::create($dataToInsert);
    }

    public function update($id, Request $request)
    {
        DispoPretre::findOrFail($id)->update($request->all());
    }

    public function destroy($id)
    {
        $dispopretre = DispoPretre::findOrFail($id);
        $dispopretre->delete();
    }
}
