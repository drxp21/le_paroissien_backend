<?php

namespace App\Http\Controllers;

use App\Models\Institution;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class InstitutionController extends Controller
{
    public function store(Request $request){
        $password=HelperFuncs::randomPassword();
        $user=User::create([
            'name' => $request->denomination,
            'email' => $request->emailinstitution,
            // 'password' => Hash::make($password),
            'password' => Hash::make('$password'),
        ]);
        $dataToInsert = $request->all();
        $dataToInsert['user_id']=$user->id;
        Institution::create($dataToInsert);
        return redirect()->route('login', ['email'=>$user->email,'password'=>$user->password]);
    }
}
