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

    /**
     * Get announcements for a specific institution.
     *
     * @param  int  $institution_id
     * @return \Illuminate\Database\Eloquent\Collection
     */
    Route::get('/{institution_id}/annonces', function ($institution_id) {
        return Institution::findOrFail($institution_id)->annonces;
    });

    /**
     * Get schedule for masses for a specific institution.
     *
     * @param  int  $institution_id
     * @return \Illuminate\Database\Eloquent\Collection
     */
    Route::get('/{institution_id}/heures-messes', function ($institution_id) {
        return PermanenceMesse::where('institution_id', $institution_id)->orderBy('jour_id', 'asc')->get();
    });

    /**
     * Get events for a specific institution.
     *
     * @param  int  $institution_id
     * @return \Illuminate\Database\Eloquent\Collection
     */
    Route::get('/{institution_id}/evenements', function ($institution_id) {
        return Institution::findOrFail($institution_id)->evenements;
    });

    /**
     * Get various types of permanences for a specific institution.
     *
     * @param  int  $institution_id
     * @return \Illuminate\Http\JsonResponse
     */
    Route::get('/{institution_id}/permanences', function ($institution_id) {
        $permanences_messe = PermanenceMesse::where('institution_id', $institution_id)->orderBy('jour_id', 'asc')->get();
        $permanences_confession = PermanenceConfession::where('institution_id', $institution_id)->orderBy('jour_id', 'asc')->get();
        $permanences_pretre = PermanencePretres::where('institution_id', $institution_id)->orderBy('jour_id', 'asc')->get();
        $permanences_secretariat = PermanenceSecretariat::where('institution_id', $institution_id)->orderBy('jour_id', 'asc')->get();

        return response()->json(
            compact('permanences_messe', 'permanences_confession', 'permanences_pretre', 'permanences_secretariat')
        );
    });

    /**
     * Get fund raisings for a specific institution.
     *
     * @param  int  $institution_id
     * @return \Illuminate\Database\Eloquent\Collection
     */
    Route::get('/{institution_id}/collectes', function ($institution_id) {
        return Institution::findOrFail($institution_id)->collectes;
    });

    /**
     * Get details of a specific fund raising including the total amount gathered.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    Route::get('/collectes/{id}', function ($id) {
        $collecte = Collecte::find($id);
        $collecte['reunis'] = $collecte->participations->sum('montant');

        return response()->json(['collecte' => $collecte]);
    });

    /**
     * Create a new fund raising.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    Route::post('/collectes', function (Request $request) {
        ParoissienCollecte::create([
            'montant' => $request->montant,
            'operateur' => $request->operateur,
            'auteur' => $request->auteur,
            'intention' => $request->intention,
            'collecte_id' => $request->collecte_id,
            'paroissien_id' => $request->paroissien_id,
        ]);
    });

    /**
     * Register a new donation.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    Route::post('/dons', function (Request $request) {
        Don::create($request->all());
    });

    /**
     * Create a new mass request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    Route::post('/demandes', function (Request $request) {
        DemandeMesse::create($request->all());
    });

    /**
     * Get mass requests for a specific paroissien.
     *
     * @param  int  $paroissien_id
     * @return \Illuminate\Database\Eloquent\Collection
     */
    Route::get('/demandes/{paroissien_id}', function ($paroissien_id) {
        $px = Paroissien::find($paroissien_id);
        return $px->demandes;
    });

    /**
     * Get data (events, announcements, and mass requests) for a paroissien and institution.
     *
     * @param  int  $paroissien_id
     * @param  int  $institution_id
     * @return \Illuminate\Http\JsonResponse
     */
    Route::get('/{paroissien_id}/data/{institution_id}', function ($paroissien_id, $institution_id) {
        $evenements = Institution::findOrFail($institution_id)->evenements->count();
        $annonces = Institution::findOrFail($institution_id)->annonces->count();

        $demandes = DemandeMesse::where('paroissien_id', $paroissien_id)
            ->where('institution_id', $institution_id)
            ->where('date', '>=', Carbon::today())
            ->count();

        return response()->json(compact('evenements', 'annonces', 'demandes'));
    });

    /**
     * Get details of a specific institution with associated internal user.
     *
     * @param  int  $institution_id
     * @return \Illuminate\Database\Eloquent\Collection
     */
    Route::get('/eglise/{institution_id}', function ($institution_id) {
        return Institution::where('id', $institution_id)->with('user')->get();
    });

    /**
     * Get institutions associated with pelerinages.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    Route::get('/eglises-pelerinage', function () {
        return response()->json(Institution::whereIn('id', Pelerinage::pluck('institution_id'))->with('user')->get(), 200);
    });

    /**
     * Register a paroissien for a pelerinage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    Route::post('/inscription-pelerinage', function (Request $request) {
        $ps = ParoissienPelerinage::where('institution_id', $request->institution_id)
            ->where('numeroBeneficiare', $request->numeroBeneficiare)
            ->first();

        if ($ps != null) {
            return response(['error' => 'Ce numéro est déjà inscrit au pèlerinage de cette paroisse.'], 404);
        } else {
            ParoissienPelerinage::create($request->all());
            return response(['success' => true], 201);
        }
    });

    /**
     * Get verified institutions with associated internal user.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    Route::get('/institutions', function () {
        return Institution::where('verified', 1)->with('user')->get();
    });

    /**
     * Get intentions for a specific paroissien.
     *
     * @param  int  $paroissien_id
     * @return \Illuminate\Database\Eloquent\Collection
     */
    Route::get('/intentions/{paroissien_id}', function ($paroissien_id) {
        $intentions = [];
        foreach (Intention::all() as $intention) {
            $intention['hasPrayed'] = ParoissienIntention::where('intention_id', $intention->id)
                ->where('paroissien_id', $paroissien_id)
                ->exists();
            array_push($intentions, $intention);
        }

        return $intentions;
    });

    /**
     * Create a new intention.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    Route::post('/intentions', function (Request $request) {
        Intention::create($request->all());
        return response(['success' => true], 201);
    });

    /**
     * Increase the count of a specific intention and link it to a paroissien.
     *
     * @param  int  $id
     * @param  int  $paroissien_id
     * @return \App\Models\Intention
     */
    Route::put('/intentions/{id}/{paroissien_id}', function ($id, $paroissien_id) {
        $intention = Intention::find($id);
        $intention->count += 1;
        $intention->save();
        ParoissienIntention::create([
            'intention_id' => $id,
            'paroissien_id' => $paroissien_id,
        ]);
        return $intention;
    });

    /**
     * Get pelerinage details for a specific institution.
     *
     * @param  int  $institution_id
     * @return \Illuminate\Database\Eloquent\Collection
     */
    Route::get('/{institution_id}/pelerinage', function ($institution_id) {
        return Institution::findOrFail($institution_id)->pelerinage;
    });

    /**
     * Get donation history for a specific paroissien.
     *
     * @param  int  $id
     * @return array
     */
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
        }
        foreach ($paroissien->dons as $don) {
            $dataToInsert['type'] = 'Don(' . ' ' . $typeMap[$don->type] . ' ' . ')';
            $dataToInsert['date'] = $don->created_at;
            $dataToInsert['montant'] = $don->montant;
            array_push($data, $dataToInsert);
        }
        foreach ($paroissien->pelerinages as $pelerinage) {
            $dataToInsert['type'] = 'Inscription pélerinage';
            $dataToInsert['date'] = $pelerinage->created_at;
            $dataToInsert['montant'] = $pelerinage->montant;
            array_push($data, $dataToInsert);
        }
        return $data;
    });
});

