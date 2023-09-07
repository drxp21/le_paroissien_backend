<?php

use App\Models\Annonce;
use App\Models\DemandeMesse;
use App\Models\DispoPretre;
use App\Models\Institution;
use App\Models\Collecte;
use App\Models\Don;
use App\Models\ParoissienCollecte;
use App\Models\Paroissien;
use App\Models\ParoissienPelerinage;
use App\Models\Pelerinage;
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

Route::get('/{institution_id}/annonces', function ($institution_id) {

    return Institution::findOrFail($institution_id)->annonces;
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
Route::get('/{institution_id}/collectes', function ($institution_id) {
    return Institution::findOrFail($institution_id)->collectes;
});

Route::get('/collectes/{id}', function ($id) {
    $collecte = Collecte::find($id)->participations->sum('montant');

    return response()->json(
        [
            'collecte' => Collecte::find($id),
            'reunis' => $collecte,
        ]
    );
});

Route::post('/collectes', function (Request $request) {
    ParoissienCollecte::create([
        'montant' => $request->montant,
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


Route::get('/{paroissien_id}/data/{institution_id}', function ($paroissien_id, $institution_id) {
    $evenements = Institution::findOrFail($institution_id)->evenements->count();
    $annonces = Institution::findOrFail($institution_id)->annonces->count();
    $demandes = Paroissien::find($paroissien_id)->demandes->where('date', '>=', Carbon::today())->count();
    return response()->json(compact('evenements', 'annonces', 'demandes'));
});

Route::get('/eglise/{institution_id}', function ($institution_id) {
    return Institution::where('id', $institution_id)->with('user')->get();
});

Route::get('/eglises', function () {
    return Institution::whereIn('statut', array('cathedrale', 'paroisse', 'quasiparoisse', 'chapelle'))->with('user')->get();
});

Route::get('/eglises-pelerinage', function () {
    return response()->json(Institution::whereIn('id', Pelerinage::pluck('institution_id'))->with('user')->get(), 200);
});

Route::post('/inscription-pelerinage', function (Request $request) {
    $ps = ParoissienPelerinage::where('institution_id', $request->institution_id)->where('numeroBeneficiare', $request->numeroBeneficiare)->first();
    if ($ps != null) {
        return response(['error' => 'Ce numéro est déja inscrit à cette paroisse.'], 404);
    } else {
        ParoissienPelerinage::create($request->all());
        return response(['success' => true], 201);
    }
});

Route::get('/institutions', function () {
    return Institution::with('user')->get();
});

Route::get('/paroissienexists/{numero}', function ($numero) {
    return response(['present' => Paroissien::where('numero', $numero)->exists()]);
});

Route::get('/{institution_id}/pelerinage', function ($institution_id) {
    return Institution::findOrFail($institution_id)->pelerinage;
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
    return $paroissien;
});

Route::post('/loginp', function (Request $request) {
    $ps = Paroissien::where('numero', $request->numero)->first();
    if ($ps != null && Hash::check($request->password, $ps->password)) {
        return response(['user' => $ps], 200);
    } else {
        return response(['error' => 'User not found'], 404);
    }
});
