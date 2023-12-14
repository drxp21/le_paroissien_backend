<?php

namespace App\Http\Controllers;

use App\Models\Collecte;
use App\Models\Institution;
use App\Models\ParoissienCollecte;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class CollecteController extends Controller
{
    public function index()
    {
        $collectes = Institution::find(HelperFuncs::getInstitutionId())->collectes;
        foreach ($collectes as $collecte) {
            $collecte['reunis'] = $collecte->participations->sum('montant');
        }
        return Inertia::render('Eglise/CollectesView', compact('collectes'));
    }

    public function show($id)
    {
        $collecte = Collecte::find($id);
        $collecte['participations'] = $collecte->participations;
        $collecte['reunis'] = $collecte->participations->sum('montant');
        return Inertia::render('Eglise/CollecteDetails', compact('collecte'));
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
            'toutlemonde' => 'required',
        ]);

        $dataToInsert = $request->all();
        if ($request->couverture) {
            // convertir la photo en base64
            $photoFile = HelperFuncs::getFileFromBase64($request->couverture);
            $extension = $photoFile->guessExtension();
            $filename = 'collecte' . time() . '.' . $extension;
            $photoFile->storeAs('public', $filename);
            $dataToInsert['couverture'] = $filename;
        } else {
            $dataToInsert['couverture'] = Auth::user()->profile_photo_path;
        }
        $dataToInsert['toutlemonde'] = $request->toutlemonde == 0 ? false : true;
        $dataToInsert['institution_id'] = HelperFuncs::getInstitutionId();
        Collecte::create($dataToInsert);
    }
    public function update($id, Request  $request)
    {
        $collecte = Collecte::find($id);

        $request->validate([
            'titre' => 'required',
            'description' => 'required',
            'minimum' => 'required|numeric',
            'date_debut' => 'required',
            'date_cloture' => 'required',
            'objectif' => 'required|numeric',
            'toutlemonde' => 'required',
        ]);
        $dataToInsert = $request->all();
        $dataToInsert['couverture'] = $collecte->couverture;

        if (Storage::exists('public/' . $collecte->couverture) && $request->couverture != null) {
            Storage::delete('public/' . $collecte->couverture);
        }
        if ($request->couverture != null) {
            // convertir la photo en base64
            $photoFile = HelperFuncs::getFileFromBase64($request->couverture);
            $extension = $photoFile->guessExtension();
            $filename = 'collecte' . time() . '.' . $extension;
            $photoFile->storeAs('public', $filename);
            $dataToInsert['couverture'] = $filename;
        }
        $dataToInsert['toutlemonde'] = $request->toutlemonde == false ? 0 : 1;
        Collecte::find($id)->update($dataToInsert);
    }
    public function destroy($id)
    {
        Collecte::find($id)->delete();
        return redirect(route('collectes.index'));
    }


    /**
     * Register physical donnation for a specific fund raising
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    public function collectePhysique(Request $request)
    {
        $request->validate([
            'montant' => 'required|numeric'
        ]);
        ParoissienCollecte::create([
            'montant' => $request->montant,
            'intention' => $request->intention,
            'auteur' => $request->auteur ?? 'Anonyme',
            'collecte_id' => $request->collecte_id,
            'paroissien_id' => $request->paroissien_id,
        ]);
    }
}
