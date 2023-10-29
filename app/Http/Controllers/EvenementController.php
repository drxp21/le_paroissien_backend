<?php

namespace App\Http\Controllers;

use App\Models\Evenement;
use App\Models\Institution;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
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
            'date' => 'required|date',
            'description' => 'required',
            'frais' => 'required|numeric',
            'couverture' => 'required|string',
        ]);
        $dataToInsert = $request->all();
        if ($request->exists('couverture')) {
            // convertir la photo en base64
            $photoFile = HelperFuncs::getFileFromBase64($request->couverture);
            $extension = $photoFile->guessExtension();
            $filename = 'evenement' . time() . '.' . $extension;
            $photoFile->storeAs('public/evenements', $filename);
            $dataToInsert['couverture'] = $filename;
        }
        $dataToInsert['institution_id'] = HelperFuncs::getInstitutionId();
        Evenement::create($dataToInsert);
    }

    public function update($id, Request $request)
    {
        $evenement = Evenement::find($id);
        $dataToInsert = $request->all();
        if (Storage::exists('public/evenements/' . $evenement->couverture)) {
            Storage::delete('public/evenements/' . $evenement->couverture);
        }
        if ($request->exists('couverture')) {
            // convertir la photo en base64
            $photoFile = HelperFuncs::getFileFromBase64($request->couverture);
            $extension = $photoFile->guessExtension();
            $filename = 'evenement' . time() . '.' . $extension;
            $photoFile->storeAs('public/evenements', $filename);
            $dataToInsert['couverture'] = $filename;
        }
        Evenement::findOrFail($id)->update($dataToInsert);
    }

    public function destroy($id)
    {
        $evenement = Evenement::findOrFail($id);
        $evenement->delete();
    }
}
