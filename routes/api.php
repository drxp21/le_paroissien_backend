<?php

use App\Models\Annonce;
use App\Models\DemandeMesse;
use App\Models\DispoPretre;
use App\Models\Institution;
use App\Models\Collecte;
use App\Models\Don;
use App\Models\Intention;
use App\Models\ParoissienCollecte;
use App\Models\Paroissien;
use App\Models\ParoissienIntention;
use App\Models\ParoissienPelerinage;
use App\Models\Pelerinage;
use App\Models\PermanenceConfession;
use App\Models\PermanenceMesse;
use App\Models\PermanencePretres;
use App\Models\PermanenceSecretariat;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/




Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware(['auth:sanctum'])->group(function () {
    // Ajoutez d'autres routes protégées ici
    Route::get('/{institution_id}/annonces', function ($institution_id) {

        return Institution::findOrFail($institution_id)->annonces->where('dateFin', 1);
    });
    Route::get('/{institution_id}/dispopretres', function ($institution_id) {
        return Institution::findOrFail($institution_id)->dispoPretres;
    });

    Route::get('/{institution_id}/jourconfs', function ($institution_id) {
        return Institution::findOrFail($institution_id)->jourConfs;
    });

    Route::get('/{institution_id}/evenements', function ($institution_id) {
        return Institution::findOrFail($institution_id)->evenements;
    });
    Route::get('/{institution_id}/permanences', function ($institution_id) {
        $permanences_messe = PermanenceMesse::where('institution_id', $institution_id)->orderBy('jour_id', 'asc')->get();
        $permanences_confession = PermanenceConfession::where('institution_id', $institution_id)->orderBy('jour_id', 'asc')->get();
        $permanences_pretre = PermanencePretres::where('institution_id', $institution_id)->orderBy('jour_id', 'asc')->get();
        $permanences_secretariat = PermanenceSecretariat::where('institution_id', $institution_id)->orderBy('jour_id', 'asc')->get();

        return response()->json(
            compact('permanences_messe', 'permanences_confession', 'permanences_pretre', 'permanences_secretariat')
        );
    });
    Route::get('/{institution_id}/collectes', function ($institution_id) {
        return Institution::findOrFail($institution_id)->collectes;
    });
    Route::get('/collectes/{id}', function ($id) {
        $collecte = Collecte::find($id);
        $collecte['reunis'] = $collecte->participations->sum('montant');

        return response()->json(
            [
                'collecte' => $collecte

            ]
        );
    });
    Route::post('/collectes', function (Request $request) {
        ParoissienCollecte::create([
            'montant' => $request->montant,
            'operateur' => $request->operateur,
            'auteur' => $request->auteur,
            'collecte_id' => $request->collecte_id,
            'paroissien_id' => $request->paroissien_id,
        ]);
    });
    Route::post('/dons', function (Request $request) {
        Don::create($request->all());
    });
    Route::post('/demandes', function (Request $request) {
        DemandeMesse::create($request->all());
    });
    Route::get('/demandes/{paroissien_id}', function ($paroissien_id) {
        $px = Paroissien::find($paroissien_id);
        return $px->demandes;
    });


    Route::get('/{paroissien_id}/data/{institution_id}', function ($paroissien_id, $institution_id) {
        $evenements = Institution::findOrFail($institution_id)->evenements->count();
        $annonces = Institution::findOrFail($institution_id)->annonces->count();

        $demandes = DemandeMesse::where('paroissien_id', $paroissien_id)->where('institution_id', $institution_id)->where('date', '>=', Carbon::today())->count();
        return response()->json(compact('evenements', 'annonces', 'demandes'));
    });

    Route::get('/eglise/{institution_id}', function ($institution_id) {
        return Institution::where('id', $institution_id)->with('user')->get();
    });


    Route::get('/eglises-pelerinage', function () {
        return response()->json(Institution::whereIn('id', Pelerinage::pluck('institution_id'))->with('user')->get(), 200);
    });

    Route::post('/inscription-pelerinage', function (Request $request) {
        $ps = ParoissienPelerinage::where('institution_id', $request->institution_id)->where('numeroBeneficiare', $request->numeroBeneficiare)->first();
        if ($ps != null) {
            return response(['error' => 'Ce numéro est déja inscrit au pélerinage de cette paroisse.'], 404);
        } else {
            ParoissienPelerinage::create($request->all());
            return response(['success' => true], 201);
        }
    });

    Route::get('/institutions', function () {
        return Institution::where('verified', 1)->with('user')->get();
    });

    Route::get('/intentions/{paroissien_id}', function ($paroissien_id) {
        $intentions = [];
        foreach (Intention::all() as $intention) {
            $intention['hasPrayed'] = ParoissienIntention::where('intention_id', $intention->id)->where('paroissien_id', $paroissien_id)->exists();
            array_push($intentions, $intention);
        }

        return $intentions;
    });
    Route::post('/intentions', function (Request $request) {
        Intention::create($request->all());
        return response(['success' => true], 201);
    });
    Route::put('/intentions/{id}/{paroissien_id}', function ($id, $paroissien_id) {
        $intention = Intention::find($id);
        $intention->count += 1;
        $intention->save();
        ParoissienIntention::create([
            'intention_id' => $id,
            'paroissien_id' => $paroissien_id
        ]);
        return $intention;
    });



    Route::get('/{institution_id}/pelerinage', function ($institution_id) {
        return Institution::findOrFail($institution_id)->pelerinage;
    });


    Route::get('history/{id}', function ($id) {

        $typeMap = [
            'pretres' => "Don aux prêtres",
            'paroisse' => "Soutenir une Paroisse",
            'caritas' => "Caritas",
            'denier' => "Denier de culte",
            'dime' => "Dîme",
            'dieu' => "Offrande à Dieu",
            'autre' => "Autres",
        ];
        $data = [];
        $paroissien = Paroissien::find($id);
        foreach ($paroissien->demandes as $demande) {
            $dataToInsert['type'] = 'Demande de messe(' . ' ' . $demande->type . ' ' . ')';
            $dataToInsert['date'] = $demande->created_at;
            $dataToInsert['montant'] = $demande->montant;
            array_push($data, $dataToInsert);
        };
        foreach ($paroissien->dons as $don) {
            $dataToInsert['type'] = 'Don(' . ' ' . $typeMap[$don->type] . ' ' . ')';
            $dataToInsert['date'] = $don->created_at;
            $dataToInsert['montant'] = $don->montant;
            array_push($data, $dataToInsert);
        };
        foreach ($paroissien->pelerinages as $pelerinage) {
            $dataToInsert['type'] = 'Inscription pélerinage';
            $dataToInsert['date'] = $pelerinage->created_at;
            $dataToInsert['montant'] = $pelerinage->montant;
            array_push($data, $dataToInsert);
        };
        return $data;
    });
});

