<?php

namespace App\Http\Controllers;

use App\Models\Annonce;
use App\Models\Institution;
use Helper;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AnnonceController extends Controller
{

    public function index()
    {
        $annonces = Institution::find(Helper::getInstitutionId())->annonces;
        return Inertia::render('Eglise/', compact('annonces'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'contenu' => 'required',
        ]);
        Annonce::create($request->all());
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
