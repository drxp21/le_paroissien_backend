<?php

namespace App\Http\Controllers;

use App\Models\Evenement;
use Illuminate\Http\Request;
use Inertia\Inertia;

class EvenementController extends Controller
{
    public function index()
    {
        // $eglise_id = Auth::user()->eglise ? Auth::user()->eglise->id : Auth::user()->agent->eglise->id;
        $evenements = Evenement::all();
        return Inertia::render('Eglise/EvenementsView', compact('evenements'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'contenu' => 'required',
        ]);
        Evenement::create($request->all());
    }

    public function update($id, Request $request)
    {
        Evenement::findOrFail($id)->update($request->all());
    }

    public function destroy($id)
    {
        $evenement = Evenement::findOrFail($id);
        $evenement->delete();
    }
}
