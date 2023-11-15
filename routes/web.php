<?php

use App\Http\Controllers\AnnonceController;
use App\Http\Controllers\CollecteController;
use App\Http\Controllers\DemandeMesseController;
use App\Http\Controllers\DispoPretreController;
use App\Http\Controllers\DonController;
use App\Http\Controllers\EvenementController;
use App\Http\Controllers\HelperFuncs;
use App\Http\Controllers\InstitutionController;
use App\Http\Controllers\PelerinageController;
use App\Http\Controllers\PermanenceConfessionController;
use App\Http\Controllers\PermanenceMesseController;
use App\Http\Controllers\PermanencePretresController;
use App\Http\Controllers\PermanenceSecretariatController;
use App\Http\Controllers\SuperAdminController;
use App\Mail\InvitationMail;
use App\Models\Collecte;
use App\Models\Institution;
use App\Models\Pelerinage;
use App\Models\PermanenceConfession;
use App\Models\PermanenceMesse;
use App\Models\PermanencePretres;
use App\Models\PermanenceSecretariat;
use Carbon\Carbon;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    $response = Http::get("https://api.aelf.org/v1/messes/" . Carbon::now()->format('Y-m-d') . "/afrique");

    $verset = array_filter($response->json()["messes"][0]["lectures"], function ($s) {
        return $s["type"] == "evangile";
    });
    $verset = reset($verset);
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
        'edj' => $verset
    ]);
})->name('home');

Route::middleware([
    'auth:sanctum',
    'hasChangedPassword',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        $demandeurs = Institution::where('verified', 0)->get();

        return Auth::user()->role == 'superadmin' ?  Inertia::render('SuperAdmin/Dashboard', compact('demandeurs')) : Inertia::render('Dashboard');
    })->name('dashboard');
    Route::post('inviter',function(Request $request){
        $request->validate([
            'email'=>'required|email|unique:institutions,emaildemandeur',
            'message'=>'required',
        ],[
            'email.unique'=>'Ce responsable a déjà fait une demande d\'adhésion !'
        ]);
        Mail::to($request->email)->send(new InvitationMail($request->message));
        session()->flash('flash.banner', 'Email d\'invitation envoyé avec succès');


    } )->name('inviter');
    Route::resource('/annonces', AnnonceController::class);
    Route::resource('/permanencesmesse', PermanenceMesseController::class);
    Route::resource('/permanencesconfession', PermanenceConfessionController::class);
    Route::resource('/permanencespretres', PermanencePretresController::class);
    Route::resource('/permanencessecretariat', PermanenceSecretariatController::class);
    Route::resource('/pelerinage', PelerinageController::class);
    Route::resource('/demandemesses', DemandeMesseController::class);
    Route::resource('/dispopretres', DispoPretreController::class);
    Route::resource('/evenements', EvenementController::class);
    Route::resource('/dons', DonController::class);
    Route::resource('/collectes', CollecteController::class);
    Route::post('/collectes/dons/physique', [CollecteController::class, 'collectePhysique'])->name('collectePhysique');

    Route::get('/institutions-historique', [InstitutionController::class, 'historique'])->name('historique');
    Route::get('/permanences', function () {
        $institution_id = HelperFuncs::getInstitutionId();
        $permanences_messe = PermanenceMesse::where('institution_id', $institution_id)->orderBy('jour_id', 'asc')->get();
        $permanences_confession = PermanenceConfession::where('institution_id', $institution_id)->orderBy('jour_id', 'asc')->get();
        $permanences_pretre = PermanencePretres::where('institution_id', $institution_id)->orderBy('jour_id', 'asc')->get();
        $permanences_secretariat = PermanenceSecretariat::where('institution_id', $institution_id)->orderBy('jour_id', 'asc')->get();

        return Inertia::render('Eglise/PermanencesView', compact('permanences_messe', 'permanences_confession', 'permanences_pretre', 'permanences_secretariat'));
    })->name('permanences');

    Route::get('/permanences-check', function () {
        session()->flash('flash.banner', 'Permanences mises à jour avec succès');
    })->name('permanences-check');
});

Route::get('/register', function () {

    Auth::logout();
    return Inertia::render('Register');
})->name('register');
Route::resource('/institutions', InstitutionController::class);




Route::delete('permanenceMesseDestroy', function (Request $request) {
    PermanenceMesse::find($request->id)->delete();
})->name('permanenceMesseDestroy');
Route::post('permanenceMesseAdd', function (Request $request) {
    PermanenceMesse::create([
        'jour_id' => $request->jour_id,
        'heureDebut' => '00:00',
        'institution_id' => $request->institution_id
    ]);
})->name('permanenceMesseAdd');


Route::post('/valider-demande', [SuperAdminController::class, 'valider'])->name('valider');
Route::post('/rejeter-demande', [SuperAdminController::class, 'rejeter'])->name('rejeter');

Route::get('/admins', [SuperAdminController::class, 'admins'])->name('admins');
Route::post('/admins', [SuperAdminController::class, 'create_admin'])->name('create_admin');
Route::delete('/admins/{id}', [SuperAdminController::class, 'delete_admin'])->name('delete_admin');
Route::post('/pelerinage/inscription/physique', [PelerinageController::class, 'pelerinagePhysique'])->name('pelerinagePhysique');
