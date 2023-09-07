<?php

namespace App\Http\Controllers;

use App\Models\Annonce;
use App\Models\Institution;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AnnonceController extends Controller
{

    public function index()
    {
        $annonces = Institution::find(HelperFuncs::getInstitutionId())->annonces;
        return Inertia::render('Eglise/AnnoncesView', compact('annonces'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'contenu' => 'required',
        ]);
        $dataToInsert=$request->all();
        $dataToInsert['institution_id']=HelperFuncs::getInstitutionId();
        Annonce::create($dataToInsert);
    }

    public function update($id, Request $request)
    {
        Annonce::findOrFail($id)->update($request->all());
    }

    public function destroy($id)
    {
        $annonce = Annonce::findOrFail($id);
        $annonce->delete();
    }
}
