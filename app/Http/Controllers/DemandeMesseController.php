<?php

namespace App\Http\Controllers;

use App\Models\DemandeMesse;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DemandeMesseController extends Controller
{
    public function index()
    {
        // $eglise_id = Auth::user()->eglise ? Auth::user()->eglise->id : Auth::user()->agent->eglise->id;
        $demandemesses = DemandeMesse::all();
        return Inertia::render('Eglise/DemandeMessesView', compact('demandemesses') );
    }

    public function store(Request $request)
    {
        $request->validate([
            'contenu' => 'required',
        ]);
        DemandeMesse::create($request->all());
    }

    public function update($id, Request $request)
    {
        DemandeMesse::findOrFail($id)->update($request->all());
    }

    public function destroy($id)
    {
        $demandemesse = DemandeMesse::findOrFail($id);
        $demandemesse->delete();
    }
}
