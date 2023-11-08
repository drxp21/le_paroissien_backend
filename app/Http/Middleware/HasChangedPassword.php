<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class HasChangedPassword
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        if ($user->role == 'institution' && $user->created_at == $user->updated_at) {
            session()->flash('flash.banner', 'Veuillez d\'abord changer votre mot de passe par défaut.');
            session()->flash('flash.hasChanged', false);
            session()->flash('flash.bannerStyle', 'danger');
            return redirect()->route('profile.show');
        }
        return $next($request);
    }
}
