<?php

namespace App\Http\Controllers;

use App\Models\DispoPretre;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DispoPretreController extends Controller
{
    public function index()
    {
        // $eglise_id = Auth::user()->eglise ? Auth::user()->eglise->id : Auth::user()->agent->eglise->id;
        $dispopretre = DispoPretre::all();
        return Inertia::render('Eglise/DispoPretresView', compact('demandemesses') );
    }

    public function store(Request $request)
    {
        $request->validate([
            'contenu' => 'required',
        ]);
        DispoPretre::create($request->all());
    }

    public function update($id, Request $request)
    {
        DispoPretre::findOrFail($id)->update($request->all());
    }

    public function destroy($id)
    {
        $dispopretre = DispoPretre::findOrFail($id);
        $dispopretre->delete();
    }
}
