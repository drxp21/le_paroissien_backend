<?php

namespace App\Http\Controllers;

use App\Jobs\SendRejectionEmailJob;
use App\Jobs\SendWelcomeEmailJob;
use App\Models\Institution;
use App\Models\PermanenceConfession;
use App\Models\PermanenceMesse;
use App\Models\PermanencePretres;
use App\Models\PermanenceSecretariat;
use App\Models\User;
use Dotenv\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class SuperAdminController extends Controller
{
    public function dashboard()
    {
        return Inertia::render('SuperAdmin/Dashboard');
    }

    public function valider(Request $request)
    {

        $password = HelperFuncs::randomPassword();
        $instit = Institution::find($request->id);


        $user = User::create([

            'name' => $instit->denomination,
            'email' => $instit->emailinstitution,
            'password' => Hash::make($password),
        ]);


        $instit->update([
            'verified' => 1,
            'user_id' => $user->id

        ]);

        for ($i = 1; $i < 8; $i++) {
            PermanenceMesse::create([
                'jour_id' => $i,
                'heureDebut' => '00:00',
                'institution_id' => $instit->id
            ]);
        }
        for ($i = 1; $i < 8; $i++) {
            PermanencePretres::create([
                'jour_id' => $i,
                'heureDebut' => '00:00',
                'institution_id' => $instit->id

            ]);
        }
        for ($i = 1; $i < 8; $i++) {
            PermanenceSecretariat::create([
                'jour_id' => $i,
                'heureDebut' => '00:00',
                'heureFin' => '00:01',
                'institution_id' => $instit->id

            ]);
        }
        for ($i = 1; $i < 8; $i++) {
            PermanenceConfession::create([
                'jour_id' => $i,
                'heureDebut' => '00:00',
                'institution_id' => $instit->id

            ]);
        }
        SendWelcomeEmailJob::dispatch($request->emailinstitution, $password);
        session()->flash('flash.banner', 'Email de validation envoyé avec succès');
    }

    public function rejeter(Request $request){
        SendRejectionEmailJob::dispatch($request->emaildemandeur, $request->raison,$request->denomination);
        Institution::destroy($request->id);
        session()->flash('flash.banner', 'Email de rejet envoyé avec succès');

    }
    public function admins()
    {
        $admins = User::where('role', 'superadmin')->get();
        return Inertia::render('SuperAdmin/UsersView', compact('admins'));
    }
    public function create_admin(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role' => 'superadmin',
            'password' => Hash::make($request->password),
        ]);
    }
    public function delete_admin($id)
    {
        User::find($id)->delete();
    }
}
