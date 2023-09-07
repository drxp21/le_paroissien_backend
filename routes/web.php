<?php

use App\Http\Controllers\AnnonceController;
use App\Http\Controllers\CollecteController;
use App\Http\Controllers\DemandeMesseController;
use App\Http\Controllers\DispoPretreController;
use App\Http\Controllers\DonController;
use App\Http\Controllers\EvenementController;
use App\Http\Controllers\InstitutionController;
use App\Http\Controllers\PelerinageController;
use App\Models\Collecte;
use App\Models\Pelerinage;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Auth::user()->role == 'superadmin' ?  Inertia::render('SuperAdmin/Dashboard') : Inertia::render('Dashboard');
    })->name('dashboard');
});

Route::get('/register', function(){
    return Inertia::render('Register');
})->name('register');


Route::resource('/annonces',AnnonceController::class);
Route::resource('/pelerinage',PelerinageController  ::class);
Route::resource('/demandemesses',DemandeMesseController::class);
Route::resource('/dispopretres',DispoPretreController::class);
Route::resource('/institutions',InstitutionController::class);
Route::resource('/evenements',EvenementController::class);
Route::resource('/dons',DonController::class);
Route::resource('/collectes', CollecteController::class);
