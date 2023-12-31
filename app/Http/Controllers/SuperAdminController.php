<?php

namespace App\Http\Controllers;

use App\Jobs\SendRejectionEmailJob;
use App\Jobs\SendWelcomeEmailJob;
use App\Mail\RejectionEmail;
use App\Mail\WelcomeUserMail;
use App\Models\Institution;
use App\Models\PermanenceConfession;
use App\Models\PermanenceMesse;
use App\Models\PermanencePretres;
use App\Models\PermanenceSecretariat;
use App\Models\User;
use Dotenv\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;

class SuperAdminController extends Controller
{
    public function dashboard()
    {
        return Inertia::render('SuperAdmin/Dashboard');
    }

    public function valider(Request $request)
    {

        $password = 'leparoissien';
        $instit = Institution::find($request->id);


        $user = User::create([

            'name' => $instit->denomination,
            'email' => $instit->emaildemandeur,
            'password' => Hash::make($password),
        ]);


        $instit->update([
            'verified' => 1,
            'user_id' => $user->id

        ]);

        for ($i = 1; $i < 7; $i++) {
            PermanenceMesse::create([
                'jour_id' => $i,
                'heureDebut' => '06:30',
                'institution_id' => $instit->id
            ]);
        }
        PermanenceMesse::create([
            'jour_id' => '7',
            'heureDebut' => '07:30',
            'institution_id' => $instit->id
        ]);
        PermanenceMesse::create([
            'jour_id' => '7',
            'heureDebut' => '10:30',
            'institution_id' => $instit->id
        ]);
        PermanenceMesse::create([
            'jour_id' => '7',
            'heureDebut' => '18:30',
            'institution_id' => $instit->id
        ]);

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
        Mail::to($request->emaildemandeur)->send(new WelcomeUserMail($request->emaildemandeur, $password));
        session()->flash('flash.banner', 'Email de validation envoyé avec succès');
    }

    public function rejeter(Request $request)
    {
        Mail::to($request->emaildemandeur)->send(new RejectionEmail($request->emaildemandeur, $request->raison));
        Institution::destroy($request->id);
        session()->flash('flash.banner', 'Email de rejet envoyé avec succès');
    }
    public function admins()
    {
        $admins = User::where('role', 'superadmin')->get();
        return Inertia::render('SuperAdmin/UsersView', compact('admins'));
    }

    /**
     * Create  a new SuperAdmin user
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
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
