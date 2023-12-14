<?php

namespace App\Http\Controllers;
// TODO: create chapelle

use App\Custom\FileHelper;
use App\Http\Controllers\FileHelper as ControllersFileHelper;
use App\Models\Institution;
use App\Models\ParoissienPelerinage;
use App\Models\Pelerinage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class PelerinageController extends Controller
{
    public function index()
    {
        $institution_id = HelperFuncs::getInstitutionId();
        $pelerinage = Institution::find($institution_id)->pelerinage;
        $inscriptions = ParoissienPelerinage::where('institution_id', $institution_id)->get();
        return Inertia::render('Eglise/PelerinageView', compact('pelerinage', 'inscriptions'));
    }
    public function show($id)
    {
        $pelerinage = Pelerinage::find($id);
        return Inertia::render('Eglise/PelerinageView.vue', compact('pelerinage'));
    }
    public function store(Request $request)
    {
        $request->validate(
            [
                'edition' => 'required',
                'theme' => 'required',
                'dateLimCar' => 'required|date|before:dateDebut',
                'dateLimMarche' => 'required|date|before:dateDebut',
                'fraisCar' => 'required|numeric',
                'fraisMarche' => 'required|numeric',
                'couverture' => 'required|string',
                'dateDebut'=>'required|after:today',
                'dateFin'=>'required|after:dateDebut'
            ],[
                'dateDebut.after'=>'La date de début doit être postérieure à aujourd\'hui.',
                'dateFin.after'=>'La date de fin doit être postérieure à la date de début.',
                'dateLimCar.after'=>'La date de limite d\'inscription doit être antérieure à la date de début.',
                'dateLimMarche.after'=>'La date de limite d\'inscription doit être antérieure à la date de début.'

            ]

        );
        $dataToInsert = $request->all();
        if ($request->exists('couverture')) {
            // convertir la photo en base64
            $photoFile = HelperFuncs::getFileFromBase64($request->couverture);
            $extension = $photoFile->guessExtension();
            $filename = $request->edition . HelperFuncs::getInstitutionId() . '.' . $extension;
            $photoFile->storeAs('public/pelerinages', $filename);
            $dataToInsert['couverture'] = $filename;
        }
        $dataToInsert['institution_id'] = HelperFuncs::getInstitutionId();
        Pelerinage::create($dataToInsert);
    }
    public function update($id, Request  $request)
    {
        $pelerinage = Pelerinage::find($id);
        // dd($request->all());
        $request->validate([
            'edition' => 'required',
            'theme' => 'required',
            'dateLimCar' => 'required|date',
            'dateLimMarche' => 'required|date',
            'fraisCar' => 'required|numeric',
            'fraisMarche' => 'required|numeric',
            'description' => 'required',
        ]);
        $dataToInsert = $request->all();
        $dataToInsert['couverture'] = $pelerinage->couverture;
        if (Storage::exists('public/pelerinages/' . $pelerinage->couverture) && $request->couverture != null) {
            Storage::delete('public/pelerinages/' . $pelerinage->couverture);
        }
        if ($request->couverture != null) {
            // convertir la photo en base64
            $photoFile = HelperFuncs::getFileFromBase64($request->couverture);
            $extension = $photoFile->guessExtension();
            $filename = $request->edition . HelperFuncs::getInstitutionId() . '.' . $extension;
            $photoFile->storeAs('public/pelerinages', $filename);
            $dataToInsert['couverture'] = $filename;
        }
        Pelerinage::find($id)->update($dataToInsert);
    }
    public function destroy($id)
    {
        Pelerinage::find($id)->delete();
    }
     /**
     * Register physical inscription for a pelerinage
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    public function pelerinagePhysique(Request $request)
    {
        $request->validate([
            'montant' => 'required|numeric',
            'numeroBeneficiare' => 'required|regex:^\+[0-9]+$^',
            'nomBeneficiare' => 'required',
            'moyen' => 'required',
            'pelerinage_id' => 'required',
        ]);


        $dataToInsert = $request->all();
        $dataToInsert['institution_id'] = HelperFuncs::getInstitutionId();
        $dataToInsert['pour'] = 'soi';


        $ps = ParoissienPelerinage::where('institution_id', $request->institution_id)->where('numeroBeneficiare', $request->numeroBeneficiare)->first();
        if ($ps != null) {
            return Inertia::render('Eglise/PelerinageView', [' message' => 'Ce numéro est déja inscrit à cette paroisse.']);
        } else {
            ParoissienPelerinage::create($dataToInsert);
        }
    }
}
