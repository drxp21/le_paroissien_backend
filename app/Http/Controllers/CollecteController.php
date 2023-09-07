<?php

namespace App\Http\Controllers;

use App\Models\Collecte;
use App\Models\Institution;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CollecteController extends Controller
{
    public function index()
    {
        $collectes = Institution::find(HelperFuncs::getInstitutionId())->collectes;
        return Inertia::render('Eglise/CollectesView', compact('collectes'));
    }

    public function show($id)
    {
        $collecte = Collecte::find($id);
        $participations = $collecte->participations;
        return Inertia::render('Eglise/CollecteDetails', compact('collecte', 'participations'));
    }
    public function store(Request $request)
    {

        $request->validate([
            'titre' => 'required',
            'description' => 'required',
            'minimum' => 'required|numeric',
            'date_debut' => 'required',
            'date_cloture' => 'required',
            'objectif' => 'required|numeric',
            'couverture' => 'image|mimes:jpeg,png,jpg,webp',
        ]);
        $dataToInsert = $request->all();
        $dataToInsert['institution_id'] = HelperFuncs::getInstitutionId();
        Collecte::create($dataToInsert);
    }
}