/**
 * Get a list of verified institutions with specific statuses and associated users.
 *
 * @return \Illuminate\Database\Eloquent\Collection
 */
Route::get('/eglises', function () {
    return Institution::whereIn('statut', ['cathedrale', 'paroisse', 'quasiparoisse', 'chapelle'])
        ->where('verified', 1)
        ->with('user')
        ->get();
});

/**
 * Register a new paroissien and associate it with a specific institution.
 *
 * @param  \Illuminate\Http\Request  $request
 * @return \Illuminate\Http\JsonResponse
 */
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

/**
 * Authenticate a paroissien and generate an authentication token.
 *
 * @param  \Illuminate\Http\Request  $request
 * @return \Illuminate\Http\JsonResponse
 */
Route::post('/loginp', function (Request $request) {
    $ps = Paroissien::where('numero', $request->numero)->first();

    if ($ps != null && Hash::check($request->password, $ps->password)) {
        $token = $ps->createToken('auth_token')->plainTextToken;
        return response(['token' => $token, 'user' => $ps], 200);
    }

    return response(['error' => 'Invalid credentials'], 401);
});

/**
 * Log out a paroissien by revoking their authentication token.
 *
 * @param  \Illuminate\Http\Request  $request
 * @return void
 */
Route::post('/logoutp', function (Request $request) {
    $request->user()->currentAccessToken()->delete();
});

/**
 * Check if a paroissien with a specific number already exists.
 *
 * @param  string  $numero
 * @return \Illuminate\Http\JsonResponse
 */
Route::get('/paroissienexists/{numero}', function ($numero) {
    return response(['present' => Paroissien::where('numero', $numero)->exists()]);
});