Route::get('/eglises', function () {
    return Institution::whereIn('statut', array('cathedrale', 'paroisse', 'quasiparoisse', 'chapelle'))->where('verified', 1)->with('user')->get();
});

Route::post('/registerp', function (Request $request) {
    $eglise = Institution::findOrFail($request->eglise);
    $paroissien = new Paroissien();
    $paroissien->id = time();
    $paroissien->nom = $request->input('nom');
    $paroissien->numero = $request->input('numero');
    $paroissien->adresse = $request->input('adresse');
    $paroissien->genre = $request->input('genre');
    $paroissien->password = Hash::make($request->input('password'));
    $paroissien->institution_id = $eglise->id;
    $paroissien->save();
    if ($paroissien != null && Hash::check($request->password, $paroissien->password)) {
        $token = $paroissien->createToken('auth_token')->plainTextToken;
        return response(['token' => $token, 'user' => $paroissien], 201);
    }
});



Route::post('/loginp', function (Request $request) {

    $ps = Paroissien::where('numero', $request->numero)->first();
    if ($ps != null && Hash::check($request->password, $ps->password)) {
        $token = $ps->createToken('auth_token')->plainTextToken;
        return response(['token' => $token, 'user' => $ps], 200);
    }
    return response(['error' => 'Invalid credentials'], 401);
});
Route::post('/logoutp', function (Request $request) {
    $request->user()->currentAccessToken()->delete();
});
Route::get('/paroissienexists/{numero}', function ($numero) {
    return response(['present' => Paroissien::where('numero', $numero)->exists()]);
});
