<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class HelperFuncs
{
    public static function getInstitutionId()
    {
        return Auth::user()->institution ? Auth::user()->institution->id : Auth::user()->agent->institution->id;
    }
    
    public static function randomPassword($length = 10)
    {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_-=+;:,.?";
        $password = substr(str_shuffle($chars), 0, $length);
        return $password;
    }
}
