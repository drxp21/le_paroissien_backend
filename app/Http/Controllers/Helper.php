<?php

use Illuminate\Support\Facades\Auth;

class Helper {
    public static function getInstitutionId(){
        return Auth::user()->institution ? Auth::user()->institution->id : Auth::user()->agent->institution->id;
    }
}
