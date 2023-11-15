<?php

namespace App\Http\Controllers;

use App\Jobs\NewAdhesionJob;
use App\Jobs\SendWelcomeEmailJob;
use App\Mail\NewAdhesionMail;
use App\Mail\WelcomeUserMail;
use App\Models\Institution;
use App\Models\Pelerinage;
use App\Models\PermanenceConfession;
use App\Models\PermanenceMesse;
use App\Models\PermanencePretres;
use App\Models\PermanenceSecretariat;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class InstitutionController extends Controller
{
    public function index()
    {
        $institutions = Institution::where('verified', 1)->get();
        return Inertia::render('SuperAdmin/InstitutionsView', compact('institutions'));
    }

    public function store(Request $request)
    {
        $r = $request->validate([
            'emaildemandeur' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'denomination' => ['required', 'string', 'max:255', 'unique:institutions,denomination'],
        ], [
            'emaildemandeur.unique' => 'Cet email d\'institution est déjà utilisé.',
            'denomination.unique' => 'Cette institution est déjà inscrite.',
        ]);
        Institution::create($request->all());
        foreach (User::where('role', 'superadmin')->get() as $user) {
            Mail::to($user->email)->send(new NewAdhesionMail($request->prenomdemandeur,$request->nomdemandeur, $request->telephonemobiledemandeur, $request->emaildemandeur, $request->denomination, $request->statut));
        }
        session()->flash('flash.banner', 'Votre demande d\'adhésion a été transmise. Vous recevez vos accès par email une fois la demande traitée.');
        return redirect()->route('home');
    }
    public function historique()
    {

        $demandes = Auth::user()->institution->demandemesses;
        $collectes = Auth::user()->institution->collectes;
        $dons = Auth::user()->institution->dons;
        $pelerinages = Auth::user()->institution->pelerinage;

        $data = [];

        foreach ($demandes as $demande) {
            $dataToInsert['type'] = 'Demande de messe(' . $demande->type . ')';
            $dataToInsert['date'] = $demande->created_at;
            $dataToInsert['montant'] = $demande->montant;
            array_push($data,  $dataToInsert);
        };
        $demandes = $data;
        $data = [];

        foreach ($dons as $don) {
            $dataToInsert['type'] = $don->type;
            $dataToInsert['date'] = $don->created_at;
            $dataToInsert['montant'] = $don->montant;
            array_push($data, $dataToInsert);
        };
        $dons = $data;
        $data = [];

        foreach ($collectes as $collecte) {
            if($collecte){

                foreach ($collecte?->participations as $participation) {
                    $dataToInsert['type'] = $collecte->titre ;
                    $dataToInsert['date'] = $participation->created_at->jsonSerialize();
                    $dataToInsert['montant'] = $participation->montant;
                    array_push($data, $dataToInsert);
                };
            }
        };
        $collectes = $data;
        $data = [];
        if($pelerinages){

            foreach ($pelerinages->participations as $participation) {
                $dataToInsert['type'] = $pelerinages->edition;
                $dataToInsert['date'] = $participation->created_at->jsonSerialize();
                $dataToInsert['montant'] = $participation->montant;
                array_push($data, $dataToInsert);
            };
        }
        $pelerinages = $data;
        return Inertia::render('Eglise/HistoriqueView', compact('collectes', 'demandes', 'pelerinages', 'dons'));
    }
}
